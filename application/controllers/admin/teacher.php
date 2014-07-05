<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 老师管理
 * Class Teacher
 * @author yanrui@tizi.com
 */
class Teacher extends NH_Admin_Controller {

    private $arr_response = array(
        'status' => 'error',
        'msg' => '操作失败',
    );

    function __construct(){
        parent::__construct();
    }

    /**
     * user index
     * @author yanrui@tizi.com
     */
    public function index(){
        $int_start = $this->uri->segment(3) ? $this->uri->segment(3) : 0;
        $int_user_id = $this->input->get('user_id') ? intval($this->input->get('user_id')) : 0 ;
        $str_term = $this->input->get('term') ? trim($this->input->get('term')) : 0 ;
        $int_province = $this->input->get('province') ? trim($this->input->get('province')) : 0 ;
        $int_subject = $this->input->get('subject') ? trim($this->input->get('subject')) : 0 ;
        $int_gender = $this->input->get('gender') ? trim($this->input->get('gender')) : 0 ;
        $int_title = $this->input->get('title') ? trim($this->input->get('title')) : 0 ;
        $int_account = $this->input->get('account') ? trim($this->input->get('account')) : 0 ;
        // var_dump($int_title);
        $int_add_time1 = $this->input->get('add_time1') ? trim($this->input->get('add_time1')) : '' ;
        $int_add_time2 = $this->input->get('add_time2') ? trim($this->input->get('add_time2')) : '' ;
        $arr_where = array();
        if($int_user_id > 0){
            $arr_where['user_id'] = $int_user_id;
        }
        if($str_term == 1){
            $arr_where['realname'] = $this->input->get('reason');
        }
        if($str_term == 2){
            $arr_where['nickname'] = $this->input->get('reason');
        }
        if($str_term == 3){
            $arr_where['email'] = $this->input->get('reason');
        }
        if($str_term == 4){
            $arr_where['phone_mask'] = $this->input->get('reason');
        }
        if($str_term == 5){
            $arr_where['id'] = $this->input->get('reason');
        }
        if($int_province > 0){
            $arr_where['province'] = $int_province;
        }
        if($int_subject > 0){
            $arr_where['subject'] = $int_subject;
        }
        if($int_gender > 0){
            $arr_where['gender'] = $int_gender;
        }
        if($int_title > 0){
            $arr_where['title'] = $int_title;
        }
        if($int_account > 0){
            $arr_where['account'] = $int_account;
        }
        if($int_add_time1 != "" && $int_add_time2 != ""){
            $arr_where['add_time1'] = $int_add_time1;
            $arr_where['add_time2'] = $int_add_time2;
        }
        $int_count = $this->teacher->get_teacher_count($arr_where);
        $arr_list = $this->teacher->get_teacher_list($arr_where, $int_start,PER_PAGE_NO);
//        var_dump($arr_list);die;
        $this->load->model('business/admin/business_lecture');
        $province=$this->business_lecture->all_province();
        $this->load->model('business/common/business_subject','subject');
        $subject=$this->subject->get_subjects();
        $total_count=$int_count;
        $day_count=$this->teacher->day_count();
        $this->load->library('pagination');
        $config = config_item('page_admin');
        $config['suffix'] = '/?' . $this->input->server('QUERY_STRING');
        $config['base_url'] = '/' . $this->current['controller'] . '/' . $this->current['action'];
        $config['total_rows'] = $int_count;
        $config['per_page'] = PER_PAGE_NO;
        $this->pagination->initialize($config);
        parse_str($this->input->server('QUERY_STRING'),$arr_query_param);

        $config_gender = config_item('gender');
        $config_title = config_item('teacher_title');
        $config_stage = config_item('stage');
        $config_account = config_item('account');
        $this->smarty->assign('total_count',$total_count);
        $this->smarty->assign('day_count',$day_count);
        $this->smarty->assign('province',$province);
        $this->smarty->assign('subject',$subject);
        $this->smarty->assign('config_gender',$config_gender);
        $this->smarty->assign('config_title',$config_title);
        $this->smarty->assign('config_stage',$config_stage);
        $this->smarty->assign('config_account',$config_account);
        $this->smarty->assign('page',$this->pagination->create_links());
        $this->smarty->assign('count',$int_count);
        $this->smarty->assign('list',$arr_list);
        $this->smarty->assign('arr_query_param', $arr_query_param);
        $this->smarty->assign('view', 'teacher_list');
        $this->smarty->display('admin/layout.html');
    }

