<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Admin_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("search_m");
        $this->load->model("role_m");
        $this->load->model("designation_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('search', $language);
	}

	public function index() 
	{
		if($_GET) {
			$search = xssRemove($this->input->get('search'));
			if(((string)$search)) {
                $this->data['searchs'] = [];
                if(permissionChecker('user') && permissionChecker('patient')) {
                    $this->data['searchs'] = $this->search_m->get_order_by_search_for_user_patient($search);
                } elseif(permissionChecker('user')) {
                    $this->data['searchs'] = $this->search_m->get_order_by_search_for_user($search);
                } elseif(permissionChecker('patient')) {
                    $this->data['searchs'] = $this->search_m->get_order_by_search_for_patient($search);
                }

                $this->data['roles']        = pluck($this->role_m->get_role(), 'role', 'roleID');
                $this->data['designations'] = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
                $this->data['getsearch']    = $search;

                $this->data["subview"] = "search/index";
                $this->load->view('_layout_main', $this->data);
			} else {
				redirect(site_url('dashboard/index'));
			}
		} else {
            redirect(site_url('dashboard/index'));
		}
	}




}
