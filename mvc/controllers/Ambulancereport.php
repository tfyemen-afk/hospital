<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ambulancereport extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('ambulance_m');
        $this->load->model('ambulancecall_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('ambulancereport', $language);
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
                'assets/inilabs/report/ambulance/index.js'
            )
        );
        $this->data['ambulances']  = pluck($this->ambulance_m->get_select_ambulance(),'name','ambulanceID');

        $this->data["subview"]  = "report/ambulance/AmbulanceReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getambulancereport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('ambulancereport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/ambulance/AmbulanceReport', $this->data,true);
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
        if(permissionChecker('ambulancereport')) {
            $ambulanceID = htmlentities(escapeString($this->uri->segment(3)));
            $from_date   = htmlentities(escapeString($this->uri->segment(4)));
            $to_date     = htmlentities(escapeString($this->uri->segment(5)));
            
            if(((int)$ambulanceID || $ambulanceID==0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {
                
                $qArray['ambulanceID']= $ambulanceID; 
                $qArray['from_date']  = ($from_date) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']    = ($to_date) ? date('d-m-Y', $to_date) : 0;
                
                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'ambulancereport.css', 'data' => $this->data, 'viewpath' => 'report/ambulance/AmbulanceReportPDF']);
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
        if(permissionChecker('ambulancereport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'ambulancereport.css', 'data' => $this->data, 'viewpath' => 'report/ambulance/AmbulanceReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('ambulancereport_permission_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('ambulancereport_method_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }
    
    public function xlsx() {
        if(permissionChecker('ambulancereport')) {
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
            header('Content-Disposition: attachment;filename="ambulancereport.xlsx"');
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
        if(permissionChecker('ambulancereport')) {
            $ambulanceID = htmlentities(escapeString($this->uri->segment(3)));
            $from_date   = htmlentities(escapeString($this->uri->segment(4)));
            $to_date     = htmlentities(escapeString($this->uri->segment(5)));

            if(((int)$ambulanceID || $ambulanceID==0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['ambulanceID']= $ambulanceID; 
                $qArray['from_date']  = ($from_date) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']    = ($to_date) ? date('d-m-Y', $to_date) : 0;

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('ambulancereport'));
            }
        } else {
            redirect(site_url('ambulancereport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($ambulancecalls)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'G';
            $topCellMerge = TRUE;

            $leftvalue     = '';
            if($from_date) { $f=FALSE;
                $leftvalue = $this->lang->line('ambulancereport_from_date')." : ".date('d M Y',$from_date);
            }
            if($leftvalue != '') {
                $sheet->setCellValue('A'.$row, $leftvalue);
                $topCellMerge = FALSE;
            }

            $rightvalue     = '';
            if($to_date) {
                $rightvalue = $this->lang->line('ambulancereport_to_date')." : ".date('d M Y',$to_date);
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
                    $sheet->mergeCells("B1:F1");
                }  else {
                    $sheet->mergeCells("B1:G1");
                }
            }

            $headers   = array();
            $headers[] = '#';
            $headers[] = $this->lang->line('ambulancereport_ambulance_name');
            $headers[] = $this->lang->line('ambulancereport_driver_name');
            $headers[] = $this->lang->line('ambulancereport_patient_name');
            $headers[] = $this->lang->line('ambulancereport_patient_contact');
            $headers[] = $this->lang->line('ambulancereport_date');
            $headers[] = $this->lang->line('ambulancereport_amount');

            if(inicompute($headers)) {
                $column = "A";
                $row    = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }

            $i     = 0;
            $total_amount = 0;
            $bodys = [];
            foreach($ambulancecalls as $ambulancecall) {
                $bodys[$i][]   = ++$i;
                $bodys[$i][]   = isset($ambulances[$ambulancecall->ambulanceID]) ? $ambulances[$ambulancecall->ambulanceID] : '&nbsp';
                $bodys[$i][]   = $ambulancecall->drivername;
                $bodys[$i][]   = $ambulancecall->patientname;
                $bodys[$i][]   = $ambulancecall->patientcontact;
                $bodys[$i][]   = app_date($ambulancecall->date);
                $bodys[$i][]   = number_format($ambulancecall->amount, 2);

                $total_amount += $ambulancecall->amount;   
            }
            $i++;
            $bodys[$i][]   = $this->lang->line('ambulancereport_grand_total'). (!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : '');
            $bodys[$i][]   = "";
            $bodys[$i][]   = "";
            $bodys[$i][]   = "";
            $bodys[$i][]   = "";
            $bodys[$i][]   = "";
            $bodys[$i][]   = number_format($total_amount, 2);

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
            $styleColumn = $lastcolumn.($row-2);
            $sheet->getStyle('A3:'.$styleColumn)->applyFromArray($styleArray);

            // After Two Row to Before Last Row
            $styleArray = [
                'font' => [
                    'bold' => TRUE,
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
            $styleColumn1 = 'A'.($row-1);
            $styleColumn  = $lastcolumn.($row-1);
            $sheet->getStyle($styleColumn1.':'.$styleColumn)->applyFromArray($styleArray);

            // Bottom Row Merge
            $styleColumn = $row-1;
            $startmerge  = "A".$styleColumn;
            $endmerge    = "F".$styleColumn;
            $sheet->mergeCells("$startmerge:$endmerge");
        } else {
            redirect(site_url('ambulancereport'));
        }
    }

    private function queryArray($posts) {
        $ambulanceID= $posts['ambulanceID'];
        $from_date  = $posts['from_date'];
        $to_date    = $posts['to_date'];

        $queryArray = [];
        if($ambulanceID) {
            $queryArray['ambulanceID']  = $ambulanceID;
        }

        if($from_date && $to_date) {
            $from_date_str   = strtotime($from_date);
            $to_date_str     = strtotime($to_date);
            if($to_date_str >= $from_date_str) {
                $queryArray['from_date']  = date('Y-m-d', $from_date_str);
                $queryArray['to_date']    = date('Y-m-d', $to_date_str);
            }
        } elseif($from_date) {
            $from_date_str   = strtotime($from_date);
            $queryArray['from_date']  = date('Y-m-d', $from_date_str);
            $queryArray['to_date']    = date('Y-m-d');
        }

        $this->data['ambulanceID'] = $ambulanceID;
        $this->data['from_date']   = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']     = ($to_date) ? strtotime($to_date) : 0;

        $this->data['ambulancecalls'] = $this->ambulancecall_m->get_ambulancecall_for_report($queryArray);
        $this->data['ambulances']     = pluck($this->ambulance_m->get_select_ambulance(),'name','ambulanceID');
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'ambulanceID',
                'label' => $this->lang->line("ambulancereport_ambulance"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("ambulancereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("ambulancereport_to_date"),
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
                'label' => $this->lang->line("ambulancereport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("ambulancereport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("ambulancereport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'ambulanceID',
                'label' => $this->lang->line("ambulancereport_ambulance"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("ambulancereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("ambulancereport_to_date"),
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