<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dischargereport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('admission_m');
        $this->load->model('discharge_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('dischargereport', $language);
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
                'assets/inilabs/report/discharge/index.js'
            )
        );

        $this->data["subview"] = "report/discharge/DischargeReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getdischargereport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('dischargereport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/discharge/DischargeReport', $this->data,true);
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
        if(permissionChecker('dischargereport')) {
            $conditionofdischarge = htmlentities(escapeString($this->uri->segment(3)));
            $from_date  = htmlentities(escapeString($this->uri->segment(4)));
            $to_date    = htmlentities(escapeString($this->uri->segment(5)));
            if(((int)$conditionofdischarge || $conditionofdischarge == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {
                $qArray['conditionofdischarge']  = $conditionofdischarge;
                $qArray['from_date'] = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']   = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'dischargereport.css', 'data' => $this->data, 'viewpath' => 'report/discharge/DischargeReportPDF']);
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
        if(permissionChecker('dischargereport')) {
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
            header('Content-Disposition: attachment;filename="dischargereport.xlsx"');
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
        if(permissionChecker('dischargereport')) {
            $conditionofdischarge = htmlentities(escapeString($this->uri->segment(3)));
            $from_date  = htmlentities(escapeString($this->uri->segment(4)));
            $to_date    = htmlentities(escapeString($this->uri->segment(5)));
            if(((int)$conditionofdischarge || $conditionofdischarge == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {
                $qArray['conditionofdischarge']  = $conditionofdischarge;
                $qArray['from_date'] = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']   = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('dischargereport'));
            }
        } else {
            redirect(site_url('dischargereport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($discharges)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $conditionofdischargeArray[1] = $this->lang->line('dischargereport_stable');
            $conditionofdischargeArray[2] = $this->lang->line('dischargereport_almost_stable');
            $conditionofdischargeArray[3] = $this->lang->line('dischargereport_own_risk');
            $conditionofdischargeArray[4] = $this->lang->line('dischargereport_unstable');

            $row          = 1;
            $lastcolumn   = 'E';

            $leftlabel  = "";
            $rightlabel = "";
            if($from_date && $to_date) { 
                $leftlabel =  $this->lang->line('dischargereport_from_date')." : ".$label_from_date;;
            } elseif($conditionofdischarge) {
                $leftlabel =  $this->lang->line('dischargereport_condition_of_discharge')." : ".$label_conditionofdischarge;;
            }

            if($from_date && $to_date) {
                $rightlabel = $this->lang->line('dischargereport_to_date')." : ".$label_to_date;
            } elseif($from_date) {
                $rightlabel = $this->lang->line('dischargereport_from_date')." : ".$label_from_date;
            } 

            if($leftlabel != '' && $rightlabel != '') {
                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->setCellValue('E'.$row, $rightlabel);
                $sheet->mergeCells("B1:D1");
            } elseif($leftlabel != '') {
                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->mergeCells("B1:E1");
            } elseif ($rightlabel != '') {
                $sheet->setCellValue('A'.$row, $rightlabel);
                $sheet->mergeCells("B1:E1");
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers[] = '#';
            $headers[] = $this->lang->line('dischargereport_patientID');
            $headers[] = $this->lang->line('dischargereport_name');
            $headers[] = $this->lang->line('dischargereport_condition_of_discharge');
            $headers[] = $this->lang->line('dischargereport_date');

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
            foreach($discharges as $discharge) {
                $bodys[$i][] = ++$i;
                $bodys[$i][] = $discharge->patientID;
                $bodys[$i][] = $discharge->name;
                $bodys[$i][] = isset($conditionofdischargeArray[$discharge->conditionofdischarge]) ? $conditionofdischargeArray[$discharge->conditionofdischarge] : '';
                $bodys[$i][] = app_date($discharge->date, false);
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
            redirect(site_url('dischargereport'));
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('dischargereport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'dischargereport.css', 'data' => $this->data, 'viewpath' => 'report/discharge/DischargeReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('dischargereport_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('dischargereport_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    private function queryArray($posts) {
        $conditionofdischarge = $posts['conditionofdischarge'];
        $from_date   = $posts['from_date'];
        $to_date     = $posts['to_date'];

        $queryArray = [];
        if($conditionofdischarge) {
            $queryArray['conditionofdischarge']  = $conditionofdischarge;
        }

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

        $this->data['discharges']     = $this->discharge_m->get_order_by_discharge_for_report($queryArray);

        $this->data['conditionofdischarge'] = $conditionofdischarge;
        $this->data['from_date'] = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']   = ($to_date) ? strtotime($to_date) : 0;


        $conditionofdischargeArray[1] = $this->lang->line('dischargereport_stable');
        $conditionofdischargeArray[2] = $this->lang->line('dischargereport_almost_stable');
        $conditionofdischargeArray[3] = $this->lang->line('dischargereport_own_risk');
        $conditionofdischargeArray[4] = $this->lang->line('dischargereport_unstable');

        $this->data['label_conditionofdischarge'] = isset($conditionofdischargeArray[$conditionofdischarge]) ? $conditionofdischargeArray[$conditionofdischarge] : '';
        $this->data['label_from_date']   = ($from_date) ? app_date($from_date, FALSE) : '';
        $this->data['label_to_date']     = ($to_date) ? app_date($to_date, FALSE) : '';
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'conditionofdischarge',
                'label' => $this->lang->line("dischargereport_condition_of_discharge"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("dischargereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("dischargereport_to_date"),
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
                'label' => $this->lang->line("dischargereport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("dischargereport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("dischargereport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'conditionofdischarge',
                'label' => $this->lang->line("dischargereport_condition_of_discharge"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("dischargereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("dischargereport_to_date"),
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

    public function unique_date_check() {
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