<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Birthregister extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('birthregister_m');
        $this->load->model('patient_m');
        $this->load->library('report');
        $language = $this->session->userdata('lang');;
        $this->lang->load('birthregister', $language);
    }

    public function index()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/datetimepicker/css/datetimepicker.css',
            ],
            'js'  => [
                'assets/select2/select2.js',
                'assets/datetimepicker/js/datetimepicker.js',
                'assets/inilabs/birthregister/index.js',
            ]
        ];
        $displayID                  = htmlentities(escapeString($this->uri->segment('3')));
        if ( $displayID == 2 ) {
            $this->data['birthregisters'] = $this->birthregister_m->get_order_by_birthregister([
                'YEAR(date)'  => date('Y'),
                'MONTH(date)' => date('m')
            ]);
        } elseif ( $displayID == 3 ) {
            $this->data['birthregisters'] = $this->birthregister_m->get_order_by_birthregister([ 'YEAR(date)' => date('Y') ]);
        } elseif ( $displayID == 4 ) {
            $this->data['birthregisters'] = $this->birthregister_m->get_birthregister();
        } else {
            $displayID                    = 1;
            $this->data['birthregisters'] = $this->birthregister_m->get_order_by_birthregister([ 'DATE(date)' => date('Y-m-d') ]);
        }
        $this->data['displayID'] = $displayID;

        $this->data['patients'] = $this->patient_m->get_select_patient('patientID, name', [ 'delete_at' => 0 ]);
        if ( permissionChecker('birthregister_add') ) {
            if ( $_POST ) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $this->data["subview"] = 'birthregister/index';
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array                  = [];
                    $array['name']          = $this->input->post('name');
                    $array['father_name']   = $this->input->post('father_name');
                    $array['mother_name']   = $this->input->post('mother_name');
                    $array['gender']        = $this->input->post('gender');
                    $array['date']          = date('Y-m-d H:i:s', strtotime($this->input->post('date')));
                    $array['weight']        = $this->input->post('weight');
                    $array['length']        = $this->input->post('length');
                    $array['patientID']     = $this->input->post('patientID');
                    $array['birth_place']   = $this->input->post('birth_place');
                    $array['nationality']   = $this->input->post('nationality');
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');

                    $this->birthregister_m->insert_birthregister($array);
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('birthregister/index'));
                }
            } else {
                $this->data["subview"] = 'birthregister/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'birthregister/index';
            $this->load->view('_layout_main', $this->data);
        }
    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'name',
                'label' => $this->lang->line("birthregister_name"),
                'rules' => 'trim|required|max_length[40]'
            ],
            [
                'field' => 'father_name',
                'label' => $this->lang->line("birthregister_father_name"),
                'rules' => 'trim|required|max_length[40]'
            ],
            [
                'field' => 'mother_name',
                'label' => $this->lang->line("birthregister_mother_name"),
                'rules' => 'trim|required|max_length[40]'
            ],
            [
                'field' => 'gender',
                'label' => $this->lang->line("birthregister_gender"),
                'rules' => 'trim|max_length[4]|callback_required_no_zero'
            ],
            [
                'field' => 'date',
                'label' => $this->lang->line("birthregister_date"),
                'rules' => 'trim|required|max_length[19]|callback_valid_date|callback_check_future_date'
            ],
            [
                'field' => 'weight',
                'label' => $this->lang->line("birthregister_weight"),
                'rules' => 'trim|max_length[15]'
            ],
            [
                'field' => 'length',
                'label' => $this->lang->line("birthregister_length"),
                'rules' => 'trim|max_length[15]'
            ],
            [
                'field' => 'patientID',
                'label' => $this->lang->line("birthregister_patient"),
                'rules' => 'trim|max_length[11]|numeric'
            ],
            [
                'field' => 'birth_place',
                'label' => $this->lang->line("birthregister_birth_place"),
                'rules' => 'trim|required|max_length[128]'
            ],
            [
                'field' => 'nationality',
                'label' => $this->lang->line("birthregister_nationality"),
                'rules' => 'trim|required|max_length[128]'
            ]
        ];
        return $rules;
    }

    public function edit()
    {
        $birthregisterID = htmlentities(escapeString($this->uri->segment('3')));
        $displayID       = htmlentities(escapeString($this->uri->segment('4')));
        if ( (int) $birthregisterID && (int) $displayID ) {
            $this->data['birthregister'] = $this->birthregister_m->get_single_birthregister([ 'birthregisterID' => $birthregisterID ]);
            if ( inicompute($this->data['birthregister']) ) {
                $this->data['headerassets'] = [
                    'css' => [
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datetimepicker/css/datetimepicker.css',
                    ],
                    'js'  => [
                        'assets/select2/select2.js',
                        'assets/datetimepicker/js/datetimepicker.js',
                        'assets/inilabs/birthregister/index.js',
                    ]
                ];


                if ( $displayID == 2 ) {
                    $this->data['birthregisters'] = $this->birthregister_m->get_order_by_birthregister([
                        'YEAR(date)'  => date('Y'),
                        'MONTH(date)' => date('m')
                    ]);
                } elseif ( $displayID == 3 ) {
                    $this->data['birthregisters'] = $this->birthregister_m->get_order_by_birthregister([ 'YEAR(date)' => date('Y') ]);
                } elseif ( $displayID == 4 ) {
                    $this->data['birthregisters'] = $this->birthregister_m->get_birthregister();
                } else {
                    $displayID                    = 1;
                    $this->data['birthregisters'] = $this->birthregister_m->get_order_by_birthregister([ 'DATE(date)' => date('Y-m-d') ]);
                }
                $this->data['displayID'] = $displayID;

                $this->data['patients'] = $this->patient_m->get_select_patient('patientID, name', [ 'delete_at' => 0 ]);
                if ( $_POST ) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() == false ) {
                        $this->data["subview"] = 'birthregister/edit';
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array                = [];
                        $array['name']        = $this->input->post('name');
                        $array['father_name'] = $this->input->post('father_name');
                        $array['mother_name'] = $this->input->post('mother_name');
                        $array['gender']      = $this->input->post('gender');
                        $array['date']        = date('Y-m-d H:i:s', strtotime($this->input->post('date')));
                        $array['weight']      = $this->input->post('weight');
                        $array['length']      = $this->input->post('length');
                        $array['patientID']   = $this->input->post('patientID');
                        $array['birth_place'] = $this->input->post('birth_place');
                        $array['nationality'] = $this->input->post('nationality');
                        $array["modify_date"] = date("Y-m-d H:i:s");

                        $this->birthregister_m->update_birthregister($array, $birthregisterID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(site_url('birthregister/index/' . $displayID));
                    }
                } else {
                    $this->data["subview"] = 'birthregister/edit';
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
                'assets/inilabs/birthregister/view.js'
            ]
        ];

        $birthregisterID = htmlentities(escapeString($this->uri->segment('3')));
        $displayID       = htmlentities(escapeString($this->uri->segment('4')));
        if ( (int) $birthregisterID && (int) $displayID ) {
            $birthregister = $this->birthregister_m->get_single_birthregister([ 'birthregisterID' => $birthregisterID ]);
            if ( inicompute($birthregister) ) {
                $this->data['jsmanager']     = [ 'birthregisterID' => $birthregister->birthregisterID ];
                $this->data['displayID']     = $displayID;
                $this->data['patient']       = $this->patient_m->get_select_patient('patientID, name',
                    [ 'patientID' => $birthregister->patientID ], true);
                $this->data['birthregister'] = $birthregister;
                $this->data["subview"]       = 'birthregister/view';
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
        $birthregisterID = escapeString($this->uri->segment('3'));
        if ( (int) $birthregisterID ) {
            $birthregister = $this->birthregister_m->get_single_birthregister([ 'birthregisterID' => $birthregisterID ]);
            if ( inicompute($birthregister) ) {
                $this->data['patient']       = $this->patient_m->get_select_patient('patientID, name',
                    [ 'patientID' => $birthregister->patientID ], true);
                $this->data['birthregister'] = $birthregister;

                $this->report->reportPDF([
                    'stylesheet' => 'birthregistermodule.css',
                    'data'       => $this->data,
                    'viewpath'   => 'birthregister/printpreview'
                ]);
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
        if ( permissionChecker('birthregister_view') ) {
            if ( $_POST ) {
                $rules = $this->mail_rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $birthregisterID = $this->input->post('birthregisterID');
                    if ( (int) $birthregisterID ) {
                        $birthregister = $this->birthregister_m->get_single_birthregister([ 'birthregisterID' => $birthregisterID ]);
                        if ( inicompute($birthregister) ) {
                            $this->data['patient']       = $this->patient_m->get_select_patient('patientID, name',
                                [ 'patientID' => $birthregister->patientID ], true);
                            $this->data['birthregister'] = $birthregister;

                            $email   = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');

                            $this->report->reportSendToMail([
                                'stylesheet' => 'birthregistermodule.css',
                                'data'       => $this->data,
                                'viewpath'   => 'birthregister/printpreview',
                                'email'      => $email,
                                'subject'    => $subject,
                                'message'    => $message
                            ]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = true;
                        } else {
                            $retArray['message'] = $this->lang->line('birthregister_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('birthregister_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('birthregister_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('birthregister_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function mail_rules()
    {
        $rules = [
            [
                'field' => 'to',
                'label' => $this->lang->line("birthregister_to"),
                'rules' => 'trim|required|valid_email'
            ],
            [
                'field' => 'subject',
                'label' => $this->lang->line("birthregister_subject"),
                'rules' => 'trim|required'
            ],
            [
                'field' => 'message',
                'label' => $this->lang->line("birthregister_message"),
                'rules' => 'trim'
            ],
            [
                'field' => 'birthregisterID',
                'label' => $this->lang->line("birthregister_birthregister"),
                'rules' => 'trim|required|numeric'
            ]
        ];
        return $rules;
    }

    public function certificate()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/birthregister/certificate.js'
            ]
        ];

        if ( permissionChecker('birthregister_view') ) {
            $birthregisterID = htmlentities(escapeString($this->uri->segment('3')));
            $displayID       = htmlentities(escapeString($this->uri->segment('4')));
            if ( (int) $birthregisterID && (int) $displayID ) {
                $this->data['displayID'] = $displayID;
                $birthregister           = $this->birthregister_m->get_single_birthregister([ 'birthregisterID' => $birthregisterID ]);
                if ( inicompute($birthregister) ) {
                    $this->data['jsmanager']     = [ 'birthregisterID' => $birthregister->birthregisterID ];
                    $this->data['birthregister'] = $birthregister;
                    $this->data["subview"]       = 'birthregister/certificate';
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
        if ( permissionChecker('birthregister_view') ) {
            $birthregisterID = htmlentities(escapeString($this->uri->segment('3')));
            if ( (int) $birthregisterID ) {
                $birthregister = $this->birthregister_m->get_single_birthregister([ 'birthregisterID' => $birthregisterID ]);
                if ( inicompute($birthregister) ) {
                    $this->data['birthregister'] = $birthregister;
                    $this->report->reportPDF([
                        'stylesheet' => 'birthregistercertificatemodule.css',
                        'data'       => $this->data,
                        'viewpath'   => 'birthregister/certificateprintpreview',
                        'designnone' => 0
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

    public function certificatesendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = false;
        if ( permissionChecker('birthregister_view') ) {
            if ( $_POST ) {
                $rules = $this->mail_rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $birthregisterID = $this->input->post('birthregisterID');
                    if ( (int) $birthregisterID ) {
                        $birthregister = $this->birthregister_m->get_single_birthregister([ 'birthregisterID' => $birthregisterID ]);
                        if ( inicompute($birthregister) ) {
                            $this->data['birthregister'] = $birthregister;
                            $email                       = $this->input->post('to');
                            $subject                     = $this->input->post('subject');
                            $message                     = $this->input->post('message');
                            $this->report->reportSendToMail([
                                'stylesheet' => 'birthregistercertificatemodule.css',
                                'data'       => $this->data,
                                'viewpath'   => 'birthregister/certificateprintpreview',
                                'email'      => $email,
                                'subject'    => $subject,
                                'message'    => $message,
                                'designnone' => 0
                            ]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = true;
                        } else {
                            $retArray['message'] = $this->lang->line('birthregister_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('birthregister_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('birthregister_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('birthregister_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    public function delete()
    {
        $birthregisterID = htmlentities(escapeString($this->uri->segment('3')));
        $displayID       = htmlentities(escapeString($this->uri->segment('4')));
        if ( (int) $birthregisterID && (int) $displayID ) {
            $birthregister = $this->birthregister_m->get_single_birthregister([ 'birthregisterID' => $birthregisterID ]);
            if ( inicompute($birthregister) ) {
                $this->birthregister_m->delete_birthregister($birthregisterID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('birthregister/index/' . $displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function required_no_zero( $data )
    {
        if ( $data == '0' ) {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return false;
        }
        return true;
    }

    public function valid_date( $date )
    {
        if ( $date ) {
            if ( strlen($date) < 19 ) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                return false;
            } else {
                $arr = explode("-", $date);
                if ( inicompute($arr) > 2 ) {
                    $dd   = $arr[0];
                    $mm   = $arr[1];
                    $yyyy = explode(" ", $arr[2]);
                    if ( inicompute($yyyy) > 1 ) {
                        $yyyy = $yyyy[0];
                        if ( checkdate($mm, $dd, $yyyy) ) {
                            return true;
                        } else {
                            $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                            return false;
                        }
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is invalid.");
                        return false;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is invalid.");
                    return false;
                }
            }
        }
        return true;
    }

    public function check_future_date( $date )
    {
        $todaydate = strtotime(date('d-m-Y'));
        $date      = strtotime(date('d-m-Y', strtotime($date)));

        if ( $date > $todaydate ) {
            $this->form_validation->set_message("check_future_date", "The %s cann't be set future date.");
            return false;
        }
        return true;
    }
}
