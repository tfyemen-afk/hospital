<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salarytemplate extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("salarytemplate_m");
        $this->load->model('salaryoption_m');

        $this->load->library('report');

        $language = $this->session->userdata('lang');
        $this->lang->load('salarytemplate', $language);
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'salary_grades',
                'label' => $this->lang->line("salarytemplate_salary_grades"),
                'rules' => 'trim|required|max_length[128]|callback_unique_salary_grades'
            ),
            array(
                'field' => 'basic_salary',
                'label' => $this->lang->line("salarytemplate_basic_salary"),
                'rules' => 'trim|required|max_length[11]'
            ),
            array(
                'field' => 'overtime_rate',
                'label' => $this->lang->line("salarytemplate_overtime_rate"),
                'rules' => 'trim|required|max_length[11]'
            )
        );
        return $rules;
    }

    public function index() 
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/salarytemplate/index.js'
            ]
        ];

        $this->data['jsmanager'] = [
            'salarytemplate_allowances_label' => $this->lang->line('salarytemplate_allowances_label'),
            'salarytemplate_allowances_value' => $this->lang->line('salarytemplate_allowances_value'),
            'salarytemplate_deductions_label' => $this->lang->line('salarytemplate_deductions_label'),
            'salarytemplate_deductions_value' => $this->lang->line('salarytemplate_deductions_value'),
            'salarytemplate_allowances_val' => $this->lang->line('salarytemplate_allowances_val'),
            'salarytemplate_deductions_val' => $this->lang->line('salarytemplate_deductions_val')
        ];
        $this->data['salarytemplates'] = $this->salarytemplate_m->get_salarytemplate();
        $this->data["subview"]         = "salarytemplate/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function add_ajax()
    {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error');
                $json = array("status" => 'error', 'errors' => $this->form_validation->error_array());
                header("Content-Type: application/json", true);
                echo json_encode($json);
                exit;
            } else {
                $array = array(
                    "salary_grades"     => $this->input->post("salary_grades"),
                    "basic_salary"      => $this->input->post("basic_salary"),
                    "overtime_rate"     => $this->input->post("overtime_rate"),
                );

                $this->salarytemplate_m->insert_salarytemplate($array);
                $salarytemplateID = $this->db->insert_id();

                $allowances_number = $this->input->post('allowances_number');
                if(inicompute($allowances_number)) {
                    for ($i=1; $i <= $allowances_number; $i++) { 
                        if($this->input->post('allowanceslabel'.$i) !='' && $this->input->post('allowancesamount'.$i) !='') {
                            $allowancesArray = array(
                                'salarytemplateID' => $salarytemplateID,
                                'option_type'       => 1,
                                'label_name'        => $this->input->post('allowanceslabel'.$i),
                                'label_amount'      => $this->input->post('allowancesamount'.$i)
                            );
                            $this->salaryoption_m->insert_salaryoption($allowancesArray);
                        }
                    }
                }

                $deductions_number = $this->input->post('deductions_number');
                if(inicompute($deductions_number)) {
                    for ($i=1; $i <= $deductions_number; $i++) { 
                        if($this->input->post('deductionslabel'.$i) !='' && $this->input->post('deductionsamount'.$i) !='') {
                            $deductionsArray = array(
                                'salarytemplateID' => $salarytemplateID,
                                'option_type'       => 2,
                                'label_name'        => $this->input->post('deductionslabel'.$i),
                                'label_amount'      => $this->input->post('deductionsamount'.$i)
                            );
                            $this->salaryoption_m->insert_salaryoption($deductionsArray);
                        }
                    }
                }

                $json = array("status" => 'success');
                header("Content-Type: application/json", true);
                echo json_encode($json);
                $this->session->set_flashdata('success','Success');
            }
        }
    }

    public function edit()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/salarytemplate/index.js'
            ]
        ];

        $this->data['jsmanager'] = [
            'salarytemplate_allowances_label' => $this->lang->line('salarytemplate_allowances_label'),
            'salarytemplate_allowances_value' => $this->lang->line('salarytemplate_allowances_value'),
            'salarytemplate_deductions_label' => $this->lang->line('salarytemplate_deductions_label'),
            'salarytemplate_deductions_value' => $this->lang->line('salarytemplate_deductions_value'),
            'salarytemplate_allowances_val' => $this->lang->line('salarytemplate_allowances_val'),
            'salarytemplate_deductions_val' => $this->lang->line('salarytemplate_deductions_val')
        ];

        $salarytemplateID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$salarytemplateID) {
            $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $salarytemplateID));
            if(inicompute($this->data['salarytemplate'])) {
                $this->data['salarytemplates'] = $this->salarytemplate_m->get_salarytemplate();

                $this->salaryoption_m->order("salaryoptionID asc");
                $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $salarytemplateID));
                $this->data['setid']         = $salarytemplateID;

                $grosssalary     = 0;
                $totaldeduction  = 0;
                $netsalary       = $this->data['salarytemplate']->basic_salary;
                $orginalNetsalary= $this->data['salarytemplate']->basic_salary;
                $grosssalarylist    = array();
                $totaldeductionlist = array();

                if(inicompute($this->data['salaryoptions'])) {
                    foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                        if($salaryOption->option_type == 1) {
                            $netsalary    += $salaryOption->label_amount;
                            $grosssalary  += $salaryOption->label_amount;
                            $grosssalarylist[$salaryOption->label_name]    = $salaryOption->label_amount;
                        } elseif($salaryOption->option_type == 2) {
                            $netsalary      -= $salaryOption->label_amount;
                            $totaldeduction += $salaryOption->label_amount;
                            $totaldeductionlist[$salaryOption->label_name] = $salaryOption->label_amount;
                        }
                    }
                }                

                $this->data['grosssalary']        = $grosssalary+$orginalNetsalary;
                $this->data['totaldeduction']     = $totaldeduction;
                $this->data['netsalary']          = $netsalary;
                $this->data['grosssalarylist']    = $grosssalarylist;
                $this->data['totaldeductionlist'] = $totaldeductionlist;
                $this->data["subview"] = "salarytemplate/edit";
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

     public function edit_ajax() {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error');
                $json = array("status" => 'error', 'errors' => $this->form_validation->error_array());
                header("Content-Type: application/json", true);
                echo json_encode($json);
                exit;
            } else {

                $array = array(
                    "salary_grades"     => $this->input->post("salary_grades"),
                    "basic_salary"      => $this->input->post("basic_salary"),
                    "overtime_rate"     => $this->input->post("overtime_rate"),
                );

                $id = htmlentities($this->input->post('id')); 
                $this->salarytemplate_m->update_salarytemplate($array, $id);

                $allowances_number = $this->input->post('allowances_number');
                $this->salaryoption_m->delete_salaryoption_by_salarytemplateID($id);

                if(inicompute($allowances_number)) {
                    for ($i=1; $i <= $allowances_number; $i++) { 
                        if($this->input->post('allowanceslabel'.$i) !='' && $this->input->post('allowancesamount'.$i) !='') {
                            $allowancesArray = array(
                                'salarytemplateID' => $id,
                                'option_type'       => 1,
                                'label_name'        => $this->input->post('allowanceslabel'.$i),
                                'label_amount'      => $this->input->post('allowancesamount'.$i)
                            );
                            $this->salaryoption_m->insert_salaryoption($allowancesArray);
                        }
                    }
                }

                $deductions_number = $this->input->post('deductions_number');
                if(inicompute($deductions_number)) {
                    for ($i=1; $i <= $deductions_number; $i++) { 
                        if($this->input->post('deductionslabel'.$i) !='' && $this->input->post('deductionsamount'.$i) !='') {
                            $deductionsArray = array(
                                'salarytemplateID' => $id,
                                'option_type'       => 2,
                                'label_name'        => $this->input->post('deductionslabel'.$i),
                                'label_amount'      => $this->input->post('deductionsamount'.$i)
                            );
                            $this->salaryoption_m->insert_salaryoption($deductionsArray);
                        }
                    }
                }

                $json = array("status" => 'success');
                header("Content-Type: application/json", true);
                echo json_encode($json);
                $this->session->set_flashdata('success','Success');
            }
        }
    }

    public function view() 
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/salarytemplate/view.js'
            ]
        ];

        $salarytemplateID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$salarytemplateID) {
            $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $salarytemplateID));
            if(inicompute($this->data['salarytemplate'])) {
                $this->data['jsmanager'] = ['salarytemplateID' => $this->data['salarytemplate']->salarytemplateID];

                $this->db->order_by("salaryoptionID", "asc");
                $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $salarytemplateID));

                $grosssalary      = 0;
                $totaldeduction   = 0;
                $netsalary        = $this->data['salarytemplate']->basic_salary;
                $orginalNetsalary = $this->data['salarytemplate']->basic_salary;
                if(inicompute($this->data['salaryoptions'])) {
                    foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                        if($salaryOption->option_type == 1) {
                            $netsalary   += $salaryOption->label_amount;
                            $grosssalary += $salaryOption->label_amount;
                        } elseif($salaryOption->option_type == 2) {
                            $netsalary      -= $salaryOption->label_amount;
                            $totaldeduction += $salaryOption->label_amount;
                        }
                    }
                }
                $this->data['grosssalary']    = $grosssalary+$orginalNetsalary;
                $this->data['totaldeduction'] = $totaldeduction;
                $this->data['netsalary']      = $netsalary;

                $this->data["subview"] = "salarytemplate/view";
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

    public function printpreview() 
    {
        $salarytemplateID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$salarytemplateID) {
            $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $salarytemplateID));
            if(inicompute($this->data['salarytemplate'])) {
                $this->db->order_by("salaryoptionID", "asc");
                $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $salarytemplateID));

                $grosssalary      = 0;
                $totaldeduction   = 0;
                $netsalary        = $this->data['salarytemplate']->basic_salary;
                $orginalNetsalary = $this->data['salarytemplate']->basic_salary;
                if(inicompute($this->data['salaryoptions'])) {
                    foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                        if($salaryOption->option_type == 1) {
                            $netsalary   += $salaryOption->label_amount;
                            $grosssalary += $salaryOption->label_amount;
                        } elseif($salaryOption->option_type == 2) {
                            $netsalary      -= $salaryOption->label_amount;
                            $totaldeduction += $salaryOption->label_amount;
                        }
                    }
                }
                $this->data['grosssalary']    = $grosssalary+$orginalNetsalary;
                $this->data['totaldeduction'] = $totaldeduction;
                $this->data['netsalary']      = $netsalary;

                $this->report->reportPDF(['stylesheet' => 'salarytemplatemodule.css', 'data' => $this->data, 'viewpath' => 'salarytemplate/printpreview']);
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
        $salarytemplateID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$salarytemplateID) {
            $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $salarytemplateID));
            if(inicompute($this->data['salarytemplate'])) {
                $this->salarytemplate_m->delete_salarytemplate($salarytemplateID);
                $this->salaryoption_m->delete_salaryoption_by_salarytemplateID($salarytemplateID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url("salarytemplate/index"));
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function sendmail() {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('salarytemplate_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $salarytemplateID = $this->input->post('salarytemplateID');
                    if((int)$salarytemplateID) {
                        $email      = $this->input->post('to');
                        $subject    = $this->input->post('subject');
                        $message    = $this->input->post('message');
                        $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $salarytemplateID));
                        if(inicompute($this->data['salarytemplate'])) {
                            $this->db->order_by("salaryoptionID", "asc");
                            $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $salarytemplateID));
                            $grosssalary      = 0;
                            $totaldeduction   = 0;
                            $netsalary        = $this->data['salarytemplate']->basic_salary;
                            $orginalNetsalary = $this->data['salarytemplate']->basic_salary;
                            if(inicompute($this->data['salaryoptions'])) {
                                foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                    if($salaryOption->option_type == 1) {
                                        $netsalary   += $salaryOption->label_amount;
                                        $grosssalary += $salaryOption->label_amount;
                                    } elseif($salaryOption->option_type == 2) {
                                        $netsalary      -= $salaryOption->label_amount;
                                        $totaldeduction += $salaryOption->label_amount;
                                    }
                                }
                            }
                            $this->data['grosssalary']    = $grosssalary+$orginalNetsalary;
                            $this->data['totaldeduction'] = $totaldeduction;
                            $this->data['netsalary']      = $netsalary;

                            $this->report->reportSendToMail(['stylesheet' => 'salarytemplatemodule.css', 'data' => $this->data, 'viewpath' => 'salarytemplate/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {
                            $retArray['message'] = $this->lang->line('salarytemplate_method_not_allowed');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('salarytemplate_method_not_allowed');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('salarytemplate_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('salarytemplate_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

        protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("salarytemplate_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("salarytemplate_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("salarytemplate_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'salarytemplateID',
                'label' => $this->lang->line("salarytemplate_salary_template"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function unique_salary_grades() 
    {
        if($this->input->post('salary_grades')) {
            $salarytemplateID = htmlentities(escapeString($this->input->post('id')));
            if((int)$salarytemplateID) {
                $salary_grades = $this->salarytemplate_m->get_single_salarytemplate(array('salary_grades' => $this->input->post("salary_grades"), 'salarytemplateID !=' => $salarytemplateID));
                if(inicompute($salary_grades)) {
                    $this->form_validation->set_message("unique_salary_grades", "The %s already exists");
                    return FALSE;
                }
                return TRUE;
            } else {
                $salary_grades = $this->salarytemplate_m->get_single_salarytemplate(array('salary_grades' => $this->input->post("salary_grades")));
                if(inicompute($salary_grades)) {
                    $this->form_validation->set_message("unique_salary_grades", "The %s already exists");
                    return FALSE;
                }
                return TRUE;
            }
        }
        return TRUE;
    }

}
