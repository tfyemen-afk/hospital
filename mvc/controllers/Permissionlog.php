<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissionlog extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('permissionlog_m');
        $language = $this->session->userdata('lang');;
        $this->lang->load('permissionlog', $language);
        redirect(site_url('dashboard/index'));
    }

    private function rules() 
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("permissionlog_name"),
                'rules' => 'trim|required|max_length[50]|callback_unique_permissionlog'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("permissionlog_description"),
                'rules' => 'trim|required|max_length[255]'
            ),
            array(
                'field' => 'active',
                'label' => $this->lang->line("permissionlog_active"),
                'rules' => 'trim|required|max_length[3]|callback_check_active_value'
            )
        );
        return $rules;
    }

	public function index() 
    { 
        $this->data['permissionlogs'] = $this->permissionlog_m->get_permissionlog();
        if(permissionChecker('permissionlog_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'permissionlog/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = [];
                    $array['name']        = $this->input->post('name');
                    $array['description'] = $this->input->post('description');
                    $array['active']      = $this->input->post('active');
                    
                    $this->permissionlog_m->insert_permissionlog($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('permissionlog/index'));
                }
            } else {
    		    $this->data["subview"] = 'permissionlog/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'permissionlog/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $permissionlogID = escapeString($this->uri->segment('3'));
        if((int)$permissionlogID) {
            $this->data['permissionlog'] = $this->permissionlog_m->get_single_permissionlog(array('permissionlogID'=>$permissionlogID));
            if(inicompute($this->data['permissionlog'])) {
                $this->data['permissionlogs'] = $this->permissionlog_m->get_permissionlog();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'permissionlog/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = [];
                        $array['name']        = $this->input->post('name');
                        $array['description'] = $this->input->post('description');
                        $array['active']      = $this->input->post('active');
                        $this->permissionlog_m->update_permissionlog($array, $permissionlogID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('permissionlog/index'));
                    }
                } else {
                    $this->data["subview"] = 'permissionlog/edit';
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
        $permissionlogID = escapeString($this->uri->segment('3'));
        if((int)$permissionlogID) {
            $permissionlog = $this->permissionlog_m->get_single_permissionlog(array('permissionlogID'=>$permissionlogID));
            if(inicompute($permissionlog)) {
                $this->permissionlog_m->delete_permissionlog($permissionlogID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('permissionlog/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function check_active_value($data) 
    {
        if($data) {
            $activeArray = ['yes','no'];
            if(!in_array($data, $activeArray)) {
                $this->form_validation->set_message("check_active_value", "Please provide active value yes/no value.");
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    public function unique_permissionlog($data) 
    {
        if($data) {
            $permissionlogID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$permissionlogID){
                $permissionlog = $this->permissionlog_m->get_order_by_permissionlog(array("name" => $data, "permissionlogID !=" => $permissionlogID));
                if(inicompute($permissionlog)) {
                    $this->form_validation->set_message("unique_permissionlog", "This %s already exists.");
                    return FALSE;
                } else {
                    return TRUE;
                }
            } else {
                $permissionlog = $this->permissionlog_m->get_order_by_permissionlog(array("name" => $data));
                if(inicompute($permissionlog)) {
                    $this->form_validation->set_message("unique_permissionlog", "This %s already exists.");
                    return FALSE;
                } else {
                    return TRUE;
                }
            }
        } else {
            return TRUE;
        }
    }

}
