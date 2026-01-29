<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leavecategory extends Admin_Controller
{
     public function __construct()
     {
        parent::__construct();
        $this->load->model('leavecategory_m');
        $language = $this->session->userdata('lang');;
        $this->lang->load('leavecategory', $language);
     }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'leavecategory',
                'label' => $this->lang->line("leavecategory_category"),
                'rules' => 'trim|required|max_length[255]|callback_unique_leavecategory'
            )
        );
        return $rules;
    }

	public function index()
    {
        $this->data['leavecategorys'] = $this->leavecategory_m->get_leavecategory();
        if(permissionChecker('leavecategory_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'leavecategory/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = [];
                    $array['leavecategory'] = $this->input->post('leavecategory');
                    $array['create_date']   = date('Y-m-d H:i:s');
                    $array['modify_date']   = date('Y-m-d H:i:s');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_roleID'] = $this->session->userdata('roleID');

                    $this->leavecategory_m->insert_leavecategory($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('leavecategory/index'));
                }
            } else {
    		    $this->data["subview"] = 'leavecategory/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'leavecategory/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit()
    {
        $leavecategoryID = escapeString($this->uri->segment('3'));
        if((int)$leavecategoryID) {
            $this->data['leavecategory'] = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $leavecategoryID));
            if(inicompute($this->data['leavecategory'])) {
                $this->data['leavecategorys'] = $this->leavecategory_m->get_leavecategory();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'leavecategory/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = [];
                        $array['leavecategory'] = $this->input->post('leavecategory');
                        $array['modify_date']   = date('Y-m-d H:i:s');

                        $this->leavecategory_m->update_leavecategory($array, $leavecategoryID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('leavecategory/index'));
                    }
                } else {
                    $this->data["subview"] = 'leavecategory/edit';
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
        $leavecategoryID = escapeString($this->uri->segment('3'));
        if((int)$leavecategoryID) {
            $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=>$leavecategoryID));
            if(inicompute($leavecategory)) {
                $this->leavecategory_m->delete_leavecategory($leavecategoryID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('leavecategory/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_leavecategory($data)
    {
        $leavecategoryID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$leavecategoryID){
            $leavecategory = $this->leavecategory_m->get_order_by_leavecategory(array("leavecategory" => $data, "leavecategoryID !=" => $leavecategoryID));
            if(inicompute($leavecategory)) {
                $this->form_validation->set_message("unique_leavecategory", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $leavecategory = $this->leavecategory_m->get_order_by_leavecategory(array("leavecategory" => $data));
            if(inicompute($leavecategory)) {
                $this->form_validation->set_message("unique_leavecategory", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function required_no_zero($data)
    {
        if($data != '') {
            if($data == '0') {
                $this->form_validation->set_message("required_no_zero", "The %s field is required.");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }
}
