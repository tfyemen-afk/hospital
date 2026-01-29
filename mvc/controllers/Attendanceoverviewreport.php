<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendanceoverviewreport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();

        $this->load->model('user_m');
        $this->load->model('role_m');
        $this->load->model('attendance_m');
        $this->load->model('leaveapplication_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('attendanceoverviewreport', $language);
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
                'assets/inilabs/report/attendanceoverview/index.js'
            )
        );
        $this->data['roles']    = pluck($this->role_m->get_order_by_role(array('roleID !=' => 3)),'role', 'roleID');
        $this->data["subview"]  = "report/attendanceoverview/AttendanceoverviewReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getattendanceoverviewreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('attendanceoverviewreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/attendanceoverview/AttendanceoverviewReport', $this->data,true);
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
        if(permissionChecker('attendanceoverviewreport')) {
            $roleID   = htmlentities(escapeString($this->uri->segment(3)));
            $userID   = htmlentities(escapeString($this->uri->segment(4)));
            $month    = htmlentities(escapeString($this->uri->segment(5)));
            if(((int)$roleID || $roleID==0) && ((int)$userID || $userID==0) && ((int)$month || $month == 0)) {
                $qArray['roleID']  = $roleID;
                $qArray['userID']  = $userID;
                $qArray['month']   = ($month) ? date('m-Y', $month) : '';
                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'attendanceoverviewreport.css', 'data' => $this->data, 'viewpath' => 'report/attendanceoverview/AttendanceoverviewReportPDF', 'pagetype'=> 'landscape']);
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
        if(permissionChecker('attendanceoverviewreport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'attendanceoverviewreport.css', 'data' => $this->data, 'viewpath' => 'report/attendanceoverview/AttendanceoverviewReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message, 'pagetype'=> 'landscape']);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('attendanceoverviewreport_permission_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('attendanceoverviewreport_method_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }
    
    public function xlsx() {
        if(permissionChecker('attendanceoverviewreport')) {
            $this->load->library('phpspreadsheet');

            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $sheet->getDefaultColumnDimension()->setWidth(25);
            $sheet->getDefaultRowDimension()->setRowHeight(40);
            $sheet->getColumnDimension('A')->setWidth(25);
            $sheet->getRowDimension('1')->setRowHeight(25);
            $sheet->getRowDimension('2')->setRowHeight(25);

            $this->xmlData();

            // Redirect output to a client’s web browser (Xlsx)
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="attendanceoverviewreport.xlsx"');
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
        if(permissionChecker('attendanceoverviewreport')) {
            $roleID   = htmlentities(escapeString($this->uri->segment(3)));
            $userID   = htmlentities(escapeString($this->uri->segment(4)));
            $month    = htmlentities(escapeString($this->uri->segment(5)));
            if(((int)$roleID || $roleID==0) && ((int)$userID || $userID==0) && ((int)$month || $month == 0)) {
                $qArray['roleID']  = $roleID;
                $qArray['userID']  = $userID;
                $qArray['month']   = ($month) ? date('m-Y', $month) : '';
                $queryArray = $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('attendanceoverviewreport'));
            }
        } else {
            redirect(site_url('attendanceoverviewreport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($users)) {
            $sheet         = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            $sheet->getColumnDimension('A')->setWidth(15);
            $sheet->getColumnDimension('B')->setWidth(25);

            $getDayOfMonth = date('t', mktime(0, 0, 0, $monthday[0], 1, $monthday[1]));
            $row           = 1;
            $headerColumn  = 'A';
            $countColumn   = $getDayOfMonth + 7;          
            for($j = 1; $j < $countColumn; $j++) {
                $headerColumn++;
            }
            $lastcolumn    = $headerColumn;

            $leftvalue     = "";
            if($userID) {
                $leftvalue =  $label_user;
            } else {
                $leftvalue =  $label_role;
            }
            $sheet->setCellValue('A'.$row, $leftvalue);

            $rightvalue = $label_month;
            $sheet->setCellValue($lastcolumn.$row, $rightvalue);


            $header_column_last_letter     = substr($headerColumn, -1);
            $header_column_last_letter_new = chr(ord($header_column_last_letter) - 1);  //Decreament Header Section Column
            $mergeCellsColumn = 'A'.$header_column_last_letter_new.'1';
            $sheet->mergeCells("B1:$mergeCellsColumn");

            $headers   = array();
            $headers[] = '#';
            $headers[] = $this->lang->line('attendanceoverviewreport_name').' / '.$this->lang->line('attendanceoverviewreport_date');
            for($i=1; $i <= $getDayOfMonth; $i++) { 
                $headers[] = $this->lang->line('attendanceoverviewreport_'."$i");
            } 
            $headers[] = $this->lang->line('attendanceoverviewreport_la');
            $headers[] = $this->lang->line('attendanceoverviewreport_p');
            $headers[] = $this->lang->line('attendanceoverviewreport_le');
            $headers[] = $this->lang->line('attendanceoverviewreport_l');
            $headers[] = $this->lang->line('attendanceoverviewreport_a');

            if(inicompute($headers)) {
                $column = "A";
                $row    = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }

            $i=0; 
            $bodys = [];
            foreach($users  as $user) { 
                $leavedayCount   = 0;
                $presentCount    = 0;
                $lateexcuseCount = 0;
                $lateCount       = 0;
                $absentCount     = 0;

                $bodys[$i][] = ++$i;
                $bodys[$i][] = $user->name;
                $userleaveapplications = isset($leaveapplications[$user->userID]) ? $leaveapplications[$user->userID] : [];
                if(isset($attendances[$user->userID])) {
                    for($j=1; $j <= $getDayOfMonth; $j++) { 
                        $atten = "a".$j; 
                        $currentDate = sprintf("%02d", $j).'-'.$monthdays;

                        if(in_array($currentDate, $userleaveapplications)) {
                            $bodys[$i][] = "LA";
                            $leavedayCount++;
                        } else {
                            if($attendances[$user->userID]->$atten == NULL) { 
                                $bodys[$i][] = "N/A";
                            } elseif($attendances[$user->userID]->$atten == 'P') {
                                $bodys[$i][] = $attendances[$user->userID]->$atten;
                                $presentCount++;
                            } elseif($attendances[$user->userID]->$atten == 'LE') {
                                $bodys[$i][] = $attendances[$user->userID]->$atten;
                                $lateexcuseCount++;
                            } elseif($attendances[$user->userID]->$atten == 'L') {
                                $bodys[$i][] = $attendances[$user->userID]->$atten;
                                $lateCount++;
                            } elseif($attendances[$user->userID]->$atten == 'A') {
                                $bodys[$i][] = $attendances[$user->userID]->$atten;
                                $absentCount++;
                            }
                        }
                    }
                } else {
                    for($j=1; $j <= $getDayOfMonth; $j++) { 
                        $atten = "a".$j; 
                        $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                        
                        if(in_array($currentDate, $userleaveapplications)) {
                            $bodys[$i][] = "LA";
                            $leavedayCount++;
                        } else {
                            $bodys[$i][] = "N/A";
                        }
                    } 
                }
                $bodys[$i][] = $leavedayCount;
                $bodys[$i][] = $presentCount;
                $bodys[$i][] = $lateexcuseCount;
                $bodys[$i][] = $lateCount;
                $bodys[$i][] = $absentCount;
            }

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
            redirect(site_url('attendanceoverviewreport'));
        }
    }

    private function queryArray($posts) {
        $roleID  = $posts['roleID'];
        $userID  = $posts['userID'];
        $month   = $posts['month'];

        $queryArray = [];
        $userArray  = [];
        if($roleID) {
            $queryArray['roleID']  = $roleID;
            $userArray['roleID']   = $roleID;
            if($userID) {
                $queryArray['userID']  = $userID;
                $userArray['userID']   = $userID;
            }
        }
        $queryArray['monthyear']  = $month;
        $userArray['status']      = 1;
        $userArray['roleID !=']   = 3;


        $this->data['attendances']  = pluck($this->attendance_m->get_order_by_attendance($queryArray), 'obj', 'userID');
        $this->data['users']        = pluck($this->user_m->get_order_by_user($userArray),'obj','userID');
        $this->data['roles']        = pluck($this->role_m->get_select_role(),'role','roleID');

        $this->data['leaveapplications'] = $this->leave_applications_date_list($queryArray);

        $this->data['roleID']   = ($roleID) ? $roleID : 0;
        $this->data['userID']   = ($userID) ? $userID : 0;
        $this->data['month']    = strtotime('01-'.$month);
        $this->data['monthday'] = explode('-', $month);
        $this->data['monthdays']= $month;

        $roles = $this->data['roles'];
        $users = $this->data['users'];
        $this->data['label_role']  = $this->lang->line('attendanceoverviewreport_role')." : ".(isset($roles[$roleID]) ? $roles[$roleID] : $this->lang->line('attendanceoverviewreport_all'));
        $this->data['label_user']  = $this->lang->line('attendanceoverviewreport_user')." : ".(isset($users[$userID]) ? $users[$userID]->name : '');
        $this->data['label_month'] = $this->lang->line('attendanceoverviewreport_month')." : ".(date('F Y', strtotime('01-'.$month)));
    }

    public function get_user() {
        echo "<option value='0'>— ".$this->lang->line('attendanceoverviewreport_please_select')." —</option>";
        if($_POST) {
            $roleID = $this->input->post('roleID');
            if((int)$roleID) {
                $users = $this->user_m->get_order_by_user(array('status'=>1, 'roleID' => $roleID));
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
                'label' => $this->lang->line("attendanceoverviewreport_report_for"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("attendanceoverviewreport_user"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'month',
                'label' => $this->lang->line("attendanceoverviewreport_month"),
                'rules' => 'trim|required|callback_month_valid'
            )
        );
        return $rules;
    }

    protected function send_pdf_to_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("attendanceoverviewreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("attendanceoverviewreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("attendanceoverviewreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("attendanceoverviewreport_report_for"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("attendanceoverviewreport_user"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'month',
                'label' => $this->lang->line("attendanceoverviewreport_month"),
                'rules' => 'trim|required|callback_month_valid'
            )
        );
        return $rules;
    }

    public function month_valid() {
        $month = "01-".$this->input->post('month');
        if($month) {
            if(strlen($month) < 10) {
                $this->form_validation->set_message("month_valid", "The %s is not valid dd-mm-yyyy");
                return FALSE;
            } else {
                $arr  = explode("-", $month);
                $dd   = $arr[0];
                $mm   = $arr[1];
                $yyyy = $arr[2];
                if(checkdate($mm, $dd, $yyyy)) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message("month_valid", "The %s is not valid dd-mm-yyyy");
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    private function leave_applications_date_list($array) {
        $queryArray = [];
        if(isset($array['roleID']) && $array['roleID']) {
            $queryArray['create_roleID'] = $array['roleID'];
            if(isset($array['userID']) && $array['userID']) {
                $queryArray['create_userID'] = $array['userID'];
            }
        }

        $currentyear = date('Y');
        if(isset($array['monthyear']) && $array['monthyear']) {
            $monthyearArr = explode('-', $array['monthyear']);
            $currentyear  = isset($monthyearArr[1]) ? $monthyearArr[1] : date('Y');
        }
        $queryArray['status'] = 1;
        $queryArray['year']   = $currentyear;

        $leaveapplications = $this->leaveapplication_m->get_order_by_leaveapplication($queryArray);

        $retArray = [];
        if(inicompute($leaveapplications)) {
            $oneday    = 60*60*24;
            foreach($leaveapplications as $leaveapplication) {
                for($i=strtotime($leaveapplication->from_date); $i<= strtotime($leaveapplication->to_date); $i= $i+$oneday) {
                    $retArray[$leaveapplication->create_userID][] = date('d-m-Y', $i);
                }
            }
        }
        return $retArray;
    }

}