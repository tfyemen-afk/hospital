<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discharge extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('discharge_m');
        $this->load->model('admission_m');
        $this->load->model('patient_m');
        $this->load->model('bed_m');
        $this->load->model('ward_m');
        $this->load->model('room_m');
        $this->load->model('floor_m');
        $this->load->library('report');

        $language = $this->session->userdata('lang');
        $this->lang->load('discharge', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'uhid',
                'label' => $this->lang->line("discharge_name"),
                'rules' => 'trim|required|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'conditionofdischarge',
                'label' => $this->lang->line("discharge_condition_of_discharge"),
                'rules' => 'trim|required|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("discharge_date"),
                'rules' => 'trim|required|max_length[19]|callback_valid_date|callback_check_date'
            ),
            array(
                'field' => 'note',
                'label' => $this->lang->line("discharge_note"),
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
                'assets/inilabs/discharge/index.js',
            )
        );

        $displayID                  = htmlentities(escapeString($this->uri->segment(3)));
        $displayArray               = $this->_displayManager($displayID);
        $this->data['patients']     = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name', ['admission.status' => 0]);
        $this->data['genders']      = [0 => '', 1 => $this->lang->line('discharge_male'), 2 => $this->lang->line('discharge_female')];
        $this->data['conditions']   = [1 => $this->lang->line('discharge_stable'), 2 => $this->lang->line('discharge_almost_stable'), 3 => $this->lang->line('discharge_own_risk'), 4 => $this->lang->line('discharge_unstable')];
        if(permissionChecker('discharge_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run()) {
                    $array['admissionID'] = 0;
                    $admission = $this->admission_m->get_select_admission('*', ['patientID' => $this->input->post('uhid'), 'status' => 0], true);
                    if(inicompute($admission)) {
                        $array['admissionID']   = $admission->admissionID;
                        $this->admission_m->update_admission(['status' => 1], $admission->admissionID);
                        $this->bed_m->update_bed(['patientID' => 0, 'status' => 0], $admission->bedID);
                    }

                    $array['patientID']             = $this->input->post('uhid');
                    $array['conditionofdischarge']  = $this->input->post('conditionofdischarge');
                    $array['date']                  = date('Y-m-d H:i:s', strtotime($this->input->post('date')));
                    $array['note']                  = $this->input->post('note');
                    $array['create_date']           = date("Y-m-d H:i:s");
                    $array['modify_date']           = date("Y-m-d H:i:s");
                    $array['create_userID']         = $this->session->userdata('loginuserID');
                    $array['create_roleID']         = $this->session->userdata('roleID');

                    $this->discharge_m->insert_discharge($array);
                    $dischargeID = $this->db->insert_id();

                    $this->session->set_flashdata('success','Success');
                    if(permissionChecker('discharge_view')) {
                        redirect(site_url('discharge/view/'.$dischargeID.'/'.$displayArray['displayID']));
                    } else {
                        redirect(site_url('discharge/index/'.$displayArray['displayID']));
                    }
                }
            }
        }
        $this->data["subview"] = 'discharge/index';
        $this->load->view('_layout_main', $this->data);
    }

    public function edit()
    {
        $dischargeID    = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        $displayArray   = $this->_displayManager($displayID);

        if(((int)$dischargeID && (int)$displayID)) {
            $this->data['discharge']    = $this->discharge_m->get_single_discharge(['dischargeID' => $dischargeID]);
            $this->data['patients']     = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name', ['admission.status' => 0]);
            $this->data['genders']      = [0 => '', 1 => $this->lang->line('discharge_male'), 2 => $this->lang->line('discharge_female')];
            $this->data['conditions']   = [1 => $this->lang->line('discharge_stable'), 2 => $this->lang->line('discharge_almost_stable'), 3 => $this->lang->line('discharge_own_risk'), 4 => $this->lang->line('discharge_unstable')];
            if (inicompute($this->data['discharge'])) {
                $this->data['editpatient'] = $this->patient_m->get_select_patient('patientID, name', ['patientID' => $this->data['discharge']->patientID], true);
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datetimepicker/css/datetimepicker.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/datetimepicker/js/datetimepicker.js',
                        'assets/inilabs/discharge/index.js',
                    )
                );

                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run()) {
                        if($this->data['discharge']->patientID != $this->input->post('uhid')) {
                            $oldAdmission = $this->admission_m->get_single_admission(['admissionID' => $this->data['discharge']->admissionID]);
                            if(inicompute($oldAdmission)) {
                                $bed = $this->bed_m->get_single_bed(['bedID' => $oldAdmission->bedID]);
                                if($bed->status == 0) {
                                    $this->admission_m->update_admission(['status' => 0], $oldAdmission->admissionID);
                                    $this->bed_m->update_bed(['patientID' => $oldAdmission->patientID, 'status' => 1], $oldAdmission->bedID);
                                } else {
                                    $this->session->set_flashdata('error','You cannot edit or delete this patient. Because this patient bed is not empty. it\' has already fill-up' );
                                    redirect(site_url('discharge/index/'.$displayID));
                                }
                            }
                        }

                        if($this->data['discharge']->patientID != $this->input->post('uhid')) {
                            $array['admissionID'] = 0;
                        }

                        $admission = $this->admission_m->get_select_admission('*', ['patientID' => $this->input->post('uhid'), 'status' => 0], true);
                        if(inicompute($admission)) {
                            $array['admissionID']   = $admission->admissionID;
                            $this->admission_m->update_admission(['status' => 1], $admission->admissionID);
                            $this->bed_m->update_bed(['patientID' => 0, 'status' => 0], $admission->bedID);
                        }

                        $array['patientID']             = $this->input->post('uhid');
                        $array['conditionofdischarge']  = $this->input->post('conditionofdischarge');
                        $array['date']                  = date('Y-m-d H:i:s', strtotime($this->input->post('date')));
                        $array['note']                  = $this->input->post('note');
                        $array['modify_date']           = date("Y-m-d H:i:s");

                        $this->discharge_m->update_discharge($array, $dischargeID);

                        $this->session->set_flashdata('success','Success');
                        if(permissionChecker('discharge_view')) {
                            redirect(site_url('discharge/view/'.$dischargeID.'/'.$displayArray['displayID']));
                        } else {
                            redirect(site_url('discharge/index/'.$displayArray['displayID']));
                        }
                    } else {
                        $this->data["subview"] = 'discharge/edit';
                        $this->load->view('_layout_main', $this->data);
                    }
                } else {
                    $this->data["subview"] = 'discharge/edit';
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
        $dischargeID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));

        if (((int)$dischargeID && (int)$displayID)) {
            $discharge = $this->discharge_m->get_single_discharge(['dischargeID' => $dischargeID]);
            if (inicompute($discharge)) {
                $admission = $this->admission_m->get_single_admission(['admissionID' => $discharge->admissionID]);
                if(inicompute($admission)) {
                    $bed = $this->bed_m->get_single_bed(['bedID' => $admission->bedID]);
                    if($bed->status == 0) {
                        $this->admission_m->update_admission(['status' => 0], $admission->admissionID);
                        $this->bed_m->update_bed(['patientID' => $admission->patientID, 'status' => 1], $admission->bedID);
                    } else {
                        $this->session->set_flashdata('error','You cannot edit or delete this patient. Because this patient bed is not empty. it\' has already fill-up' );
                        redirect(site_url('discharge/index/'.$displayID));
                    }
                }

                $this->discharge_m->delete_discharge($dischargeID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('discharge/index/'.$displayID));
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
                'assets/inilabs/discharge/view.js'
            ]
        ];

        $dischargeID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));

        if (((int)$dischargeID && (int)$displayID)) {
            $this->data['dischargeID']  = $dischargeID;
            $this->data['displayID']    = $displayID;
            $this->data['discharge'] = $this->discharge_m->get_single_discharge(['dischargeID' => $dischargeID]);
            if (inicompute($this->data['discharge'])) {
                $this->data['patient'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name, patient.age_day, patient.age_month, patient.age_year, patient.gender, admission.admissiondate, admission.wardID, admission.bedID', ['patient.patientID' => $this->data['discharge']->patientID, 'admissionID' =>  $this->data['discharge']->admissionID], true);
                if(inicompute($this->data['patient'])) {
                    $this->data['jsmanager']    = ['dischargeID' => $dischargeID];
                    $this->data['genders']      = [0 => '', 1 => $this->lang->line('discharge_male'), 2 => $this->lang->line('discharge_female')];
                    $this->data['conditions']   = [1 => $this->lang->line('discharge_stable'), 2 => $this->lang->line('discharge_almost_stable'), 3 => $this->lang->line('discharge_own_risk'), 4 => $this->lang->line('discharge_unstable')];
                    $this->data['ward'] = $this->ward_m->get_single_ward(['wardID' => $this->data['patient']->wardID]);
                    if(inicompute($this->data['ward'])) {
                        $this->data['room'] = $this->room_m->get_single_room(['roomID' => $this->data['ward']->roomID]);
                    } else {
                        $this->data['room'] = [];
                    }
                    if(inicompute($this->data['ward'])) {
                        $this->data['floor'] = $this->floor_m->get_single_floor(['floorID' => $this->data['ward']->floorID]);
                    } else {
                        $this->data['floor'] = [];
                    }
                    $this->data['bed'] = $this->bed_m->get_single_bed(['bedID' => $this->data['patient']->bedID]);
                    $this->data["subview"] = 'discharge/view';
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

    public function printpreview()
    {
        if(permissionChecker('discharge_view')) {
            $dischargeID = htmlentities(escapeString($this->uri->segment(3)));
            if (((int)$dischargeID)) {
                $this->data['discharge']   = $this->discharge_m->get_single_discharge(['dischargeID' => $dischargeID]);
                if (inicompute($this->data['discharge'])) {
                    $this->data['patient'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name, patient.age_day, patient.age_month, patient.age_year, patient.gender, admission.admissiondate, admission.wardID, admission.bedID', ['patient.patientID' => $this->data['discharge']->patientID, 'admissionID' =>  $this->data['discharge']->admissionID], true);
                    if(inicompute($this->data['patient'])) {
                        $this->data['genders']      = [0 => '', 1 => $this->lang->line('discharge_male'), 2 => $this->lang->line('discharge_female')];
                        $this->data['conditions']   = [1 => $this->lang->line('discharge_stable'), 2 => $this->lang->line('discharge_almost_stable'), 3 => $this->lang->line('discharge_own_risk'), 4 => $this->lang->line('discharge_unstable')];
                        $this->data['ward'] = $this->ward_m->get_single_ward(['wardID' => $this->data['patient']->wardID]);
                        if(inicompute($this->data['ward'])) {
                            $this->data['room'] = $this->room_m->get_single_room(['roomID' => $this->data['ward']->roomID]);
                        } else {
                            $this->data['room'] = [];
                        }
                        if(inicompute($this->data['ward'])) {
                            $this->data['floor'] = $this->floor_m->get_single_floor(['floorID' => $this->data['ward']->floorID]);
                        } else {
                            $this->data['floor'] = [];
                        }
                        $this->data['bed'] = $this->bed_m->get_single_bed(['bedID' => $this->data['patient']->bedID]);
                        
                        $this->report->reportPDF(['stylesheet' => 'dischargemodule.css', 'data' => $this->data, 'viewpath' => 'discharge/printpreview', 'pagetype'=> 'a4', 'pagetype'=> 'landscape']);
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
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function sendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('discharge_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $dischargeID = $this->input->post('dischargeID');
                    $email       = $this->input->post('to');
                    $subject     = $this->input->post('subject');
                    $message     = $this->input->post('message');
                    if (((int)$dischargeID)) {
                        $this->data['discharge']   = $this->discharge_m->get_single_discharge(['dischargeID' => $dischargeID]);
                        if (inicompute($this->data['discharge'])) {
                            $this->data['patient'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name, patient.age_day, patient.age_month, patient.age_year, patient.gender, admission.admissiondate, admission.wardID, admission.bedID', ['patient.patientID' => $this->data['discharge']->patientID, 'admissionID' =>  $this->data['discharge']->admissionID], true);
                            if(inicompute($this->data['patient'])) {
                                $this->data['genders']      = [0 => '', 1 => $this->lang->line('discharge_male'), 2 => $this->lang->line('discharge_female')];
                                $this->data['conditions']   = [1 => $this->lang->line('discharge_stable'), 2 => $this->lang->line('discharge_almost_stable'), 3 => $this->lang->line('discharge_own_risk'), 4 => $this->lang->line('discharge_unstable')];
                                $this->data['ward'] = $this->ward_m->get_single_ward(['wardID' => $this->data['patient']->wardID]);
                                if(inicompute($this->data['ward'])) {
                                    $this->data['room'] = $this->room_m->get_single_room(['roomID' => $this->data['ward']->roomID]);
                                } else {
                                    $this->data['room'] = [];
                                }
                                if(inicompute($this->data['ward'])) {
                                    $this->data['floor'] = $this->floor_m->get_single_floor(['floorID' => $this->data['ward']->floorID]);
                                } else {
                                    $this->data['floor'] = [];
                                }
                                $this->data['bed'] = $this->bed_m->get_single_bed(['bedID' => $this->data['patient']->bedID]);
                                
                                $this->report->reportSendToMail(['stylesheet' => 'dischargemodule.css', 'data' => $this->data, 'viewpath' => 'discharge/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message, 'pagetype'=> 'a4', 'pagetype'=> 'landscape']);
                                $retArray['message'] = "Success";
                                $retArray['status']  = TRUE;
                            } else {
                                $retArray['message'] = $this->lang->line('discharge_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('discharge_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('discharge_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('discharge_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('discharge_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("discharge_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("discharge_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("discharge_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'dischargeID',
                'label' => $this->lang->line("discharge_discharge"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    private function _displayManager($displayID)
    {
        if($displayID == '') {
            $displayID = 1;
        } else {
            if($displayID > 4) {
                $displayID = 1;
            }
        }

        if($displayID == 2) {
            $displayArray['YEAR(discharge.date)']      = date('Y');
            $displayArray['MONTH(discharge.date)']     = date('m');
        } elseif($displayID == 3) {
            $displayArray['YEAR(discharge.date)']      = date('Y');
        } elseif($displayID == 4) {
            $displayArray                              = [];
        } else {
            $displayArray['DATE(discharge.date)']      = date('Y-m-d');
        }

        $this->data['discharges'] = $this->discharge_m->get_select_discharge_patient('discharge.dischargeID, discharge.date, patient.patientID, patient.name, patient.gender', $displayArray);

        $this->data['displayID'] = $displayID;
        $returnArray = [
            'displayID' => $displayID,
        ];
        return $returnArray;
    }

    public function required_no_zero($data)
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return false;
        }
        return true;
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

    public function check_date()
    {
        $method = htmlentities(escapeString($this->uri->segment(2)));
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if(($method == 'edit') && (int)$id) {
            $discharge = $this->discharge_m->get_single_discharge(['dischargeID' => $id]);
            if(inicompute($discharge)) {
                if($this->input->post('uhid') == $discharge->patientID) {
                    $admission = $this->admission_m->get_select_admission('*', ['admissionID' => $discharge->admissionID], true);
                } else {
                    $admission = $this->admission_m->get_select_admission('*', ['patientID' => $this->input->post('uhid'), 'status' => 0], true);
                }
                if(inicompute($admission)) {
                    $admissionDate = $admission->admissiondate;
                    $date = $this->input->post('date');
                    if(strtotime($date) < strtotime($admissionDate)) {
                        $this->form_validation->set_message('check_date', 'This %s is shorter than the date of admission.');
                        return false;
                    }
                    return true;
                } else {
                    $this->form_validation->set_message('check_date', 'This Patient is not admitted.');
                    return false;
                }
            } else {
                $this->form_validation->set_message('check_date', 'Discharge is does not found.');
                return false;
            }
        } else {
            if(!empty($this->input->post('uhid'))) {
                $admission = $this->admission_m->get_select_admission('*', ['patientID' => $this->input->post('uhid'), 'status' => 0], true);
                if(inicompute($admission)) {
                    $admissionDate = $admission->admissiondate;
                    $date = $this->input->post('date');
                    if(strtotime($date) < strtotime($admissionDate)) {
                        $this->form_validation->set_message('check_date', 'This %s is shorter than the date of admission.');
                        return false;
                    }
                    return true;
                } else {
                    $this->form_validation->set_message('check_date', 'This Patient is not admitted.');
                    return false;
                }
            }
            return true;
        }
    }
}