     /**
     * create teacher
     * @author shangshikai@tizi.com
     */
    public function create(){
        $this->load->model('business/common/business_subject','subject');
        $subject=$this->subject->get_subjects();
        $config_title=config_item('teacher_title');
        $config_bank=config_item('bank');
        $this->load->model('business/admin/business_lecture');
        $province=$this->business_lecture->all_province();

        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys ( NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY );
        $obj_putPolicy = new Qiniu_RS_PutPolicy ( NH_QINIU_BUCKET );
        $str_upToken = $obj_putPolicy->Token ( null );
        $str_salt = random_string('alnum', 6);

        $str_work_img_file_name = 'teacher_'.time().'_work_auth_i'.$str_salt.'.png';
        $str_auth_img_file_name = 'teacher_'.time().'_teacher_auth_i'.$str_salt.'.png';
        $str_title_img_file_name = 'teacher_'.date('YmdHis',time()).'_title_auth_i'.$str_salt.'.png';
        $this->smarty->assign('upload_token',$str_upToken);
        $this->smarty->assign('upload_work_img_key', $str_work_img_file_name);
        $this->smarty->assign('upload_auth_img_key', $str_auth_img_file_name);
        $this->smarty->assign('upload_title_img_key', $str_title_img_file_name);

        $this->smarty->assign('config_bank',$config_bank);
        $this->smarty->assign('config_title',$config_title);
        $this->smarty->assign('province',$province);
        $this->smarty->assign('subject',$subject);
        $this->smarty->assign('view','insert_teacher');
        $this->smarty->display('admin/layout.html');
    }
    /**
     * 验证添加教师表单
     * @aurhor shangshikai@tizi.com
    **/
    public function check_techer_post()
    {
        $post=$this->input->post(NULL,TRUE);
        //var_dump($post);die;
        if($this->teacher->check_post($post))
        {
            redirect('teacher');
        }
       // var_dump($post);
    }
    /**
     * 根据省id查找市
     * @author shangshikai@tizi.com
    */
    public function city()
    {
        $province=$this->input->post('province',TRUE);
        echo json_encode($this->teacher->city1($province));
    }
    /**
     * 根据市城市查找学校
     * @author shangshikai@tizi.com
     */
    public function school()
    {
        $school_id=$this->input->post(NULL,TRUE);
        echo json_encode($this->teacher->school_business_pid($school_id));
    }
    /**
     * 根据市id查找区
     * @author shangshikai@tizi.com
     */
    public function area()
    {
        $city=$this->input->post('city',TRUE);
        echo json_encode($this->teacher->area1($city));
       // echo $this->teacher->area1($city);
       // echo $city;
    }
    /**
     * teacher账户禁用
     * @author shangshikai@tizi.com
     */
    public function close_account(){
        $arr=$this->input->post('arr',TRUE);
       // echo $this->teacher->close_ban($arr);
        echo $this->teacher->close_ban($arr);
    }

    /**
     * teacher账户启用
     * @author shangshikai@tizi.com
    */
    public function open_account(){
        $arr=$this->input->post('arr',TRUE);
       // echo $this->teacher->close_ban($arr);
        echo $this->teacher->open_ban($arr);
    }
    /**
     * 昵称验证
     * @author shangshikai@tizi.com
    */
    public function nickname()
    {
        $nickname=$this->input->post('nickname',TRUE);
        $user_id=$this->input->post('user_id',TRUE);
        $nick=$this->teacher->check_nick_name($nickname,$user_id);
        echo $nick;
        //echo $nick;
    }
    /**
     * 真实姓名长度
     * @author shangshikai@tizi.com
     */
    public function check_realname_length()
    {
        $realname=$this->input->post('realname',TRUE);
        echo $this->teacher->check_real_name($realname);
    }
    /**
     * 电话是否存在
     * @author shangshikai@tizi.com
     */
    public function check_phone()
    {
        $phone=$this->input->post('phone');
        echo $this->teacher->check_mobile_phone($phone);
    }
    /**
     * 邮箱是否存在
     * @author shangshikai@tizi.com
     */
    public function check_email()
    {
        $email=$this->input->post('email');
        echo $this->teacher->check_email_tec($email);
    }

