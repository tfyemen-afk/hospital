<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Postcategories extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("postcategories_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('postcategories', $language);
	}

	protected function rules() 
	{
		$rules = array(
			array(
				'field' => 'postcategories',
				'label' => $this->lang->line("postcategories_name"),
				'rules' => 'trim|required|max_length[40]|callback_unique_postcategories'
			),
			array(
				'field' => 'postdescription',
				'label' => $this->lang->line("postcategories_description"),
				'rules' => 'trim|max_length[100]'
			)
		);
		return $rules;
	}

	public function index() 
	{
		$this->data['postcategories'] = $this->postcategories_m->get_postcategories();
		if(permissionChecker('postcategories_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data['form_validation'] = validation_errors();
					$this->data["subview"] = "postcategories/index";
					$this->load->view('_layout_main', $this->data);
				} else {
					$array["postcategories"]    = $this->input->post('postcategories');
					$array["postslug"]          = '#';
					$array["postdescription"]   = $this->input->post('postdescription');
					$array["create_date"]       = date("Y-m-d H:i:s");
	                $array["modify_date"]       = date("Y-m-d H:i:s");
	                $array["create_userID"]     = $this->session->userdata('loginuserID');
	                $array["create_roleID"]     = $this->session->userdata('roleID');
					$this->postcategories_m->insert_postcategories($array);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("postcategories/index"));
				}
			} else {
				$this->data["subview"] = "postcategories/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "postcategories/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$postcategoriesID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$postcategoriesID) {
            $this->data['postscategory'] = $this->postcategories_m->get_single_postcategories(array('postcategoriesID'=>$postcategoriesID));
			if(inicompute($this->data['postscategory'])) {
				$this->data['postcategories'] = $this->postcategories_m->get_postcategories();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "postcategories/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array["postcategories"]   = $this->input->post('postcategories');
						$array["postdescription"]  = $this->input->post('postdescription');
		                $array["modify_date"]      = date("Y-m-d H:i:s");
						$this->postcategories_m->update_postcategories($array, $postcategoriesID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("postcategories/index"));
					}
				} else {
					$this->data["subview"] = "postcategories/edit";
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
        $postcategoriesID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$postcategoriesID) {
            $postscategory = $this->postcategories_m->get_single_postcategories(array('postcategoriesID'=>$postcategoriesID));
            if(inicompute($postscategory)) {
	            $this->postcategories_m->delete_postcategories($postcategoriesID);
	            $this->session->set_flashdata('success','Success');
	            redirect(site_url('postcategories/index'));
            } else {
            	$this->data["subview"] = '_not_found';
            	$this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_postcategories($data) 
    {
        $postcategoriesID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$postcategoriesID) {
            $postcategories = $this->postcategories_m->get_order_by_postcategories(array("postcategories" => $data, "postcategoriesID !=" => $postcategoriesID));
            if(inicompute($postcategories)) {
                $this->form_validation->set_message("unique_postcategories", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $postcategories = $this->postcategories_m->get_order_by_postcategories(array("postcategories" => $data));
            if(inicompute($postcategories)) {
                $this->form_validation->set_message("unique_postcategories", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}