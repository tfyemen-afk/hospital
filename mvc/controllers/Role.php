<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends Admin_Controller
{

    public $notdeleteArray = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    public function __construct()
     {
        parent::__construct();
        $this->load->model('role_m');
        $this->data['notdeleteArray'] = $this->notdeleteArray;

        $language = $this->session->userdata('lang');
        $this->lang->load('role', $language);
     }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'role',
                'label' => $this->lang->line("role_role"),
                'rules' => 'trim|required|max_length[40]|callback_unique_role'
            )
        );
        return $rules;
    }

	public function index()
    {
        $this->data['roles'] = $this->role_m->get_role();
        if(permissionChecker('role_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'role/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = [];
                    $array['role'] = $this->input->post('role');
                    $array['create_date']  = date('Y-m-d H:i:s');
                    $array['modify_date']  = date('Y-m-d H:i:s');
                    $array['create_userID']= $this->session->userdata('loginuserID');
                    $array['create_roleID']= $this->session->userdata('roleID');
                    
                    $this->role_m->insert_role($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('role/index'));
                }
            } else {
    		    $this->data["subview"] = 'role/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'role/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit()
    {
        $roleID = escapeString($this->uri->segment('3'));
        if((int)$roleID) {
            $this->data['role'] = $this->role_m->get_single_role(array('roleID' => $roleID));
            if(inicompute($this->data['role'])) {
                $this->data['roles'] = $this->role_m->get_role();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'role/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = [];
                        $array['role'] = $this->input->post('role');
                        $array['modify_date']  = date('Y-m-d H:i:s');

                        $this->role_m->update_role($array, $roleID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('role/index'));
                    }
                } else {
                    $this->data["subview"] = 'role/edit';
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
        $roleID = escapeString($this->uri->segment(3));
        if((int)$roleID && !in_array($roleID, $this->notdeleteArray)) {
            $role = $this->role_m->get_single_role(array('roleID' => $roleID));
            if(inicompute($role)) {
                $this->role_m->delete_role($roleID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('role/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_role($data)
    {
        $roleID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$roleID){
            $role = $this->role_m->get_order_by_role(array("role" => $data, "roleID !=" => $roleID));
            if(inicompute($role)) {
                $this->form_validation->set_message("unique_role", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $role = $this->role_m->get_order_by_role(array("role" => $data));
            if(inicompute($role)) {
                $this->form_validation->set_message("unique_role", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }
}
