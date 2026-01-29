<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinepurchasereport extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('medicinewarehouse_m');
        $this->load->model('medicinepurchase_m');
        $this->load->model('medicinepurchasepaid_m');

        $this->load->library('report');

		$language = $this->session->userdata('lang');;
		$this->lang->load('medicinepurchasereport', $language);
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
                'assets/inilabs/report/medicinepurchase/index.js'
            )
        );
        $this->data['medicinewarehouses']          = $this->medicinewarehouse_m->get_select_medicinewarehouse();
        
        $this->data["subview"] = "report/medicinepurchase/MedicinepurchaseReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getmedicinepurchasereport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('medicinepurchasereport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/medicinepurchase/MedicinepurchaseReport', $this->data,true);
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
        if(permissionChecker('medicinepurchasereport')) {
            $medicinewarehouseID = htmlentities(escapeString($this->uri->segment(3)));
            $reference_no = htmlentities(escapeString($this->uri->segment(4)));
            $statusID       = htmlentities(escapeString($this->uri->segment(5)));
            $from_date    = htmlentities(escapeString($this->uri->segment(6)));
            $to_date      = htmlentities(escapeString($this->uri->segment(7)));

            if(((int)$medicinewarehouseID || $medicinewarehouseID == 0) && ((string)$reference_no || $reference_no == 0) && ((int)$statusID || $statusID == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['medicinewarehouseID'] = $medicinewarehouseID; 
                $qArray['reference_no'] = ($reference_no) ? $reference_no : ''; 
                $qArray['statusID']       = $statusID;
                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'medicinepurchasereport.css', 'data' => $this->data, 'viewpath' => 'report/medicinepurchase/MedicinepurchaseReportPDF']);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('medicinepurchasereport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'medicinepurchasereport.css', 'data' => $this->data, 'viewpath' => 'report/medicinepurchase/MedicinepurchaseReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('medicinepurchasereport_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('medicinepurchasereport_permission');
        }
        echo json_encode($retArray);
        exit;
    }
    
    public function xlsx() {
        if(permissionChecker('medicinepurchasereport')) {
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
            header('Content-Disposition: attachment;filename="medicinepurchasereport.xlsx"');
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
        if(permissionChecker('medicinepurchasereport')) {
            $medicinewarehouseID = htmlentities(escapeString($this->uri->segment(3)));
            $reference_no = htmlentities(escapeString($this->uri->segment(4)));
            $statusID       = htmlentities(escapeString($this->uri->segment(5)));
            $from_date    = htmlentities(escapeString($this->uri->segment(6)));
            $to_date      = htmlentities(escapeString($this->uri->segment(7)));

            if(((int)$medicinewarehouseID || $medicinewarehouseID == 0) && ((string)$reference_no || $reference_no == 0) && ((int)$statusID || $statusID == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['medicinewarehouseID'] = $medicinewarehouseID; 
                $qArray['reference_no'] = ($reference_no) ? $reference_no : ''; 
                $qArray['statusID']       = $statusID;
                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('medicinepurchasereport'));
            }
        } else {
            redirect(site_url('medicinepurchasereport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($medicinepurchases)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            
            $row = 1;
            $topCellMerge = TRUE;
            if($from_date != 0 && $to_date != 0 ) { 
                $topCellMerge = FALSE;
                $datefrom   = $this->lang->line('medicinepurchasereport_from_date')." : ";
                $datefrom  .= date('d M Y',$from_date);
                $dateto     = $this->lang->line('medicinepurchasereport_to_date')." : ";
                $dateto    .= date('d M Y', $to_date);
             
                $sheet->setCellValue('A'.$row, $datefrom);
                $sheet->setCellValue('G'.$row, $dateto);
            } elseif($statusID != 0 ) {
                $statuslabel  = $this->lang->line('medicinepurchasereport_status')." : ";
                if($statusID == 1) {
                    $statuslabel .=  $this->lang->line("medicinepurchasereport_pending");
                } elseif($statusID == 2) {
                    $statuslabel .=  $this->lang->line("medicinepurchasereport_partial");
                } elseif($statusID == 3) {
                    $statuslabel .=  $this->lang->line("medicinepurchasereport_fully_paid");
                } elseif($statusID == 4) {
                    $statuslabel .=  $this->lang->line("medicinepurchasereport_refund");
                }
               
                $sheet->setCellValue('A'.$row, $statuslabel);
            } elseif($reference_no != 0) {
                $reference_no  = $this->lang->line('medicinepurchasereport_reference_no')." : ".$reference_no;

                $sheet->setCellValue('A'.$row, $reference_no);
            } elseif($medicinewarehouseID != 0) {
                $medicinepurchase  = $this->lang->line('medicinepurchasereport_warehouse')." : ";
                $medicinepurchase .= isset($medicinewarehouses[$medicinewarehouseID]) ? $medicinewarehouses[$medicinewarehouseID] : '';
                $sheet->setCellValue('A'.$row, $medicinepurchase);
            } elseif($from_date) {
                $datefrom   = $this->lang->line('medicinepurchasereport_from_date')." : ";
                $datefrom  .= date('d M Y',$from_date);
                $sheet->setCellValue('A'.$row, $datefrom);
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers   = array();
            $headers[] = $this->lang->line('medicinepurchasereport_slno');
            $headers[] = $this->lang->line('medicinepurchasereport_reference_no');
            $headers[] = $this->lang->line('medicinepurchasereport_warehouse');
            $headers[] = $this->lang->line('medicinepurchasereport_date');
            $headers[] = $this->lang->line('medicinepurchasereport_total');
            $headers[] = $this->lang->line('medicinepurchasereport_paid');
            $headers[] = $this->lang->line('medicinepurchasereport_balance');

            if(inicompute($headers)) {
                $column = "A";
                $row    = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }

            $totalmedicinepurchaseprice         = 0;
            $totalmedicinepurchasepaidamount    = 0;
            $totalmedicinepurchasebalanceamount = 0;
            $i     = 0;
            $bodys = [];
            foreach($medicinepurchases as $medicinepurchase) {
                $bodys[$i][]   = ++$i;
                $bodys[$i][]   = $medicinepurchase->medicinepurchasereferenceno;
                $bodys[$i][]   = isset($medicinewarehouses[$medicinepurchase->medicinewarehouseID]) ? $medicinewarehouses[$medicinepurchase->medicinewarehouseID] : '';
                $bodys[$i][]   = app_date($medicinepurchase->medicinepurchasedate);
                $bodys[$i][]   = number_format($medicinepurchase->totalamount, 2);
                $paidamount    = isset($medicinepurchasepaids[$medicinepurchase->medicinepurchaseID]) ? $medicinepurchasepaids[$medicinepurchase->medicinepurchaseID] : 0;
                $bodys[$i][]   = number_format($paidamount, 2);
                $balanceamount = $medicinepurchase->totalamount-$paidamount;
                $bodys[$i][]   = number_format($balanceamount, 2);
                
                $totalmedicinepurchaseprice         += $medicinepurchase->totalamount;
                $totalmedicinepurchasepaidamount    += $paidamount;
                $totalmedicinepurchasebalanceamount += $balanceamount;
                $i++;
            }
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = number_format($totalmedicinepurchaseprice,2);
            $bodys[$i][] = number_format($totalmedicinepurchasepaidamount,2);
            $bodys[$i][] = number_format($totalmedicinepurchasebalanceamount,2);


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
            $grand_total_label = $this->lang->line('medicinepurchasereport_grand_total') . (!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : '');
            $sheet->setCellValue('A'.($row-1), $grand_total_label);

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
            $sheet->getStyle('A1:G2')->applyFromArray($styleArray);


            // After Two Row to Before Two Last Row
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
            $styleColumn = "G".($row-2);
            $sheet->getStyle('A3:'.$styleColumn)->applyFromArray($styleArray);


            // Last Row
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
            $styleColumn = $row-1;
            $sheet->getStyle('A'.$styleColumn.':'.'G'.$styleColumn)->applyFromArray($styleArray);

            // Top Row Merge
            if($topCellMerge) {
                $sheet->mergeCells("B1:G1");
            } else {
                $sheet->mergeCells("B1:F1");
            }

            // Bottom Row Merge
            $startmerge  = "A".$styleColumn;
            $endmerge    = "D".$styleColumn;
            $sheet->mergeCells("$startmerge:$endmerge");
        } else {
            redirect(site_url('medicinepurchasereport'));
        }
    }

    private function queryArray($posts) {
        $medicinewarehouseID = $posts['medicinewarehouseID'];
        $reference_no = $posts['reference_no'];
        $statusID       = $posts['statusID'];
        $from_date    = $posts['from_date'];
        $to_date      = $posts['to_date'];

        $queryArray = [];
        if((int)$medicinewarehouseID) {
            $queryArray['medicinewarehouseID'] = $medicinewarehouseID;
        }
        if($reference_no != '') {
            $queryArray['reference_no'] = $reference_no;
        }
        if((int)$statusID) {
            $queryArray['statusID'] = $statusID;
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

        $this->data['medicinewarehouseID'] = $medicinewarehouseID;
        $this->data['reference_no']        = ($reference_no) ? $reference_no : 0;
        $this->data['statusID']  = $statusID;
        $this->data['from_date'] = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']   = ($to_date) ? strtotime($to_date) : 0;

        $this->data['medicinewarehouses']  = pluck($this->medicinewarehouse_m->get_select_medicinewarehouse(),'name','medicinewarehouseID');
        $this->data['medicinepurchases']   = $this->medicinepurchase_m->get_medicinepurchase_for_report($queryArray);

        $medicinepurchasepaids     = $this->medicinepurchasepaid_m->get_select_medicinepurchasepaid();
        $medicinepurchasepaidArray = [];
        if(inicompute($medicinepurchasepaids)) {
            foreach($medicinepurchasepaids as $medicinepurchasepaid) {
                if(isset($medicinepurchasepaidArray[$medicinepurchasepaid->medicinepurchaseID])) {
                    $medicinepurchasepaidArray[$medicinepurchasepaid->medicinepurchaseID] += $medicinepurchasepaid->medicinepurchasepaidamount;
                } else {
                    $medicinepurchasepaidArray[$medicinepurchasepaid->medicinepurchaseID] = $medicinepurchasepaid->medicinepurchasepaidamount;
                }
            }
        }
        $this->data['medicinepurchasepaids']   = $medicinepurchasepaidArray;
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'medicinewarehouseID',
                'label' => $this->lang->line("medicinepurchasereport_warehouse"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line("medicinepurchasereport_reference_no"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line("medicinepurchasereport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'form_date',
                'label' => $this->lang->line("medicinepurchasereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("medicinepurchasereport_to_date"),
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
                'label' => $this->lang->line("medicinepurchasereport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("medicinepurchasereport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("medicinepurchasereport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'medicinewarehouseID',
                'label' => $this->lang->line("medicinepurchasereport_warehouse"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line("medicinepurchasereport_reference_no"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line("medicinepurchasereport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'form_date',
                'label' => $this->lang->line("medicinepurchasereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("medicinepurchasereport_to_date"),
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