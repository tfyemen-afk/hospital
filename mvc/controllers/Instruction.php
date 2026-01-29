<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instruction extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('instruction_m');
        $this->load->model('patient_m');
        $this->load->model('admission_m');
        $this->load->model('user_m');
        $this->load->model('designation_m');
        $this->load->library('report');

        $language = $this->session->userdata('lang');
        $this->lang->load('instruction', $language);
    }

    public function index()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ],
            'js'  => [
                'assets/select2/select2.js',
                'assets/inilabs/instruction/index.js',
            ]
        ];

        if ( $this->session->flashdata('instructionstatus') && permissionChecker('instruction_add') ) {
            $this->data['activetab'] = false;
        } else {
            $this->data['activetab'] = true;
        }
        $this->data['instructions'] = [];
        $this->data['patientinfo']  = [];
        $admission                  = (object) [ 'admissionID' => 0 ];
        $displayID                  = htmlentities(escapeString($this->uri->segment(3)));
        $displayuhID                = htmlentities(escapeString($this->uri->segment(4)));
        $displayArray               = $this->_displayManager($displayID, $displayuhID);
        $this->data['genders']      = [
            '0' => '',
            '1' => $this->lang->line('instruction_male'),
            '2' => $this->lang->line('instruction_female')
        ];

        $currentPatient['patient.delete_at'] = 0;
        $currentPatient['admission.status']  = 0;
        if ( $this->data['loginroleID'] == 2 ) {
            $currentPatient['admission.doctorID'] = $this->data['loginuserID'];
        } elseif ( $this->data['loginroleID'] == 3 ) {
            $currentPatient['admission.patientID'] = $this->data['loginuserID'];
        }

        $this->data['jsmanager']       = [ 'displayID' => $this->data['displayID'] ];
        $this->data['currentpatients'] = $this->admission_m->get_select_admission_patient('patient.patientID as uhid, patient.name, patient.gender, patient.age_day, patient.age_month, patient.age_year, admission.admissionID, admission.admissiondate',
            $currentPatient);
        if ( permissionChecker('instruction_add') ) {
            if ( $displayArray['displayuhID'] > 0 ) {
                $admissionAddArray['patientID'] = $displayArray['displayuhID'];
                $admissionAddArray['status']    = 0;
                if ( $this->data['loginroleID'] == 2 ) {
                    $admissionAddArray['doctorID'] = $this->data['loginuserID'];
                } elseif ( $this->data['loginroleID'] == 3 ) {
                    $admissionAddArray['patientID'] = $this->data['loginuserID'];
                }

                $admission = $this->admission_m->get_select_admission('admissionID, patientID', $admissionAddArray,
                    true);
                if ( inicompute($admission) ) {
                    $this->data['patientinfo']  = $this->patient_m->get_single_patient([ 'patientID' => $displayArray['displayuhID'] ]);
                    $this->data['instructions'] = $this->instruction_m->get_instruction_with_user([
                        'instruction.patientID'   => $displayArray['displayuhID'],
                        'instruction.admissionID' => $admission->admissionID
                    ]);
                    $this->data['user']         = $this->user_m->get_single_user([ 'patientID' => $displayArray['displayuhID'] ]);
                    if ( inicompute($this->data['patientinfo']) && inicompute($this->data['user']) ) {
                        $this->data['designation'] = $this->designation_m->get_single_designation([ 'designationID' => $this->data['user']->designationID ]);
                    }
                }
            }
            if ( $_POST ) {
                $this->data['activetab'] = false;
                $rules                   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() ) {
                    $array = [
                        'patientID'     => $displayArray['displayuhID'],
                        'admissionID'   => $admission->admissionID,
                        'instruction'   => $this->input->post('instruction'),
                        'create_date'   => date("Y-m-d H:i:s"),
                        'modify_date'   => date("Y-m-d H:i:s"),
                        'create_userID' => $this->session->userdata('loginuserID'),
                        'create_roleID' => $this->session->userdata('roleID')
                    ];

                    $this->instruction_m->insert_instruction($array);
                    $this->session->set_flashdata('success', 'Success');
                    $this->session->set_flashdata('instructionstatus', true);
                    redirect(site_url('instruction/index/' . $displayArray['displayID'] . '/' . $displayArray['displayuhID']));
                }
            }
        }

        $this->data["subview"] = 'instruction/index';
        $this->load->view('_layout_main', $this->data);
    }

    private function _displayManager( $displayID, $displayuhID, $queryStatus = true )
    {
        if ( $displayID == '' ) {
            $displayID = 1;
        } else {
            if ( $displayID > 4 ) {
                $displayID = 1;
            }
        }

        if ( $displayuhID == '' ) {
            $displayuhID = 0;
        }

        if ( $displayID == 2 ) {
            $displayArray['YEAR(admission.create_date)']  = date('Y');
            $displayArray['MONTH(admission.create_date)'] = date('m');
            $displayArray['patient.delete_at']            = 0;
        } elseif ( $displayID == 3 ) {
            $displayArray['YEAR(admission.create_date)'] = date('Y');
            $displayArray['patient.delete_at']           = 0;
        } elseif ( $displayID == 4 ) {
            $displayArray['patient.delete_at'] = 0;
        } else {
            $displayArray['DATE(admission.create_date)'] = date('Y-m-d');
            $displayArray['patient.delete_at']           = 0;
        }

        if ( $this->data['loginroleID'] == 3 ) {
            $displayID                         = 4;
            $displayArray['patient.delete_at'] = 0;
        }

        if ( $queryStatus ) {
            if ( $this->data['loginroleID'] == 2 ) {
                $displayArray['admission.doctorID'] = $this->data['loginuserID'];
            } elseif ( $this->data['loginroleID'] == 3 ) {
                $displayArray['admission.patientID'] = $this->data['loginuserID'];
            }

            $this->admission_m->order('admissiondate desc');
            $this->data['patients'] = $this->admission_m->get_select_admission_patient('patient.patientID as uhid, patient.name, patient.gender, patient.age_day, patient.age_month, patient.age_year, admission.admissionID, admission.admissiondate',
                $displayArray);
        }

        $this->data['displayID']   = $displayID;
        $this->data['displayuhID'] = $displayuhID;

        $returnArray = [
            'displayID'   => $displayID,
            'displayuhID' => $displayuhID,
        ];
        return $returnArray;
    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'instruction',
                'label' => $this->lang->line("instruction_instruction"),
                'rules' => 'trim|required|max_length[800]'
            ]
        ];
        return $rules;
    }

    public function edit()
    {
        $instructionID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID     = htmlentities(escapeString($this->uri->segment(4)));
        $displayuhID   = htmlentities(escapeString($this->uri->segment(5)));
        if ( ( (int) $instructionID && (int) $displayID ) && (int) $displayuhID ) {
            $instructionQueryArray['instruction.instructionID'] = $instructionID;
            $instructionQueryArray['admission.status']          = 0;
            if ( $this->data['loginroleID'] == 2 ) {
                $instructionQueryArray['admission.doctorID'] = $this->data['loginuserID'];
            } elseif ( $this->data['loginroleID'] == 3 ) {
                $instructionQueryArray['instruction.patientID'] = $this->data['loginuserID'];
            }

            $this->data['instruction'] = $this->instruction_m->get_instruction_with_admission($instructionQueryArray,
                true);
            if ( inicompute($this->data['instruction']) ) {
                $this->data['activetab']       = false;
                $displayArray                  = $this->_displayManager($displayID, $displayuhID);
                $this->data['currentpatients'] = $this->admission_m->get_select_admission_patient('patient.patientID as uhid, patient.name, patient.gender, patient.age_day, patient.age_month, patient.age_year, admission.admissionID, admission.admissiondate',
                    [ 'patient.delete_at' => 0, 'admission.status' => 0 ]);
                $this->data['patientinfo']     = $this->patient_m->get_single_patient([ 'patientID' => $this->data['instruction']->patientID ]);
                $this->data['instructions']    = $this->instruction_m->get_instruction_with_user([
                    'instruction.patientID'   => $this->data['instruction']->patientID,
                    'instruction.admissionID' => $this->data['instruction']->admissionID
                ]);
                $this->data['user']            = $this->user_m->get_single_user([ 'patientID' => $this->data['instruction']->patientID ]);
                if ( inicompute($this->data['patientinfo']) && inicompute($this->data['user']) ) {
                    $this->data['designation'] = $this->designation_m->get_single_designation([ 'designationID' => $this->data['user']->designationID ]);
                }

                if ( $_POST ) {
                    $this->data['activetab'] = false;
                    $rules                   = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() ) {
                        $array = [
                            'instruction'   => $this->input->post('instruction'),
                            'modify_date'   => date("Y-m-d H:i:s"),
                            'create_userID' => $this->session->userdata('loginuserID'),
                            'create_roleID' => $this->session->userdata('roleID')
                        ];
                        $this->instruction_m->update_instruction($array, $instructionID);
                        $this->session->set_flashdata('success', 'Success');
                        $this->session->set_flashdata('instructionstatus', true);
                        redirect(site_url('instruction/index/' . $displayArray['displayID'] . '/' . $this->data['instruction']->patientID));
                    }
                }
                $this->data["subview"] = 'instruction/edit';
                $this->load->view('_layout_main', $this->data);

            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete()
    {
        $instructionID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID     = htmlentities(escapeString($this->uri->segment(4)));
        $displayuhID   = htmlentities(escapeString($this->uri->segment(5)));

        if ( ( (int) $instructionID && (int) $displayID ) && (int) $displayuhID ) {
            $instructionQueryArray['instruction.instructionID'] = $instructionID;
            $instructionQueryArray['admission.status']          = 0;
            if ( $this->data['loginroleID'] == 2 ) {
                $instructionQueryArray['admission.doctorID'] = $this->data['loginuserID'];
            } elseif ( $this->data['loginroleID'] == 3 ) {
                $instructionQueryArray['instruction.patientID'] = $this->data['loginuserID'];
            }

            $instruction = $this->instruction_m->get_instruction_with_admission($instructionQueryArray, true);
            if ( inicompute($instruction) ) {
                $this->instruction_m->delete_instruction($instructionID);
                $this->session->set_flashdata('instructionstatus', true);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('instruction/index/' . $displayID . '/' . $displayuhID));
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
                'assets/inilabs/instruction/view.js'
            ]
        ];

        $admissionID = htmlentities(escapeString($this->uri->segment(3)));
        $patientID   = htmlentities(escapeString($this->uri->segment(4)));
        $displayID   = htmlentities(escapeString($this->uri->segment(5)));
        $displayuhID = htmlentities(escapeString($this->uri->segment(6)));

        if ( ( (int) $admissionID && (int) $patientID && (int) $displayID ) && ( $displayuhID == 0 || (int) $displayuhID > 0 ) ) {
            $this->_displayManager($displayID, $displayuhID, false);
            $admissionQueryArray['admissionID'] = $admissionID;
            if ( $this->data['loginroleID'] == 2 ) {
                $admissionQueryArray['doctorID']  = $this->data['loginuserID'];
                $admissionQueryArray['patientID'] = $patientID;
            } elseif ( $this->data['loginroleID'] == 3 ) {
                $admissionQueryArray['patientID'] = $this->data['loginuserID'];
            } else {
                $admissionQueryArray['patientID'] = $patientID;
            }

            $admission = $this->admission_m->get_single_admission($admissionQueryArray);
            if ( inicompute($admission) ) {
                $this->data['admission']    = $admission;
                $this->data['patient']      = $this->patient_m->get_single_patient([ 'patientID' => $patientID ]);
                $this->data['instructions'] = $this->instruction_m->get_instruction_with_user([
                    'instruction.patientID'   => $patientID,
                    'instruction.admissionID' => $admissionID
                ]);
                $this->data['user']         = $this->user_m->get_single_user([ 'patientID' => $patientID ]);
                if ( inicompute($this->data['patient']) && inicompute($this->data['user']) ) {
                    $this->data['jsmanager'] = [ 'admissionID' => $this->data['admission']->admissionID, 'patientID' => $this->data['patient']->patientID ];
                    $this->data['designation'] = $this->designation_m->get_single_designation([ 'designationID' => $this->data['user']->designationID ]);
                }
                $this->data["subview"] = 'instruction/view';
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
        if ( permissionChecker('instruction_view') ) {
            $admissionID = htmlentities(escapeString($this->uri->segment(3)));
            $patientID   = htmlentities(escapeString($this->uri->segment(4)));

            if ( ( (int) $admissionID && (int) $patientID ) ) {
                $admissionQueryArray['admissionID'] = $admissionID;
                if ( $this->data['loginroleID'] == 2 ) {
                    $admissionQueryArray['doctorID']  = $this->data['loginuserID'];
                    $admissionQueryArray['patientID'] = $patientID;
                } elseif ( $this->data['loginroleID'] == 3 ) {
                    $admissionQueryArray['patientID'] = $this->data['loginuserID'];
                } else {
                    $admissionQueryArray['patientID'] = $patientID;
                }

                $admission = $this->admission_m->get_single_admission($admissionQueryArray);
                if ( inicompute($admission) ) {
                    $this->data['admission']    = $admission;
                    $this->data['patient']      = $this->patient_m->get_single_patient([ 'patientID' => $patientID ]);
                    $this->data['instructions'] = $this->instruction_m->get_instruction_with_user([
                        'instruction.patientID'   => $patientID,
                        'instruction.admissionID' => $admissionID
                    ]);
                    $this->data['user']         = $this->user_m->get_single_user([ 'patientID' => $patientID ]);
                    if ( inicompute($this->data['patient']) && inicompute($this->data['user']) ) {
                        $this->data['designation'] = $this->designation_m->get_single_designation([ 'designationID' => $this->data['user']->designationID ]);
                    }

                    $this->report->reportPDF([
                        'stylesheet' => 'instructionmodule.css',
                        'data'       => $this->data,
                        'viewpath'   => 'instruction/printpreview'
                    ]);
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
        $retArray['status']  = false;
        if ( permissionChecker('instruction_view') ) {
            if ( $_POST ) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $admissionID = $this->input->post('admissionID');
                    $patientID   = $this->input->post('patientID');
                    if ( ( (int) $admissionID && (int) $patientID ) ) {
                        $email   = $this->input->post('to');
                        $subject = $this->input->post('subject');
                        $message = $this->input->post('message');

                        $admissionQueryArray['admissionID'] = $admissionID;
                        if ( $this->data['loginroleID'] == 2 ) {
                            $admissionQueryArray['doctorID']  = $this->data['loginuserID'];
                            $admissionQueryArray['patientID'] = $patientID;
                        } elseif ( $this->data['loginroleID'] == 3 ) {
                            $admissionQueryArray['patientID'] = $this->data['loginuserID'];
                        } else {
                            $admissionQueryArray['patientID'] = $patientID;
                        }

                        $admission = $this->admission_m->get_single_admission($admissionQueryArray);
                        if ( inicompute($admission) ) {
                            $this->data['admission']    = $admission;
                            $this->data['patient']      = $this->patient_m->get_single_patient([ 'patientID' => $patientID ]);
                            $this->data['instructions'] = $this->instruction_m->get_instruction_with_user([
                                'instruction.patientID'   => $patientID,
                                'instruction.admissionID' => $admissionID
                            ]);
                            $this->data['user']         = $this->user_m->get_single_user([ 'patientID' => $patientID ]);
                            if ( inicompute($this->data['patient']) && inicompute($this->data['user']) ) {
                                $this->data['designation'] = $this->designation_m->get_single_designation([ 'designationID' => $this->data['user']->designationID ]);
                            }

                            $this->report->reportSendToMail([
                                'stylesheet' => 'instructionmodule.css',
                                'data'       => $this->data,
                                'viewpath'   => 'instruction/printpreview',
                                'email'      => $email,
                                'subject'    => $subject,
                                'message'    => $message
                            ]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = true;
                        } else {
                            $retArray['message'] = $this->lang->line('instruction_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('instruction_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('instruction_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('instruction_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules()
    {
        $rules = [
            [
                'field' => 'to',
                'label' => $this->lang->line("instruction_to"),
                'rules' => 'trim|required|valid_email'
            ],
            [
                'field' => 'subject',
                'label' => $this->lang->line("instruction_subject"),
                'rules' => 'trim|required'
            ],
            [
                'field' => 'message',
                'label' => $this->lang->line("instruction_message"),
                'rules' => 'trim'
            ],
            [
                'field' => 'admissionID',
                'label' => $this->lang->line("instruction_instruction"),
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'patientID',
                'label' => $this->lang->line("instruction_patient"),
                'rules' => 'trim|required|numeric'
            ]
        ];
        return $rules;
    }

    public function get_instruction_url()
    {
        if ( permissionChecker('instruction_add') ) {
            $displayID   = $this->input->post('displayID');
            $displayuhID = $this->input->post('displayuhID');
            $this->session->set_flashdata('instructionstatus', true);
            echo site_url('instruction/index/' . $displayID . '/' . $displayuhID);
        } else {
            echo site_url('instruction/index/1/0');
        }
    }
}