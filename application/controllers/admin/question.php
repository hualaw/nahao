<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 题目管理
 * Class Round
 */
class Question extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
        $this->load->model('business/admin/business_lesson');
        $this->load->model('business/admin/business_question');
        $this->load->model('business/admin/business_class');
        header('Content-Type:text/html;CHARSET=utf-8');
    }

    /**
     * 课节题目列表
     */
    public function lesson_question(){
    	#1. 课节信息
        $lesson_id = $this->uri->segment(3,0);
        $lesson_info = $this->business_lesson->get_lesson_by_id($lesson_id);
        #2. 课节题目列表
        $question_list = $this->business_question->lesson_question(array('lesson_id'=>$lesson_id));
        $_GET['tab'] = "question_add";
		$data = array(
			'lesson_info' => $lesson_info,
			'question_list' => $question_list,
			'total' => count($question_list),
			'lesson_id' => $lesson_id,
			'tab' => in_array($_GET['tab'],array('question_list','question_add','question_edit')) ? $_GET['tab'] : 'question_list' ,
		);
    	$this->smarty->assign('view', 'question_lesson_list');
    	$this->smarty->assign('data', $data);
        $this->smarty->display('admin/layout.html');
    }
	
    /**
     * 课节提交添题
     */
    public function lesson_add(){
    	$param['lesson_id'] = $this->input->post('lesson_id');
    	if(!$param['lesson_id']){
    		echo '<script>alert("缺少必要参数");window.history.go(-1);</script>';
    	}
    	$param['question'] = $this->input->post('question');//'Aaaaaaa'
    	$param['analysis'] = $this->input->post('analysis');//'Aaaaaaa'
    	$param['answer'] = $this->input->post('answer');//'A'
    	$param['A'] = $this->input->post('A');//'Aaaaaaa'
    	$param['B'] = $this->input->post('B');
    	$param['C'] = $this->input->post('C');
    	$param['D'] = $this->input->post('D');
    	$param['E'] = $this->input->post('E');
    	$param['do'] = 'add';
    	$param['add_lesson_question'] = 1;
    	$res = $this->business_question->lesson_question_doWrite($param);
    	
    	if($res){
			echo '<script>alert("添加成功");if(confirm("是否继续添加？")){window.location.href="/question/lesson_question/'.$param['lesson_id'].'/?tag=question_add&tmp='.time().'";}else{window.location.href="/question/lesson_question/'.$param['lesson_id'].'/?tmp='.time().'"};</script>';
		}else{
			echo '<script>alert("添加失败");window.history.go(-1);</script>';
		}
		exit;
    }
    
    /**
     * 修改界面
     */
    public function lesson_edit(){
    	#1. 课节信息
        $lesson_id = $this->uri->segment(3,0);
        $question_id = $this->uri->segment(4,0);
        $lesson_info = $this->business_lesson->get_lesson_by_id($lesson_id);
        #2. 课节题目列表
        $param = array(
        	'lesson_id'=>$lesson_id,
        	'question_id'=>$question_id,
        );
        $question_list = $this->business_question->lesson_question($param);
        if(!isset($question_list[0])){exit('题目不存在或内容不全');}
        $data = array(
        	'lesson_id' => $lesson_id,
        	'question_id' => $question_id,
			'lesson_info' => $lesson_info,
			'question_info' => isset($question_list[0]) ? $question_list[0] : array(),
		);
		$this->smarty->assign('view', 'question_lesson_edit');
    	$this->smarty->assign('data', $data);
        $this->smarty->display('admin/layout.html');
    }
    
    /**
     * 课节提交改题
     */
    public function lesson_doEdit(){
    	$param['lesson_id'] = $this->input->post('lesson_id');
    	$param['question_id'] = $this->input->post('question_id');
    	if(!$param['lesson_id'] || !$param['question_id']){
    		echo '<script>alert("缺少必要参数");window.history.go(-1);</script>';
    	}
    	$param['question'] = $this->input->post('question');//'Aaaaaaa'
    	$param['analysis'] = $this->input->post('analysis');//'Aaaaaaa'
    	$param['answer'] = $this->input->post('answer');//'A'
    	$param['A'] = $this->input->post('A');//'Aaaaaaa'
    	$param['B'] = $this->input->post('B');
    	$param['C'] = $this->input->post('C');
    	$param['D'] = $this->input->post('D');
    	$param['E'] = $this->input->post('E');
    	$param['do'] = 'edit';//这个别忘了
    	$res = $this->business_question->lesson_question_doWrite($param);
    	
    	if($res){
			echo '<script>alert("修改成功");window.location.href="/question/lesson_question/'.$param['lesson_id'].'/?tmp='.time().'";</script>';
		}else{
			echo '<script>alert("修改失败");window.history.go(-1);</script>';
		}
    }
    
    /**
     * 课节删题
     */
    public function lesson_delete(){
    	#1. 信息
    	$lesson_id = $this->uri->segment(3,0);
    	$question_id = $this->uri->segment(4,0);
    	#2. 执行删除,只删关系
    	$param = array(
    		'lesson_id' => $lesson_id,
    		'question_id' => $question_id,
    		'do' => 'delete',
    		'delete_lesson_question' => 1,
    	);
    	$res = $this->business_question->lesson_question_delete($param);
    	if($res){
    		echo '<script>alert("删除成功");window.location.href="/question/lesson_question/'.$lesson_id.'/?tmp='.time().'"</script>';
    	}else{
    		echo '<script>alert("删除失败");history.go(-1);</script>';
    	}
    }
    
    /**
     * 课题列表
     */
    public function class_question(){
    	#1. 课信息
        $class_id = $this->uri->segment(3,0);
        $class_info = $this->business_class->get_class_by_id($class_id);
        #2. 课题目列表
        $question_list = $this->business_question->class_question(array('class_id'=>$class_id));
        $_GET['tab'] = "question_add";
		$data = array(
			'class_info' => $class_info,
			'question_list' => $question_list,
			'total' => count($question_list),
			'class_id' => $class_id,
			'tab' => in_array($_GET['tab'],array('question_list','question_add','question_edit')) ? $_GET['tab'] : 'question_list' ,
		);
    	$this->smarty->assign('view', 'question_class_list');
    	$this->smarty->assign('data', $data);
        $this->smarty->display('admin/layout.html');
    }
    
    /**
     * 课添题
     */
    public function class_add(){
    	$param['class_id'] = $this->input->post('class_id');
    	if(empty($param['class_id'])){
    		echo '<script>alert("缺少必要参数");window.history.go(-1);</script>';
    	}
    	$param['question'] = $this->input->post('question');//'Aaaaaaa'
    	$param['analysis'] = $this->input->post('analysis');//'Aaaaaaa'
    	$param['answer'] = $this->input->post('answer');//'A'
    	$param['A'] = $this->input->post('A');//'Aaaaaaa'
    	$param['B'] = $this->input->post('B');
    	$param['C'] = $this->input->post('C');
    	$param['D'] = $this->input->post('D');
    	$param['E'] = $this->input->post('E');
    	$param['do'] = 'add';
    	$param['add_class_question'] = 1;
    	$res = $this->business_question->class_question_doWrite($param);
    	
    	if($res){
			echo '<script>alert("添加成功");if(confirm("是否继续添加？")){window.location.href="/question/class_question/'.$param['class_id'].'/?tag=question_add&tmp='.time().'";}else{window.location.href="/question/class_question/'.$param['class_id'].'/?tmp='.time().'"};</script>';
		}else{
			echo '<script>alert("添加失败");window.history.go(-1);</script>';
		}
		exit;
    }
    
    /**
     * 修改界面
     */
    public function class_edit(){
    	#1. 课信息
        $class_id = $this->uri->segment(3,0);
        $question_id = $this->uri->segment(4,0);
        $class_info = $this->business_class->get_class_by_id($class_id);
        #2. 课题目列表
        $param = array(
        	'class_id'=>$class_id,
        	'question_id'=>$question_id,
        );
        $question_list = $this->business_question->class_question($param);
        if(!isset($question_list[0])){exit('题目不存在或内容不全');}
        $data = array(
        	'class_id' => $class_id,
        	'question_id' => $question_id,
			'class_info' => $class_info,
			'question_info' => isset($question_list[0]) ? $question_list[0] : array(),
		);
		$this->smarty->assign('view', 'question_class_edit');
    	$this->smarty->assign('data', $data);
        $this->smarty->display('admin/layout.html');
    }
    
    /**
     * 课提交改题
     */
    public function class_doEdit(){
    	$param['class_id'] = $this->input->post('class_id');
    	$param['question_id'] = $this->input->post('question_id');
    	if(!$param['class_id'] || !$param['question_id']){
    		echo '<script>alert("缺少必要参数");window.history.go(-1);</script>';
    	}
    	$param['question'] = $this->input->post('question');//'Aaaaaaa'
    	$param['analysis'] = $this->input->post('analysis');//'Aaaaaaa'
    	$param['answer'] = $this->input->post('answer');//'A'
    	$param['A'] = $this->input->post('A');//'Aaaaaaa'
    	$param['B'] = $this->input->post('B');
    	$param['C'] = $this->input->post('C');
    	$param['D'] = $this->input->post('D');
    	$param['E'] = $this->input->post('E');
    	$param['do'] = 'edit';//这个别忘了
    	$res = $this->business_question->class_question_doWrite($param);
    	
    	if($res){
			echo '<script>alert("修改成功");window.location.href="/question/class_question/'.$param['class_id'].'/?tmp='.time().'";</script>';
		}else{
			echo '<script>alert("修改失败");window.history.go(-1);</script>';
		}
    }
    
    /**
     * 课删题
     */
    public function class_question_delete(){
    	#1. 信息
    	$class_id = $this->uri->segment(3,0);
    	$question_id = $this->uri->segment(4,0);
    	#2. 执行删除,只删关系
    	$param = array(
    		'class_id' => $class_id,
    		'question_id' => $question_id,
    		'do' => 'delete',
    		'delete_class_question' => 1,
    	);
    	$res = $this->business_question->class_question_delete($param);
    	if($res){
    		echo '<script>alert("删除成功");window.location.href="/question/class_question/'.$class_id.'/?tmp='.time().'"</script>';
    	}else{
    		echo '<script>alert("删除失败");history.go(-1);</script>';
    	}
    }
}