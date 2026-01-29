<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appointmentreport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('appointment_m');
        $this->load->model('user_m');
        $this->load->model('patient_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('appointmentreport', $language);
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
                'assets/inilabs/report/appointment/index.js'
            )
        );
        $this->data['doctors']  = $this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0));
        $this->data['patients'] = $this->patient_m->get_select_patient('patientID, name',array('delete_at' => 0));

        $this->data["subview"]  = "report/appointment/AppointmentReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getappointmentreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('appointmentreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/appointment/AppointmentReport', $this->data,true);
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
        if(permissionChecker('appointmentreport')) {
            $doctorID    = htmlentities(escapeString($this->uri->segment(3)));
            $patientID   = htmlentities(escapeString($this->uri->segment(4)));
            $casualty    = htmlentities(escapeString($this->uri->segment(5)));
            $payment     = htmlentities(escapeString($this->uri->segment(6)));
            $status      = htmlentities(escapeString($this->uri->segment(7)));
            $from_date   = htmlentities(escapeString($this->uri->segment(8)));
            $to_date     = htmlentities(escapeString($this->uri->segment(9)));

            if(((int)$doctorID || $doctorID==0) && ((int)$patientID || $patientID == 0) && ((int)$casualty || $casualty == 0) && ((int)$payment || $payment == 0) && ((int)$status || $status == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['doctorID']   = $doctorID; 
                $qArray['patientID']  = $patientID; 
                $qArray['casualty']   = $casualty;
                $qArray['payment']    = $payment;
                $qArray['status']     = $status;
                $qArray['from_date']  = ($from_date) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']    = ($to_date) ? date('d-m-Y', $to_date) : 0;
                
                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'appointmentreport.css', 'data' => $this->data, 'viewpath' => 'report/appointment/AppointmentReportPDF']);
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
        if(permissionChecker('appointmentreport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'appointmentreport.css', 'data' => $this->data, 'viewpath' => 'report/appointment/AppointmentReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('appointmentreport_permission_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('appointmentreport_method_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }
    
    public function xlsx() {
        if(permissionChecker('appointmentreport')) {
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
            header('Content-Disposition: attachment;filename="appointmentreport.xlsx"');
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
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function xmlData() {
        if(permissionChecker('appointmentreport')) {
            $doctorID    = htmlentities(escapeString($this->uri->segment(3)));
            $patientID   = htmlentities(escapeString($this->uri->segment(4)));
            $casualty    = htmlentities(escapeString($this->uri->segment(5)));
            $payment     = htmlentities(escapeString($this->uri->segment(6)));
            $status      = htmlentities(escapeString($this->uri->segment(7)));
            $from_date   = htmlentities(escapeString($this->uri->segment(8)));
            $to_date     = htmlentities(escapeString($this->uri->segment(9)));

            if(((int)$doctorID || $doctorID==0) && ((int)$patientID || $patientID == 0) && ((int)$casualty || $casualty == 0) && ((int)$payment || $payment == 0) && ((int)$status || $status == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['doctorID']   = $doctorID; 
                $qArray['patientID']  = $patientID; 
                $qArray['casualty']   = $casualty;
                $qArray['payment']    = $payment;
                $qArray['status']     = $status;
                $qArray['from_date']  = ($from_date) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']    = ($to_date) ? date('d-m-Y', $to_date) : 0;

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('appointmentreport'));
            }
        } else {
            redirect(site_url('appointmentreport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($appointments)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'H';
            $topCellMerge = TRUE;

            $leftvalue     = '';
            if($from_date) { $f=FALSE;
                $leftvalue = $this->lang->line('appointmentreport_from_date')." : ".date('d M Y',$from_date);
            } elseif($doctorID) { $f=FALSE;
                $leftvalue = $this->lang->line('appointmentreport_doctor')." : ".(isset($doctors[$doctorID]) ? $doctors[$doctorID] : '');
            } elseif($casualty) { $f=FALSE;
                $leftvalue = $this->lang->line('appointmentreport_casualty')." : ".(($casualty==2) ? $this->lang->line('appointmentreport_yes') : $this->lang->line('appointmentreport_no'));
            } elseif($status) { $f=FALSE;
                $leftvalue = $this->lang->line('appointmentreport_status')." : ".(($status==2) ? $this->lang->line('appointmentreport_visited') : $this->lang->line('appointmentreport_not_visited'));
            }
            if($leftvalue != '') {
                $sheet->setCellValue('A'.$row, $leftvalue);
                $topCellMerge = FALSE;
            }

            $rightvalue     = '';
            if($to_date) {
                $rightvalue = $this->lang->line('appointmentreport_to_date')." : ".date('d M Y',$to_date);
            } elseif($patientID) {
                $rightvalue = $this->lang->line('appointmentreport_patient')." : ".(isset($patients[$patientID]) ? $patients[$patientID] : '&nbsp;');
            } elseif($payment) {
                $rightvalue = $this->lang->line('appointmentreport_payment')." : ".(($payment==1) ? $this->lang->line('appointmentreport_paid') : $this->lang->line('appointmentreport_due'));
            } 

            if($leftvalue == '' && $rightvalue != '') {
                $sheet->setCellValue('A'.$row, $rightvalue);
                $topCellMerge = FALSE;
            } elseif($rightvalue != '') {
                $sheet->setCellValue($lastcolumn.$row, $rightvalue);
                $topCellMerge = FALSE;
            }

            if($topCellMerge) {
                $sheet->getRowDimension(1)->setVisible(false);
            } else {
                if($leftvalue != '' && $rightvalue != '') {
                    $sheet->mergeCells("B1:G1");
                }  else {
                    $sheet->mergeCells("B1:H1");
                }
            }

            $headers   = array();
            $headers[] = '#';
            $headers[] = $this->lang->line('appointmentreport_doctor');
            $headers[] = $this->lang->line('appointmentreport_patient');
            $headers[] = $this->lang->line('appointmentreport_casualty');
            $headers[] = $this->lang->line('appointmentreport_payment');
            $headers[] = $this->lang->line('appointmentreport_status');
            $headers[] = $this->lang->line('appointmentreport_appointment_date');
            $headers[] = $this->lang->line('appointmentreport_amount');

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
            foreach($appointments as $appointment) {
                $bodys[$i][]   = ++$i;
                $bodys[$i][]   = isset($doctors[$appointment->doctorID]) ? $doctors[$appointment->doctorID] : '';
                $bodys[$i][]   = isset($patients[$appointment->patientID]) ? $patients[$appointment->patientID] : '';
                $bodys[$i][]   = ($appointment->casualty==1) ? $this->lang->line('appointmentreport_yes') : $this->lang->line('appointmentreport_no');
                $bodys[$i][]   = ($appointment->paymentstatus==1) ? $this->lang->line('appointmentreport_paid') : $this->lang->line('appointmentreport_due');
                $bodys[$i][]   = ($appointment->status==1) ? $this->lang->line('appointmentreport_visited') : $this->lang->line('appointmentreport_not_visited');
                $bodys[$i][]   = app_datetime($appointment->appointmentdate);
                $bodys[$i][]   = number_format($appointment->amount, 2);   
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
            redirect(site_url('appointmentreport'));
        }
    }

    private function queryArray($posts) {
        $doctorID   = $posts['doctorID'];
        $patientID  = $posts['patientID'];
        $casualty   = $posts['casualty'];
        $payment    = $posts['payment'];
        $status     = $posts['status'];
        $from_date  = $posts['from_date'];
        $to_date    = $posts['to_date'];

        $queryArray = [];
        $queryArray['doctorID']  = $doctorID;
        $queryArray['patientID'] = $patientID;
        if($casualty) {
            $queryArray['casualty'] = $casualty-1;
        }
        if($status) {
            $queryArray['status']   = $status-1;
        }
        $queryArray['payment'] = $payment;

        if($from_date && $to_date) {
            $from_date_str   = strtotime($from_date);
            $to_date_str     = strtotime($to_date);
            if($to_date_str >= $from_date_str) {
                $queryArray['from_date']  = date('Y-m-d', $from_date_str);
                $queryArray['to_date']    = date('Y-m-d', $to_date_str);
            }
        } elseif($from_date) {
            $queryArray['from_date']  = date('Y-m-d', strtotime($from_date));
            $queryArray['to_date']    = date('Y-m-d');
        }

        $this->data['doctorID']    = $doctorID;
        $this->data['patientID']   = $patientID;
        $this->data['casualty']    = $casualty;
        $this->data['payment']     = $payment;
        $this->data['status']      = $status;
        $this->data['from_date']   = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']     = ($to_date) ? strtotime($to_date) : 0;

        $this->data['appointments']= $this->appointment_m->get_appointment_for_report($queryArray);
        $this->data['doctors']     = pluck($this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0)),'name','userID');
        $this->data['patients']    = pluck($this->patient_m->get_select_patient('patientID, name',array('delete_at' => 0)),'name', 'patientID');
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'doctorID',
                'label' => $this->lang->line("appointmentreport_doctor"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("appointmentreport_patient"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'casualty',
                'label' => $this->lang->line("appointmentreport_casualty"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'payment',
                'label' => $this->lang->line("appointmentreport_payment"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("appointmentreport_status"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("appointmentreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("appointmentreport_to_date"),
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
                'label' => $this->lang->line("appointmentreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("appointmentreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("appointmentreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'doctorID',
                'label' => $this->lang->line("appointmentreport_doctor"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("appointmentreport_patient"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'casualty',
                'label' => $this->lang->line("appointmentreport_casualty"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'payment',
                'label' => $this->lang->line("appointmentreport_payment"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("appointmentreport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("appointmentreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("appointmentreport_to_date"),
                'rules' => 'trim|callback_date_valid|callback_unique_date_check'
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

    public function unique_date_check() {
        $from_date = $this->input->post('from_date');
        $to_date   = $this->input->post('to_date');
        if($from_date != '' && $to_date != '') {
            if(strtotime($from_date) > strtotime($to_date)) {
                $this->form_validation->set_message("unique_date_check", "The to date can not be lower than from date .");
                return FALSE;
            }
        }
        return TRUE;
    }
    
}