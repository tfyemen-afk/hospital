<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bedreport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('ward_m');
        $this->load->model('bed_m');
        $this->load->model('room_m');
        $this->load->model('bedtype_m');

        $this->load->library('report');
		$language = $this->session->userdata('lang');;
		$this->lang->load('bedreport', $language);
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
                'assets/inilabs/report/bed/index.js'
            )
        );
        $this->data['rooms']    = pluck($this->room_m->get_select_room('roomID, name'), 'name','roomID');
        $this->data['wards']    = $this->ward_m->get_select_ward('wardID, name');
        $this->data['bedtypes'] = $this->bedtype_m->get_bedtype();

        $this->data["subview"]  = "report/bed/BedReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getbedreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('bedreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/bed/BedReport', $this->data,true);
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
        if(permissionChecker('bedreport')) {
            $wardID     = htmlentities(escapeString($this->uri->segment(3)));
            $bedtypeID  = htmlentities(escapeString($this->uri->segment(4)));
            $bedID      = htmlentities(escapeString($this->uri->segment(5)));
            $statusID   = htmlentities(escapeString($this->uri->segment(6)));
            if(((int)$wardID || $wardID == 0) && ((int)$bedtypeID || $bedtypeID == 0) && ((int)$bedID || $bedID == 0) && ((int)$statusID || $statusID == 0)) {
                $qArray['wardID']    = $wardID;
                $qArray['bedtypeID'] = $bedtypeID;
                $qArray['bedID']     = $bedID;
                $qArray['statusID']  = $statusID;

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'bedreport.css', 'data' => $this->data, 'viewpath' => 'report/bed/BedReportPDF']);
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
        if(permissionChecker('bedreport')) {
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
            header('Content-Disposition: attachment;filename="bedreport.xlsx"');
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
        if(permissionChecker('bedreport')) {
            $wardID     = htmlentities(escapeString($this->uri->segment(3)));
            $bedtypeID  = htmlentities(escapeString($this->uri->segment(4)));
            $bedID      = htmlentities(escapeString($this->uri->segment(5)));
            $statusID   = htmlentities(escapeString($this->uri->segment(6)));
            if(((int)$wardID || $wardID == 0) && ((int)$bedtypeID || $bedtypeID == 0) && ((int)$bedID || $bedID == 0) && ((int)$statusID || $statusID == 0)) {
                $qArray['wardID']    = $wardID;
                $qArray['bedtypeID'] = $bedtypeID;
                $qArray['bedID']     = $bedID;
                $qArray['statusID']  = $statusID;

                $queryArray = $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('bedreport'));
            }
        } else {
            redirect(site_url('bedreport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($beds)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'F';
            $mergecolumn  = 'E';

            $leftlabel  = "";
            $rightlabel = "";
            if($wardID) { 
                $leftlabel =  $this->lang->line('bedreport_ward')." : ".$label_ward;
            } elseif($bedtypeID) {
                $leftlabel =  $this->lang->line('bedreport_bed_type')." : ".$label_bedtype;
            }

            if($bedID) {
                $rightlabel = $this->lang->line('bedreport_bed')." : ".$label_bed;
            } elseif($statusID) {
                $rightlabel = $this->lang->line('bedreport_status')." : ".$label_status;
            }

            if($statusID == 1) {
                $lastcolumn   = 'E';
            }
            

            if($leftlabel != '' && $rightlabel != '') {
                if($statusID == 1) {
                    $lastcolumn   = 'E';
                    $mergecolumn  = 'D';
                }
                $mainmergecolumn = $mergecolumn.'1';

                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->setCellValue($lastcolumn.$row, $rightlabel);
                $sheet->mergeCells("B1:$mainmergecolumn");
            } elseif($leftlabel != '') {
                $mergecolumn   = 'F';
                if($statusID == 1) {
                    $mergecolumn  = 'E';
                }
                $mainmergecolumn = $mergecolumn.'1';

                $sheet->setCellValue('A'.$row, $leftlabel);
                $sheet->mergeCells("B1:$mainmergecolumn");
            } elseif ($rightlabel != '') {
                $mergecolumn   = 'F';
                if($statusID == 1) {
                    $mergecolumn  = 'E';
                }
                $mainmergecolumn = $mergecolumn.'1';

                $sheet->setCellValue('A'.$row, $rightlabel);
                $sheet->mergeCells("B1:$mainmergecolumn");
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers[] = '#';
            $headers[] = $this->lang->line('bedreport_bed_name');
            $headers[] = $this->lang->line('bedreport_bed_type');
            $headers[] = $this->lang->line('bedreport_ward');
            $headers[] = $this->lang->line('bedreport_status');
            if($statusID !=1) {
                $headers[] = $this->lang->line('bedreport_patient_name');
            }


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
            foreach($beds as $bed) {
                $bodys[$i][] = ++$i;

                $bodys[$i][] = $bed->name;
                $bodys[$i][] = isset($bedtypes[$bed->bedtypeID]) ? $bedtypes[$bed->bedtypeID] : '';
                $bodys[$i][] = (isset($wards[$bed->wardID]) ? $wards[$bed->wardID]->name : '').' - '.((isset($wards[$bed->wardID]) && isset($rooms[$wards[$bed->wardID]->roomID])) ? $rooms[$wards[$bed->wardID]->roomID] : '');
                $bodys[$i][] = ($bed->status) ? $statusArray[2] : $statusArray[1];
                if($statusID !=1) {
                    $bodys[$i][] = $bed->patientname;
                }
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
            redirect(site_url('bedreport'));
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('bedreport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'bedreport.css', 'data' => $this->data, 'viewpath' => 'report/bed/BedReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('bedreport_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('bedreport_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    private function queryArray($posts) {
        $wardID     = $posts['wardID'];
        $bedtypeID  = $posts['bedtypeID'];
        $bedID      = $posts['bedID'];
        $statusID   = $posts['statusID'];

        $queryArray = [];
        if($wardID) {
            $queryArray['wardID']  = $wardID;
        }
        if($bedtypeID) {
            $queryArray['bedtypeID']  = $bedtypeID;
        }
        if($bedID) {
            $queryArray['bedID']  = $bedID;
        }
        if($statusID) {
            $queryArray['status']  = $statusID-1;
        }
        $this->data['beds']      = $this->bed_m->get_order_by_bed_for_report($queryArray);
        $this->data['wards']     = pluck($this->ward_m->get_ward(), 'obj', 'wardID');
        $this->data['bedtypes']  = pluck($this->bedtype_m->get_bedtype(), 'name', 'bedtypeID');

        $this->data['rooms']     = pluck($this->room_m->get_select_room('roomID, name'), 'name','roomID');

        $this->data['wardID']    = $wardID;
        $this->data['bedtypeID'] = $bedtypeID;
        $this->data['bedID']     = $bedID;
        $this->data['statusID']  = $statusID;


        $statusArray[1] = $this->lang->line('bedreport_avialable');
        $statusArray[2] = $this->lang->line('bedreport_unavailable');

        $wards     = $this->data['wards'];     
        $bedtypes  = $this->data['bedtypes'];  

        $this->data['label_ward']    = "";
        $this->data['label_bedtype'] = "";
        $this->data['label_bed']     = "";
        if($wardID) {
            $this->data['label_ward']    = (isset($wards[$wardID])) ? $wards[$wardID]->name : '';
        }
        if($bedtypeID) {
            $this->data['label_bedtype'] = (isset($bedtypes[$bedtypeID])) ? $bedtypes[$bedtypeID] : '';
        }
        if($bedID) {
            $bed = $this->bed_m->get_single_bed(array('bedID'=> $bedID));
            $this->data['label_bed']     =  (inicompute($bed)) ? $bed->name : '';
        }
        $this->data['label_status'] = isset($statusArray[$statusID]) ? $statusArray[$statusID] : '';

        $this->data['statusArray']  = $statusArray;
    }

    public function get_bed() {
        echo "<option value='0'>— ".$this->lang->line('bedreport_please_select')." —</option>";
        if($_POST) {
            $wardID    = $this->input->post('wardID');
            $bedtypeID = $this->input->post('bedtypeID');
            
            $queryArray = [];
            if((int)$wardID) {
                $queryArray['wardID'] = $wardID;
            }
            if((int)$bedtypeID) {
                $queryArray['bedtypeID'] = $bedtypeID;
            }
            $beds = $this->bed_m->get_order_by_bed($queryArray);
            if(inicompute($beds)) {
                foreach ($beds as $bed) {
                    echo "<option value='".$bed->bedID."'>".$bed->name."</option>";
                }
            }
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'wardID',
                'label' => $this->lang->line("bedreport_ward"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'bedtypeID',
                'label' => $this->lang->line("bedreport_bed_type"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'bedID',
                'label' => $this->lang->line("bedreport_bed"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line("bedreport_status"),
                'rules' => 'trim|numeric'
            )
        );
        return $rules;
    }

    protected function send_pdf_to_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("bedreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("bedreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("bedreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'wardID',
                'label' => $this->lang->line("bedreport_ward"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'bedtypeID',
                'label' => $this->lang->line("bedreport_bed_type"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'bedID',
                'label' => $this->lang->line("bedreport_bed"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'statusID',
                'label' => $this->lang->line("bedreport_status"),
                'rules' => 'trim|numeric'
            )
        );
        return $rules;
    }

}