<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blooddonorreport extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('blooddonor_m');
        $this->load->model('patient_m');
        $this->load->model('bloodgroup_m');
        $this->load->library('report');

		$language = $this->session->userdata('lang');;
		$this->lang->load('blooddonorreport', $language);
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
                'assets/inilabs/report/blooddonor/index.js'
            )
        );

        $this->data['blooddonors'] = $this->blooddonor_m->get_select_blooddonor('blooddonorID, name');
        $this->patient_m->order('patientID asc');
        $this->data['patients']    = $this->patient_m->get_select_patient('patientID, name', array('delete_at' => 0));
        $this->data["bloodgroups"] = $this->bloodgroup_m->get_bloodgroup();

        $this->data["subview"] = "report/blooddonor/BlooddonorReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getblooddonorreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('blooddonorreport')) {
            if($_POST) {
                $rules   = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                } else {                    
                    $this->queryArray($this->input->post());

                    $retArray['status'] = TRUE;
                    $retArray['render'] = $this->load->view('report/blooddonor/BlooddonorReport', $this->data,true);
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
        if(permissionChecker('blooddonorreport')) {
            $bloodgroupID  = htmlentities(escapeString($this->uri->segment(3)));
            $blooddonorID  = htmlentities(escapeString($this->uri->segment(4)));
            $patientID     = htmlentities(escapeString($this->uri->segment(5)));
            $statusID      = htmlentities(escapeString($this->uri->segment(6)));
            $bagno         = htmlentities(escapeString($this->uri->segment(7)));
            $from_date     = htmlentities(escapeString($this->uri->segment(8)));
            $to_date       = htmlentities(escapeString($this->uri->segment(9)));
            if(((int)$bloodgroupID || $bloodgroupID == 0) && ((int)$blooddonorID || $blooddonorID == 0) && ((int)$patientID || $patientID == 0) && ((int)$statusID || $statusID == 0) && ((int)$bagno || $bagno == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['bloodgroupID'] = $bloodgroupID; 
                $qArray['blooddonorID'] = $blooddonorID; 
                $qArray['patientID']    = $patientID; 
                $qArray['statusID']     = $statusID; 
                $qArray['bagno']        = $bagno; 
                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 

                $queryArray = $this->queryArray($qArray);
                $this->report->reportPDF(['stylesheet' => 'blooddonorreport.css', 'data' => $this->data, 'viewpath' => 'report/blooddonor/BlooddonorReportPDF']);
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
        if(permissionChecker('blooddonorreport')) {
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
            header('Content-Disposition: attachment;filename="blooddonorreport.xlsx"');
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
        if(permissionChecker('blooddonorreport')) {
            $bloodgroupID  = htmlentities(escapeString($this->uri->segment(3)));
            $blooddonorID  = htmlentities(escapeString($this->uri->segment(4)));
            $patientID     = htmlentities(escapeString($this->uri->segment(5)));
            $statusID      = htmlentities(escapeString($this->uri->segment(6)));
            $bagno         = htmlentities(escapeString($this->uri->segment(7)));
            $from_date     = htmlentities(escapeString($this->uri->segment(8)));
            $to_date       = htmlentities(escapeString($this->uri->segment(9)));
            if(((int)$bloodgroupID || $bloodgroupID == 0) && ((int)$blooddonorID || $blooddonorID == 0) && ((int)$patientID || $patientID == 0) && ((int)$statusID || $statusID == 0) && ((int)$bagno || $bagno == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['bloodgroupID'] = $bloodgroupID; 
                $qArray['blooddonorID'] = $blooddonorID; 
                $qArray['patientID']    = $patientID; 
                $qArray['statusID']     = $statusID; 
                $qArray['bagno']        = $bagno; 
                $qArray['from_date']    = ($from_date) ? date('d-m-Y', $from_date) : ''; 
                $qArray['to_date']      = ($to_date) ? date('d-m-Y', $to_date) : ''; 
                $this->queryArray($qArray);
                return $this->generateXML($this->data);
            } else {
                redirect(site_url('blooddonorreport'));
            }
        } else {
            redirect(site_url('blooddonorreport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($blooddonors)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();

            $row          = 1;
            $lastcolumn   = 'I';

            $leftlabel  = "";
            $rightlabel = "";
            if($from_date && $to_date) {
                $leftlabel = $this->lang->line('blooddonorreport_from_date')." : ".$label_from_date;
            } elseif($bloodgroupID) { 
                $leftlabel =  $this->lang->line('blooddonorreport_blood_group')." : ".$label_bloodgroup;
            } elseif ($blooddonorID) {
                $leftlabel =  $this->lang->line('blooddonorreport_donor_name')." : ".$label_blooddonor;
            } elseif($patientID) {
                $leftlabel =  $this->lang->line('blooddonorreport_patient_name')." : ".$label_patient;
            }

            if($from_date && $to_date) {
                $rightlabel = $this->lang->line('blooddonorreport_to_date')." : ".$label_to_date;
            } elseif($statusID) {
                $rightlabel = $this->lang->line('blooddonorreport_status')." : ".$label_status;
            } elseif($bagno) {
                $rightlabel = $this->lang->line('blooddonorreport_bag_no')." : ".$label_bagno;
            } elseif($from_date) {
                $rightlabel = $this->lang->line('blooddonorreport_date')." : ".$label_from_date;
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

            $headers   = array();
            $headers[] = '#';
            $headers[] = $this->lang->line('blooddonorreport_name');
            $headers[] = $this->lang->line('blooddonorreport_phone');
            $headers[] = $this->lang->line('blooddonorreport_email');
            $headers[] = $this->lang->line('blooddonorreport_blood_group');
            $headers[] = $this->lang->line('blooddonorreport_patient_name');
            $headers[] = $this->lang->line('blooddonorreport_date');
            $headers[] = $this->lang->line('blooddonorreport_status');
            $headers[] = $this->lang->line('blooddonorreport_bag_no');

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
            foreach($blooddonors as $blooddonor) {
                $bodys[$i][] = ++$i;
                $bodys[$i][] = $blooddonor->name; 
                $bodys[$i][] = $blooddonor->phone; 
                $bodys[$i][] = $blooddonor->email; 
                $bodys[$i][] = isset($bloodgroups[$blooddonor->bloodgroupID]) ? $bloodgroups[$blooddonor->bloodgroupID] : ''; 
                $bodys[$i][] = isset($patients[$blooddonor->patientID]) ? $patients[$blooddonor->patientID] : ''; 
                $bodys[$i][] = app_date($blooddonor->create_date);
                if($blooddonor->status==0) {
                    $bodys[$i][] = $this->lang->line('blooddonorreport_reserve');
                } elseif($blooddonor->status==1) {
                    $bodys[$i][] = $this->lang->line('blooddonorreport_release');
                } else {
                    $bodys[$i][] = "";
                }
                $bodys[$i][] = $blooddonor->bagno; 
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
            redirect(site_url('blooddonorreport'));
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('blooddonorreport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'blooddonorreport.css', 'data' => $this->data, 'viewpath' => 'report/blooddonor/BlooddonorReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('blooddonorreport_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('blooddonorreport_permission');
        }
        echo json_encode($retArray);
        exit;
    }

    private function queryArray($posts) {
        $bloodgroupID = $posts['bloodgroupID'];
        $blooddonorID = $posts['blooddonorID'];
        $patientID    = $posts['patientID'];
        $statusID     = $posts['statusID'];
        $bagno        = $posts['bagno'];
        $from_date    = $posts['from_date'];
        $to_date      = $posts['to_date'];

        $queryArray = [];
        if($bloodgroupID) {
            $queryArray['bloodgroupID'] = $bloodgroupID;
        }
        if($blooddonorID) {
            $queryArray['blooddonorID'] = $blooddonorID;
        }
        if($patientID) {
            $queryArray['patientID']    = $patientID;
        }
        if($statusID) {
            $queryArray['statusID']     = $statusID-1;
        }
        if($bagno) {
            $queryArray['bagno']        = $bagno;
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

        $this->data['blooddonors']  = $this->blooddonor_m->get_order_by_blooddonor_for_report($queryArray);
        $this->data['patients']     = pluck($this->patient_m->get_select_patient('patientID, name', array('delete_at' => 0)),'name', 'patientID');
        $this->data["bloodgroups"]  = pluck($this->bloodgroup_m->get_bloodgroup(),'bloodgroup','bloodgroupID');

        $this->data['bloodgroupID'] = ($bloodgroupID) ? $bloodgroupID : 0;
        $this->data['blooddonorID'] = ($blooddonorID) ? $blooddonorID : 0;
        $this->data['patientID']    = ($patientID) ? $patientID : 0;
        $this->data['statusID']     = ($statusID) ? $statusID : 0;
        $this->data['bagno']        = ($bagno) ? $bagno : 0;
        $this->data['from_date']    = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']      = ($to_date) ? strtotime($to_date) : 0;

        $patients      = $this->data['patients'];
        $bloodgroups   = $this->data["bloodgroups"];

        $this->data['label_bloodgroup'] = isset($bloodgroups[$bloodgroupID]) ? $bloodgroups[$bloodgroupID] : '';
        $this->data['label_blooddonor'] = "";
        if($blooddonorID) {
            $blooddonor = $this->blooddonor_m->get_single_blooddonor(array('blooddonorID'=>$blooddonorID));
            $this->data['label_blooddonor'] = inicompute($blooddonor) ? $blooddonor->name : '';
        }
        $this->data['label_patient']    = isset($patients[$bloodgroupID]) ? $patients[$bloodgroupID] : '';
        $statusArray[1]                 = $this->lang->line('blooddonorreport_reserve');
        $statusArray[2]                 = $this->lang->line('blooddonorreport_release');
        $this->data['label_status']     = isset($statusArray[$statusID]) ? $statusArray[$statusID] : '';
        $this->data['label_bagno']      = ($bagno) ? $bagno : '';
        $this->data['label_from_date']  = ($from_date) ? app_date($from_date, FALSE) : '';
        $this->data['label_to_date']    = ($to_date) ? app_date($to_date, FALSE) : '';
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'bloodgroupID',
                'label' => $this->lang->line("blooddonorreport_blood_group"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'blooddonorID',
                'label' => $this->lang->line("blooddonorreport_donor_name"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("blooddonorreport_patient_name"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("blooddonorreport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'bagno',
                'label' => $this->lang->line("blooddonorreport_bag_no"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("blooddonorreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("blooddonorreport_to_date"),
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
                'label' => $this->lang->line("blooddonorreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("blooddonorreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("blooddonorreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'bloodgroupID',
                'label' => $this->lang->line("blooddonorreport_blood_group"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'blooddonorID',
                'label' => $this->lang->line("blooddonorreport_donor_name"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("blooddonorreport_patient_name"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("blooddonorreport_status"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'bagno',
                'label' => $this->lang->line("blooddonorreport_bag_no"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'from_date',
                'label' => $this->lang->line("blooddonorreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("blooddonorreport_to_date"),
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