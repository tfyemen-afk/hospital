<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bedtype extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
		$this->load->model("bedtype_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('bedtype', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("bedtype_name"),
				'rules' => 'trim|required|max_length[60]|callback_unique_bedtype'
			)
		);
		return $rules;
	}

	public function index()
    {
		$this->data['bedtypes'] = $this->bedtype_m->get_bedtype();
        if(permissionChecker('bedtype_add')) {
    		if($_POST) {
    			$rules = $this->rules();
    			$this->form_validation->set_rules($rules);
    			if ($this->form_validation->run() == FALSE) {
    				$this->data["subview"] = "bedtype/index";
    				$this->load->view('_layout_main', $this->data);
    			} else {
    				$array['name'] 			= $this->input->post('name');
    				$array['create_date'] 	= date('Y-m-d H:i:s');
    				$array['modify_date'] 	= date('Y-m-d H:i:s');
    				$array['create_userID'] = $this->session->userdata('loginuserID');
    				$array['create_roleID'] = $this->session->userdata('roleID');

    				$this->bedtype_m->insert_bedtype($array);
    				$this->session->set_flashdata('success','Success');
    				redirect(site_url("bedtype/index"));
    			}
    		} else {
    			$this->data["subview"] = "bedtype/index";
    			$this->load->view('_layout_main', $this->data);
    		} 
        } else {
            $this->data["subview"] = "bedtype/index";
            $this->load->view('_layout_main', $this->data);
        }
	}

	public function edit()
    {
		$bedtypeID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$bedtypeID) {
			$this->data['bedtype'] = $this->bedtype_m->get_single_bedtype(array('bedtypeID'=>$bedtypeID));
			if(inicompute($this->data['bedtype'])) {
			    $this->data['bedtypes'] = $this->bedtype_m->get_bedtype();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "bedtype/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array['name'] 		  = $this->input->post('name');
						$array['modify_date'] = date('Y-m-d H:i:s');

						$this->bedtype_m->update_bedtype($array, $bedtypeID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("bedtype/index"));
					}
				} else {
					$this->data["subview"] = "bedtype/edit";
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
        $bedtypeID = escapeString($this->uri->segment('3'));
        if((int)$bedtypeID) {
            $bedtype = $this->bedtype_m->get_single_bedtype(array('bedtypeID'=>$bedtypeID));
            if(inicompute($bedtype)) {
                $this->bedtype_m->delete_bedtype($bedtypeID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('bedtype/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_bedtype($name)
    {
        $bedtypeID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$bedtypeID){
            $bedtype = $this->bedtype_m->get_order_by_bedtype(array("name" => $name, "bedtypeID !=" => $bedtypeID));
            if(inicompute($bedtype)) {
                $this->form_validation->set_message("unique_bedtype", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $bedtype = $this->bedtype_m->get_order_by_bedtype(array("name" => $name));
            if(inicompute($bedtype)) {
                $this->form_validation->set_message("unique_bedtype", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}