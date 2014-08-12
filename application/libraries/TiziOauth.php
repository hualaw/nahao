<?php

session_start();
class TiziOauth {

	/*授权地址*/
	Const AUTORIZE_URL = "http://192.168.11.12:8787/oauth/show";
	/*获取accessToken Api*/
	Const ACCESSTOKEN_URL = "http://192.168.11.12:8787/oauth/access_token";
	/*获取用户资料  Api*/
	Const USER_INFO_URL = "http://192.168.11.12:8787/oauth/user/get_user_info";
	/*获取openid Api*/
	Const GET_OPENID_URL = "http://192.168.11.12:8787/oauth/me";

	public $client_id;

	public $client_secret;

	public $access_token;

	public $openid;

	public $redirect_uri;

	public $scope;

	/**
	 * construct
	 *
	 * @access public 
	 * @param string $access_token  access_token 
	 * @param string $openid        openid
	 * @param array  $config
	 *
	 */
	public function __construct($config = array(), $access_token = "", $openid = "") {
		
		isset($config['appid']) && $this->client_id = $config['appid'];
		isset($config['appkey']) && $this->client_secret = $config['appkey'];
		isset($config['callback']) && $this->redirect_uri = $config['callback'];
		isset($config['scope']) && $this->scope = $config['scope'];

		$this->access_token = $access_token;
		$this->openid = $openid;
			
	}
	
	/**
	 * 登录入口
	 *
	 * @access public 
	 *
	 */
	public function tizi_login() {
		
		$state = md5(uniqid(rand(), TRUE));
		$params = array();
		$params['client_id'] = $this->client_id;
		$params['redirect_uri'] = $this->redirect_uri;
		$params['response_type'] = 'code';
		$params['scope'] = $this->scope;
		$params['state'] = $state;
		$_SESSION['tizi_oauth_state'] = $state;

		$login_url = $this->combineURL(self::AUTORIZE_URL, $params);

		header("Location:$login_url");

	}
	
	/**
	 * 获取accessToken 
	 *
	 * @access public 
	 *
	 * @return array  $response
	 */
	public function get_accesstoken() {
		
		//$state = $this->_CI->session->userdata('tizi_oauth_state');
		$state = $_SESSION['tizi_oauth_state'];

		if (empty($state) || $state != trim($_GET['state'])) {
		
			exit('state error');
		}

		$params = array();
		$params['grant_type'] = 'authorization_code';
		$params['client_id'] = $this->client_id;
		$params['client_secret'] = $this->client_secret;
		$params['redirect_uri'] = $this->redirect_uri;
		$params['code'] = $_GET['code'];

		$token_url = self::ACCESSTOKEN_URL;
		
		$response = json_decode($this->post($token_url, $params), true);
		if(!isset($response['error']) && !empty($response['access_token']))
		   	$this->access_token = $response['access_token'];
		return $response;
			
	}

	/**
	 * 获取 openId (用户唯一标识)
	 *
	 * @access public 
	 *
	 * @return string  $openId , On Error return false
	 *
	 */
	public function get_openid(){
		
		$params = array(
			"access_token" => $this->access_token
		);

		$response = $this->get(self::GET_OPENID_URL, $params);

		$result = json_decode($response, true);
		if (isset($result['openid'])) {
		
			return $this->openid = $result['openid'];

		}
		return false;
		
	}

	/**
	 * 获取用户资料
	 *
	 * @access public 
	 *
	 * @return array  $result 	
	 *
	 */

	public function get_user_info(){
		
		$params = array();
		$params['openid'] = $this->openid;
		$params['access_token'] = $this->access_token;
		$result = json_decode($this->get(self::USER_INFO_URL, $params), true);
		return $result;

	}

    private function combineURL($baseURL,$keysArr){

        $combined = $baseURL."?";
        $valueArr = array();

        foreach($keysArr as $key => $val){
            $valueArr[] = "$key=$val";
        }

        $keyStr = implode("&",$valueArr);
        $combined .= ($keyStr);
        
        return $combined;
    }
	
    /**
     * get
     */
    private function get($url, $keysArr){
        $combined = $this->combineURL($url, $keysArr);
        return $this->get_contents($combined);
    }

    /**
     * post
     */
    private function post($url, $keysArr, $flag = 0){

        $ch = curl_init();
        if(! $flag) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_POST, TRUE); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr); 
        curl_setopt($ch, CURLOPT_URL, $url);
        $ret = curl_exec($ch);

        curl_close($ch);
        return $ret;
    }

    private function get_contents($url){

        if (ini_get("allow_url_fopen") == "1") {
            $response = file_get_contents($url);
        }else{
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_URL, $url);
            $response =  curl_exec($ch);
            curl_close($ch);
        }

        if(empty($response)){
			exit('error');
            $this->error->showError("50001");
        }

        return $response;
    }

}
