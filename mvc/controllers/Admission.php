<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admission extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tpa_m');
        $this->load->model('user_m');
        $this->load->model('patient_m');
        $this->load->model('admission_m');
        $this->load->model('appointment_m');
        $this->load->model('designation_m');
        $this->load->model('ward_m');
        $this->load->model('bed_m');
        $this->load->model('heightweightbp_m');
        $this->load->model('instruction_m');
        $this->load->model('prescription_m');
        $this->load->model('prescriptionitem_m');
        $this->load->library('report');
        $this->load->library('mail');

        $language = $this->session->userdata('lang');
        $this->lang->load('admission', $language);
    }

    public function index()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/datetimepicker/css/datetimepicker.css',
                'assets/datepicker/dist/css/bootstrap-datepicker.css',
            ],
            'js'  => [
                'assets/select2/select2.js',
                'assets/datetimepicker/js/datetimepicker.js',
                'assets/datepicker/dist/js/bootstrap-datepicker.js',
                'assets/inilabs/admission/index.js'
            ]
        ];

        $displayDate           = htmlentities(escapeString($this->uri->segment(3)));
        $displayDoctorID       = htmlentities(escapeString($this->uri->segment(4)));
        $admissionDisplayArray = $this->_admissionDisplayArrayGenerate($displayDate, $displayDoctorID);

        $this->data['displayType'] = 'index';
        $this->data['patients']    = $this->patient_m->get_select_patient('patientID, name', [ 'delete_at' => 0 ]);
        $this->admission_m->order('admission.create_date asc');
        $this->data['admissions'] = $this->admission_m->get_select_admission_patient('patient.name, admission.admissionID, admission.patientID, admission.doctorID, admission.admissiondate, admission.prescriptionstatus, admission.status',
            $admissionDisplayArray);
        $this->data['doctors']    = pluck($this->user_m->get_select_user('userID, name, delete_at, status',
            [ 'roleID' => 2 ]), 'obj', 'userID');
        $this->data['jsmanager']  = [
            'startDate'      => date('Y-m-d'),
            'displayType'    => $this->data['displayType'],
            'loginuserID'    => $this->session->userdata('loginuserID'),
            'strDisplayDate' => $this->data['displayDateStrtotime']
        ];
        if ( permissionChecker('admission_add') ) {
            $this->data['tpas']  = $this->tpa_m->get_select_tpa('tpaID, name');
            $this->data['wards'] = $this->ward_m->get_ward_with_room();
            $this->data['beds']  = [];
            if ( $_POST ) {
                if ( $this->input->post('wardID') ) {
                    $this->data['beds'] = $this->bed_m->get_order_by_bed([
                        'wardID' => $this->input->post('wardID'),
                        'status' => 0
                    ]);
                }

                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $this->data["subview"] = 'admission/index';
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $admissionArray['patientID']     = $this->input->post('uhid');
                    $admissionArray['admissiondate'] = date('Y-m-d H:i:s',
                        strtotime($this->input->post('admissiondate')));
                    $admissionArray['pcase']         = $this->input->post('case');
                    $admissionArray['casualty']      = $this->input->post('casualty');
                    $admissionArray['oldpatient']    = $this->input->post('oldpatient');
                    $admissionArray['tpaID']         = $this->input->post('tpaID');
                    $admissionArray['reference']     = $this->input->post('reference');
                    $admissionArray['doctorID']      = $this->input->post('doctorID');
                    $admissionArray['creditlimit']   = $this->input->post('creditlimit');
                    $admissionArray['wardID']        = $this->input->post('wardID');
                    $admissionArray['bedID']         = $this->input->post('bedID');
                    $admissionArray['note']          = '';
                    $admissionArray['symptoms']      = $this->input->post('symptoms');
                    $admissionArray['allergies']     = $this->input->post('allergies');
                    $admissionArray['create_date']   = date("Y-m-d H:i:s");
                    $admissionArray['modify_date']   = date("Y-m-d H:i:s");
                    $admissionArray['create_userID'] = $this->session->userdata('loginuserID');
                    $admissionArray['create_roleID'] = $this->session->userdata('roleID');
                    $admissionArray['status']        = 0;

                    if ( $this->data['loginroleID'] == 3 ) {
                        $admissionArray['patientID']   = $this->data['loginuserID'];
                        $admissionArray['tpaID']       = 0;
                        $admissionArray['creditlimit'] = $this->data['generalsettings']->patient_credit_limit;
                    }

                    $this->_forEmailSend($admissionArray);

                    $this->admission_m->insert_admission($admissionArray);
                    $admissionID = $this->db->insert_id();

                    $this->patient_m->update_patient([ 'patienttypeID' => 1 ],
                        ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') ));
                    $this->bed_m->update([
                        'patientID' => ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') ),
                        'status'    => 1
                    ], $this->input->post('bedID'));

                    $heightweightbp = $this->heightweightbp_m->get_single_heightweightbp([
                        'patienttypeID'             => 1,
                        'appointmentandadmissionID' => $admissionID
                    ]);
                    if ( !inicompute($heightweightbp) ) {
                        $heightWeightBpArray['patientID']                 = ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') );
                        $heightWeightBpArray['patienttypeID']             = 1;
                        $heightWeightBpArray['appointmentandadmissionID'] = $admissionID;
                        $heightWeightBpArray['date']                      = $admissionArray['admissiondate'];
                        $heightWeightBpArray['height']                    = '';
                        $heightWeightBpArray['weight']                    = '';
                        $heightWeightBpArray['bp']                        = '';

                        $this->heightweightbp_m->insert_heightweightbp($heightWeightBpArray);
                    }

                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('admission/index/' . $this->data['displayDateStrtotime'] . '/' . $this->data['displayDoctorID']));
                }
            } else {
                $this->data["subview"] = 'admission/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'admission/index';
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function _admissionDisplayArrayGenerate( $displayDate, $displayDoctorID )
    {
        $displayArray = [];
        if ( $displayDoctorID > 0 ) {
            if ( $this->data['loginroleID'] != 2 && $this->data['loginroleID'] != 3 ) {
                $displayArray['admission.doctorID'] = $displayDoctorID;
            }
        } else {
            $displayDoctorID = 0;
        }

        if ( $displayDate == '' ) {
            $displayDate = strtotime(date('d-m-Y'));
        } else {
            if ( (int) $displayDate ) {
                $displayDayGenerate   = date('d', $displayDate);
                $displayMonthGenerate = date('m', $displayDate);
                $displayYearGenerate  = date('Y', $displayDate);
                if ( !checkdate($displayMonthGenerate, $displayDayGenerate, $displayYearGenerate) ) {
                    $displayDate = strtotime(date('d-m-Y'));
                }
            } else {
                $displayDate = strtotime(date('d-m-Y'));
            }
        }

        if ( $this->data['loginroleID'] == 2 ) {
            $displayArray['admission.doctorID']            = $this->data['loginuserID'];
            $displayArray['DATE(admission.admissiondate)'] = date('Y-m-d', $displayDate);
            $this->data['displayDate']                     = date('d-m-Y',
                strtotime($displayArray['DATE(admission.admissiondate)']));
            $this->data['displayDateStrtotime']            = strtotime($displayArray['DATE(admission.admissiondate)']);
            $this->data['displayDoctorID']                 = $this->data['loginuserID'];

        } elseif ( $this->data['loginroleID'] == 3 ) {
            $displayArray['admission.patientID'] = $this->data['loginuserID'];
            $this->data['displayDateStrtotime']  = strtotime(date('d-m-Y'));
            $this->data['displayDoctorID']       = 0;
        } else {
            $displayArray['DATE(admission.admissiondate)'] = date('Y-m-d', $displayDate);
            $this->data['displayDate']                     = date('d-m-Y',
                strtotime($displayArray['DATE(admission.admissiondate)']));
            $this->data['displayDateStrtotime']            = strtotime($displayArray['DATE(admission.admissiondate)']);
            $this->data['displayDoctorID']                 = $displayDoctorID;
        }
        return $displayArray;
    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'admissiondate',
                'label' => $this->lang->line("admission_admissiondate"),
                'rules' => 'trim|required|min_length[19]|max_length[19]|callback_valid_date|callback_unique_admission'
            ],
            [
                'field' => 'case',
                'label' => $this->lang->line("admission_case"),
                'rules' => 'trim|max_length[128]'
            ],
            [
                'field' => 'casualty',
                'label' => $this->lang->line("admission_casualty"),
                'rules' => 'trim|numeric|max_length[1]'
            ],
            [
                'field' => 'oldpatient',
                'label' => $this->lang->line("admission_oldpatient"),
                'rules' => 'trim|required|max_length[1]|callback_unique_oldpatient'
            ],
            [
                'field' => 'reference',
                'label' => $this->lang->line("admission_reference"),
                'rules' => 'trim|max_length[128]'
            ],
            [
                'field' => 'symptoms',
                'label' => $this->lang->line("admission_symptoms"),
                'rules' => 'trim|max_length[1000]'
            ],
            [
                'field' => 'allergies',
                'label' => $this->lang->line("admission_allergies"),
                'rules' => 'trim|max_length[1000]'
            ],
            [
                'field' => 'doctorID',
                'label' => $this->lang->line("admission_doctor"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero'
            ],
            [
                'field' => 'wardID',
                'label' => $this->lang->line("admission_ward"),
                'rules' => 'trim|required|numeric|callback_required_no_zero'
            ],
            [
                'field' => 'bedID',
                'label' => $this->lang->line("admission_bed"),
                'rules' => 'trim|required|numeric|callback_required_no_zero|callback_unique_bed'
            ]
        ];

        if ( $this->data['loginroleID'] != 3 ) {
            $rules[] = [
                'field' => 'uhid',
                'label' => $this->lang->line("admission_uhid"),
                'rules' => 'trim|required|callback_required_no_zero'
            ];
            $rules[] = [
                'field' => 'tpaID',
                'label' => $this->lang->line("admission_tpa"),
                'rules' => 'trim|numeric|max_length[11]'
            ];
            $rules[] = [
                'field' => 'creditlimit',
                'label' => $this->lang->line("admission_creditlimit"),
                'rules' => 'trim|required|numeric|max_length[15]'
            ];
        }

        return $rules;
    }

    private function _forEmailSend( $array )
    {
        $patientID = $array['patientID'];
        $patient   = $this->patient_m->get_single_patient([ 'patientID' => $patientID ]);
        if ( inicompute($patient) && $patient->email ) {
            $ward = $this->ward_m->get_single_ward([ 'wardID' => $array['wardID'] ]);
            $bed  = $this->bed_m->get_single_bed([ 'bedID' => $array['bedID'] ]);

            $passArray                    = $array;
            $passArray['patient']         = $patient;
            $passArray['wardName']        = ( inicompute($ward) ? $ward->name : '' );
            $passArray['bedName']         = ( inicompute($bed) ? $bed->name : '' );
            $passArray['generalsettings'] = $this->data['generalsettings'];

            $message = $this->load->view('admission/mail', $passArray, true);
            $message = trim($message);
            $subject = $this->lang->line('admission_patient') . " " . $this->lang->line('admission_appointment');
            $email   = $patient->email;
            @$this->mail->sendmail($this->data, $email, $subject, $message);
        }
    }

    public function edit()
    {
        $admissionID           = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate           = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID       = htmlentities(escapeString($this->uri->segment(5)));
        $admissionDisplayArray = $this->_admissionDisplayArrayGenerate($displayDate, $displayDoctorID);

        if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
            $queryArray['admissionID'] = $admissionID;
            $queryArray['status']      = 0;
            if ( $this->data['loginroleID'] == 2 ) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            } elseif ( $this->data['loginroleID'] == 3 ) {
                $queryArray['patientID'] = $this->data['loginuserID'];
            }

            $this->data['admission'] = $this->admission_m->get_select_admission('*', $queryArray, true);
            if ( inicompute($this->data['admission']) ) {
                $this->data['headerassets'] = [
                    'css' => [
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datetimepicker/css/datetimepicker.css',
                        'assets/datepicker/dist/css/bootstrap-datepicker.css',
                    ],
                    'js'  => [
                        'assets/select2/select2.js',
                        'assets/datetimepicker/js/datetimepicker.js',
                        'assets/datepicker/dist/js/bootstrap-datepicker.js',
                        'assets/inilabs/admission/index.js'
                    ]
                ];

                $this->data['displayType'] = 'edit';
                $this->data['jsmanager']   = [
                    'startDate'      => ( ( strtotime(date('Y-m-d')) < strtotime($this->data['admission']->admissiondate) ) ? date('Y-m-d') : date('Y-m-d',
                        strtotime($this->data['admission']->admissiondate)) ),
                    'displayType'    => $this->data['displayType'],
                    'loginuserID'    => $this->session->userdata('loginuserID'),
                    'strDisplayDate' => $this->data['displayDateStrtotime'],
                    'admissionID'    => $admissionID
                ];
                $this->data['patients']    = $this->patient_m->get_select_patient('patientID, name',
                    [ 'delete_at' => 0 ]);
                $this->admission_m->order('admission.create_date asc');
                $this->data['admissions'] = $this->admission_m->get_select_admission_patient('patient.name, admission.admissionID, admission.patientID, admission.doctorID, admission.admissiondate, admission.prescriptionstatus, admission.status',
                    $admissionDisplayArray);
                $this->data['doctors']    = pluck($this->user_m->get_select_user('userID, name, status, delete_at',
                    [ 'roleID' => 2 ]), 'obj', 'userID');
                $this->data['tpas']       = $this->tpa_m->get_select_tpa('tpaID, name');
                $this->data['wards']      = $this->ward_m->get_ward_with_room();
                $this->data['beds']       = $this->bed_m->get_order_by_bed([ 'wardID' => $this->data['admission']->wardID ]);

                if ( $_POST ) {
                    if ( $this->input->post('wardID') ) {
                        $this->data['beds'] = $this->bed_m->get_order_by_bed([ 'wardID' => $this->input->post('wardID') ]);
                    }

                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() == false ) {
                        $this->data["subview"] = 'admission/edit';
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $admissionArray['patientID']     = $this->input->post('uhid');
                        $admissionArray['admissiondate'] = date('Y-m-d H:i:s',
                            strtotime($this->input->post('admissiondate')));
                        $admissionArray['pcase']         = $this->input->post('case');
                        $admissionArray['casualty']      = $this->input->post('casualty');
                        $admissionArray['oldpatient']    = $this->input->post('oldpatient');
                        $admissionArray['tpaID']         = $this->input->post('tpaID');
                        $admissionArray['reference']     = $this->input->post('reference');
                        $admissionArray['doctorID']      = $this->input->post('doctorID');
                        $admissionArray['creditlimit']   = $this->input->post('creditlimit');
                        $admissionArray['wardID']        = $this->input->post('wardID');
                        $admissionArray['bedID']         = $this->input->post('bedID');
                        $admissionArray['symptoms']      = $this->input->post('symptoms');
                        $admissionArray['allergies']     = $this->input->post('allergies');
                        $admissionArray['modify_date']   = date("Y-m-d H:i:s");

                        if ( $this->data['loginroleID'] == 3 ) {
                            unset($admissionArray['patientID']);
                            unset($admissionArray['tpaID']);
                            unset($admissionArray['creditlimit']);
                        }

                        $this->admission_m->update_admission($admissionArray, $admissionID);
                        if ( $this->data['admission']->patientID != ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') ) ) {
                            $patientTypeID = $this->_patientType($admissionID, $this->data['admission']->patientID);
                            $this->patient_m->update_patient([ 'patienttypeID' => $patientTypeID ],
                                $this->data['admission']->patientID);
                            $this->patient_m->update_patient([ 'patienttypeID' => 1 ],
                                ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') ));
                        }

                        if ( $this->data['admission']->prescriptionstatus == 1 ) {
                            if ( $this->data['admission']->doctorID != $this->input->post('doctorID') ) {
                                $prescription = $this->prescription_m->get_single_prescription([
                                    'patienttypeID'             => 1,
                                    'appointmentandadmissionID' => $this->data['admission']->admissionID
                                ]);
                                if ( inicompute($prescription) ) {
                                    $this->prescription_m->update_prescription([ 'doctorID' => $this->input->post('doctorID') ],
                                        $prescription->prescriptionID);
                                }
                            }
                        }

                        $this->heightweightbp_m->order('heightweightbpID asc');
                        $heightweightbp = $this->heightweightbp_m->get_order_by_heightweightbp([
                            'patienttypeID'             => 1,
                            'appointmentandadmissionID' => $admissionID
                        ]);
                        if ( inicompute($heightweightbp) ) {
                            $i = 1;
                            foreach ( $heightweightbp as $heightweightbpVal ) {
                                $heightWeightBpArray['date'] = $admissionArray['admissiondate'];
                                if ( $i == 1 ) {
                                    $heightWeightBpArray['date']                      = $admissionArray['admissiondate'];
                                    $heightWeightBpArray['patientID']                 = ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') );
                                    $heightWeightBpArray['patienttypeID']             = 1;
                                    $heightWeightBpArray['appointmentandadmissionID'] = $admissionID;
                                    $this->heightweightbp_m->update_heightweightbp($heightWeightBpArray,
                                        $heightweightbpVal->heightweightbpID);
                                } else {
                                    unset($heightWeightBpArray['date']);
                                    $heightWeightBpArray['patientID']                 = ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') );
                                    $heightWeightBpArray['patienttypeID']             = 1;
                                    $heightWeightBpArray['appointmentandadmissionID'] = $admissionID;
                                    $this->heightweightbp_m->update_heightweightbp($heightWeightBpArray,
                                        $heightweightbpVal->heightweightbpID);
                                }
                                $i++;
                            }
                        } else {
                            $heightWeightBpArray['patientID']                 = ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') );
                            $heightWeightBpArray['patienttypeID']             = 1;
                            $heightWeightBpArray['appointmentandadmissionID'] = $admissionID;
                            $heightWeightBpArray['date']                      = $admissionArray['admissiondate'];
                            $heightWeightBpArray['height']                    = '';
                            $heightWeightBpArray['weight']                    = '';
                            $heightWeightBpArray['bp']                        = '';

                            $this->heightweightbp_m->insert_heightweightbp($heightWeightBpArray);
                        }

                        if ( $this->data['admission']->bedID != $this->input->post('bedID') ) {
                            $bed = $this->bed_m->get_single_bed([
                                'patientID' => $this->data['admission']->patientID,
                                'status'    => 1,
                                'bedID'     => $this->data['admission']->bedID
                            ]);
                            if ( inicompute($bed) ) {
                                $this->bed_m->update([ 'patientID' => 0, 'status' => 0 ], $bed->bedID);
                            }
                            $this->bed_m->update([
                                'patientID' => ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') ),
                                'status'    => 1
                            ], $this->input->post('bedID'));
                        } else {
                            $this->bed_m->update([ 'patientID' => ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') ) ],
                                $this->input->post('bedID'));
                        }

                        $this->session->set_flashdata('success', 'Success');
                        redirect(site_url('admission/index/' . $displayDate . '/' . $displayDoctorID));
                    }
                } else {
                    $this->data["subview"] = 'admission/edit';
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

    private function _patientType( $admissionID, $patientID )
    {
        $mergeData    = [];
        $returnData   = 5;
        $appointments = $this->appointment_m->get_order_by_appointment([ 'patientID' => $patientID ]);
        $admissions   = $this->admission_m->get_order_by_admission([ 'patientID' => $patientID ]);

        if ( inicompute($appointments) ) {
            foreach ( $appointments as $appointment ) {
                $mergeData[ strtotime($appointment->create_date) ] = $appointment->appointmentID;
            }
        }

        if ( inicompute($admissions) ) {
            foreach ( $admissions as $admission ) {
                if ( $admission->admissionID != $admissionID ) {
                    $mergeData[ strtotime($admission->create_date) ] = $admission->admissionID;
                }
            }
        }

        if ( inicompute($mergeData) ) {
            arsort($mergeData);
            $firstData = [];
            if ( inicompute($mergeData) ) {
                foreach ( $mergeData as $key => $mr ) {
                    $firstData = [ $key => $mr ];
                    break;
                }
                if ( inicompute($firstData) ) {
                    $date        = date('Y-m-d H:i:s', key($firstData));
                    $id          = $firstData[ key($firstData) ];
                    $appointment = $this->appointment_m->get_single_appointment([
                        'appointmentID' => $id,
                        'create_date'   => $date,
                        'patientID'     => $patientID
                    ]);
                    if ( inicompute($appointment) ) {
                        $returnData = 0;
                    } else {
                        $admission = $this->admission_m->get_single_admission([
                            'admissionID' => $id,
                            'create_date' => $date,
                            'patientID'   => $patientID
                        ]);
                        if ( inicompute($admission) ) {
                            $returnData = 1;
                        }
                    }
                }
            }
        }
        return $returnData;
    }

    public function view()
    {
        $admissionID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));
        $this->_admissionDisplayArrayGenerate($displayDate, $displayDoctorID);

        $this->data['headerassets'] = [
            'js'  => [
                'assets/inilabs/admission/view.js'
            ]
        ];

        if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
            $queryArray['admissionID'] = $admissionID;
            if ( $this->data['loginroleID'] == 2 ) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            } elseif ( $this->data['loginroleID'] == 3 ) {
                $queryArray['patientID'] = $this->data['loginuserID'];
            }

            $this->data['admission'] = $this->admission_m->get_select_admission('*', $queryArray, true);
            if ( inicompute($this->data['admission']) ) {
                $this->data['jsmanager']    = [
                    'admissionID' => $admissionID,
                    'date'        => $displayDate,
                    'doctorID'    => $displayDoctorID
                ];
                $this->data['patient']      = $this->patient_m->get_single_patient([ 'patientID' => $this->data['admission']->patientID ]);
                $this->data['designations'] = pluck($this->designation_m->get_select_designation('designationID, designation'),
                    'designation', 'designationID');
                $this->data['user']         = $this->user_m->get_single_user([ 'patientID' => $this->data['admission']->patientID ]);
                $this->data['tpa']          = $this->tpa_m->get_select_tpa('tpaID, name',
                    [ 'tpaID' => $this->data['admission']->tpaID ], true);
                $this->data['doctor']       = $this->user_m->get_select_user('userID, name',
                    [ 'userID' => $this->data['admission']->doctorID, 'roleID' => 2 ], true);
                $this->data['bed']          = $this->bed_m->get_single_bed([ 'bedID' => $this->data['admission']->bedID ]);
                $this->data["subview"]      = 'admission/view';
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
        $admissionID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

        if ( permissionChecker('admission_view') ) {
            if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
                $queryArray['admissionID'] = $admissionID;
                if ( $this->data['loginroleID'] == 2 ) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                } elseif ( $this->data['loginroleID'] == 3 ) {
                    $queryArray['patientID'] = $this->data['loginuserID'];
                }

                $this->data['admission'] = $this->admission_m->get_select_admission('*', $queryArray, true);
                if ( inicompute($this->data['admission']) ) {
                    $this->data['patient']      = $this->patient_m->get_single_patient([ 'patientID' => $this->data['admission']->patientID ]);
                    $this->data['designations'] = pluck($this->designation_m->get_select_designation('designationID, designation'),
                        'designation', 'designationID');
                    $this->data['user']         = $this->user_m->get_single_user([ 'patientID' => $this->data['admission']->patientID ]);
                    $this->data['tpa']          = $this->tpa_m->get_select_tpa('tpaID, name',
                        [ 'tpaID' => $this->data['admission']->tpaID ], true);
                    $this->data['doctor']       = $this->user_m->get_select_user('userID, name',
                        [ 'userID' => $this->data['admission']->doctorID, 'roleID' => 2 ], true);
                    $this->data['bed']          = $this->bed_m->get_single_bed([ 'bedID' => $this->data['admission']->bedID ]);

                    $this->report->reportPDF([
                        'stylesheet' => 'admissionmodule.css',
                        'data'       => $this->data,
                        'viewpath'   => 'admission/printpreview'
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
        if ( permissionChecker('admission_view') ) {
            if ( $_POST ) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $admissionID     = $this->input->post('admissionID');
                    $displayDate     = $this->input->post('date');
                    $displayDoctorID = $this->input->post('doctorID');

                    if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
                        $queryArray['admissionID'] = $admissionID;
                        if ( $this->data['loginroleID'] == 2 ) {
                            $queryArray['doctorID'] = $this->data['loginuserID'];
                        } elseif ( $this->data['loginroleID'] == 3 ) {
                            $queryArray['patientID'] = $this->data['loginuserID'];
                        }

                        $email   = $this->input->post('to');
                        $subject = $this->input->post('subject');
                        $message = $this->input->post('message');

                        $this->data['admission'] = $this->admission_m->get_select_admission('*', $queryArray, true);
                        if ( inicompute($this->data['admission']) ) {
                            $this->data['patient']      = $this->patient_m->get_single_patient([ 'patientID' => $this->data['admission']->patientID ]);
                            $this->data['designations'] = pluck($this->designation_m->get_select_designation('designationID, designation'),
                                'designation', 'designationID');
                            $this->data['user']         = $this->user_m->get_single_user([ 'patientID' => $this->data['admission']->patientID ]);
                            $this->data['tpa']          = $this->tpa_m->get_select_tpa('tpaID, name',
                                [ 'tpaID' => $this->data['admission']->tpaID ], true);
                            $this->data['doctor']       = $this->user_m->get_select_user('userID, name',
                                [ 'userID' => $this->data['admission']->doctorID, 'roleID' => 2 ], true);
                            $this->data['bed']          = $this->bed_m->get_single_bed([ 'bedID' => $this->data['admission']->bedID ]);

                            $this->report->reportSendToMail([
                                'stylesheet' => 'admissionmodule.css',
                                'data'       => $this->data,
                                'viewpath'   => 'admission/printpreview',
                                'email'      => $email,
                                'subject'    => $subject,
                                'message'    => $message
                            ]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = true;
                        } else {
                            $retArray['message'] = $this->lang->line('admission_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('admission_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('admission_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('admission_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules()
    {
        $rules = [
            [
                'field' => 'to',
                'label' => $this->lang->line("admission_to"),
                'rules' => 'trim|required|valid_email'
            ],
            [
                'field' => 'subject',
                'label' => $this->lang->line("admission_subject"),
                'rules' => 'trim|required'
            ],
            [
                'field' => 'message',
                'label' => $this->lang->line("admission_message"),
                'rules' => 'trim'
            ],
            [
                'field' => 'admissionID',
                'label' => $this->lang->line("admission_admission"),
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'date',
                'label' => $this->lang->line("admission_date"),
                'rules' => 'trim'
            ],
            [
                'field' => 'doctorID',
                'label' => $this->lang->line("admission_doctor"),
                'rules' => 'trim'
            ]
        ];
        return $rules;
    }

    public function delete()
    {
        $admissionID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

        if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
            $queryArray['admissionID'] = $admissionID;
            $queryArray['status']      = 0;
            if ( $this->data['loginroleID'] == 2 ) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            } elseif ( $this->data['loginroleID'] == 3 ) {
                $queryArray['patientID'] = $this->data['loginuserID'];
            }
            $admission = $this->admission_m->get_select_admission('*', $queryArray, true);
            if ( inicompute($admission) ) {
                $patientTypeID = $this->_patientType($admissionID, $admission->patientID);
                $this->patient_m->update_patient([ 'patienttypeID' => $patientTypeID ], $admission->patientID);

                $heightweightbp = $this->heightweightbp_m->get_order_by_heightweightbp([
                    'patienttypeID'             => 1,
                    'appointmentandadmissionID' => $admission->admissionID
                ]);
                if ( inicompute($heightweightbp) ) {
                    foreach ( $heightweightbp as $heightweightbpVal ) {
                        $this->heightweightbp_m->delete_heightweightbp($heightweightbpVal->heightweightbpID);
                    }
                }

                $bed = $this->bed_m->get_single_bed([
                    'patientID' => $admission->patientID,
                    'bedID'     => $admission->bedID,
                    'status'    => 1
                ]);
                if ( inicompute($bed) ) {
                    $this->bed_m->update([ 'patientID' => 0, 'status' => 0 ], $bed->bedID);
                }

                if ( $admission->prescriptionstatus ) {
                    $prescription = $this->prescription_m->get_single_prescription([
                        'patienttypeID'             => 1,
                        'appointmentandadmissionID' => $admission->admissionID
                    ]);
                    if ( inicompute($prescription) ) {
                        $prescriptionitems = $this->prescriptionitem_m->get_order_by_prescriptionitem([ 'prescriptionID' => $prescription->prescriptionID ]);
                        if ( inicompute($prescriptionitems) ) {
                            foreach ( $prescriptionitems as $prescriptionitem ) {
                                $this->prescriptionitem_m->delete_prescriptionitem($prescriptionitem->prescriptionitemID);
                            }
                        }
                    }
                    $this->prescription_m->delete_prescription($prescription->prescriptionID);
                }

                $instructions = $this->instruction_m->get_order_by_instruction([ 'admissionID' => $admission->admissionID ]);
                if ( inicompute($instructions) ) {
                    foreach ( $instructions as $instruction ) {
                        $this->instruction_m->delete_instruction($instruction->instructionID);
                    }
                }

                $this->admission_m->delete_admission($admissionID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('admission/index/' . $displayDate . '/' . $displayDoctorID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function getbed()
    {
        echo "<option value='0'>— " . $this->lang->line('admission_please_select') . " —</option>";
        if ( $_POST ) {
            $wardID = $this->input->post('wardID');
            $bedID  = $this->input->post('bedID');

            if ( (int) $wardID ) {
                $beds = $this->bed_m->get_select_bed('bedID, name, status', [ 'wardID' => $wardID ]);
                if ( inicompute($beds) ) {
                    foreach ( $beds as $bed ) {
                        if ( !empty($bedID) ) {
                            if ( $bed->status == 0 || $bed->bedID == $bedID ) {
                                echo "<option value='" . $bed->bedID . "'>" . $bed->name . "</option>";
                            }
                        } else {
                            if ( $bed->status == 0 ) {
                                echo "<option value='" . $bed->bedID . "'>" . $bed->name . "</option>";
                            }
                        }
                    }
                }
            }
        }
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
                            $admissionID     = htmlentities(escapeString($this->uri->segment(3)));
                            $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
                            $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

                            if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
                                if ( strtotime($date) < strtotime($this->data['admission']->admissiondate) && strtotime($date) < strtotime(date('Y-m-d')) ) {
                                    $this->form_validation->set_message("valid_date", "The %s can not take past date.");
                                    return false;
                                }
                                return true;
                            } else {
                                if ( strtotime(date('d-m-Y')) > strtotime($date) ) {
                                    $this->form_validation->set_message("valid_date", "The %s can not take past date.");
                                    return false;
                                }
                                return true;
                            }
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

    public function unique_admission()
    {
        $admissionID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));
        $patientID       = ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') );

        if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
            $admission = $this->admission_m->get_single_admission([
                'admissionID !=' => $admissionID,
                'patientID'      => $patientID,
                'status'         => 0
            ]);
            if ( inicompute($admission) ) {
                $this->form_validation->set_message('unique_admission', 'This patient has already admitted.');
                return false;
            }
            return true;
        } else {
            $admission = $this->admission_m->get_single_admission([ 'patientID' => $patientID, 'status' => 0 ]);
            if ( inicompute($admission) ) {
                $this->form_validation->set_message('unique_admission', 'This patient has already admitted.');
                return false;
            }
            return true;
        }
    }

    public function unique_oldpatient()
    {
        $admissionID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));
        $patientID       = ( ( $this->data['loginroleID'] == 3 ) ? $this->data['loginuserID'] : $this->input->post('uhid') );
        if ( $this->input->post('oldpatient') == 0 ) {
            if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
                $admission = $this->admission_m->get_single_admission([
                    'patientID'      => $patientID,
                    'status'         => 1,
                    'admissionID !=' => $admissionID
                ]);
                if ( !inicompute($admission) ) {
                    $this->form_validation->set_message('unique_oldpatient', 'This is the new patient.');
                    return false;
                }
                return true;
            } else {
                $admission = $this->admission_m->get_single_admission([ 'patientID' => $patientID, 'status' => 1 ]);
                if ( !inicompute($admission) ) {
                    $this->form_validation->set_message('unique_oldpatient', 'This is the new patient.');
                    return false;
                }
                return true;
            }
        }
        return true;
    }

    public function unique_bed()
    {
        $admissionID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

        if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
            if ( isset($this->data['admission']) && inicompute($this->data['admission']) && $this->data['admission']->bedID != $this->input->post('bedID') ) {
                $bed = $this->bed_m->get_single_bed([ 'bedID' => $this->input->post('bedID'), 'status' => 1 ]);
                if ( inicompute($bed) ) {
                    $this->form_validation->set_message('unique_bed', 'The %s has already exist');
                    return false;
                }
                return true;
            }
            return true;
        } else {
            $bed = $this->bed_m->get_single_bed([ 'bedID' => $this->input->post('bedID'), 'status' => 1 ]);
            if ( inicompute($bed) ) {
                $this->form_validation->set_message('unique_bed', 'The %s has already existed.');
                return false;
            }
            return true;
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

    public function strtotimegenerate()
    {
        $date = $this->input->post('date');
        echo strtotime($date);
    }

    public function prescription()
    {
        $admissionID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

        $this->data['headerassets'] = [
            'js'  => [
                'assets/inilabs/admission/prescription.js'
            ]
        ];

        if ( permissionChecker('admission_view') ) {
            if ( ( (int) $admissionID && (int) $displayDate ) && ( $displayDoctorID == 0 || $displayDoctorID > 0 ) ) {
                $queryArray['admissionID']        = $admissionID;
                $queryArray['status']             = 1;
                $queryArray['prescriptionstatus'] = 1;
                if ( $this->data['loginroleID'] == 2 ) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                } elseif ( $this->data['loginroleID'] == 3 ) {
                    $queryArray['patientID'] = $this->data['loginuserID'];
                }

                $this->data['jsmanager'] = ['admissionID' => $admissionID];
                $this->data['admission'] = $this->admission_m->get_single_admission($queryArray);
                if ( inicompute($this->data['admission']) && $this->data['admission']->status && $this->data['admission']->prescriptionstatus ) {
                    $this->data['prescription'] = $this->prescription_m->get_single_prescription([
                        'patienttypeID'             => 1,
                        'appointmentandadmissionID' => $this->data['admission']->admissionID
                    ]);
                    if ( inicompute($this->data['prescription']) ) {
                        $this->data['displayDate']     = $displayDate;
                        $this->data['displayDoctorID'] = $displayDoctorID;
                        $this->data['patientinfo']     = $this->patient_m->get_single_patient([ 'patientID' => $this->data['prescription']->patientID ]);
                        $this->data['gender']          = [ 0 => '', 1 => 'M', 2 => 'F' ];

                        if ( $this->data['prescription']->patienttypeID == 0 ) {
                            $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment([
                                'appointmentID' => $this->data['prescription']->appointmentandadmissionID,
                                'patientID'     => $this->data['prescription']->patientID
                            ]);
                        } else {
                            $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission([
                                'admissionID' => $this->data['prescription']->appointmentandadmissionID,
                                'patientID'   => $this->data['prescription']->patientID
                            ]);
                        }

                        $this->data['create'] = $this->user_m->get_single_user([ 'userID' => $this->data['prescription']->create_userID ]);
                        $this->prescriptionitem_m->order('prescriptionitemID asc');
                        $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem([ 'prescriptionID' => $this->data['prescription']->prescriptionID ]);

                        $this->data["subview"] = 'admission/prescription';
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
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function prescriptionprintpreview()
    {
        if ( permissionChecker('admission_view') ) {
            $admissionID = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $admissionID ) {
                $queryArray['admissionID']        = $admissionID;
                $queryArray['status']             = 1;
                $queryArray['prescriptionstatus'] = 1;
                if ( $this->data['loginroleID'] == 2 ) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                } elseif ( $this->data['loginroleID'] == 3 ) {
                    $queryArray['patientID'] = $this->data['loginuserID'];
                }

                $this->data['admission'] = $this->admission_m->get_single_admission($queryArray);
                if ( inicompute($this->data['admission']) && $this->data['admission']->status && $this->data['admission']->prescriptionstatus ) {
                    $this->data['prescription'] = $this->prescription_m->get_single_prescription([
                        'patienttypeID'             => 1,
                        'appointmentandadmissionID' => $this->data['admission']->admissionID
                    ]);
                    if ( inicompute($this->data['prescription']) ) {
                        $this->data['patientinfo'] = $this->patient_m->get_single_patient([ 'patientID' => $this->data['prescription']->patientID ]);
                        $this->data['gender']      = [ 0 => '', 1 => 'M', 2 => 'F' ];

                        if ( $this->data['prescription']->patienttypeID == 0 ) {
                            $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment([
                                'appointmentID' => $this->data['prescription']->appointmentandadmissionID,
                                'patientID'     => $this->data['prescription']->patientID
                            ]);
                        } else {
                            $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission([
                                'admissionID' => $this->data['prescription']->appointmentandadmissionID,
                                'patientID'   => $this->data['prescription']->patientID
                            ]);
                        }

                        $this->data['create'] = $this->user_m->get_single_user([ 'userID' => $this->data['prescription']->create_userID ]);
                        $this->prescriptionitem_m->order('prescriptionitemID asc');
                        $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem([ 'prescriptionID' => $this->data['prescription']->prescriptionID ]);

                        $this->report->reportPDF([
                            'stylesheet' => 'prescriptionprintpreviewmodule.css',
                            'data'       => $this->data,
                            'viewpath'   => 'admission/prescriptionprintpreview'
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
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function prescriptionsendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = false;
        if ( permissionChecker('admission_view') ) {
            if ( $_POST ) {
                $rules = $this->prescriptionsendmail_rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $admissionID = $this->input->post('admissionID');
                    if ( (int) $admissionID ) {
                        $queryArray['admissionID']        = $admissionID;
                        $queryArray['status']             = 1;
                        $queryArray['prescriptionstatus'] = 1;
                        if ( $this->data['loginroleID'] == 2 ) {
                            $queryArray['doctorID'] = $this->data['loginuserID'];
                        } elseif ( $this->data['loginroleID'] == 3 ) {
                            $queryArray['patientID'] = $this->data['loginuserID'];
                        }

                        $this->data['admission'] = $this->admission_m->get_single_admission($queryArray);
                        if ( inicompute($this->data['admission']) && $this->data['admission']->status && $this->data['admission']->prescriptionstatus ) {
                            $this->data['prescription'] = $this->prescription_m->get_single_prescription([
                                'patienttypeID'             => 1,
                                'appointmentandadmissionID' => $this->data['admission']->admissionID
                            ]);
                            if ( inicompute($this->data['prescription']) ) {
                                $this->data['patientinfo'] = $this->patient_m->get_single_patient([ 'patientID' => $this->data['prescription']->patientID ]);
                                $this->data['gender']      = [ 0 => '', 1 => 'M', 2 => 'F' ];

                                if ( $this->data['prescription']->patienttypeID == 0 ) {
                                    $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment([
                                        'appointmentID' => $this->data['prescription']->appointmentandadmissionID,
                                        'patientID'     => $this->data['prescription']->patientID
                                    ]);
                                } else {
                                    $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission([
                                        'admissionID' => $this->data['prescription']->appointmentandadmissionID,
                                        'patientID'   => $this->data['prescription']->patientID
                                    ]);
                                }

                                $this->data['create'] = $this->user_m->get_single_user([ 'userID' => $this->data['prescription']->create_userID ]);
                                $this->prescriptionitem_m->order('prescriptionitemID asc');
                                $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem([ 'prescriptionID' => $this->data['prescription']->prescriptionID ]);

                                $email   = $this->input->post('to');
                                $subject = $this->input->post('subject');
                                $message = $this->input->post('message');

                                $this->report->reportSendToMail([
                                    'stylesheet' => 'prescriptionprintpreviewmodule.css',
                                    'data'       => $this->data,
                                    'viewpath'   => 'admission/prescriptionprintpreview',
                                    'email'      => $email,
                                    'subject'    => $subject,
                                    'message'    => $message
                                ]);
                                $retArray['message'] = "Success";
                                $retArray['status']  = true;
                            } else {
                                $retArray['message'] = $this->lang->line('admission_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('admission_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('admission_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('admission_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('admission_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function prescriptionsendmail_rules()
    {
        $rules = [
            [
                'field' => 'to',
                'label' => $this->lang->line("admission_to"),
                'rules' => 'trim|required|valid_email'
            ],
            [
                'field' => 'subject',
                'label' => $this->lang->line("admission_subject"),
                'rules' => 'trim|required'
            ],
            [
                'field' => 'message',
                'label' => $this->lang->line("admission_message"),
                'rules' => 'trim'
            ],
            [
                'field' => 'admissionID',
                'label' => $this->lang->line("admission_admission"),
                'rules' => 'trim|required|numeric'
            ]
        ];
        return $rules;
    }


}
