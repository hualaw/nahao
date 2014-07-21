<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tools相关逻辑
 * Class Business_Tools
 */
class Business_Tools extends NH_Model
{
    function __construct(){
        parent::__construct();
        $this->load->model('model/admin/model_tools');
    }
    
    /**
     * 应用1：临时添加学生下单买课，【含下订单】
     */
    public function student_order_APP($param)
    {
    	#1. 查询此轮信息，含已购买数，是否达到上限，是就终止，不是就继续
    	
    	#2. 【如果第1步成功】：查询学生与轮是否有状态为2（已支付）的订单记录
    	
    	#3. 删除student_order中学生与轮的订单记录
    	
    	#4. 删除student_class中学生与轮下面所有课的记录
    	
    	#5. 【如果第1步成功】：如果存在购买记录，轮购买人数-1 
    	
    	#6. 生成学生与订单记录
    	
    	#7. 【如果第6步成功】：创建订单日志，修改轮购买人数，修改用户类型,生成学生与课的记录。
    		#7.1 生成学生下单日志记录2条：1.创建订单成功  2.  0元课程支付成功/支付成功
    		
    		#7.2 修改用户类型
    			#7.2.1 如果轮的售价为0 此处不应该更改用户的付费类型为付费用户
    			
    			#7.2.2 如果轮的售价大于0 此处应该更改用户的付费类型为付费用户
    		#7.3 轮购买人数+1
	    
    		#7.4 查询当前轮中所有课以及授课状态,
    		
    		#7.5 根据【7.4课以及授课状态】生成学生与课的记录，没上过的课状态插入为【初始化0】，其他状态插入为【缺席1】
    	
    	// $this->model_tools->student_order_clearer($param);
    }
    
    /**
     * 应用2：临时添加学生试听课，【不含下订单】
     */
    public function student_Audition_APP($param)
    {
    	#1. 查询此轮信息，含预约人数，是否达到上限，是就终止，不是就继续
    	
    	#2. 删除student_试听_class中学生与轮下面所有课的记录
    	
    	#3. 【如果第6步成功】：修改轮购买人数，修改用户类型,生成学生与课的记录。
	    	
    		#3.2 查询当前轮中所有课以及授课状态,
    		
    		#3.3 根据【7.4课以及授课状态】生成学生与课的记录，没上过的课状态插入为【初始化0】，其他状态插入为【缺席1】
    }
    
    /**
     * 应用3:搜索学生,返回id
     */
   	public function search_student($param)
   	{
   		
   	}
   	
   	/**
   	 * 应用4：搜索购买轮，返回id
   	 */
   	public function search_round($param)
   	{
   		#图片地址
        $array_return['class_img'] = empty($img) ? static_url(HOME_IMG_DEFAULT) : get_course_img_by_size($img,'large');
   	}
}