<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription extends Admin_Controller
{
    /*
        1 = OPD
        2 = IPD
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('prescription_m');
        $this->load->model('prescriptionitem_m');
        $this->load->model('patient_m');
        $this->load->model('user_m');
        $this->load->model('designation_m');
        $this->load->model('admission_m');
        $this->load->model('appointment_m');
        $this->load->library('report');

        $language = $this->session->userdata('lang');
        $this->lang->load('prescription', $language);
    }

    protected function rules($counter)
    {
        $rules = array(
            array(
                'field' => 'symptoms',
                'label' => $this->lang->line("prescription_symptoms"),
                'rules' => 'trim|required|max_length[500]'
            ),
            array(
                'field' => 'allergies',
                'label' => $this->lang->line("prescription_allergies"),
                'rules' => 'trim|max_length[500]'
            ),
            array(
                'field' => 'test',
                'label' => $this->lang->line("prescription_test"),
                'rules' => 'trim|max_length[500]'
            ),
            array(
                'field' => 'advice',
                'label' => $this->lang->line("prescription_advice"),
                'rules' => 'trim|max_length[500]'
            ),
            array(
                'field' => 'counter',
                'label' => 'counter',
                'rules' => 'trim|required|numeric|max_length[11]'
            )
        );

        if ($counter > 0) {
            $j = 4;
            for ($i = 1; $i <= $counter; $i++) {
                $rules[$j] = array(
                    'field' => 'medicine_' . $i,
                    'label' => 'medicine ' . $i,
                    'rules' => 'trim|required|max_length[500]'
                );
                $j++;
            }
            $k = $j;

            for ($i = 1; $i <= $counter; $i++) {
                $rules[$k] = array(
                    'field' => 'instruction_' . $i,
                    'label' => 'instruction ' . $i,
                    'rules' => 'trim|required|max_length[500]'
                );
                $k++;
            }
        }
        return $rules;
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
                'assets/inilabs/prescription/index.js',
                'assets/inilabs/prescription/item.js',
            )
        );

        if($this->session->flashdata('prescriptionstatus')) {
            $this->data['activetab']        = false;
        } else {
            $this->data['activetab']        = true;
        }

        $this->data['patientinfo']      = [];
        $displayID                      = htmlentities(escapeString($this->uri->segment(3)));
        $displaytypeID                  = htmlentities(escapeString($this->uri->segment(4)));
        $displayuhID                    = htmlentities(escapeString($this->uri->segment(5)));
        $displayArray                   = $this->_displayManager($displayID, $displaytypeID, $displayuhID);
        $this->data['counter']          = 0;
        $this->data['genders']          = ['0' => '', '1' => $this->lang->line('prescription_male'), '2' => $this->lang->line('prescription_female')];
        $this->data['patienttypes']     = ['1' => $this->lang->line('prescription_opd'), '2' => $this->lang->line('prescription_ipd')];
        $this->data['jsmanager']        = ['displayID' => $this->data['displayID'], ];
        if(permissionChecker('prescription_add')) {
            if($_POST) {
                $this->data['activetab']    = false;
                $this->data['counter'] = $this->input->post('counter');
                $rules = $this->rules($this->data['counter']);
                $this->form_validation->set_rules($rules);
                if($this->form_validation->run()) {
                    $array = array(
                        'patientID'                     => $displayArray['displayuhID'],
                        'patienttypeID'                 => $displayArray['patienttypeID'],
                        'appointmentandadmissionID'     => $displayArray['appointmentandadmissionID'],
                        'visitno'                       => $displayArray['visitno'],
                        'advice'                        => $this->input->post('advice'),
                        'create_date'                   => date("Y-m-d H:i:s"),
                        'modify_date'                   => date("Y-m-d H:i:s"),
                        'create_userID'                 => $this->session->userdata('loginuserID'),
                        'create_roleID'                 => $this->session->userdata('roleID'),
                        'doctorID'                      => $displayArray['doctorID'],
                    );

                    $this->prescription_m->insert_prescription($array);
                    $prescriptionID = $this->db->insert_id();

                    $prescriptionItems = [];
                    if($prescriptionID > 0) {
                        if($this->data['counter'] > 0) {
                            for($i = 1; $i <= $this->data['counter']; $i++) {
                                $prescriptionItems[$i] = array(
                                    'prescriptionID'    => $prescriptionID,
                                    'medicine'          => $this->input->post('medicine_'.$i),
                                    'instruction'       => $this->input->post('instruction_'.$i),
                                );
                            }
                        }
                    }

                    if(inicompute($prescriptionItems)) {
                        $this->prescriptionitem_m->insert_batch_prescriptionitem($prescriptionItems);
                    }

                    if($displayArray['displaytypeID'] == 1) {
                        $this->appointment_m->update_appointment(['symptoms' => $this->input->post('symptoms'), 'allergies' => $this->input->post('allergies'), 'test' => $this->input->post('test'), 'status' => 1], $displayArray['appointmentandadmissionID']);
                    } else {
                        $this->admission_m->update_admission(['symptoms' => $this->input->post('symptoms'), 'allergies' => $this->input->post('allergies'), 'test' => $this->input->post('test'), 'prescriptionstatus' => 1], $displayArray['appointmentandadmissionID']);
                    }

                    $this->session->set_flashdata('success','Success');
                    if(permissionChecker('prescription_view')) {
                        redirect(site_url('prescription/view/'.$prescriptionID.'/'.$displayArray['displayID'].'/'.$displayArray['displaytypeID'].'/'.$displayArray['displayuhID']));
                    } else {
                        redirect(site_url('prescription/index/'.$displayArray['displayID'].'/'.$displayArray['displaytypeID'].'/0'));
                    }
                }
            }
        }

        $this->data["subview"] = 'prescription/index';
        $this->load->view('_layout_main', $this->data);
    }

    public function edit()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/prescription/item.js',
            ]
        ];

        $prescriptionID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        $displaytypeID  = htmlentities(escapeString($this->uri->segment(5)));
        $displayuhID    = htmlentities(escapeString($this->uri->segment(6)));

        if(((int)$prescriptionID && (int)$displayID && (int)$displaytypeID) && ($displayuhID == 0 || (int)$displayuhID > 0)) {
            if(($this->session->flashdata('prescriptionEditDisplayID') == '') || ($this->session->flashdata('prescriptionEditDisplayID') == $displayID)) {
                $this->data['activetab']        = false;
            } else {
                $this->data['activetab']        = true;
            }
            $this->session->set_flashdata('prescriptionEditDisplayID', $displayID);

            $this->data['prescription'] = $this->prescription_m->get_single_prescription(['prescriptionID' => $prescriptionID]);
            if (inicompute($this->data['prescription'])) {
                if($this->data['prescription']->patienttypeID == 0) {
                    $appointQueryArray['appointmentID'] = $this->data['prescription']->appointmentandadmissionID;
                    if($this->data['loginroleID'] == 2) {
                        $appointQueryArray['doctorID'] = $this->data['loginuserID'];
                    } elseif($this->data['loginroleID'] == 3) {
                        $appointQueryArray['patientID'] = $this->data['loginuserID'];
                    }
                    $checker = $this->appointment_m->get_single_appointment($appointQueryArray);
                } else {
                    $admissionQueryArray['admissionID'] = $this->data['prescription']->appointmentandadmissionID;
                    if($this->data['loginroleID'] == 2) {
                        $admissionQueryArray['doctorID'] = $this->data['loginuserID'];
                    } elseif($this->data['loginroleID'] == 3) {
                        $admissionQueryArray['patientID'] = $this->data['loginuserID'];
                    }
                    $checker = $this->admission_m->get_single_admission($admissionQueryArray);
                }

                if(inicompute($checker)) {
                    $displayArray                   = $this->_displayManagerEdit($this->data['prescription'], $displayID, $displaytypeID, $displayuhID, $prescriptionID);
                    $this->data['genders']          = ['0' => '', '1' => $this->lang->line('prescription_male'), '2' => $this->lang->line('prescription_female')];
                    if ($_POST) {
                        $this->data['activetab']        = false;
                        $this->data['counter'] = $this->input->post('counter');
                        $rules = $this->rules($this->data['counter']);
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run()) {
                            $array = array(
                                'advice'                        => $this->input->post('advice'),
                                'modify_date'                   => date("Y-m-d H:i:s"),
                                'create_userID'                 => $this->session->userdata('loginuserID'),
                                'create_roleID'                 => $this->session->userdata('roleID'),
                                'doctorID'                      => $displayArray['doctorID'],
                            );

                            $this->prescription_m->update_prescription($array, $prescriptionID);
                            $prescriptionItems = [];
                            if($prescriptionID > 0) {
                                if($this->data['counter'] > 0) {
                                    for($i = 1; $i <= $this->data['counter']; $i++) {
                                        $prescriptionItems[$i] = array(
                                            'prescriptionID'    => $prescriptionID,
                                            'medicine'          => $this->input->post('medicine_'.$i),
                                            'instruction'       => $this->input->post('instruction_'.$i),
                                        );
                                    }
                                }
                            }

                            if(inicompute($this->data['prescriptionitems'])) {
                                $this->prescriptionitem_m->delete_prescriptionitem_by_prescriptionID($prescriptionID);
                            }

                            if(inicompute($prescriptionItems)) {
                                $this->prescriptionitem_m->insert_batch_prescriptionitem($prescriptionItems);
                            }

                            if($displayArray['patienttypeID'] == 0) {
                                $this->appointment_m->update_appointment(['symptoms' => $this->input->post('symptoms'), 'allergies' => $this->input->post('allergies'), 'test' => $this->input->post('test')], $displayArray['appointmentandadmissionID']);
                            } else {
                                $this->admission_m->update_admission(['symptoms' => $this->input->post('symptoms'), 'allergies' => $this->input->post('allergies'), 'test' => $this->input->post('test')], $displayArray['appointmentandadmissionID']);
                            }

                            $this->session->set_flashdata('success','Success');
                            if(permissionChecker('prescription_view')) {
                                redirect(site_url('prescription/view/'.$prescriptionID.'/'.$displayArray['displayID'].'/'.$displayArray['displaytypeID'].'/'.$displayArray['displayuhID']));
                            } else {
                                redirect(site_url('prescription/index/'.$displayArray['displayID'].'/'.$displayArray['displaytypeID'].'/0'));
                            }
                        } else {
                            $this->data["subview"] = 'prescription/edit';
                            $this->load->view('_layout_main', $this->data);
                        }
                    } else {
                        $this->data["subview"] = 'prescription/edit';
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

    public function delete()
    {
        $prescriptionID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        $displaytypeID  = htmlentities(escapeString($this->uri->segment(5)));
        $displayuhID    = htmlentities(escapeString($this->uri->segment(6)));

        if(((int)$prescriptionID && (int)$displayID && (int)$displaytypeID) && ($displayuhID == 0 || (int)$displayuhID > 0)) {

            $prescription = $this->prescription_m->get_single_prescription(['prescriptionID' => $prescriptionID]);
            if (inicompute($prescription)) {
                if ($prescription->patienttypeID == 0) {
                    $appointQueryArray['appointmentID'] = $prescription->appointmentandadmissionID;
                    if ($this->data['loginroleID'] == 2) {
                        $appointQueryArray['doctorID'] = $this->data['loginuserID'];
                    } elseif ($this->data['loginroleID'] == 3) {
                        $appointQueryArray['patientID'] = $this->data['loginuserID'];
                    }
                    $checker = $this->appointment_m->get_single_appointment($appointQueryArray);
                } else {
                    $admissionQueryArray['admissionID'] = $prescription->appointmentandadmissionID;
                    if ($this->data['loginroleID'] == 2) {
                        $admissionQueryArray['doctorID'] = $this->data['loginuserID'];
                    } elseif ($this->data['loginroleID'] == 3) {
                        $admissionQueryArray['patientID'] = $this->data['loginuserID'];
                    }
                    $checker = $this->admission_m->get_single_admission($admissionQueryArray);
                }

                if (inicompute($checker)) {
                    if ($prescription->patienttypeID == 0) {
                        $this->appointment_m->update_appointment(['status' => 0], $prescription->appointmentandadmissionID);
                    } else {
                        $this->admission_m->update_admission(['prescriptionstatus' => 0], $prescription->appointmentandadmissionID);
                    }

                    $prescriptionItems = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $prescriptionID]);
                    if (inicompute($prescriptionItems)) {
                        $this->prescriptionitem_m->delete_prescriptionitem_by_prescriptionID($prescriptionID);
                    }
                    $this->prescription_m->delete_prescription($prescriptionID);

                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('prescription/index/' . $displayID . '/' . $displaytypeID . '/' . $displayuhID));
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

    public function view()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/prescription/view.js'
            ]
        ];

        $prescriptionID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        $displaytypeID  = htmlentities(escapeString($this->uri->segment(5)));
        $displayuhID    = htmlentities(escapeString($this->uri->segment(6)));

        if(((int)$prescriptionID && (int)$displayID && (int)$displaytypeID) && ($displayuhID == 0 || (int)$displayuhID > 0)) {
            $this->data['prescription'] = $this->prescription_m->get_single_prescription(['prescriptionID' => $prescriptionID]);

            if (inicompute($this->data['prescription'])) {
                if ($this->data['prescription']->patienttypeID == 0) {
                    $appointQueryArray['appointmentID'] = $this->data['prescription']->appointmentandadmissionID;
                    if ($this->data['loginroleID'] == 2) {
                        $appointQueryArray['doctorID'] = $this->data['loginuserID'];
                    } elseif ($this->data['loginroleID'] == 3) {
                        $appointQueryArray['patientID'] = $this->data['loginuserID'];
                    }
                    $checker = $this->appointment_m->get_single_appointment($appointQueryArray);
                } else {
                    $admissionQueryArray['admissionID'] = $this->data['prescription']->appointmentandadmissionID;
                    if ($this->data['loginroleID'] == 2) {
                        $admissionQueryArray['doctorID'] = $this->data['loginuserID'];
                    } elseif ($this->data['loginroleID'] == 3) {
                        $admissionQueryArray['patientID'] = $this->data['loginuserID'];
                    }
                    $checker = $this->admission_m->get_single_admission($admissionQueryArray);
                }

                if (inicompute($checker)) {
                    $this->data['jsmanager'] = ['prescriptionID' => $prescriptionID];
                    $this->data['displayID'] = $displayID;
                    $this->data['displaytypeID'] = $displaytypeID;
                    $this->data['displayuhID'] = $displayuhID;
                    $this->data['patientinfo'] = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                    $this->data['gender'] = [0 => '', 1 => 'M', 2 => 'F'];

                    if ($this->data['prescription']->patienttypeID == 0) {
                        $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                    } else {
                        $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                    }

                    $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                    $this->prescriptionitem_m->order('prescriptionitemID asc');
                    $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $prescriptionID]);

                    $this->data["subview"] = 'prescription/view';
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
        if(permissionChecker('prescription_view')) {
            $prescriptionID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$prescriptionID) {
                $this->data['prescription'] = $this->prescription_m->get_single_prescription(['prescriptionID' => $prescriptionID]);
                if(inicompute($this->data['prescription'])) {
                    $this->data['patientinfo']      = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                    $this->data['gender']           = [0 => '', 1 => 'M', 2 => 'F'];

                    if($this->data['prescription']->patienttypeID == 0) {
                        $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                    } else {
                        $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                    }

                    $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                    $this->prescriptionitem_m->order('prescriptionitemID asc');
                    $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $prescriptionID]);
                   
                   $this->report->reportPDF(['stylesheet' => 'prescriptionmodule.css', 'data' => $this->data, 'viewpath' => 'prescription/printpreview']);
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
        if(permissionChecker('prescription_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $prescriptionID = $this->input->post('prescriptionID');
                    $email      = $this->input->post('to');
                    $subject    = $this->input->post('subject');
                    $message    = $this->input->post('message');

                    if((int)$prescriptionID) {
                        $this->data['prescription'] = $this->prescription_m->get_single_prescription(['prescriptionID' => $prescriptionID]);
                        if(inicompute($this->data['prescription'])) {
                            $this->data['patientinfo']      = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                            $this->data['gender']           = [0 => '', 1 => 'M', 2 => 'F'];

                            if($this->data['prescription']->patienttypeID == 0) {
                                $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                            } else {
                                $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                            }

                            $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                            $this->prescriptionitem_m->order('prescriptionitemID asc');
                            $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $prescriptionID]);
                           
                           $this->report->reportSendToMail(['stylesheet' => 'prescriptionmodule.css', 'data' => $this->data, 'viewpath' => 'prescription/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {
                            $retArray['message'] = $this->lang->line('prescription_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('prescription_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('prescription_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('prescription_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("prescription_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("prescription_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("prescription_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'prescriptionID',
                'label' => $this->lang->line("prescription_prescription"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    private function _displayManagerEdit($prescription, $displayID, $displaytypeID, $displayuhID, $prescriptionID)
    {
        if($displayID == 2) {
            $displayArray['YEAR(prescription.create_date)']      = date('Y');
            $displayArray['MONTH(prescription.create_date)']     = date('m');
        } elseif($displayID == 3) {
            $displayArray['YEAR(prescription.create_date)']      = date('Y');
        } elseif($displayID == 4) {
            $displayArray                                        = [];
        } else {
            $displayArray['DATE(prescription.create_date)']      = date('Y-m-d');
        }

        if($this->data['loginroleID'] == 2) {
            $displayArray['prescription.doctorID'] = $this->data['loginuserID'];
        } elseif($this->data['loginroleID'] == 3) {
            $displayArray                           = [];
            $displayArray['prescription.patientID'] = $this->data['loginuserID'];
        }

        $this->data['prescriptions'] = $this->prescription_m->get_select_appointment_patient('prescription.prescriptionID, prescription.patienttypeID, prescription.appointmentandadmissionID, prescription.create_date, patient.patientID, patient.name, patient.gender, patient.age_day, patient.age_month, patient.age_year', $displayArray);

        if($prescription->patienttypeID == 0) {
            $this->data['patientinfo'] = $this->appointment_m->get_select_appointment_patient('patient.*, appointment.*', ['patient.patientID' => $prescription->patientID, 'appointment.appointmentID' => $prescription->appointmentandadmissionID], true);
        } else {
            $this->data['patientinfo'] = $this->admission_m->get_select_admission_patient('patient.*, admission.*', ['patient.patientID' => $prescription->patientID, 'admission.admissionID' => $prescription->appointmentandadmissionID], true);
        }

        $this->prescriptionitem_m->order('prescriptionitemID asc');
        $this->data['prescriptionitems']    = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $prescriptionID]);
        $this->data['counter']              = inicompute($this->data['prescriptionitems']);
        $appointmentandadmissionID = 0;
        if(inicompute($this->data['patientinfo'])) {
            $appointmentandadmissionID = $prescription->appointmentandadmissionID;
            $user = $this->user_m->get_single_user(['patientID' => $this->data['patientinfo']->patientID]);
            if(inicompute($user)) {
                $this->data['designation'] = $this->designation_m->get_single_designation(['designationID' => $user->designationID]);
            } else {
                $this->data['designation'] = [];
            }
        } else {
            $this->data['designation'] = [];
        }

        $doctorID = (inicompute($this->data['patientinfo']) ? $this->data['patientinfo']->doctorID : 0);

        $this->data['displayID']                        = $displayID;
        $this->data['displaytypeID']                    = $displaytypeID;
        $this->data['displayuhID']                      = $displayuhID;
        $this->data['prescriptionID']                   = $prescriptionID;
        $this->data['appointmentandadmissionID']        = $appointmentandadmissionID;
        $this->data['patienttypeID']                    = $prescription->patienttypeID;
        $this->data['viewpatientID']                    = $prescription->patientID;
        $this->data['doctorID']                         = $doctorID;

        $returnArray = [
            'displayID'                     => $displayID,
            'displaytypeID'                 => $displaytypeID,
            'displayuhID'                   => $displayuhID,
            'prescriptionID'                => $prescriptionID,
            'appointmentandadmissionID'     => $appointmentandadmissionID,
            'patienttypeID'                 => $prescription->patienttypeID,
            'viewpatientID'                 => $prescription->patientID,
            'doctorID'                      => $doctorID,
        ];
        return $returnArray;
    }

    private function _displayManager($displayID, $displaytypeID, $displayuhID)
    {
        if($displayID == '') {
            $displayID = 1;
        } else {
            if($displayID > 4) {
                $displayID = 1;
            }
        }

        if($displayID == 2) {
            $displayArray['YEAR(prescription.create_date)']      = date('Y');
            $displayArray['MONTH(prescription.create_date)']     = date('m');
        } elseif($displayID == 3) {
            $displayArray['YEAR(prescription.create_date)']      = date('Y');
        } elseif($displayID == 4) {
            $displayArray                                        = [];
        } else {
            $displayArray['DATE(prescription.create_date)']      = date('Y-m-d');
        }

        if($this->data['loginroleID'] == 2) {
            $displayArray['prescription.doctorID'] = $this->data['loginuserID'];
        } elseif($this->data['loginroleID'] == 3) {
            $displayArray                           = [];
            $displayArray['prescription.patientID'] = $this->data['loginuserID'];
        }

        $this->data['prescriptions'] = $this->prescription_m->get_select_appointment_patient('prescription.prescriptionID, prescription.patienttypeID, prescription.appointmentandadmissionID, prescription.create_date, patient.patientID, patient.name, patient.gender, patient.age_day, patient.age_month, patient.age_year', $displayArray);

        if($displaytypeID == '') {
            $displaytypeID = 1;
        }

        if($displayuhID == '') {
            $displayuhID = 0;
        }

        if($displaytypeID == 1) {
            $appointmentQueryArray['DATE(appointment.appointmentdate)']     = date('Y-m-d');
            $appointmentQueryArray['appointment.status']                    = 0;
            if($this->data['loginroleID'] == 2) {
                $appointmentQueryArray['appointment.doctorID'] = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $appointmentQueryArray['appointment.patientID'] = $this->data['loginuserID'];
            }
            $this->data['patients'] = $this->appointment_m->get_select_appointment_patient('patient.patientID, patient.name', $appointmentQueryArray);
        } else {
            $admissionQueryArray['admission.status']                = 0;
            $admissionQueryArray['admission.prescriptionstatus']    = 0;
            if($this->data['loginroleID'] == 2) {
                $appointmentQueryArray['admission.doctorID'] = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $appointmentQueryArray['admission.patientID'] = $this->data['loginuserID'];
            }
            $this->data['patients'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name', $admissionQueryArray);
        }

        if($displaytypeID == 1) {
            $patienttypeID = 0;
            $appointmentPatientInfoQueryArray['DATE(appointment.appointmentdate)']  = date('Y-m-d');
            $appointmentPatientInfoQueryArray['patient.patientID']                  = $displayuhID;
            $appointmentPatientInfoQueryArray['appointment.patientID']              = $displayuhID;
            $appointmentPatientInfoQueryArray['appointment.status']                 = 0;
            if($this->data['loginroleID'] == 2) {
                $appointmentPatientInfoQueryArray['appointment.doctorID']   = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $appointmentPatientInfoQueryArray['appointment.patientID']  = $this->data['loginuserID'];
            }
            $this->data['patientinfo'] = $this->appointment_m->get_select_appointment_patient('patient.*, appointment.*', $appointmentPatientInfoQueryArray, true);
            $visitno = (inicompute($this->appointment_m->get_select_appointment_patient('patient.patientID, appointment.appointmentID', ['patient.patientID' => $displayuhID, 'appointment.patientID' => $displayuhID, 'patient.delete_at' => 0, 'appointment.status' => 1]))+1);
        } else {
            $patienttypeID = 1;
            $admissionPatientInfoQueryArray['patient.patientID']            = $displayuhID;
            $admissionPatientInfoQueryArray['admission.patientID']          = $displayuhID;
            $admissionPatientInfoQueryArray['admission.status']             = 0;
            $admissionPatientInfoQueryArray['admission.prescriptionstatus'] = 0;
            if($this->data['loginroleID'] == 2) {
                $admissionPatientInfoQueryArray['admission.doctorID']   = $this->data['loginuserID'];
            } elseif($this->data['loginroleID'] == 3) {
                $admissionPatientInfoQueryArray['admission.patientID']  = $this->data['loginuserID'];
            }
            $this->data['patientinfo'] = $this->admission_m->get_select_admission_patient('patient.*, admission.*', $admissionPatientInfoQueryArray, true);
            $visitno = (inicompute($this->admission_m->get_select_admission_patient('patient.patientID, admission.admissionID', ['patient.patientID' => $displayuhID, 'admission.patientID' => $displayuhID, 'patient.delete_at' => 0, 'admission.status' => 1, 'admission.prescriptionstatus' => 1]))+1);
        }

        $appointmentandadmissionID = 0;
        if(inicompute($this->data['patientinfo'])) {
            if($displaytypeID == 1) {
                $appointmentandadmissionID = $this->data['patientinfo']->appointmentID;
            } else {
                $appointmentandadmissionID = $this->data['patientinfo']->admissionID;
            }

            $user = $this->user_m->get_single_user(['patientID' => $this->data['patientinfo']->patientID]);
            if(inicompute($user)) {
                $this->data['designation'] = $this->designation_m->get_single_designation(['designationID' => $user->designationID]);
            } else {
                $this->data['designation'] = [];
            }
        } else {
            $this->data['designation'] = [];
        }

        $doctorID = (inicompute($this->data['patientinfo']) ? $this->data['patientinfo']->doctorID : 0);

        $this->data['displayID']                        = $displayID;
        $this->data['displaytypeID']                    = $displaytypeID;
        $this->data['displayuhID']                      = $displayuhID;
        $this->data['appointmentandadmissionID']        = $appointmentandadmissionID;
        $this->data['patienttypeID']                    = $patienttypeID;
        $this->data['visitno']                          = $visitno;
        $this->data['doctorID']                         = $doctorID;

        $returnArray = [
            'displayID'                     => $displayID,
            'displaytypeID'                 => $displaytypeID,
            'displayuhID'                   => $displayuhID,
            'appointmentandadmissionID'     => $appointmentandadmissionID,
            'patienttypeID'                 => $patienttypeID,
            'visitno'                       => $visitno,
            'doctorID'                      => $doctorID,
        ];
        return $returnArray;
    }

    public function get_patient()
    {
        $displaytypeID = $this->input->post('patienttypeID');
        if((int)$displaytypeID) {
            if($displaytypeID == 1) {
                $appointmentQueryArray['DATE(appointment.appointmentdate)']     = date('Y-m-d');
                $appointmentQueryArray['appointment.status']                    = 0;
                if($this->data['loginroleID'] == 2) {
                    $appointmentQueryArray['appointment.doctorID'] = $this->data['loginuserID'];
                } elseif($this->data['loginroleID'] == 3) {
                    $appointmentQueryArray['appointment.patientID'] = $this->data['loginuserID'];
                }
                $this->data['patients'] = $this->appointment_m->get_select_appointment_patient('patient.patientID, patient.name', $appointmentQueryArray);
            } else {
                $admissionQueryArray['admission.status']                = 0;
                $admissionQueryArray['admission.prescriptionstatus']    = 0;
                if($this->data['loginroleID'] == 2) {
                    $admissionQueryArray['admission.doctorID'] = $this->data['loginuserID'];
                } elseif($this->data['loginroleID'] == 3) {
                    $admissionQueryArray['admission.patientID'] = $this->data['loginuserID'];
                }
                $this->data['patients'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name', $admissionQueryArray);
            }
            echo '<option value="0">— '.$this->lang->line('prescription_please_select').' —</option>';
            if(inicompute($this->data['patients'])) {
                foreach ($this->data['patients'] as $patient) {
                    echo '<option value="'.$patient->patientID.'">'.$patient->patientID.' - '.$patient->name.'</option>';
                }
            }
        } else {
            echo '<option value="0">— '.$this->lang->line('prescription_please_select').' —</option>';
        }
    }

    public function get_patientinfo()
    {
        if(permissionChecker('prescription_add')) {
            $displayID      = $this->input->post('displayID');
            $displaytypeID  = $this->input->post('displaytypeID');
            $displayuhID    = $this->input->post('displayuhID');
            $this->session->set_flashdata('prescriptionstatus', true);
            echo site_url('prescription/index/'.$displayID.'/'.$displaytypeID.'/'.$displayuhID);
        } else {
            echo site_url('prescription/index/1/1/0');
        }
    }
}