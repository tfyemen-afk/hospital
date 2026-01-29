<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expensereport extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('expense_m');
        $this->load->model('medicinepurchasepaid_m');
        $this->load->model('makepayment_m');
        $this->load->library('report');

		$language = $this->session->userdata('lang');;
		$this->lang->load('expensereport', $language);
	}

	public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
            ),
            'js' => array(
                'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                'assets/inilabs/report/expense/index.js'
            )
        );
        $this->data["subview"] = "report/expense/ExpenseReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getexpensereport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('expensereport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/expense/ExpenseReport', $this->data,true);
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
        if(permissionChecker('expensereport')) {
            $from_date    = htmlentities(escapeString($this->uri->segment(3)));
            $to_date      = htmlentities(escapeString($this->uri->segment(4)));
            if(((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'expensereport.css', 'data' => $this->data, 'viewpath' => 'report/expense/ExpenseReportPDF']);
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
        if(permissionChecker('expensereport')) {
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
            header('Content-Disposition: attachment;filename="expensereport.xlsx"');
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
            redirect(site_url('expensereport'));
        }
    }

    private function xmlData() {
        if(permissionChecker('expensereport')) {
            $from_date   = htmlentities(escapeString($this->uri->segment(3)));
            $to_date     = htmlentities(escapeString($this->uri->segment(4)));

            if(((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['from_date']  = ($from_date) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']    = ($to_date) ? date('d-m-Y', $to_date) : 0;

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('expensereport'));
            }
        } else {
            redirect(site_url('expensereport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($totalexpenses)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'D';
            if($from_date && $to_date) { 
                $from_date = $this->lang->line('expensereport_from_date')." : ".date('d M Y', $from_date);
                $to_date   = $this->lang->line('expensereport_to_date')." : ".date('d M Y', $to_date);

                $sheet->setCellValue('A'.$row, $from_date);
                $sheet->setCellValue('D'.$row, $to_date);
                // Top Row Merge
                $sheet->mergeCells("B1:C1");
            } elseif($from_date) { 
                $from_date = $this->lang->line('expensereport_from_date')." : ".date('d M Y', $from_date);
                $sheet->setCellValue('A'.$row, $from_date);
                // Top Row Merge
                $sheet->mergeCells("B1:D1");
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers   = array();
            $headers[] = '#';
            $headers[] = $this->lang->line('expensereport_name');
            $headers[] = $this->lang->line('expensereport_date');
            $headers[] = $this->lang->line('expensereport_amount');

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
            foreach($totalexpenses as $totalexpense) {
                $bodys[$i][]   = ++$i;
                $bodys[$i][]   = $totalexpense['name'];
                $bodys[$i][]   = app_date($totalexpense['date'], FALSE);
                $bodys[$i][]   = number_format($totalexpense['amount'], 2);   
                
                $total_amount += $totalexpense['amount'];
            }
            $i++;

            $bodys[$i][]   = $this->lang->line('expensereport_grand_total'). (!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : '');
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
            $endmerge    = "C".$styleColumn;
            $sheet->mergeCells("$startmerge:$endmerge");

        } else {
            redirect(site_url('expensereport'));
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('expensereport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'expensereport.css', 'data' => $this->data, 'viewpath' => 'report/expense/ExpenseReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('expensereport_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('expensereport_permission');
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

        $expenses              = $this->expense_m->get_order_by_expense_for_report($queryArray);
        $medicinepurchasepaids = $this->medicinepurchasepaid_m->get_order_by_medicinepurchasepaid_for_report($queryArray);
        $makepayments          = $this->makepayment_m->get_order_by_makepayment_for_report($queryArray);

        $totalexpenses      = [];
        $i=0;
        if(inicompute($expenses)) {
            foreach($expenses as $expense) {
                $totalexpenses[$i]['name']      = $expense->name;
                $totalexpenses[$i]['date']      = $expense->date;
                $totalexpenses[$i]['amount']    = $expense->amount;
                $i++;
            }
        }
        if(inicompute($medicinepurchasepaids)) {
            foreach($medicinepurchasepaids as $medicinepurchasepaid) {
                $totalexpenses[$i]['name']      = 'Medicine Purchase';
                $totalexpenses[$i]['date']      = $medicinepurchasepaid->medicinepurchasepaiddate;
                $totalexpenses[$i]['amount']    = $medicinepurchasepaid->medicinepurchasepaidamount;
                $i++;
            }
        }
        if(inicompute($makepayments)) {
            foreach($makepayments as $makepayment) {
                $totalexpenses[$i]['name']      = 'Salary';
                $totalexpenses[$i]['date']      = $makepayment->create_date;
                $totalexpenses[$i]['amount']    = $makepayment->payment_amount;
                $i++;
            }
        }

        function date_compare($a, $b)
        {
            $t1 = strtotime($a['date']);
            $t2 = strtotime($b['date']);
            return $t1 - $t2;
        }    
        usort($totalexpenses, 'date_compare');
        $this->data['totalexpenses'] = $totalexpenses;
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("expensereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("expensereport_to_date"),
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
                'label' => $this->lang->line("expensereport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("expensereport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("expensereport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("expensereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("expensereport_to_date"),
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