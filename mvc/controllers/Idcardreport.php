<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Idcardreport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('role_m');
        $this->load->model('user_m');
        $this->load->model('designation_m');
        $this->load->model('patient_m');
        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('idcardreport', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'roleID',
				'label' => $this->lang->line("idcardreport_role"),
				'rules' => 'trim|callback_required_no_zero'
			),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("idcardreport_user"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line("idcardreport_type"),
                'rules' => 'trim|callback_required_no_zero'
            ),
            array(
                'field' => 'background',
                'label' => $this->lang->line("idcardreport_background"),
                'rules' => 'trim|callback_required_no_zero'
            )
		);
		return $rules;
	}

    protected function send_pdf_to_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("idcardreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("idcardreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("idcardreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("idcardreport_role"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("idcardreport_user"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line("idcardreport_type"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'background',
                'label' => $this->lang->line("idcardreport_background"),
                'rules' => 'trim'
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
                'assets/inilabs/report/idcard/index.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/inilabs/report/idcard/index.js',
            )
        );
        $this->data['roles']   = $this->role_m->get_select_role();
        
        $this->data["subview"] = "report/idcard/IdcardReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getidcardreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('idcardreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $queryArray = $this->queryArray($this->input->post());

                    $this->data['roleID']    = $this->input->post('roleID');
                    $this->data['userID']    = $this->input->post('userID');
                    $this->data['type']      = $this->input->post('type');
                    $this->data['background']= $this->input->post('background');
                    $this->data['roles']     = pluck($this->role_m->get_select_role(),'role','roleID');
                    $this->data['users']     = $this->user_m->get_order_by_user($queryArray);

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/idcard/IdcardReport', $this->data,true);
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['status'] = TRUE;
            $retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
            echo json_encode($retArray);
            exit;
        }
    }

    public function pdf() {
        if(permissionChecker('idcardreport')) {
            $roleID     = htmlentities(escapeString($this->uri->segment(3)));
            $userID     = htmlentities(escapeString($this->uri->segment(4)));
            $type       = htmlentities(escapeString($this->uri->segment(5)));
            $background = htmlentities(escapeString($this->uri->segment(6)));

            if(((int)$roleID || $roleID == 0) && ((int)$userID || $userID == 0) && ((int)$type || $type == 0) && ((int)$background || $background == 0)) {
                $qArray['roleID']    = $roleID; 
                $qArray['userID']    = $userID; 
                $qArray['type']      = $type; 
                $qArray['background']= $background;

                $queryArray = $this->queryArray($qArray);

                $this->data['roleID']     = $roleID;
                $this->data['userID']     = $userID;
                $this->data['type']       = $type;
                $this->data['background'] = $background;
                $this->data['roles']      = pluck($this->role_m->get_select_role(),'role','roleID');
                $this->data['users']      = $this->user_m->get_order_by_user($queryArray);
                $this->report->reportPDF(['stylesheet' => 'idcardreport.css', 'data' => $this->data, 'viewpath' => 'report/idcard/IdcardReportPDF', 'designnone'=> 0]);
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('idcardreport')) {
            if($_POST) {
                $rules   = $this->send_pdf_to_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $queryArray = $this->queryArray($this->input->post());

                    $email   = $this->input->post('to');
                    $subject = $this->input->post('subject');
                    $message = $this->input->post('message');

                    $this->data['roleID']    = $this->input->post('roleID');
                    $this->data['userID']    = $this->input->post('userID');
                    $this->data['type']      = $this->input->post('type');
                    $this->data['background']= $this->input->post('background');

                    $this->data['roles']     = pluck($this->role_m->get_select_role(),'role','roleID');
                    $this->data['users']      = $this->user_m->get_order_by_user($queryArray);
                    
                    $this->report->reportSendToMail(['stylesheet' => 'idcardreport.css', 'data' => $this->data, 'viewpath' => 'report/idcard/IdcardReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message, 'designnone'=> 0]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['message'] = $this->lang->line('idcardreport_permissionmethod');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = $this->lang->line('idcardreport_permission');
            echo json_encode($retArray);
            exit;
        }
    }

    private function queryArray($posts) {
        $roleID     = $posts['roleID'];
        $userID     = $posts['userID'];

        $queryArray = [];
        if($roleID > 0) {
            $queryArray['roleID'] = $roleID;
        }
        if($userID > 0) {
            $queryArray['userID'] = $userID;
        }

        $this->data['patients'] = []; 
        if($roleID ==3) {
            $this->data['patients'] = pluck($this->patient_m->get_select_patient('patientID,name,guardianname'),'obj','patientID');
        }

        $this->data['designations'] = pluck($this->designation_m->get_select_designation(),'designation', 'designationID');
        return $queryArray;
    }

    public function get_user() {
        echo "<option value='0'>".$this->lang->line("idcardreport_please_select")."</option>";
        if($_POST) {
            $roleID = $this->input->post('roleID');
            if((int)$roleID) {
                $users = $this->user_m->get_select_user('userID, name', array('roleID' => $roleID));
                if(inicompute($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->userID."'>".$user->name."</option>";
                    }
                }
            }
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