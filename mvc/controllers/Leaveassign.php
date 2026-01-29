<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaveassign extends Admin_Controller
{
    public function __construct()
     {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('leaveassign_m');
        $this->load->model('leavecategory_m');
        $language = $this->session->userdata('lang');;
        $this->lang->load('leaveassign', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("leaveassign_role"),
                'rules' => 'trim|required|callback_required_no_zero|callback_unique_role'
            ),
            array(
                'field' => 'leavecategoryID',
                'label' => $this->lang->line("leaveassign_category"),
                'rules' => 'trim|required|callback_required_no_zero|callback_unique_leaveassign'
            ),
            array(
                'field' => 'year',
                'label' => $this->lang->line("leaveassign_year"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'leaveassignday',
                'label' => $this->lang->line("leaveassign_no_of_day"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    private function get_yearlist()
    {
        $years = range(date('Y'), 2000);
        return $years;
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

        $this->data['leaveassigns']   = $this->leaveassign_m->get_leaveassign();
        $this->data['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(),'obj','leavecategoryID');
        $this->data['roles']          = pluck($this->role_m->get_order_by_role(array('roleID != ' => 3)), 'obj', 'roleID');
        $this->data['yearlists']      = $this->get_yearlist();

        if(permissionChecker('leaveassign_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'leaveassign/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['roleID']           = $this->input->post('roleID');
                    $array['leavecategoryID']  = $this->input->post('leavecategoryID');
                    $array['year']             = $this->input->post('year');
                    $array['leaveassignday']   = $this->input->post('leaveassignday');
                    $array['create_date']      = date('Y-m-d H:i:s');
                    $array['modify_date']      = date('Y-m-d H:i:s');
                    $array['create_userID']    = $this->session->userdata('loginuserID');
                    $array['create_roleID']    = $this->session->userdata('roleID');

                    $this->leaveassign_m->insert_leaveassign($array);
                    $this->session->set_flashdata('success','Success');
                    redirect('leaveassign/index');
                }
            } else {
    		    $this->data["subview"] = 'leaveassign/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'leaveassign/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $leaveassignID = escapeString($this->uri->segment('3'));
        if((int)$leaveassignID) {
            $this->data['leaveassign'] = $this->leaveassign_m->get_single_leaveassign(array('leaveassignID'=> $leaveassignID));
            if(inicompute($this->data['leaveassign'])) {
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

                $this->data['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(),'obj','leavecategoryID');
                $this->data['roles']          = pluck($this->role_m->get_order_by_role(array('roleID != ' => 3)),'obj','roleID');
                $this->data['yearlists']      = $this->get_yearlist();
                $this->data['leaveassigns']   = $this->leaveassign_m->get_leaveassign();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'leaveassign/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array['roleID']           = $this->input->post('roleID');
                        $array['leavecategoryID']  = $this->input->post('leavecategoryID');
                        $array['year']             = $this->input->post('year');
                        $array['leaveassignday']   = $this->input->post('leaveassignday');
                        $array['modify_date']      = date('Y-m-d H:i:s');

                        $this->leaveassign_m->update_leaveassign($array, $leaveassignID);
                        $this->session->set_flashdata('success','Success');
                        redirect('leaveassign/index');
                    }
                } else {
                    $this->data["subview"] = 'leaveassign/edit';
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
        $leaveassignID = escapeString($this->uri->segment('3'));
        if((int)$leaveassignID) {
            $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leaveassignID'=>$leaveassignID));
            if(inicompute($leaveassign)) {
                $this->leaveassign_m->delete_leaveassign($leaveassignID);
                $this->session->set_flashdata('success','Success');
                redirect('leaveassign/index');
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_leaveassign() 
    {
        $array['roleID']         = $this->input->post('roleID');
        $array['leavecategoryID']= $this->input->post('leavecategoryID');
        $array['year']           = $this->input->post('year');

        $leaveassignID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$leaveassignID){
            $array['leaveassignID !=']  = $leaveassignID;
            $leaveassign = $this->leaveassign_m->get_order_by_leaveassign($array);
            if(inicompute($leaveassign)) {
                $this->form_validation->set_message("unique_leaveassign", "This %s has already assigned.");
                return FALSE;
            }
            return TRUE;
        } else {
            $leaveassign = $this->leaveassign_m->get_order_by_leaveassign($array);
            if(inicompute($leaveassign)) {
                $this->form_validation->set_message("unique_leaveassign", "This %s has already assigned.");
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
            } else {
                return TRUE;
            }
        } else {
            return TRUE;
        }
    }

    public function unique_role()
    {
        $role = $this->role_m->get_single_role(array($this->input->post('roleID') => 3));
        if(inicompute($role)) {
            $this->form_validation->set_message("unique_role", "This %s is deny.");
            return false;
        }
        return true;
    }
}
