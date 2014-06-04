<?php
if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class School extends NH_Controller {
	
	public function __construct(){
		parent::__construct();
        $this->load->model("business/common/business_school");
	}
	
	public function index(){
		$parent_id = intval($this->input->get("id"));
		$result = $this->business_school->id_children($parent_id);
		$this->json_output($result);
	}
	
	public function type(){
		$school_type_arr = config_item('school_type');
		foreach ($school_type_arr as $key => $value){
			$school_type[] = array("id" => $key, "name" => $value);
		}
        $this->json_output($school_type);
    }

    public function get_school() {
        $parent_id = intval($this->input->get("id"));
        $school_type = intval($this->input->get("sctype"));
        $result = $this->business_school->county_schools($parent_id, $school_type, "id,schoolname");
        $this->json_output($result);
    }
}