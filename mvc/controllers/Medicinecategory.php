<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicinecategory extends Admin_Controller
{
    function __construct() 
    {
        parent::__construct();
        $this->load->model('medicinecategory_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('medicinecategory', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("medicinecategory_name"),
                'rules' => 'trim|required|max_length[60]|callback_unique_medicinecategory'
            )
        );
        return $rules;
    }

	public function index() 
    {
        $this->data['medicinecategorys'] = $this->medicinecategory_m->get_medicinecategory();
        if(permissionChecker('medicinecategory_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'medicinecategory/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array  = array(
                        'name'          => $this->input->post('name'),
                        'create_date'   => date('Y-m-d H:i:s'),
                        'modify_date'   => date('Y-m-d H:i:s'),
                        'create_userID' => $this->session->userdata('loginuserID'),
                        'create_roleID' => $this->session->userdata('roleID')
                    );

                    $this->medicinecategory_m->insert_medicinecategory($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('medicinecategory/index'));
                }
            } else {
                $this->data["subview"] = 'medicinecategory/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'medicinecategory/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $medicinecategoryID = escapeString($this->uri->segment('3'));
        if((int)$medicinecategoryID) {
            $this->data['medicinecategory']  = $this->medicinecategory_m->get_single_medicinecategory(array('medicinecategoryID'=> $medicinecategoryID));
            if(inicompute($this->data['medicinecategory'])) {
                $this->data['medicinecategorys'] = $this->medicinecategory_m->get_medicinecategory();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'medicinecategory/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = array(
                            'name'        => $this->input->post('name'),
                            'modify_date' => date('Y-m-d H:i:s'),
                        );
                        $this->medicinecategory_m->update_medicinecategory($array, $medicinecategoryID);
                        $this->session->set_flashdata('success','Success');
                        redirect('medicinecategory/index');
                    }
                } else {
                    $this->data["subview"] = 'medicinecategory/edit';
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
        $medicinecategoryID = escapeString($this->uri->segment('3'));
        if((int)$medicinecategoryID) {
            $medicinecategory = $this->medicinecategory_m->get_single_medicinecategory(array('medicinecategoryID'=> $medicinecategoryID));
            if(inicompute($medicinecategory)) {
                $this->medicinecategory_m->delete_medicinecategory($medicinecategoryID);
                $this->session->set_flashdata('success','Success');
                redirect('medicinecategory/index');
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_medicinecategory($name) 
    {
        $medicinecategoryID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$medicinecategoryID){
            $medicinecategory = $this->medicinecategory_m->get_order_by_medicinecategory(array("name" => $name, "medicinecategoryID !=" => $medicinecategoryID));
            if(inicompute($medicinecategory)) {
                $this->form_validation->set_message("unique_medicinecategory", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $medicinecategory = $this->medicinecategory_m->get_order_by_medicinecategory(array("name" => $name));
            if(inicompute($medicinecategory)) {
                $this->form_validation->set_message("unique_medicinecategory", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

}
