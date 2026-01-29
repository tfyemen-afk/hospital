<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accountledgerreport extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('income_m');
        $this->load->model('expense_m');
        $this->load->model('medicinepurchase_m');
        $this->load->model('medicinepurchasepaid_m');
        $this->load->model('medicinesale_m');
        $this->load->model('medicinesalepaid_m');
        $this->load->model('bill_m');
        $this->load->model('billpayment_m');
        $this->load->model('makepayment_m');
        $this->load->model('ambulancecall_m');
        $this->load->model('ambulance_m');
        $this->load->model('makepayment_m');

        $this->load->library('report');

		$language = $this->session->userdata('lang');;
		$this->lang->load('accountledgerreport', $language);
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
                'assets/inilabs/report/accountledger/index.js'
            )
        );
        
        $this->data["subview"] = "report/accountledger/AccountledgerReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getaccountledgerreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('accountledgerreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/accountledger/AccountledgerReport', $this->data,true);
                }
            }
        } else {
            $retArray['status'] = TRUE;
            $retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
        }
        echo json_encode($retArray);
        exit;
    }

    public function pdf() {
        if(permissionChecker('accountledgerreport')) {
            $from_date    = htmlentities(escapeString($this->uri->segment(3)));
            $to_date      = htmlentities(escapeString($this->uri->segment(4)));
            if(((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'accountledgerreport.css', 'data' => $this->data, 'viewpath' => 'report/accountledger/AccountledgerReportPDF']);
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_pdf_to_mail()
    {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('accountledgerreport')) {
            if($_POST) {
                $rules   = $this->send_pdf_to_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {

                    $this->queryArray($this->input->post());

                    $email   = $this->input->post('to');
                    $subject = $this->input->post('subject');
                    $message = $this->input->post('message');

                    $this->report->reportSendToMail(['stylesheet' => 'accountledgerreport.css', 'data' => $this->data, 'viewpath' => 'report/accountledger/AccountledgerReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('accountledgerreport_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('accountledgerreport_permission');
        }
        echo json_encode($retArray);
        exit;
    }

    private function queryArray($posts)
    {
        $from_date    = $posts['from_date'];
        $to_date      = $posts['to_date'];

        $queryArray = [];
        if($from_date && $to_date) {
            $from_date_str   = strtotime($from_date);
            $to_date_str     = strtotime($to_date);
            if($to_date_str >= $from_date_str) {
                $from_date_time           = date('Y-m-d', $from_date_str);
                $queryArray['from_date']  = $from_date_time." 00:00:00";
                $to_date_time             = date('Y-m-d', $to_date_str);
                $queryArray['to_date']    = $to_date_time." 23:59:59";
            }
        } elseif($from_date) {
            $queryArray['from_date']  = date('Y-m-d', strtotime($from_date))." 00:00:00";
            $queryArray['to_date']    = date('Y-m-d')." 23:59:59";
        }

        $this->data['from_date'] = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']   = ($to_date) ? strtotime($to_date) : 0;

        $this->data['expense']   = $this->expense_m->get_expense_order_by_date($queryArray);
        $this->data['income']    = $this->income_m->get_income_order_by_date($queryArray);
        $this->data['medicinepurchasepaid'] = $this->medicinepurchasepaid_m->get_medicinepurchasepaid_amount_order_by_date($queryArray);
        $ambulancecalls = $this->ambulancecall_m->get_ambulancecall_for_report($queryArray);
        $amount         = 0;
        if(inicompute($ambulancecalls)) {
            foreach($ambulancecalls as $ambulancecall)
                $amount   += $ambulancecall->amount;
        }
        $this->data['ambulancecallAmount']       = $amount;

        $this->data['medicinesalepaid']     = $this->medicinesalepaid_m->get_medicinesalepaid_amount_order_by_date($queryArray);
        $this->data['billpayment']  = $this->billpayment_m->get_billpayment_amount_order_by_date($queryArray);
        $this->data['makepayment']  = $this->makepayment_m->get_makepayment_amount_order_by_date($queryArray);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("accountledgerreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("accountledgerreport_to_date"),
                'rules' => 'trim|callback_date_valid|callback_unique_date_check'
            )
        );
        return $rules;
    }

    protected function send_pdf_to_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("accountledgerreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("accountledgerreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("accountledgerreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("accountledgerreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("accountledgerreport_to_date"),
                'rules' => 'trim|callback_date_valid|callback_unique_date_check'
            )
        );
        return $rules;
    }

    public function date_valid($date)
    {
        if($date) {
            if(strlen($date) < 10) {
                $this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                $dd = $arr[0];
                $mm = $arr[1];
                $yyyy = $arr[2];
                if(checkdate($mm, $dd, $yyyy)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("date_valid", "The %s is not valid dd-mm-yyyy");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    public function unique_date_check()
    {
        $from_date = $this->input->post('from_date');
        $to_date   = $this->input->post('to_date');

        if($from_date != '' && $to_date != '') {
            if(strtotime($from_date) > strtotime($to_date)) {
                $this->form_validation->set_message("unique_date_check", "The to date can not be lower than from date .");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }
    
}