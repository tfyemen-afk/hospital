<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bill extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('bill_m');
        $this->load->model('patient_m');
        $this->load->model('billcategory_m');
        $this->load->model('billlabel_m');
        $this->load->model('appointment_m');
        $this->load->model('admission_m');
        $this->load->model('billitems_m');
        $this->load->model('billpayment_m');
        $this->load->model('user_m');
        $this->load->library('report');

        $language = $this->session->userdata('lang');
        $this->lang->load('bill', $language);
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
                'assets/inilabs/bill/index.js',
            ]
        ];

        $patientQueryArray['delete_at'] = 0;
        if ( $this->data['loginroleID'] == 3 ) {
            $patientQueryArray['patientID'] = $this->data['loginuserID'];
        }

        $this->data['activetab']     = true;
        $displayID                   = htmlentities(escapeString($this->uri->segment(3)));
        $displayArray                = $this->_displayManager($displayID);
        $this->data['billstatus']    = [
            '0' => $this->lang->line('bill_due'),
            '1' => $this->lang->line('bill_fully_paid'),
            '2' => $this->lang->line('bill_partially_paid')
        ];
        $this->data['bills']         = $this->bill_m->get_select_bill_patient('bill.billID, bill.patientID, bill.patienttypeID, bill.date, bill.totalamount, bill.status, patient.name',
            $displayArray);
        $this->data['patients']      = $this->patient_m->get_select_patient('patientID, name', $patientQueryArray);
        $this->data['billcategorys'] = $this->billcategory_m->get_select_billcategory('billcategoryID, name');
        $this->data['billlabelsobj'] = json_encode(pluck($this->billlabel_m->get_billlabel(), 'obj', 'billlabelID'));
        $this->data['jsmanager']     = [
            'displayID'               => $this->data['displayID'],
            'viewPermission'          => permissionChecker('bill_view'),
            'bill_please_select_lang' => $this->lang->line('bill_please_select'),
            'billlabelsobj'           => $this->data['billlabelsobj']
        ];
        $this->data['patienttypes']  = [
            5 => $this->lang->line('bill_register'),
            0 => $this->lang->line('bill_opd'),
            1 => $this->lang->line('bill_ipd')
        ];

        $this->data["subview"] = 'bill/index';
        $this->load->view('_layout_main', $this->data);
    }

    private function _displayManager( $displayID )
    {
        $displayArray['bill.delete_at'] = 0;
        if ( $displayID == 2 ) {
            $displayArray['YEAR(bill.create_date)']  = date('Y');
            $displayArray['MONTH(bill.create_date)'] = date('m');
        } elseif ( $displayID == 3 ) {
            $displayArray['YEAR(bill.create_date)'] = date('Y');
        } elseif ( $displayID == 4 ) {
            $displayArray['bill.delete_at'] = 0;
        } else {
            $displayID                              = 1;
            $displayArray['DATE(bill.create_date)'] = date('Y-m-d');
        }
        $this->data['displayID'] = $displayID;

        if ( $this->data['loginroleID'] == 3 ) {
            $displayArray                   = [];
            $displayArray['bill.delete_at'] = 0;
            $displayArray['bill.patientID'] = $this->data['loginuserID'];
        }

        return $displayArray;
    }

    public function edit()
    {
        $billID    = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if ( (int) $billID && (int) $displayID ) {
            $displayEditArray['bill.delete_at'] = 0;
            $displayEditArray['bill.billID']    = $billID;
            if ( $this->data['loginroleID'] == 3 ) {
                $displayEditArray['bill.patientID'] = $this->data['loginuserID'];
            }
            $this->data['bill'] = $this->bill_m->get_single_bill($displayEditArray);
            if ( inicompute($this->data['bill']) ) {
                $this->data['headerassets'] = [
                    'css' => [
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ],
                    'js'  => [
                        'assets/select2/select2.js',
                        'assets/inilabs/bill/edit.js',
                    ]
                ];

                $patientQueryArray['delete_at'] = 0;
                if ( $this->data['loginroleID'] == 3 ) {
                    $patientQueryArray['patientID'] = $this->data['loginuserID'];
                }

                $this->data['activetab']     = false;
                $this->data['displayID']     = $displayID;
                $displayArray                = $this->_displayManager($displayID);
                $this->data['billstatus']    = [
                    '0' => $this->lang->line('bill_due'),
                    '1' => $this->lang->line('bill_fully_paid'),
                    '2' => $this->lang->line('bill_partially_paid')
                ];
                $this->data['bills']         = $this->bill_m->get_select_bill_patient('bill.billID, bill.patientID, bill.patienttypeID, bill.date, bill.totalamount, bill.status, patient.name',
                    $displayArray);
                $this->data['billitems']     = $this->billitems_m->get_order_by_billitems([ 'billID' => $billID ]);
                $this->data['patients']      = $this->patient_m->get_select_patient('patientID, name',
                    $patientQueryArray);
                $this->data['billcategorys'] = $this->billcategory_m->get_select_billcategory('billcategoryID, name');
                $this->data['billlabels']    = pluck($this->billlabel_m->get_billlabel(), 'obj', 'billlabelID');
                $this->data['billlabelsobj'] = json_encode($this->data['billlabels']);
                $this->data['billID']        = $billID;
                $this->data['patienttypes']  = [
                    5 => $this->lang->line('bill_register'),
                    0 => $this->lang->line('bill_opd'),
                    1 => $this->lang->line('bill_ipd')
                ];
                $this->data['jsmanager']     = [
                    'billID'                  => $billID,
                    'displayID'               => $this->data['displayID'],
                    'viewPermission'          => permissionChecker('bill_view'),
                    'bill_please_select_lang' => $this->lang->line('bill_please_select'),
                    'billlabelsobj'           => $this->data['billlabelsobj']
                ];

                $this->data["subview"] = 'bill/edit';
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

    public function view()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/bill/view.js',
            ]
        ];

        $billID    = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if ( (int) $billID && (int) $displayID ) {
            $this->data['displayID']            = $displayID;
            $displayViewArray['bill.delete_at'] = 0;
            $displayViewArray['bill.billID']    = $billID;
            if ( $this->data['loginroleID'] == 3 ) {
                $displayViewArray['bill.patientID'] = $this->data['loginuserID'];
            }

            $this->data['billstatus'] = [
                '0' => $this->lang->line('bill_due'),
                '1' => $this->lang->line('bill_fully_paid'),
                '2' => $this->lang->line('bill_partially_paid')
            ];
            $this->data['bill']       = $this->bill_m->get_single_bill($displayViewArray);
            if ( inicompute($this->data['bill']) ) {
                $this->data['jsmanager'] = [ 'myBillID' => $this->data['bill']->billID ];
                $this->billitems_m->order('billitemsID asc');
                $this->data['patienttypes'] = [
                    '0' => $this->lang->line('bill_opd'),
                    '1' => $this->lang->line('bill_ipd'),
                    '5' => $this->lang->line('bill_register')
                ];
                $this->data['billlabels']   = pluck($this->billlabel_m->get_billlabel(), 'obj', 'billlabelID');
                $this->data['billitems']    = $this->billitems_m->get_order_by_billitems([ 'billID' => $billID ]);
                $this->data['patient']      = $this->patient_m->get_select_patient('patientID, name',
                    [ 'patientID' => $this->data['bill']->patientID ], true);
                $this->data['billpayment']  = $this->billpayment_m->get_single_billpayment([
                    'billID'    => $billID,
                    'paymentby' => 0,
                    'delete_at' => 0
                ]);
                $this->data['userName']     = $this->user_m->get_single_user([ 'userID' => $this->data['bill']->create_userID ]);
                $this->data["subview"]      = 'bill/view';
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
        if ( permissionChecker('bill_view') ) {
            $billID = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $billID ) {
                $displayViewArray['bill.delete_at'] = 0;
                $displayViewArray['bill.billID']    = $billID;
                if ( $this->data['loginroleID'] == 3 ) {
                    $displayViewArray['bill.patientID'] = $this->data['loginuserID'];
                }

                $this->data['billstatus'] = [
                    '0' => $this->lang->line('bill_due'),
                    '1' => $this->lang->line('bill_fully_paid'),
                    '2' => $this->lang->line('bill_partially_paid')
                ];
                $this->data['bill']       = $this->bill_m->get_single_bill($displayViewArray);
                if ( inicompute($this->data['bill']) ) {
                    $this->billitems_m->order('billitemsID asc');
                    $this->data['patienttypes'] = [
                        '0' => $this->lang->line('bill_opd'),
                        '1' => $this->lang->line('bill_ipd'),
                        '5' => $this->lang->line('bill_register')
                    ];
                    $this->data['billlabels']   = pluck($this->billlabel_m->get_billlabel(), 'obj', 'billlabelID');
                    $this->data['billitems']    = $this->billitems_m->get_order_by_billitems([ 'billID' => $billID ]);
                    $this->data['patient']      = $this->patient_m->get_select_patient('patientID, name',
                        [ 'patientID' => $this->data['bill']->patientID ], true);
                    $this->data['billpayment']  = $this->billpayment_m->get_single_billpayment([
                        'billID'    => $billID,
                        'paymentby' => 0,
                        'delete_at' => 0
                    ]);
                    $this->data['userName']     = $this->user_m->get_single_user([ 'userID' => $this->data['bill']->create_userID ]);

                    $this->report->reportPDF([
                        'stylesheet' => 'billmodule.css',
                        'data'       => $this->data,
                        'viewpath'   => 'bill/printpreview'
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
        if ( permissionChecker('bill_view') ) {
            if ( $_POST ) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $billID = $this->input->post('billID');
                    if ( (int) $billID ) {
                        $displayViewArray['bill.delete_at'] = 0;
                        $displayViewArray['bill.billID']    = $billID;
                        if ( $this->data['loginroleID'] == 3 ) {
                            $displayViewArray['bill.patientID'] = $this->data['loginuserID'];
                        }

                        $this->data['billstatus'] = [
                            '0' => $this->lang->line('bill_due'),
                            '1' => $this->lang->line('bill_fully_paid'),
                            '2' => $this->lang->line('bill_partially_paid')
                        ];
                        $this->data['bill']       = $this->bill_m->get_single_bill($displayViewArray);
                        if ( inicompute($this->data['bill']) ) {
                            $this->billitems_m->order('billitemsID asc');
                            $this->data['patienttypes'] = [
                                '0' => $this->lang->line('bill_opd'),
                                '1' => $this->lang->line('bill_ipd'),
                                '5' => $this->lang->line('bill_register')
                            ];
                            $this->data['billlabels']   = pluck($this->billlabel_m->get_billlabel(), 'obj',
                                'billlabelID');
                            $this->data['billitems']    = $this->billitems_m->get_order_by_billitems([ 'billID' => $billID ]);
                            $this->data['patient']      = $this->patient_m->get_select_patient('patientID, name',
                                [ 'patientID' => $this->data['bill']->patientID ], true);
                            $this->data['billpayment']  = $this->billpayment_m->get_single_billpayment([
                                'billID'    => $billID,
                                'paymentby' => 0,
                                'delete_at' => 0
                            ]);
                            $this->data['userName']     = $this->user_m->get_single_user([ 'userID' => $this->data['bill']->create_userID ]);

                            $email   = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');

                            $this->report->reportSendToMail([
                                'stylesheet' => 'billmodule.css',
                                'data'       => $this->data,
                                'viewpath'   => 'bill/printpreview',
                                'email'      => $email,
                                'subject'    => $subject,
                                'message'    => $message
                            ]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = true;
                        } else {
                            $retArray['message'] = $this->lang->line('bill_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('bill_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('bill_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('bill_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules()
    {
        $rules = [
            [
                'field' => 'to',
                'label' => $this->lang->line("bill_to"),
                'rules' => 'trim|required|valid_email'
            ],
            [
                'field' => 'subject',
                'label' => $this->lang->line("bill_subject"),
                'rules' => 'trim|required'
            ],
            [
                'field' => 'message',
                'label' => $this->lang->line("bill_message"),
                'rules' => 'trim'
            ],
            [
                'field' => 'billID',
                'label' => $this->lang->line("bill_bill"),
                'rules' => 'trim|required|numeric'
            ]
        ];
        return $rules;
    }

    public function delete()
    {
        $billID    = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if ( (int) $billID && (int) $displayID ) {
            $displayViewArray['bill.delete_at'] = 0;
            $displayViewArray['bill.billID']    = $billID;
            if ( $this->data['loginroleID'] == 3 ) {
                $displayViewArray['bill.patientID'] = $this->data['loginuserID'];
            }

            $bill = $this->bill_m->get_single_bill($displayViewArray);
            if ( inicompute($bill) ) {
                $billitems = $this->billitems_m->get_select_billitems('billitemsID', [ 'billID' => $billID ]);
                if ( inicompute($billitems) ) {
                    $deleteArray = [];
                    $i           = 0;
                    foreach ( $billitems as $billitem ) {
                        $deleteArray[ $i ]['delete_at']   = 1;
                        $deleteArray[ $i ]['billitemsID'] = $billitem->billitemsID;
                        $i++;
                    }

                    if ( inicompute($deleteArray) ) {
                        $this->billitems_m->update_batch_billitems($deleteArray, 'billitemsID');
                    }
                }
                $this->bill_m->update_bill([ 'delete_at' => 1 ], $billID);

                $this->_calculation($bill->patientID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('bill/index/' . $displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function _calculation( $patientID )
    {
        $this->bill_m->order('billID asc');
        $this->billitems_m->order('billID asc');
        $bills                   = $this->bill_m->get_order_by_bill([ 'patientID' => $patientID, 'delete_at' => 0 ]);
        $billItems               = pluck_multi_array($this->billitems_m->get_select_billitems('billitemsID, billID, amount, discount',
            [ 'patientID' => $patientID, 'delete_at' => 0 ]), 'obj', 'billID');
        $paidBillPaymentSumByOne = $this->billpayment_m->get_sum_billpayment('paymentamount',
            [ 'patientID' => $patientID, 'delete_at' => 0, 'paymentby' => 1 ]);
        $paidBillPaymentZero     = pluck($this->billpayment_m->get_order_by_billpayment([
            'patientID' => $patientID,
            'delete_at' => 0,
            'paymentby' => 0
        ]), 'obj', 'billID');
        $originalBillItems       = pluck_multi_array($this->billitems_m->get_select_billitems('billitemsID, billID, amount, discount',
            [ 'patientID' => $patientID, 'delete_at' => 0 ]), 'obj', 'billID');

        if ( inicompute($bills) ) {
            foreach ( $bills as $billKey => $bill ) {
                if ( isset($originalBillItems[ $bill->billID ]) ) {
                    foreach ( $originalBillItems[ $bill->billID ] as $billItemKey => $billItem ) {
                        $amount          = $billItem->amount;
                        $calculateAmount = $amount;
                        if ( $billItem->discount > 0 ) {
                            $discountAmount  = ( ( $amount > 0 ) ? ( ( $amount / 100 ) * $billItem->discount ) : 0 );
                            $calculateAmount = ( $amount - $discountAmount );
                        }
                        $originalBillItems[ $billItem->billID ][ $billItemKey ]->amount = $calculateAmount;
                    }
                }
            }
        }

        if ( inicompute($bills) ) {
            foreach ( $bills as $billKey => $bill ) {
                if ( isset($billItems[ $bill->billID ]) ) {
                    foreach ( $billItems[ $bill->billID ] as $billItemKey => $billItem ) {
                        $amount          = $billItem->amount;
                        $calculateAmount = $amount;
                        if ( $billItem->discount > 0 ) {
                            $discountAmount  = ( ( $amount > 0 ) ? ( ( $amount / 100 ) * $billItem->discount ) : 0 );
                            $calculateAmount = ( $amount - $discountAmount );
                        }
                        $billItems[ $billItem->billID ][ $billItemKey ]->amount = $calculateAmount;
                    }
                }
            }
        }

        if ( inicompute($bills) ) {
            foreach ( $bills as $billKey => $bill ) {
                if ( isset($billItems[ $bill->billID ]) ) {
                    $netPaymentSum = ( isset($paidBillPaymentZero[ $bill->billID ]) ? $paidBillPaymentZero[ $bill->billID ]->paymentamount : 0 );
                    foreach ( $billItems[ $bill->billID ] as $billItemKey => $billItem ) {
                        $calculateAmount = $billItem->amount;
                        if ( isset($paidBillPaymentZero[ $bill->billID ]->paymentamount) && $paidBillPaymentZero[ $bill->billID ]->paymentamount > 0 ) {
                            if ( $netPaymentSum > 0 ) {
                                if ( $calculateAmount > $netPaymentSum ) {
                                    $netPaymentSum                                          = ( $calculateAmount - $netPaymentSum );
                                    $billItems[ $billItem->billID ][ $billItemKey ]->amount = $netPaymentSum;
                                    $netPaymentSum                                          = 0;
                                } else {
                                    $netPaymentSum                                          = ( $netPaymentSum - $calculateAmount );
                                    $billItems[ $billItem->billID ][ $billItemKey ]->amount = 0;
                                }
                            }
                        }
                    }
                }
            }
        }

        if ( inicompute($bills) ) {
            $netPaymentSum = $paidBillPaymentSumByOne->paymentamount;
            foreach ( $bills as $billKey => $bill ) {
                if ( isset($billItems[ $bill->billID ]) ) {
                    foreach ( $billItems[ $bill->billID ] as $billItemKey => $billItem ) {
                        $calculateAmount = $billItem->amount;
                        if ( $netPaymentSum > 0 ) {
                            if ( $calculateAmount > $netPaymentSum ) {
                                $netPaymentSum                                          = ( $calculateAmount - $netPaymentSum );
                                $billItems[ $billItem->billID ][ $billItemKey ]->amount = $netPaymentSum;
                                $netPaymentSum                                          = 0;
                            } else {
                                $netPaymentSum                                          = ( $netPaymentSum - $calculateAmount );
                                $billItems[ $billItem->billID ][ $billItemKey ]->amount = 0;
                            }
                        }
                    }
                }
            }
        }

        $i                  = 0;
        $billItemArray      = [];
        $billItemQueryArray = [];
        if ( inicompute($originalBillItems) ) {
            foreach ( $originalBillItems as $originalBillItem ) {
                if ( inicompute($originalBillItem) ) {
                    $j = 0;
                    foreach ( $originalBillItem as $originalBillItemVal ) {
                        if ( isset($billItems[ $originalBillItemVal->billID ][ $j ]) ) {
                            if ( $billItems[ $originalBillItemVal->billID ][ $j ]->amount == 0 ) {
                                $billItemQueryArray[ $i ]['status']                                                 = 1;
                                $billItemQueryArray[ $i ]['billitemsID']                                            = $originalBillItemVal->billitemsID;
                                $billItemArray[ $originalBillItemVal->billID ][ $originalBillItemVal->billitemsID ] = 1;
                                $i++;
                            } elseif ( $originalBillItemVal->amount == $billItems[ $originalBillItemVal->billID ][ $j ]->amount ) {
                                $billItemQueryArray[ $i ]['status']                                                 = 0;
                                $billItemQueryArray[ $i ]['billitemsID']                                            = $originalBillItemVal->billitemsID;
                                $billItemArray[ $originalBillItemVal->billID ][ $originalBillItemVal->billitemsID ] = 0;
                                $i++;
                            } elseif ( $originalBillItemVal->amount > $billItems[ $originalBillItemVal->billID ][ $j ]->amount ) {
                                $billItemQueryArray[ $i ]['status']                                                 = 0;
                                $billItemQueryArray[ $i ]['billitemsID']                                            = $originalBillItemVal->billitemsID;
                                $billItemArray[ $originalBillItemVal->billID ][ $originalBillItemVal->billitemsID ] = 2;
                                $i++;
                            }
                        }
                        $j++;
                    }
                }
            }
        }

        $billQueryArray = [];
        if ( inicompute($billItemArray) ) {
            $k = 0;
            foreach ( $billItemArray as $billID => $billItemAr ) {
                if ( in_array(2, $billItemAr) ) {
                    $billQueryArray[ $k ]['status'] = 2;
                    $billQueryArray[ $k ]['billID'] = $billID;
                } elseif ( in_array(0, $billItemAr) ) {
                    $billQueryArray[ $k ]['status'] = 0;
                    $billQueryArray[ $k ]['billID'] = $billID;
                } else {
                    $billQueryArray[ $k ]['status'] = 1;
                    $billQueryArray[ $k ]['billID'] = $billID;
                }
                $k++;
            }
        }

        if ( inicompute($billItemQueryArray) ) {
            $this->billitems_m->update_batch_billitems($billItemQueryArray, 'billitemsID');
        }

        if ( inicompute($billQueryArray) ) {
            $this->bill_m->update_batch_bill($billQueryArray, 'billID');
        }
    }

    public function savebill()
    {
        $retArray           = [];
        $retArray['status'] = false;
        if ( permissionChecker('bill_add') ) {
            if ( $_POST ) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray['message'] = validation_errors();
                    $retArray['status']  = false;
                } else {
                    $patientID = $this->input->post('uhid');
                    $patient   = $this->patient_m->get_single_patient([
                        'patientID' => $this->input->post('uhid'),
                        'delete_at' => 0
                    ]);
                    if ( inicompute($patient) ) {
                        $billitems  = json_decode($this->input->post('billitems'));
                        $billlabels = pluck($this->billlabel_m->get_select_billlabel('billlabelID, amount'), 'obj',
                            'billlabelID');

                        $billArray['patientID']                 = $patientID;
                        $billArray['patienttypeID']             = inicompute($patient) ? $patient->patienttypeID : '';
                        $billArray['appointmentandadmissionID'] = $this->_getAppointmentAndAdmission($patientID,
                            $patient->patienttypeID);
                        $billArray['date']                      = date('Y-m-d H:i:s');
                        $billArray['permission']                = 1;
                        $billArray['note']                      = $this->input->post('note');
                        $billArray['paymentstatus']             = 0;
                        $billArray['status']                    = 0;
                        $billArray["modify_date"]               = date("Y-m-d H:i:s");
                        $billArray["create_date"]               = date("Y-m-d H:i:s");
                        $billArray["create_userID"]             = $this->session->userdata('loginuserID');
                        $billArray["create_roleID"]             = $this->session->userdata('roleID');
                        $billArray["delete_at"]                 = 0;
                        $billArray['totalamount']               = $this->_totalAmountAdd($billitems, $billlabels);

                        $this->bill_m->insert_bill($billArray);
                        $billID = $this->db->insert_id();

                        $billitemArray = [];
                        if ( inicompute($billitems) ) {
                            $i = 0;
                            foreach ( $billitems as $billitem ) {
                                $amount                                           = ( isset($billlabels[ $billitem->billlabel ]) ? $billlabels[ $billitem->billlabel ]->amount : 0 );
                                $billitemArray[ $i ]['billID']                    = $billID;
                                $billitemArray[ $i ]['billlabelID']               = $billitem->billlabel;
                                $billitemArray[ $i ]['patientID']                 = $patientID;
                                $billitemArray[ $i ]['patienttypeID']             = inicompute($patient) ? $patient->patienttypeID : '';
                                $billitemArray[ $i ]['appointmentandadmissionID'] = $this->_getAppointmentAndAdmission($patientID,
                                    $patient->patienttypeID);
                                $billitemArray[ $i ]['amount']                    = app_currency_format($amount);
                                $billitemArray[ $i ]['discount']                  = $billitem->discount;
                                $billitemArray[ $i ]['status']                    = 0;
                                $billitemArray[ $i ]['create_date']               = date("Y-m-d H:i:s");
                                $billitemArray[ $i ]['modify_date']               = date("Y-m-d H:i:s");
                                $billitemArray[ $i ]['create_userID']             = $this->session->userdata('loginuserID');
                                $billitemArray[ $i ]['create_roleID']             = $this->session->userdata('roleID');
                                $billitemArray[ $i ]['delete_at']                 = 0;
                                $i++;
                            }
                        }

                        if ( inicompute($billitemArray) ) {
                            $this->billitems_m->insert_batch_billitems($billitemArray);
                        }

                        $this->_calculation($patientID);
                        $retArray['status']  = true;
                        $retArray['id']      = $billID;
                        $retArray['message'] = 'Success';
                        $this->session->set_flashdata('success', 'Success');
                    } else {
                        $retArray['message'] = 'Data not found';
                    }
                }
            } else {
                $retArray['message'] = 'Method not allowed.';
            }
        } else {
            $retArray['message'] = 'Permission not allowed.';
        }
        echo json_encode($retArray);
    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'uhid',
                'label' => $this->lang->line("bill_uhid"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero'
            ],
            [
                'field' => 'note',
                'label' => $this->lang->line("bill_note"),
                'rules' => 'trim|max_length[128]'
            ]
        ];

        return $rules;
    }

    private function _getAppointmentAndAdmission( $patientID, $patienttypeID )
    {
        $id = 0;
        if ( $patienttypeID == 0 ) {
            $this->appointment_m->order('appointmentID desc');
            $appointment = $this->appointment_m->get_single_appointment([ 'patientID' => $patientID ]);
            $id          = inicompute($appointment) ? $appointment->appointmentID : 0;
        } elseif ( $patienttypeID == 1 ) {
            $this->admission_m->order('admissionID desc');
            $admission = $this->admission_m->get_single_admission([ 'patientID' => $patientID ]);
            $id        = inicompute($admission) ? $admission->admissionID : 0;
        }
        return $id;
    }

    private function _totalAmountAdd( $billitems, $billlabels )
    {
        $totalAmount = 0;
        if ( inicompute($billitems) ) {
            foreach ( $billitems as $billitem ) {
                if ( isset($billlabels[ $billitem->billlabel ]) ) {
                    $billLabelItem  = $billlabels[ $billitem->billlabel ];
                    $discountamount = ( ( $billLabelItem->amount / 100 ) * $billitem->discount );
                    $totalAmount    += ( $billLabelItem->amount - $discountamount );
                }
            }
        }
        return $totalAmount;
    }

    public function savebillforedit()
    {
        $retArray           = [];
        $retArray['status'] = false;
        if ( permissionChecker('bill_edit') ) {
            if ( $_POST ) {
                $billID = $this->input->post('billID');
                if ( (int) $billID ) {
                    $bill = $this->bill_m->get_single_bill([ 'billID' => $billID, 'delete_at' => 0 ]);
                    if ( inicompute($bill) ) {
                        $ruleArray = [ 'paymentstatus' => $this->input->post('paymentstatus') ];
                        $rules     = $this->rules($ruleArray);
                        $this->form_validation->set_rules($rules);
                        if ( $this->form_validation->run() == false ) {
                            $retArray['message'] = validation_errors();
                            $retArray['status']  = false;
                        } else {
                            $patientID = $this->input->post('uhid');
                            $patient   = $this->patient_m->get_single_patient([
                                'patientID' => $this->input->post('uhid'),
                                'delete_at' => 0
                            ]);
                            if ( inicompute($patient) ) {
                                $billitems = json_decode($this->input->post('billitems'));

                                $billArray['patientID']                 = $patientID;
                                $billArray['patienttypeID']             = inicompute($patient) ? $patient->patienttypeID : '';
                                $billArray['appointmentandadmissionID'] = $this->_getAppointmentAndAdmission($patientID,
                                    $patient->patienttypeID);
                                $billArray['note']                      = $this->input->post('note');
                                $billArray["modify_date"]               = date("Y-m-d H:i:s");
                                $billArray['status']                    = 0;
                                $billArray['totalamount']               = $this->_totalAmountEdit($billitems);

                                $this->bill_m->update_bill($billArray, $billID);

                                $billitemArray = [];
                                if ( inicompute($billitems) ) {
                                    $i = 0;
                                    foreach ( $billitems as $billitem ) {
                                        $billitemArray[ $i ]['billID']                    = $billID;
                                        $billitemArray[ $i ]['billlabelID']               = $billitem->billlabel;
                                        $billitemArray[ $i ]['patientID']                 = $patientID;
                                        $billitemArray[ $i ]['patienttypeID']             = inicompute($patient) ? $patient->patienttypeID : '';
                                        $billitemArray[ $i ]['appointmentandadmissionID'] = $this->_getAppointmentAndAdmission($patientID,
                                            $patient->patienttypeID);
                                        $billitemArray[ $i ]['amount']                    = app_currency_format($billitem->amount);
                                        $billitemArray[ $i ]['discount']                  = $billitem->discount;
                                        $billitemArray[ $i ]['status']                    = 0;
                                        $billitemArray[ $i ]['create_date']               = date("Y-m-d H:i:s");
                                        $billitemArray[ $i ]['modify_date']               = date("Y-m-d H:i:s");
                                        $billitemArray[ $i ]['create_userID']             = $this->session->userdata('loginuserID');
                                        $billitemArray[ $i ]['create_roleID']             = $this->session->userdata('roleID');
                                        $billitemArray[ $i ]['delete_at']                 = 0;
                                        $i++;
                                    }
                                }

                                if ( inicompute($billitemArray) ) {
                                    $this->billitems_m->delete_batch_billitems([ 'billID' => $billID ]);
                                    $this->billitems_m->insert_batch_billitems($billitemArray);
                                }

                                $this->_calculation($patientID);
                                $retArray['status']  = true;
                                $retArray['id']      = $billID;
                                $retArray['message'] = 'Success';
                                $this->session->set_flashdata('success', 'Success');
                            } else {
                                $retArray['message'] = 'Data not found';
                            }
                        }
                    } else {
                        $retArray['message'] = 'Data not found.';
                    }
                } else {
                    $retArray['message'] = 'Invalid id type.';
                }
            } else {
                $retArray['message'] = 'Method not allowed.';
            }
        } else {
            $retArray['message'] = 'Permission not allowed.';
        }
        echo json_encode($retArray);
    }

    private function _totalAmountEdit( $billitems )
    {
        $totalAmount = 0;
        if ( inicompute($billitems) ) {
            foreach ( $billitems as $billitem ) {
                if ( $billitem->discount > 0 ) {
                    $discountamount = ( ( $billitem->amount / 100 ) * $billitem->discount );
                    $totalAmount    += ( $billitem->amount - $discountamount );
                } else {
                    $totalAmount = $billitem->amount;
                }
            }
        }
        return $totalAmount;
    }

    public function get_billlabel()
    {
        echo "<option value='0'>— " . $this->lang->line('bill_please_select') . " —</option>";
        if ( $_POST ) {
            $billcategoryID = $this->input->post('billcategoryID');
            if ( (int) $billcategoryID ) {
                $billlabels = $this->billlabel_m->get_order_by_billlabel([ 'billcategoryID' => $billcategoryID ]);
                if ( inicompute($billlabels) ) {
                    foreach ( $billlabels as $billlabel ) {
                        echo "<option value='" . $billlabel->billlabelID . "'>" . $billlabel->name . "</option>";
                    }
                }
            }
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
}
