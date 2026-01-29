<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billcategory extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("billcategory_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('billcategory', $language);
	}

	protected function rules() 
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("billcategory_name"),
				'rules' => 'trim|required|max_length[128]|callback_unique_billcategory'
			),
			array(
				'field' => 'description',
				'label' => $this->lang->line("billcategory_description"),
				'rules' => 'trim|max_length[1000]'
			)
		);
		return $rules;
	}

	public function index() 
	{
		$this->data['billcategorys'] = $this->billcategory_m->get_billcategory();
		if(permissionChecker('billcategory_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "billcategory/index";
					$this->load->view('_layout_main', $this->data);
                } else {
                    $array['name']         = $this->input->post('name');
                    $array['description']  = $this->input->post('description');
                    $array['create_date']  = date('Y-m-d H:i:s');
                    $array['modify_date']  = date('Y-m-d H:i:s');
                    $array['create_userID']= $this->session->userdata('loginuserID');
                    $array['create_roleID']= $this->session->userdata('roleID');
                    
                    $this->billcategory_m->insert_billcategory($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('billcategory/index'));
                }
			} else {
				$this->data["subview"] = "billcategory/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "billcategory/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$billcategoryID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$billcategoryID) {
			$this->data['billcategory'] 	= $this->billcategory_m->get_single_billcategory(array('billcategoryID' => $billcategoryID));
			if(inicompute($this->data['billcategory'])) {
				$this->data['billcategorys'] 	= $this->billcategory_m->get_billcategory();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if($this->form_validation->run() == false) {
						$this->data["subview"] = "billcategory/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
	                    $array['name']         = $this->input->post('name');
	                    $array['description']  = $this->input->post('description');
	                    $array['modify_date']  = date('Y-m-d H:i:s');

						$this->billcategory_m->update_billcategory($array, $billcategoryID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("billcategory/index"));
					}
				} else {
					$this->data["subview"] = "billcategory/edit";
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
        $billcategoryID = escapeString($this->uri->segment('3'));
        if((int)$billcategoryID) {
        	$billcategory = $this->billcategory_m->get_single_billcategory(array('billcategoryID' => $billcategoryID));
        	if(inicompute($billcategory)) {
	            $this->billcategory_m->delete_billcategory($billcategoryID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('billcategory/index'));
        	} else {
	            $this->data["subview"] = '_not_found';
	            $this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_billcategory($data)
    {
        $billcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$billcategoryID){
            $billcategory = $this->billcategory_m->get_order_by_billcategory(array("name" => $data, "billcategoryID !=" => $billcategoryID));
            if(inicompute($billcategory)) {
                $this->form_validation->set_message("unique_billcategory", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $billcategory = $this->billcategory_m->get_order_by_billcategory(array("name" => $data));
            if(inicompute($billcategory)) {
                $this->form_validation->set_message("unique_billcategory", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}