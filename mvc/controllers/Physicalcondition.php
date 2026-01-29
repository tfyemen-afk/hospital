<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Physicalcondition extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tpa_m');
        $this->load->model('user_m');
        $this->load->model('patient_m');
        $this->load->model('appointment_m');
        $this->load->model('ward_m');
        $this->load->model('bed_m');
        $this->load->model('heightweightbp_m');
        $this->load->model('admission_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('physicalcondition', $language);
    }

    protected function rules($rulesArray)
    {
        $rules = array(
            array(
                'field' => 'patienttypeID',
                'label' => $this->lang->line("physicalcondition_patient_type"),
                'rules' => 'trim|required|max_length[1]'
            ),
            array(
                'field' => 'uhid',
                'label' => $this->lang->line("physicalcondition_uhid"),
                'rules' => 'trim|required|max_length[11]|callback_required_no_zero|callback_unique_heightweightbp'
            ),
            array(
                'field' => 'height',
                'label' => $this->lang->line("physicalcondition_height"),
                'rules' => 'trim|required|numeric|max_length[3]'
            ),
            array(
                'field' => 'weight',
                'label' => $this->lang->line("physicalcondition_weight"),
                'rules' => 'trim|required|numeric|max_length[3]'
            ),
            array(
                'field' => 'bp',
                'label' => $this->lang->line("physicalcondition_bp"),
                'rules' => 'trim|required|max_length[10]'
            )
        );

        if(isset($rulesArray['patienttypeID']) && ($rulesArray['patienttypeID'] == 1)) {
            $rules[] =  array(
                'field' => 'date',
                'label' => $this->lang->line("physicalcondition_date"),
                'rules' => 'trim|required|min_length[19]|max_length[19]|callback_valid_date'
            );
        }

        return $rules;
    }

    private function phaysicalConditionForDoctor($queryArray)
    {
        $physicalconditions = $this->heightweightbp_m->get_select_heightweightbp_with_patient('heightweightbp.*, patient.name', $queryArray);
        if($this->data['loginroleID'] == 2) {
            $patientArray   = [];
            $appointments   = $this->appointment_m->get_select_appointment('patientID, appointmentID', ['doctorID' => $this->data['loginuserID'], 'DATE(appointment.appointmentdate)' => date('Y-m-d'), 'status' => 0]);
            $admissions     = $this->admission_m->get_select_admission('patientID, admissionID', ['doctorID' => $this->data['loginuserID'], 'status' => 0]);

            if(inicompute($appointments)) {
                foreach ($appointments as $appointment) {
                    $patientArray[$appointment->patientID][$appointment->appointmentID] = $appointment->patientID;
                }
            }

            if(inicompute($admissions)) {
                foreach ($admissions as $admission) {
                    $patientArray[$admission->patientID][$admission->admissionID] = $admission->patientID;
                }
            }

            $physicalConditionForDoctor = [];
            if(inicompute($physicalconditions)) {
                $j = 0;
                foreach ($physicalconditions as $physicalConditionEx) {
                    if($this->_in_checker($physicalConditionEx->patientID, $physicalConditionEx->appointmentandadmissionID, $patientArray)) {
                        $physicalConditionForDoctor[$j] = $physicalConditionEx;
                        $j++;
                    }
                }
            }
            return $physicalConditionForDoctor;
        }
        return $physicalconditions;
    }

    private function _in_checker($patientID, $appointmentAndAdmissionID, $arrays)
    {
        if(isset($arrays[$patientID][$appointmentAndAdmissionID])) {
            return true;
        }
        return false;

    }

    private function phaysicalConditionForDoctorEdit($queryArray)
    {
        $physicalcondition = $this->heightweightbp_m->get_single_heightweightbp($queryArray);
        if($this->data['loginroleID'] == 2) {
            if(inicompute($physicalcondition)) {
                if($physicalcondition->patienttypeID == 0) {
                    $appointment = $this->appointment_m->get_select_appointment('patientID',
                        [
                            'doctorID' => $this->data['loginuserID'],
                            'DATE(appointmentdate)' => date('Y-m-d'),
                            'status' => 0,
                            'patientID' => $physicalcondition->patientID,
                            'appointmentID' => $physicalcondition->appointmentandadmissionID,
                        ]
                    );

                    if(inicompute($appointment)) {
                        return $physicalcondition;
                    } else {
                        return [];
                    }
                } else {
                    $admission = $this->admission_m->get_select_admission('patientID',
                        [
                            'doctorID'      => $this->data['loginuserID'],
                            'status'        => 0,
                            'patientID'     => $physicalcondition->patientID,
                            'admissionID'   => $physicalcondition->appointmentandadmissionID,
                        ]
                    );

                    if(inicompute($admission)) {
                        return $physicalcondition;
                    } else {
                        return [];
                    }
                }
            } else {
                return [];
            }
        } else {
            return $physicalcondition;
        }
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
                'assets/inilabs/physicalcondition/index.js'
            )
        );

        if($this->data['loginroleID'] == 3) {
            $queryArray['heightweightbp.patientID'] = $this->data['loginuserID'];
        } else {
            $queryArray['Date(heightweightbp.date)']       = date('Y-m-d');
        }

        $this->data['jsmanager'] = ['startDate' => date('d-m-y h:i A'), 'patienttypeID' => '0'];
        if($this->input->post('patienttypeID') == 1) {
            if($this->data['loginroleID'] == 2) {
                $queryAppointmentArray['admission.doctorID']     = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $queryAppointmentArray['admission.patientID']     = $this->data['loginuserID'];
            }

            $queryAppointmentArray['admission.status'] = 0;
            $this->data['appointments'] = $this->admission_m->get_admission_patient($queryAppointmentArray);
        } else {
            if($this->data['loginroleID'] == 2) {
                $queryAppointmentArray['appointment.doctorID']     = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $queryAppointmentArray['appointment.patientID']     = $this->data['loginuserID'];
            }

            $queryAppointmentArray['DATE(appointment.appointmentdate)'] = date('Y-m-d');
            $queryAppointmentArray['appointment.status']                = 0;
            $this->data['appointments'] = $this->appointment_m->get_appointment_patient($queryAppointmentArray);
        }

        $this->data['physicalconditions'] = $this->phaysicalConditionForDoctor($queryArray);

        if(permissionChecker('physicalcondition_add')) {
            if($_POST) {
                $this->data['jsmanager']['patienttypeID'] = $this->input->post('patienttypeID');
                $rulesArray = ['patienttypeID' => $this->input->post('patienttypeID')];
                $rules  = $this->rules($rulesArray);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'physicalcondition/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $physicalconditionArray['patienttypeID']                = $this->input->post('patienttypeID');
                    $physicalconditionArray['patientID']                    = $this->input->post('uhid');
                    $physicalconditionArray['height']                       = $this->input->post('height');
                    $physicalconditionArray['weight']                       = $this->input->post('weight');
                    $physicalconditionArray['bp']                           = $this->input->post('bp');
                    $physicalconditionArray['appointmentandadmissionID']    = $this->_get_appointment_and_admisionID($this->input->post('patienttypeID'), $this->input->post('uhid'));

                    if($this->input->post('patienttypeID') == 1) {
                        $physicalconditionArray['date'] = date('Y-m-d H:i:s',strtotime($this->input->post('date')));
                    } else {
                        $physicalconditionArray['date'] = date('Y-m-d H:i:s');
                    }

                    $this->heightweightbp_m->insert_heightweightbp($physicalconditionArray);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('physicalcondition/index'));
                }
            } else {
                $this->data["subview"] = 'physicalcondition/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'physicalcondition/index';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() 
    {
        $physicalconditionID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$physicalconditionID) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/datetimepicker/css/datetimepicker.css',
                ),
                'js' => array(
                    'assets/select2/select2.js',
                    'assets/datetimepicker/js/datetimepicker.js',
                    'assets/inilabs/physicalcondition/index.js'
                )
            );

            $queryArray['heightweightbpID']     = $physicalconditionID;
            if($this->data['loginroleID'] == 3) {
                $queryArray['heightweightbp.patientID'] = $this->data['loginuserID'];
            } else {
                $queryArray['Date(date)']           = date('Y-m-d');
            }
            
            $this->data['physicalcondition']  = $this->phaysicalConditionForDoctorEdit($queryArray);

            if(inicompute($this->data['physicalcondition'])) {
                $this->data['jsmanager'] = ['startDate' => date('d-m-y h:i A'), 'patienttypeID' => $this->data['physicalcondition']->patienttypeID];
                if($this->data['loginroleID'] == 3) {
                    $queryListArray['heightweightbp.patientID'] = $this->data['loginuserID'];
                } else {
                    $queryListArray['Date(date)']           = date('Y-m-d');
                }

                $this->data['physicalconditions']   = $this->phaysicalConditionForDoctor($queryListArray);
                if($_POST) {
                    $rulesArray = ['patienttypeID' => $this->data['physicalcondition']->patienttypeID];
                    $rules  = $this->rules($rulesArray);
                    unset($rules[0], $rules[1]);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'physicalcondition/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $physicalconditionArray['height']   = $this->input->post('height');
                        $physicalconditionArray['weight']   = $this->input->post('weight');
                        $physicalconditionArray['bp']       = $this->input->post('bp');

                        if($this->input->post('patienttypeID') == 1) {
                            $physicalconditionArray['date'] = date('Y-m-d H:i:s',strtotime($this->input->post('date')));
                        } else {
                            if($this->data['loginroleID'] != 3) {
                                $physicalconditionArray['date'] = date('Y-m-d H:i:s');
                            }
                        }

                        $this->heightweightbp_m->update_heightweightbp($physicalconditionArray, $physicalconditionID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('physicalcondition/index'));
                    }
                } else {
                    $this->data["subview"] = 'physicalcondition/edit';
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
        $physicalconditionID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$physicalconditionID) {
            $queryArray['heightweightbpID']     = $physicalconditionID;
            if($this->data['loginroleID'] == 3) {
                $queryArray['heightweightbp.patientID'] = $this->data['loginuserID'];
            } else {
                $queryArray['Date(date)']           = date('Y-m-d');
            }

            $physicalcondition  = $this->phaysicalConditionForDoctorEdit($queryArray);

            if(inicompute($physicalcondition)) {
                $deletePermission = $this->_deletePermission($physicalcondition->patienttypeID, $physicalcondition->patientID, $physicalcondition->appointmentandadmissionID);
                if($deletePermission) {
                    $this->heightweightbp_m->delete_heightweightbp($physicalconditionID);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('physicalcondition/index'));
                } else {
                    $this->session->set_flashdata('error','At lest one record needed for this patient.');
                    redirect(site_url('physicalcondition/index'));
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
        $heightweightbpID = $this->input->post('heightweightbpID');
        if((int)$heightweightbpID) {

            $queryArray['heightweightbpID']     = $heightweightbpID;
            if($this->data['loginroleID'] == 3) {
                $queryArray['heightweightbp.patientID'] = $this->data['loginuserID'];
            } else {
                $queryArray['Date(date)']           = date('Y-m-d');
            }

            $heightweightbp  = $this->phaysicalConditionForDoctorEdit($queryArray);
            if(inicompute($heightweightbp)) {
                $patient = $this->patient_m->get_select_patient('patientID, name', array('patientID' => $heightweightbp->patientID), true);
                echo '<div class="profile-view-dis">';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('physicalcondition_uhid').'</span>: '.(inicompute($patient) ? $patient->patientID : '').'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('physicalcondition_name').'</span>: '.(inicompute($patient) ? $patient->name : '').'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('physicalcondition_date').'</span>: '.app_datetime($heightweightbp->date).'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('physicalcondition_height').'</span>: '.$heightweightbp->height.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('physicalcondition_weight').'</span>: '.$heightweightbp->weight.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('physicalcondition_bp').'</span>: '.$heightweightbp->bp.'</p>';
                    echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="error-card">';
                    echo '<div class="error-title-block">';
                        echo '<h1 class="error-title">404</h1>';
                        echo '<h2 class="error-sub-title"> Sorry, page not found </h2>';
                    echo '</div>';
                    echo '<div class="error-container">';
                        echo '<a class="btn btn-primary" href="'.site_url('dashboard/index').'">';
                        echo '<i class="fa fa-angle-left"></i> Back to Dashboard</a>';
                    echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="error-card">';
                echo '<div class="error-title-block">';
                    echo '<h1 class="error-title">404</h1>';
                    echo '<h2 class="error-sub-title"> Sorry, page not found </h2>';
                echo '</div>';
                echo '<div class="error-container">';
                    echo '<a class="btn btn-primary" href="'.site_url('dashboard/index').'">';
                    echo '<i class="fa fa-angle-left"></i> Back to Dashboard</a>';
                echo '</div>';
            echo '</div>';
        }
    }

    private function _get_appointment_and_admisionID($patienttypeID, $patientID)
    {
        $ret = 0;
        if($patienttypeID == 1) {
            $admission    = $this->admission_m->get_single_admission(array('patientID'=> $patientID,'status' => 0));
            if(inicompute($admission)) {
                $ret = $admission->admissionID;
            }
        } else {
            $appointment  = $this->appointment_m->get_single_appointment(array('patientID'=> $patientID, 'DATE(appointmentdate)'=> date('Y-m-d'), 'status' => 0));
            if(inicompute($appointment)) {
                $ret = $appointment->appointmentID;
            }
        }
        return $ret;
    }

    public function getpatient()
    {
        if(permissionChecker('physicalcondition_add')) {
            echo "<option value='0'>".'— '.$this->lang->line('physicalcondition_please_select').' —'."</option>";
            if($_POST) {
                if($this->input->post('patienttypeID') == 1) {
                    if($this->data['loginroleID'] == 2) {
                        $queryAppointmentArray['admission.doctorID']     = $this->data['loginuserID'];
                    } elseif($this->data['loginroleID'] == 3) {
                        $queryAppointmentArray['admission.patientID']     = $this->data['loginuserID'];
                    }
                    $queryAppointmentArray['admission.status'] = 0;
                    $patients = $this->admission_m->get_admission_patient($queryAppointmentArray);
                } else {
                    if($this->data['loginroleID'] == 2) {
                        $queryAppointmentArray['appointment.doctorID']     = $this->data['loginuserID'];
                    } elseif($this->data['loginroleID'] == 3) {
                        $queryAppointmentArray['appointment.patientID']     = $this->data['loginuserID'];
                    }
                    $queryAppointmentArray['DATE(appointment.appointmentdate)'] = date('Y-m-d');
                    $queryAppointmentArray['appointment.status']                = 0;
                    $patients = $this->appointment_m->get_appointment_patient($queryAppointmentArray);
                }

                if(inicompute($patients)) {
                    foreach ($patients as $patient) {
                        echo "<option value='".$patient->ppatientID."'>".$patient->ppatientID.' - '.$patient->name."</option>";
                    }
                }
            }
        }
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
                            if(strtotime(date('d-m-Y')) > strtotime($date)) {
                                $this->form_validation->set_message("valid_date", "The %s can not take past date");
                                return FALSE;
                            }
                            return TRUE;
                        } else {
                            $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                            return FALSE; 
                        }
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is invalid");
                        return FALSE;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is invalid");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function unique_heightweightbp()
    {
        $physicalconditionID   = htmlentities(escapeString($this->uri->segment(3)));
        if($physicalconditionID) {
            return true;
        } else {
            if($this->input->post('patienttypeID') == 0) {
                $physicalcondition = $this->heightweightbp_m->get_single_heightweightbp(array('patientID' => $this->input->post('uhid'), 'patienttypeID' => 0, 'DATE(date)' => date('Y-m-d')));
                if(inicompute($physicalcondition)) {
                    $this->form_validation->set_message('unique_heightweightbp', 'This patient information already exist. please edit this.');
                    return false;
                }
                return true;
            }
            return true;
        }
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }

    private function _deletePermission($patienttypeID, $patient, $appointmentoradmissionID)
    {
        $appointment = $this->heightweightbp_m->get_order_by(array('patienttypeID' => $patienttypeID, 'patientID' => $patient, 'appointmentandadmissionID' => $appointmentoradmissionID));
        if(inicompute($appointment) > 1) {
            return true;
        }
        return false;
    }
}
