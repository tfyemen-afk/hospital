<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicinepurchase extends Admin_Controller
{
    protected $myData = [];
    public function __construct()
    {
        parent::__construct();
        $this->load->model('medicine_m');
        $this->load->model('medicinecategory_m');
        $this->load->model('medicinewarehouse_m');
        $this->load->model('medicinepurchase_m');
        $this->load->model('medicinepurchaseitem_m');
        $this->load->model('medicinepurchasepaid_m');
        $this->load->model('user_m');
        $this->load->library('report');
        $language = $this->session->userdata('lang');
        $this->lang->load('medicinepurchase', $language);
    }

    protected function rules($payment_status)
    {
        $rules = array(
            array(
                'field' => 'medicinewarehouseID',
                'label' => $this->lang->line("medicinepurchase_warehouse"),
                'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
            ),
            array(
                'field' => 'purchase_referenceno',
                'label' => $this->lang->line("medicinepurchase_reference_no"),
                'rules' => 'trim|required|max_length[100]'
            ),
            array(
                'field' => 'medicinepurchasedate',
                'label' => $this->lang->line("medicinepurchase_date"),
                'rules' => 'trim|required|max_length[10]|callback_valid_date'
            ),
            array(
                'field' => 'medicinepurchasefile',
                'label' => $this->lang->line("medicinepurchase_file"),
                'rules' => 'trim|callback_unique_medicinepurchasefile'
            ),
            array(
                'field' => 'medicinepurchasedescription',
                'label' => $this->lang->line("medicinepurchase_description"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'payment_status',
                'label' => $this->lang->line("medicinepurchase_payment_status"),
                'rules' => 'trim|required|numeric|max_length[11]'
            )
        );

        if((int)$payment_status && ($payment_status != 0)) {
            $rules[] = array(
                'field' => 'purchasepaid_referenceno',
                'label' => $this->lang->line("medicinepurchase_reference_no"),
                'rules' => 'trim|max_length[50]'
            );
            $rules[] = array(
                'field' => 'payment_amount',
                'label' => $this->lang->line("medicinepurchase_amount"),
                'rules' => 'trim|required|numeric|greater_than[0]|max_length[16]'
            );
            $rules[] = array(
                'field' => 'purchasepaid_payment_method',
                'label' => $this->lang->line("medicinepurchase_payment_method"),
                'rules' => 'trim|required|callback_required_no_zero|max_length[4]'
            );
        }
        return $rules;
    }

    protected function rules_medicine_payment()
    {
        $rules = array(
            array(
                'field' => 'payment_date',
                'label' => $this->lang->line("medicinepurchase_date"),
                'rules' => 'trim|required|max_length[10]|callback_valid_date'
            ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line("medicinepurchase_reference_no"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'payment_amount',
                'label' => $this->lang->line("medicinepurchase_amount"),
                'rules' => 'trim|required|numeric|greater_than[0]|max_length[20]|callback_check_payment_amount'
            ),
            array(
                'field' => 'payment_method',
                'label' => $this->lang->line("medicinepurchase_payment_method"),
                'rules' => 'trim|required|numeric|max_length[1]|callback_required_no_zero'
            ),
            array(
                'field' => 'payment_file',
                'label' => $this->lang->line("medicinepurchase_file"),
                'rules' => 'trim|callback_unique_payment_file'
            ),
        );
        return $rules;
    }

	public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                'assets/inilabs/medicinepurchase/index.js',
                'assets/inilabs/medicinepurchase/table.js'
            )
        );

        $this->data['activetab']    = TRUE;
        $displayID                  = htmlentities(escapeString($this->uri->segment('3')));
        if($displayID == 2) {
            $this->data['medicinepurchases']  = $this->medicinepurchase_m->get_order_by_medicinepurchase(array('YEAR(create_date)' => date('Y'), 'MONTH(create_date)' => date('m')));
        } elseif($displayID == 3) {
            $this->data['medicinepurchases']  = $this->medicinepurchase_m->get_order_by_medicinepurchase(array('YEAR(create_date)' => date('Y')));
        } elseif($displayID == 4) {
            $this->data['medicinepurchases']  = $this->medicinepurchase_m->get_medicinepurchase();
        } else {
            $displayID = 1;
            $this->data['medicinepurchases']  = $this->medicinepurchase_m->get_order_by_medicinepurchase(array('DATE(create_date)' => date('Y-m-d')));
        }
        $this->data['displayID']          = $displayID;

        $this->data['medicinewarehouses'] = pluck($this->medicinewarehouse_m->get_select_medicinewarehouse(), 'obj', 'medicinewarehouseID');
        $this->data['medicinecategorys']  = $this->medicinecategory_m->get_select_medicinecategory();
        $this->data['medicineobj']        = json_encode(pluck($this->medicine_m->get_medicine(),'obj','medicineID'));
        $this->myData['myMedicineobj'] = $this->data['medicineobj'];

        $this->get_medicine_purchase_payment();
        $this->get_start_expire_date();
        
        $this->data['jsmanager'] = $this->myData;
        $this->data["subview"] = 'medicinepurchase/index';
        $this->load->view('_layout_main', $this->data);

	}

    public function edit()
    {
        $medicinepurchaseID     = htmlentities(escapeString($this->uri->segment('3')));
        $displayID              = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$medicinepurchaseID && (int)$displayID) {
            $this->data['medicinepurchase'] = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
            if(inicompute($this->data['medicinepurchase']) && ($this->data['medicinepurchase']->medicinepurchasestatus == 0) && ($this->data['medicinepurchase']->medicinepurchaserefund == 0)) {
                $this->data['activetab']          = FALSE;
                if($displayID == 2) {
                    $this->data['medicinepurchases']  = $this->medicinepurchase_m->get_order_by_medicinepurchase(array('YEAR(create_date)' => date('Y'), 'MONTH(create_date)' => date('m')));
                } elseif($displayID == 3) {
                    $this->data['medicinepurchases']  = $this->medicinepurchase_m->get_order_by_medicinepurchase(array('YEAR(create_date)' => date('Y')));
                } elseif($displayID == 4) {
                    $this->data['medicinepurchases']  = $this->medicinepurchase_m->get_medicinepurchase();
                } else {
                    $displayID = 1;
                    $this->data['medicinepurchases']  = $this->medicinepurchase_m->get_order_by_medicinepurchase(array('DATE(create_date)' => date('Y-m-d')));
                }
                $this->data['displayID']          = $displayID;
                
                $this->medicinepurchaseitem_m->order('medicinepurchaseitemID asc');
                $this->data['medicinepurchaseitems']    = $this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem(array('medicinepurchaseID'=> $medicinepurchaseID));
                $this->data['medicinewarehouses']       = pluck($this->medicinewarehouse_m->get_select_medicinewarehouse(), 'obj', 'medicinewarehouseID');
                $this->data['medicinecategorys']        = $this->medicinecategory_m->get_select_medicinecategory();
                $this->data['medicines']                = pluck($this->medicine_m->get_medicine(),'obj','medicineID');
                $this->data['medicineobj']              = json_encode($this->data['medicines']);
                
                $this->data['medicinepurchaseID']       = $medicinepurchaseID;
                $this->myData['myMedicineobj']            = $this->data['medicineobj'];
                $this->myData['medicinepurchaseID']     = $this->data['medicinepurchaseID'];
                $this->myData['displayID']              = $this->data['displayID'];
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                        'assets/inilabs/medicinepurchase/edit.js',
                        'assets/inilabs/medicinepurchase/table.js'
                    )
                );
                
                $this->get_medicine_purchase_payment();
                $this->get_start_expire_date();

                $this->myData['set_start_expire_date']  = $this->data['set_start_expire_date'];
                $this->myData['totalAmount']            = $this->data['medicinepurchase']->totalamount;

                $this->data['jsmanager'] = $this->myData;
                $this->data["subview"] = 'medicinepurchase/edit';
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

    public function savemedicinepurchase()
    {
        $retArray = [];
        $retArray['status']  = FALSE;
        $retArray['message'] = '';

        if(permissionChecker('medicinepurchase_add')) {
            if($_POST) {
                $update             = $this->input->post('update');
                $medicinepurchaseID = $this->input->post('medicinepurchaseID');

                $rules = $this->rules($this->input->post('payment_status'));
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray['message'] = validation_errors();
                } else {
                    $medicinepurchaseArray["year"]                              = date("Y");
                    $medicinepurchaseArray['medicinewarehouseID']               = $this->input->post('medicinewarehouseID');
                    $medicinepurchaseArray['medicinepurchasereferenceno']       = $this->input->post('purchase_referenceno');
                    $medicinepurchaseArray['medicinepurchasedate']              = date('Y-m-d', strtotime($this->input->post('medicinepurchasedate')));
                    $medicinepurchaseArray['medicinepurchasefile']              = $this->upload_data['medicinepurchasefile']['file_name'];
                    $medicinepurchaseArray['medicinepurchasefileoriginalname']  = $this->upload_data['medicinepurchasefile']['client_name'];
                    $medicinepurchaseArray['medicinepurchasedescription']       = $this->input->post('medicinepurchasedescription');
                    $medicinepurchaseArray["medicinepurchasestatus"]            = 0;
                    $medicinepurchaseArray["medicinepurchaserefund"]            = 0;
                    $medicinepurchaseArray["create_date"]                       = date("Y-m-d H:i:s");
                    $medicinepurchaseArray["modify_date"]                       = date("Y-m-d H:i:s");
                    $medicinepurchaseArray["create_userID"]                     = $this->session->userdata('loginuserID');
                    $medicinepurchaseArray["create_roleID"]                     = $this->session->userdata('roleID');

                    $medicineitems  = json_decode($this->input->post('medicineitems'));
                    $totalMedicineAmount = 0;
                    if(inicompute($medicineitems)) {
                        foreach ($medicineitems as $medicineitem) {
                            $totalMedicineAmount += ($medicineitem->quantity * $medicineitem->unit_price);
                        }
                    }
                    $medicinepurchaseArray["totalamount"]     = $totalMedicineAmount;

                    $medicinepurchaseitems = [];
                    if($update == 'update') {
                        $this->medicinepurchase_m->update_medicinepurchase($medicinepurchaseArray, $medicinepurchaseID);
                        $medicinepurchaseitems = pluck($this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem(array('medicinepurchaseID'=> $medicinepurchaseID)),'obj','medicinepurchaseitemID');
                    } else {
                        $this->medicinepurchase_m->insert_medicinepurchase($medicinepurchaseArray);
                        $medicinepurchaseID = $this->db->insert_id();
                    }

                    $i                          = 0;
                    $total_purchase_amount      = 0;
                    $insert_medicineitemArray   = [];
                    $update_medicineitemArray   = [];
                    $updateitemArray            = [];
                    if(inicompute($medicineitems)) {
                        foreach ($medicineitems as $medicineitem) {
                            if((int)$medicineitem->medicineitemid && isset($medicinepurchaseitems[$medicineitem->medicineitemid]) && inicompute($medicinepurchaseitems[$medicineitem->medicineitemid])) {
                                $medicinepurchaseitem = $medicinepurchaseitems[$medicineitem->medicineitemid];

                                $update_medicineitemArray[$i]["medicinepurchaseitemID"] = $medicinepurchaseitem->medicinepurchaseitemID;
                                $update_medicineitemArray[$i]["year"]               = date("Y");
                                $update_medicineitemArray[$i]["medicinepurchaseID"] = $medicinepurchaseID;
                                $update_medicineitemArray[$i]["medicineID"]         = $medicineitem->medicineid;
                                $update_medicineitemArray[$i]["batchID"]            = $medicineitem->batchID;
                                $update_medicineitemArray[$i]["expire_date"]        = date('Y-m-d H:i:s', strtotime($medicineitem->expire_date));
                                $update_medicineitemArray[$i]["unit_price"]         = $medicineitem->unit_price;
                                $update_medicineitemArray[$i]["quantity"]           = $medicineitem->quantity;
                                $update_medicineitemArray[$i]["subtotal"]           = $medicineitem->quantity * $medicineitem->unit_price;
                                $update_medicineitemArray[$i]["status"]             = 0;
                                if($medicineitem->quantity == $medicinepurchaseitem->salequantity) {
                                    $update_medicineitemArray[$i]["status"]         = 1;
                                }
                                $update_medicineitemArray[$i]["modify_date"]        = date("Y-m-d H:i:s");

                                $updateitemArray[] = $medicinepurchaseitem->medicinepurchaseitemID;
                            } else {
                                $insert_medicineitemArray[$i]["year"]               = date("Y");
                                $insert_medicineitemArray[$i]["medicinepurchaseID"] = $medicinepurchaseID;
                                $insert_medicineitemArray[$i]["medicineID"]         = $medicineitem->medicineid;
                                $insert_medicineitemArray[$i]["batchID"]            = $medicineitem->batchID;
                                $insert_medicineitemArray[$i]["expire_date"]        = date('Y-m-d H:i:s', strtotime($medicineitem->expire_date));
                                $insert_medicineitemArray[$i]["unit_price"]         = $medicineitem->unit_price;
                                $insert_medicineitemArray[$i]["quantity"]           = $medicineitem->quantity;
                                $insert_medicineitemArray[$i]["subtotal"]           = $medicineitem->quantity * $medicineitem->unit_price;
                                $insert_medicineitemArray[$i]["status"]             = 0;
                                $insert_medicineitemArray[$i]["create_date"]        = date("Y-m-d H:i:s");
                                $insert_medicineitemArray[$i]["modify_date"]        = date("Y-m-d H:i:s");
                                $insert_medicineitemArray[$i]["create_userID"]      = $this->session->userdata('loginuserID');
                                $insert_medicineitemArray[$i]["create_roleID"]      = $this->session->userdata('roleID');
                            }
                            $total_purchase_amount += ($medicineitem->quantity * $medicineitem->unit_price);
                            $i++;
                        }
                    }

                    $deleteitemArray = [];
                    if(inicompute($medicinepurchaseitems)) {
                        $medicinepurchaseitemArray = array_keys($medicinepurchaseitems);
                        $deleteitemArray = array_diff($medicinepurchaseitemArray, $updateitemArray);
                    }

                    if(inicompute($deleteitemArray)) {
                        $this->medicinepurchaseitem_m->delete_batch_medicinepurchaseitem_array($deleteitemArray);
                    }

                    if(inicompute($insert_medicineitemArray)) {
                        $this->medicinepurchaseitem_m->insert_batch_medicinepurchaseitem($insert_medicineitemArray);
                    }

                    if(inicompute($update_medicineitemArray)) {
                        $this->medicinepurchaseitem_m->update_batch_medicinepurchaseitem($update_medicineitemArray, 'medicinepurchaseitemID');
                    }
                    
                    $payment_status  = $this->input->post('payment_status');
                    if((int)$payment_status && $payment_status > 0) {
                        $array['medicinepurchaseID']                = $medicinepurchaseID;
                        $array['year']                              = date('Y');
                        $array['medicinepurchaseyear']              = date('Y');
                        $array['medicinepurchasepaiddate']          = date('Y-m-d', strtotime($this->input->post('medicinepurchasedate')));
                        $array['medicinepurchasepaidreferenceno']   = $this->input->post('purchasepaid_referenceno');
                        $array['medicinepurchasepaidamount']        = $this->input->post('payment_amount');
                        $array['medicinepurchasepaidpaymentmethod'] = $this->input->post('purchasepaid_payment_method');
                        $array['medicinepurchasepaidfile']          = '';
                        $array['medicinepurchasepaidoriginalname']  = '';
                        $array["create_date"]                       = date("Y-m-d H:i:s");
                        $array["modify_date"]                       = date("Y-m-d H:i:s");
                        $array["create_userID"]                     = $this->session->userdata('loginuserID');
                        $array["create_roleID"]                     = $this->session->userdata('roleID');

                        if($update == 'update') {
                            $medicinepurchase = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
                            $array['medicinepurchaseyear']          = inicompute($medicinepurchase) ? $medicinepurchase->year : date('Y');
                        }
                        $this->medicinepurchasepaid_m->insert_medicinepurchasepaid($array);

                        if($this->input->post('payment_amount') == 0) {
                            $medicinepurchasestatus = 0;
                        } elseif($this->input->post('payment_amount') == $total_purchase_amount) {
                            $medicinepurchasestatus = 2;
                        } else {
                            $medicinepurchasestatus = 1;
                        }
                        $this->medicinepurchase_m->update_medicinepurchase(array('medicinepurchasestatus'=> $medicinepurchasestatus), $medicinepurchaseID);
                    }

                    $retArray['status']  = TRUE;
                    $retArray['message'] = 'Success';
                    $this->session->set_flashdata('success','Success');
                } 
            } else {
                $retArray['message'] = $this->lang->line('medicinepurchase_method_not_allowed');
            } 
        } else {
            $retArray['message'] = $this->lang->line('medicinepurchase_permission_not_allowed');
        }
        echo json_encode($retArray);
    }

    public function view()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/medicinepurchase/view.js'
            ]
        ];

        $medicinepurchaseID = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$medicinepurchaseID && (int)$displayID) {
            $this->data['displayID']        = $displayID;
            $this->data['medicinepurchase'] = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
            if(inicompute($this->data['medicinepurchase'])) {
                $this->data['jsmanager']                = ['myMedicinepurchaseID' => $medicinepurchaseID];
                $this->medicinepurchaseitem_m->order('medicinepurchaseitemID asc');
                $this->data['medicinepurchaseItems']    = $this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem(array('medicinepurchaseID'=> $medicinepurchaseID));
                $this->data['medicinepurchasepaid']     = $this->medicinepurchasepaid_m->get_medicinepurchasepaid_sum('medicinepurchasepaidamount', array('medicinepurchaseID'=> $medicinepurchaseID));
                $this->data['medicinewarehouses']       = pluck($this->medicinewarehouse_m->get_select_medicinewarehouse(), 'obj', 'medicinewarehouseID');
                $this->data['medicines']                = pluck($this->medicine_m->get_medicine(),'obj','medicineID');
                $this->data['userName']                 = $this->user_m->get_select_user('userID, name', array('userID'=>$this->data['medicinepurchase']->create_userID), TRUE);

                $this->data["subview"] = 'medicinepurchase/view';
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
        $medicinepurchaseID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$medicinepurchaseID) {
            $this->data['medicinepurchase'] = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
            if(inicompute($this->data['medicinepurchase'])) {
                $this->medicinepurchaseitem_m->order('medicinepurchaseitemID asc');
                $this->data['medicinepurchaseItems']    = $this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem(array('medicinepurchaseID'=> $medicinepurchaseID));
                $this->data['medicinepurchasepaid']     = $this->medicinepurchasepaid_m->get_medicinepurchasepaid_sum('medicinepurchasepaidamount', array('medicinepurchaseID'=> $medicinepurchaseID));
                $this->data['medicinewarehouses']       = pluck($this->medicinewarehouse_m->get_select_medicinewarehouse(), 'obj', 'medicinewarehouseID');
                $this->data['medicines']                = pluck($this->medicine_m->get_medicine(),'obj','medicineID');
                $this->data['userName']                 = $this->user_m->get_select_user('userID, name', array('userID'=>$this->data['medicinepurchase']->create_userID), TRUE);
                $this->report->reportPDF(['stylesheet' => 'medicinepurchasemodule.css', 'data' => $this->data, 'viewpath' => 'medicinepurchase/printpreview']);
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
        if(permissionChecker('medicinepurchase_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $medicinepurchaseID = $this->input->post('medicinepurchaseID');
                    if((int)$medicinepurchaseID) {
                        $this->data['medicinepurchase'] = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
                        if(inicompute($this->data['medicinepurchase'])) {
                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');

                            $this->medicinepurchaseitem_m->order('medicinepurchaseitemID asc');
                            $this->data['medicinepurchaseItems']  = $this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem(array('medicinepurchaseID'=> $medicinepurchaseID));
                            $this->data['medicinepurchasepaid']   = $this->medicinepurchasepaid_m->get_medicinepurchasepaid_sum('medicinepurchasepaidamount', array('medicinepurchaseID'=> $medicinepurchaseID));
                            $this->data['medicinewarehouses'] = pluck($this->medicinewarehouse_m->get_select_medicinewarehouse(), 'obj', 'medicinewarehouseID');
                            $this->data['medicines']          = pluck($this->medicine_m->get_medicine(),'obj','medicineID');
                            $this->data['userName'] = $this->user_m->get_select_user('userID, name', array('userID'=>$this->data['medicinepurchase']->create_userID), TRUE);
                            $this->report->reportSendToMail(['stylesheet' => 'medicinepurchasemodule.css', 'data' => $this->data, 'viewpath' => 'medicinepurchase/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {
                            $retArray['message'] = $this->lang->line('medicinepurchase_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('medicinepurchase_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('medicinepurchase_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('medicinepurchase_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("medicinepurchase_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("medicinepurchase_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("medicinepurchase_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'medicinepurchaseID',
                'label' => $this->lang->line("medicinepurchase_medicinepurchase"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function delete()
    {
        $medicinepurchaseID = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$medicinepurchaseID && (int)$displayID) {
            $medicinepurchase = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=>$medicinepurchaseID));
            if(inicompute($medicinepurchase) && ($medicinepurchase->medicinepurchasestatus == 0) && ($medicinepurchase->medicinepurchaserefund == 0)) {
                if(($medicinepurchase->medicinepurchasefile != '') && (config_item('demo') == FALSE)) {
                    if(file_exists(FCPATH.'uploads/files/'.$medicinepurchase->medicinepurchasefile)) {
                        unlink(FCPATH.'uploads/files/'.$medicinepurchase->medicinepurchasefile);
                    }
                }
                $this->medicinepurchase_m->delete_medicinepurchase($medicinepurchaseID);
                $this->medicinepurchaseitem_m->delete_batch_medicinepurchaseitem(array('medicinepurchaseID'=> $medicinepurchaseID));
                $this->session->set_flashdata('success','Success');
                redirect(site_url('medicinepurchase/index/'.$displayID));
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
        if(permissionChecker('medicinepurchase_edit') && permissionChecker('medicinepurchase_delete'))
        $medicinepurchaseID = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$medicinepurchaseID && (int)$displayID) {
            $medicinepurchase = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=>$medicinepurchaseID));
            if(inicompute($medicinepurchase) && ($medicinepurchase->medicinepurchasestatus != 0) && ($medicinepurchase->medicinepurchaserefund != 1)) {
                $medicinepurchaseitems = $this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem(array('medicinepurchaseID'=> $medicinepurchaseID));
                $medicineitemArray     = [];
                if(inicompute($medicinepurchaseitems)) {
                    $i = 0;
                    foreach ($medicinepurchaseitems as $medicinepurchaseitem) {
                        $medicineitemArray[$i]["medicinepurchaseitemID"]    = $medicinepurchaseitem->medicinepurchaseitemID;
                        $medicineitemArray[$i]["status"]                    = 1;
                        $medicineitemArray[$i]["modify_date"]               = date("Y-m-d H:i:s");
                        $i++;
                    }
                }

                if(inicompute($medicineitemArray)) {
                    $this->medicinepurchaseitem_m->update_batch_medicinepurchaseitem($medicineitemArray, 'medicinepurchaseitemID');
                }
                $this->medicinepurchase_m->update_medicinepurchase(array('medicinepurchaserefund'=>1), $medicinepurchaseID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('medicinepurchase/index/'.$displayID));
            } else {
                redirect(site_url('medicinepurchase/index'));
            }
        } else {
            redirect(site_url('medicinepurchase/index'));
        }
    }

    public function download()
    {
        if(permissionChecker('medicinepurchase_view')) {
            $medicinepurchaseID = htmlentities(escapeString($this->uri->segment('3')));
            if((int)$medicinepurchaseID) {
                $medicinepurchase = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
                if(inicompute($medicinepurchase) && $medicinepurchase->medicinepurchasefile != '') {
                    $file = realpath('uploads/files/'.$medicinepurchase->medicinepurchasefile);
                    if (file_exists($file)) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="'.basename($medicinepurchase->medicinepurchasefileoriginalname).'"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                        exit;
                    } else {
                        redirect(site_url('medicinepurchase/index'));
                    }
                } else {
                    redirect(site_url('medicinepurchase/index'));
                }
            } else {
                redirect(site_url('medicinepurchase/index'));
            }
        } else {
            redirect(site_url('medicinepurchase/index'));
        }
    }

    public function savemedicinepurchasepayment()
    {
        $retArray = [];
        $retArray['status'] = FALSE;
        if(permissionChecker('medicinepurchase_add')) {
            if($_POST) {
                $medicinepurchaseID = $this->input->post('medicinepurchaseID');
                if((int)$medicinepurchaseID) {
                    $medicinepurchase = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
                    if(inicompute($medicinepurchase) && ($medicinepurchase->medicinepurchasestatus !=2) && ($medicinepurchase->medicinepurchaserefund == 0)) {
                        $rules = $this->rules_medicine_payment();
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $retArray = $this->form_validation->error_array();
                            $retArray['status'] = FALSE;
                        } else {
                            $array['medicinepurchaseID']                = $this->input->post('medicinepurchaseID');
                            $array['year']                              = date('Y');
                            $array['medicinepurchaseyear']              = $medicinepurchase->year;
                            $array['medicinepurchasepaiddate']          = date('Y-m-d', strtotime($this->input->post('payment_date')));
                            $array['medicinepurchasepaidreferenceno']   = $this->input->post('reference_no');
                            $array['medicinepurchasepaidamount']        = $this->input->post('payment_amount');
                            $array['medicinepurchasepaidpaymentmethod'] = $this->input->post('payment_method');
                            $array['medicinepurchasepaidfile']          = $this->upload_data['payment_file']['file_name'];
                            $array['medicinepurchasepaidoriginalname']  = $this->upload_data['payment_file']['client_name'];
                            $array["create_date"]                       = date("Y-m-d H:i:s");
                            $array["modify_date"]                       = date("Y-m-d H:i:s");
                            $array["create_userID"]                     = $this->session->userdata('loginuserID');
                            $array["create_roleID"]                     = $this->session->userdata('roleID');
                            $this->medicinepurchasepaid_m->insert_medicinepurchasepaid($array);
                            $medicinepurchasepaid = $this->medicinepurchasepaid_m->get_medicinepurchasepaid_sum('medicinepurchasepaidamount', array('medicinepurchaseID'=> $medicinepurchaseID));

                            $medicinepurchaseArray = [];
                            if($medicinepurchase->totalamount == $medicinepurchasepaid->medicinepurchasepaidamount) {
                                $medicinepurchaseArray['medicinepurchasestatus'] = 2;
                            } else {
                                $medicinepurchaseArray['medicinepurchasestatus'] = 1;
                            }
                            $this->medicinepurchase_m->update_medicinepurchase($medicinepurchaseArray, $medicinepurchaseID);
                            $this->session->set_flashdata('success','Success');
                            $retArray['message'] = 'Success';
                            $retArray['status']  = TRUE;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('medicinepurchase_data_not_found');
                    }
                } else {
                    $retArray['message'] = $this->lang->line('medicinepurchase_data_not_found');
                }
            } else {
                $retArray['message'] = $this->lang->line('medicinepurchase_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('medicinepurchase_permission_not_allowed');
        }
        echo json_encode($retArray);
    }

    public function getpurchasepaidinfo()
    {
        $retArray['status'] = TRUE;
        if(permissionChecker('medicinepurchase_view')) {
            $medicinepurchaseID = $this->input->post('medicinepurchaseID');
            if((int)$medicinepurchaseID) {
                $this->data['medicinepurchase']     = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
                $this->data['medicinepurchasepaids'] = $this->medicinepurchasepaid_m->get_order_by_medicinepurchasepaid(array('medicinepurchaseID'=> $medicinepurchaseID));
                $retArray['view']  = $this->load->view('medicinepurchase/purchasepaidinfoTable', $this->data, TRUE);
            }
        }
        echo json_encode($retArray);
    }

    public function paymentdownload()
    {
        if(permissionChecker('medicinepurchase_view')) {
            $medicinepurchasepaidID = htmlentities(escapeString($this->uri->segment('3')));
            if((int)$medicinepurchasepaidID) {
                $medicinepurchasepaid = $this->medicinepurchasepaid_m->get_single_medicinepurchasepaid(array('medicinepurchasepaidID'=>$medicinepurchasepaidID));
                if(inicompute($medicinepurchasepaid) && $medicinepurchasepaid->medicinepurchasepaidfile != '') {
                    $file = realpath('uploads/files/'.$medicinepurchasepaid->medicinepurchasepaidfile);
                    if (file_exists($file)) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="'.basename($medicinepurchasepaid->medicinepurchasepaidoriginalname).'"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                        exit;
                    } else {
                        redirect(site_url('medicinepurchase/index'));
                    }
                } else {
                    redirect(site_url('medicinepurchase/index'));
                }
            } else {
                redirect(site_url('medicinepurchase/index'));
            }
        } else {
            redirect(site_url('medicinepurchase/index'));
        }
    }

    public function paymentdelete()
    {
        if(permissionChecker('medicinepurchase_delete')) {
            $medicinepurchasepaidID = htmlentities(escapeString($this->uri->segment('3')));
            if((int)$medicinepurchasepaidID) {
                $medicinepurchasepaid = $this->medicinepurchasepaid_m->get_single_medicinepurchasepaid(array('medicinepurchasepaidID'=>$medicinepurchasepaidID));
                if(inicompute($medicinepurchasepaid)) {
                    if(($medicinepurchasepaid->medicinepurchasepaidfile != '') && (config_item('demo') == FALSE)) {
                        if(file_exists(FCPATH.'uploads/files/'.$medicinepurchasepaid->medicinepurchasepaidfile)) {
                            unlink(FCPATH.'uploads/files/'.$medicinepurchasepaid->medicinepurchasepaidfile);
                        }
                    }

                    $medicinepurchaseID   = $medicinepurchasepaid->medicinepurchaseID;
                    $medicinepurchase     = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=>$medicinepurchaseID));
                    if(inicompute($medicinepurchase) && ($medicinepurchase->medicinepurchaserefund == 0)) {
                        $this->medicinepurchasepaid_m->delete_medicinepurchasepaid($medicinepurchasepaidID);
                        $medicinepurchasepaid = $this->medicinepurchasepaid_m->get_medicinepurchasepaid_sum('medicinepurchasepaidamount', array('medicinepurchaseID'=> $medicinepurchaseID));

                        $medicinepurchaseArray = [];
                        if($medicinepurchase->totalamount == $medicinepurchasepaid->medicinepurchasepaidamount) {
                            $medicinepurchaseArray['medicinepurchasestatus'] = 2;
                        } elseif(($medicinepurchasepaid->medicinepurchasepaidamount < $medicinepurchase->totalamount) && ($medicinepurchasepaid->medicinepurchasepaidamount > 0)) {
                            $medicinepurchaseArray['medicinepurchasestatus'] = 1;
                        } else {
                            $medicinepurchaseArray['medicinepurchasestatus'] = 0;
                        }
                        $this->medicinepurchase_m->update_medicinepurchase($medicinepurchaseArray, $medicinepurchaseID);
                        $this->session->set_flashdata('success','Success');
                    }
                }
            }
        }
        redirect(site_url('medicinepurchase/index'));
    }

    private function get_medicine_purchase_payment()
    {
        $medicinepurchasepaids = $this->medicinepurchasepaid_m->get_select_medicinepurchasepaid();
        $retArray = [];
        if(inicompute($medicinepurchasepaids)) {
            foreach ($medicinepurchasepaids as $medicinepurchasepaid) {
                if(isset($retArray[$medicinepurchasepaid->medicinepurchaseID])) {
                    $retArray[$medicinepurchasepaid->medicinepurchaseID] += $medicinepurchasepaid->medicinepurchasepaidamount;
                } else {
                    $retArray[$medicinepurchasepaid->medicinepurchaseID] = $medicinepurchasepaid->medicinepurchasepaidamount;
                }
            }
        }

        $this->data['medicinepurchasepaids'] = $retArray;
    }   

    public function get_medicine()
    {
        echo "<option value='0'>".'— '.$this->lang->line("medicinepurchase_please_select").' —'."</option>";
        if($_POST) {
            $medicinecategoryID = $this->input->post('medicinecategoryID');
            if((int)$medicinecategoryID) {
                $medicines = $this->medicine_m->get_select_medicine('medicineID, name', array('medicinecategoryID' => $medicinecategoryID));
                if(inicompute($medicines)) {
                    foreach ($medicines as $medicine) {
                        echo "<option value='".$medicine->medicineID."'>".$medicine->name."</option>";
                    }
                }
            }
        }
    }

    public function getpurchaseinfo()
    {
        $retArray['status'] = FALSE;
        if(permissionChecker('medicinepurchase_add')) {
            $medicinepurchaseID = $this->input->post('medicinepurchaseID');
            if((int)$medicinepurchaseID) {
                $medicinepurchase = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
                if(inicompute($medicinepurchase)) {
                    $medicinepurchasepaid  = $this->medicinepurchasepaid_m->get_medicinepurchasepaid_sum('medicinepurchasepaidamount', array('medicinepurchaseID'=> $medicinepurchaseID));
                    $retArray['dueamount'] = ($medicinepurchase->totalamount - $medicinepurchasepaid->medicinepurchasepaidamount);
                    $retArray['status']    = TRUE;
                }
            }
        }
        echo json_encode($retArray);
    }

    public function checkmedcinebatch()
    {
        $retArray = [];
        $retArray['status'] = FALSE;
        if(permissionChecker('medicinepurchase_add') || permissionChecker('medicinepurchase_edit')) {
            if($_POST) {
                $batchs     = json_decode($this->input->post('data'));
                $batchArray = [];
                if(inicompute($batchs)) {
                    foreach ($batchs as $batch) {
                        if((int)$batch->medicineitemid) {
                            $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem(array('batchID'=> trim($batch->batchvalue),'medicineID'=>$batch->medicineid, 'medicinepurchaseitemID !='=> $batch->medicineitemid));
                            if(inicompute($medicinepurchaseitem)) {
                                $batchArray[$batch->batchrandID] = $batch->batchvalue;
                            }
                        } else {
                            $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem(array('batchID'=> trim($batch->batchvalue),'medicineID'=>$batch->medicineid));
                            if(inicompute($medicinepurchaseitem)) {
                                $batchArray[$batch->batchrandID] = $batch->batchvalue;
                            }
                        }
                    }
                }

                if(inicompute($batchArray)) {
                    $retArray['status'] = TRUE;
                    $retArray['data']   = $batchArray;
                }
            }
        }
        echo json_encode($retArray);
    }

    public function check_payment_amount()
    {
        $medicinepurchaseID = $this->input->post('medicinepurchaseID');
        if((int)$medicinepurchaseID) {
            $medicinepurchase = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
            if(inicompute($medicinepurchase)) {
                $medicinepurchasepaid  = $this->medicinepurchasepaid_m->get_medicinepurchasepaid_sum('medicinepurchasepaidamount', array('medicinepurchaseID'=> $medicinepurchaseID));
                $dueamount                  = ($medicinepurchase->totalamount - $medicinepurchasepaid->medicinepurchasepaidamount);
                $medicinepurchasepaidamount = $this->input->post('payment_amount');
                if($medicinepurchasepaidamount > $dueamount) {
                    $this->form_validation->set_message("check_payment_amount", "the payment amount greater than due amount.");
                    return FALSE;
                }
                return TRUE;
            } else {
                $this->form_validation->set_message("check_payment_amount", "Invalid Data");
                return FALSE;
            }
        } else {
            $this->form_validation->set_message("check_payment_amount", "Invalid Data");
            return FALSE;
        }
    }

    public function unique_medicinepurchasefile() 
    {
        $new_file = '';
        if($_FILES["medicinepurchasefile"]['name'] != "") {
            $file_name = $_FILES["medicinepurchasefile"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random.(strtotime(date('Y-m-d H:i:s'))). config_item("encryption_key"));
            $file_name_rename = 'medicinepurchase_'.$makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/files";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '5120';
                $config['max_width'] = '10000';
                $config['max_height'] = '10000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("medicinepurchasefile")) {
                    $this->form_validation->set_message("unique_medicinepurchasefile", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['medicinepurchasefile'] =  $this->upload->data();
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("unique_medicinepurchasefile", "Invalid File");
                return FALSE;
            }
        } else {
            $medicinepurchaseID = $this->input->post('medicinepurchaseID');
            if ((int)$medicinepurchaseID) {
                $medicinepurchase = $this->medicinepurchase_m->get_single_medicinepurchase(array('medicinepurchaseID'=> $medicinepurchaseID));
                if (inicompute($medicinepurchase)) {
                    $this->upload_data['medicinepurchasefile']['file_name']   =  $medicinepurchase->medicinepurchasefile;
                    $this->upload_data['medicinepurchasefile']['client_name'] =  $medicinepurchase->medicinepurchasefileoriginalname;
                    return TRUE;
                } else{
                    $this->upload_data['medicinepurchasefile']['file_name']   =  $new_file;
                    $this->upload_data['medicinepurchasefile']['client_name'] =  $new_file;
                    return TRUE;
                }
            } else{
                $this->upload_data['medicinepurchasefile']['file_name']   =  $new_file;
                $this->upload_data['medicinepurchasefile']['client_name'] =  $new_file;
                return TRUE;
            }
        }
    }

    public function unique_payment_file() 
    {
        $new_file = '';
        if($_FILES["payment_file"]['name'] != "") {
            $file_name = $_FILES["payment_file"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random.(strtotime(date('Y-m-d H:i:s'))). config_item("encryption_key"));
            $file_name_rename = 'medicinepurchasepaid_'.$makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/files";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '5120';
                $config['max_width'] = '10000';
                $config['max_height'] = '10000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("payment_file")) {
                    $this->form_validation->set_message("unique_file", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['payment_file'] =  $this->upload->data();
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("unique_file", "Invalid File");
                return FALSE;
            }
        } else {
            $this->upload_data['payment_file']['file_name']   =  $new_file;
            $this->upload_data['payment_file']['client_name'] =  $new_file;
            return TRUE;
        }
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function valid_date($date) 
    {
        if($date) {
            if(strlen($date) < 10) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                if(inicompute($arr) == 3) {
                    $dd = $arr[0];
                    $mm = $arr[1];
                    $yyyy = $arr[2];
                    if(checkdate($mm, $dd, $yyyy)) {
                        return TRUE;
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                        return FALSE;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                    return FALSE;
                }
            }
        } else {
            return TRUE;
        }
    }

    private function get_start_expire_date()
    {
        $medicine_expire_limit_day = isset($this->data['generalsettings']->medicine_expire_limit_day) ? $this->data['generalsettings']->medicine_expire_limit_day : 0;
        $get_start_expire_date     = strtotime("+$medicine_expire_limit_day days");
        $this->data['set_start_expire_date'] = date('d-m-Y', $get_start_expire_date);
        $this->myData['my_set_start_expire_date'] = $this->data['set_start_expire_date'];
    }

    public function check_validation()
    {
        $retArray           = [];
        $retArray['data']   = '';
        $retArray['message']= '';
        $retArray['status'] = FALSE;
        if($_POST) {
            $medicinepurchaseID = $this->input->post('medicinepurchaseID');
            $medicineitems      = json_decode($this->input->post('medicineitems'));

            if((int)$medicinepurchaseID) {
                $medicinepurchaseitems = pluck($this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem(array('medicinepurchaseID'=> $medicinepurchaseID)),'obj','medicinepurchaseitemID');
                if(inicompute($medicineitems)) {
                    $medicineitemsArray = [];
                    foreach ($medicineitems as $medicineitem) {
                        if((int)$medicineitem->medicineitemid && isset($medicinepurchaseitems[$medicineitem->medicineitemid]) && inicompute($medicinepurchaseitems[$medicineitem->medicineitemid])) {
                            $medicinepurchaseitem = $medicinepurchaseitems[$medicineitem->medicineitemid];

                            if($medicinepurchaseitem->salequantity > 0) {
                                if($medicineitem->batchID != $medicinepurchaseitem->batchID) {
                                    $medicineitemsArray[$medicineitem->randid]['batchID']    = TRUE;
                                }
                                if($medicineitem->unit_price != $medicinepurchaseitem->unit_price) {
                                    $medicineitemsArray[$medicineitem->randid]['unitprice']    = TRUE;
                                }
                                if($medicineitem->quantity < $medicinepurchaseitem->salequantity) {
                                    $medicineitemsArray[$medicineitem->randid]['quantity']    = TRUE;
                                }

                                $medicine_expire_limit_day = isset($this->data['generalsettings']->medicine_expire_limit_day) ? $this->data['generalsettings']->medicine_expire_limit_day : 0;
                                $setting_expire_date       = strtotime("+$medicine_expire_limit_day days");
                                $medicineitem_expire_date  = strtotime($medicineitem->expire_date);

                                if($medicineitem_expire_date < $setting_expire_date) {
                                    $medicineitemsArray[$medicineitem->randid]['expiredate']    = TRUE;
                                }

                                if(inicompute($medicineitemsArray)) {
                                    $retArray['status']  = TRUE;
                                    $retArray['data']    = $medicineitemsArray;
                                }
                            }
                        }
                    }
                 } else {
                    $retArray['status']  = TRUE;
                    $retArray['data']    = '';
                    $retArray['message'] = $this->lang->line('medicinesale_data_not_found');
                }
            } else {
                $retArray['status']  = TRUE;
                $retArray['data']    = '';
                $retArray['message'] = $this->lang->line('medicinesale_method_not_allowed');
            }
        } else {
            $retArray['status']  = TRUE;
            $retArray['data']    = '';
            $retArray['message'] = $this->lang->line('medicinesale_permission_not_allowed');
        }
        echo json_encode($retArray);
    }
}
