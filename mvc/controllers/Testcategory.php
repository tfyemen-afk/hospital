<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testcategory extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("testcategory_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('testcategory', $language);
	}

	protected function rules() 
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("testcategory_name"),
				'rules' => 'trim|required|max_length[128]|callback_unique_testcategory'
			),
			array(
				'field' => 'description',
				'label' => $this->lang->line("testcategory_description"),
				'rules' => 'trim|max_length[1000]'
			)
		);
		return $rules;
	}

	public function index() 
	{
		$this->data['testcategorys'] = $this->testcategory_m->get_testcategory();
		if(permissionChecker('testcategory_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "testcategory/index";
					$this->load->view('_layout_main', $this->data);
                } else {
                    $array['name']         = $this->input->post('name');
                    $array['description']  = $this->input->post('description');
                    $array['create_date']  = date('Y-m-d H:i:s');
                    $array['modify_date']  = date('Y-m-d H:i:s');
                    $array['create_userID']= $this->session->userdata('loginuserID');
                    $array['create_roleID']= $this->session->userdata('roleID');
                    
                    $this->testcategory_m->insert_testcategory($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('testcategory/index'));
                }
			} else {
				$this->data["subview"] = "testcategory/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "testcategory/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$testcategoryID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$testcategoryID) {
			$this->data['testcategory'] 	= $this->testcategory_m->get_single_testcategory(array('testcategoryID' => $testcategoryID));
			if(inicompute($this->data['testcategory'])) {
				$this->data['testcategorys'] 	= $this->testcategory_m->get_testcategory();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if($this->form_validation->run() == false) {
						$this->data["subview"] = "testcategory/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
	                    $array['name']         = $this->input->post('name');
	                    $array['description']  = $this->input->post('description');
	                    $array['modify_date']  = date('Y-m-d H:i:s');

						$this->testcategory_m->update_testcategory($array, $testcategoryID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("testcategory/index"));
					}
				} else {
					$this->data["subview"] = "testcategory/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "_not_found";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "_not_found";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() 
	{
        $testcategoryID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$testcategoryID) {
        	$testcategory = $this->testcategory_m->get_single_testcategory(array('testcategoryID' => $testcategoryID));
        	if(inicompute($testcategory)) {
	            $this->testcategory_m->delete_testcategory($testcategoryID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('testcategory/index'));
        	} else {
	            $this->data["subview"] = '_not_found';
	            $this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_testcategory($data)
    {
        $testcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$testcategoryID){
            $testcategory = $this->testcategory_m->get_order_by_testcategory(array("name" => $data, "testcategoryID !=" => $testcategoryID));
            if(inicompute($testcategory)) {
                $this->form_validation->set_message("unique_testcategory", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $testcategory = $this->testcategory_m->get_order_by_testcategory(array("name" => $data));
            if(inicompute($testcategory)) {
                $this->form_validation->set_message("unique_testcategory", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}