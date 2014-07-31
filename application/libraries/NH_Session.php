<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NH_Session extends CI_Session {

	private $_md5_encrypt = false;
	private $_redis = null;
	private $_redis_select = false;
	private $_use_db = true;

	public function __construct($params = array())
	{
        log_message('debug', "Session Class Initialized");

        // Set the super object to a local variable for use throughout the class
        $this->CI =& get_instance();

        //tizi load redis
        $this->_redis_load();
        
        // Set all the session preferences, which can either be set
        // manually via the $params array above or via the config file
        foreach (array('sess_encrypt_cookie', 'sess_use_database', 'sess_table_name', 'sess_expiration', 'sess_expire_on_close', 'sess_match_ip', 'sess_match_useragent', 'sess_cookie_name', 'cookie_path', 'cookie_domain', 'cookie_secure', 'sess_time_to_update', 'time_reference', 'cookie_prefix', 'encryption_key', 'sess_autologin_expiration') as $key)
        {
            $this->$key = (isset($params[$key])) ? $params[$key] : $this->CI->config->item($key);
        }

        if ($this->encryption_key == '')
        {
            show_error('In order to use the Session class you are required to set an encryption key in your config file.');
        }

        // Load the string helper so we can use the strip_slashes() function
        $this->CI->load->helper('string');

        // Do we need encryption? If so, load the encryption class
        if ($this->sess_encrypt_cookie == TRUE)
        {
            $this->CI->load->library('encrypt');
        }

        // Are we using a database?  If so, load it
        if ($this->sess_use_database === TRUE AND $this->sess_table_name != '')
        {
            if($this->_use_db) $this->CI->load->database();
        }

        // Set the "now" time.  Can either be GMT or server time, based on the
        // config prefs.  We use this to set the "last activity" time
        $this->now = $this->_get_time();

        // Set the session length. If the session expiration is
        // set to zero we'll set the expiration two years from now.
        if ($this->sess_expiration == 0)
        {
            $this->sess_expiration = (60*60*24*365*2);
        }

        //nahao add
        if ($this->sess_autologin_expiration == 0)
        {
            $this->sess_autologin_expiration = (60*60*24*365*2);
        }
        //end nahao add

        // Set the cookie name
        $this->sess_cookie_name = $this->cookie_prefix.$this->sess_cookie_name;

        // Run the Session routine. If a session doesn't exist we'll
        // create a new one.  If it does, we'll update it.
        if ( ! $this->sess_read())
        {
            $this->sess_create();
        }
        else
        {
            $this->sess_update();
        }

        // Delete 'old' flashdata (from last request)
        $this->_flashdata_sweep();

        // Mark all new flashdata as old (data will be deleted before next request)
        $this->_flashdata_mark();

        // Delete expired sessions if necessary
        $this->_sess_gc();

        log_message('debug', "Session routines successfully run");
    }

	private function _redis_load()
	{
		$_nrd = $this->CI->input->cookie('_nrd');

		if($this->_redis !== false && !$_nrd && extension_loaded('redis'))
		{
			$this->CI->config->load('redis', TRUE, TRUE);
			$redis_config = $this->CI->config->item('redis');
			$config = $redis_config['redis_default'];
			$this->_redis_select=$redis_config['redis_db']['session'];
			//$config['timeout'] = 0.1;

			$redis = new Redis();
			try
			{
				$return = $redis->connect($config['host'], $config['port'], $config['timeout']);
			}
			catch (RedisException $e)
			{
                log_message('error_nahao', '10070:Redis session connection refused. '.$e->getMessage());
				log_message('error', '10070:Redis session connection refused. '.$e->getMessage());
				$return = false;
			}
			if($return)
			{
				try
				{
					$redis->auth($config['password']);
					$redis->select($this->_redis_select);
				}
				catch (RedisException $e)
				{
					log_message('error', '10070:Redis session auth and select refused. '.$e->getMessage());
					$return = false;
				}
			}
			else
			{
				$this->CI->input->set_cookie('_nrd','1',0);
				$redis = false;
			}

			$this->_redis = $redis;
		}
		else if(!$_nrd)
		{
			$this->CI->input->set_cookie('_nrd','1',0);
		}
        //var_dump($this->_redis);die();
	}

	private function _redis_set($key, $value, $ttl)
	{
		if($this->_redis && $this->_redis_select) 
		{
			$this->_redis->select($this->_redis_select);
			return $this->_redis->set($key, $value, $ttl);
		}
		else 
		{
			log_message('error', '100706:session redis set failed');
		}
	}

	private function _redis_get($key)
	{
		if($this->_redis && $this->_redis_select) 
		{
			$this->_redis->select($this->_redis_select);
			return $this->_redis->get($key);
		}
		else 
		{
			log_message('error', '100707:session redis get failed');
		}
	}

	private function _redis_del($key)
	{
		if($this->_redis && $this->_redis_select) 
		{
			$this->_redis->select($this->_redis_select);
			return $this->_redis->del($key);
		}
		else 
		{
			log_message('error', '100708:session redis del failed');
		}
	}

	private function _redis_close()
	{
		if($this->_redis) 
		{
			return $this->_redis->close();
		}
		else 
		{
			log_message('error', '100709:session redis close failed');
		}
	}

	function sess_read()
	{
        log_message('debug_nahao', "enter into sess_read() function");

		// Fetch the cookie
		$session = $this->CI->input->cookie($this->sess_cookie_name);
		
		//nahao check get session_id, for xunlei download
        //log_message('debug_nahao', "In sess_read(), segment: ".print_r($segment, 1));
        $session_get = $this->CI->input->get('session_id');
        //if session is empty, get GET parameter's session_id instead
        if ($session === FALSE && $session_get) $session = $session_get;


		// No cookie?  Goodbye cruel world!...
		if ($session === FALSE)
		{
			log_message('debug', 'A session cookie was not found.');
			return FALSE;
		}
        else
        {
            log_message('debug_nahao', 'sess_read: A session cookie was found.'.print_r($_COOKIE,1));
        }

		//nahao do not match useragent when get session id
		if ($session === $session_get) $this->sess_match_useragent = false;

		// Decrypt the cookie data
		if ($this->sess_encrypt_cookie == TRUE)
		{
			$session = $this->CI->encrypt->decode($session);
		}
		else if($this->_md5_encrypt)
		{
			// encryption was not used, so we need to check the md5 hash
			$hash	 = substr($session, strlen($session)-32); // get last 32 chars
			$session = substr($session, 0, strlen($session)-32);

			// Does the md5 hash match?  This is to prevent manipulation of session data in userspace
			if ($hash !==  md5($session.$this->encryption_key))
			{
				log_message('error', 'The session cookie data did not match what was expected. This could be a possible hacking attempt.');
				$this->sess_destroy();
				return FALSE;
			}
		}

		//tizi
		if ($this->sess_use_database === TRUE)
		{
			$session_id = $session;
			$session = array();

            //redis test
			if($this->_redis)
			{
                log_message('debug_nahao', "In sess_read(), redis is available");
				$userdata = $this->_redis_get($session_id);
				if(!empty($userdata)) $session = json_decode($userdata,true);
			}
			if(empty($session)&&$this->_use_db)
			{
				$this->CI->load->database();
				$this->CI->db->where('session_id',$session_id);
				$query = $this->CI->db->get($this->sess_table_name);

				// No result?  Kill it!
				if ($query->num_rows() == 0)
				{
					$session = array();
				}
				else
				{
					// Is there custom data?  If so, add it to the main session array
					$session = $query->row_array();
					if($this->_redis)
					{
						$this->_redis_set($session['session_id'],json_encode($session),$this->sess_expiration);
					}
				}
			}

			if(empty($session))
			{
				log_message('error','session_destroy_unload_session');
				$this->sess_destroy();
				return FALSE;
			}

			if (isset($session['user_data']) AND $session['user_data'] != '')
			{
				$custom_data = $this->_unserialize($session['user_data']);

				if (is_array($custom_data))
				{
					foreach ($custom_data as $key => $val)
					{
						$session[$key] = $val;
					}
				}
				unset($session['user_data']);
			}
		}
		else
		{
			// Unserialize the session array
			$session = $this->_unserialize($session);
		}

		// Is the session data we unserialized an array with the correct format?
		if ( ! is_array($session) OR ! isset($session['session_id']) OR ! isset($session['ip_address']) OR ! isset($session['user_agent']) OR ! isset($session['last_activity']))
		{
			log_message('error','session_destroy_session_info');
			$this->sess_destroy();
			return FALSE;
		}

		// Is the session current?
		if (($session['last_activity'] + $this->sess_expiration) < $this->now)
		{
			log_message('error','session_destroy_last_activity');
			$this->sess_destroy();
			return FALSE;
		}

		// Does the IP Match?
		if ($this->sess_match_ip == TRUE AND $session['ip_address'] != $this->CI->input->ip_address())
		{
			log_message('error','session_destroy_ip_address');
			$this->sess_destroy();
			return FALSE;
		}

		// Does the User Agent Match?
		if ($this->sess_match_useragent == TRUE AND trim($session['user_agent']) != trim(substr($this->CI->input->user_agent(), 0, 120)))
		{
			log_message('error','session_destroy_ip_address');
			$this->sess_destroy();
			return FALSE;
		}

		// Session is valid!
		$this->userdata = $session;
		unset($session);

        log_message('debug_nahao', "go out of into sess_read() function");
		return TRUE;
	}

	function sess_write()
	{
        log_message('debug_nahao', "enter into sess_write() function");
		// Are we saving custom data to the DB?  If not, all we do is update the cookie
		if ($this->sess_use_database === FALSE)
		{
			$this->_set_cookie();
			return;
		}

		// set the custom userdata, the session data we will set in a second
		$custom_userdata = $this->userdata;
		$cookie_userdata = array();

		// Before continuing, we need to determine if there is any custom data to deal with.
		// Let's determine this by removing the default indexes to see if there's anything left in the array
		// and set the session data while we're at it
		foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
		{
			unset($custom_userdata[$val]);
			$cookie_userdata[$val] = $this->userdata[$val];
		}

		// Did we find any custom data?  If not, we turn the empty array into a string
		// since there's no reason to serialize and store an empty array in the DB
		if (count($custom_userdata) === 0)
		{
			$custom_userdata = '';
		}
		else
		{
			// Serialize the custom data array so we can store it
			$custom_userdata = $this->_serialize($custom_userdata);
		}

        log_message('debug_nahao', 'sess_write, custom_userdata is:'.print_r($this->_unserialize($custom_userdata),1));
		// Run the update query
		if($this->_redis)
		{
			$userdata = $this->_redis_get($this->userdata['session_id']);
			$userdata = json_decode($userdata,true);
			$userdata['last_activity'] = $this->userdata['last_activity'];
			$userdata['user_data'] = $custom_userdata;

            //nahao logic
            if(isset($custom_userdata['remb_me']) && $custom_userdata['remb_me'])
            {
			    $this->_redis_set($this->userdata['session_id'],json_encode($userdata),$this->sess_autologin_expiration);
            }
            else
            {
                $this->_redis_set($this->userdata['session_id'],json_encode($userdata),$this->sess_expiration);
            }
            //end nahao logic
		}
		else if($this->_use_db)
		{
			$this->CI->load->database('',true);
			$this->CI->db->where('session_id', $this->userdata['session_id']);
			$this->CI->db->update($this->sess_table_name, array('last_activity' => $this->userdata['last_activity'], 'user_data' => $custom_userdata));
		}
		// Write the cookie.  Notice that we manually pass the cookie data array to the
		// _set_cookie() function. Normally that function will store $this->userdata, but
		// in this case that array contains custom data, which we do not want in the cookie.
		
		//tizi 更新数据不再更新cookie
        log_message('debug_nahao', "sess_write: cookie_userdata:".print_r($cookie_userdata, 1));
		$this->_set_cookie($cookie_userdata);

        log_message('debug_nahao', "go out of sess_write() function");
	}

	function sess_create()
	{
        log_message('debug_nahao', "enter into sess_create() function");
		$sessid = '';
		while (strlen($sessid) < 32)
		{
			$sessid .= mt_rand(0, mt_getrandmax());
		}

		// To make the session ID even more secure we'll combine it with the user's IP
		$sessid .= $this->CI->input->ip_address();

        $session_id = md5(uniqid($sessid, TRUE));
		$this->userdata = array(
							'session_id'	=> $session_id,
							'ip_address'	=> $this->CI->input->ip_address(),
							'user_agent'	=> substr($this->CI->input->user_agent(), 0, 120),
							'last_activity'	=> $this->now,
							'user_data'		=> '',
							);


		// Save the data to the DB if needed
		if ($this->sess_use_database === TRUE)
		{
			if($this->_redis)
			{
				$this->_redis_set($this->userdata['session_id'],json_encode($this->userdata),$this->sess_expiration);
			}
			else if($this->_use_db) 
			{
				$this->CI->load->database();
				$this->CI->db->query($this->CI->db->insert_string($this->sess_table_name, $this->userdata));
			}
		}

		// Write the cookie
		$this->_set_cookie();

        $this->CI->load->model('model/common/model_session_log', 'msl');
        $session_log = array(
            'session_id' => $session_id,
        );

        $this->CI->msl->save_session_log($session_log);

        log_message('debug_nahao', "go out of sess_create() function");
	}


    function sess_update()
    {
        log_message('debug_nahao', 'session sess_update() is called');
        // We only update the session every five minutes by default
        if (($this->userdata['last_activity'] + $this->sess_time_to_update) >= $this->now)
        {
            return;
        }

        // Save the old session id so we know which record to
        // update in the database if we need it
        $old_sessid = $this->userdata['session_id'];
        $new_sessid = '';
        while (strlen($new_sessid) < 32)
        {
            $new_sessid .= mt_rand(0, mt_getrandmax());
        }

        // To make the session ID even more secure we'll combine it with the user's IP
        $new_sessid .= $this->CI->input->ip_address();

        // Turn it into a hash
        $new_sessid = md5(uniqid($new_sessid, TRUE));

        // Update the session data in the session data array
        $this->userdata['session_id'] = $new_sessid;
        $this->userdata['last_activity'] = $this->now;

        // _set_cookie() will handle this for us if we aren't using database sessions
        // by pushing all userdata to the cookie.
        $cookie_data = NULL;

        // Update the session ID and last_activity field in the DB if needed
        $expire = $this->sess_expiration;
        if ($this->sess_use_database === TRUE)
        {
            // set cookie explicitly to only have our session data
            $cookie_data = array();
            foreach (array('session_id','ip_address','user_agent','last_activity') as $val)
            {
                $cookie_data[$val] = $this->userdata[$val];
            }

            if($this->_redis)
            {
                $userdata = $this->_redis_get($old_sessid);
                $userdata = json_decode($userdata,true);
                $userdata['last_activity'] = $this->now;
                $userdata['session_id'] = $new_sessid;


                if(isset($userdata['remb_me']) && $userdata['remb_me'] )
                {
                    $this->_redis_set($new_sessid,json_encode($userdata),$this->sess_autologin_expiration);
                    $expire = $this->sess_autologin_expiration;
                }
                else
                {
                    $this->_redis_set($new_sessid,json_encode($userdata),$this->sess_expiration);
                }

                //$this->_redis_del($old_sessid);
                $this->_redis_set($old_sessid,json_encode($userdata),120);
            }
            else if($this->_use_db)
            {
                $this->CI->load->database();
                $this->CI->db->query($this->CI->db->update_string($this->sess_table_name, array('last_activity' => $this->now, 'session_id' => $new_sessid), array('session_id' => $old_sessid)));
            }
        }

        // Write the cookie
        $this->_set_cookie($cookie_data);

        log_message('debug_nahao', 'session_update this->userdata is:'.print_r($this->userdata, 1));

        $this->CI->load->model('model/common/model_session_log', 'msl');
        $session_log = array(
            'session_id' => $new_sessid,
            'user_id' => isset($this->userdata['user_id']) ? $this->userdata['user_id'] : '',
            'nickname' => isset($this->userdata['nickname']) ? $this->userdata['nickname'] :'',
            'ip' => ip2long($this->CI->input->ip_address()),
            'expire_time' => $expire + time(),
            'user_type' => isset($this->userdata['user_type']) ? $this->userdata['user_type'] : 0,
            'exit_time' => 0,
        );

        $this->CI->msl->update_session_log($session_log);
        log_message('debug_nahao', "go out of sess_update() function");
    }


	function sess_destroy()
	{
        log_message('debug_nahao', "enter into  sess_destroy() function");
		// Kill the session DB row
		if ($this->sess_use_database === TRUE && isset($this->userdata['session_id']))
		{
			if($this->_redis) 
			{
                log_message('debug_nahao', "del session_id is: ".$this->userdata['session_id']);
				$this->_redis_del($this->userdata['session_id']);
			}
			else if($this->_use_db)
			{
				$this->CI->load->database('',true);
				$this->CI->db->where('session_id', $this->userdata['session_id']);
				$this->CI->db->delete($this->sess_table_name);
			}
		}

		// Kill the cookie
        log_message('debug_nahao', "sess_destory setcookie cookie_name is: $this->sess_cookie_name");
		setcookie(
			$this->sess_cookie_name,
			addslashes(serialize(array())),
			($this->now - 31500000),
			$this->cookie_path,
			$this->cookie_domain,
			0
		);

		// Kill session data
		$this->userdata = array();
        log_message('debug_nahao', "go out of  sess_destroy() function");
	}

	function _set_cookie($cookie_data = NULL)
	{
		if (is_null($cookie_data))
		{
			$cookie_data = $this->userdata;
		}

		//tizi
		if ($this->sess_use_database == TRUE && is_array($cookie_data) && isset($cookie_data['session_id']))
		{
			$cookie_data = $cookie_data['session_id'];
		}
		else
		{
			// Serialize the userdata for the cookie
			$cookie_data = $this->_serialize($cookie_data);
		}

		if ($this->sess_encrypt_cookie == TRUE)
		{
			$cookie_data = $this->CI->encrypt->encode($cookie_data);
		}
		else if ($this->_md5_encrypt)
		{
			// if encryption is not used, we provide an md5 hash to prevent userside tampering
			$cookie_data = $cookie_data.md5($cookie_data.$this->encryption_key);
		}

        log_message('debug_nahao', "_set_cookie userdata is:".print_r($this->userdata, 1));


        $expire = ($this->sess_expire_on_close === TRUE) ? 0 : $this->sess_expiration + time();
        if(isset($this->userdata['remb_me']) && $this->userdata['remb_me'])
        {
            $expire = $this->sess_autologin_expiration + time();
        }

        log_message('debug_nahao', "_set_cookie expire time is $expire");

		// Set the cookie
		setcookie(
			$this->sess_cookie_name,
			$cookie_data,
			$expire,
			$this->cookie_path,
			$this->cookie_domain,
			$this->cookie_secure
		);
	}

	function _sess_gc()
	{
		if ($this->sess_use_database != TRUE)
		{
			return;
		}

		if ($this->_redis)
		{
			return;
		}
		if (!$this->_use_db)
		{
			return;
		}

		srand(time());
		if ((rand() % 100) < $this->gc_probability)
		{
			$expire = $this->now - $this->sess_expiration;

			if($this->_use_db)
			{
				$this->CI->load->database('',true);
				$this->CI->db->where("last_activity < {$expire}");
				$this->CI->db->delete($this->sess_table_name);
			}

			log_message('debug', 'Session garbage collection performed.');
		}
	}

}
// END Session Class

/* End of file MY_Session.php */
/* Location: ./application/libraries/MY_Session.php */