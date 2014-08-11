<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 
 */
class Add_All_Permission extends NH_Controller
{
    /**
     * 加载数据库连接
     */
    public function __construct()
    {
        parent::__construct();
        $this->amount = 2000;
        $this->load->database();
    }
	/**
	 * @desc 遍历controller下面的admin所有文件，把需要增加的权限填到数据库
	 * @author shuaiqi_zhang
	 */
    public function index()
    {
    	foreach (glob(APPPATH . 'controllers/admin/*.php') as  $filename ) {
    		require ($filename);
    		$controller = basename($filename, '.php');
    		if (class_exists($controller)) {
    			$obj = new ReflectionClass($controller);
    		}else{
    			continue;
    		}
    		$arr_method = $obj->getMethods();
    		foreach($arr_method as $k=>$v){
    			$action =  $v->getName();
    			$is_exist = T(TABLE_PERMISSION)->count('controller ="'.$controller.'" and action="'.$action.'"');
//     			echo $this->db->last_query().'<br />';
    			if ($is_exist) continue;
    			$doc = $v->getDocComment();
    			if (!empty($doc)){
    				$doc_array = explode("@",$doc);
    				$doc_p = '';
    				foreach ($doc_array as $key=>$value){
    					if (strpos($value, 'permission') === 0){
    						$doc_p = $value;
    					}
    				}
    				if (!empty($doc_p)){
    					$doc = explode('*',$doc_p);
    					$doc_result = substr($doc[0], strlen('permission'));
    					$data['controller'] = $controller;
    					$data['action'] = $action;
    					$data['name'] = $doc_result;
    					$data['status'] = 1;
    					T(TABLE_PERMISSION)->add($data);
    					log_message('debug_nahao','添加权限：'.print_r($data,true));
    				}
    			}
    			
    			
    		}
    	}
    }
    
    				
   
}