<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attendancereport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();

        $this->load->model('user_m');
        $this->load->model('designation_m');
        $this->load->model('attendance_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('attendancereport', $language);
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
                'assets/inilabs/report/attendance/index.js',
            )
        );

        $this->data["subview"]  = "report/attendance/AttendanceReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getAttendancereport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('attendancereport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/attendance/AttendanceReport', $this->data,true);
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
        if(permissionChecker('attendancereport')) {
            $attendancetype = htmlentities(escapeString($this->uri->segment(3)));
            $date   = htmlentities(escapeString($this->uri->segment(4)));
            
            if(((int)$attendancetype || $attendancetype==0) && ((int)$date || $date == 0)) {
                
                $qArray['attendancetype']= $attendancetype; 
                $qArray['date']  = ($date) ? date('d-m-Y', $date) : 0; 
                
                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'attendancereport.css', 'data' => $this->data, 'viewpath' => 'report/attendance/AttendanceReportPDF']);
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
        if(permissionChecker('attendancereport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'attendancereport.css', 'data' => $this->data, 'viewpath' => 'report/attendance/AttendanceReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('attendancereport_permission_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('attendancereport_method_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }
    
    public function xlsx() {
        if(permissionChecker('attendancereport')) {
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
            header('Content-Disposition: attachment;filename="attendancereport.xlsx"');
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
        if(permissionChecker('attendancereport')) {
            $attendancetype = htmlentities(escapeString($this->uri->segment(3)));
            $date   = htmlentities(escapeString($this->uri->segment(4)));
            
            if(((int)$attendancetype || $attendancetype==0) && ((int)$date || $date == 0)) {
                
                $qArray['attendancetype']= $attendancetype; 
                $qArray['date']  = ($date) ? date('d-m-Y', $date) : 0;

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('attendancereport'));
            }
        } else {
            redirect(site_url('attendancereport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($users)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'F';
            $topCellMerge = TRUE;

            $leftvalue     = $this->lang->line('attendancereport_attendance_type')." : ".$attendance_type;
            $sheet->setCellValue('A'.$row, $leftvalue);

            $rightvalue = $this->lang->line('attendancereport_date')." : ".date('d M Y',$date);
            $sheet->setCellValue($lastcolumn.$row, $rightvalue);

            $sheet->mergeCells("B1:E1");

            $headers   = array();
            $headers[] = '#';
            $headers[] = $this->lang->line('attendancereport_photo');
            $headers[] = $this->lang->line('attendancereport_name');
            $headers[] = $this->lang->line('attendancereport_designation');
            $headers[] = $this->lang->line('attendancereport_email');
            $headers[] = $this->lang->line('attendancereport_phone');

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
            foreach($users as $user) {
                $attendanceDay = isset($attendances[$user->userID]) ? $attendances[$user->userID]->$aday : '';
                if($attendancetype == 'P' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                    continue;
                } elseif($attendancetype == 'LE' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='P' || $attendanceDay =='L' )) {
                    continue;
                } elseif($attendancetype == 'L' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='P' )) {
                    continue;
                } elseif($attendancetype == 'A' && ($attendanceDay == 'P' || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                    continue;
                } 

                $bodys[$i][]   = ++$i;
                $bodys[$i][]   = $user->photo;
                $bodys[$i][]   = $user->name;
                $bodys[$i][]   = isset($designations[$user->designationID]) ? $designations[$user->designationID] : '';
                $bodys[$i][]   = $user->email;
                $bodys[$i][]   = $user->phone;
            }

            if(inicompute($bodys)) {
                $row = 3;
                foreach($bodys as $single_rows) {
                    $column = 'A';
                    foreach($single_rows as $key=> $value) {
                        if($key == 1) {
                            if (file_exists(FCPATH.'uploads/user/'.$value)) {
                                $this->phpspreadsheet->draw_images(FCPATH.'uploads/user/'.$value, $column.$row,$sheet, 40);
                            } else {
                                $this->phpspreadsheet->draw_images(FCPATH.'uploads/user/default.png', $column.$row,$sheet, 40);
                            }
                        } else {
                            $sheet->setCellValue($column.$row, $value);
                        }
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
            redirect(site_url('attendancereport'));
        }
    }

    private function queryArray($posts) {
        $attendancetype = $posts['attendancetype'];
        $date  = $posts['date'];
        
        $dateArray  = explode('-',$date);
        $aday        = 'a'.(int)$dateArray[0];
        $monthyear  = $dateArray[1].'-'.$dateArray[2];

        $queryArray = [];
        $queryArray['monthyear']  = $monthyear;


        $this->data['attendances']  = pluck($this->attendance_m->get_order_by_attendance($queryArray), 'obj', 'userID');
        $this->data['users']        = $this->user_m->get_order_by_user(array('status'=>1, 'roleID !=' => 3));
        $this->data['designations'] = pluck($this->designation_m->get_select_designation(),'designation','designationID');

        $this->data['attendancetype'] = $attendancetype;
        $this->data['aday']             = $aday;
        $this->data['date']             = ($date) ? strtotime($date) : 0;
        
        $attendancetypeArray['P'] = $this->lang->line('attendancereport_present');
        $attendancetypeArray['LE'] = $this->lang->line('attendancereport_late_present_with_excuse');
        $attendancetypeArray['L'] = $this->lang->line('attendancereport_late_present');
        $attendancetypeArray['A'] = $this->lang->line('attendancereport_absent');

        $this->data['attendance_type']  = isset($attendancetypeArray[$attendancetype]) ? $attendancetypeArray[$attendancetype] : '';
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'attendancetype',
                'label' => $this->lang->line("attendancereport_attendance_type"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("attendancereport_date"),
                'rules' => 'trim|required|callback_date_valid'
            )
        );
        return $rules;
    }

    protected function send_pdf_to_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("attendancereport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("attendancereport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("attendancereport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'attendancetype',
                'label' => $this->lang->line("attendancereport_attendance_type"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("attendancereport_date"),
                'rules' => 'trim|required|callback_date_valid'
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
                $arr  = explode("-", $date);
                $dd   = $arr[0];
                $mm   = $arr[1];
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