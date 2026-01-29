<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Managesalary extends Admin_Controller
{
    public function __construct()
     {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('user_m');
        $this->load->model('designation_m');
        $this->load->model('salaryoption_m');
        $this->load->model('managesalary_m');
        $this->load->model('salarytemplate_m');
        $this->load->model('hourlytemplate_m');

        $this->load->library('report');

        $language = $this->session->userdata('lang');;
        $this->lang->load('managesalary', $language);
    }
    
    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'salary',
                'label' => $this->lang->line("managesalary_salary"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'template',
                'label' => $this->lang->line("managesalary_select_template"),
                'rules' => 'trim|required|callback_required_no_zero'
            )
        );
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
                'assets/inilabs/managesalary/index.js',
            )
        );

        $roleID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$roleID) {
            $this->data['users'] = $this->user_m->get_select_user('userID, photo, name, designationID, email', array('roleID !=' => 3, 'roleID' => $roleID, 'status' => 1, 'delete_at' => 0));
        } else {
            $this->data['users'] = $this->user_m->get_select_user('userID, photo, name, designationID, email', array('roleID !=' => 3, 'status' => 1, 'delete_at' => 0));
        }

        $this->data['jsmanager']     = ['managesalary_select_template' => $this->lang->line('managesalary_select_template')];
        $this->data['setroleID']     = $roleID ? $roleID : 0;
        $this->data['roles']         = $this->role_m->get_select_role('roleID, role', array('roleID !=' => 3));
        $this->data['designations']  = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
        $this->data['managesalarys'] = pluck($this->managesalary_m->get_managesalary(),'obj','userID');
        $this->data["subview"]       = 'managesalary/index';
        $this->load->view('_layout_main', $this->data);
	}

    public function view()
    {
        $managesalaryID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$managesalaryID) {
            $this->data['managesalary'] = $this->managesalary_m->get_single_managesalary(array('managesalaryID' => $managesalaryID));
            if (inicompute($this->data['managesalary'] )) {
                $this->data['jsmanager'] = ['myManageSalaryID' => $managesalaryID];
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/inilabs/managesalary/index.js',
                        'assets/inilabs/managesalary/view.js',
                    )
                );
                $this->data['user']      = $this->user_m->get_single_user(array('userID' => $this->data['managesalary']->userID, 'status' => 1, 'delete_at' => 0));
                $this->data['designations'] = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
                if ($this->data['managesalary']->salary == '1') {
                    $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $this->data['managesalary']->template));
                    if(inicompute($this->data['salarytemplate'])) {
                        $this->salaryoption_m->order("salaryoptionID asc");
                        $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $this->data['managesalary']->template));

                        $grosssalary      = 0;
                        $totaldeduction   = 0;
                        $netsalary        = $this->data['salarytemplate']->basic_salary;
                        $orginalNetsalary = $this->data['salarytemplate']->basic_salary;

                        if(inicompute($this->data['salaryoptions'])) {
                            foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                if($salaryOption->option_type == 1) {
                                    $netsalary += $salaryOption->label_amount;
                                    $grosssalary += $salaryOption->label_amount;
                                } elseif($salaryOption->option_type == 2) {
                                    $netsalary -= $salaryOption->label_amount;
                                    $totaldeduction += $salaryOption->label_amount;
                                }
                            }
                        }
                        
                        $this->data['grosssalary']    = $grosssalary+$orginalNetsalary;
                        $this->data['totaldeduction'] = $totaldeduction;
                        $this->data['netsalary']      = $netsalary;

                        $this->data["subview"] = "managesalary/view";
                        $this->load->view('_layout_main', $this->data);
                    }  
                } elseif($this->data['managesalary']->salary == '2') {
                    $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID'=> $this->data['managesalary']->template));
                    if(inicompute($this->data['hourly_salary'])) {
                        $this->data['grosssalary'] = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary'] = $this->data['hourly_salary']->hourly_rate;

                        $this->data["subview"] = "managesalary/view";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $this->data["subview"] = "_not_found";
                        $this->load->view('_layout_main', $this->data);
                    }
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
        $managesalaryID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$managesalaryID) {
            $this->data['managesalary'] = $this->managesalary_m->get_single_managesalary(array('managesalaryID' => $managesalaryID));
            if (inicompute($this->data['managesalary'] )) {
                $this->data['user']      = $this->user_m->get_single_user(array('userID' => $this->data['managesalary']->userID, 'status' => 1, 'delete_at' => 0));
                $this->data['designations'] = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
                if ($this->data['managesalary']->salary == '1') {
                    $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $this->data['managesalary']->template));
                    if(inicompute($this->data['salarytemplate'])) {
                        $this->salaryoption_m->order("salaryoptionID asc");
                        $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $this->data['managesalary']->template));

                        $grosssalary      = 0;
                        $totaldeduction   = 0;
                        $netsalary        = $this->data['salarytemplate']->basic_salary;
                        $orginalNetsalary = $this->data['salarytemplate']->basic_salary;

                        if(inicompute($this->data['salaryoptions'])) {
                            foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                if($salaryOption->option_type == 1) {
                                    $netsalary += $salaryOption->label_amount;
                                    $grosssalary += $salaryOption->label_amount;
                                } elseif($salaryOption->option_type == 2) {
                                    $netsalary -= $salaryOption->label_amount;
                                    $totaldeduction += $salaryOption->label_amount;
                                }
                            }
                        }
                        
                        $this->data['grosssalary']    = $grosssalary+$orginalNetsalary;
                        $this->data['totaldeduction'] = $totaldeduction;
                        $this->data['netsalary']      = $netsalary;

                        $this->report->reportPDF(['stylesheet' => 'managesalarymodule.css', 'data' => $this->data, 'viewpath' => 'managesalary/printpreview']);
                    }  
                } elseif($this->data['managesalary']->salary == '2') {
                    $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID'=> $this->data['managesalary']->template));
                    if(inicompute($this->data['hourly_salary'])) {
                        $this->data['grosssalary'] = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary'] = $this->data['hourly_salary']->hourly_rate;

                        $this->report->reportPDF(['stylesheet' => 'managesalarymodule.css', 'data' => $this->data, 'viewpath' => 'managesalary/printpreview']);
                    } else {
                        $this->data["subview"] = "_not_found";
                        $this->load->view('_layout_main', $this->data);
                    }
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

    public function sendmail() {
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
                    $managesalaryID = $this->input->post('managesalaryID');
                    if((int)$managesalaryID) {
                        $this->data['managesalary'] = $this->managesalary_m->get_single_managesalary(array('managesalaryID' => $managesalaryID));
                        if (inicompute($this->data['managesalary'] )) {
                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');

                            $this->data['user']      = $this->user_m->get_single_user(array('userID' => $this->data['managesalary']->userID, 'status' => 1, 'delete_at' => 0));
                            $this->data['designations'] = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
                            if ($this->data['managesalary']->salary == '1') {
                                $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $this->data['managesalary']->template));
                                if(inicompute($this->data['salarytemplate'])) {
                                    $this->salaryoption_m->order("salaryoptionID asc");
                                    $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $this->data['managesalary']->template));

                                    $grosssalary      = 0;
                                    $totaldeduction   = 0;
                                    $netsalary        = $this->data['salarytemplate']->basic_salary;
                                    $orginalNetsalary = $this->data['salarytemplate']->basic_salary;

                                    if(inicompute($this->data['salaryoptions'])) {
                                        foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                            if($salaryOption->option_type == 1) {
                                                $netsalary += $salaryOption->label_amount;
                                                $grosssalary += $salaryOption->label_amount;
                                            } elseif($salaryOption->option_type == 2) {
                                                $netsalary -= $salaryOption->label_amount;
                                                $totaldeduction += $salaryOption->label_amount;
                                            }
                                        }
                                    }
                                    
                                    $this->data['grosssalary']    = $grosssalary+$orginalNetsalary;
                                    $this->data['totaldeduction'] = $totaldeduction;
                                    $this->data['netsalary']      = $netsalary;

                                    $this->report->reportSendToMail(['stylesheet' => 'managesalarymodule.css', 'data' => $this->data, 'viewpath' => 'managesalary/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                    $retArray['message'] = "Success";
                                    $retArray['status']  = TRUE;
                                } else {
                                    $retArray['message'] = $this->lang->line('managesalary_data_not_found');
                                }  
                            } elseif($this->data['managesalary']->salary == '2') {
                                $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID'=> $this->data['managesalary']->template));
                                if(inicompute($this->data['hourly_salary'])) {
                                    $this->data['grosssalary'] = 0;
                                    $this->data['totaldeduction'] = 0;
                                    $this->data['netsalary'] = $this->data['hourly_salary']->hourly_rate;

                                    $this->report->reportSendToMail(['stylesheet' => 'managesalarymodule.css', 'data' => $this->data, 'viewpath' => 'managesalary/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                    $retArray['message'] = "Success";
                                    $retArray['status']  = TRUE;
                                } else {
                                    $retArray['message'] = $this->lang->line('managesalary_data_not_found');
                                }
                            } else {
                                $retArray['message'] = $this->lang->line('managesalary_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('managesalary_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('managesalary_data_not_found');
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

    public function delete()
    {
        $managesalaryID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$managesalaryID) {
            $managesalary = $this->managesalary_m->get_single_managesalary(array('managesalaryID' => $managesalaryID));
            if(inicompute($managesalary)) {
                $this->managesalary_m->delete_managesalary($managesalaryID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('managesalary/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function templatecall()
    {
        if($_POST) {
            $salary = $this->input->post('salary');
            if($salary == 1) {
                $salarytemplates = $this->salarytemplate_m->get_salarytemplate();
                echo '<option value="0">'.$this->lang->line('managesalary_select_template').'</option>';
                if(inicompute($salarytemplates)) {
                    foreach ($salarytemplates as $salarytemplatekey => $salarytemplate) { 
                        echo '<option value="'.$salarytemplate->salarytemplateID.'">'.$salarytemplate->salary_grades.'</option>';
                    }
                }
            } elseif($salary == 2) {
                $salarytemplates = $this->hourlytemplate_m->get_hourlytemplate();
                echo '<option value="0">'.$this->lang->line('managesalary_select_template').'</option>';
                if(inicompute($salarytemplates)) {
                    foreach ($salarytemplates as $salarytemplatekey => $salarytemplate) { 
                        echo '<option value="'.$salarytemplate->hourlytemplateID.'">'.$salarytemplate->hourly_grades.'</option>';
                    }
                }
            }
        }
    }

    public function addsalary()
    {
        $retArray = []; 
        $retArray['status']  = FALSE; 
        $retArray['message'] = ''; 
        if($_POST) {
            if(permissionChecker('managesalary_add')) { 
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE; 
                } else {
                    $array = array(
                        'userID'        => $this->input->post('userID'),
                        'salary'        => $this->input->post('salary'),
                        'template'      => $this->input->post('template'),
                        'create_date'   => date('Y-m-d H:i:s'),
                        'modify_date'   => date('Y-m-d H:i:s'),
                        'create_userID' => $this->session->userdata('loginuserID'),
                        'create_roleID' => $this->session->userdata('roleID')
                    );
                    $this->managesalary_m->insert_managesalary($array);
                    $this->session->set_flashdata('success','Success');
                    $retArray['status'] = TRUE; 
                }
            } else {
                $retArray['message'] = $this->lang->line('managesalary_permission_not_allowed'); 
            }
        } else {
            $retArray['message'] = $this->lang->line('managesalary_method_not_allowed'); 
        }
        echo json_encode($retArray);
    }

    public function editinfocall()
    {
        $retArray = []; 
        $retArray['status']  = FALSE; 
        $retArray['message'] = ''; 
        if($_POST) {
            if(permissionChecker('managesalary_edit')) {
                $managesalaryID = $this->input->post('managesalaryid');
                if((int)$managesalaryID){
                    $managesalary  = $this->managesalary_m->get_single_managesalary(array('managesalaryID' => $managesalaryID));
                    if(inicompute($managesalary)) {
                        $retArray['status']       = TRUE;
                        $retArray['managesalary'] = $managesalary;
                    } else {
                        $retArray['message'] = $this->lang->line('managesalary_data_not_found'); 
                    }
                } else {
                    $retArray['message'] = $this->lang->line('managesalary_data_not_found'); 
                }
            } else {
                $retArray['message'] = $this->lang->line('managesalary_permission_not_allowed'); 
            }
        } else {
            $retArray['message'] = $this->lang->line('managesalary_method_not_allowed'); 
        }
        echo json_encode($retArray);
    }

    public function updatesalary()
    {
        $retArray = []; 
        $retArray['status']  = FALSE; 
        $retArray['message'] = ''; 
        if($_POST) {
            if(permissionChecker('managesalary_edit')) {
                $managesalaryID = $this->input->post('managesalaryID');
                if((int)$managesalaryID) {
                    $managesalary  = $this->managesalary_m->get_single_managesalary(array('managesalaryID' =>$managesalaryID));
                    if(inicompute($managesalary)) {
                        $rules = $this->rules();
                        $this->form_validation->set_rules($rules);
                        if($this->form_validation->run() == FALSE) {
                            $retArray = $this->form_validation->error_array();
                            $retArray['status']  = FALSE; 
                        } else {
                            $array = array(
                                'salary'      => $this->input->post('salary'),
                                'template'    => $this->input->post('template'),
                                'modify_date' => date('Y-m-d H:i:s'),
                            );
                            $this->managesalary_m->update_managesalary($array, $managesalaryID);
                            $retArray['status']  = TRUE; 
                            $this->session->set_flashdata('success','Success');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('managesalary_data_not_found'); 
                    }
                } else {
                    $retArray['message'] = $this->lang->line('managesalary_data_not_found'); 
                }
            } else {
                $retArray['message'] = $this->lang->line('managesalary_permission_not_allowed'); 
            }
        } else {
            $retArray['message'] = $this->lang->line('managesalary_method_not_allowed'); 
        }
        echo json_encode($retArray);
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("managesalary_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("managesalary_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("managesalary_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'managesalaryID',
                'label' => $this->lang->line("managesalary_manage_salary"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function required_no_zero($data)
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }
}