     /**
     * 修改教师
     * @author shangshikai@tizi.com
     */
    public function modify()
    {
        $user_id=$this->input->get('user_id',TRUE);
        $teacher_details=$this->teacher->teacher_momdify($user_id);
        //var_dump($teacher_details);die;
        $city=$this->teacher->city1($teacher_details['user_info_data']['province']);
        $area=$this->teacher->area1($teacher_details['user_info_data']['city']);
        if($teacher_details['user_info_data']['area']!=0)
        {
            $school_id=array();
            $school_id['school_id']=$teacher_details['user_info_data']['area'];
            $school=$this->teacher->school_business_pid($school_id);
        }
        elseif($teacher_details['user_info_data']['city']!=0)
        {
            $school_id=array();
            $school_id['school_id']=$teacher_details['user_info_data']['city'];
            $school=$this->teacher->school_business_pid($school_id);
        }
        else
        {
            $school_id=array();
            $school_id['school_id']=$teacher_details['user_info_data']['province'];
            $school=$this->teacher->school_business_pid($school_id);
        }
        $this->load->model('business/common/business_subject','subject');
        $subject=$this->subject->get_subjects();
        $config_title=config_item('teacher_title');
        $config_bank=config_item('bank');
        $this->load->model('business/admin/business_lecture');
        $province=$this->business_lecture->all_province();
        $subject_tea_id=$this->subject->get_teacher_subject($user_id);
        $phone=get_pnum_phone_server($user_id);
       // var_dump($subject_tea_id);die;
        //var_dump($subject_tea_id[0]);die;
        if(!empty($subject_tea_id))
        {
            $this->smarty->assign('subject_tea_id',$subject_tea_id[0]);
        }

        require_once APPPATH . 'libraries/qiniu/rs.php';
        require_once APPPATH . 'libraries/qiniu/io.php';
        Qiniu_SetKeys ( NH_QINIU_ACCESS_KEY, NH_QINIU_SECRET_KEY );
        $obj_putPolicy = new Qiniu_RS_PutPolicy ( NH_QINIU_BUCKET );
        $str_upToken = $obj_putPolicy->Token ( null );
        $str_salt = random_string('alnum', 6);
        $str_work_img_file_name = 'teacher_'.date('YmdHis',time()).'_work_auth_i'.$str_salt.'.png';
        $str_auth_img_file_name = 'teacher_'.date('YmdHis',time()).'_teacher_auth_i'.$str_salt.'.png';
        $str_title_img_file_name = 'teacher_'.date('YmdHis',time()).'_title_auth_i'.$str_salt.'.png';
        $this->smarty->assign('upload_token',$str_upToken);
        $this->smarty->assign('upload_work_img_key', $str_work_img_file_name);
        $this->smarty->assign('upload_auth_img_key', $str_auth_img_file_name);
        $this->smarty->assign('upload_title_img_key', $str_title_img_file_name);

        $this->smarty->assign('user_id',$user_id);
        $this->smarty->assign('phone',$phone);
        $this->smarty->assign('school',$school);
        $this->smarty->assign('city',$city);
        $this->smarty->assign('area',$area);
        $this->smarty->assign('teacher_details',$teacher_details);
        $this->smarty->assign('config_bank',$config_bank);
        $this->smarty->assign('config_title',$config_title);
        $this->smarty->assign('province',$province);
        $this->smarty->assign('subject',$subject);
        $this->smarty->assign('view','modify_teacher');
        $this->smarty->display('admin/layout.html');
    }
    /**
     * 修改教师验证
     * @author shangshikai@tizi.com
     */
    public function check_edittecher_post()
    {
        $post_edit=$this->input->post(NULL,TRUE);
       // var_dump($post_edit);die;
        if($this->teacher->check_edit_post($post_edit))
        {
            redirect('teacher');
        }
        else
        {
            redirect("teacher/modify?user_id=$post_edit[user_id]");
        }
    }
    /**
     * Ajax添加课程时选取教师列表
     * @author yanrui@tizi.com
     */
    public function teachers(){
        $arr_teachers = $this->input->post("teacher_ids");
        $arr_return = array();
        if($this->is_ajax()){
            $arr_return = $this->teacher->get_teacher_list(array(TABLE_USER.'.status'=>TABLE_USER_DIC_STATUS_ON),0,30);
            if($arr_teachers){
                foreach($arr_return as $k => $v){
                    if(in_array($v['id'],$arr_teachers)){
                        unset($arr_return[$k]);
                    }
                }
            }
            $arr_return = array_values($arr_return);
        }
        self::json_output($arr_return);
    }
}