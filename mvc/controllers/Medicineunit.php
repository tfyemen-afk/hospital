<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicineunit extends Admin_Controller {

    function __construct() 
    {
        parent::__construct();
        $this->load->model('medicineunit_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('medicineunit', $language);
    }

    private function rules() 
    {
        $rules = array(
            array(
                'field' => 'medicineunit',
                'label' => $this->lang->line("medicineunit_unit"),
                'rules' => 'trim|required|max_length[128]|callback_unique_medicineunit'
            )
        );
        return $rules;
    }

	public function index() 
    {
        $this->data['medicineunits'] = $this->medicineunit_m->get_medicineunit();
        if(permissionChecker('medicineunit_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'medicineunit/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array  = array(
                        'medicineunit' => $this->input->post('medicineunit'),
                        'create_date'   => date('Y-m-d H:i:s'),
                        'modify_date'   => date('Y-m-d H:i:s'),
                        'create_userID' => $this->session->userdata('loginuserID'),
                        'create_roleID' => $this->session->userdata('roleID')
                    );
                    $this->medicineunit_m->insert_medicineunit($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('medicineunit/index'));
                }
            } else {
    		    $this->data["subview"] = 'medicineunit/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'medicineunit/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $medicineunitID = escapeString($this->uri->segment('3'));
        if((int)$medicineunitID) {
            $this->data['medicineunit']      = $this->medicineunit_m->get_single_medicineunit(array('medicineunitID'=>$medicineunitID));
            if(inicompute($this->data['medicineunit'])) {
                $this->data['medicineunits'] = $this->medicineunit_m->get_medicineunit();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'medicineunit/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = array(
                            'medicineunit' => $this->input->post('medicineunit'),
                            'modify_date'    => date('Y-m-d H:i:s'),
                        );
                        $this->medicineunit_m->update_medicineunit($array, $medicineunitID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('medicineunit/index'));
                    }
                } else {
                    $this->data["subview"] = 'medicineunit/edit';
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
        $medicineunitID = escapeString($this->uri->segment('3'));
        if((int)$medicineunitID) {
            $medicineunit = $this->medicineunit_m->get_single_medicineunit(array('medicineunitID' => $medicineunitID));
            if(inicompute($medicineunit)) {
                $this->medicineunit_m->delete_medicineunit($medicineunitID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('medicineunit/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_medicineunit($medicineunit) 
    {
        $medicineunitID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$medicineunitID){
            $medicineunit = $this->medicineunit_m->get_order_by_medicineunit(array("medicineunit" => $medicineunit, "medicineunitID !=" => $medicineunitID));
            if(inicompute($medicineunit)) {
                $this->form_validation->set_message("unique_medicineunit", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $medicineunit = $this->medicineunit_m->get_order_by_medicineunit(array("medicineunit" => $medicineunit));
            if(inicompute($medicineunit)) {
                $this->form_validation->set_message("unique_medicineunit", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }
}
