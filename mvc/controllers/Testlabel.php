<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class testlabel extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("testlabel_m");
		$this->load->model("testcategory_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('testlabel', $language);
	}

	protected function rules() 
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("testlabel_name"),
				'rules' => 'trim|required|max_length[128]|callback_check_testlabel_name'
			),
			array(
				'field' => 'testcategoryID',
				'label' => $this->lang->line("testlabel_test_category"),
				'rules' => 'trim|required|numeric|callback_required_no_zero|max_length[11]'
			)
		);
		return $rules;
	}

	public function index() 
	{
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/inilabs/select2.js',
            )
        );

		$this->data['testlabels']    = $this->testlabel_m->get_testlabel();
		$this->testcategory_m->order('testcategoryID asc');
		$this->data['testcategorys'] = pluck($this->testcategory_m->get_select_testcategory('testcategoryID, name'), 'name', 'testcategoryID');
		if(permissionChecker('testlabel_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "testlabel/index";
					$this->load->view('_layout_main', $this->data);
                } else {
                    $array['name']          = $this->input->post('name');
                    $array['testcategoryID']= $this->input->post('testcategoryID');
                    $array['create_date']   = date('Y-m-d H:i:s');
                    $array['modify_date']   = date('Y-m-d H:i:s');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_roleID'] = $this->session->userdata('roleID');

                    $this->testlabel_m->insert_testlabel($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('testlabel/index'));
                }
			} else {
				$this->data["subview"] = "testlabel/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "testlabel/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$testlabelID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$testlabelID) {
			$this->data['testlabel'] 	= $this->testlabel_m->get_single_testlabel(array('testlabelID' => $testlabelID));
			if(inicompute($this->data['testlabel'])) {
				$this->data['headerassets'] = array(
		            'css' => array(
		                'assets/select2/css/select2.css',
		                'assets/select2/css/select2-bootstrap.css',
		            ),
		            'js' => array(
		                'assets/select2/select2.js',
                        'assets/inilabs/select2.js',
		            )
		        );
				$this->data['testlabels'] 	 = $this->testlabel_m->get_testlabel();
				$this->data['testcategorys'] = pluck($this->testcategory_m->get_select_testcategory('testcategoryID, name'), 'name', 'testcategoryID');
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if($this->form_validation->run() == false) {
						$this->data["subview"] = "testlabel/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                    	$array['name']          = $this->input->post('name');
	                    $array['testcategoryID']= $this->input->post('testcategoryID');
	                    $array['modify_date']   = date('Y-m-d H:i:s');

						$this->testlabel_m->update_testlabel($array, $testlabelID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("testlabel/index"));
					}
				} else {
					$this->data["subview"] = "testlabel/edit";
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
        $testlabelID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$testlabelID) {
        	$testlabel = $this->testlabel_m->get_single_testlabel(array('testlabelID' => $testlabelID));
        	if(inicompute($testlabel)) {
	            $this->testlabel_m->delete_testlabel($testlabelID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('testlabel/index'));
        	} else {
	            $this->data["subview"] = '_not_found';
	            $this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function check_testlabel_name()
    {
    	$name           = $this->input->post('name');
		$testcategoryID = $this->input->post('testcategoryID');
		$testlabelID    = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$testlabelID) {
        	$testlabel = $this->testlabel_m->get_order_by_testlabel(array('name'=>$name, 'testcategoryID'=>$testcategoryID, 'testlabelID !='=> $testlabelID));
        	if(inicompute($testlabel)) {
        		$this->form_validation->set_message("check_testlabel_name", "The %s field is already exit.");
            	return FALSE;
        	}
        	return TRUE;
        } else {
        	$testlabel = $this->testlabel_m->get_order_by_testlabel(array('name'=>$name, 'testcategoryID'=>$testcategoryID));
        	if(inicompute($testlabel)) {
        		$this->form_validation->set_message("check_testlabel_name", "The %s field is already exit.");
            	return FALSE;
        	}
        	return TRUE;
        }
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }
}