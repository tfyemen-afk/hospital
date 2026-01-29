<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Incomereport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('income_m');
        $this->load->model('medicinesalepaid_m');
        $this->load->model('billpayment_m');
        $this->load->model('ambulance_m');
        $this->load->model('ambulancecall_m');
        $this->load->library('report');

		$language = $this->session->userdata('lang');;
		$this->lang->load('incomereport', $language);
	}

	

	public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
            ),
            'js' => array(
                'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                'assets/inilabs/report/income/index.js'
            )
        );
        $this->data["subview"] = "report/income/IncomeReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getincomereport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('incomereport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/income/IncomeReport', $this->data,true);
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
        if(permissionChecker('incomereport')) {
            $from_date    = htmlentities(escapeString($this->uri->segment(3)));
            $to_date      = htmlentities(escapeString($this->uri->segment(4)));
            if(((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'incomereport.css', 'data' => $this->data, 'viewpath' => 'report/income/IncomeReportPDF']);
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
        if(permissionChecker('incomereport')) {
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
            header('Content-Disposition: attachment;filename="incomereport.xlsx"');
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
        if(permissionChecker('incomereport')) {
            $from_date   = htmlentities(escapeString($this->uri->segment(3)));
            $to_date     = htmlentities(escapeString($this->uri->segment(4)));

            if(((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('incomereport'));
            }
        } else {
            redirect(site_url('incomereport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($totalincomes)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'E';
            if($from_date && $to_date) { 
                $from_date = $this->lang->line('incomereport_from_date')." : ".date('d M Y', $from_date);
                $to_date   = $this->lang->line('incomereport_to_date')." : ".date('d M Y', $to_date);

                $sheet->setCellValue('A'.$row, $from_date);
                $sheet->setCellValue('E'.$row, $to_date);

                // Top Row Merge
                $sheet->mergeCells("B1:D1");
            } elseif($from_date) {
                $from_date = $this->lang->line('incomereport_from_date')." : ".date('d M Y', $from_date);
                $sheet->setCellValue('A'.$row, $from_date);
                $sheet->mergeCells("B1:E1");
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers   = array();
            $headers[] = '#';
            $headers[] = $this->lang->line('incomereport_name');
            $headers[] = $this->lang->line('incomereport_type');
            $headers[] = $this->lang->line('incomereport_date');
            $headers[] = $this->lang->line('incomereport_amount');

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
            foreach($totalincomes as $totalincome) {
                $bodys[$i][]   = ++$i;
                $bodys[$i][]   = $totalincome['name'];
                $bodys[$i][]   = $totalincome['type'];
                $bodys[$i][]   = app_date($totalincome['date'], FALSE);
                $bodys[$i][]   = number_format($totalincome['amount'], 2);   
                
                $total_amount += $totalincome['amount'];
            }
            $i++;

            $bodys[$i][]   = $this->lang->line('incomereport_grand_total'). (!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : '');
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
            $endmerge    = "D".$styleColumn;
            $sheet->mergeCells("$startmerge:$endmerge");
        } else {
            redirect(site_url('incomereport'));
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('incomereport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'incomereport.css', 'data' => $this->data, 'viewpath' => 'report/income/IncomeReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('incomereport_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('incomereport_permission');
        }
        echo json_encode($retArray);
        exit;
    }

    private function queryArray($posts) {
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

        $patienttypes     = [5 => $this->lang->line('incomereport_register'), 0 => $this->lang->line('incomereport_opd'), 1 => $this->lang->line('incomereport_ipd')];
        $patient_types    = array(
            0 => $this->lang->line('incomereport_opd'),
            1 => $this->lang->line('incomereport_ipd'),
            2 => $this->lang->line('incomereport_none'),
        );

        $incomes           = $this->income_m->get_order_by_income_for_report($queryArray);
        $medicinesalepaids = $this->medicinesalepaid_m->get_order_by_medicinesalepaid_for_report($queryArray);
        $billpayments      = $this->billpayment_m->get_order_by_billpayment_for_report($queryArray);
        $ambulancecalls    = $this->ambulancecall_m->get_ambulancecall_for_report($queryArray);
        $ambulances        = pluck($this->ambulance_m->get_ambulance(),'name', 'ambulanceID');


        $totalincomes      = [];
        $i=0;
        if(inicompute($incomes)) {
            foreach($incomes as $income) {
                $totalincomes[$i]['name']      = $income->name;
                $totalincomes[$i]['type']      = 'Income';
                $totalincomes[$i]['date']      = $income->date;
                $totalincomes[$i]['amount']    = $income->amount;
                $i++;
            }
        }
        if(inicompute($medicinesalepaids)) {
            foreach($medicinesalepaids as $medicinesalepaid) {
                $totalincomes[$i]['name']      = (isset($patient_types[$medicinesalepaid->patient_type]) ? $patient_types[$medicinesalepaid->patient_type] : '') . ' - '. $medicinesalepaid->uhid;
                $totalincomes[$i]['type']      = 'Medicine Sale';
                $totalincomes[$i]['date']      = $medicinesalepaid->medicinesalepaiddate;
                $totalincomes[$i]['amount']    = $medicinesalepaid->medicinesalepaidamount;
                $i++;
            }
        }
        if(inicompute($billpayments)) {
            foreach($billpayments as $billpayment) {
                $totalincomes[$i]['name']      = (isset($patienttypes[$billpayment->patienttypeID]) ? $patienttypes[$billpayment->patienttypeID] : '')." - ". $billpayment->patientID;
                $totalincomes[$i]['type']      = 'Bill';
                $totalincomes[$i]['date']      = $billpayment->paymentdate;
                $totalincomes[$i]['amount']    = $billpayment->paymentamount;
                $i++;
            }
        }
        if(inicompute($ambulancecalls)) {
            foreach($ambulancecalls as $ambulancecall) {
                if($ambulancecall->amount > 0) {
                    $totalincomes[$i]['name']      = (isset($ambulances[$ambulancecall->ambulanceID]) ? $ambulances[$ambulancecall->ambulanceID] : '')." - ".$ambulancecall->drivername;
                    $totalincomes[$i]['type']      = 'Ambulance Call';
                    $totalincomes[$i]['date']      = $ambulancecall->date;
                    $totalincomes[$i]['amount']    = $ambulancecall->amount;
                    $i++;
                }
            }
        }

        function date_compare($a, $b)
        {
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        }    
        usort($totalincomes, 'date_compare');
        $this->data['totalincomes'] = $totalincomes;
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("incomereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("incomereport_to_date"),
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
                'label' => $this->lang->line("incomereport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("incomereport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("incomereport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("incomereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("incomereport_to_date"),
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