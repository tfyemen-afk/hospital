<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deathregister extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('deathregister_m');
        $this->load->model('patient_m');
        $this->load->model('user_m');
        $this->load->library('report');
        $language = $this->session->userdata('lang');;
        $this->lang->load('deathregister', $language);
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
                'assets/inilabs/deathregister/index.js',
            )
        );
        $displayID = htmlentities(escapeString($this->uri->segment('3')));

        $queryArray = [];
        if($displayID == 2) {
            $queryArray = array('YEAR(death_date)' => date('Y'), 'MONTH(death_date)' => date('m'));
        } elseif($displayID == 3) {
            $queryArray = array('YEAR(death_date)' => date('Y'));
        } elseif($displayID == 4) {

        } else {
            $displayID = 1;
            $queryArray = array('DATE(death_date)' => date('Y-m-d'));
        }
        if($this->data['loginroleID'] == 2) {
            $queryArray['doctorID'] = $this->data['loginuserID'];
        }
        $this->data['deathregisters']    = $this->deathregister_m->get_order_by_deathregister($queryArray);
        $this->data['displayID'] = $displayID;

        $this->data['patients']       = $this->patient_m->get_select_patient('patientID, name', array('delete_at' => 0));
        $this->data['doctors']        = $this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0));
        if(permissionChecker('deathregister_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'deathregister/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['name']          = $this->input->post('name');
                    $array['relation']      = $this->input->post('relation');
                    $array['guardian_name'] = $this->input->post('guardian_name');
                    $array['permanent_address'] = $this->input->post('permanent_address');
                    $array['gender']        = $this->input->post('gender');
                    $array['age']           = $this->input->post('age');
                    $array['death_date']    = date('Y-m-d H:i:s', strtotime($this->input->post('death_date')));
                    $array['nationality']   = $this->input->post('nationality');
                    $array['death_cause']   = $this->input->post('death_cause');
                    $array['doctorID']      = $this->input->post('doctorID');
                    $array['confirm_date']  = date('Y-m-d H:i:s', strtotime($this->input->post('confirm_date')));
                    $array['patientID']     = $this->input->post('patientID');
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');

                    $this->deathregister_m->insert_deathregister($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('deathregister/index'));
                }
            } else {
    		    $this->data["subview"] = 'deathregister/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'deathregister/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $deathregisterID    = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$deathregisterID && (int)$displayID) {
            $queryArray = array('deathregisterID'=> $deathregisterID);
            if($this->data['loginroleID'] == 2) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            }
            $this->data['deathregister'] = $this->deathregister_m->get_single_deathregister($queryArray);
            if(inicompute($this->data['deathregister'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datetimepicker/css/datetimepicker.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/datetimepicker/js/datetimepicker.js',
                        'assets/inilabs/deathregister/index.js',
                    )
                );
                $queryArray = [];
                if($displayID == 2) {
                    $queryArray = array('YEAR(death_date)' => date('Y'), 'MONTH(death_date)' => date('m'));
                } elseif($displayID == 3) {
                    $queryArray = array('YEAR(death_date)' => date('Y'));
                } elseif($displayID == 4) {

                } else {
                    $displayID = 1;
                    $queryArray = array('DATE(death_date)' => date('Y-m-d'));
                }
                if($this->data['loginroleID'] == 2) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                }
                $this->data['deathregisters']    = $this->deathregister_m->get_order_by_deathregister($queryArray);
                $this->data['displayID'] = $displayID;

                $this->data['patients']       = $this->patient_m->get_select_patient('patientID, name', array('delete_at' => 0));
                $this->data['doctors']        = $this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0));
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'deathregister/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array['name']          = $this->input->post('name');
                        $array['relation']      = $this->input->post('relation');
                        $array['guardian_name'] = $this->input->post('guardian_name');
                        $array['permanent_address'] = $this->input->post('permanent_address');
                        $array['gender']        = $this->input->post('gender');
                        $array['age']           = $this->input->post('age');
                        $array['death_date']    = date('Y-m-d H:i:s', strtotime($this->input->post('death_date')));
                        $array['nationality']   = $this->input->post('nationality');
                        $array['death_cause']   = $this->input->post('death_cause');
                        $array['doctorID']      = $this->input->post('doctorID');
                        $array['confirm_date']  = date('Y-m-d H:i:s', strtotime($this->input->post('confirm_date')));
                        $array['patientID']     = $this->input->post('patientID');
                        $array["modify_date"]   = date("Y-m-d H:i:s");

                        $this->deathregister_m->update_deathregister($array, $deathregisterID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('deathregister/index/'.$displayID));
                    }
                } else {
                    $this->data["subview"] = 'deathregister/edit';
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
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/deathregister/view.js'
            ]
        ];

        $deathregisterID    = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$deathregisterID && (int)$displayID) {
            $queryArray = array('deathregisterID'=> $deathregisterID);
            if($this->data['loginroleID'] == 2) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            }
            $deathregister = $this->deathregister_m->get_single_deathregister($queryArray);
            if(inicompute($deathregister)) {
                $this->data['jsmanager'] = ['deathregisterID' => $deathregisterID];

                $this->data['displayID']        = $displayID;
                $this->data['patient']          = $this->patient_m->get_select_patient('patientID, name', array('patientID'=> $deathregister->patientID), TRUE);
                $this->data['doctor']           = $this->user_m->get_select_user('userID, name', array('userID'=> $deathregister->doctorID), TRUE);
                $this->data['deathregister']    = $deathregister;
                $this->data["subview"]          = 'deathregister/view';
                $this->load->view('_layout_main', $this->data);
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function printpreview() 
    {
        $deathregisterID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$deathregisterID) {
            $queryArray = array('deathregisterID'=> $deathregisterID);
            if($this->data['loginroleID'] == 2) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            }
            $deathregister = $this->deathregister_m->get_single_deathregister($queryArray);
            if(inicompute($deathregister)) {
                $this->data['patient']          = $this->patient_m->get_select_patient('patientID, name', array('patientID'=> $deathregister->patientID), TRUE);
                $this->data['doctor']           = $this->user_m->get_select_user('userID, name', array('userID'=> $deathregister->doctorID), TRUE);
                $this->data['deathregister']    = $deathregister;
                $this->report->reportPDF(['stylesheet' => 'deathregistermodule.css', 'data' => $this->data, 'viewpath' => 'deathregister/printpreview']);
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function sendmail() {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('deathregister_view')) {
            if($_POST) {
                $rules = $this->mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $deathregisterID   = $this->input->post('deathregisterID');
                    if((int)$deathregisterID) {
                        $queryArray = array('deathregisterID'=> $deathregisterID);
                        if($this->data['loginroleID'] == 2) {
                            $queryArray['doctorID'] = $this->data['loginuserID'];
                        }
                        $deathregister = $this->deathregister_m->get_single_deathregister($queryArray);
                        if(inicompute($deathregister)) {
                            $this->data['patient']  = $this->patient_m->get_select_patient('patientID, name', array('patientID'=> $deathregister->patientID), TRUE);
                            $this->data['doctor']   = $this->user_m->get_select_user('userID, name', array('userID'=> $deathregister->doctorID), TRUE);
                            $this->data['deathregister'] = $deathregister;

                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');

                            $this->report->reportSendToMail(['stylesheet' => 'deathregistermodule.css', 'data' => $this->data, 'viewpath' => 'deathregister/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {
                            $retArray['message'] = $this->lang->line('deathregister_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('deathregister_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('deathregister_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('deathregister_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    public function certificate() 
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/deathregister/certificate.js'
            ]
        ];

        if(permissionChecker('deathregister_view')) {
            $deathregisterID    = htmlentities(escapeString($this->uri->segment('3')));
            $displayID          = htmlentities(escapeString($this->uri->segment('4')));
            if((int)$deathregisterID && (int)$displayID) {
                $queryArray = array('deathregisterID'=> $deathregisterID);
                if($this->data['loginroleID'] == 2) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                }
                $deathregister = $this->deathregister_m->get_single_deathregister($queryArray);
                if(inicompute($deathregister)) {
                    $this->data['jsmanager']        = ['deathregisterID' => $deathregisterID];
                    $this->data['displayID']        = $displayID;
                    $this->data['patient']          = $this->patient_m->get_select_patient('patientID, name', array('patientID'=> $deathregister->patientID), TRUE);
                    $this->data['doctor']           = $this->user_m->get_select_user('userID, name', array('userID'=> $deathregister->doctorID), TRUE);
                    $this->data['deathregister']    = $deathregister;
                    $this->data["subview"] = 'deathregister/certificate';
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $this->data["subview"] = '_not_found';
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

    public function certificateprintpreview() 
    {
        if(permissionChecker('deathregister_view')) {
            $deathregisterID = escapeString($this->uri->segment('3'));
            if((int)$deathregisterID) {
                $queryArray = array('deathregisterID'=> $deathregisterID);
                if($this->data['loginroleID'] == 2) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                }
                $deathregister = $this->deathregister_m->get_single_deathregister($queryArray);
                if(inicompute($deathregister)) {
                    $this->data['patient']          = $this->patient_m->get_select_patient('patientID, name', array('patientID'=> $deathregister->patientID), TRUE);
                    $this->data['doctor']           = $this->user_m->get_select_user('userID, name', array('userID'=> $deathregister->doctorID), TRUE);
                    $this->data['deathregister']    = $deathregister;

                    $this->report->reportPDF(['stylesheet' => 'deathregistercertificatemodule.css', 'data' => $this->data, 'viewpath' => 'deathregister/certificateprintpreview', 'designnone'=> 0]);
                } else {
                    $this->data["subview"] = '_not_found';
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

    public function certificatesendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('deathregister_view')) {
            if($_POST) {
                $rules = $this->mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $deathregisterID   = $this->input->post('deathregisterID');
                    if((int)$deathregisterID) {
                        $queryArray = array('deathregisterID'=> $deathregisterID);
                        if($this->data['loginroleID'] == 2) {
                            $queryArray['doctorID'] = $this->data['loginuserID'];
                        }
                        $deathregister = $this->deathregister_m->get_single_deathregister($queryArray);
                        if(inicompute($deathregister)) {
                            $this->data['patient']  = $this->patient_m->get_select_patient('patientID, name', array('patientID'=> $deathregister->patientID), TRUE);
                            $this->data['doctor']   = $this->user_m->get_select_user('userID, name', array('userID'=> $deathregister->doctorID), TRUE);
                            $this->data['deathregister'] = $deathregister;

                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');

                            $this->report->reportSendToMail(['stylesheet' => 'deathregistercertificatemodule.css', 'data' => $this->data, 'viewpath' => 'deathregister/certificateprintpreview', 'email' => $email, 'subject' => $subject, 'message' => $message, 'designnone'=> 0]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {
                            $retArray['message'] = $this->lang->line('deathregister_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('deathregister_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('deathregister_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('deathregister_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    public function delete() 
    {
        $deathregisterID    = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$deathregisterID && (int)$displayID) {
            $queryArray = array('deathregisterID'=> $deathregisterID);
            if($this->data['loginroleID'] == 2) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            }
            $deathregister = $this->deathregister_m->get_single_deathregister($queryArray);
            if(inicompute($deathregister)) {
                $this->deathregister_m->delete_deathregister($deathregisterID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('deathregister/index/'.$displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("deathregister_name_of_deceased"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'relation',
                'label' => $this->lang->line("deathregister_relation_of_deceased"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'guardian_name',
                'label' => $this->lang->line("deathregister_guardian_name"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'permanent_address',
                'label' => $this->lang->line("deathregister_permanent_address"),
                'rules' => 'trim|required|max_length[255]'
            ),
            array(
                'field' => 'gender',
                'label' => $this->lang->line("deathregister_gender"),
                'rules' => 'trim|required|max_length[1]|callback_required_no_zero'
            ),
            array(
                'field' => 'age',
                'label' => $this->lang->line("deathregister_age_of_the_deceased"),
                'rules' => 'trim|required|max_length[100]'
            ),
            array(
                'field' => 'death_date',
                'label' => $this->lang->line("deathregister_date_of_death"),
                'rules' => 'trim|required|max_length[19]|callback_valid_date|callback_check_future_date'
            ),
            array(
                'field' => 'nationality',
                'label' => $this->lang->line("deathregister_nationality"),
                'rules' => 'trim|required|max_length[100]'
            ),
            array(
                'field' => 'death_cause',
                'label' => $this->lang->line("deathregister_cause_of_death"),
                'rules' => 'trim|required|max_length[255]'
            ),
            array(
                'field' => 'doctorID',
                'label' => $this->lang->line("deathregister_name_of_the_doctor"),
                'rules' => 'trim|required|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'confirm_date',
                'label' => $this->lang->line("deathregister_death_confirm_date"),
                'rules' => 'trim|required|max_length[19]|callback_valid_date|callback_check_future_date'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("deathregister_patient"),
                'rules' => 'trim|required|max_length[11]'
            )
        );
        return $rules;
    }

    protected function mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("deathregister_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("deathregister_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("deathregister_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'deathregisterID',
                'label' => $this->lang->line("deathregister_death_register"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }
    
    public function valid_date($date) 
    {
        if($date) {
            if(strlen($date) < 19) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                if(inicompute($arr) > 2) {
                    $dd = $arr[0];
                    $mm = $arr[1];
                    $yyyy = explode(" ", $arr[2]);
                    if(inicompute($yyyy) > 1) {
                        $yyyy = $yyyy[0];
                        if(checkdate($mm, $dd, $yyyy)) {
                            return TRUE;
                        } else {
                            $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                            return FALSE; 
                        }
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is invalid.");
                        return FALSE;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is invalid.");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function check_future_date($date) 
    {
        $todaydate = strtotime(date('d-m-Y'));
        $date      = strtotime(date('d-m-Y', strtotime($date)));

        if($date > $todaydate) {
            $this->form_validation->set_message("check_future_date", "The %s cann't be set future date.");
            return FALSE;
        }
        return TRUE;
    }

}
