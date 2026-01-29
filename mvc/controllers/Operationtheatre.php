<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operationtheatre extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('operationtheatre_m');
        $this->load->model('user_m');
        $this->load->model('patient_m');
        $this->load->library('report');
        $language = $this->session->userdata('lang');;
        $this->lang->load('operationtheatre', $language);
    }

    private function _displayManager($displayID)
    {
        if($displayID == 2) {
            $operationTheatreDisplayArray['YEAR(operationtheatre.operation_date)']   = date('Y');
            $operationTheatreDisplayArray['MONTH(operationtheatre.operation_date)']  = date('m');
        } elseif($displayID == 3) {
            $operationTheatreDisplayArray['YEAR(operationtheatre.operation_date)']   = date('Y');
        } elseif($displayID == 4) {
            $operationTheatreDisplayArray                        = [];
        } else {
            $displayID = 1;
            $operationTheatreDisplayArray['DATE(operationtheatre.operation_date)']   = date('Y-m-d');
        }
        $this->data['displayID'] = $displayID;

        if($this->data['loginroleID'] == 2) {
            $operationTheatreDisplayArray['operationtheatre.doctorID']   = $this->data['loginuserID'];
        } elseif($this->data['loginroleID'] == 3) {
            $operationTheatreDisplayArray = [];
            $operationTheatreDisplayArray['operationtheatre.patientID']  = $this->data['loginuserID'];
        }

        return $operationTheatreDisplayArray;
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
                'assets/inilabs/operationtheatre/index.js',
            )
        );

        $displayID                          = htmlentities(escapeString($this->uri->segment(3)));
        $displayArray                       = $this->_displayManager($displayID);
        $this->data['operationtheatres']    = $this->operationtheatre_m->get_select_operationtheatre_patient('operationtheatre.operationtheatreID, operationtheatre.operation_name, operationtheatre.operation_type, operationtheatre.patientID, patient.name', $displayArray);
        $this->data['jsmanager']            = ['doctorID' => null, 'assistant_doctor_1' => null, 'assistant_doctor_2' => null];
        if(permissionChecker('operationtheatre_add')) {
            $this->data['patients']   = $this->patient_m->get_select_patient('patientID, name', ['delete_at' => 0]);
            $this->data['doctors']    = $this->user_m->get_select_user('userID, name', ['roleID' => 2, 'status' => 1, 'delete_at' => 0]);
            if($_POST) {
                $this->data['jsmanager'] = ['doctorID' => $this->input->post('doctorID'), 'assistant_doctor_1' => $this->input->post('assistant_doctor_1'), 'assistant_doctor_2' => $this->input->post('assistant_doctor_2')];
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'operationtheatre/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['operation_name'] = $this->input->post('operation_name');
                    $array['operation_type'] = $this->input->post('operation_type');
                    $array['patientID']      = $this->input->post('patientID');
                    $array['operation_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('operation_date')));
                    $array['doctorID']       = $this->input->post('doctorID');
                    $array['assistant_doctor_1'] = $this->input->post('assistant_doctor_1');
                    $array['assistant_doctor_2'] = $this->input->post('assistant_doctor_2');
                    $array["create_date"]    = date("Y-m-d H:i:s");
                    $array["modify_date"]    = date("Y-m-d H:i:s");
                    $array["create_userID"]  = $this->session->userdata('loginuserID');
                    $array["create_roleID"]  = $this->session->userdata('roleID');
                    
                    $this->operationtheatre_m->insert_operationtheatre($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('operationtheatre/index'));
                }
            } else {
    		    $this->data["subview"] = 'operationtheatre/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'operationtheatre/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $operationtheatreID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID          = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$operationtheatreID && (int)$displayID) {
            $operationTheatreEditArray['operationtheatreID'] = $operationtheatreID;
            if($this->data['loginroleID'] == 2) {
                $operationTheatreEditArray['doctorID']   = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $operationTheatreEditArray['patientID']  = $this->data['loginuserID'];
            }

            $this->data['operationtheatre'] = $this->operationtheatre_m->get_single_operationtheatre($operationTheatreEditArray);

            if(inicompute($this->data['operationtheatre'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datetimepicker/css/datetimepicker.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/datetimepicker/js/datetimepicker.js',
                        'assets/inilabs/operationtheatre/index.js',
                    )
                );

                $displayArray                       = $this->_displayManager($displayID);
                $this->data['operationtheatres']    = $this->operationtheatre_m->get_select_operationtheatre_patient('operationtheatre.operationtheatreID, operationtheatre.operation_name, operationtheatre.operation_type, operationtheatre.patientID, patient.name', $displayArray);
                $this->data['patients']             = $this->patient_m->get_select_patient('patientID, name', ['delete_at' => 0]);
                $this->data['doctors']              = $this->user_m->get_select_user('userID, name', ['roleID' => 2, 'status' => 1, 'delete_at' => 0]);
                $this->data['jsmanager']            = ['doctorID' => null, 'assistant_doctor_1' => null, 'assistant_doctor_2' => null];
                if($_POST) {
                    $this->data['jsmanager'] = ['doctorID' => $this->input->post('doctorID'), 'assistant_doctor_1' => $this->input->post('assistant_doctor_1'), 'assistant_doctor_2' => $this->input->post('assistant_doctor_2')];
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'operationtheatre/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array['operation_name'] = $this->input->post('operation_name');
                        $array['operation_type'] = $this->input->post('operation_type');
                        $array['patientID']      = $this->input->post('patientID');
                        $array['operation_date'] = date('Y-m-d H:i:s', strtotime($this->input->post('operation_date')));
                        $array['doctorID']       = $this->input->post('doctorID');
                        $array['assistant_doctor_1'] = $this->input->post('assistant_doctor_1');
                        $array['assistant_doctor_2'] = $this->input->post('assistant_doctor_2');
                        $array["modify_date"]    = date("Y-m-d H:i:s");

                        $this->operationtheatre_m->update_operationtheatre($array, $operationtheatreID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('operationtheatre/index/'.$displayID));
                    }
                } else {
                    $this->data["subview"] = 'operationtheatre/edit';
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
                'assets/inilabs/operationtheatre/view.js'
            ]
        ];

        $operationtheatreID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID          = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$operationtheatreID && (int)$displayID) {
            $this->data['displayID'] = $displayID;
            $operationTheatreDeleteArray['operationtheatreID'] = $operationtheatreID;
            if($this->data['loginroleID'] == 2) {
                $operationTheatreDeleteArray['doctorID']   = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $operationTheatreDeleteArray['patientID']  = $this->data['loginuserID'];
            }

            $operationtheatre = $this->operationtheatre_m->get_single_operationtheatre($operationTheatreDeleteArray);
            if(inicompute($operationtheatre)) {
                $this->data['jsmanager'] = ['operationtheatreID' => $operationtheatre->operationtheatreID];
                $this->data['patient']  = $this->patient_m->get_select_patient('patientID, name', array('patientID'=>$operationtheatre->patientID), TRUE);
                $this->data['doctors']  = pluck($this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0)), 'name', 'userID');
                $this->data['operationtheatre'] = $operationtheatre;
                $this->data["subview"] = 'operationtheatre/view';
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
        if(permissionChecker('operationtheatre_view')) {
            $operationtheatreID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$operationtheatreID) {
                $operationTheatreDeleteArray['operationtheatreID'] = $operationtheatreID;
                if($this->data['loginroleID'] == 2) {
                    $operationTheatreDeleteArray['doctorID']   = $this->data['loginuserID'];
                } elseif($this->data['loginroleID'] == 3) {
                    $operationTheatreDeleteArray['patientID']  = $this->data['loginuserID'];
                }

                $operationtheatre = $this->operationtheatre_m->get_single_operationtheatre($operationTheatreDeleteArray);
                if(inicompute($operationtheatre)) {
                    $this->data['patient']   = $this->patient_m->get_select_patient('patientID, name', array('patientID'=>$operationtheatre->patientID), TRUE);
                    $this->data['doctors']    = pluck($this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0)), 'name', 'userID');
                    $this->data['operationtheatre'] = $operationtheatre;

                    $this->report->reportPDF(['stylesheet' => 'operationtheatremodule.css', 'data' => $this->data, 'viewpath' => 'operationtheatre/printpreview']);
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

    public function sendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('operationtheatre_view')) {
            if($_POST) {
                $rules = $this->mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $operationtheatreID   = $this->input->post('operationtheatreID');
                    if((int)$operationtheatreID) {
                        $operationTheatreDeleteArray['operationtheatreID'] = $operationtheatreID;
                        if($this->data['loginroleID'] == 2) {
                            $operationTheatreDeleteArray['doctorID']   = $this->data['loginuserID'];
                        } elseif($this->data['loginroleID'] == 3) {
                            $operationTheatreDeleteArray['patientID']  = $this->data['loginuserID'];
                        }

                        $operationtheatre = $this->operationtheatre_m->get_single_operationtheatre($operationTheatreDeleteArray);
                        if(inicompute($operationtheatre)) {
                            $this->data['patient']          = $this->patient_m->get_select_patient('patientID, name', array('patientID'=>$operationtheatre->patientID), TRUE);
                            $this->data['doctors']          = pluck($this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0)), 'name', 'userID');
                            $this->data['operationtheatre'] = $operationtheatre;

                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');

                            $this->report->reportSendToMail(['stylesheet' => 'operationtheatremodule.css', 'data' => $this->data, 'viewpath' => 'operationtheatre/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {
                            $retArray['message'] = $this->lang->line('operationtheatre_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('operationtheatre_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('operationtheatre_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('operationtheatre_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    public function delete() 
    {
        $operationtheatreID = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$operationtheatreID && (int)$displayID) {
            $operationTheatreDeleteArray['operationtheatreID'] = $operationtheatreID;
            if($this->data['loginroleID'] == 2) {
                $operationTheatreDeleteArray['doctorID']   = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $operationTheatreDeleteArray['patientID']  = $this->data['loginuserID'];
            }

            $operationtheatre = $this->operationtheatre_m->get_single_operationtheatre($operationTheatreDeleteArray);
            if(inicompute($operationtheatre)) {
                $this->operationtheatre_m->delete_operationtheatre($operationtheatreID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('operationtheatre/index/'.$displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function get_doctor()
    {
        echo "<option value='0'>— ".$this->lang->line('operationtheatre_please_select')." —</option>";
        if($_POST) {
            $doctorID           = $this->input->post('doctorID');
            $assistant_doctor_1 = $this->input->post('assistant_doctor_1');
            if((int)$doctorID) {
                $doctors = $this->user_m->get_select_user('userID, name', array('userID !='=>$doctorID, 'roleID'=> 2, 'status'=> 1, 'delete_at'=> 0));
                if(inicompute($doctors)) {
                    foreach ($doctors as $doctor) {
                        if($assistant_doctor_1 != $doctor->userID) {
                            echo "<option value='".$doctor->userID."'>".$doctor->name."</option>";
                        }
                    }
                }
            }
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'operation_name',
                'label' => $this->lang->line("operationtheatre_operation_name"),
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'operation_type',
                'label' => $this->lang->line("operationtheatre_operation_type"),
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("operationtheatre_patient"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'operation_date',
                'label' => $this->lang->line("operationtheatre_operation_date"),
                'rules' => 'trim|required|max_length[19]|callback_valid_date'
            ),
            array(
                'field' => 'doctorID',
                'label' => $this->lang->line("operationtheatre_doctor"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'assistant_doctor_1',
                'label' => $this->lang->line("operationtheatre_assistant_doctor_1"),
                'rules' => 'trim|numeric|max_length[11]'
            ),
            array(
                'field' => 'assistant_doctor_2',
                'label' => $this->lang->line("operationtheatre_assistant_doctor_2"),
                'rules' => 'trim|numeric|max_length[11]'
            )
        );
        return $rules;
    }

    protected function mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("operationtheatre_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("operationtheatre_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("operationtheatre_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'operationtheatreID',
                'label' => $this->lang->line("operationtheatre_operation_theatre"),
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
}
