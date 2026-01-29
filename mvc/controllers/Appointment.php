<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Appointment extends Admin_Controller
{
    private $paymentMethod = [
        'cash'   => 1,
        'cheque' => 2,
        'other'  => 3,
    ];

    public $paymentGateway;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('report');
        $this->load->library('mail');
        $this->load->library('payment');
        $this->load->model('tpa_m');
        $this->load->model('user_m');
        $this->load->model('zoomsettings_m');
        $this->load->model('patient_m');
        $this->load->model('admission_m');
        $this->load->model('appointment_m');
        $this->load->model('heightweightbp_m');
        $this->load->model('designation_m');
        $this->load->model('prescription_m');
        $this->load->model('prescriptionitem_m');
        $this->load->model('department_m');
        $this->load->model('doctorinfo_m');
        $this->load->model('payment_gateway_m');
        $this->load->model('payment_gateway_option_m');
        $this->load->library('zoom');


        $this->paymentGateway = $this->payment_gateway_m->get_order_by_payment_gateway(['status' => 1]);

        $language = $this->session->userdata('lang');
        $this->lang->load('appointment', $language);

        $this->data['appointmentType'] = [
            1 => $this->lang->line('appointment_live_visit'),
            2 => $this->lang->line('appointment_online'),
        ];
    }

    protected function rules($rulesArray = [])
    {
        $rules = array(
            array(
                'field' => 'appointmentdate',
                'label' => $this->lang->line("appointment_appointmentdate"),
                'rules' => 'trim|required|min_length[19]|max_length[19]|callback_valid_date|callback_unique_appointment|callback_unique_admission',
            ),
            array(
                'field' => 'case',
                'label' => $this->lang->line("appointment_case"),
                'rules' => 'trim|max_length[128]',
            ),
            array(
                'field' => 'casualty',
                'label' => $this->lang->line("appointment_casualty"),
                'rules' => 'trim|numeric|max_length[1]',
            ),
            array(
                'field' => 'oldpatient',
                'label' => $this->lang->line("appointment_oldpatient"),
                'rules' => 'trim|required|max_length[1]|callback_unique_oldpatient',
            ),
            array(
                'field' => 'reference',
                'label' => $this->lang->line("appointment_reference"),
                'rules' => 'trim|max_length[128]',
            ),
            array(
                'field' => 'symptoms',
                'label' => $this->lang->line("appointment_symptoms"),
                'rules' => 'trim|max_length[1000]',
            ),
            array(
                'field' => 'allergies',
                'label' => $this->lang->line("appointment_allergies"),
                'rules' => 'trim|max_length[1000]',
            ),
        );

        $paymentstatus    = isset($rulesArray['paymentstatus']) ? $rulesArray['paymentstatus'] : 0;
        $validationStatus = true;
        $appointmentID    = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $appointmentID) {
            if ($this->data['loginroleID'] == 3 && $paymentstatus == 1) {
                $validationStatus = false;
            }
        }

        if ($validationStatus) {
            $rules[] = array(
                'field' => 'departmentID',
                'label' => $this->lang->line("appointment_department"),
                'rules' => 'trim|required|numeric|max_length[11]',
            );
            $rules[] = array(
                'field' => 'doctorID',
                'label' => $this->lang->line("appointment_doctor"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero',
            );
            $rules[] = array(
                'field' => 'type',
                'label' => $this->lang->line("appointment_type"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero',
            );
            $rules[] = array(
                'field' => 'amount',
                'label' => $this->lang->line("appointment_amount"),
                'rules' => 'trim|required|numeric|max_length[15]|callback_appointment_amount_check',
            );
            $rules[] = array(
                'field' => 'paymentstatus',
                'label' => $this->lang->line("appointment_payment"),
                'rules' => 'trim|required|numeric|max_length[1]|callback_required_no_zero',
            );

            if ($paymentstatus == 1) {
                $rules[] = array(
                    'field' => 'paymentmethodID',
                    'label' => $this->lang->line("appointment_paymentmethod"),
                    'rules' => 'trim|required|callback_required_no_zero',
                );
            }
        }

        if ($this->data['loginroleID'] != 3) {
            $rules[] = array(
                'field' => 'uhid',
                'label' => $this->lang->line("appointment_uhid"),
                'rules' => 'trim|required|callback_required_no_zero',
            );
            $rules[] = array(
                'field' => 'tpaID',
                'label' => $this->lang->line("appointment_tpa"),
                'rules' => 'trim|numeric|max_length[11]',
            );
        }

        if ($this->data['loginroleID'] != 3 && ($paymentstatus == 1)) {
            $rules[] = array(
                'field' => 'paymentmethodID',
                'label' => $this->lang->line("appointment_paymentmethod"),
                'rules' => 'trim|required|callback_required_no_zero',
            );
        }
        return $rules;
    }

    private function _appointmentDisplayArrayGenerate($displayDate, $displayDoctorID)
    {
        $displayArray = [];
        if ($displayDoctorID > 0) {
            if ($this->data['loginroleID'] != 2 && $this->data['loginroleID'] != 3) {
                $displayArray['appointment.doctorID'] = $displayDoctorID;
            }
        } else {
            $displayDoctorID = 0;
        }

        if ($displayDate == '') {
            $displayDate = strtotime(date('d-m-Y'));
        } else {
            if ((int) $displayDate) {
                $displayDayGenerate   = date('d', $displayDate);
                $displayMonthGenerate = date('m', $displayDate);
                $displayYearGenerate  = date('Y', $displayDate);
                if (!checkdate($displayMonthGenerate, $displayDayGenerate, $displayYearGenerate)) {
                    $displayDate = strtotime(date('d-m-Y'));
                }
            } else {
                $displayDate = strtotime(date('d-m-Y'));
            }
        }

        if ($this->data['loginroleID'] == 2) {
            $displayArray['appointment.doctorID']              = $this->data['loginuserID'];
            $displayArray['DATE(appointment.appointmentdate)'] = date('Y-m-d', $displayDate);
            $this->data['displayDate']                         = date('d-m-Y', strtotime($displayArray['DATE(appointment.appointmentdate)']));
            $this->data['displayDateStrtotime']                = strtotime($displayArray['DATE(appointment.appointmentdate)']);
            $this->data['displayDoctorID']                     = $this->data['loginuserID'];
        } elseif ($this->data['loginroleID'] == 3) {
            $displayArray['appointment.patientID'] = $this->data['loginuserID'];
            $this->data['displayDateStrtotime']    = strtotime(date('d-m-Y'));
            $this->data['displayDoctorID']         = 0;
        } else {
            $displayArray['DATE(appointment.appointmentdate)'] = date('Y-m-d', $displayDate);
            $this->data['displayDate']                         = date('d-m-Y', strtotime($displayArray['DATE(appointment.appointmentdate)']));
            $this->data['displayDateStrtotime']                = strtotime($displayArray['DATE(appointment.appointmentdate)']);
            $this->data['displayDoctorID']                     = $displayDoctorID;
        }
        return $displayArray;
    }

    private function _getPaymentMethod()
    {
        $paymentmethodArray['0'] = '— ' . $this->lang->line('appointment_please_select') . ' —';

        if ($this->session->userdata('roleID') != 3 ) {
            $paymentmethodArray['cash']   = $this->lang->line('appointment_cash');
            $paymentmethodArray['cheque'] = $this->lang->line('appointment_cheque');
            $paymentmethodArray['other']  = $this->lang->line('appointment_other');
        }

        if (inicompute($this->paymentGateway)) {
            foreach ($this->paymentGateway as $gateway) {
                if ($this->session->userdata('roleID') == 3 ) {
                    $paymentmethodArray[$gateway->slug] = $gateway->name;
                }
            }
        }

        return $paymentmethodArray;
    }

    private function _getPaymentMethodNameByID()
    {
        $paymentmethodArray[0] = '';
        $paymentmethodArray[1] = 'cash';
        $paymentmethodArray[2] = 'cheque';
        $paymentmethodArray[3] = 'other';

        if (inicompute($this->paymentGateway)) {
            foreach ($this->paymentGateway as $gateway) {
                $paymentmethodArray[$gateway->id] = $gateway->slug;
            }
        }
        return $paymentmethodArray;
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/datetimepicker/css/datetimepicker.css',
                'assets/datepicker/dist/css/bootstrap-datepicker.css',
            ),
            'js'  => array(
                'assets/select2/select2.js',
                'assets/datetimepicker/js/datetimepicker.js',
                'assets/datepicker/dist/js/bootstrap-datepicker.js',
                'assets/inilabs/appointment/index.js',
            ),
        );

        $paymentViews = [];
        if (inicompute($this->paymentGateway)) {
            foreach ($this->paymentGateway as $payGateway) {

                if ($payGateway->misc) {
                    $miscArray = json_decode($payGateway->misc, true);

                    if (isset($miscArray['cdn'])) {
                        foreach ($miscArray['cdn'] as $key => $cdn) {
                            $this->data['footerassets']['cdn'][] = $cdn;
                        }
                    }

                    if (isset($miscArray['js'])) {
                        foreach ($miscArray['js'] as $key => $js) {
                            $this->data['footerassets']['js'][] = $js;
                        }
                    }

                    if (isset($miscArray['css'])) {
                        foreach ($miscArray['css'] as $key => $css) {
                            $this->data['headerassets']['css'][] = $css;
                        }
                    }

                    if (isset($miscArray['view'])) {
                        foreach ($miscArray['view'] as $key => $view) {
                            $paymentViews[] = $view;
                        }
                    }
                }
            }
        }
        $this->data['paymentViews'] = $paymentViews;

        $displayDate         = htmlentities(escapeString($this->uri->segment(3)));
        $displayDoctorID     = htmlentities(escapeString($this->uri->segment(4)));
        $appointDisplayArray = $this->_appointmentDisplayArrayGenerate($displayDate, $displayDoctorID);

        $this->data['displayType'] = 'index';
        $this->data['patients']    = $this->patient_m->get_select_patient('patientID, name', array('delete_at' => 0));
        $this->appointment_m->order('appointment.create_date asc');
        $this->data['appointments'] = $this->appointment_m->get_select_appointment_patient('appointment.type,appointment.paymentstatus,appointment.appointmentID, appointment.patientID, appointment.doctorID, appointment.appointmentdate, appointment.status, patient.name', $appointDisplayArray);
        $this->data['jsmanager'] = ['displayType' => $this->data['displayType'], 'startDate' => date('Y-m-d'), 'paymentstatus' => 0, 'strDisplayDate' => (($displayDate == '') ? strtotime(date('d-m-Y')) : $displayDate), 'appointmentID' => 0, 'loginuserID' => $this->session->userdata('loginuserID')];

        if (permissionChecker('appointment_add')) {
            $this->data['tpas']            = $this->tpa_m->get_select_tpa('tpaID, name');
            $this->data['departments']     = $this->department_m->get_department();
            $this->data['types']           = $this->_type($this->input->post('doctorID'));
            $this->data['payment_options'] = pluck($this->payment_gateway_option_m->get_payment_gateway_option(), 'payment_value', 'payment_option');

            $doctorQueryArray['designationID'] = 3;
            if ($this->input->post('departmentID')) {
                $doctorQueryArray['departmentID'] = $this->input->post('departmentID');
            }
            $this->data['doctors'] = pluck($this->user_m->get_select_doctor_with_doctorinfo('userID, name', $doctorQueryArray), 'obj', 'userID');

            $this->data['paymentstatus']      = 0;
            $this->data['paymentmethodArray'] = $this->_getPaymentMethod();

            if ($_POST) {
                $this->data['paymentstatus'] = $this->input->post('paymentstatus');
                $rulesArray                  = ['paymentstatus' => $this->input->post('paymentstatus')];
                $this->data['jsmanager']     = ['displayType' => $this->data['displayType'], 'startDate' => date('Y-m-d'), 'paymentstatus' => $this->data['paymentstatus'], 'strDisplayDate' => (($displayDate == '') ? strtotime(date('d-m-Y')) : $displayDate), 'appointmentID' => 0, 'loginuserID' => $this->session->userdata('loginuserID')];
                $rules                       = $this->rules($rulesArray);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = 'appointment/index';
                    $this->load->view('_layout_main', $this->data);
                } else {

                    $appointmentArray['patientID']       = $this->input->post('uhid');
                    $appointmentArray['appointmentdate'] = date('Y-m-d H:i:s', strtotime($this->input->post('appointmentdate')));
                    $appointmentArray['pcase']           = $this->input->post('case');
                    $appointmentArray['casualty']        = $this->input->post('casualty');
                    $appointmentArray['oldpatient']      = $this->input->post('oldpatient');
                    $appointmentArray['tpaID']           = $this->input->post('tpaID');
                    $appointmentArray['reference']       = $this->input->post('reference');
                    $appointmentArray['doctorID']        = $this->input->post('doctorID');
                    $appointmentArray['amount']          = $this->input->post('amount');
                    $appointmentArray['paymentstatus']   = $this->input->post('paymentstatus');
                    $appointmentArray['symptoms']        = $this->input->post('symptoms');
                    $appointmentArray['allergies']       = $this->input->post('allergies');
                    $appointmentArray['note']            = '';
                    $appointmentArray['create_date']     = date("Y-m-d H:i:s");
                    $appointmentArray['modify_date']     = date("Y-m-d H:i:s");
                    $appointmentArray['create_userID']   = $this->session->userdata('loginuserID');
                    $appointmentArray['create_roleID']   = $this->session->userdata('roleID');
                    $appointmentArray['type']            = $this->input->post('type');
                    $appointmentArray['status']          = 0;
                    $appointmentArray['duration']          = 40;


                    if($this->input->post('type')==2){
                        $zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
                        $tokenInfo 		= json_decode($zoomSetting->data, true);

                        $refreshToken 	= $this->zoom->refreshToken($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo['refresh_token']);

                        if($refreshToken->status) {
                            if(isset($refreshToken->data)) {
                                $this->zoomsettings_m->update_zoomsettings(['data' => json_encode($refreshToken->data)], 1);
                            }

                            $zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
                            $tokenInfo 		= json_decode($zoomSetting->data, true);
                            $response 		= $this->zoom->createMetting($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo, $appointmentArray);

                            if($response->status) {
                                if(isset($response->update_token)) {
                                    $this->zoomsettings_m->update_zoomsettings(['data' => json_encode($response->update_token)], 1);
                                }
                                if($response->data) {
                                    $appointmentArray['join_url'] = $response->data['join_url'];
                                    $appointmentArray['password'] = $response->data['password'];
                                    $appointmentArray['metting_id'] = $response->data['metting_id'];
                                }
                            }
                        }
                    }


                    if ($this->input->post('paymentstatus') == 1) {
                        $appointmentArray['paymentmethodID'] = isset($this->paymentMethod[$this->input->post('paymentmethodID')]) ? $this->paymentMethod[$this->input->post('paymentmethodID')] : 0;
                        $appointmentArray['paymentdate']     = date('Y-m-d H:i:s');
                    } else {
                        $appointmentArray['paymentmethodID'] = 0;
                        $appointmentArray['paymentdate']     = '0000-00-00 00:00:00';
                    }

                    if ($this->data['loginroleID'] == 3) {
                        $appointmentArray['patientID']       = $this->data['loginuserID'];
                        $appointmentArray['tpaID']           = 0;
                        $appointmentArray['paymentstatus']   = 2;
                        $appointmentArray['paymentmethodID'] = 0;
                        $appointmentArray['paymentdate']     = '0000-00-00 00:00:00';
                    } else {
                        if (!isset($this->paymentMethod[$this->input->post('paymentmethodID')])) {
                            $appointmentArray['paymentstatus']   = 2;
                            $appointmentArray['paymentmethodID'] = 0;
                            $appointmentArray['paymentdate']     = '0000-00-00 00:00:00';
                        }
                    }

                    $this->_forEmailSend($appointmentArray);

                    $this->appointment_m->insert_appointment($appointmentArray);
                    $appointmentID = $this->db->insert_id();
                    $this->patient_m->update_patient(array('patienttypeID' => 0), (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid')));
                    $heightweightbp = $this->heightweightbp_m->get_single_heightweightbp(array('patienttypeID' => 0, 'appointmentandadmissionID' => $appointmentID));
                    if (!inicompute($heightweightbp)) {
                        $heightWeightBpArray['patientID']                 = (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid'));
                        $heightWeightBpArray['patienttypeID']             = 0;
                        $heightWeightBpArray['appointmentandadmissionID'] = $appointmentID;
                        $heightWeightBpArray['date']                      = $appointmentArray['appointmentdate'];
                        $heightWeightBpArray['height']                    = '';
                        $heightWeightBpArray['weight']                    = '';
                        $heightWeightBpArray['bp']                        = '';

                        $this->heightweightbp_m->insert_heightweightbp($heightWeightBpArray);
                    }

                    $method       = $this->input->post('paymentmethodID');
                    $gatewayArray = [
                        'slug'          => $method,
                        'amount'        => $appointmentArray['amount'],
                        'stripeToken'   => $this->input->post('stripeToken'),
                        'currency_code' => $this->data['generalsettings']->currency_code,
                        'appointmentID' => $appointmentID,
                    ];

                    $message = 'Success';
                    $f       = false;
                    if (in_array($method, pluck($this->paymentGateway, 'slug'))) {
                        $retArray = $this->payment->$method($gatewayArray);
                        if (!$retArray['status']) {
                            $message = $retArray['message'];
                            $f       = true;
                        }
                    }

                    if ($f) {
                        $this->session->set_flashdata('error', $message);
                    } else {
                        $this->session->set_flashdata('success', $message);
                    }

                    redirect(site_url('appointment/index/' . $this->data['displayDateStrtotime'] . '/' . $this->data['displayDoctorID']));
                }
            } else {
                $this->data["subview"] = 'appointment/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'appointment/index';
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function _forEmailSend($array)
    {
        $patientID = $array['patientID'];
        $patient   = $this->patient_m->get_single_patient(array('patientID' => $patientID));
        if (inicompute($patient) && $patient->email) {
            $doctorID = $array['doctorID'];
            $userID   = $array['create_userID'];

            $designation = [];
            $doctor      = $this->user_m->get_single_user(array('userID' => $doctorID));
            if (inicompute($doctor)) {
                $designation = $this->designation_m->get_single_designation(array('designationID' => $doctor->designationID));
            }
            $user = $this->user_m->get_single_user(array('userID' => $userID));

            $passArray                      = $array;
            $passArray['doctorName']        = inicompute($doctor) ? $doctor->name : '';
            $passArray['doctorDesignation'] = inicompute($designation) ? $designation->designation : '';
            $passArray['userName']          = inicompute($user) ? $user->name : '';
            $passArray['patient']           = $patient;
            $passArray['generalsettings']   = $this->data['generalsettings'];

            $message = $this->load->view('appointment/mail', $passArray, true);
            $message = trim($message);
            $subject = $this->lang->line('appointment_patient') . " " . $this->lang->line('appointment_appointment');
            $email   = $patient->email;
            @$this->mail->sendmail($this->data, $email, $subject, $message);
        }
    }

    public function edit()
    {
        $appointmentID       = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate         = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID     = htmlentities(escapeString($this->uri->segment(5)));
        $appointDisplayArray = $this->_appointmentDisplayArrayGenerate($displayDate, $displayDoctorID);

        if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/datetimepicker/css/datetimepicker.css',
                    'assets/datepicker/dist/css/bootstrap-datepicker.css',
                ),
                'js'  => array(
                    'assets/select2/select2.js',
                    'assets/datetimepicker/js/datetimepicker.js',
                    'assets/datepicker/dist/js/bootstrap-datepicker.js',
                    'assets/inilabs/appointment/index.js',
                ),
            );

            $paymentViews = [];
            if (inicompute($this->paymentGateway)) {
                foreach ($this->paymentGateway as $payGateway) {
                    if ($payGateway->misc) {
                        $miscArray = json_decode($payGateway->misc, true);

                        if (isset($miscArray['cdn'])) {
                            foreach ($miscArray['cdn'] as $key => $cdn) {
                                $this->data['footerassets']['cdn'][] = $cdn;
                            }
                        }

                        if (isset($miscArray['js'])) {
                            foreach ($miscArray['js'] as $key => $js) {
                                $this->data['footerassets']['js'][] = $js;
                            }
                        }

                        if (isset($miscArray['css'])) {
                            foreach ($miscArray['css'] as $key => $css) {
                                $this->data['headerassets']['css'][] = $css;
                            }
                        }

                        if (isset($miscArray['view'])) {
                            foreach ($miscArray['view'] as $key => $view) {
                                $paymentViews[] = $view;
                            }
                        }
                    }
                }
            }
            $this->data['paymentViews'] = $paymentViews;

            $queryArray['appointmentID'] = $appointmentID;
            $queryArray['status']        = 0;
            if ($this->data['loginroleID'] == 2) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            } elseif ($this->data['loginroleID'] == 3) {
                $queryArray['patientID'] = $this->data['loginuserID'];
            }

            $this->data['appointment'] = $this->appointment_m->get_select_appointment('*', $queryArray, true);
            if (inicompute($this->data['appointment'])) {
                $this->data['displayType'] = 'edit';
                $this->data['patients']    = $this->patient_m->get_select_patient('patientID, name', ['delete_at' => 0]);
                $this->appointment_m->order('create_date asc');
                $this->data['appointments']  = $this->appointment_m->get_select_appointment_patient('appointment.paymentstatus,appointment.type,appointment.appointmentID, appointment.patientID, appointment.doctorID, appointment.appointmentdate, appointment.status, patient.name', $appointDisplayArray);
                $this->data['tpas']          = $this->tpa_m->get_select_tpa('tpaID, name');
                $this->data['paymentstatus'] = $this->data['appointment']->paymentstatus;
                $this->data['jsmanager']     = ['displayType' => $this->data['displayType'], 'startDate' => ((strtotime(date('Y-m-d')) < strtotime($this->data['appointment']->appointmentdate)) ? date('Y-m-d') : date('Y-m-d', strtotime($this->data['appointment']->appointmentdate))), 'paymentstatus' => $this->data['paymentstatus'], 'strDisplayDate' => (($displayDate == '') ? strtotime(date('d-m-Y')) : $displayDate), 'appointmentID' => $appointmentID, 'loginuserID' => $this->session->userdata('loginuserID')];

                $this->data['departments'] = $this->department_m->get_department();
                if ($this->input->post('doctorID')) {
                    $typeDoctorID = $this->input->post('doctorID');
                } else {
                    $typeDoctorID = $this->data['appointment']->doctorID;
                }
                $this->data['types']           = $this->_type($typeDoctorID);
                $this->data['payment_options'] = pluck($this->payment_gateway_option_m->get_payment_gateway_option(), 'payment_value', 'payment_option');

                $doctorQueryArray['designationID'] = 3;
                if ($this->input->post('departmentID')) {
                    $doctorQueryArray['departmentID'] = $this->input->post('departmentID');
                }
                $this->data['doctors']              = pluck($this->user_m->get_select_doctor_with_doctorinfo('userID, name', $doctorQueryArray), 'obj', 'userID');
                $this->data['paymentmethodArray']   = $this->_getPaymentMethod();
                $this->data['paymentmethodIDArray'] = $this->_getPaymentMethodNameByID();

                if ($_POST) {
                    $this->data['paymentstatus'] = $this->input->post('paymentstatus');
                    $this->data['jsmanager']     = ['displayType' => $this->data['displayType'], 'startDate' => ((strtotime(date('Y-m-d')) < strtotime($this->data['appointment']->appointmentdate)) ? date('Y-m-d') : date('Y-m-d', strtotime($this->data['appointment']->appointmentdate))), 'paymentstatus' => $this->data['paymentstatus'], 'strDisplayDate' => (($displayDate == '') ? strtotime(date('d-m-Y')) : $displayDate), 'appointmentID' => $appointmentID, 'loginuserID' => $this->session->userdata('loginuserID')];

                    if ($this->input->post('paymentstatus')) {
                        $paymentStatus = $this->input->post('paymentstatus');
                    } else {
                        $paymentStatus = $this->data['appointment']->paymentstatus;
                    }

                    $rulesArray = ['paymentstatus' => $paymentStatus];
                    $rules      = $this->rules($rulesArray);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = 'appointment/edit';
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $appointmentArray['patientID']       = $this->input->post('uhid');
                        $appointmentArray['appointmentdate'] = date('Y-m-d H:i:s', strtotime($this->input->post('appointmentdate')));
                        $appointmentArray['pcase']           = $this->input->post('case');
                        $appointmentArray['casualty']        = $this->input->post('casualty');
                        $appointmentArray['oldpatient']      = $this->input->post('oldpatient');
                        $appointmentArray['tpaID']           = $this->input->post('tpaID');
                        $appointmentArray['reference']       = $this->input->post('reference');
                        $appointmentArray['doctorID']        = $this->input->post('doctorID');
                        $appointmentArray['amount']          = $this->input->post('amount');
                        $appointmentArray['paymentstatus']   = $this->input->post('paymentstatus');
                        $appointmentArray['symptoms']        = $this->input->post('symptoms');
                        $appointmentArray['allergies']       = $this->input->post('allergies');
                        $appointmentArray['note']            = '';
                        $appointmentArray['modify_date']     = date("Y-m-d H:i:s");
                        $appointmentArray['type']            = $this->input->post('type');
                        $appointmentArray['duration']            = 40;


                        if($this->input->post('type')==2){
                            $zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
                            $tokenInfo 		= json_decode($zoomSetting->data, true);
                            $refreshToken 	= $this->zoom->refreshToken($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo['refresh_token']);

                            if($refreshToken->status) {

                                if(isset($refreshToken->data)) {
                                    $this->zoomsettings_m->update_zoomsettings(['data' => json_encode($refreshToken->data)], 1);
                                }

                                $zoomSetting 	= $this->zoomsettings_m->get_zoomsettings(1);
                                $tokenInfo 		= json_decode($zoomSetting->data, true);
                                $response 		= $this->zoom->deleteMetting($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo,$this->data['appointment']->metting_id);

                                if($response->status) {
                                    if(isset($response->update_token)) {
                                        $this->zoomsettings_m->update_zoomsettings(['data' => json_encode($response->update_token)], 1);
                                    }
                                } else {
                                    $this->session->set_flashdata('error', $response->message);
                                }

                                $response = $this->zoom->createMetting($zoomSetting->client_id, $zoomSetting->client_secret, $tokenInfo, $appointmentArray);

                                if($response->status) {
                                    if(isset($response->update_token)) {
                                        $this->zoomsettings_m->update_zoomsettings(['data' => json_encode($response->update_token)], 1);
                                    }

                                    if($response->data) {
                                        $appointmentArray['join_url'] = $response->data['join_url'];
                                        $appointmentArray['password'] = $response->data['password'];
                                        $appointmentArray['metting_id'] = $response->data['metting_id'];
                                    }
                                }
                            }
                        }

                        if ($this->input->post('paymentstatus') == 1) {
                            $appointmentArray['paymentmethodID'] = isset($this->paymentMethod[$this->input->post('paymentmethodID')]) ? $this->paymentMethod[$this->input->post('paymentmethodID')] : 0;
                            $appointmentArray['paymentdate']     = date('Y-m-d H:i:s');
                        }

                        if ($this->data['loginroleID'] == 3) {
                            $appointmentArray['patientID']       = $this->data['loginuserID'];
                            $appointmentArray['tpaID']           = 0;
                            $appointmentArray['paymentstatus']   = 2;
                            $appointmentArray['paymentmethodID'] = 0;
                            $appointmentArray['paymentdate']     = '0000-00-00 00:00:00';

                            if ($this->data['appointment']->paymentstatus == 1) {
                                unset($appointmentArray['paymentdate']);
                                unset($appointmentArray['doctorID']);
                                unset($appointmentArray['type']);
                                unset($appointmentArray['amount']);
                                unset($appointmentArray['paymentstatus']);
                                unset($appointmentArray['paymentmethodID']);
                            }
                        } else {
                            if (!isset($this->paymentMethod[$this->input->post('paymentmethodID')])) {
                                $appointmentArray['paymentstatus']   = 2;
                                $appointmentArray['paymentmethodID'] = 0;
                                $appointmentArray['paymentdate']     = '0000-00-00 00:00:00';
                            }
                        }
                        $this->appointment_m->update_appointment($appointmentArray, $appointmentID);

                        if ($this->data['appointment']->patientID != (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid'))) {
                            $patientTypeID = $this->_patientType($appointmentID, $this->data['appointment']->patientID);
                            $this->patient_m->update_patient(array('patienttypeID' => $patientTypeID), $this->data['appointment']->patientID);
                            $this->patient_m->update_patient(array('patienttypeID' => 0), (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid')));
                        }

                        $heightweightbp = $this->heightweightbp_m->get_single_heightweightbp(array('patienttypeID' => 0, 'appointmentandadmissionID' => $appointmentID));
                        if (inicompute($heightweightbp)) {
                            $heightWeightBpArray['patientID']                 = (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid'));
                            $heightWeightBpArray['appointmentandadmissionID'] = $appointmentID;
                            $heightWeightBpArray['date']                      = $appointmentArray['appointmentdate'];
                            $this->heightweightbp_m->update_heightweightbp($heightWeightBpArray, $heightweightbp->heightweightbpID);
                        } else {
                            $heightWeightBpArray['patientID']                 = (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid'));
                            $heightWeightBpArray['patienttypeID']             = 0;
                            $heightWeightBpArray['appointmentandadmissionID'] = $appointmentID;
                            $heightWeightBpArray['date']                      = $appointmentArray['appointmentdate'];
                            $heightWeightBpArray['height']                    = '';
                            $heightWeightBpArray['weight']                    = '';
                            $heightWeightBpArray['bp']                        = '';
                            $this->heightweightbp_m->insert_heightweightbp($heightWeightBpArray);
                        }

                        $method       = $this->input->post('paymentmethodID');
                        $gatewayArray = [
                            'slug'          => $method,
                            'amount'        => $appointmentArray['amount'],
                            'stripeToken'   => $this->input->post('stripeToken'),
                            'currency_code' => $this->data['generalsettings']->currency_code,
                            'appointmentID' => $appointmentID,
                        ];

                        $message = 'Success';
                        $f       = false;
                        if (in_array($method, pluck($this->paymentGateway, 'slug'))) {
                            $retArray = $this->payment->$method($gatewayArray);
                            if (!$retArray['status']) {
                                $message = $retArray['message'];
                                $f       = true;
                            }
                        }

                        if ($f) {
                            $this->session->set_flashdata('error', $message);
                        } else {
                            $this->session->set_flashdata('success', $message);
                        }
                        redirect(site_url('appointment/index/' . $displayDate . '/' . $displayDoctorID));
                    }
                } else {
                    $this->data["subview"] = 'appointment/edit';
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
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/inilabs/appointment/view.js',
            ),
        );
        $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));
        $this->_appointmentDisplayArrayGenerate($displayDate, $displayDoctorID);

        if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
            $queryArray['appointmentID'] = $appointmentID;
            if ($this->data['loginroleID'] == 2) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            } elseif ($this->data['loginroleID'] == 3) {
                $queryArray['patientID'] = $this->data['loginuserID'];
            }

            $this->data['appointment'] = $this->appointment_m->get_select_appointment('*', $queryArray, true);
            if (inicompute($this->data['appointment'])) {
                $this->data['jsmanager']      = ['appointmentID' => $this->data['appointment']->appointmentID, 'date' => $this->data['displayDateStrtotime'], 'doctorID' => $this->data['displayDoctorID']];
                $this->data['designations']   = pluck($this->designation_m->get_select_designation('designationID, designation'), 'designation', 'designationID');
                $this->data['paymentmethods'] = $this->_getPaymentMethodNameByID();
                $this->data['paymentstatus']  = [
                    0 => '',
                    1 => $this->lang->line('appointment_paid'),
                    2 => $this->lang->line('appointment_due'),
                ];
                $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $this->data['appointment']->patientID));
                $this->data['user']    = $this->user_m->get_single_user(array('patientID' => $this->data['appointment']->patientID));
                $this->data['tpa']     = $this->tpa_m->get_select_tpa('tpaID, name', array('tpaID' => $this->data['appointment']->tpaID), true);
                $this->data['doctor']  = $this->user_m->get_select_user('userID, name', array('userID' => $this->data['appointment']->doctorID, 'roleID' => 2), true);
                $this->data["subview"] = 'appointment/view';
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


    public function zoomview()
    {


        $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));
        $this->_appointmentDisplayArrayGenerate($displayDate, $displayDoctorID);

        if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
            $queryArray['appointmentID'] = $appointmentID;
            if ($this->data['loginroleID'] == 2) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            } elseif ($this->data['loginroleID'] == 3) {
                $queryArray['patientID'] = $this->data['loginuserID'];
            }
            $this->data['appointment'] = $this->appointment_m->get_select_appointment('*', $queryArray, true);
            if (inicompute($this->data['appointment'])) {
                $this->data['zoomsetting'] 	= $this->zoomsettings_m->get_single_zoomsettings(['id' => 1]);
                $this->data["subview"] = 'appointment/zoomview';
                $this->load->view($this->data["subview"], $this->data);
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
        $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

        if (permissionChecker('appointment_view')) {
            if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
                $queryArray['appointmentID'] = $appointmentID;
                if ($this->data['loginroleID'] == 2) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                } elseif ($this->data['loginroleID'] == 3) {
                    $queryArray['patientID'] = $this->data['loginuserID'];
                }
                $this->data['appointment'] = $this->appointment_m->get_select_appointment('*', $queryArray, true);
                if (inicompute($this->data['appointment'])) {
                    $this->data['designations']   = pluck($this->designation_m->get_select_designation('designationID, designation'), 'designation', 'designationID');
                    $this->data['paymentmethods'] = $this->_getPaymentMethodNameByID();
                    $this->data['paymentstatus']  = [
                        0 => '',
                        1 => $this->lang->line('appointment_paid'),
                        2 => $this->lang->line('appointment_due'),
                    ];
                    $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $this->data['appointment']->patientID));
                    $this->data['user']    = $this->user_m->get_single_user(array('patientID' => $this->data['appointment']->patientID));
                    $this->data['tpa']     = $this->tpa_m->get_select_tpa('tpaID, name', array('tpaID' => $this->data['appointment']->tpaID), true);
                    $this->data['doctor']  = $this->user_m->get_select_user('userID, name', array('userID' => $this->data['appointment']->doctorID, 'roleID' => 2), true);

                    $this->report->reportPDF(['stylesheet' => 'appointmentmodule.css', 'data' => $this->data, 'viewpath' => 'appointment/printpreview']);
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
        if (permissionChecker('appointment_view')) {
            if ($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $appointmentID   = $this->input->post('appointmentID');
                    $displayDate     = $this->input->post('date');
                    $displayDoctorID = $this->input->post('doctorID');

                    if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
                        $email   = $this->input->post('to');
                        $subject = $this->input->post('subject');
                        $message = $this->input->post('message');

                        $queryArray['appointmentID'] = $appointmentID;
                        if ($this->data['loginroleID'] == 2) {
                            $queryArray['doctorID'] = $this->data['loginuserID'];
                        } elseif ($this->data['loginroleID'] == 3) {
                            $queryArray['patientID'] = $this->data['loginuserID'];
                        }

                        $this->data['appointment'] = $this->appointment_m->get_select_appointment('*', $queryArray, true);
                        if (inicompute($this->data['appointment'])) {
                            $this->data['designations']   = pluck($this->designation_m->get_select_designation('designationID, designation'), 'designation', 'designationID');
                            $this->data['paymentmethods'] = $this->_getPaymentMethodNameByID();
                            $this->data['paymentstatus']  = [
                                0 => '',
                                1 => $this->lang->line('appointment_paid'),
                                2 => $this->lang->line('appointment_due'),
                            ];
                            $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $this->data['appointment']->patientID));
                            $this->data['user']    = $this->user_m->get_single_user(array('patientID' => $this->data['appointment']->patientID));
                            $this->data['tpa']     = $this->tpa_m->get_select_tpa('tpaID, name', array('tpaID' => $this->data['appointment']->tpaID), true);
                            $this->data['doctor']  = $this->user_m->get_select_user('userID, name', array('userID' => $this->data['appointment']->doctorID, 'roleID' => 2), true);

                            $this->report->reportSendToMail(['stylesheet' => 'appointmentmodule.css', 'data' => $this->data, 'viewpath' => 'appointment/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = true;
                        } else {
                            $retArray['message'] = $this->lang->line('appointment_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('appointment_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('appointment_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('appointment_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("appointment_to"),
                'rules' => 'trim|required|valid_email',
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("appointment_subject"),
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("appointment_message"),
                'rules' => 'trim',
            ),
            array(
                'field' => 'appointmentID',
                'label' => $this->lang->line("appointment_appointment"),
                'rules' => 'trim|required|numeric',
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("appointment_date"),
                'rules' => 'trim',
            ),
            array(
                'field' => 'doctorID',
                'label' => $this->lang->line("appointment_doctor"),
                'rules' => 'trim',
            ),
        );
        return $rules;
    }

    public function delete()
    {
        $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));
        if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
            $queryArray['appointmentID'] = $appointmentID;
            $queryArray['status']        = 0;
            if ($this->data['loginroleID'] == 2) {
                $queryArray['doctorID'] = $this->data['loginuserID'];
            } elseif ($this->data['loginroleID'] == 3) {
                $queryArray['patientID'] = $this->data['loginuserID'];
            }
            $appointment = $this->appointment_m->get_select_appointment('*', $queryArray, true);
            if (inicompute($appointment)) {
                $patientTypeID = $this->_patientType($appointmentID, $appointment->patientID);
                $this->patient_m->update_patient(array('patienttypeID' => $patientTypeID), $appointment->patientID);

                $heightweightbp = $this->heightweightbp_m->get_single_heightweightbp(['patienttypeID' => 0, 'appointmentandadmissionID' => $appointmentID]);
                if (inicompute($heightweightbp)) {
                    $this->heightweightbp_m->delete_heightweightbp($heightweightbp->heightweightbpID);
                }
                $this->appointment_m->delete_appointment($appointmentID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('appointment/index/' . $displayDate . '/' . $displayDoctorID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function valid_date($date)
    {
        if ($date) {
            if (strlen($date) < 19) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                return false;
            } else {
                $arr = explode("-", $date);
                if (inicompute($arr) > 2) {
                    $dd   = $arr[0];
                    $mm   = $arr[1];
                    $yyyy = explode(" ", $arr[2]);
                    if (inicompute($yyyy) > 1) {
                        $yyyy = $yyyy[0];
                        if (checkdate($mm, $dd, $yyyy)) {
                            $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
                            $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
                            $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

                            if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
                                if (strtotime($date) < strtotime($this->data['appointment']->appointmentdate) && strtotime($date) < strtotime(date('Y-m-d'))) {
                                    $this->form_validation->set_message("valid_date", "The %s can not take past date.");
                                    return false;
                                }
                                return true;
                            } else {
                                if (strtotime(date('d-m-Y')) > strtotime($date)) {
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

    public function unique_appointment()
    {
        $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));
        $appointmentdate = date('Y-m-d', strtotime($this->input->post('appointmentdate')));
        $patientID       = (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid'));
        $doctorID        = $this->input->post('doctorID');

        if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
            $appointment = $this->appointment_m->get_single_appointment(array('patientID' => $patientID, 'DATE(appointmentdate)' => $appointmentdate, 'doctorID' => $doctorID, 'status' => 0, 'appointmentID !=' => $appointmentID));
            if (inicompute($appointment)) {
                $this->form_validation->set_message('unique_appointment', 'The appointment was made before.');
                return false;
            }
            return true;
        } else {
            $appointment = $this->appointment_m->get_single_appointment_date($appointmentdate, ['patientID' => $patientID, 'doctorID' => $doctorID, 'status' => 0]);
            if (inicompute($appointment)) {
                $this->form_validation->set_message('unique_appointment', 'The appointment was made before.');
                return false;
            }
            return true;
        }
    }

    public function unique_admission()
    {
        $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));
        $patientID       = (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid'));

        if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
            $admission = $this->admission_m->get_single_admission(array('patientID' => $patientID, 'status' => 0));
            if (inicompute($admission)) {
                $this->form_validation->set_message('unique_admission', 'The patient has already admitted.');
                return false;
            }
            return true;
        } else {
            $admission = $this->admission_m->get_single_admission(array('patientID' => $patientID, 'status' => 0));
            if (inicompute($admission)) {
                $this->form_validation->set_message('unique_admission', 'The patient has already admitted.');
                return false;
            }
            return true;
        }
    }

    public function unique_oldpatient()
    {
        $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

        $patientID = (($this->data['loginroleID'] == 3) ? $this->data['loginuserID'] : $this->input->post('uhid'));
        if ($this->input->post('oldpatient') == 0) {
            if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
                $appointment = $this->appointment_m->get_single_appointment(array('patientID' => $patientID, 'doctorID' => $this->input->post('doctorID'), 'status' => 1, 'appointmentID !=' => $appointmentID));
                if (!inicompute($appointment)) {
                    $this->form_validation->set_message('unique_oldpatient', 'This is the new patient.');
                    return false;
                }
                return true;
            } else {
                $appointment = $this->appointment_m->get_single_appointment(array('patientID' => $patientID, 'doctorID' => $this->input->post('doctorID'), 'status' => 1));
                if (!inicompute($appointment)) {
                    $this->form_validation->set_message('unique_oldpatient', 'This is the new patient.');
                    return false;
                }
                return true;
            }
        }
        return true;
    }

    public function required_no_zero($data)
    {
        if ($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return false;
        }
        return true;
    }

    private function _patientType($appointmentID, $patientID)
    {
        $mergeData    = [];
        $returnData   = 5;
        $appointments = $this->appointment_m->get_order_by_appointment(array('patientID' => $patientID));
        $admissions   = $this->admission_m->get_order_by_admission(array('patientID' => $patientID));
        if (inicompute($appointments)) {
            foreach ($appointments as $appointment) {
                if ($appointment->appointmentID != $appointmentID) {
                    $mergeData[strtotime($appointment->create_date)] = $appointment->appointmentID;
                }
            }
        }

        if (inicompute($admissions)) {
            foreach ($admissions as $admission) {
                $mergeData[strtotime($admission->create_date)] = $admission->admissionID;
            }
        }

        if (inicompute($mergeData)) {
            arsort($mergeData);
            $firstData = [];
            if (inicompute($mergeData)) {
                foreach ($mergeData as $key => $mr) {
                    $firstData = [$key => $mr];
                    break;}
                if (inicompute($firstData)) {
                    $date        = date('Y-m-d H:i:s', key($firstData));
                    $id          = $firstData[key($firstData)];
                    $appointment = $this->appointment_m->get_single_appointment(array('appointmentID' => $id, 'create_date' => $date, 'patientID' => $patientID));
                    if (inicompute($appointment)) {
                        $returnData = 0;
                    } else {
                        $admission = $this->admission_m->get_single_admission(array('admissionID' => $id, 'create_date' => $date, 'patientID' => $patientID));
                        if (inicompute($admission)) {
                            $returnData = 1;
                        }
                    }
                }
            }
        }
        return $returnData;
    }

    public function strtotimegenerate()
    {
        $date = $this->input->post('date');
        echo strtotime($date);
    }

    public function prescription()
    {
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/inilabs/appointment/prescription.js',
            ),
        );

        $appointmentID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayDate     = htmlentities(escapeString($this->uri->segment(4)));
        $displayDoctorID = htmlentities(escapeString($this->uri->segment(5)));

        if (permissionChecker('appointment_view')) {
            if (((int) $appointmentID && (int) $displayDate) && ($displayDoctorID == 0 || $displayDoctorID > 0)) {
                $queryArray['appointmentID'] = $appointmentID;
                $queryArray['status']        = 1;
                if ($this->data['loginroleID'] == 2) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                } elseif ($this->data['loginroleID'] == 3) {
                    $queryArray['patientID'] = $this->data['loginuserID'];
                }

                $this->data['appointment'] = $this->appointment_m->get_single_appointment($queryArray);
                if (inicompute($this->data['appointment'])) {
                    $this->data['jsmanager']    = ['appointmentID' => $this->data['appointment']->appointmentID];
                    $this->data['prescription'] = $this->prescription_m->get_single_prescription(['patienttypeID' => 0, 'appointmentandadmissionID' => $this->data['appointment']->appointmentID]);
                    if (inicompute($this->data['prescription'])) {
                        $this->data['displayDate']     = $displayDate;
                        $this->data['displayDoctorID'] = $displayDoctorID;
                        $this->data['patientinfo']     = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                        $this->data['gender']          = [0 => '', 1 => 'M', 2 => 'F'];

                        if ($this->data['prescription']->patienttypeID == 0) {
                            $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                        } else {
                            $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                        }

                        $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                        $this->prescriptionitem_m->order('prescriptionitemID asc');
                        $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $this->data['prescription']->prescriptionID]);

                        $this->data["subview"] = 'appointment/prescription';
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
        if (permissionChecker('appointment_view')) {
            $appointmentID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int) $appointmentID) {
                $queryArray['appointmentID'] = $appointmentID;
                $queryArray['status']        = 1;
                if ($this->data['loginroleID'] == 2) {
                    $queryArray['doctorID'] = $this->data['loginuserID'];
                } elseif ($this->data['loginroleID'] == 3) {
                    $queryArray['patientID'] = $this->data['loginuserID'];
                }

                $this->data['appointment'] = $this->appointment_m->get_single_appointment($queryArray);
                if (inicompute($this->data['appointment'])) {
                    $this->data['prescription'] = $this->prescription_m->get_single_prescription(['patienttypeID' => 0, 'appointmentandadmissionID' => $this->data['appointment']->appointmentID]);
                    if (inicompute($this->data['prescription'])) {
                        $this->data['patientinfo'] = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                        $this->data['gender']      = [0 => '', 1 => 'M', 2 => 'F'];

                        if ($this->data['prescription']->patienttypeID == 0) {
                            $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                        } else {
                            $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                        }

                        $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                        $this->prescriptionitem_m->order('prescriptionitemID asc');
                        $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $this->data['prescription']->prescriptionID]);

                        $this->report->reportPDF(['stylesheet' => 'prescriptionprintpreviewmodule.css', 'data' => $this->data, 'viewpath' => 'appointment/prescriptionprintpreview']);
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
        if (permissionChecker('appointment_view')) {
            if ($_POST) {
                $rules = $this->prescriptionsendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $appointmentID = $this->input->post('appointmentID');
                    if ((int) $appointmentID) {
                        $queryArray['appointmentID'] = $appointmentID;
                        $queryArray['status']        = 1;
                        if ($this->data['loginroleID'] == 2) {
                            $queryArray['doctorID'] = $this->data['loginuserID'];
                        } elseif ($this->data['loginroleID'] == 3) {
                            $queryArray['patientID'] = $this->data['loginuserID'];
                        }

                        $this->data['appointment'] = $this->appointment_m->get_single_appointment($queryArray);
                        if (inicompute($this->data['appointment'])) {
                            $this->data['prescription'] = $this->prescription_m->get_single_prescription(['patienttypeID' => 0, 'appointmentandadmissionID' => $this->data['appointment']->appointmentID]);
                            if (inicompute($this->data['prescription'])) {
                                $this->data['patientinfo'] = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                                $this->data['gender']      = [0 => '', 1 => 'M', 2 => 'F'];

                                if ($this->data['prescription']->patienttypeID == 0) {
                                    $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                                } else {
                                    $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                                }

                                $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                                $this->prescriptionitem_m->order('prescriptionitemID asc');
                                $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $this->data['prescription']->prescriptionID]);

                                $email   = $this->input->post('to');
                                $subject = $this->input->post('subject');
                                $message = $this->input->post('message');

                                $this->report->reportSendToMail(['stylesheet' => 'prescriptionprintpreviewmodule.css', 'data' => $this->data, 'viewpath' => 'appointment/prescriptionprintpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                $retArray['message'] = "Success";
                                $retArray['status']  = true;
                            } else {
                                $retArray['message'] = $this->lang->line('appointment_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('appointment_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('appointment_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('appointment_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('appointment_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function prescriptionsendmail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("appointment_to"),
                'rules' => 'trim|required|valid_email',
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("appointment_subject"),
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("appointment_message"),
                'rules' => 'trim',
            ),
            array(
                'field' => 'appointmentID',
                'label' => $this->lang->line("appointment_appointment"),
                'rules' => 'trim|required|numeric',
            ),
        );
        return $rules;
    }

    public function getdoctor()
    {
        echo "<option value='0'>— " . $this->lang->line('appointment_please_select') . " —</option>";

        if ($_POST) {
            $departmentID = $this->input->post('departmentID');
            if($departmentID == 0) {
                $doctors = $this->user_m->get_select_doctor_with_doctorinfo('userID, name', array('designationID' => 3));
            } else {
                $doctors = $this->user_m->get_select_doctor_with_doctorinfo('userID, name', array('designationID' => 3, 'departmentID' => $departmentID));
            }

            if (inicompute($doctors)) {
                foreach ($doctors as $doctor) {
                    echo "<option value='" . $doctor->userID . "'>" . $doctor->name . "</option>";
                }
            }
        }
    }

    public function gettype()
    {
        if ($_POST) {
            $userID = $this->input->post('doctorID');
            $types  = $this->_type($userID);
            if (inicompute($types)) {
                foreach ($types as $typeKey => $typeValue) {
                    echo "<option value='" . $typeKey . "'>" . $typeValue . "</option>";
                }
            }
        }
    }

    private function _type($userID)
    {
        $retArray['0'] = $this->lang->line('appointment_please_select');
        $retArray['1'] = $this->lang->line('appointment_live_visit');
        if ((int) $userID) {
            $doctorinfo = $this->doctorinfo_m->get_single_doctorinfo(['userID' => $userID, 'online_consultation' => 1]);
            if (inicompute($doctorinfo)) {
                $retArray['2'] = $this->lang->line('appointment_online');
            }
        }
        return $retArray;
    }

    public function getAmount()
    {
        if ($_POST) {
            $type   = $this->input->post('type');
            $userID = $this->input->post('userID');
            if ($type > 0 && $userID) {
                $queryArray['userID'] = $userID;
                $doctorinfo           = $this->doctorinfo_m->get_single_doctorinfo($queryArray);
                if (inicompute($doctorinfo)) {
                    if ($type == 2) {
                        echo $doctorinfo->consultation_fee;
                    } else {
                        echo $doctorinfo->visit_fee;
                    }
                }
            }
        }
    }

    public function appointment_amount_check($amount)
    {
        $typeAmount = 0;
        $type       = $this->input->post('type');
        $userID     = $this->input->post('doctorID');
        if ($type > 0 && $userID) {
            $queryArray['userID'] = $userID;
            $doctorinfo           = $this->doctorinfo_m->get_single_doctorinfo($queryArray);
            if (inicompute($doctorinfo)) {
                if ($type == 2) {
                    $typeAmount = $doctorinfo->consultation_fee;
                } else {
                    $typeAmount = $doctorinfo->visit_fee;
                }
            }
        }
        if (!($amount == $typeAmount)) {
            $this->form_validation->set_message("appointment_amount_check", "The %s field value not correct please provide corrent value.");
            return false;
        }
        return true;
    }

}
