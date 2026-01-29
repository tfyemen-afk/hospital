<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class makepayment extends Admin_Controller
{
     public function __construct()
     {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('user_m');
        $this->load->model('salaryoption_m');
        $this->load->model('makepayment_m');
        $this->load->model('salarytemplate_m');
        $this->load->model('hourlytemplate_m');
        $this->load->model('managesalary_m');
        $this->load->model('designation_m');

        $this->load->library('report');

        $language = $this->session->userdata('lang');;
        $this->lang->load('makepayment', $language);
    }
    
    protected function rules($salary)
    {
        $rules = array(
            array(
                'field' => 'gross_salary',
                'label' => $this->lang->line("makepayment_gross_salary"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'total_deduction',
                'label' => $this->lang->line("makepayment_total_deduction"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'net_salary',
                'label' => $this->lang->line("makepayment_net_salary"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'month',
                'label' => $this->lang->line("makepayment_month"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'payment_amount',
                'label' => $this->lang->line("makepayment_payment_amount"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'payment_method',
                'label' => $this->lang->line("makepayment_payment_method"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'comments',
                'label' => $this->lang->line("makepayment_comments"),
                'rules' => 'trim'
            )
        );

        if($salary == 2) {
            $rules[] = array(
                'field' => 'total_hours',
                'label' => $this->lang->line("makepayment_total_hours"),
                'rules' => 'trim|required|numeric'
            );
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
                'assets/inilabs/makepayment/index.js',
            )
        );

        $roleID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$roleID) {
            $this->data['users'] = $this->user_m->get_select_user('userID, photo, name, designationID, email', array('roleID' => $roleID, 'roleID !=' => 3, 'status' => 1, 'delete_at' => 0));
        } else {
            $this->data['users'] = $this->user_m->get_select_user('userID, photo, name, designationID, email', array('roleID !=' => 3, 'status' => 1, 'delete_at' => 0));
        }

        $this->data['designations']  = pluck($this->designation_m->get_select_designation('designationID, designation'), 'designation', 'designationID');
        $this->data['managesalarys'] = pluck($this->managesalary_m->get_managesalary(),'obj','userID');
        $this->data['setroleID']     = $roleID ? $roleID : '';
        $this->data['roles']         = $this->role_m->get_role();
        $this->data["subview"]       = 'makepayment/index';
        $this->load->view('_layout_main', $this->data);
	}

    public function add()
    {
        if(permissionChecker('makepayment')) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
                ),
                'js' => array(
                    'assets/select2/select2.js',
                    'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                    'assets/inilabs/makepayment/index.js',
                    'assets/inilabs/makepayment/add.js',
                )
            );

            $error = FALSE;
            $this->data['grosssalary'] = 0;
            $this->data['totaldeduction'] = 0;
            $this->data['netsalary'] = 0;
            $this->data['jsmanager'] = ['netsalary' => 0];

            $userID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$userID) {
                $user = $this->user_m->get_single_user(array('userID' => $userID,  'status' => 1, 'delete_at' => 0));
                if (inicompute($user)) {
                    $managesalary = $this->managesalary_m->get_single_managesalary(array('userID' => $userID));
                    if (inicompute($managesalary)) {
                        $this->data["user"] = $user;
                        $this->data['managesalary'] = $managesalary;
                        $this->data["designations"] = pluck($this->designation_m->get_designation(), 'designation', 'designationID');

                        $this->makepayment_m->order('makepaymentID desc');
                        $this->data["makepayments"] = $this->makepayment_m->get_order_by_makepayment(array('userID' => $userID));
                        if ($managesalary->salary == 1) {
                            $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $managesalary->template));
                            if (inicompute($this->data['salarytemplate'])) {
                                $this->salaryoption_m->order("salaryoptionID asc");
                                $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $managesalary->template));

                                $grosssalary = 0;
                                $totaldeduction = 0;
                                $netsalary = $this->data['salarytemplate']->basic_salary;
                                $grosssalarylist = [];
                                $totaldeductionlist = [];
                                if (inicompute($this->data['salaryoptions'])) {
                                    foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                        if ($salaryOption->option_type == 1) {
                                            $netsalary += $salaryOption->label_amount;
                                            $grosssalary += $salaryOption->label_amount;
                                            $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                        } elseif ($salaryOption->option_type == 2) {
                                            $netsalary -= $salaryOption->label_amount;
                                            $totaldeduction += $salaryOption->label_amount;
                                            $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                        }
                                    }
                                }

                                $this->data['grosssalary'] = $grosssalary;
                                $this->data['totaldeduction'] = $totaldeduction;
                                $this->data['netsalary'] = $netsalary;
                                $this->data['jsmanager'] = ['netsalary' => $this->data['netsalary']];
                            } else {
                                $error = TRUE;
                            }
                        } elseif ($managesalary->salary == 2) {
                            $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID' => $managesalary->template));
                            if (inicompute($this->data['hourly_salary'])) {
                                $this->data['grosssalary'] = 0;
                                $this->data['totaldeduction'] = 0;
                                $this->data['netsalary'] = $this->data['hourly_salary']->hourly_rate;
                            } else {
                                $error = TRUE;
                            }
                        }

                        if ($error == FALSE) {
                            if ($_POST) {
                                $rules = $this->rules($managesalary->salary);
                                $this->form_validation->set_rules($rules);
                                if ($this->form_validation->run() == FALSE) {
                                    $this->data["subview"] = "makepayment/add";
                                    $this->load->view('_layout_main', $this->data);
                                } else {
                                    $array = array(
                                        "month" => $this->input->post("month"),
                                        "gross_salary" => $this->data['grosssalary'],
                                        "total_deduction" => $this->data['totaldeduction'],
                                        "net_salary" => $this->data['netsalary'],
                                        'payment_amount' => $this->input->post('payment_amount'),
                                        "payment_method" => $this->input->post("payment_method"),
                                        "comments" => $this->input->post("comments"),
                                        'templateID' => $managesalary->template,
                                        'salaryID' => $managesalary->salary,
                                        'roleID' => $user->roleID,
                                        'userID' => $userID,
                                        'year' => date('Y'),
                                        'create_date' => date("Y-m-d H:i:s"),
                                        'modify_date' => date("Y-m-d H:i:s"),
                                        'create_userID' => $this->session->userdata('loginuserID'),
                                        'create_roleID' => $this->session->userdata('roleID'),
                                    );

                                    if ($managesalary->salary == 2) {
                                        $array['total_hours'] = $this->input->post('total_hours');
                                    }

                                    $this->makepayment_m->insert_makepayment($array);
                                    $this->session->set_flashdata('success', 'Success');
                                    redirect(site_url("makepayment/add/$userID"));
                                }
                            } else {
                                $this->data["subview"] = "makepayment/add";
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
                'assets/inilabs/makepayment/view.js'
            ]
        ];

        if(permissionChecker('makepayment')) {
            $makepaymentID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$makepaymentID) {
                $makepayment = $this->makepayment_m->get_single_makepayment(array('makepaymentID' => $makepaymentID));
                if (inicompute($makepayment)) {
                    $this->data['jsmanager'] = ['myMakepaymentID' => $makepaymentID];
                    $this->data['user'] = $this->user_m->get_single_user(array('userID' => $makepayment->userID, 'status' => 1, 'delete_at' => 0));
                    $this->data['makepayment'] = $makepayment;
                    if (inicompute($this->data['user'])) {
                        $this->data['designation'] = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                        if ($makepayment->salaryID == 1) {
                            $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $makepayment->templateID));
                            if (inicompute($this->data['salarytemplate'])) {
                                $this->salaryoption_m->order("salaryoptionID asc");
                                $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $makepayment->templateID));

                                $grosssalary = 0;
                                $totaldeduction = 0;
                                $netsalary = $this->data['salarytemplate']->basic_salary;
                                $grosssalarylist = [];
                                $totaldeductionlist = [];
                                if (inicompute($this->data['salaryoptions'])) {
                                    foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                        if ($salaryOption->option_type == 1) {
                                            $netsalary += $salaryOption->label_amount;
                                            $grosssalary += $salaryOption->label_amount;
                                            $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                        } elseif ($salaryOption->option_type == 2) {
                                            $netsalary -= $salaryOption->label_amount;
                                            $totaldeduction += $salaryOption->label_amount;
                                            $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                        }
                                    }
                                }

                                $this->data['grosssalary'] = $grosssalary;
                                $this->data['totaldeduction'] = $totaldeduction;
                                $this->data['netsalary'] = $netsalary;
                                $this->data["subview"] = "makepayment/view";
                                $this->load->view('_layout_main', $this->data);
                            } else {
                                $this->data["subview"] = "_not_found";
                                $this->load->view('_layout_main', $this->data);
                            }
                        } elseif ($makepayment->salaryID == 2) {
                            $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID' => $makepayment->templateID));
                            if (inicompute($this->data['hourly_salary'])) {
                                $this->data['grosssalary'] = 0;
                                $this->data['totaldeduction'] = 0;
                                $this->data['netsalary'] = $this->data['hourly_salary']->hourly_rate;
                                $this->data["subview"] = "makepayment/view";
                                $this->load->view('_layout_main', $this->data);
                            } else {
                                $this->data["subview"] = "_not_found";
                                $this->load->view('_layout_main', $this->data);
                            }
                        } else {
                            $this->data["subview"] = "_not_found";
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
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function printpreview()
    {
        $makepaymentID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$makepaymentID) {
            $makepayment = $this->makepayment_m->get_single_makepayment(array('makepaymentID' => $makepaymentID));
            if(inicompute($makepayment)) {
                $this->data['user']         = $this->user_m->get_single_user(array('userID' => $makepayment->userID, 'status' => 1, 'delete_at' => 0));
                $this->data['makepayment']  = $makepayment;
                if(inicompute($this->data['user'])) {
                    $this->data['designation'] = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                    if($makepayment->salaryID == 1) {
                        $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $makepayment->templateID));
                        if(inicompute($this->data['salarytemplate'])) {
                            $this->salaryoption_m->order("salaryoptionID asc");
                            $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $makepayment->templateID));

                            $grosssalary        = 0;
                            $totaldeduction     = 0;
                            $netsalary          = $this->data['salarytemplate']->basic_salary;
                            $grosssalarylist    = [];
                            $totaldeductionlist = [];
                            if(inicompute($this->data['salaryoptions'])) {
                                foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                    if($salaryOption->option_type == 1) {
                                        $netsalary += $salaryOption->label_amount;
                                        $grosssalary += $salaryOption->label_amount;
                                        $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                    } elseif($salaryOption->option_type == 2) {
                                        $netsalary -= $salaryOption->label_amount;
                                        $totaldeduction += $salaryOption->label_amount;
                                        $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                    }
                                }
                            }

                            $this->data['grosssalary']    = $grosssalary;
                            $this->data['totaldeduction'] = $totaldeduction;
                            $this->data['netsalary']      = $netsalary;
                            
                            $this->report->reportPDF(['stylesheet' => 'makepaymentmodule.css', 'data' => $this->data, 'viewpath' => 'makepayment/printpreview']);
                        } else {
                            $this->data["subview"] = "_not_found";
                            $this->load->view('_layout_main', $this->data);
                        }
                    } elseif($makepayment->salaryID == 2) {
                        $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID'=> $makepayment->templateID));
                        if(inicompute($this->data['hourly_salary'])) {
                            $this->data['grosssalary']    = 0;
                            $this->data['totaldeduction'] = 0;
                            $this->data['netsalary']      = $this->data['hourly_salary']->hourly_rate;
                            
                            $this->report->reportPDF(['stylesheet' => 'makepaymentmodule.css', 'data' => $this->data, 'viewpath' => 'makepayment/printpreview']);
                        } else {
                            $this->data["subview"] = "_not_found";
                            $this->load->view('_layout_main', $this->data);
                        }
                    } else {
                        $this->data["subview"] = "_not_found";
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
        if(permissionChecker('managesalary_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $makepaymentID = $this->input->post('makepaymentID');
                    if((int)$makepaymentID) {
                        $makepayment = $this->makepayment_m->get_single_makepayment(array('makepaymentID' => $makepaymentID));
                        if(inicompute($makepayment)) {
                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');
                            
                            $this->data['user']         = $this->user_m->get_single_user(array('userID' => $makepayment->userID, 'status' => 1, 'delete_at' => 0));
                            $this->data['makepayment']  = $makepayment;
                            if(inicompute($this->data['user'])) {
                                $this->data['designation'] = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                                if($makepayment->salaryID == 1) {
                                    $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $makepayment->templateID));
                                    if(inicompute($this->data['salarytemplate'])) {
                                        $this->salaryoption_m->order("salaryoptionID asc");
                                        $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $makepayment->templateID));

                                        $grosssalary        = 0;
                                        $totaldeduction     = 0;
                                        $netsalary          = $this->data['salarytemplate']->basic_salary;
                                        $grosssalarylist    = [];
                                        $totaldeductionlist = [];
                                        if(inicompute($this->data['salaryoptions'])) {
                                            foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                                if($salaryOption->option_type == 1) {
                                                    $netsalary += $salaryOption->label_amount;
                                                    $grosssalary += $salaryOption->label_amount;
                                                    $grosssalarylist[$salaryOption->label_name] = $salaryOption->label_amount;
                                                } elseif($salaryOption->option_type == 2) {
                                                    $netsalary -= $salaryOption->label_amount;
                                                    $totaldeduction += $salaryOption->label_amount;
                                                    $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                                                }
                                            }
                                        }

                                        $this->data['grosssalary']    = $grosssalary;
                                        $this->data['totaldeduction'] = $totaldeduction;
                                        $this->data['netsalary']      = $netsalary;
                                        
                                        $this->report->reportSendToMail(['stylesheet' => 'makepaymentmodule.css', 'data' => $this->data, 'viewpath' => 'makepayment/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                        $retArray['message'] = "Success";
                                        $retArray['status']  = TRUE;
                                    } else {
                                        $retArray['message'] = $this->lang->line('makepayment_data_not_found'); 
                                    }
                                } elseif($makepayment->salaryID == 2) {
                                    $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID'=> $makepayment->templateID));
                                    if(inicompute($this->data['hourly_salary'])) {
                                        $this->data['grosssalary']    = 0;
                                        $this->data['totaldeduction'] = 0;
                                        $this->data['netsalary']      = $this->data['hourly_salary']->hourly_rate;
                                        
                                        $this->report->reportSendToMail(['stylesheet' => 'makepaymentmodule.css', 'data' => $this->data, 'viewpath' => 'makepayment/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                        $retArray['message'] = "Success";
                                        $retArray['status']  = TRUE;
                                    } else {
                                        $retArray['message'] = $this->lang->line('makepayment_data_not_found'); 
                                    }
                                } else {
                                    $retArray['message'] = $this->lang->line('makepayment_data_not_found'); 
                                }
                            } else {
                                $retArray['message'] = $this->lang->line('makepayment_data_not_found'); 
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('makepayment_data_not_found'); 
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('makepayment_data_not_found'); 
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('managesalary_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('managesalary_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("makepayment_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("makepayment_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("makepayment_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'makepaymentID',
                'label' => $this->lang->line("makepayment_make_payment"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function delete()
    {
        if(permissionChecker('makepayment')) {
            $makepaymentID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$makepaymentID) {
                $makepayment = $this->makepayment_m->get_single_makepayment(array('makepaymentID' => $makepaymentID));
                if (inicompute($makepayment)) {
                    $this->makepayment_m->delete_makepayment($makepaymentID);
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('makepayment/add/' . $makepayment->userID));
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

    public function required_no_zero($data)
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
