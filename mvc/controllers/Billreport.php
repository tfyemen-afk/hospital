<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billreport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('billcategory_m');
        $this->load->model('billlabel_m');
        $this->load->model('patient_m');
        $this->load->model('bill_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('billreport', $language);
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
                'assets/inilabs/report/bill/index.js'
            )
        );
        $this->data['jsmanager'] = ['billreport_please_select' => $this->lang->line('billreport_please_select')];
        $this->data["billcategorys"] = $this->billcategory_m->get_select_billcategory();
        $this->data['patients']      = $this->patient_m->get_select_patient('patientID, name', ['delete_at' => 0]);

        $this->data["subview"] = "report/bill/BillReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getbillreport() 
    {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('billreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/bill/BillReport', $this->data,true);
                }
            }
        } else {
            $retArray['status'] = TRUE;
            $retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
        }
        echo json_encode($retArray);
        exit;
    }

    public function pdf() 
    {
        if(permissionChecker('billreport')) {
            $billcategoryID = htmlentities(escapeString($this->uri->segment(3)));
            $billlabelID    = htmlentities(escapeString($this->uri->segment(4)));
            $uhid           = htmlentities(escapeString($this->uri->segment(5)));
            $paymentstatus  = htmlentities(escapeString($this->uri->segment(6)));
            $from_date      = htmlentities(escapeString($this->uri->segment(7)));
            $to_date        = htmlentities(escapeString($this->uri->segment(8)));
            if(((int)$billcategoryID || $billcategoryID == 0) && ((int)$billlabelID || $billlabelID == 0) && ((int)$uhid || $uhid == 0) && ((int)$paymentstatus || $paymentstatus == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {
                
                $qArray['billcategoryID'] = $billcategoryID;
                $qArray['billlabelID']    = $billlabelID;
                $qArray['uhid']           = $uhid;
                $qArray['paymentstatus']  = $paymentstatus;
                $qArray['from_date']      = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']        = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'billreport.css', 'data' => $this->data, 'viewpath' => 'report/bill/BillReportPDF']);
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function xlsx() 
    {
        if(permissionChecker('billreport')) {
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
            header('Content-Disposition: attachment;filename="billreport.xlsx"');
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

    private function xmlData() 
    {
        if(permissionChecker('billreport')) {
            $billcategoryID = htmlentities(escapeString($this->uri->segment(3)));
            $billlabelID    = htmlentities(escapeString($this->uri->segment(4)));
            $uhid           = htmlentities(escapeString($this->uri->segment(5)));
            $paymentstatus  = htmlentities(escapeString($this->uri->segment(6)));
            $from_date      = htmlentities(escapeString($this->uri->segment(7)));
            $to_date        = htmlentities(escapeString($this->uri->segment(8)));
            if(((int)$billcategoryID || $billcategoryID == 0) && ((int)$billlabelID || $billlabelID == 0) && ((int)$uhid || $uhid == 0) && ((int)$paymentstatus || $paymentstatus == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {
                
                $qArray['billcategoryID'] = $billcategoryID;
                $qArray['billlabelID']    = $billlabelID;
                $qArray['uhid']           = $uhid;
                $qArray['paymentstatus']  = $paymentstatus;
                $qArray['from_date']      = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']        = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('billreport'));
            }
        } else {
            redirect(site_url('billreport'));
        }
    }

    private function generateXML($array) 
    {
        extract($array);
        if(inicompute($bills)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'I';

            $leftlabel    = "";
            $rightlabel   = "";
            if($from_date && $to_date) { 
                $leftlabel  =  $this->lang->line('billreport_from_date')." : ".$label_from_date;
            } elseif($billcategoryID) {
                $leftlabel  =  $this->lang->line('billreport_category')." : ".$label_category;
            } elseif($billlabelID) {
                $leftlabel  =  $this->lang->line('billreport_label')." : ".$label_billlabel;
            }

            if($from_date && $to_date) {
                $rightlabel = $this->lang->line('billreport_to_date')." : ".$label_to_date;
            } elseif($uhid) {
                $rightlabel = $this->lang->line('billreport_patient_name')." : ".$label_patient;
            } elseif($paymentstatus) {
                $rightlabel = $this->lang->line('billreport_payment_status')." : ".$label_payment_status;
            } elseif($from_date) {
                $rightlabel = $this->lang->line('billreport_from_date')." : ".$label_from_date;
            }  

            if($leftlabel != '' && $rightlabel != '') {
                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->setCellValue('I'.$row, $rightlabel);
                $sheet->mergeCells("B1:H1");
            } elseif($leftlabel != '') {
                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->mergeCells("B1:I1");
            } elseif ($rightlabel != '') {
                $sheet->setCellValue('A'.$row, $rightlabel);
                $sheet->mergeCells("B1:I1");
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers[] = '#';
            $headers[] = $this->lang->line('billreport_label');
            $headers[] = $this->lang->line('billreport_category');
            $headers[] = $this->lang->line('billreport_patient_name');
            $headers[] = $this->lang->line('billreport_date');
            $headers[] = $this->lang->line('billreport_payment_status');
            $headers[] = $this->lang->line('billreport_discount'). " (%) ";
            $headers[] = $this->lang->line('billreport_amount');
            $headers[] = $this->lang->line('billreport_total');

            if(inicompute($headers)) {
                $column = "A";
                $row    = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }

            $i = 0;
            $total_amount = 0;
            $total_total  = 0;
            foreach($bills as $bill) { $i++;
                $bodys[$i][] = $i;
                $bodys[$i][] = $bill->billlabelname;
                $bodys[$i][] = isset($billcategorys[$bill->billcategoryID]) ? $billcategorys[$bill->billcategoryID] : '';
                $bodys[$i][] = $bill->patientname;
                $bodys[$i][] = app_date($bill->create_date, FALSE);
                $bodys[$i][] = ($bill->status) ? $this->lang->line('billreport_paid') : $this->lang->line('billreport_due');
                $bodys[$i][] = $bill->discount;
                $bodys[$i][] = number_format($bill->amount, 2);

                $billdiscount  = $bill->discount;
                $billamount    = $bill->amount;
                $total_amount += $billamount;
                $billtotal     = $billamount - (($billdiscount/100) * $billamount);
                $total_total  += $billtotal;
                $bodys[$i][] = number_format($billtotal, 2);
            }
            $i++;
            $bodys[$i][] = $this->lang->line('billreport_grand_total')." ".(!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : '');
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = number_format($total_amount, 2);
            $bodys[$i][] = number_format($total_total, 2);

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

            $grand_total_row = $row-1;
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
            $grand_total_row_1 = "A".$grand_total_row;
            $grand_total_row_2 = "I".$grand_total_row;
            $sheet->getStyle("$grand_total_row_1:$grand_total_row_2")->applyFromArray($styleArray);

            $grand_total_row_merge_start = "A".$grand_total_row;
            $grand_total_row_merge_end   = "G".$grand_total_row;
            $sheet->mergeCells("$grand_total_row_merge_start:$grand_total_row_merge_end");
        } else {
            redirect(site_url('billreport'));
        }
    }

    public function send_pdf_to_mail() 
    {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('billreport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'billreport.css', 'data' => $this->data, 'viewpath' => 'report/bill/BillReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('billreport_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('billreport_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    public function get_billlabel()
    {
        echo "<option value='0'>— ".$this->lang->line('bill_please_select')." —</option>";
        if($_POST) {
            $billcategoryID = $this->input->post('billcategoryID');
            if((int)$billcategoryID) {
                $billlabels = $this->billlabel_m->get_order_by_billlabel(array('billcategoryID'=> $billcategoryID));
                if(inicompute($billlabels)) {
                    foreach ( $billlabels as $billlabel) {
                        echo "<option value='".$billlabel->billlabelID."'>".$billlabel->name."</option>";
                    }
                }
            }
        }
    }

    private function queryArray($posts) 
    {
        $billcategoryID = $posts['billcategoryID'];
        $billlabelID    = $posts['billlabelID'];
        $uhid           = $posts['uhid'];
        $paymentstatus  = $posts['paymentstatus'];
        $from_date      = $posts['from_date'];
        $to_date        = $posts['to_date'];

        $queryArray = [];
        if($billcategoryID) {
            $queryArray['billcategoryID']  = $billcategoryID;
        }
        if($billlabelID) {
            $queryArray['billlabelID']  = $billlabelID;
        }
        if($uhid) {
            $queryArray['uhid']  = $uhid;
        }
        if($paymentstatus) {
            $queryArray['paymentstatus']  = $paymentstatus-1;
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

        $this->data['bills']           = $this->bill_m->get_order_by_bill_for_report($queryArray);
        $this->data["billcategorys"]   = pluck($this->billcategory_m->get_select_billcategory(), 'name', 'billcategoryID');
        
        $this->data["billcategoryID"]  = $billcategoryID;
        $this->data["billlabelID"]     = $billlabelID;
        $this->data["uhid"]            = $uhid;
        $this->data["paymentstatus"]   = $paymentstatus;
        $this->data["from_date"]       = ($from_date) ? strtotime($from_date) : 0;
        $this->data["to_date"]         = ($to_date) ? strtotime($to_date) : 0;

        $this->data['label_category'] = "";
        if((int)$billcategoryID) {
            $this->data['label_category'] = (isset($this->data["billcategorys"][$billcategoryID]) ? $this->data["billcategorys"][$billcategoryID] : "");
        }
        $this->data['label_billlabel'] = "";
        if((int)$billlabelID) {
            $billlabel = $this->billlabel_m->get_single_billlabel(['billlabelID'=> $billlabelID]);
            $this->data['label_billlabel'] = (inicompute($billlabel) ? $billlabel->name : "");
        }
        $this->data['label_patient'] = "";
        if((int)$uhid) {
            $patient = $this->patient_m->get_select_patient('patientID, name', ['patientID'=> $uhid,'delete_at' => 0], TRUE);
            $this->data['label_patient'] = (inicompute($patient) ? $patient->name : "");
        }
        $paymentstatusArray['1']    = $this->lang->line('billreport_due');
        $paymentstatusArray['2']    = $this->lang->line('billreport_paid');
        $this->data['label_paymentstatus'] = (isset($paymentstatusArray[$paymentstatus]) ? $paymentstatusArray[$paymentstatus] : '');
        $this->data['label_from_date'] = ($from_date) ? app_date($from_date, FALSE) : '';
        $this->data['label_to_date']   = ($to_date) ? app_date($to_date, FALSE) : '';
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'billcategoryID',
                'label' => $this->lang->line("billreport_category"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'billlabelID',
                'label' => $this->lang->line("billreport_label"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'uhid',
                'label' => $this->lang->line("billreport_uhid"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'paymentstatus',
                'label' => $this->lang->line("billreport_payment_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("billreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("billreport_to_date"),
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
                'label' => $this->lang->line("billreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("billreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("billreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'billcategoryID',
                'label' => $this->lang->line("billreport_category"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'billlabelID',
                'label' => $this->lang->line("billreport_label"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'uhid',
                'label' => $this->lang->line("billreport_uhid"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'paymentstatus',
                'label' => $this->lang->line("billreport_payment_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("billreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("billreport_to_date"),
                'rules' => 'trim|callback_date_valid|callback_unique_date_check'
            )
        );
        return $rules;
    }

    public function date_valid($date) 
    {
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

    public function unique_date_check() 
    {
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