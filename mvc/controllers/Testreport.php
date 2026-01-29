<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testreport extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('test_m');
        $this->load->model('testlabel_m');
        $this->load->model('testcategory_m');
        $this->load->model('patient_m');
        $this->load->library('report');

		$language = $this->session->userdata('lang');;
		$this->lang->load('testreport', $language);
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
                'assets/inilabs/report/test/index.js'
            )
        );
        $this->data['testcategorys']  = $this->testcategory_m->get_select_testcategory('testcategoryID, name');

        $this->data["subview"] = "report/test/TestReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function gettestreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('testreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/test/TestReport', $this->data,true);
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
        if(permissionChecker('testreport')) {
            $testcategoryID = htmlentities(escapeString($this->uri->segment(3)));
            $testlabelID    = htmlentities(escapeString($this->uri->segment(4)));
            $billID     = htmlentities(escapeString($this->uri->segment(5)));
            $from_date  = htmlentities(escapeString($this->uri->segment(6)));
            $to_date    = htmlentities(escapeString($this->uri->segment(7)));
            if(((int)$testcategoryID || $testcategoryID == 0) && ((int)$testlabelID || $testlabelID == 0) && ((int)$billID || $billID == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['testcategoryID']  = $testcategoryID;
                $qArray['testlabelID'] = $testlabelID;
                $qArray['billID'] = $billID;
                $qArray['from_date'] = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']   = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'testreport.css', 'data' => $this->data, 'viewpath' => 'report/test/TestReportPDF']);
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
        if(permissionChecker('testreport')) {
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
            header('Content-Disposition: attachment;filename="testreport.xlsx"');
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
        if(permissionChecker('testreport')) {
            $testcategoryID = htmlentities(escapeString($this->uri->segment(3)));
            $testlabelID    = htmlentities(escapeString($this->uri->segment(4)));
            $billID     = htmlentities(escapeString($this->uri->segment(5)));
            $from_date  = htmlentities(escapeString($this->uri->segment(6)));
            $to_date    = htmlentities(escapeString($this->uri->segment(7)));
            if(((int)$testcategoryID || $testcategoryID == 0) && ((int)$testlabelID || $testlabelID == 0) && ((int)$billID || $billID == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['testcategoryID']  = $testcategoryID;
                $qArray['testlabelID'] = $testlabelID;
                $qArray['billID'] = $billID;
                $qArray['from_date'] = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']   = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('testreport'));
            }
        } else {
            redirect(site_url('testreport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($tests)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'F';

            $leftlabel  = "";
            $rightlabel = "";
            if($from_date && $to_date) { 
                $leftlabel =  $this->lang->line('testreport_from_date')." : ".$label_from_date;;
            } elseif($testcategoryID) {
                $leftlabel =  $this->lang->line('testreport_category')." : ".$label_testcategory;;
            } elseif($billID) {
                $leftlabel =  $this->lang->line('testreport_bill_no')." : ".$label_bill_no;;
            }

            if($from_date && $to_date) {
                $rightlabel = $this->lang->line('testreport_to_date')." : ".$label_to_date;
            } elseif($testlabelID) {
                $rightlabel = $this->lang->line('testreport_test_name')." : ".$label_testlabel;
            } elseif($from_date) {
                $rightlabel = $this->lang->line('testreport_from_date')." : ".$label_from_date;
            } 

            if($leftlabel != '' && $rightlabel != '') {
                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->setCellValue('F'.$row, $rightlabel);
                $sheet->mergeCells("B1:E1");
            } elseif($leftlabel != '') {
                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->mergeCells("B1:F1");
            } elseif ($rightlabel != '') {
                $sheet->setCellValue('A'.$row, $rightlabel);
                $sheet->mergeCells("B1:F1");
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers   = [];
            $headers[] = '#';
            $headers[] = $this->lang->line('testreport_test_name');
            $headers[] = $this->lang->line('testreport_category');
            $headers[] = $this->lang->line('testreport_bill_no');
            $headers[] = $this->lang->line('testreport_name');
            $headers[] = $this->lang->line('testreport_date');

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
            foreach($tests as $test) {
                $bodys[$i][] = ++$i;
                $bodys[$i][] = isset($testlabels[$test->testlabelID]) ? $testlabels[$test->testlabelID] : '';
                $bodys[$i][] = isset($testcategorys[$test->testcategoryID]) ? $testcategorys[$test->testcategoryID] : '';
                $bodys[$i][] = $test->billID;
                $bodys[$i][] = isset($patients[$test->billID]) ? $patients[$test->billID] : '';
                $bodys[$i][] = app_date($test->create_date, false);
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
            redirect(site_url('testreport'));
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('testreport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'testreport.css', 'data' => $this->data, 'viewpath' => 'report/test/TestReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('testreport_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('testreport_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    public function get_test_label() {
        echo "<option value='0'>— ".$this->lang->line('testreport_please_select')." —</option>";
        if($_POST) {
            $testcategoryID = $this->input->post('testcategoryID');
            if((int)$testcategoryID) {
                $testlabels = $this->testlabel_m->get_order_by_testlabel(array('testcategoryID'=>$testcategoryID));
                if(inicompute($testlabels)) {
                    foreach ($testlabels as $testlabel) {
                        echo "<option value='".$testlabel->testlabelID."'>".$testlabel->name."</option>";
                    }
                }
            }
        }
    }

    private function queryArray($posts) {
        $testcategoryID = $posts['testcategoryID'];
        $testlabelID    = $posts['testlabelID'];
        $billID         = $posts['billID'];
        $from_date      = $posts['from_date'];
        $to_date        = $posts['to_date'];

        $queryArray = [];
        if($testcategoryID) {
            $queryArray['testcategoryID']  = $testcategoryID;
            if($testlabelID) {
                $queryArray['testlabelID'] = $testlabelID;
            }
        }
        if($billID) {
            $queryArray['billID']  = $billID;
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

        $this->data['testcategorys']  = pluck($this->testcategory_m->get_select_testcategory('testcategoryID, name'),'name','testcategoryID');
        $this->data['testlabels'] = pluck($this->testlabel_m->get_select_testlabel('testlabelID, name'), 'name','testlabelID');
        $this->data['patients']   = pluck($this->patient_m->get_patient_by_billID(),'name', 'billID');
        $this->data['tests']      = $this->test_m->get_order_by_test_for_report($queryArray);
        
        $this->data['testcategoryID'] = $testcategoryID;
        $this->data['testlabelID']    = $testlabelID;
        $this->data['billID']    = ($billID) ? $billID : 0;
        $this->data['from_date'] = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']   = ($to_date) ? strtotime($to_date) : 0;


        $testcategorys   = $this->data['testcategorys'];
        $testlabels      = $this->data['testlabels'];

        $this->data['label_testcategory']= isset($testcategorys[$testcategoryID]) ? $testcategorys[$testcategoryID] : '';
        $this->data['label_testlabel']   = isset($testlabels[$testlabelID]) ? $testlabels[$testlabelID] : '';
        $this->data['label_bill_no']     = $billID;
        $this->data['label_from_date']   = ($from_date) ? app_date($from_date, FALSE) : '';
        $this->data['label_to_date']     = ($to_date) ? app_date($to_date, FALSE) : '';
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'testcategoryID',
                'label' => $this->lang->line("testreport_test_category"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'testlabelID',
                'label' => $this->lang->line("testreport_test_label"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'billID',
                'label' => $this->lang->line("testreport_bill_no"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("testreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("testreport_to_date"),
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
                'label' => $this->lang->line("testreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("testreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("testreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'testcategoryID',
                'label' => $this->lang->line("testreport_test_category"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'testlabelID',
                'label' => $this->lang->line("testreport_test_label"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'billID',
                'label' => $this->lang->line("testreport_bill_no"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("testreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("testreport_to_date"),
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