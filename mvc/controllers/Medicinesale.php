<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicinesale extends Admin_Controller
{

    protected $myData = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('medicine_m');
        $this->load->model('medicinesale_m');
        $this->load->model('medicinesalepaid_m');
        $this->load->model('medicinepurchase_m');
        $this->load->model('medicinecategory_m');
        $this->load->model('medicinewarehouse_m');
        $this->load->model('medicinepurchasepaid_m');
        $this->load->model('medicinepurchaseitem_m');
        $this->load->model('medicinesaleitem_m');
        $this->load->model('patient_m');
        $this->load->model('user_m');
        $this->load->library('report');
        $language = $this->session->userdata('lang');
        $this->lang->load('medicinesale', $language);
    }

    public function index()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
            ],
            'js'  => [
                'assets/select2/select2.js',
                'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                'assets/inilabs/medicinesale/index.js',
                'assets/inilabs/medicinesale/table.js',
            ]
        ];

        $this->data['activetab']         = true;
        $displayID                       = htmlentities(escapeString($this->uri->segment(3)));
        $displayArray                    = $this->_displayManager($displayID);
        $this->data['medicinesales']     = $this->medicinesale_m->get_order_by_medicinesale($displayArray);
        $this->data['medicinecategorys'] = $this->medicinecategory_m->get_select_medicinecategory();
        $this->data['medicineobj']       = json_encode(pluck($this->medicine_m->get_medicine(), 'obj', 'medicineID'));
        $this->data['patient_types']     = [
            0 => $this->lang->line('medicinesale_opd'),
            1 => $this->lang->line('medicinesale_ipd'),
            2 => $this->lang->line('medicinesale_none'),
        ];
        $this->get_medicine_sale_payment();
        $this->myData['myMedicineobj']              = $this->data['medicineobj'];
        $this->myData['medicinesale_please_select'] = $this->lang->line('medicinesale_please_select');

        $this->data['jsmanager'] = $this->myData;
        $this->data["subview"]   = 'medicinesale/index';
        $this->load->view('_layout_main', $this->data);

    }

    private function _displayManager( $displayID )
    {
        if ( $displayID == 2 ) {
            $displayArray['YEAR(create_date)']  = date('Y');
            $displayArray['MONTH(create_date)'] = date('m');
        } elseif ( $displayID == 3 ) {
            $displayArray['YEAR(create_date)'] = date('Y');
        } elseif ( $displayID == 4 ) {
            $displayArray = [];
        } else {
            $displayID                         = 1;
            $displayArray['DATE(create_date)'] = date('Y-m-d');
        }
        $this->data['displayID'] = $displayID;

        if ( $this->data['loginroleID'] == 3 ) {
            $displayArray         = [];
            $displayArray['uhid'] = $this->data['loginuserID'];
        }

        return $displayArray;
    }

    private function get_medicine_sale_payment()
    {
        $medicinesalepaids = $this->medicinesalepaid_m->get_select_medicinesalepaid();
        $retArray          = [];
        if ( inicompute($medicinesalepaids) ) {
            foreach ( $medicinesalepaids as $medicinesalepaid ) {
                if ( isset($retArray[ $medicinesalepaid->medicinesaleID ]) ) {
                    $retArray[ $medicinesalepaid->medicinesaleID ] += $medicinesalepaid->medicinesalepaidamount;
                } else {
                    $retArray[ $medicinesalepaid->medicinesaleID ] = $medicinesalepaid->medicinesalepaidamount;
                }
            }
        }
        $this->data['medicinesalepaids'] = $retArray;
    }

    public function edit()
    {
        $medicinesaleID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        if ( (int) $medicinesaleID && (int) $displayID ) {
            $this->data['patient_types'] = [
                0 => $this->lang->line('medicinesale_opd'),
                1 => $this->lang->line('medicinesale_ipd'),
                2 => $this->lang->line('medicinesale_none'),
            ];
            $this->data['headerassets']  = [
                'css' => [
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
                ],
                'js'  => [
                    'assets/select2/select2.js',
                    'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                    'assets/inilabs/medicinesale/edit.js',
                    'assets/inilabs/medicinesale/table.js',
                ]
            ];

            $medicineSaleEditQueryArray['medicinesaleID'] = $medicinesaleID;
            if ( $this->data['loginroleID'] == 3 ) {
                $medicineSaleEditQueryArray['uhid'] = $this->data['loginuserID'];
            }
            $this->data['medicinesale'] = $this->medicinesale_m->get_single_medicinesale($medicineSaleEditQueryArray);
            if ( inicompute($this->data['medicinesale']) && ( $this->data['medicinesale']->medicinesalestatus == 0 ) && ( $this->data['medicinesale']->medicinesalerefund == 0 ) ) {
                $displayArray                = $this->_displayManager($displayID);
                $this->data['medicinesales'] = $this->medicinesale_m->get_order_by_medicinesale($displayArray);

                $this->data['activetab']         = false;
                $this->data['medicinecategorys'] = $this->medicinecategory_m->get_select_medicinecategory();
                $this->data['medicines']         = pluck($this->medicine_m->get_medicine(), 'obj', 'medicineID');
                $this->data['medicineobj']       = json_encode($this->data['medicines']);

                $this->medicinesaleitem_m->order('medicinesaleitemID asc');
                $this->data['medicinesaleitems'] = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);

                $salesArray    = [];
                $quantityArray = [];
                if ( inicompute($this->data['medicinesaleitems']) ) {
                    foreach ( $this->data['medicinesaleitems'] as $medicinesaleitem ) {
                        if ( isset($quantityArray[ $medicinesaleitem->medicineID ][ $medicinesaleitem->medicinebatchID ]) ) {
                            $quantityArray[ $medicinesaleitem->medicineID ][ $medicinesaleitem->medicinebatchID ] += $medicinesaleitem->medicinesalequantity;
                        } else {
                            $quantityArray[ $medicinesaleitem->medicineID ][ $medicinesaleitem->medicinebatchID ] = $medicinesaleitem->medicinesalequantity;
                        }
                        $salesArray[ $medicinesaleitem->medicinepurchaseitemID ] = $medicinesaleitem->medicinepurchaseitemID;
                    }
                }

                $i                     = 0;
                $batchArray            = [];
                $maxQuantity           = [];
                $medicinepurchaseitems = $this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem([ 'status' => 0 ]);
                if ( inicompute($medicinepurchaseitems) ) {
                    foreach ( $medicinepurchaseitems as $medicinepurchaseitem ) {
                        if ( $this->checkexpiredate($medicinepurchaseitem->expire_date) ) {
                            $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['batchID']        = $medicinepurchaseitem->batchID;
                            $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['expiredate']     = date('d-m-Y',
                                strtotime($medicinepurchaseitem->expire_date));
                            $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['purchaseqty']    = $medicinepurchaseitem->quantity;
                            $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['saleqty']        = $medicinepurchaseitem->salequantity;
                            $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['medicineitemid'] = $medicinepurchaseitem->medicinepurchaseitemID;

                            if ( isset($quantityArray[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ]) ) {
                                $maxQuantity[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ] = ( $medicinepurchaseitem->quantity - $medicinepurchaseitem->salequantity ) + $quantityArray[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ];
                            } else {
                                $maxQuantity[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ] = 0;
                            }

                            $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['quantity'] = $maxQuantity[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ];
                            $i++;
                        }
                    }
                }

                if ( inicompute($salesArray) ) {
                    $medicinepurchaseitems = $this->medicinepurchaseitem_m->get_where_in_medicinepurchaseitem($salesArray,
                        'medicinepurchaseitemID', [ 'status' => 1 ]);
                    if ( inicompute($medicinepurchaseitems) ) {
                        foreach ( $medicinepurchaseitems as $medicinepurchaseitem ) {
                            if ( $this->checkexpiredate($medicinepurchaseitem->expire_date) ) {
                                $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['batchID']        = $medicinepurchaseitem->batchID;
                                $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['expiredate']     = date('d-m-Y',
                                    strtotime($medicinepurchaseitem->expire_date));
                                $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['purchaseqty']    = $medicinepurchaseitem->quantity;
                                $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['saleqty']        = $medicinepurchaseitem->salequantity;
                                $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['medicineitemid'] = $medicinepurchaseitem->medicinepurchaseitemID;

                                if ( isset($quantityArray[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ]) ) {
                                    $maxQuantity[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ] = ( $medicinepurchaseitem->quantity - $medicinepurchaseitem->salequantity ) + $quantityArray[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ];
                                } else {
                                    $maxQuantity[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ] = 0;
                                }

                                $batchArray[ $medicinepurchaseitem->medicineID ][ $i ]['quantity'] = $maxQuantity[ $medicinepurchaseitem->medicineID ][ $medicinepurchaseitem->batchID ];
                                $i++;
                            }
                        }
                    }
                }

                $this->myData['my_patient_type']              = $this->data['medicinesale']->patient_type;
                $this->myData['myMedicinesaleID']             = $medicinesaleID;
                $this->myData['myDisplayID']                  = $displayID;
                $this->myData['myMedicineobj']                = $this->data['medicineobj'];
                $this->myData['myMedicinesaletotalamount']    = $this->data['medicinesale']->medicinesaletotalamount;
                $this->myData['myMedicinesale_please_select'] = $this->lang->line('medicinesale_please_select');


                $this->data['jsmanager']      = $this->myData;
                $this->data['batchs']         = $batchArray;
                $this->data['maxQuantity']    = $maxQuantity;
                $this->data['medicinesaleID'] = $medicinesaleID;
                $this->data["subview"]        = 'medicinesale/edit';
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

    private function checkexpiredate( $expiredate )
    {
        $medicine_expire_limit_day = isset($this->data['generalsettings']->medicine_expire_limit_day) ? $this->data['generalsettings']->medicine_expire_limit_day : 0;
        $currentdate               = strtotime("+$medicine_expire_limit_day days");
        $expiredate                = strtotime($expiredate);
        if ( $currentdate > $expiredate ) {
            return false;
        }
        return true;
    }

    public function view()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/medicinesale/view.js'
            ]
        ];
        $medicinesaleID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        if ( (int) $medicinesaleID && (int) $displayID ) {
            $this->data['displayID']                      = $displayID;
            $medicineSaleEditQueryArray['medicinesaleID'] = $medicinesaleID;
            if ( $this->data['loginroleID'] == 3 ) {
                $medicineSaleEditQueryArray['uhid'] = $this->data['loginuserID'];
            }
            $this->data['medicinesale'] = $this->medicinesale_m->get_single_medicinesale($medicineSaleEditQueryArray);
            if ( inicompute($this->data['medicinesale']) ) {
                $this->data['jsmanager'] = [ 'myMedicinesaleID' => $this->data['medicinesale']->medicinesaleID ];
                $this->medicinesaleitem_m->order('medicinesaleitemID asc');
                $this->data['medicinesaleitems'] = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                $this->data['medicines']         = pluck($this->medicine_m->get_medicine(), 'obj', 'medicineID');
                $this->data['userName']          = $this->user_m->get_select_user('userID, name',
                    [ 'userID' => $this->data['medicinesale']->create_userID ], true);
                $this->data['medicinesalepaid']  = $this->medicinesalepaid_m->get_medicinesalepaid_sum('medicinesalepaidamount',
                    [ 'medicinesaleID' => $medicinesaleID ]);

                $this->data["subview"] = 'medicinesale/view';
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
        $medicinesaleID = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $medicinesaleID ) {
            $medicineSaleEditQueryArray['medicinesaleID'] = $medicinesaleID;
            if ( $this->data['loginroleID'] == 3 ) {
                $medicineSaleEditQueryArray['uhid'] = $this->data['loginuserID'];
            }
            $this->data['medicinesale'] = $this->medicinesale_m->get_single_medicinesale($medicineSaleEditQueryArray);
            if ( inicompute($this->data['medicinesale']) ) {
                $this->medicinesaleitem_m->order('medicinesaleitemID asc');
                $this->data['medicinesaleitems'] = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                $this->data['medicines']         = pluck($this->medicine_m->get_medicine(), 'obj', 'medicineID');
                $this->data['userName']          = $this->user_m->get_select_user('userID, name',
                    [ 'userID' => $this->data['medicinesale']->create_userID ], true);
                $this->data['medicinesalepaid']  = $this->medicinesalepaid_m->get_medicinesalepaid_sum('medicinesalepaidamount',
                    [ 'medicinesaleID' => $medicinesaleID ]);

                $this->report->reportPDF([
                    'stylesheet' => 'medicinesalemodule.css',
                    'data'       => $this->data,
                    'viewpath'   => 'medicinesale/printpreview'
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
        if ( permissionChecker('medicinesale_view') ) {
            if ( $_POST ) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $medicinesaleID = $this->input->post('medicinesaleID');
                    if ( (int) $medicinesaleID ) {
                        $medicineSaleEditQueryArray['medicinesaleID'] = $medicinesaleID;
                        if ( $this->data['loginroleID'] == 3 ) {
                            $medicineSaleEditQueryArray['uhid'] = $this->data['loginuserID'];
                        }
                        $this->data['medicinesale'] = $this->medicinesale_m->get_single_medicinesale($medicineSaleEditQueryArray);
                        if ( inicompute($this->data['medicinesale']) ) {
                            $email   = $this->input->post('to');
                            $subject = $this->input->post('subject');
                            $message = $this->input->post('message');

                            $this->medicinesaleitem_m->order('medicinesaleitemID asc');
                            $this->data['medicinesaleitems'] = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                            $this->data['medicines']         = pluck($this->medicine_m->get_medicine(), 'obj',
                                'medicineID');
                            $this->data['userName']          = $this->user_m->get_select_user('userID, name',
                                [ 'userID' => $this->data['medicinesale']->create_userID ], true);
                            $this->data['medicinesalepaid']  = $this->medicinesalepaid_m->get_medicinesalepaid_sum('medicinesalepaidamount',
                                [ 'medicinesaleID' => $medicinesaleID ]);

                            $this->report->reportSendToMail([
                                'stylesheet' => 'medicinesalemodule.css',
                                'data'       => $this->data,
                                'viewpath'   => 'medicinesale/printpreview',
                                'email'      => $email,
                                'subject'    => $subject,
                                'message'    => $message
                            ]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = true;
                        } else {
                            $retArray['message'] = $this->lang->line('medicinesale_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('medicinesale_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('medicinesale_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('medicinesale_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules()
    {
        $rules = [
            [
                'field' => 'to',
                'label' => $this->lang->line("medicinesale_to"),
                'rules' => 'trim|required|valid_email'
            ],
            [
                'field' => 'subject',
                'label' => $this->lang->line("medicinesale_subject"),
                'rules' => 'trim|required'
            ],
            [
                'field' => 'message',
                'label' => $this->lang->line("medicinesale_message"),
                'rules' => 'trim'
            ],
            [
                'field' => 'medicinesaleID',
                'label' => $this->lang->line("medicinesale_medicine_sale"),
                'rules' => 'trim|required|numeric'
            ]
        ];
        return $rules;
    }

    public function checkmedcine()
    {
        $retArray            = [];
        $retArray['status']  = false;
        $retArray['message'] = '';
        $retArray['data']    = '';

        if ( permissionChecker('medicinesale_add') || permissionChecker('medicinesale_edit') ) {
            if ( $_POST ) {
                $medicineitems = json_decode($this->input->post('medicineitems'));
                if ( inicompute($medicineitems) ) {
                    $medicineitemQty = [];
                    foreach ( $medicineitems as $medicineitem ) {
                        if ( isset($medicineitemQty[ $medicineitem->medicineitemid ]) ) {
                            $medicineitemQty[ $medicineitem->medicineitemid ] += $medicineitem->quantity;
                        } else {
                            $medicineitemQty[ $medicineitem->medicineitemid ] = $medicineitem->quantity;
                        }
                    }

                    $medicinesaleQuantity = [];
                    if ( $this->input->post('update') == 'update' ) {
                        $medicinesaleID = $this->input->post('medicinesaleID');
                        if ( (int) $medicinesaleID ) {
                            $medicinesaleitems = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                            if ( inicompute($medicinesaleitems) ) {
                                foreach ( $medicinesaleitems as $medicinesaleitem ) {
                                    if ( isset($medicinesaleQuantity[ $medicinesaleitem->medicinepurchaseitemID ]) ) {
                                        $medicinesaleQuantity[ $medicinesaleitem->medicinepurchaseitemID ] += $medicinesaleitem->medicinesalequantity;
                                    } else {
                                        $medicinesaleQuantity[ $medicinesaleitem->medicinepurchaseitemID ] = $medicinesaleitem->medicinesalequantity;
                                    }
                                }
                            }
                        }
                    }

                    $medicineArray = [];
                    foreach ( $medicineitems as $medicineitem ) {
                        $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem([ 'medicinepurchaseitemID' => $medicineitem->medicineitemid ]);
                        if ( inicompute($medicinepurchaseitem) ) {
                            if ( ( $medicineitem->batchID == '' ) && ( $medicineitem->batchID == '0' ) ) {
                                $medicineArray[ $medicineitem->randid ]['batchID'] = true;
                            }

                            if ( $medicineitem->expire_date == '' ) {
                                $medicineArray[ $medicineitem->randid ]['expiredate'] = true;
                            }

                            if ( $medicineitem->unit_price == '' ) {
                                $medicineArray[ $medicineitem->randid ]['unitprice'] = true;
                            }

                            if ( $medicineitem->quantity == '' ) {
                                $medicineArray[ $medicineitem->randid ]['quantity'] = true;
                            }

                            if ( !$this->checkexpiredate(date('d-m-Y',
                                strtotime($medicinepurchaseitem->expire_date))) ) {
                                $medicineArray[ $medicineitem->randid ]['expiredate'] = true;
                            }

                            $currentsaleQty  = isset($medicineitemQty[ $medicineitem->medicineitemid ]) ? $medicineitemQty[ $medicineitem->medicineitemid ] : 0;
                            $medicinesaleQty = isset($medicinesaleQuantity[ $medicineitem->medicineitemid ]) ? $medicinesaleQuantity[ $medicineitem->medicineitemid ] : 0;
                            $saleQty         = ( $currentsaleQty + $medicinepurchaseitem->salequantity ) - $medicinesaleQty;

                            if ( $medicinepurchaseitem->quantity < $saleQty ) {
                                $medicineArray[ $medicineitem->randid ]['quantity'] = true;
                            }
                        } else {
                            $medicineArray[ $medicineitem->randid ]['batchID']    = true;
                            $medicineArray[ $medicineitem->randid ]['expiredate'] = true;
                            $medicineArray[ $medicineitem->randid ]['unitprice']  = true;
                            $medicineArray[ $medicineitem->randid ]['quantity']   = true;
                        }
                    }

                    if ( inicompute($medicineArray) ) {
                        $retArray['status'] = true;
                        $retArray['data']   = $medicineArray;
                    }
                } else {
                    $retArray['status']  = true;
                    $retArray['data']    = '';
                    $retArray['message'] = $this->lang->line('medicinesale_data_not_found');
                }
            } else {
                $retArray['status']  = true;
                $retArray['data']    = '';
                $retArray['message'] = $this->lang->line('medicinesale_method_not_allowed');
            }
        } else {
            $retArray['status']  = true;
            $retArray['data']    = '';
            $retArray['message'] = $this->lang->line('medicinesale_permission_not_allowed');
        }
        echo json_encode($retArray);
    }

    public function savemedicinesale()
    {
        $retArray            = [];
        $retArray['status']  = false;
        $retArray['message'] = '';

        if ( permissionChecker('medicinesale_add') || permissionChecker('medicinesale_edit') ) {
            if ( $_POST ) {
                $update         = $this->input->post('update');
                $medicinesaleID = $this->input->post('medicinesaleID');

                $rules = $this->rules($this->input->post('payment_status'));
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $retArray['message'] = validation_errors();
                } else {
                    $medicinesaleArray         = [];
                    $medicinesaleArray['year'] = date('Y');
                    if ( $this->input->post('patient_type') == 'none' ) {
                        $medicinesaleArray['patient_type'] = 2;
                        $medicinesaleArray['uhid']         = 0;
                    } else {
                        $medicinesaleArray['patient_type'] = $this->input->post('patient_type');
                        $medicinesaleArray['uhid']         = $this->input->post('uhid');
                    }
                    $medicinesaleArray['medicinesaledate']             = date('Y-m-d',
                        strtotime($this->input->post('medicinesaledate')));
                    $medicinesaleArray['medicinesalefile']             = $this->upload_data['medicinesalefile']['file_name'];
                    $medicinesaleArray['medicinesalefileoriginalname'] = $this->upload_data['medicinesalefile']['client_name'];
                    $medicinesaleArray['medicinesaledescription']      = $this->input->post('medicinesaledescription');
                    $medicinesaleArray['medicinesalestatus']           = $this->input->post('payment_status');
                    $medicinesaleArray['medicinesalerefund']           = 0;
                    $medicinesaleArray["create_date"]                  = date("Y-m-d H:i:s");
                    $medicinesaleArray["modify_date"]                  = date("Y-m-d H:i:s");
                    $medicinesaleArray["create_userID"]                = $this->session->userdata('loginuserID');
                    $medicinesaleArray["create_roleID"]                = $this->session->userdata('roleID');

                    $medicineitems     = json_decode($this->input->post('medicineitems'));
                    $medicineitemQty   = [];
                    $total_sale_amount = 0;
                    if ( inicompute($medicineitems) ) {
                        foreach ( $medicineitems as $medicineitem ) {
                            $total_sale_amount += ( $medicineitem->quantity * $medicineitem->unit_price );

                            if ( isset($medicineitemQty[ $medicineitem->medicineitemid ]) ) {
                                $medicineitemQty[ $medicineitem->medicineitemid ] += $medicineitem->quantity;
                            } else {
                                $medicineitemQty[ $medicineitem->medicineitemid ] = $medicineitem->quantity;
                            }
                        }
                    }
                    $medicinesaleArray["medicinesaletotalamount"] = $total_sale_amount;

                    if ( $update == 'update' ) {
                        $this->medicinesale_m->update_medicinesale($medicinesaleArray, $medicinesaleID);
                    } else {
                        $this->medicinesale_m->insert_medicinesale($medicinesaleArray);
                        $medicinesaleID = $this->db->insert_id();
                    }

                    // Here Need Check Here 
                    if ( $update == 'update' ) {
                        $medicinesaleitems = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                        if ( inicompute($medicinesaleitems) ) {
                            foreach ( $medicinesaleitems as $medicinesaleitem ) {
                                $removeQuatity          = $medicinesaleitem->medicinesalequantity;
                                $medicinepurchaseitemID = $medicinesaleitem->medicinepurchaseitemID;
                                $medicinepurchaseitem   = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem([ 'medicinepurchaseitemID' => $medicinepurchaseitemID ]);
                                if ( inicompute($medicinepurchaseitem) ) {
                                    $medicinesaleQuantity                  = $medicinepurchaseitem->salequantity - $removeQuatity;
                                    $medicinepurchaseArray                 = [];
                                    $medicinepurchaseArray['salequantity'] = $medicinesaleQuantity;
                                    $medicinepurchaseArray['status']       = 0;
                                    if ( $medicinepurchaseitem->quantity == $medicinesaleQuantity ) {
                                        $medicinepurchaseArray['status'] = 1;
                                    }
                                    $this->medicinepurchaseitem_m->update_medicinepurchaseitem($medicinepurchaseArray,
                                        $medicinepurchaseitemID);
                                }
                            }
                        }
                    }

                    if ( inicompute($medicineitemQty) ) {
                        foreach ( $medicineitemQty as $medicineitemid => $itemQuatity ) {
                            $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem([ 'medicinepurchaseitemID' => $medicineitemid ]);
                            if ( inicompute($medicinepurchaseitem) ) {
                                $medicinesaleQuantity = $medicinepurchaseitem->salequantity + $itemQuatity;

                                $medicinepurchaseitemArray                 = [];
                                $medicinepurchaseitemArray['salequantity'] = $medicinesaleQuantity;
                                $medicinepurchaseitemArray['status']       = 0;
                                if ( $medicinepurchaseitem->quantity == $medicinesaleQuantity ) {
                                    $medicinepurchaseitemArray['status'] = 1;
                                }
                                $this->medicinepurchaseitem_m->update_medicinepurchaseitem($medicinepurchaseitemArray,
                                    $medicineitemid);
                            }
                        }
                    }

                    $medcineitemArray = [];
                    if ( inicompute($medicineitems) ) {
                        $i = 0;
                        foreach ( $medicineitems as $medicineitem ) {
                            $medcineitemArray[ $i ]['year']                   = date('Y');
                            $medcineitemArray[ $i ]['medicinesaleID']         = $medicinesaleID;
                            $medcineitemArray[ $i ]['medicineID']             = $medicineitem->medicineid;
                            $medcineitemArray[ $i ]['medicinepurchaseitemID'] = $medicineitem->medicineitemid;
                            $medcineitemArray[ $i ]['medicinebatchID']        = $medicineitem->batchID;
                            $medcineitemArray[ $i ]['medicineexpiredate']     = date('Y-m-d H:i:s',
                                strtotime($medicineitem->expire_date));
                            $medcineitemArray[ $i ]['medicinesaleunitprice']  = $medicineitem->unit_price;
                            $medcineitemArray[ $i ]['medicinesalequantity']   = $medicineitem->quantity;
                            $medcineitemArray[ $i ]['medicinesalesubtotal']   = ( $medicineitem->unit_price * $medicineitem->quantity );
                            $i++;
                        }
                    }

                    if ( inicompute($medcineitemArray) ) {
                        if ( $update == 'update' ) {
                            $this->medicinesaleitem_m->delete_batch_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                        }
                        $this->medicinesaleitem_m->insert_batch_medicinesaleitem($medcineitemArray);
                    }

                    if ( $this->input->post('payment_status') > 0 ) {
                        $medicinepaidArray                                  = [];
                        $medicinepaidArray['year']                          = date('Y');
                        $medicinepaidArray['medicinesalepaidyear']          = date('Y');
                        $medicinepaidArray['medicinesaleID']                = $medicinesaleID;
                        $medicinepaidArray['medicinesalepaiddate']          = date('Y-m-d H:i:s');
                        $medicinepaidArray['medicinesalepaidreferenceno']   = $this->input->post('reference_no');
                        $medicinepaidArray['medicinesalepaidamount']        = $this->input->post('payment_amount');
                        $medicinepaidArray['medicinesalepaidpaymentmethod'] = $this->input->post('payment_method');
                        $medicinepaidArray["create_date"]                   = date("Y-m-d H:i:s");
                        $medicinepaidArray["modify_date"]                   = date("Y-m-d H:i:s");
                        $medicinepaidArray["create_userID"]                 = $this->session->userdata('loginuserID');
                        $medicinepaidArray["create_roleID"]                 = $this->session->userdata('roleID');

                        $this->medicinesalepaid_m->insert_medicinesalepaid($medicinepaidArray);

                        if ( $this->input->post('payment_amount') == $total_sale_amount ) {
                            $this->medicinesale_m->update_medicinesale([ 'medicinesalestatus' => 2 ], $medicinesaleID);
                        } else {
                            $this->medicinesale_m->update_medicinesale([ 'medicinesalestatus' => 1 ], $medicinesaleID);
                        }
                    }

                    $retArray['status']  = true;
                    $retArray['message'] = 'Success';
                    $this->session->set_flashdata('success', 'Success');
                }
            } else {
                $retArray['message'] = $this->lang->line('medicinepurchase_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('medicinepurchase_permission_not_allowed');
        }
        echo json_encode($retArray);
    }

    protected function rules( $payment_status )
    {
        $rules = [
            [
                'field' => 'patient_type',
                'label' => $this->lang->line("medicinesale_patient_type"),
                'rules' => 'trim|required'
            ],
            [
                'field' => 'medicinesaledate',
                'label' => $this->lang->line("medicinesale_date"),
                'rules' => 'trim|required|max_length[10]|callback_valid_date'
            ],
            [
                'field' => 'payment_status',
                'label' => $this->lang->line("medicinesale_payment_status"),
                'rules' => 'trim|required|max_length[1]|numeric|callback_valid_data'
            ],
            [
                'field' => 'medicinesalefile',
                'label' => $this->lang->line("medicinesale_file"),
                'rules' => 'trim|callback_unique_medicinesalefile'
            ],
            [
                'field' => 'medicinesaledescription',
                'label' => $this->lang->line("medicinesale_description"),
                'rules' => 'trim|max_length[200]'
            ]
        ];

        if ( $this->input->post('patient_type') != 'none' ) {
            $rules[] = [
                'field' => 'uhid',
                'label' => $this->lang->line("medicinesale_uhid"),
                'rules' => 'trim|required|max_length[11]|callback_unique_uhid'
            ];
        }

        if ( (int) $payment_status && ( $payment_status != 0 ) ) {
            $rules[] = [
                'field' => 'reference_no',
                'label' => $this->lang->line("medicinesale_reference_no"),
                'rules' => 'trim|max_length[50]'
            ];
            $rules[] = [
                'field' => 'payment_amount',
                'label' => $this->lang->line("medicinesale_amount"),
                'rules' => 'trim|required|numeric|greater_than[0]|max_length[16]|callback_check_grandtotal_amount'
            ];
            $rules[] = [
                'field' => 'payment_method',
                'label' => $this->lang->line("medicinesale_payment_method"),
                'rules' => 'trim|required|numeric|callback_required_no_zero|max_length[1]'
            ];
        }
        return $rules;
    }

    public function delete()
    {
        $medicinesaleID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        if ( (int) $medicinesaleID && (int) $displayID ) {
            $medicineSaleDeleteQueryArray['medicinesaleID'] = $medicinesaleID;
            if ( $this->data['loginroleID'] == 3 ) {
                $medicineSaleDeleteQueryArray['uhid'] = $this->data['loginuserID'];
            }
            $medicinesale = $this->medicinesale_m->get_single_medicinesale($medicineSaleDeleteQueryArray);
            if ( inicompute($medicinesale) && ( $medicinesale->medicinesalestatus == 0 ) && ( $medicinesale->medicinesalerefund == 0 ) ) {
                if ( ( $medicinesale->medicinesalefile != '' ) && ( config_item('demo') == false ) ) {
                    if ( file_exists(FCPATH . 'uploads/files/' . $medicinesale->medicinesalefile) ) {
                        unlink(FCPATH . 'uploads/files/' . $medicinesale->medicinesalefile);
                    }
                }
                $medicinesaleitems = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                if ( inicompute($medicinesaleitems) ) {
                    foreach ( $medicinesaleitems as $medicinesaleitem ) {
                        $saleremoveQuatity      = $medicinesaleitem->medicinesalequantity;
                        $medicinepurchaseitemID = $medicinesaleitem->medicinepurchaseitemID;
                        $medicinepurchaseitem   = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem([ 'medicinepurchaseitemID' => $medicinepurchaseitemID ]);
                        if ( inicompute($medicinepurchaseitem) ) {
                            $medicinesaleQuantity  = $medicinepurchaseitem->salequantity - $saleremoveQuatity;
                            $array                 = [];
                            $array['salequantity'] = $medicinesaleQuantity;
                            $array['status']       = 0;
                            if ( $medicinesaleQuantity == $medicinepurchaseitem->quantity ) {
                                $array['status'] = 1;
                            }
                            $this->medicinepurchaseitem_m->update_medicinepurchaseitem($array, $medicinepurchaseitemID);
                        }
                    }
                }
                $this->medicinesale_m->delete_medicinesale($medicinesaleID);
                $this->medicinesaleitem_m->delete_batch_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('medicinesale/index/' . $displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function cancel()
    {
        if ( permissionChecker('medicinesale_edit') && permissionChecker('medicinesale_delete') ) {
            $medicinesaleID = htmlentities(escapeString($this->uri->segment(3)));
            $displayID      = htmlentities(escapeString($this->uri->segment(4)));
            if ( (int) $medicinesaleID && (int) $displayID ) {
                $medicineSaleEditQueryArray['medicinesaleID'] = $medicinesaleID;
                if ( $this->data['loginroleID'] == 3 ) {
                    $medicineSaleEditQueryArray['uhid'] = $this->data['loginuserID'];
                }
                $medicinesale = $this->medicinesale_m->get_single_medicinesale($medicineSaleEditQueryArray);
                if ( inicompute($medicinesale) && ( $medicinesale->medicinesalestatus != 0 ) && ( $medicinesale->medicinesalerefund != 1 ) ) {
                    $medicinesaleitems = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                    if ( inicompute($medicinesaleitems) ) {
                        foreach ( $medicinesaleitems as $medicinesaleitem ) {
                            $saleremoveQuatity      = $medicinesaleitem->medicinesalequantity;
                            $medicinepurchaseitemID = $medicinesaleitem->medicinepurchaseitemID;
                            $medicinepurchaseitem   = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem([ 'medicinepurchaseitemID' => $medicinepurchaseitemID ]);
                            if ( inicompute($medicinepurchaseitem) ) {
                                $medicinesaleQuantity  = $medicinepurchaseitem->salequantity - $saleremoveQuatity;
                                $array                 = [];
                                $array['salequantity'] = $medicinesaleQuantity;
                                $array['status']       = 0;
                                if ( $medicinesaleQuantity == $medicinepurchaseitem->quantity ) {
                                    $array['status'] = 1;
                                }
                                $this->medicinepurchaseitem_m->update_medicinepurchaseitem($array,
                                    $medicinepurchaseitemID);
                            }
                        }
                    }
                    $this->medicinesale_m->update_medicinesale([ 'medicinesalerefund' => 1 ], $medicinesaleID);
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('medicinesale/index/' . $displayID));
                } else {
                    redirect(site_url('medicinesale/index/' . $displayID));
                }
            } else {
                redirect(site_url('medicinesale/index/' . $displayID));
            }
        } else {
            redirect(site_url('medicinesale/index'));
        }
    }

    public function paymentdelete()
    {
        if ( permissionChecker('medicinesale_delete') ) {
            $medicinesalepaidID = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $medicinesalepaidID ) {
                $medicinesalepaid = $this->medicinesalepaid_m->get_select_medicinesalepaid_with_medicinesale('medicinesalepaid.*, medicinesale.uhid',
                    [ 'medicinesalepaidID' => $medicinesalepaidID ], true);
                if ( $this->data['loginroleID'] == 3 ) {
                    if ( inicompute($medicinesalepaid) ) {
                        if ( $medicinesalepaid->uhid == $this->data['loginuserID'] ) {
                            $typeStatus = true;
                        } else {
                            $typeStatus = false;
                        }
                    } else {
                        $typeStatus = false;
                    }
                } else {
                    $typeStatus = true;
                }
                if ( inicompute($medicinesalepaid) && $typeStatus ) {
                    if ( ( $medicinesalepaid->medicinesalepaidfile != '' ) && ( config_item('demo') == false ) ) {
                        if ( file_exists(FCPATH . 'uploads/files/' . $medicinesalepaid->medicinesalepaidfile) ) {
                            unlink(FCPATH . 'uploads/files/' . $medicinesalepaid->medicinesalepaidfile);
                        }
                    }

                    $medicinesaleID = $medicinesalepaid->medicinesaleID;
                    $medicinesale   = $this->medicinesale_m->get_single_medicinesale([ 'medicinesaleID' => $medicinesaleID ]);
                    if ( inicompute($medicinesale) && ( $medicinesale->medicinesalerefund == 0 ) ) {
                        $this->medicinesalepaid_m->delete_medicinesalepaid($medicinesalepaidID);
                        $medicinesalepaid = $this->medicinesalepaid_m->get_medicinesalepaid_sum('medicinesalepaidamount',
                            [ 'medicinesaleID' => $medicinesaleID ]);

                        $medicinesaleArray = [];
                        if ( $medicinesale->medicinesaletotalamount == $medicinesalepaid->medicinesalepaidamount ) {
                            $medicinesaleArray['medicinesalestatus'] = 2;
                        } elseif ( ( $medicinesalepaid->medicinesalepaidamount < $medicinesale->medicinesaletotalamount ) && ( $medicinesalepaid->medicinesalepaidamount > 0 ) ) {
                            $medicinesaleArray['medicinesalestatus'] = 1;
                        } else {
                            $medicinesaleArray['medicinesalestatus'] = 0;
                        }
                        $this->medicinesale_m->update_medicinesale($medicinesaleArray, $medicinesaleID);
                        $this->session->set_flashdata('success', 'Success');
                    }
                }
            }
        }
        redirect(site_url('medicinesale/index'));
    }

    public function savemedicinesalepayment()
    {
        $retArray           = [];
        $retArray['status'] = false;
        if ( permissionChecker('medicinesale_add') ) {
            $medicinesaleID                           = $this->input->post('medicinesaleID');
            $medicineSaleQueryArray['medicinesaleID'] = $medicinesaleID;
            if ( $this->data['loginroleID'] == 3 ) {
                $medicineSaleQueryArray['uhid'] = $this->data['loginuserID'];
            }
            $medicinesale = $this->medicinesale_m->get_single_medicinesale($medicineSaleQueryArray);
            if ( inicompute($medicinesale) ) {
                if ( $_POST ) {
                    $rules = $this->rules_medicine_payment();
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() == false ) {
                        $retArray           = $this->form_validation->error_array();
                        $retArray['status'] = false;
                    } else {
                        $array['medicinesaleID']                = $this->input->post('medicinesaleID');
                        $array['year']                          = date('Y');
                        $array['medicinesalepaidyear']          = $medicinesale->year;
                        $array['medicinesalepaiddate']          = date('Y-m-d',
                            strtotime($this->input->post('payment_date')));
                        $array['medicinesalepaidreferenceno']   = $this->input->post('paidreference_no');
                        $array['medicinesalepaidamount']        = $this->input->post('payment_amount');
                        $array['medicinesalepaidpaymentmethod'] = $this->input->post('paidpayment_method');
                        $array['medicinesalepaidfile']          = $this->upload_data['payment_file']['file_name'];
                        $array['medicinesalepaidorginalname']   = $this->upload_data['payment_file']['client_name'];
                        $array['medicinesalepaiddescription']   = $this->input->post('payment_method');
                        $array["create_date"]                   = date("Y-m-d H:i:s");
                        $array["modify_date"]                   = date("Y-m-d H:i:s");
                        $array["create_userID"]                 = $this->session->userdata('loginuserID');
                        $array["create_roleID"]                 = $this->session->userdata('roleID');

                        $this->medicinesalepaid_m->insert_medicinesalepaid($array);

                        $medicinesalepaid  = $this->medicinesalepaid_m->get_medicinesalepaid_sum('medicinesalepaidamount',
                            [ 'medicinesaleID' => $medicinesaleID ]);
                        $medicinesaleArray = [];
                        if ( $medicinesale->medicinesaletotalamount == $medicinesalepaid->medicinesalepaidamount ) {
                            $medicinesaleArray['medicinesalestatus'] = 2;
                        } else {
                            $medicinesaleArray['medicinesalestatus'] = 1;
                        }
                        $this->medicinesale_m->update_medicinesale($medicinesaleArray, $medicinesaleID);

                        $this->session->set_flashdata('success', 'Success');
                        $retArray['message'] = 'Success';
                        $retArray['status']  = true;
                    }
                } else {
                    $retArray['message'] = $this->lang->line('medicinesale_method_not_allowed');
                }
            } else {
                $retArray['message'] = $this->lang->line('medicinesale_data_not_found');
            }
        } else {
            $retArray['message'] = $this->lang->line('medicinesale_permission_not_allowed');
        }
        echo json_encode($retArray);
    }

    protected function rules_medicine_payment()
    {
        $rules = [
            [
                'field' => 'payment_date',
                'label' => $this->lang->line("medicinesale_date"),
                'rules' => 'trim|required|callback_valid_date|max_length[10]'
            ],
            [
                'field' => 'paidreference_no',
                'label' => $this->lang->line("medicinesale_reference_no"),
                'rules' => 'trim|max_length[50]'
            ],
            [
                'field' => 'payment_amount',
                'label' => $this->lang->line("medicinesale_amount"),
                'rules' => 'trim|required|max_length[20]|numeric|greater_than[0]|callback_check_payment_amount'

            ],
            [
                'field' => 'paidpayment_method',
                'label' => $this->lang->line("medicinesale_payment_method"),
                'rules' => 'trim|required|numeric|max_length[1]|callback_required_no_zero'
            ],
            [
                'field' => 'payment_file',
                'label' => $this->lang->line("medicinesale_file"),
                'rules' => 'trim|callback_unique_medicinesalepaid_file'
            ],
        ];
        return $rules;
    }

    public function download()
    {
        if ( permissionChecker('medicinesale_view') ) {
            $medicinesaleID = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $medicinesaleID ) {
                $medicineSaleQueryArray['medicinesaleID'] = $medicinesaleID;
                if ( $this->data['loginroleID'] == 3 ) {
                    $medicineSaleQueryArray['uhid'] = $this->data['loginuserID'];
                }
                $medicinesale = $this->medicinesale_m->get_single_medicinesale($medicineSaleQueryArray);
                if ( inicompute($medicinesale) && $medicinesale->medicinesalefile != '' ) {
                    $file = realpath('uploads/files/' . $medicinesale->medicinesalefile);
                    if ( file_exists($file) ) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . basename($medicinesale->medicinesalefileoriginalname) . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                        exit;
                    } else {
                        redirect(site_url('medicinesale/index'));
                    }
                } else {
                    redirect(site_url('medicinesale/index'));
                }
            } else {
                redirect(site_url('medicinesale/index'));
            }
        } else {
            redirect(site_url('medicinesale/index'));
        }
    }

    public function paymentdownload()
    {
        if ( permissionChecker('medicinesale_view') ) {
            $medicinesalepaidID = htmlentities(escapeString($this->uri->segment(3)));
            if ( (int) $medicinesalepaidID ) {
                $medicinesalepaid = $this->medicinesalepaid_m->get_select_medicinesalepaid_with_medicinesale('medicinesalepaid.*, medicinesale.uhid',
                    [ 'medicinesalepaidID' => $medicinesalepaidID ], true);
                if ( $this->data['loginroleID'] == 3 ) {
                    if ( inicompute($medicinesalepaid) ) {
                        if ( $medicinesalepaid->uhid == $this->data['loginuserID'] ) {
                            $typeStatus = true;
                        } else {
                            $typeStatus = false;
                        }
                    } else {
                        $typeStatus = false;
                    }
                } else {
                    $typeStatus = true;
                }

                if ( ( inicompute($medicinesalepaid) && $typeStatus ) && $medicinesalepaid->medicinesalepaidfile != '' ) {
                    $file = realpath('uploads/files/' . $medicinesalepaid->medicinesalepaidfile);
                    if ( file_exists($file) ) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . basename($medicinesalepaid->medicinesalepaidorginalname) . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                        exit;
                    } else {
                        redirect(site_url('medicinesale/index'));
                    }
                } else {
                    redirect(site_url('medicinesale/index'));
                }
            } else {
                redirect(site_url('medicinesale/index'));
            }
        } else {
            redirect(site_url('medicinesale/index'));
        }
    }

    public function get_sale_paid_info()
    {
        $retArray['status'] = true;
        if ( permissionChecker('medicinesale_view') ) {
            $medicinesaleID = $this->input->post('medicinesaleID');
            if ( (int) $medicinesaleID ) {
                $this->data['medicinesale'] = $this->medicinesale_m->get_single_medicinesale([ 'medicinesaleID' => $medicinesaleID ]);
                if ( inicompute($this->data['medicinesale']) ) {
                    $this->data['medicinesalepaids'] = $this->medicinesalepaid_m->get_order_by_medicinesalepaid([ 'medicinesaleID' => $medicinesaleID ]);
                    $retArray['view']                = $this->load->view('medicinesale/sale_paid_info_table',
                        $this->data, true);
                }
            }
        }
        echo json_encode($retArray);
    }

    public function get_medicine()
    {
        echo "<option value='0'>" . '— ' . $this->lang->line("medicinesale_please_select") . ' —' . "</option>";
        if ( $_POST ) {
            $medicinecategoryID = $this->input->post('medicinecategoryID');
            if ( (int) $medicinecategoryID ) {
                $medicines = $this->medicine_m->get_select_medicine('medicineID, name',
                    [ 'medicinecategoryID' => $medicinecategoryID ]);
                if ( inicompute($medicines) ) {
                    foreach ( $medicines as $medicine ) {
                        echo "<option value='" . $medicine->medicineID . "'>" . $medicine->name . "</option>";
                    }
                }
            }
        }
    }

    public function get_sale_info()
    {
        $retArray['status'] = false;
        if ( permissionChecker('medicinesale_add') ) {
            $medicinesaleID = $this->input->post('medicinesaleID');
            if ( (int) $medicinesaleID ) {
                $medicinesale = $this->medicinesale_m->get_single_medicinesale([ 'medicinesaleID' => $medicinesaleID ]);
                if ( inicompute($medicinesale) ) {
                    $medicinesalepaid      = $this->medicinesalepaid_m->get_medicinesalepaid_sum('medicinesalepaidamount',
                        [ 'medicinesaleID' => $medicinesaleID ]);
                    $retArray['dueamount'] = ( $medicinesale->medicinesaletotalamount - $medicinesalepaid->medicinesalepaidamount );
                    $retArray['status']    = true;
                }
            }
        }
        echo json_encode($retArray);
    }

    public function check_payment_amount()
    {
        $medicinesaleID = $this->input->post('medicinesaleID');
        if ( (int) $medicinesaleID ) {
            $medicinesale = $this->medicinesale_m->get_single_medicinesale([ 'medicinesaleID' => $medicinesaleID ]);
            if ( inicompute($medicinesale) ) {
                $medicinesalepaid = $this->medicinesalepaid_m->get_medicinesalepaid_sum('medicinesalepaidamount',
                    [ 'medicinesaleID' => $medicinesaleID ]);
                $dueamount        = ( $medicinesale->medicinesaletotalamount - $medicinesalepaid->medicinesalepaidamount );
                $payment_amount   = $this->input->post('payment_amount');
                if ( $payment_amount > $dueamount ) {
                    $this->form_validation->set_message("check_payment_amount",
                        "the payment amount greater than due amount.");
                    return false;
                }
                return true;
            } else {
                $this->form_validation->set_message("check_payment_amount", "Invalid Data");
                return false;
            }
        } else {
            $this->form_validation->set_message("check_payment_amount", "Invalid Data");
            return false;
        }
    }

    public function check_grandtotal_amount( $payment_amount )
    {
        $globalsubtotal = $this->input->post('globalsubtotal');
        if ( $payment_amount > $globalsubtotal ) {
            $this->form_validation->set_message("check_grandtotal_amount",
                "Payment amount grater than total invoice amount");
            return false;
        }
        return true;
    }

    public function unique_medicinesalefile()
    {
        $new_file = '';
        if ( $_FILES["medicinesalefile"]['name'] != "" ) {
            $file_name        = $_FILES["medicinesalefile"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512',
                $random . ( strtotime(date('Y-m-d H:i:s')) ) . config_item("encryption_key"));
            $file_name_rename = 'medicinesale_' . $makeRandom;
            $explode          = explode('.', $file_name);
            if ( inicompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/files";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name']     = $new_file;
                $config['max_size']      = '5120';
                $config['max_width']     = '10000';
                $config['max_height']    = '10000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("medicinesalefile") ) {
                    $this->form_validation->set_message("unique_medicinesalefile", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['medicinesalefile'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("unique_medicinesalefile", "Invalid File");
                return false;
            }
        } else {
            $medicinesaleID = $this->input->post('medicinesaleID');
            if ( (int) $medicinesaleID ) {
                $medicinesale = $this->medicinesale_m->get_single_medicinesale([ 'medicinesaleID' => $medicinesaleID ]);
                if ( inicompute($medicinesale) ) {
                    $this->upload_data['medicinesalefile']['file_name']   = $medicinesale->medicinesalefile;
                    $this->upload_data['medicinesalefile']['client_name'] = $medicinesale->medicinesalefileoriginalname;
                    return true;
                } else {
                    $this->upload_data['medicinesalefile']['file_name']   = $new_file;
                    $this->upload_data['medicinesalefile']['client_name'] = $new_file;
                    return true;
                }
            } else {
                $this->upload_data['medicinesalefile']['file_name']   = $new_file;
                $this->upload_data['medicinesalefile']['client_name'] = $new_file;
                return true;
            }
        }
    }

    public function unique_medicinesalepaid_file()
    {
        $new_file = '';
        if ( $_FILES["payment_file"]['name'] != "" ) {
            $file_name        = $_FILES["payment_file"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512',
                $random . ( strtotime(date('Y-m-d H:i:s')) ) . config_item("encryption_key"));
            $file_name_rename = 'medicinesalepaid_' . $makeRandom;
            $explode          = explode('.', $file_name);
            if ( inicompute($explode) >= 2 ) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/files";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name']     = $new_file;
                $config['max_size']      = '5120';
                $config['max_width']     = '10000';
                $config['max_height']    = '10000';
                $this->load->library('upload', $config);
                if ( !$this->upload->do_upload("payment_file") ) {
                    $this->form_validation->set_message("unique_file", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['payment_file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("unique_file", "Invalid File");
                return false;
            }
        } else {
            $this->upload_data['payment_file']['file_name']   = $new_file;
            $this->upload_data['payment_file']['client_name'] = $new_file;
            return true;
        }
    }

    public function getmedicinebatch()
    {
        $retArray['status'] = false;
        if ( permissionChecker('medicinesale_add') || permissionChecker('medicinesale_edit') ) {
            if ( $_POST ) {
                $medicineID = $this->input->post('medicineID');
                if ( (int) $medicineID ) {
                    $medicinepurchaseitems = $this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem([
                        'medicineID' => $medicineID,
                        'status'     => 0
                    ]);

                    $medicineitemQty = [];
                    if ( $this->input->post('update') == 'update' ) {
                        $medicinesaleID    = $this->input->post('medicinesaleID');
                        $medicinesaleitems = $this->medicinesaleitem_m->get_order_by_medicinesaleitem([ 'medicinesaleID' => $medicinesaleID ]);
                        if ( inicompute($medicinesaleitems) ) {
                            foreach ( $medicinesaleitems as $medicinesaleitem ) {
                                $medicineitemQty[ $medicinesaleitem->medicinepurchaseitemID ] = $medicinesaleitem->medicinesalequantity;
                            }
                        }
                    }

                    $i          = 0;
                    $batchArray = [];
                    if ( inicompute($medicinepurchaseitems) ) {
                        foreach ( $medicinepurchaseitems as $medicinepurchaseitem ) {
                            if ( $this->checkexpiredate($medicinepurchaseitem->expire_date) ) {
                                $batchArray[ $i ]['batchID']     = $medicinepurchaseitem->batchID;
                                $batchArray[ $i ]['expiredate']  = date('d-m-Y',
                                    strtotime($medicinepurchaseitem->expire_date));
                                $batchArray[ $i ]['purchaseqty'] = $medicinepurchaseitem->quantity;
                                $batchArray[ $i ]['saleqty']     = $medicinepurchaseitem->salequantity;

                                $quantity = ( $medicinepurchaseitem->quantity - $medicinepurchaseitem->salequantity );
                                if ( $this->input->post('update') == 'update' ) {
                                    $batchArray[ $i ]['quantity'] = ( isset($medicineitemQty[ $medicinepurchaseitem->medicinepurchaseitemID ]) ? $medicineitemQty[ $medicinepurchaseitem->medicinepurchaseitemID ] : 0 ) + $quantity;
                                } else {
                                    $batchArray[ $i ]['quantity'] = $quantity;
                                }
                                $batchArray[ $i ]['medicineitemid'] = $medicinepurchaseitem->medicinepurchaseitemID;
                                $i++;
                            }
                        }
                    }
                    if ( inicompute($batchArray) ) {
                        $retArray['status'] = true;
                        $retArray['data']   = $batchArray;
                    }
                }
            }
        }
        echo json_encode($retArray);
    }

    public function required_no_zero( $data )
    {
        if ( $data == '0' ) {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return false;
        } else {
            return true;
        }
    }

    public function valid_data( $data )
    {
        if ( $data == 'none' ) {
            $this->form_validation->set_message("valid_data", "The %s field is required.");
            return false;
        }
        return true;
    }

    public function valid_date( $date )
    {
        if ( $date ) {
            if ( strlen($date) < 10 ) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                return false;
            } else {
                $arr = explode("-", $date);
                if ( inicompute($arr) == 3 ) {
                    $dd   = $arr[0];
                    $mm   = $arr[1];
                    $yyyy = $arr[2];
                    if ( checkdate($mm, $dd, $yyyy) ) {
                        return true;
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                        return false;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                    return false;
                }
            }
        } else {
            return true;
        }
    }

    public function unique_uhid()
    {
        if ( $this->input->post('patient_type') != 'none' ) {
            $uniqueUhidQueryArray['delete_at'] = 0;
            $uniqueUhidQueryArray['patientID'] = $this->input->post('uhid');
            if ( $this->data['loginroleID'] == 3 ) {
                if ( $this->data['loginuserID'] == $this->input->post('uhid') ) {
                    return true;
                } else {
                    $this->form_validation->set_message("unique_uhid", "Only your UHID take here ");
                    return false;
                }
            }
            $patient = $this->patient_m->get_single_patient($uniqueUhidQueryArray);
            if ( !inicompute($patient) ) {
                $this->form_validation->set_message("unique_uhid", "UHID does not match");
                return false;
            }
            return true;
        }
        return true;
    }
}
