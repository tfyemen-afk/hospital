<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinesalereport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('medicinesale_m');
        $this->load->model('medicinesalepaid_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('medicinesalereport', $language);
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
                'assets/inilabs/report/medicinesale/index.js'
            )
        );

        $this->data["subview"] = "report/medicinesale/MedicinesaleReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getmedicinesalereport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('medicinesalereport')) {
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
                    $retArray['render'] = $this->load->view('report/medicinesale/MedicinesaleReport', $this->data,true);
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
        if(permissionChecker('medicinesalereport')) {
            $patient_type = htmlentities(escapeString($this->uri->segment(3)));
            $uhid         = htmlentities(escapeString($this->uri->segment(4)));
            $statusID     = htmlentities(escapeString($this->uri->segment(5)));
            $from_date    = htmlentities(escapeString($this->uri->segment(6)));
            $to_date      = htmlentities(escapeString($this->uri->segment(7)));

            if(((int)$patient_type || $patient_type==0) && ((int)$uhid || $uhid == 0) && ((int)$statusID || $statusID == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['patient_type'] = $patient_type; 
                $qArray['uhid']         = ($uhid) ? $uhid : 0; 
                $qArray['statusID']       = $statusID;
                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'medicinesalereport.css', 'data' => $this->data, 'viewpath' => 'report/medicinesale/MedicinesaleReportPDF']);
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
        if(permissionChecker('medicinesalereport')) {
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
                    $this->report->reportSendToMail(['stylesheet' => 'medicinesalereport.css', 'data' => $this->data, 'viewpath' => 'report/medicinesale/MedicinesaleReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('medicinesalereport_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('medicinesalereport_permission');
        }
        echo json_encode($retArray);
        exit;
    }
    
    public function xlsx() {
        if(permissionChecker('medicinesalereport')) {
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
            header('Content-Disposition: attachment;filename="medicinesalereport.xlsx"');
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
            $this->data["subview"] = "reporterror";
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function xmlData() {
        if(permissionChecker('medicinesalereport')) {
            $patient_type = htmlentities(escapeString($this->uri->segment(3)));
            $uhid         = htmlentities(escapeString($this->uri->segment(4)));
            $statusID     = htmlentities(escapeString($this->uri->segment(5)));
            $from_date    = htmlentities(escapeString($this->uri->segment(6)));
            $to_date      = htmlentities(escapeString($this->uri->segment(7)));

            if(((int)$patient_type || $patient_type==0) && ((int)$uhid || $uhid == 0) && ((int)$statusID || $statusID == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['patient_type'] = $patient_type; 
                $qArray['uhid']         = ($uhid) ? $uhid : 0; 
                $qArray['statusID']       = $statusID;
                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            }
        } else {
            $this->data["subview"] = "reporterror";
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($medicinesales)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $lastcolumn = 'G';
            if($statusID == 0 && $statusID !=4) {
                $lastcolumn = 'H';  
            }
            
            $row = 1;
            $topCellMerge = TRUE;
            if($from_date != 0 && $to_date != 0 ) { 
                $topCellMerge = FALSE;
                $datefrom   = $this->lang->line('medicinesalereport_from_date')." : ";
                $datefrom  .= date('d M Y',$from_date);
                $dateto     = $this->lang->line('medicinesalereport_to_date')." : ";
                $dateto    .= date('d M Y', $to_date);
             
                $sheet->setCellValue('A'.$row, $datefrom);
                $sheet->setCellValue($lastcolumn.$row, $dateto);
            } elseif($patient_type ==1 || $patient_type == 2) {
                $patient_type_label = $this->lang->line('medicinesalereport_patient_type').' : ';
                if($patient_type == 1) {
                    $patient_type_label .= $this->lang->line('medicinesalereport_opd');           
                } elseif ($patient_type==2) {
                    $patient_type_label .= $this->lang->line('medicinesalereport_ipd');
                } else {
                    $patient_type_label .= $this->lang->line('medicinesalereport_none');
                }
                $sheet->setCellValue('A'.$row, $patient_type_label);
            } elseif($statusID != 0 ) {
                $statuslabel  = $this->lang->line('medicinesalereport_status')." : ";
                if($statusID == 1) {
                    $statuslabel .=  $this->lang->line("medicinesalereport_pending");
                } elseif($statusID == 2) {
                    $statuslabel .=  $this->lang->line("medicinesalereport_partial");
                } elseif($statusID == 3) {
                    $statuslabel .=  $this->lang->line("medicinesalereport_fully_paid");
                } elseif($statusID == 4) {
                    $statuslabel .=  $this->lang->line("medicinesalereport_refund");
                }
               
                $sheet->setCellValue('A'.$row, $statuslabel);
            } elseif($from_date) {
                $datefrom   = $this->lang->line('medicinesalereport_from_date')." : ";
                $datefrom  .= date('d M Y',$from_date);
                $sheet->setCellValue('A'.$row, $datefrom);
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers   = array();
            $headers[] = $this->lang->line('medicinesalereport_slno');
            $headers[] = $this->lang->line('medicinesalereport_patient_type');
            $headers[] = $this->lang->line('medicinesalereport_uhid');
            $headers[] = $this->lang->line('medicinesalereport_date');
            if($statusID == 0 && $statusID !=4) {
                $headers[] = $this->lang->line('medicinesalereport_status');
            }
            $headers[] = $this->lang->line('medicinesalereport_total');
            $headers[] = $this->lang->line('medicinesalereport_paid');
            $headers[] = $this->lang->line('medicinesalereport_balance');

            if(inicompute($headers)) {
                $column = "A";
                $row    = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }

            $totalmedicinesaleprice         = 0;
            $totalmedicinesalepaidamount    = 0;
            $totalmedicinesalebalanceamount = 0;
            $i     = 0;
            $bodys = [];
            foreach($medicinesales as $medicinesale) {
                $bodys[$i][]   = ++$i;
                if($medicinesale->patient_type == 0) {
                    $bodys[$i][] =  $this->lang->line('medicinesalereport_opd');           
                } elseif ($medicinesale->patient_type==1) {
                    $bodys[$i][] =  $this->lang->line('medicinesalereport_ipd');
                } else {
                    $bodys[$i][] =  $this->lang->line('medicinesalereport_none');
                }
                $bodys[$i][]   = ($medicinesale->uhid != 0) ? $medicinesale->uhid : '';
                $bodys[$i][]   = app_date($medicinesale->medicinesaledate);
                if($statusID == 0 && $statusID !=4) {
                    if($medicinesale->medicinesalestatus == 0) {
                        $bodys[$i][] = $this->lang->line("medicinesalereport_pending");
                    } elseif($medicinesale->medicinesalestatus == 1) {
                        $bodys[$i][] = $this->lang->line("medicinesalereport_partial");
                    } elseif($medicinesale->medicinesalestatus == 2) {
                        $bodys[$i][] = $this->lang->line("medicinesalereport_fully_paid");
                    }   
                }
                $bodys[$i][]   = number_format($medicinesale->medicinesaletotalamount, 2);
                $paidamount    = isset($medicinesalepaids[$medicinesale->medicinesaleID]) ? $medicinesalepaids[$medicinesale->medicinesaleID] : 0;
                $bodys[$i][]   = number_format($paidamount, 2);
                $balanceamount = $medicinesale->medicinesaletotalamount-$paidamount;
                $bodys[$i][]   = number_format($balanceamount, 2);
                
                $totalmedicinesaleprice         += $medicinesale->medicinesaletotalamount;
                $totalmedicinesalepaidamount    += $paidamount;
                $totalmedicinesalebalanceamount += $balanceamount;
                $i++;
            }
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            if($statusID == 0 && $statusID !=4) {
                $bodys[$i][] = "";
            }
            $bodys[$i][] = number_format($totalmedicinesaleprice,2);
            $bodys[$i][] = number_format($totalmedicinesalepaidamount,2);
            $bodys[$i][] = number_format($totalmedicinesalebalanceamount,2);


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
            $grand_total_label = $this->lang->line('medicinesalereport_grand_total') . (!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : '');
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
            $sheet->getStyle("A1:".$lastcolumn.'2')->applyFromArray($styleArray);


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
            $styleColumn = $lastcolumn.($row-2);
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
            $sheet->getStyle('A'.$styleColumn.':'.$lastcolumn.$styleColumn)->applyFromArray($styleArray);

            // Top Row Merge
            $sheet->mergeCells("B1:$lastcolumn".'1');

            // Bottom Row Merge
            $startmerge  = "A".$styleColumn;
            if($statusID == 0 && $statusID !=4) {
                $endmerge    = "E".$styleColumn;
            } else {
                $endmerge    = "D".$styleColumn;
            }
            $sheet->mergeCells("$startmerge:$endmerge");
        } else {
            redirect(site_url('medicinesalereport'));
        }
    }

    private function queryArray($posts) {
        $patient_type = $posts['patient_type'];
        $uhid         = $posts['uhid'];
        $statusID       = $posts['statusID'];
        $from_date    = $posts['from_date'];
        $to_date      = $posts['to_date'];

        $queryArray = [];
        if($patient_type) {
            $queryArray['patient_type'] = $patient_type-1;
            if(($patient_type == 1 || $patient_type == 2) && $uhid != '') {
                $queryArray['uhid'] = $uhid;
            }
        }

        if((int)$statusID && $statusID > 0 ) {
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

        $this->data['patient_type'] = $patient_type;
        $this->data['uhid']      = ($uhid) ? $uhid : 0;
        $this->data['statusID']    = $statusID;
        $this->data['from_date'] = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']   = ($to_date) ? strtotime($to_date) : 0;

        $this->data['medicinesales']   = $this->medicinesale_m->get_medicinesale_for_report($queryArray);

        $medicinesalepaids     = $this->medicinesalepaid_m->get_select_medicinesalepaid();
        $medicinesalepaidArray = [];
        if(inicompute($medicinesalepaids)) {
            foreach($medicinesalepaids as $medicinesalepaid) {
                if(isset($medicinesalepaidArray[$medicinesalepaid->medicinesaleID])) {
                    $medicinesalepaidArray[$medicinesalepaid->medicinesaleID] += $medicinesalepaid->medicinesalepaidamount;
                } else {
                    $medicinesalepaidArray[$medicinesalepaid->medicinesaleID] = $medicinesalepaid->medicinesalepaidamount;
                }
            }
        }

        $this->data['medicinesalepaids']   = $medicinesalepaidArray;
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'patient_type',
                'label' => $this->lang->line("medicinesalereport_patient_type"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'uhid',
                'label' => $this->lang->line("medicinesalereport_uhid"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'reference_no',
                'label' => $this->lang->line("medicinesalereport_reference_no"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line("medicinesalereport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'form_date',
                'label' => $this->lang->line("medicinesalereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("medicinesalereport_to_date"),
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
                'label' => $this->lang->line("medicinesalereport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("medicinesalereport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("medicinesalereport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'patient_type',
                'label' => $this->lang->line("medicinesalereport_patient_type"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'uhid',
                'label' => $this->lang->line("medicinesalereport_uhid"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line("medicinesalereport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'form_date',
                'label' => $this->lang->line("medicinesalereport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("medicinesalereport_to_date"),
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