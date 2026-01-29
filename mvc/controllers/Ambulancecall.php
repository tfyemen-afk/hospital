<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ambulancecall extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ambulancecall_m');
        $this->load->model('ambulance_m');
        $language = $this->session->userdata('lang');;
        $this->lang->load('ambulancecall', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'ambulanceID',
                'label' => $this->lang->line("ambulancecall_ambulance"),
                'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
            ),
            array(
                'field' => 'drivername',
                'label' => $this->lang->line("ambulancecall_driver_name"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("ambulancecall_date"),
                'rules' => 'trim|required|max_length[19]'
            ),
            array(
                'field' => 'amount',
                'label' => $this->lang->line("ambulancecall_amount"),
                'rules' => 'trim|max_length[40]'
            ),
            array(
                'field' => 'patientname',
                'label' => $this->lang->line("ambulancecall_patient_name"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'patientcontact',
                'label' => $this->lang->line("ambulancecall_patient_contact"),
                'rules' => 'trim|max_length[25]'
            ),
            array(
                'field' => 'pickup_point',
                'label' => $this->lang->line("ambulancecall_pickup_point"),
                'rules' => 'trim|max_length[255]'
            )
        );
        return $rules;
    }

	public function index() 
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/datetimepicker/css/datetimepicker.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/datetimepicker/js/datetimepicker.js',
                'assets/inilabs/ambulancecall/index.js'
            )
        );

        $displayID = htmlentities(escapeString($this->uri->segment('3')));
        if($displayID == 2) {
            $this->data['ambulancecalls']    = $this->ambulancecall_m->get_order_by_ambulancecall(array('YEAR(date)' => date('Y'), 'MONTH(date)' => date('m')));
        } elseif($displayID == 3) {
            $this->data['ambulancecalls']    = $this->ambulancecall_m->get_order_by_ambulancecall(array('YEAR(date)' => date('Y')));
        } elseif($displayID == 4) {
            $this->data['ambulancecalls']    = $this->ambulancecall_m->get_ambulancecall();
        } else {
            $displayID = 1;
            $this->data['ambulancecalls']    = $this->ambulancecall_m->get_order_by_ambulancecall(array('DATE(date)' => date('Y-m-d')));
        }
        $this->data['displayID']     = $displayID;

        $this->data['ambulances']     = pluck($this->ambulance_m->get_select_ambulance('ambulanceID, name, status'),'obj','ambulanceID');
        if(permissionChecker('ambulancecall_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'ambulancecall/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = [];
                    $array['ambulanceID']   = $this->input->post('ambulanceID');
                    $array['drivername']    = $this->input->post('drivername');
                    $array['date']          = date('Y-m-d H:i:s', strtotime($this->input->post('date')));
                    $array['amount']        = $this->input->post('amount');
                    $array['patientname']   = $this->input->post('patientname');
                    $array['patientcontact']= $this->input->post('patientcontact');
                    $array['pickup_point']  = $this->input->post('pickup_point');
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');
                    $this->ambulancecall_m->insert_ambulancecall($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('ambulancecall/index'));
                }
            } else {
    		    $this->data["subview"] = 'ambulancecall/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'ambulancecall/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $ambulancecallID    = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$ambulancecallID && (int)$displayID) {
            $this->data['ambulancecall'] = $this->ambulancecall_m->get_single_ambulancecall(array('ambulancecallID'=> $ambulancecallID));
            if(inicompute($this->data['ambulancecall'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datetimepicker/css/datetimepicker.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/datetimepicker/js/datetimepicker.js',
                        'assets/inilabs/ambulancecall/index.js'
                    )
                );

                if($displayID == 2) {
                    $this->data['ambulancecalls']    = $this->ambulancecall_m->get_order_by_ambulancecall(array('YEAR(date)' => date('Y'), 'MONTH(date)' => date('m')));
                } elseif($displayID == 3) {
                    $this->data['ambulancecalls']    = $this->ambulancecall_m->get_order_by_ambulancecall(array('YEAR(date)' => date('Y')));
                } elseif($displayID == 4) {
                    $this->data['ambulancecalls']    = $this->ambulancecall_m->get_ambulancecall();
                } else {
                    $displayID = 1;
                    $this->data['ambulancecalls']    = $this->ambulancecall_m->get_order_by_ambulancecall(array('DATE(date)' => date('Y-m-d')));
                }
                $this->data['displayID']        = $displayID;
                $this->data['ambulances']       = pluck($this->ambulance_m->get_select_ambulance('ambulanceID, name, status'),'obj','ambulanceID');
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'ambulancecall/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = [];
                        $array['ambulanceID']   = $this->input->post('ambulanceID');
                        $array['drivername']    = $this->input->post('drivername');
                        $array['date']          = date('Y-m-d H:i:s', strtotime($this->input->post('date')));
                        $array['amount']        = $this->input->post('amount');
                        $array['patientname']   = $this->input->post('patientname');
                        $array['patientcontact']= $this->input->post('patientcontact');
                        $array['pickup_point']  = $this->input->post('pickup_point');
                        $array["modify_date"]   = date("Y-m-d H:i:s");

                        $this->ambulancecall_m->update_ambulancecall($array, $ambulancecallID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('ambulancecall/index/'.$displayID));
                    }
                } else {
                    $this->data["subview"] = 'ambulancecall/edit';
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

    public function view()
    {
        $this->data["ambulancecall"] = [];
        $this->data['ambulance']     = [];
        $ambulancecallID    = $this->input->post('ambulancecallID');
        if((int)$ambulancecallID) {
            $this->data["ambulancecall"] = $this->ambulancecall_m->get_single_ambulancecall(array('ambulancecallID'=> $ambulancecallID));
            if(inicompute($this->data["ambulancecall"])) {
                $this->data['ambulance']     = $this->ambulance_m->get_select_ambulance('ambulanceID, name', array('ambulanceID'=>$this->data["ambulancecall"]->ambulanceID, 'status'=>1), TRUE);
            }
        }
        $this->load->view('ambulancecall/view', $this->data);
    }

    public function delete() 
    {
        $ambulancecallID    = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$ambulancecallID && (int)$displayID) {
            $ambulancecall = $this->ambulancecall_m->get_single_ambulancecall(array('ambulancecallID'=> $ambulancecallID));
            if(inicompute($ambulancecall)) {
                $this->ambulancecall_m->delete_ambulancecall($ambulancecallID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('ambulancecall/index/'.$displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function get_driver_info()
    {
        $retArray          = [];
        $retArray['status']= FALSE;
        $ambulanceID       = $this->input->post('ambulanceID');
        if((int)$ambulanceID) {
            $ambulance = $this->ambulance_m->get_select_ambulance('ambulanceID, drivername',array('ambulanceID'=> $ambulanceID,'status'=>1), TRUE);
            if(inicompute($ambulance)) {
                $retArray['status'] = TRUE;
                $retArray['data']   = $ambulance;
            }
        }
        echo json_encode($retArray);
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }
}
