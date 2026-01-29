<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salaryreport extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
        $this->load->model('role_m');
        $this->load->model('user_m');
        $this->load->model('makepayment_m');

        $this->load->library('report');

		$language = $this->session->userdata('lang');;
		$this->lang->load('salaryreport', $language);
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
                'assets/inilabs/report/salary/index.js'
            )
        );
        $this->data['roles']          = $this->role_m->get_select_role('roleID, role', array('roleID !='=>3));
        
        $this->data["subview"] = "report/salary/SalaryReportView";
        $this->load->view('_layout_main', $this->data);
	}

    public function getsalaryreport() {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';
        if(permissionChecker('salaryreport')) {
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
                    $retArray['render'] = $this->load->view('report/salary/SalaryReport', $this->data,true);
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
        if(permissionChecker('salaryreport')) {
            $roleID     = htmlentities(escapeString($this->uri->segment(3)));
            $userID     = htmlentities(escapeString($this->uri->segment(4)));
            $month      = htmlentities(escapeString($this->uri->segment(5)));
            $from_date  = htmlentities(escapeString($this->uri->segment(6)));
            $to_date    = htmlentities(escapeString($this->uri->segment(7)));

            if(((int)$roleID || $roleID == 0) && ((int)$userID || $userID == 0) && ((int)$month || $month == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['roleID']    = $roleID; 
                $qArray['userID']    = $userID; 
                $qArray['month']     = ($month != 0) ? date('m-Y', $month) : 0;
                $qArray['from_date'] = ($from_date != 0) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']   = ($to_date != 0) ? date('d-m-Y', $to_date) : 0; 

                $queryArray = $this->queryArray($qArray);

                $this->report->reportPDF(['stylesheet' => 'salaryreport.css', 'data' => $this->data, 'viewpath' => 'report/salary/SalaryReportPDF']);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function send_pdf_to_mail() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('salaryreport')) {
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

                    $this->report->reportSendToMail(['stylesheet' => 'salaryreport.css', 'data' => $this->data, 'viewpath' => 'report/salary/SalaryReportPDF', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                    $retArray['message'] = "Success";
                    $retArray['status'] = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('salaryreport_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('salaryreport_permission');
        }
        echo json_encode($retArray);
        exit;
    }

    
    public function xlsx() {
        if(permissionChecker('salaryreport')) {
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
            header('Content-Disposition: attachment;filename="salaryreport.xlsx"');
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
        if(permissionChecker('salaryreport')) {
            $roleID     = htmlentities(escapeString($this->uri->segment(3)));
            $userID     = htmlentities(escapeString($this->uri->segment(4)));
            $month      = htmlentities(escapeString($this->uri->segment(5)));
            $from_date  = htmlentities(escapeString($this->uri->segment(6)));
            $to_date    = htmlentities(escapeString($this->uri->segment(7)));

            if(((int)$roleID || $roleID == 0) && ((int)$userID || $userID == 0) && ((int)$month || $month == 0) && ((int)$from_date || $from_date == 0) && ((int)$to_date || $to_date == 0)) {

                $qArray['roleID']    = $roleID; 
                $qArray['userID']    = $userID; 
                $qArray['month']     = ($month != 0) ? date('m-Y', $month) : 0;
                $qArray['from_date'] = ($from_date != 0) ? date('d-m-Y', $from_date) : 0; 
                $qArray['to_date']   = ($to_date != 0) ? date('d-m-Y', $to_date) : 0;

                $queryArray = $this->queryArray($qArray);

                return $this->generateXML($this->data);
            } else {
                redirect(site_url('salaryreport'));
            }
        } else {
            redirect(site_url('salaryreport'));
        }
    }

    private function generateXML($array) {
        extract($array);
        if(inicompute($salarys)) {
            $sheet = $this->phpspreadsheet->spreadsheet->getActiveSheet();
            
            $row = 1;
            $topCellMerge = TRUE;
            if($from_date != 0 && $to_date != 0 ) { 
                $datefrom   = $this->lang->line('salaryreport_from_date')." : ";
                $datefrom  .= date('d M Y',$from_date);
                $dateto     = $this->lang->line('salaryreport_to_date')." : ";
                $dateto    .= date('d M Y', $to_date);
             
                $sheet->setCellValue('A'.$row, $datefrom);
                $sheet->setCellValue('F'.$row, $dateto);
            } elseif($month != 0 ) {
                $topCellMerge = FALSE;
                $role  = $this->lang->line('salaryreport_month')." : ";
                $role .= date('M Y', $month);
               
                $sheet->setCellValue('A'.$row, $role);
            } elseif($roleID != 0 && $userID != 0) {
                $role  = $this->lang->line('salaryreport_role')." : ";
                $role .= isset($roles[$roleID]) ? $roles[$roleID] : '';
                $user_name  = $this->lang->line('salaryreport_employee')." : ";
                $user_name .= isset($users[$userID]) ? $users[$userID] : '';

                $sheet->setCellValue('A'.$row, $role);
                $sheet->setCellValue('F'.$row, $user_name);
            } elseif($roleID != 0) {
                $topCellMerge = FALSE;
                $role  = $this->lang->line('salaryreport_role')." : ";
                $role .= $roles[$roleID];

                $sheet->setCellValue('A'.$row, $role);
            } else {
                $sheet->getRowDimension(1)->setVisible(false);
            }

            $headers = array();
            $headers[]  = $this->lang->line('salaryreport_slno');
            $headers[]  = $this->lang->line('salaryreport_date');
            $headers[]  = $this->lang->line('salaryreport_name');
            $headers[]  = $this->lang->line('salaryreport_role');
            $headers[]  = $this->lang->line('salaryreport_month');
            $headers[]  = $this->lang->line('salaryreport_amount');

            if(inicompute($headers)) {
                $column = "A";
                $row = 2;
                foreach($headers as $header) {
                    $sheet->setCellValue($column.$row, $header);
                    $column++;
                }
            }

            $i= 0;
            $totalSalary = 0;
            $bodys = array();
            foreach($salarys as $salary) {
                $bodys[$i][] = ++$i;
                $bodys[$i][] = app_date($salary->create_date);
                $bodys[$i][] = isset($users[$salary->userID]) ? $users[$salary->userID] : '';
                $bodys[$i][] = isset($roles[$salary->roleID]) ? $roles[$salary->roleID] : '';
                $bodys[$i][] = date('M Y',strtotime('01-'.$salary->month));
                $bodys[$i][] = number_format($salary->payment_amount,2);
                $totalSalary += $salary->payment_amount;
                $i++;
            }
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = "";
            $bodys[$i][] = number_format($totalSalary,2);


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
            $grand_total_label = $this->lang->line('salaryreport_grand_total') . (!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : '');
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
            $sheet->getStyle('A1:F2')->applyFromArray($styleArray);


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
            $styleColumn = "F".($row-2);
            $sheet->getStyle('A3:'.$styleColumn)->applyFromArray($styleArray);


            // Two Last Row
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
            $sheet->getStyle('A'.$styleColumn.':'.'F'.$styleColumn)->applyFromArray($styleArray);

            // Top Row Merge
            if($topCellMerge) {
                $sheet->mergeCells("B1:E1");
            } else {
                $sheet->mergeCells("B1:F1");
            }

            // Bottom Row Merge
            $startmerge  = "A".$styleColumn;
            $endmerge    = "E".$styleColumn;
            $sheet->mergeCells("$startmerge:$endmerge");
        } else {
            redirect(site_url('salaryreport'));
        }
    }

    private function queryArray($posts) {
        $roleID     = $posts['roleID'];
        $userID     = $posts['userID'];
        $month      = $posts['month'];
        $from_date  = $posts['from_date'];
        $to_date    = $posts['to_date'];

        $queryArray = [];
        if($roleID > 0) {
            $queryArray['roleID'] = $roleID;
        }
        if($userID > 0) {
            $queryArray['userID'] = $userID;
        }
        if((int) $month && $month != '') {
            $queryArray['month'] = $month;
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

        $this->data['roleID']    = $roleID;
        $this->data['userID']    = $userID;
        $this->data['month']     = ($month) ? strtotime('01-'.$month) : 0;
        $this->data['from_date'] = ($from_date) ? strtotime($from_date) : 0;
        $this->data['to_date']   = ($to_date) ? strtotime($to_date) : 0;

        $this->data['roles']     = pluck($this->role_m->get_select_role(),'role','roleID');
        $this->data['users']     = pluck($this->user_m->get_select_user(),'name','userID');
        $this->data['salarys']   = $this->makepayment_m->get_all_salary_for_report($queryArray);

    }

    public function get_user() {
        echo "<option value='0'>".$this->lang->line("salaryreport_please_select")."</option>";
        if($_POST) {
            $roleID = $this->input->post('roleID');
            if((int)$roleID && $roleID != 3) {
                $users = $this->user_m->get_select_user('userID, name', array('roleID' => $roleID));
                if(inicompute($users)) {
                    foreach ($users as $user) {
                        echo "<option value='".$user->userID."'>".$user->name."</option>";
                    }
                }
            }
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("salaryreport_role"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("salaryreport_user"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'month',
                'label' => $this->lang->line("salaryreport_month"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'form_date',
                'label' => $this->lang->line("salaryreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("salaryreport_to_date"),
                'rules' => 'trim|callback_date_valid|callback_unique_date'
            )
        );
        return $rules;
    }

    protected function send_pdf_to_mail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("salaryreport_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("salaryreport_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("salaryreport_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("salaryreport_role"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("salaryreport_employee"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'month',
                'label' => $this->lang->line("salaryreport_month"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'form_date',
                'label' => $this->lang->line("salaryreport_from_date"),
                'rules' => 'trim|callback_date_valid'
            ),
            array(
                'field' => 'to_date',
                'label' => $this->lang->line("salaryreport_to_date"),
                'rules' => 'trim|callback_date_valid|callback_unique_date'
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

    public function unique_date() {
        $from_date = $this->input->post('from_date');
        $to_date   = $this->input->post('to_date');

        if($from_date != '' && $to_date != '') {
            if(strtotime($from_date) > strtotime($to_date)) {
                $this->form_validation->set_message("unique_date", "The from date can not be upper than to_date .");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }
    
}