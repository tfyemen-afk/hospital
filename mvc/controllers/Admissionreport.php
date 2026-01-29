<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admissionreport extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('admission_m');
        $this->load->model('user_m');
        $this->load->model('patient_m');
        $this->load->model('ward_m');
        $this->load->model('bed_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('admissionreport', $language);
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
                'assets/inilabs/report/admission/index.js'
            )
        );

        $this->data['doctors']  = $this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0));
        $this->data['patients'] = $this->patient_m->get_select_patient('patientID, name',array('delete_at' => 0));
        $this->data['wards']    = pluck($this->ward_m->get_select_ward('wardID, name'), 'obj', 'wardID');

        $this->data["subview"]  = "report/admission/AdmissionReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getadmissionreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('admissionreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());
                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/admission/AdmissionReport', $this->data,true);
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
        if(permissionChecker('admissionreport')) {
            $doctorID    = htmlentities(escapeString($this->uri->segment(3)));
            $patientID   = htmlentities(escapeString($this->uri->segment(4)));
            $wardID      = htmlentities(escapeString($this->uri->segment(5)));
            $bedID       = htmlentities(escapeString($this->uri->segment(6)));
            $casualty    = htmlentities(escapeString($this->uri->segment(7)));
            $status      = htmlentities(escapeString($this->uri->segment(8)));
            $from_date   = htmlentities(escapeString($this->uri->segment(9)));
            $to_date     = htmlentities(escapeString($this->uri->segment(10)));

            if(((int)$doctorID || $doctorID==0) && ((int)$patientID || $patientID == 0) && (((int)$wardID || $wardID==0) && ((int)$bedID || $bedID == 0)) && ((int)$casualty || $casualty == 0) && ((int)$status || $status == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['doctorID']   = $doctorID; 
                $qArray['patientID']  = $patientID; 
                $qArray['wardID']     = $wardID; 
                $qArray['bedID']      = $bedID; 
                $qArray['casualty']   = $casualty;
                $qArray['status']     = $status;
                $qArray['from_date']  = ($from_date) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']    = ($to_date) ? date('d-m-Y', $to_date) : 0;
                
                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'admissionreport.css', 'data' => $this->data, 'viewpath' => 'report/admission/AdmissionReportPDF']);
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
        if(permissionChecker('admissionreport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'admissionreport.css', 'data' => $this->data, 'viewpath' => 'report/admission/AdmissionReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('admissionreport_permission_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('admissionreport_method_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }
    
    public function xlsx() {
        if(permissionChecker('admissionreport')) {
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
            header('Content-Disposition: attachment;filename="admissionreport.xlsx"');
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
        if(permissionChecker('admissionreport')) {
            $doctorID    = htmlentities(escapeString($this->uri->segment(3)));
            $patientID   = htmlentities(escapeString($this->uri->segment(4)));
            $wardID      = htmlentities(escapeString($this->uri->segment(5)));
            $bedID       = htmlentities(escapeString($this->uri->segment(6)));
            $casualty    = htmlentities(escapeString($this->uri->segment(7)));
            $status      = htmlentities(escapeString($this->uri->segment(8)));
            $from_date   = htmlentities(escapeString($this->uri->segment(9)));
            $to_date     = htmlentities(escapeString($this->uri->segment(10)));

            if(((int)$doctorID || $doctorID==0) && ((int)$patientID || $patientID == 0) && (((int)$wardID || $wardID==0) && ((int)$bedID || $bedID == 0)) && ((int)$casualty || $casualty == 0) && ((int)$status || $status == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['doctorID']   = $doctorID; 
                $qArray['patientID']  = $patientID; 
                $qArray['wardID']     = $wardID; 
                $qArray['bedID']      = $bedID; 
                $qArray['casualty']   = $casualty;
                $qArray['status']     = $status;
                $qArray['from_date']  = ($from_date) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']    = ($to_date) ? date('d-m-Y', $to_date) : 0;

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('admissionreport'));
            }
        } else {
            redirect(site_url('admissionreport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($admissions)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'H';
            $topCellMerge = TRUE;

            $leftvalue = '';
            if($from_date) {
                $leftvalue =  $this->lang->line('admissionreport_from_date')." : ".date('d M Y',$from_date);
            } elseif($doctorID) {
                $leftvalue =  $this->lang->line('admissionreport_doctor')." : ".(isset($doctors[$doctorID]) ? $doctors[$doctorID] : '&nbsp;');
            } elseif($wardID) {
                $leftvalue =  $this->lang->line('admissionreport_ward')." : ".(isset($wards[$wardID]) ? $wards[$wardID] : '&nbsp;');
            } elseif($casualty) {
                $leftvalue =  $this->lang->line('admissionreport_casualty')." : ".(($casualty==2) ? $this->lang->line('admissionreport_yes') : $this->lang->line('admissionreport_no'));
            }
            if($leftvalue != '') {
                $sheet->setCellValue('A'.$row, $leftvalue);
                $topCellMerge = FALSE;
            }

            $rightvalue = '';
            if($to_date ) {
                $rightvalue = $this->lang->line('admissionreport_to_date')." : ".date('d M Y',$to_date);
            } elseif($patientID) {
                $rightvalue = $this->lang->line('admissionreport_patient')." : ".(isset($patients[$patientID]) ? $patients[$patientID] : '&nbsp;');
            } elseif($bedID) {
                $rightvalue = $this->lang->line('admissionreport_bed')." : ".(isset($beds[$bedID]) ? $beds[$bedID] : '&nbsp;');
            } elseif($status) {
                $rightvalue = $this->lang->line('admissionreport_status')." : ".(($status==2) ? $this->lang->line('admissionreport_release') : $this->lang->line('admissionreport_admitted'));
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
            $headers[] = $this->lang->line('admissionreport_doctor');
            $headers[] = $this->lang->line('admissionreport_patient');
            $headers[] = $this->lang->line('admissionreport_ward');
            $headers[] = $this->lang->line('admissionreport_bed');
            $headers[] = $this->lang->line('admissionreport_casualty');
            $headers[] = $this->lang->line('admissionreport_status');
            $headers[] = $this->lang->line('admissionreport_admission_date');

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
            foreach($admissions as $admission) {
                $bodys[$i][]   = ++$i;
                $bodys[$i][]   = isset($doctors[$admission->doctorID]) ? $doctors[$admission->doctorID] : '';
                $bodys[$i][]   = isset($patients[$admission->patientID]) ? $patients[$admission->patientID] : '';    
                $bodys[$i][]   = isset($wards[$admission->wardID]) ? $wards[$admission->wardID] : '';
                $bodys[$i][]   = isset($beds[$admission->bedID]) ? $beds[$admission->bedID] : '';
                $bodys[$i][]   = ($admission->casualty==1) ? $this->lang->line('admissionreport_yes') : $this->lang->line('admissionreport_no') ;
                $bodys[$i][]   = ($admission->status==1) ? $this->lang->line('admissionreport_release') : $this->lang->line('admissionreport_admitted');
                $bodys[$i][]   = app_datetime($admission->admissiondate);
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
            redirect(site_url('admissionreport'));
        }
    }

    private function queryArray($posts) {
        $doctorID   = $posts['doctorID'];
        $patientID  = $posts['patientID'];
        $wardID     = $posts['wardID'];
        $bedID      = $posts['bedID'];
        $casualty   = $posts['casualty'];
        $status     = $posts['status'];
        $from_date  = $posts['from_date'];
        $to_date    = $posts['to_date'];

        $queryArray = [];
        $queryArray['doctorID']  = $doctorID;
        $queryArray['patientID'] = $patientID;
        if($wardID) {
            $queryArray['wardID'] = $wardID;
            if($bedID) {
                $queryArray['bedID'] = $bedID;
            }
        }

        if($casualty) {
            $queryArray['casualty'] = $casualty-1;
        }
        if($status) {
            $queryArray['status']   = $status-1;
        }

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
        $this->data['wardID']      = $wardID;
        $this->data['bedID']       = $bedID;
        $this->data['casualty']    = $casualty;
        $this->data['status']      = $status;
        $this->data['from_date']   = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']     = ($to_date) ? strtotime($to_date) : 0;

        $this->data['admissions']  = $this->admission_m->get_admission_for_report($queryArray);
        $this->data['doctors']     = pluck($this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0)),'name','userID');
        $this->data['patients']    = pluck($this->patient_m->get_select_patient('patientID, name',array('delete_at' => 0)),'name', 'patientID');
        $this->data['wards']       = pluck($this->ward_m->get_select_ward('wardID, name'), 'name', 'wardID');
        $this->data['beds']        = pluck($this->bed_m->get_select_bed('bedID, name'), 'name', 'bedID');
    }

    public function get_bed() {
        echo "<option value='0'>— ".$this->lang->line('admissionreport_please_select')." —</option>";
        if($_POST) {
            $wardID = $this->input->post('wardID');
            if((int)$wardID) {
                $beds = $this->bed_m->get_order_by_bed(array('wardID'=>$wardID));
                if(inicompute($beds)) {
                    foreach ($beds as $bed) {
                        echo "<option value='".$bed->bedID."'>".$bed->name."</option>";
                    }
                }
            }
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'doctorID',
                'label' => $this->lang->line("admissionreport_doctor"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("admissionreport_patient"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'wardID',
                'label' => $this->lang->line("admissionreport_ward"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'bedID',
                'label' => $this->lang->line("admissionreport_bed"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'casualty',
                'label' => $this->lang->line("admissionreport_casualty"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("admissionreport_status"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("admissionreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("admissionreport_to_date"),
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
                'label' => $this->lang->line("admissionreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("admissionreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("admissionreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'doctorID',
                'label' => $this->lang->line("admissionreport_doctor"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("admissionreport_patient"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'wardID',
                'label' => $this->lang->line("admissionreport_ward"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'bedID',
                'label' => $this->lang->line("admissionreport_bed"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'casualty',
                'label' => $this->lang->line("admissionreport_casualty"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("admissionreport_status"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("admissionreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("admissionreport_to_date"),
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