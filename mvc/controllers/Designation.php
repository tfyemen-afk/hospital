<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Designation extends Admin_Controller
{
    public $notdeleteArray = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('designation_m');
        $this->data['notdeleteArray'] = $this->notdeleteArray;

        $language = $this->session->userdata('lang');
        $this->lang->load('designation', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'designation',
                'label' => $this->lang->line("designation_designation"),
                'rules' => 'trim|required|max_length[40]|callback_unique_designation'
            )
        );
        return $rules;
    }

	public function index() 
    {
        $this->data['designations'] = $this->designation_m->get_designation();
        if(permissionChecker('designation_add')) {   
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'designation/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = [];
                    $array['designation']   = $this->input->post('designation');
                    $array['create_date']   = date('Y-m-d H:i:s');
                    $array['modify_date']   = date('Y-m-d H:i:s');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_roleID'] = $this->session->userdata('roleID');

                    $this->designation_m->insert_designation($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('designation/index'));
                }
            } else {
    		    $this->data["subview"] = 'designation/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'designation/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $designationID = escapeString($this->uri->segment('3'));
        if((int)$designationID) {
            $this->data['designation']  = $this->designation_m->get_single_designation(array('designationID'=> $designationID));
            if(inicompute($this->data['designation'])) {
                $this->data['designations'] = $this->designation_m->get_designation();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'designation/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = [];
                        $array['designation']   = $this->input->post('designation');
                        $array['create_date']   = date('Y-m-d H:i:s');
                        $array['modify_date']   = date('Y-m-d H:i:s');
                        $array['create_userID'] = $this->session->userdata('loginuserID');
                        $array['create_roleID'] = $this->session->userdata('roleID');

                        $this->designation_m->update_designation($array, $designationID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('designation/index'));
                    }
                } else {
                    $this->data["subview"] = 'designation/edit';
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete() 
    {
        $designationID = escapeString($this->uri->segment(3));
        if((int)$designationID && !in_array($designationID, $this->notdeleteArray)) {
            $designation = $this->designation_m->get_single_designation(array('designationID'=> $designationID));
            if(inicompute($designation)) {
                $this->designation_m->delete_designation($designationID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('designation/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_designation($data) 
    {
        $designationID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$designationID){
            $designation = $this->designation_m->get_order_by_designation(array("designation" => $data, "designationID !=" => $designationID));
            if(inicompute($designation)) {
                $this->form_validation->set_message("unique_designation", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $designation = $this->designation_m->get_order_by_designation(array("designation" => $data));
            if(inicompute($designation)) {
                $this->form_validation->set_message("unique_designation", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }
}
