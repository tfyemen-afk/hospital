<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billpayment extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bill_m');
        $this->load->model('billlabel_m');
        $this->load->model('billitems_m');
        $this->load->model('billpayment_m');
        $this->load->model('patient_m');
        $this->load->model('designation_m');
        $this->load->model('bloodgroup_m');
        $this->load->model('appointment_m');
        $this->load->model('admission_m');
        $this->load->model('user_m');
        $this->load->library('report');

        $language = $this->session->userdata('lang');
        $this->lang->load('billpayment', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line("billpayment_reference_no"),
                'rules' => 'trim|max_length[40]'
            ),
            array(
                'field' => 'amount',
                'label' => $this->lang->line("billpayment_amount"),
                'rules' => 'trim|required|numeric|max_length[16]|callback_unique_amount'
            ),
            array(
                'field' => 'paymentmethod',
                'label' => $this->lang->line("billpayment_payment_method"),
                'rules' => 'trim|required|numeric|max_length[1]|callback_required_no_zero'
            )
        );
        return $rules;
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
                'assets/inilabs/billpayment/index.js',
            ]
        ];

        $displayID                  = htmlentities(escapeString($this->uri->segment(3)));
        $displayuhID                = htmlentities(escapeString($this->uri->segment(4)));
        $billPaymentDisplayArray    = $this->_billPaymentDisplayArrayGenerate($displayID, $displayuhID);
        $dueAmount                  = $this->_dueCalculation($this->data['displayuhID']);

        $this->billpayment_m->order('billpayment.paymentdate desc');
        $this->data['billpayments']     = $this->billpayment_m->get_order_by_billpayment_patient('billpayment.billpaymentID, billpayment.patientID, patient.name, billpayment.paymentdate, billpayment.paymentmethod, billpayment.paymentamount', $billPaymentDisplayArray['displayArray']);
        $this->data['paymentmethods']   = [
            '1'  => $this->lang->line('billpayment_cash'),
            '2'  => $this->lang->line('billpayment_cheque'),
            '3'  => $this->lang->line('billpayment_credit_card'),
            '4'  => $this->lang->line('billpayment_other')
        ];
        $patientQueryArray['delete_at'] = 0;
        if($this->data['loginroleID'] == 3) {
            $patientQueryArray['patientID'] = $this->data['loginuserID'];
        }

        $this->data['patients']  = $this->patient_m->get_select_patient('patientID, name', $patientQueryArray);
        $this->data['dueamount'] = $dueAmount;
        $this->data['jsmanager'] = ['displayID' => $this->data['displayID']];

        if(permissionChecker('billpayment_add')) {
            $this->_dueBillList($this->data['displayuhID']);
            if($_POST) {
                $this->session->set_flashdata('billpaymentother', true);
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if($this->form_validation->run()) {
                    $patient = $this->patient_m->get_single_patient(['patientID' => $this->data['displayuhID'], 'delete_at' => 0]);
                    $paymentArray = array(
                        'patientID'                 => $this->data['displayuhID'],
                        'patienttypeID'             => inicompute($patient) ? $patient->patienttypeID: '',
                        'appointmentandadmissionID' => $this->_appointmentAndAdmission($this->data['displayuhID'], $patient->patienttypeID),
                        'paymentdate'               => date('Y-m-d H:i:s'),
                        'reference_no'              => $this->input->post('reference_no'),
                        'paymentamount'             => app_currency_format($this->input->post('amount')),
                        'paymentmethod'             => $this->input->post('paymentmethod'),
                        'billID'                    => 0,
                        'permission'                => 1,
                        'create_date'               => date("Y-m-d H:i:s"),
                        'modify_date'               => date("Y-m-d H:i:s"),
                        'create_userID'             => $this->session->userdata('loginuserID'),
                        'create_roleID'             => $this->session->userdata('roleID'),
                        'delete_at'                 => 0,
                        'paymentby'                 => 1,
                    );

                    $this->billpayment_m->insert_billpayment($paymentArray);
                    $billpaymentID = $this->db->insert_id();
                    $this->_calculation($this->data['displayuhID']);
                    $this->session->set_flashdata('success','Success');
                    if(permissionChecker('billpayment_view')) {
                        redirect(site_url("billpayment/view/".$billpaymentID.'/'.$this->data['displayID'].'/'.$this->data['displayuhID']));
                    } else {
                        redirect(site_url("billpayment/index/".$this->data['displayID'].'/'.$this->data['displayuhID']));
                    }
                }
            }
        }

        $this->data["subview"] = 'billpayment/index';
        $this->load->view('_layout_main', $this->data);
    }

    public function edit()
    {
        $billpaymentID  = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        $displayuhID    = htmlentities(escapeString($this->uri->segment(5)));

        if(((int)$billpaymentID && (int)$displayID) && ($displayuhID == 0 || $displayuhID > 0)) {
            $this->session->set_flashdata('billpaymentedit', true);
            $billPaymentEditArray['delete_at']      = 0;
            $billPaymentEditArray['billpaymentID']  = $billpaymentID;
            if($this->data['loginroleID'] == 3) {
                $billPaymentEditArray['patientID'] = $this->data['loginuserID'];
            }
            $this->data['billpayment']  = $this->billpayment_m->get_single_billpayment($billPaymentEditArray);
            if(inicompute($this->data['billpayment'])) {
                $this->data['patient']      = $this->patient_m->get_single_patient(['patientID' => $this->data['billpayment']->patientID]);
                $this->data['user']         = $this->user_m->get_single_user(['patientID'=> $this->data['billpayment']->patientID]);
                if(inicompute($this->data['patient']) && inicompute($this->data['user'])) {
                    $this->data['designation']   = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                    $this->data['headerassets'] = array(
                        'css' => array(
                            'assets/select2/css/select2.css',
                            'assets/select2/css/select2-bootstrap.css',
                        ),
                        'js' => array(
                            'assets/select2/select2.js',
                            'assets/inilabs/select2.js',
                        )
                    );

                    $this->data['billpaymentID']    = $billpaymentID;
                    $billPaymentDisplayArray        = $this->_billPaymentDisplayArrayGenerate($displayID, $displayuhID);
                    $this->billpayment_m->order('billpayment.paymentdate desc');
                    $this->data['billpayments']     = $this->billpayment_m->get_order_by_billpayment_patient('billpayment.billpaymentID, billpayment.patientID, patient.name, billpayment.paymentdate, billpayment.paymentmethod, billpayment.paymentamount', $billPaymentDisplayArray['displayArray']);

                    $this->data['paymentmethods']   = [
                        '1'  => $this->lang->line('billpayment_cash'),
                        '2'  => $this->lang->line('billpayment_cheque'),
                        '3'  => $this->lang->line('billpayment_credit_card'),
                        '4'  => $this->lang->line('billpayment_other')
                    ];
                    $this->data['dueamount'] = $this->_calculationForEdit($this->data['billpayment']);
                    if($_POST) {
                        $rules = $this->rules();
                        $this->form_validation->set_rules($rules);
                        if($this->form_validation->run()) {
                            $paymentArray = array(
                                'reference_no'              => $this->input->post('reference_no'),
                                'paymentamount'             => app_currency_format($this->input->post('amount')),
                                'paymentmethod'             => $this->input->post('paymentmethod'),
                                'modify_date'               => date("Y-m-d H:i:s")
                            );

                            $this->billpayment_m->update_billpayment($paymentArray, $billpaymentID);
                            $this->_calculation($this->data['billpayment']->patientID);
                            $this->session->set_flashdata('billpaymentedit', false);
                            $this->session->set_flashdata('success','Success');
                            if(permissionChecker('billpayment_view')) {
                                redirect(site_url("billpayment/view/".$billpaymentID.'/'.$displayID.'/'.$displayuhID));
                            } else {
                                redirect(site_url("billpayment/index/".$displayID.'/'.$displayuhID));
                            }
                        } else {
                            $this->data["subview"] = "billpayment/edit";
                            $this->load->view('_layout_main', $this->data);
                        }
                    } else {
                        $this->data["subview"] = "billpayment/edit";
                        $this->load->view('_layout_main', $this->data);
                    }
                } else {
                    $this->data["subview"] = "_not_found";
                    $this->load->view('_layout_main', $this->data);
                }
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
        $billpaymentID      = htmlentities(escapeString($this->uri->segment(3)));
        $displayID          = htmlentities(escapeString($this->uri->segment(4)));
        $displayuhID        = htmlentities(escapeString($this->uri->segment(5)));

        if(((int)$billpaymentID && (int)$displayID) && ($displayuhID == 0 || $displayuhID > 0)) {
            $billPaymentDeleteArray['delete_at']      = 0;
            $billPaymentDeleteArray['billpaymentID']  = $billpaymentID;
            if($this->data['loginroleID'] == 3) {
                $billPaymentDeleteArray['patientID']  = $this->data['loginuserID'];
            }
            $billPayment  = $this->billpayment_m->get_single_billpayment($billPaymentDeleteArray);

            if(inicompute($billPayment)) {
                if($billPayment->paymentby == 0) {
                    $billID = $billPayment->billID;
                    $this->bill_m->update_bill(['paymentstatus' => 1], $billID);
                }
                $this->billpayment_m->update(['delete_at' => 1], $billpaymentID);
                $this->_calculation($billPayment->patientID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('billpayment/index/'.$displayID.'/'.$displayuhID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function details()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/billpayment/details.js',
            ]
        ];
        $displayID      = htmlentities(escapeString($this->uri->segment(3)));
        $displayuhID    = htmlentities(escapeString($this->uri->segment(4)));

        if((int)$displayID && (int)$displayuhID) {
            if($this->data['loginroleID'] == 3) {
                $displayuhID = $this->data['loginuserID'];
            }
            $this->data['jsmanager'] = ['displayuhID' => $displayuhID];
            $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $displayuhID));
            if(inicompute($this->data['patient'])) {
                $this->data['user']  = $this->user_m->get_single_user(['patientID' => $this->data['patient']->patientID]);
                if(inicompute($this->data['user'])) {
                    $this->data['displayID']    = $displayID;
                    $this->data['displayuhID']  = $displayuhID;
                    $this->data['designation']  = $this->designation_m->get_single_designation(['designationID' => $this->data['user']->designationID]);
                    $this->data['billlabels']   = pluck($this->billlabel_m->get_billlabel(), 'name', 'billlabelID');

                    $this->billitems_m->order('create_date desc');
                    $this->data['bills']        = $this->bill_m->get_order_by_bill(['patientID' => $displayuhID,  'delete_at' => 0]);

                    $this->billitems_m->order('create_date desc');
                    $this->data['billitems']    = $this->billitems_m->get_order_by_billitems(['patientID' => $displayuhID,  'delete_at' => 0]);

                    $this->billpayment_m->order('create_date desc');
                    $this->data['billpayments'] = $this->billpayment_m->get_order_by_billpayment(['patientID' => $displayuhID, 'delete_at' => 0]);

                    $this->data['paymentmethods']   = [
                        '1'  => $this->lang->line('billpayment_cash'),
                        '2'  => $this->lang->line('billpayment_cheque'),
                        '3'  => $this->lang->line('billpayment_credit_card'),
                        '4'  => $this->lang->line('billpayment_other')
                    ];

                    $this->data["subview"] = 'billpayment/billdetails';
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

    public function view()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/billpayment/view.js'
            ]
        ];

        $billpaymentID  = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        $displayuhID    = htmlentities(escapeString($this->uri->segment(5)));

        if(((int)$billpaymentID && (int)$displayID) && ($displayuhID == 0 || $displayuhID > 0)) {
            $billPaymentViewArray['delete_at']      = 0;
            $billPaymentViewArray['billpaymentID']  = $billpaymentID;
            if($this->data['loginroleID'] == 3) {
                $billPaymentViewArray['patientID'] = $this->data['loginuserID'];
            }
            $this->data['billpayment']  = $this->billpayment_m->get_single_billpayment($billPaymentViewArray);

            if (inicompute($this->data['billpayment'])) {
                $this->data['jsmanager'] = ['myBillpaymentID' =>  $this->data['billpayment']->billpaymentID];
                $this->data['patient'] = $this->patient_m->get_single_patient(['patientID' => $this->data['billpayment']->patientID]);
                if (inicompute($this->data['patient'])) {
                    $this->data['billpaymentID']    = $billpaymentID;
                    $this->data['displayID']        = $displayID;
                    $this->data['displayuhID']      = $displayuhID;

                    $this->data['paymentmethods'] = [
                        '1' => $this->lang->line('billpayment_cash'),
                        '2' => $this->lang->line('billpayment_cheque'),
                        '3' => $this->lang->line('billpayment_credit_card'),
                        '4' => $this->lang->line('billpayment_other')
                    ];

                    $this->data["subview"] = 'billpayment/view';
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
        if(permissionChecker('billpayment_view')) {
            $billpaymentID  = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$billpaymentID) {
                $billPaymentViewArray['delete_at']     = 0;
                $billPaymentViewArray['billpaymentID'] = $billpaymentID;
                if($this->data['loginroleID'] == 3) {
                    $billPaymentViewArray['patientID'] = $this->data['loginuserID'];
                }
                $this->data['billpayment']  = $this->billpayment_m->get_single_billpayment($billPaymentViewArray);

                if (inicompute($this->data['billpayment'])) {
                    $this->data['patient'] = $this->patient_m->get_single_patient(['patientID' => $this->data['billpayment']->patientID]);
                    if (inicompute($this->data['patient'])) {
                        $this->data['paymentmethods'] = [
                            '1' => $this->lang->line('billpayment_cash'),
                            '2' => $this->lang->line('billpayment_cheque'),
                            '3' => $this->lang->line('billpayment_credit_card'),
                            '4' => $this->lang->line('billpayment_other')
                        ];

                        $this->report->reportPDF(['stylesheet' => 'billpaymentmodule.css', 'data' => $this->data, 'viewpath' => 'billpayment/printpreview', 'pagetype'=> 'landscape']);
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
        if(permissionChecker('billpayment_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $billpaymentID  = $this->input->post('billpaymentID');
                    if((int)$billpaymentID) {
                        $billPaymentViewArray['delete_at']     = 0;
                        $billPaymentViewArray['billpaymentID'] = $billpaymentID;
                        if($this->data['loginroleID'] == 3) {
                            $billPaymentViewArray['patientID'] = $this->data['loginuserID'];
                        }
                        $this->data['billpayment']  = $this->billpayment_m->get_single_billpayment($billPaymentViewArray);

                        if (inicompute($this->data['billpayment'])) {
                            $this->data['patient'] = $this->patient_m->get_single_patient(['patientID' => $this->data['billpayment']->patientID]);
                            if (inicompute($this->data['patient'])) {
                                $this->data['paymentmethods'] = [
                                    '1' => $this->lang->line('billpayment_cash'),
                                    '2' => $this->lang->line('billpayment_cheque'),
                                    '3' => $this->lang->line('billpayment_credit_card'),
                                    '4' => $this->lang->line('billpayment_other')
                                ];
                                $email    = $this->input->post('to');
                                $subject  = $this->input->post('subject');
                                $message  = $this->input->post('message');

                                $this->report->reportSendToMail(['stylesheet' => 'billpaymentmodule.css', 'data' => $this->data, 'viewpath' => 'billpayment/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message, 'pagetype'=> 'landscape']);
                                $retArray['message'] = "Success";
                                $retArray['status']  = TRUE;
                            } else {
                                $retArray['message'] = $this->lang->line('billpayment_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('billpayment_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('billpayment_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('billpayment_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('billpayment_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    public function detailsprintpreview()
    {
        if(permissionChecker('billpayment_view')) {
            $displayuhID      = htmlentities(escapeString($this->uri->segment(3)));
            $printpreviewID   = htmlentities(escapeString($this->uri->segment(4)));

            if((int)$displayuhID && (int)$printpreviewID) {
                if($this->data['loginroleID'] == 3) {
                    $displayuhID = $this->data['loginuserID'];
                }
                $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $displayuhID));
                if(inicompute($this->data['patient'])) {
                    $this->data['user']  = $this->user_m->get_single_user(['patientID' => $this->data['patient']->patientID]);
                    if(inicompute($this->data['user'])) {
                        $this->data['printpreviewID'] = $printpreviewID;
                        $this->data['displayuhID']    = $displayuhID;
                        $this->data['designation']    = $this->designation_m->get_single_designation(['designationID' => $this->data['user']->designationID]);
                        $this->data['billlabels']     = pluck($this->billlabel_m->get_billlabel(), 'name', 'billlabelID');


                        $this->billitems_m->order('create_date desc');
                        $this->data['bills']        = $this->bill_m->get_order_by_bill(['patientID' => $displayuhID,  'delete_at' => 0]);

                        $this->billitems_m->order('create_date desc');
                        $this->data['billitems']    = $this->billitems_m->get_order_by_billitems(['patientID' => $displayuhID,  'delete_at' => 0]);

                        $this->billpayment_m->order('create_date desc');
                        $this->data['billpayments'] = $this->billpayment_m->get_order_by_billpayment(['patientID' => $displayuhID, 'delete_at' => 0]);

                        $this->data['paymentmethods']   = [
                            '1'  => $this->lang->line('billpayment_cash'),
                            '2'  => $this->lang->line('billpayment_cheque'),
                            '3'  => $this->lang->line('billpayment_credit_card'),
                            '4'  => $this->lang->line('billpayment_other')
                        ];

                        $this->report->reportPDF(['stylesheet' => 'detailsprintpreviewmodule.css', 'data' => $this->data, 'viewpath' => 'billpayment/detailsprintpreview']);
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

    public function detailssendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('billpayment_view')) {
            if($_POST) {
                $rules = $this->detailssendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $displayuhID      = $this->input->post('displayuhID');
                    $printpreviewID   = $this->input->post('printpreviewID');

                    if((int)$displayuhID && (int)$printpreviewID) {
                        if($this->data['loginroleID'] == 3) {
                            $displayuhID = $this->data['loginuserID'];
                        }
                        $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $displayuhID));
                        if(inicompute($this->data['patient'])) {
                            $this->data['user']  = $this->user_m->get_single_user(['patientID' => $this->data['patient']->patientID]);
                            if(inicompute($this->data['user'])) {
                                $this->data['printpreviewID'] = $printpreviewID;
                                $this->data['displayuhID']    = $displayuhID;
                                $this->data['designation']    = $this->designation_m->get_single_designation(['designationID' => $this->data['user']->designationID]);
                                $this->data['billlabels']     = pluck($this->billlabel_m->get_billlabel(), 'name', 'billlabelID');


                                $this->billitems_m->order('create_date desc');
                                $this->data['bills']        = $this->bill_m->get_order_by_bill(['patientID' => $displayuhID,  'delete_at' => 0]);

                                $this->billitems_m->order('create_date desc');
                                $this->data['billitems']    = $this->billitems_m->get_order_by_billitems(['patientID' => $displayuhID,  'delete_at' => 0]);

                                $this->billpayment_m->order('create_date desc');
                                $this->data['billpayments'] = $this->billpayment_m->get_order_by_billpayment(['patientID' => $displayuhID, 'delete_at' => 0]);

                                $this->data['paymentmethods']   = [
                                    '1'  => $this->lang->line('billpayment_cash'),
                                    '2'  => $this->lang->line('billpayment_cheque'),
                                    '3'  => $this->lang->line('billpayment_credit_card'),
                                    '4'  => $this->lang->line('billpayment_other')
                                ];

                                $email    = $this->input->post('to');
                                $subject  = $this->input->post('subject');
                                $message  = $this->input->post('message');

                                $this->report->reportSendToMail(['stylesheet' => 'detailsprintpreviewmodule.css', 'data' => $this->data, 'viewpath' => 'billpayment/detailsprintpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                $retArray['message'] = "Success";
                                $retArray['status']  = TRUE;
                            } else {
                                $retArray['message'] = $this->lang->line('billpayment_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('billpayment_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('billpayment_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('billpayment_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('billpayment_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    public function detailssendmail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("billpayment_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("billpayment_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("billpayment_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'displayuhID',
                'label' => $this->lang->line("billpayment_uhid"),
                'rules' => 'trim|required|numeric'
            ),
            array(
                'field' => 'printpreviewID',
                'label' => $this->lang->line("billpayment_print_option"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("billpayment_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("billpayment_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("billpayment_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'billpaymentID',
                'label' => $this->lang->line("billpayment_billpayment"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    private function _billPaymentDisplayArrayGenerate($displayID, $displayuhID)
    {
        if($displayID == 2) {
            $displayArray['YEAR(billpayment.paymentdate)']      = date('Y');
            $displayArray['MONTH(billpayment.paymentdate)']     = date('m');
            $displayArray['billpayment.delete_at']              = 0;
        } elseif($displayID == 3) {
            $displayArray['YEAR(billpayment.paymentdate)']      = date('Y');
            $displayArray['billpayment.delete_at']              = 0;
        } elseif($displayID == 4) {
            $displayArray['billpayment.delete_at']              = 0;
        } else {
            $displayID                                          = 1;
            $displayArray['DATE(billpayment.paymentdate)']      = date('Y-m-d');
            $displayArray['billpayment.delete_at']              = 0;
        }

        if($this->data['loginroleID'] == 3) {
            $displayArray = [];
            $displayArray['billpayment.delete_at']              = 0;
            $displayArray['billpayment.patientID']  = $this->data['loginuserID'];
        }

        if($displayuhID == '') {
            $displayuhID = 0;
            $displayuhidArray['patientID'] = $displayuhID;
        } else {
            $displayuhidArray['patientID'] = $displayuhID;
        }

        $this->data['displayID']        = $displayID;
        $this->data['displayuhID']      = $displayuhID;

        $returnArray = ['displayArray' => $displayArray, 'displayuhidArray' => $displayuhidArray];
        return $returnArray;
    }

    private function _calculationForEdit($billpayment)
    {
        if(inicompute($billpayment)) {
            $billPaymentSum     = $this->billpayment_m->get_sum_billpayment('paymentamount', ['patientID' => $billpayment->patientID, 'delete_at' => 0]);
            $billItemsAllSum    = $this->billitems_m->get_sum_with_discount_billitems(['patientID' => $billpayment->patientID, 'delete_at' => 0]);
            return app_currency_format(($billItemsAllSum->billamount - $billPaymentSum->paymentamount) + $billpayment->paymentamount);
        }
    }

    private function _appointmentAndAdmission($patientID, $patienttypeID)
    {
        $id = 0;
        if($patienttypeID == 0) {
            $this->appointment_m->order('appointmentID desc');
            $appointment = $this->appointment_m->get_single_appointment(array('patientID' => $patientID));
            $id = inicompute($appointment) ? $appointment->appointmentID : 0;
        } elseif($patienttypeID == 1) {
            $this->admission_m->order('admissionID desc');
            $admission   = $this->admission_m->get_single_admission(array('patientID' => $patientID));
            $id = inicompute($admission) ? $admission->admissionID : 0;
        }
        return $id;
    }

    public function getuhid()
    {
        $this->session->set_flashdata('billpaymentother', true);
    }

    public function required_no_zero($data)
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }

    public function unique_amount($data)
    {
        if($data != '') {
            if($data < 1) {
                $this->form_validation->set_message("unique_amount", "The %s can not take zero or any negative value.");
                return FALSE;
            } else {
                $dueAmount = $this->data['dueamount'];
                if($this->input->post('amount') > $dueAmount) {
                    $this->form_validation->set_message("unique_amount", "This %s can not be more then $dueAmount");
                    return FALSE;
                }
                return true;
            }
        } else {
            $this->form_validation->set_message("unique_amount", "The %s field is required.");
            return FALSE;
        }
    }

    private function _dueCalculation($patientID)
    {
        if((int)$patientID && $patientID  > 0) {
            $this->data['patient'] = $this->patient_m->get_single_patient(['patientID' => $patientID]);
            $this->data['user'] = $this->user_m->get_single_user(array('patientID' => $patientID));
            if (inicompute($this->data['patient']) && inicompute($this->data['user'])) {
                $this->data['designation']      = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                $paidBillSum                    = $this->bill_m->get_sum_bill('totalamount', ['patientID' => $patientID, 'delete_at' => 0]);
                $paidBillPaymentSum             = $this->billpayment_m->get_sum_billpayment('paymentamount', ['patientID' => $patientID, 'delete_at' => 0]);

                return ($paidBillSum->totalamount - $paidBillPaymentSum->paymentamount);
            } else {
                $this->data['patient'] = [];
            }
        } else {
            $this->data['patient'] = [];
        }

    }

    private function _dueBillList($patientID)
    {
        $this->bill_m->order('billID asc');
        $unpaidBills                = $this->bill_m->get_order_by_bill(['patientID' => $patientID, 'delete_at' => 0, 'status !=' => 1]);
        $paidBillSum                = $this->bill_m->get_sum_bill('totalamount', ['patientID' => $patientID, 'delete_at' => 0, 'status' => 1]);
        $paidBillPaymentSumByOne    = $this->billpayment_m->get_sum_billpayment('paymentamount', ['patientID' => $patientID, 'delete_at' => 0, 'paymentby' => 1]);
        $paidBillPaymentSumByZero   = $this->billpayment_m->get_sum_billpayment('paymentamount', ['patientID' => $patientID, 'delete_at' => 0, 'paymentby' => 0]);
        $paidBillPayment            = pluck($this->billpayment_m->get_order_by_billpayment(['patientID' => $patientID, 'delete_at' => 0, 'paymentby' => 0]), 'obj', 'billID');

        $netPaymentSum                  = (($paidBillPaymentSumByOne->paymentamount + $paidBillPaymentSumByZero->paymentamount) - $paidBillSum->totalamount);
        $this->data['unpaidbills']      = $unpaidBills;

        if(inicompute($unpaidBills)) {
            foreach ($unpaidBills as $key => $unpaidBill) {
                if(isset($paidBillPayment[$unpaidBill->billID])) {
                    $netPaymentSum = ($netPaymentSum - $paidBillPayment[$unpaidBill->billID]->paymentamount);
                    $unpaidBills[$key]->totalamount = $unpaidBill->totalamount - $paidBillPayment[$unpaidBill->billID]->paymentamount;
                }
            }

            foreach ($unpaidBills as $key => $unpaidBill) {
                $unpaidBillAmount = $unpaidBill->totalamount;
                if($netPaymentSum > 0) {
                    if($unpaidBillAmount > $netPaymentSum) {
                        $netPaymentSum = ($unpaidBill->totalamount - $netPaymentSum);
                        $unpaidBills[$key]->totalamount = $netPaymentSum;
                        $netPaymentSum = 0;
                    } else {
                        $netPaymentSum = ($netPaymentSum - $unpaidBill->totalamount);
                        unset($unpaidBills[$key]);
                    }
                }
            }
        }
    }

    public function _calculation($patientID)
    {
        $this->bill_m->order('billID asc');
        $this->billitems_m->order('billID asc');
        $bills                      = $this->bill_m->get_order_by_bill(['patientID' => $patientID, 'delete_at' => 0]);
        $billItems                  = pluck_multi_array($this->billitems_m->get_select_billitems('billitemsID, billID, amount, discount', ['patientID' => $patientID, 'delete_at' => 0]), 'obj', 'billID');
        $paidBillPaymentSumByOne    = $this->billpayment_m->get_sum_billpayment('paymentamount', ['patientID' => $patientID, 'delete_at' => 0, 'paymentby' => 1]);
        $paidBillPaymentZero        = pluck($this->billpayment_m->get_order_by_billpayment(['patientID' => $patientID, 'delete_at' => 0, 'paymentby' => 0]), 'obj', 'billID');
        $originalBillItems          = pluck_multi_array($this->billitems_m->get_select_billitems('billitemsID, billID, amount, discount', ['patientID' => $patientID, 'delete_at' => 0]), 'obj', 'billID');

        if(inicompute($bills)) {
            foreach ($bills as $billKey => $bill) {
                if(isset($originalBillItems[$bill->billID])) {
                    foreach ($originalBillItems[$bill->billID] as $billItemKey => $billItem) {
                        $amount = $billItem->amount;
                        $calculateAmount = $amount;
                        if ($billItem->discount > 0) {
                            $discountAmount = (($amount > 0) ? (($amount / 100) * $billItem->discount) : 0);
                            $calculateAmount = ($amount - $discountAmount);
                        }
                        $originalBillItems[$billItem->billID][$billItemKey]->amount = $calculateAmount;
                    }
                }
            }
        }

        if(inicompute($bills)) {
            foreach ($bills as $billKey => $bill) {
                if(isset($billItems[$bill->billID])) {
                    foreach ($billItems[$bill->billID] as $billItemKey => $billItem) {
                        $amount = $billItem->amount;
                        $calculateAmount = $amount;
                        if ($billItem->discount > 0) {
                            $discountAmount = (($amount > 0) ? (($amount / 100) * $billItem->discount) : 0);
                            $calculateAmount = ($amount - $discountAmount);
                        }
                        $billItems[$billItem->billID][$billItemKey]->amount = $calculateAmount;
                    }
                }
            }
        }

        if(inicompute($bills)) {
            foreach ($bills as $billKey => $bill) {
                if(isset($billItems[$bill->billID])) {
                    $netPaymentSum = (isset($paidBillPaymentZero[$bill->billID]) ? $paidBillPaymentZero[$bill->billID]->paymentamount : 0);
                    foreach ($billItems[$bill->billID] as $billItemKey => $billItem) {
                        $calculateAmount = $billItem->amount;
                        if(isset($paidBillPaymentZero[$bill->billID]->paymentamount) && $paidBillPaymentZero[$bill->billID]->paymentamount > 0) {
                            if($netPaymentSum > 0) {
                                if($calculateAmount > $netPaymentSum) {
                                    $netPaymentSum = ($calculateAmount - $netPaymentSum);
                                    $billItems[$billItem->billID][$billItemKey]->amount = $netPaymentSum;
                                    $netPaymentSum = 0;
                                } else {
                                    $netPaymentSum = ($netPaymentSum - $calculateAmount);
                                    $billItems[$billItem->billID][$billItemKey]->amount = 0;
                                }
                            }
                        }
                    }
                }
            }
        }

        if(inicompute($bills)) {
            $netPaymentSum = $paidBillPaymentSumByOne->paymentamount;
            foreach ($bills as $billKey => $bill) {
                if(isset($billItems[$bill->billID])) {
                    foreach ($billItems[$bill->billID] as $billItemKey => $billItem) {
                        $calculateAmount = $billItem->amount;
                        if($netPaymentSum > 0) {
                            if($calculateAmount > $netPaymentSum) {
                                $netPaymentSum = ($calculateAmount - $netPaymentSum);
                                $billItems[$billItem->billID][$billItemKey]->amount = $netPaymentSum;
                                $netPaymentSum = 0;
                            } else {
                                $netPaymentSum = ($netPaymentSum - $calculateAmount);
                                $billItems[$billItem->billID][$billItemKey]->amount = 0;
                            }
                        }
                    }
                }
            }
        }

        $i                      = 0;
        $billItemArray          = [];
        $billItemQueryArray     = [];
        if(inicompute($originalBillItems)) {
            foreach ($originalBillItems as $originalBillItem) {
                if(inicompute($originalBillItem)) {
                    $j = 0;
                    foreach ($originalBillItem as $originalBillItemVal) {
                        if(isset($billItems[$originalBillItemVal->billID][$j])) {
                            if($billItems[$originalBillItemVal->billID][$j]->amount == 0) {
                                $billItemQueryArray[$i]['status']        = 1;
                                $billItemQueryArray[$i]['billitemsID']   = $originalBillItemVal->billitemsID;
                                $billItemArray[$originalBillItemVal->billID][$originalBillItemVal->billitemsID] = 1;
                                $i++;
                            } elseif($originalBillItemVal->amount == $billItems[$originalBillItemVal->billID][$j]->amount) {
                                $billItemQueryArray[$i]['status']        = 0;
                                $billItemQueryArray[$i]['billitemsID']   = $originalBillItemVal->billitemsID;
                                $billItemArray[$originalBillItemVal->billID][$originalBillItemVal->billitemsID] = 0;
                                $i++;
                            } elseif($originalBillItemVal->amount > $billItems[$originalBillItemVal->billID][$j]->amount) {
                                $billItemQueryArray[$i]['status']        = 0;
                                $billItemQueryArray[$i]['billitemsID']   = $originalBillItemVal->billitemsID;
                                $billItemArray[$originalBillItemVal->billID][$originalBillItemVal->billitemsID] = 2;
                                $i++;
                            }
                        }
                        $j++;
                    }
                }
            }
        }

        $billQueryArray = [];
        if(inicompute($billItemArray)) {
            $k = 0;
            foreach ($billItemArray as $billID =>  $billItemAr) {
                if(in_array(2, $billItemAr)) {
                    $billQueryArray[$k]['status'] = 2;
                    $billQueryArray[$k]['billID'] = $billID;
                } elseif(in_array(0, $billItemAr)) {
                    $billQueryArray[$k]['status'] = 0;
                    $billQueryArray[$k]['billID'] = $billID;
                } else {
                    $billQueryArray[$k]['status'] = 1;
                    $billQueryArray[$k]['billID'] = $billID;
                }
                $k++;
            }
        }

        if(inicompute($billItemQueryArray)) {
            $this->billitems_m->update_batch_billitems($billItemQueryArray, 'billitemsID');
        }

        if(inicompute($billQueryArray)) {
            $this->bill_m->update_batch_bill($billQueryArray, 'billID');
        }
    }


}