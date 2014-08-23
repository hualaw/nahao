<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

header('content-type: text/html; charset=utf-8');

class Index extends NH_User_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('business/student/student_index', 'index');
        $this->load->model('business/teacher/business_teacher', 'teacher_b');
        $this->load->model('business/common/business_area');
        $this->load->model('business/common/business_school');
    }

    /**
     * 浏览器 下载页
     */
    public function browser()
    {
        $this->smarty->display('www/studentHomePage/browser.html');
    }

    /**
     * 首页获取轮的列表信息
     * $debug == 1 显示测试的课程列表，否则把测试的过滤掉
     */

    public function index_OLD()
    {
        if (isset($_GET['nh_debug']) && ($_GET['nh_debug'] == '1')) {
            $array_data = $this->index->get_course_latest_round_list();
        } else {
            $array_data = $this->index->get_course_latest_round_list();
            if ($array_data) {
                #没有加nh_dbug参数 过滤掉测试轮
                $array_data = $this->index->filter_test_round($array_data);
            }
        }
        //var_dump($array_data);
        #课程列表的地址

        $this->load->model('business/admin/business_focus_photo');
        $focus_photo = $this->business_focus_photo->list_photo(1);
        foreach ($focus_photo as $k => $v) {
            $focus_photo[$k]['link'] = "http://www.nahao.com/ke_" . $v['round_id'] . ".html";
        }
        $course_url = config_item('course_url');
        $stage = config_item('stage');

        $this->load->helper('captcha');
        $vals = array(
            'img_path' => './captcha/',
            'img_url' => "/captcha/",
            'img_width' => 66,
            'img_height' => 30,
            'expiration' => 7200
        );
        $cap = create_captcha($vals);

        $this->smarty->assign('cap_word', $cap["word"]);
        $this->smarty->assign('cap_image', $cap['image']);


        $this->smarty->assign('focus_photo', $focus_photo);

        $this->smarty->assign('course_url', $course_url);
        $this->smarty->assign('stage', $stage);
        $this->smarty->assign('array_data', $array_data);
        $this->smarty->display('www/studentHomePage/index.html');
    }

    /**
     * nahao 2.0 Index
     * @author yanrui@tizi.com
     */
    public function index($str_query_param = '')
    {
        //param format
        if ($str_query_param) {
            parse_str(parse_url($str_query_param, PHP_URL_QUERY), $arr_query_param);
        } else {
            $arr_query_param = array();
        }
//        var_dump($arr_query_param);//exit;

//        stage_2_2.html
        $int_stage_id = isset($arr_query_param['stage']) ? intval($arr_query_param['stage']) : 0;
        $int_start = isset($arr_query_param['page']) ? intval($arr_query_param['page']) : 0;
        $arr_where = $int_stage_id > 0 ? array('stage' => $int_stage_id) : array();
        $int_live_per_page = SWITCH_WWW_INDEX_LIVE_SHOW == 1 ? PER_PAGE_NO : 6;
        $int_per_page = SWITCH_WWW_INDEX_COURSE_LIST == 1 ? 60 : PER_PAGE_NO;
        $int_per_page = 4;

        //cache template
        $str_template = 'www/studentHomePage/index.html';
        $str_cache_id = 'index_stage' . $int_stage_id . '_page' . $int_start . '_live' . $int_live_per_page . '_course' . $int_per_page;
//        var_dump($this->smarty->isCached($str_template, $str_cache_id));
//        o(SWITCH_WWW_SMARTY_CACHE==1 AND !$this->smarty->isCached($str_template, $str_cache_id));
        if (!$this->smarty->isCached($str_template, $str_cache_id)) {
//            focus photo
            $this->load->model('business/admin/business_focus_photo');
            $focus_photo = $this->business_focus_photo->list_photo(1);

            //live show list
            $arr_live_classes = $this->index->get_live_classes($int_live_per_page);
//            o($arr_live_classes);

            //course_list
            $int_round_count = $this->index->get_round_count($arr_where);
//        o($int_round_count);
            $arr_round_list = $this->index->get_round_list($arr_where, $int_start, $int_per_page);
//        o($arr_round_list,true);
            //pagination
            $this->load->library('pagination');
            $config = config_item('page_user');
            $config['base_url'] = '/';
            $config['total_rows'] = $int_round_count;
            $config['per_page'] = $int_per_page;
            $config['page_query_string'] = false;
            $this->pagination->initialize($config);
//        $str_page = $this->pagination->create_links();
            $str_page = $this->pagination->createIndexLinks($int_stage_id, $int_start);
//        o($str_page);
//        o($arr_round_list,true);

            //record
            $this->load->model('business/student/student_course');
            $arr_record_list = $this->student_course->read_recent_view_data();
//        o($arr_record_list,true);

            $course_url = config_item('course_url');
            $this->smarty->assign('course_url', $course_url);
            $this->smarty->assign('stage', config_item('stage'));
            $this->smarty->assign('material_versions', config_item('material_version'));
            $this->smarty->assign('course_types', $stage = config_item('course_type'));
            $this->smarty->assign('round_icons', $stage = config_item('round_icon'));
            $this->smarty->assign('today_begin_time', strtotime(date('Y-m-d', time())));
            $this->smarty->assign('today_end_time', strtotime(date('Y-m-d 23:59:59', time())));
            $this->smarty->assign('focus_photo', $focus_photo);
            $this->smarty->assign('live_list', $arr_live_classes);
            $this->smarty->assign('round_list', $arr_round_list);
            $this->smarty->assign('array_recent_view', $arr_record_list);
            $this->smarty->assign('page', $str_page);
            $this->smarty->assign('query_params', $arr_query_param);
            $this->smarty->registerPlugin('function', 'get_course_img_by_size', 'get_course_img_by_size');
        }
        $this->smarty->display($str_template, $str_cache_id);
    }

    /**
     * 验证码
     * @author shangshikai@tizi.com
     */
    public function captcha()
    {
        $this->load->helper('captcha');
        $vals = array(
            'img_path' => './captcha/',
            'img_url' => "/captcha/",
            'img_width' => 66,
            'img_height' => 35,
            'expiration' => 7200
        );
        $cap = create_captcha($vals);
        $this->session->set_userdata('captcha', strtolower($cap['word']));
        echo $cap['image'];
    }

    /**
     * 获取session里的验证码
     * @author shangshikai@tizi.com
     */
    public function get_captcha()
    {
        $arr_userdata = $this->session->all_userdata();
        echo $arr_userdata['captcha'];
    }

    /**
     * 我要开课
     */
    public function apply_teach()
    {
        #判断是否登录
        if (!$this->is_login) {
            redirect('/login');
        }

        $param['stage'] = config_item('stage');
        $param['teacher_title'] = config_item('teacher_title');
        $param['teacher_type'] = config_item('teacher_type');
        $param['subject'] = $this->teacher_b->get_subject();
        $param['teach_years'] = 50;
        $user_info = $this->_user_detail;
        #学校
        $my_school = $this->business_school->school_info($this->_user_detail['school'], 'schoolname,province_id,city_id,county_id,id,sctype', $this->_user_detail['custom_school']);
        $school = array(
            'province_id' => isset($my_school['province_id']) ? $my_school['province_id'] : '',
            'city_id' => isset($my_school['city_id']) ? $my_school['city_id'] : '',
            'county_id' => isset($my_school['county_id']) ? $my_school['county_id'] : '',
            'sctype' => isset($my_school['sctype']) ? $my_school['sctype'] : '',
            'id' => isset($my_school['id']) ? $my_school['id'] : '',
        );
        $school_name = isset($my_school['schoolname']) ? $my_school['schoolname'] : '';
        array_shift($my_school);
        $school_area = $this->business_area->get_areas_by_ids($my_school);

        $data = array(
            'school' => $school,
            'school_name' => $school_name,
            'school_area' => $school_area,
            'data' => $param,
            'user_info' => isset($user_info['phone']) || isset($user_info['email']) ? $user_info : array('phone' => '', 'email' => ''),
        );
        $this->smarty->assign('data', $data);
        $this->smarty->display('www/studentStartClass/writeInfo.html');
    }

    /**
     * 保存我要开课申请
     */
    public function save_apply_teach()
    {
        #判断是否登录
        if (!$this->is_login) {
            redirect('/login');
        }
        $course = $this->input->post("course");
        $resume = $this->input->post("resume");
        $subject = $this->input->post("subject");
        $province = $this->input->post("province_id");
        $city = $this->input->post("city_id");
        $area = $this->input->post("area_county_id");
        $school = $this->input->post("school_id");
        $school_type = $this->input->post("school_type");
        $schoolname = $this->input->post("schoolname");
        $stage = $this->input->post("stage");
        $teach_years = $this->input->post("teach_years");
        $course_intro = $this->input->post("course_intro");
        $teach_type = $this->input->post("teach_type");
        $gender = $this->input->post("gender");
        $title = $this->input->post("title");
        $age = $this->input->post("age");
        $phone = $this->input->post("phone");
        $email = $this->input->post("email");
        $qq = $this->input->post("qq");
        $date_time = $this->input->post("date_time");
        $start_time = $this->input->post("start_time");
        $end_time = $this->input->post("end_time");
        $start_time = strtotime($date_time . ' ' . $start_time);
        $end_time = strtotime($date_time . ' ' . $end_time);
        $name = $this->input->post("name");
        $user_id = $this->session->userdata('user_id'); #TODO用户登录就是user_id

        $array_data = array(
            "course" => $course,
            "resume" => $resume,
            "subject" => $subject,
            "status" => 1,
            "create_time" => time(),
            "province" => $province,
            "city" => $city,
            "area" => $area,
            "school" => $school,
            "school_type" => $school_type,
            "schoolname" => $schoolname,
            "stage" => $stage,
            "teach_years" => $teach_years,
            "course_intro" => $course_intro,
            "teach_type" => $teach_type,
            "gender" => $gender,
            "title" => $title,
            "age" => $age,
            "phone" => $phone,
            "email" => $email,
            "qq" => $qq,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "name" => $name,
            "user_id" => $user_id
        );
        $bool_flag = $this->index->save_apply_teach($array_data);
        header('Content-Type:text/html;CHARSET=utf-8');
        if ($bool_flag) {
            self::json_output(array('status' => 'ok', 'msg' => '申请试讲操作成功'));
//			echo '<script>alert("申请成功");window.location.href="'.teacher_url().'"</script>';
        } else {
            self::json_output(array('status' => 'error', 'msg' => '申请试讲操作失败'));
//			echo '<script>alert("申请失败");window.location.href="/index/apply_teach/"</script>';
        }
    }

    /**
     * 意见反馈
     */
    public function feedback()
    {
        $str_content = $this->input->post("content");
        $str_nickname = $this->input->post("nickname");
        $str_email = $this->input->post("email");
        $array_data = array(
            'content' => $str_content,
            'nickname' => $str_nickname,
            'email' => $str_email,
            'create_time' => time()
        );
        $bool_flag = $this->model_index->save_feedback($array_data);
        if ($bool_flag) {
            self::json_output(array('status' => 'ok', 'msg' => '提交意见反馈成功'));
        } else {
            self::json_output(array('status' => 'error', 'msg' => '提交意见反馈失败'));
        }
    }

    /**
     * 底部的页面
     */
    public function about()
    {
        $str_pram = $this->uri->rsegment(3) ? $this->uri->rsegment(3) : 'aboutus';
        switch ($str_pram) {
            case 'aboutus':
                $seo_title = '关于我们-那好网';
                $seo_description = '';
                break;
            case 'classmode':
                $seo_title = '那好招聘-那好网';
                $seo_description = '';
                $this->load->model('model/student/model_employment', 'employment');
                $employ_info = $this->employment->getAll();
                $this->smarty->assign('employ_info', $employ_info);
                break;
            case 'userhelp':
                $seo_title = '那好怎么用,那好学习流程-那好网';
                $seo_description = '教你如何正确使用那好，知道那好怎么用，并详细了解那好学习流程';
                break;
            case 'advise':
                $seo_title = '那好投诉与建议处理-那好网';
                $seo_description = '那好在线教育平台投诉、建议及相关问题，请联系我们。我们会及时核查您有关那好投诉与建议的问题，解决您的投诉。您的参与将帮助我们改进产品与服务。那好网！（nahao.com）';
                break;
            case 'contactus':
                $seo_title = '联系我们-那好网';
                $seo_description = '';
                break;
            case 'service':
                $seo_title = '服务条款-那好网';
                $seo_description = '';
                break;
            case 'wish':
                $seo_title = '总裁寄语-那好网';
                $seo_description = '';
                break;
            case 'link':
               	$seo_title = '友情链接-那好网';
                $seo_description = '';
               	break;
        }

        $this->smarty->assign('str_pram', $str_pram);
        $this->smarty->assign('seo_title', $seo_title);
        $this->smarty->assign('seo_description', $seo_description);
        $this->smarty->display('www/about/index.html');
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
