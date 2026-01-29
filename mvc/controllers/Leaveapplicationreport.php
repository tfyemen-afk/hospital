<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaveapplicationreport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('role_m');
        $this->load->model('leavecategory_m');
        $this->load->model('user_m');
        $this->load->model('leaveapplication_m');

        $this->load->library('report');

		$language = $this->session->userdata('lang');;
		$this->lang->load('leaveapplicationreport', $language);
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
                'assets/inilabs/report/leaveapplication/index.js'
            )
        );
        $this->data['leavecategorys'] = $this->leavecategory_m->get_leavecategory();
        $this->data['roles']          = $this->role_m->get_select_role('roleID, role', array('roleID !='=>3));
        
        $this->data["subview"] = "report/leaveapplication/LeaveapplicationReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getleaveapplicationreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('leaveapplicationreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {
                    $this->queryArray($this->input->post());
                    
                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/leaveapplication/LeaveapplicationReport', $this->data,true);
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
        if(permissionChecker('leaveapplicationreport')) {
            $roleID     = htmlentities(escapeString($this->uri->segment(3)));
            $userID     = htmlentities(escapeString($this->uri->segment(4)));
            $categoryID = htmlentities(escapeString($this->uri->segment(5)));
            $statusID   = htmlentities(escapeString($this->uri->segment(6)));
            $from_date  = htmlentities(escapeString($this->uri->segment(7)));
            $to_date    = htmlentities(escapeString($this->uri->segment(8)));

            if(((int)$roleID || $roleID == 0) && ((int)$userID || $userID == 0) && ((int)$categoryID || $categoryID == 0) && ((int)$statusID || $statusID == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['roleID']      = $roleID; 
                $qArray['userID']      = $userID; 
                $qArray['categoryID']  = $categoryID; 
                $qArray['statusID']    = $statusID;
                $qArray['from_date']   = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']     = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'leaveapplicationreport.css', 'data' => $this->data, 'viewpath' => 'report/leaveapplication/LeaveapplicationReportPDF']);
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function xlsx() {
        if(permissionChecker('leaveapplicationreport')) {
            $this->load->library('phpspreadsheet');

            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $sheet->getDefaultColumnDimension()->setWidth(25);
            $sheet->getDefaultRowDimension()->setRowHeight(25);
            $sheet->getColumnDimension('A')->setWidth(25);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getRowDimension('2')->setRowHeight(25);

            $this->xmlData();

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="leaveapplicationreport.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $this->phpspreadsheet->output($this->phpspreadsheet->spreadsheet);
        } else {
            $this->data["subview"] = "report/reporterror";
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function xmlData() {
        if(permissionChecker('leaveapplicationreport')) {
            $roleID     = htmlentities(escapeString($this->uri->segment(3)));
            $userID     = htmlentities(escapeString($this->uri->segment(4)));
            $categoryID = htmlentities(escapeString($this->uri->segment(5)));
            $statusID   = htmlentities(escapeString($this->uri->segment(6)));
            $from_date  = htmlentities(escapeString($this->uri->segment(7)));
            $to_date    = htmlentities(escapeString($this->uri->segment(8)));

            if(((int)$roleID || $roleID == 0) && ((int)$userID || $userID == 0) && ((int)$categoryID || $categoryID == 0) && ((int)$statusID || $statusID == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['roleID']      = $roleID; 
                $qArray['userID']      = $userID; 
                $qArray['categoryID']  = $categoryID; 
                $qArray['statusID']    = $statusID;
                $qArray['from_date']   = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']     = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('leaveapplicationreport'));
            }
        } else {
            redirect(site_url('leaveapplicationreport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($leaveapplications)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'H';

             $leftlabel  = "";
            $rightlabel = "";
            if($from_date && $to_date) { 
                $leftlabel = $this->lang->line('leaveapplicationreport_from_date')." : ".$label_from_date;;
            } elseif($categoryID) {
                $leftlabel = $this->lang->line('leaveapplicationreport_category')." : ".$label_category_name;;
            } elseif($roleID) {
                $leftlabel = $this->lang->line('leaveapplicationreport_role')." : ".(isset($roles[$roleID]) ? $roles[$roleID] : '');
            }

            if($from_date && $to_date) {
                $rightlabel = $this->lang->line('leaveapplicationreport_to_date')." : ".$label_to_date;
            } elseif($statusID) { 
                $rightlabel = $this->lang->line('leaveapplicationreport_status')." : ".(isset($statusArray[$statusID]) ? $statusArray[$statusID] : '');
            } elseif((int)$userID) {
                $rightlabel = $this->lang->line('leaveapplicationreport_user')." : ".(isset($users[$userID]) ? $users[$userID] : '');
            } elseif($from_date) {
                $rightlabel = $this->lang->line('leaveapplicationreport_from_date')." : ".$label_from_date;
            } 
            
            if($leftlabel != '' && $rightlabel != '') {
                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->setCellValue('H'.$row, $rightlabel);
                $sheet->mergeCells("B1:G1");
            } elseif($leftlabel != '') {
                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->mergeCells("B1:H1");
            } elseif ($rightlabel != '') {
                $sheet->setCellValue('A'.$row, $rightlabel);
                $sheet->mergeCells("B1:H1");
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers   = array();
            $headers[] = '#';
            $headers[] = $this->lang->line('leaveapplicationreport_appplicant');
            $headers[] = $this->lang->line('leaveapplicationreport_role');
            $headers[] = $this->lang->line('leaveapplicationreport_category');
            $headers[] = $this->lang->line('leaveapplicationreport_date');
            $headers[] = $this->lang->line('leaveapplicationreport_schedule');
            $headers[] = $this->lang->line('leaveapplicationreport_days');
            $headers[] = $this->lang->line('leaveapplicationreport_status');

            if(inicompute($headers)) {
                $column = "A";
                $row    = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }

            $i     = 0;
            $bodys = [];
            foreach($leaveapplications as $leaveapplication) {
                $bodys[$i][]   = ++$i;
                $bodys[$i][]   = isset($users[$leaveapplication->create_userID]) ? $users[$leaveapplication->create_userID] : '';
                $bodys[$i][]   = isset($roles[$leaveapplication->create_roleID]) ? $roles[$leaveapplication->create_roleID] : '';
                $bodys[$i][]   = $leaveapplication->leavecategory;
                $bodys[$i][]   = app_date($leaveapplication->create_date, FALSE);
                $bodys[$i][]   = app_date($leaveapplication->from_date)." - ".app_date($leaveapplication->to_date);
                $bodys[$i][]   = $leaveapplication->leave_days;

                if($leaveapplication->status == '1') {
                    $bodys[$i][] = $this->lang->line('leaveapplicationreport_approved');
                } elseif($leaveapplication->status == '0') {
                    $bodys[$i][] = $this->lang->line('leaveapplicationreport_declined');
                } else {
                    $bodys[$i][] = $this->lang->line('leaveapplicationreport_pending');
                }
            }
            $i++;

            if(inicompute($bodys)) {
                $row = 3;
                foreach($bodys as $single_rows) {
                    $column = 'A';
                    foreach($single_rows as $value) {
                        $sheet->setCellValue($column.$row, $value);
                        $column++;
                    }
                    $row++;
                }
            }

            // Top Two Row
            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' =>[
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ]
                ]
            ];
            $sheet->getStyle("A1:".$lastcolumn.'2')->applyFromArray($styleArray);


            // After Two Row to Before Last Row
            $styleArray = [
                'font' => [
                    'bold' => FALSE,
                ],
                'alignment' =>[
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ]
                ]
            ];
            $styleColumn = $lastcolumn.($row-1);
            $sheet->getStyle('A3:'.$styleColumn)->applyFromArray($styleArray);

        } else {
            redirect(site_url('leaveapplicationreport'));
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('leaveapplicationreport')) {
            if($_POST) {
                $rules   = $this->send_pdf_to_mail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {
                    $queryArray = $this->queryArray($this->input->post());

                    $email   = $this->input->post('to');
                    $subject = $this->input->post('subject');
                    $message = $this->input->post('message');
                    $this->report->reportSendToMail(['stylesheet' => 'leaveapplicationreport.css', 'data' => $this->data, 'viewpath' => 'report/leaveapplication/LeaveapplicationReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('leaveapplicationreport_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('leaveapplicationreport_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    private function queryArray($posts) {
        $roleID     = $posts['roleID'];
        $userID     = $posts['userID'];
        $categoryID = $posts['categoryID'];
        $statusID   = $posts['statusID'];
        $from_date  = $posts['from_date'];
        $to_date    = $posts['to_date'];

        $queryArray = [];
        if((int)$roleID) {
            $queryArray['roleID'] = $roleID;
            if((int)$userID) {
                $queryArray['userID'] = $userID;
            }
        }
        if((int)$categoryID) {
            $queryArray['categoryID'] = $categoryID;
        }
        if((int)$statusID) {
            $queryArray['statusID'] = $statusID;
        }
        if($from_date && $to_date) {
            $from_date_str   = strtotime($from_date);
            $to_date_str     = strtotime($to_date);
            if($to_date_str >= $from_date_str) {
                $from_date_time          = date('Y-m-d', $from_date_str);
                $queryArray['from_date'] = $from_date_time." 00:00:00";
                $to_date_time            = date('Y-m-d', $to_date_str);
                $queryArray['to_date']   = $to_date_time." 23:59:59";
            }
        } elseif($from_date) {
            $queryArray['from_date']  = date('Y-m-d', strtotime($from_date))." 00:00:00";
            $queryArray['to_date']    = date('Y-m-d')." 23:59:59";
        }
        
        $this->data['roleID']    = $roleID;
        $this->data['userID']    = $userID;
        $this->data['categoryID']= $categoryID;
        $this->data['statusID']  = $statusID;
        $this->data['from_date'] = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']   = ($to_date) ? strtotime($to_date) : 0;

        $this->data['label_category_name'] = '';
        if($categoryID) {
            $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=>$categoryID));
            if(inicompute($leavecategory)) {
                $this->data['label_category_name'] = $leavecategory->leavecategory;
            }
        }

        $statusArray[1] = $this->lang->line("leaveapplicationreport_pending");
        $statusArray[2] = $this->lang->line("leaveapplicationreport_declined");
        $statusArray[3] = $this->lang->line("leaveapplicationreport_approved");
        $this->data['statusArray'] = $statusArray;

        $this->data['label_from_date']   = ($from_date) ? app_date($from_date, FALSE) : '';
        $this->data['label_to_date']     = ($to_date) ? app_date($to_date, FALSE) : '';

        $this->data['roles']             = pluck($this->role_m->get_select_role(),'role','roleID');
        $this->data['users']             = pluck($this->user_m->get_select_user(),'name','userID');
        $this->data['leaveapplications'] = $this->leaveapplication_m->get_leaveapplication_for_report($queryArray);
    }

    public function get_user() {
        echo "<option value='0'>".$this->lang->line("leaveapplicationreport_please_select")."</option>";
        if($_POST) {
            $roleID = $this->input->post('roleID');
            if((int)$roleID && $roleID != 3) {
                $users = $this->user_m->get_select_user('userID, name', array('roleID' => $roleID));
                if(inicompute($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->userID."'>".$user->name."</option>";
                    }
                }
            }
        }
    }

        protected function rules()
    {
        $rules = array(
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("leaveapplicationreport_role"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("leaveapplicationreport_user"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'categoryID',
                'label' => $this->lang->line("leaveapplicationreport_category"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line("leaveapplicationreport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("leaveapplicationreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("leaveapplicationreport_to_date"),
                'rules' => 'trim|callback_date_valid|callback_unique_date'
            )
        );
        return $rules;
    }

    protected function send_pdf_to_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("leaveapplicationreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("leaveapplicationreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("leaveapplicationreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("leaveapplicationreport_role"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("leaveapplicationreport_user"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'categoryID',
                'label' => $this->lang->line("leaveapplicationreport_category"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line("leaveapplicationreport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("leaveapplicationreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("leaveapplicationreport_to_date"),
                'rules' => 'trim|callback_date_valid|callback_unique_date'
            )
        );
        return $rules;
    }

    public function date_valid($date) {
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

    public function unique_date() {
        $from_date = $this->input->post('from_date');
        $to_date   = $this->input->post('to_date');

        if($from_date != '' && $to_date != '') {
            if(strtotime($from_date) > strtotime($to_date)) {
                $this->form_validation->set_message("unique_date", "The from date can not be upper than to_date .");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }
    
}