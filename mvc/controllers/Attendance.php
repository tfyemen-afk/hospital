<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance extends Admin_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('user_m');
        $this->load->model('attendance_m');
        $this->load->model('designation_m');
        $this->load->library('report');
        $language = $this->session->userdata('lang');
        $this->lang->load('attendance', $language);
    }

    private function rules() 
    {
        $rules = array(
            array(
                'field' => 'date',
                'label' => $this->lang->line("attendance_date"),
                'rules' => 'trim|required|callback_valid_date|callback_valid_future_date'
            )
        );
        return $rules;
    }

	public function index() 
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/dist/css/bootstrap-datepicker.css',
            ),
            'js' => array(
                'assets/datepicker/dist/js/bootstrap-datepicker.js',
                'assets/inilabs/attendance/index.js'
            )
        );

        $this->data['users'] = $this->user_m->get_order_by_user(['status'=>1, 'roleID !=' => 3, 'delete_at' => 0]);
        $this->data['designations'] = pluck($this->designation_m->get_designation(),'designation','designationID');
        $this->data['attendances'] = [];
        $this->data['attendance_date']  = date('d-m-Y');
        $this->data['poststatus'] = false; 
        $this->data['activetab']  = TRUE;
        if(permissionChecker('attendance_add')) {
            if($_POST) {
                $this->data['activetab']  = FALSE;
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    
                } else {
                    $this->data['poststatus'] = true;
                    $date = $this->input->post('date');
                    $dateArray = explode('-', $date);
                    $monthyear = $dateArray[1].'-'.$dateArray[2];

                    $attendances = pluck($this->attendance_m->get_order_by_attendance(array('monthyear'=>$monthyear)),'obj','userID');
                    $attendanceArray = [];
                    if(inicompute($this->data['users'])) {
                        foreach ($this->data['users'] as $user) {
                            if(!isset($attendances[$user->userID])) {
                                $attendanceArray[$user->userID]['userID'] = $user->userID;
                                $attendanceArray[$user->userID]['roleID'] = $user->roleID;
                                $attendanceArray[$user->userID]['monthyear'] = $monthyear;
                                $attendanceArray[$user->userID]['year'] = date('Y');
                            }
                        }
                    }

                    if(inicompute($attendanceArray)) {
                        $this->attendance_m->insert_batch_attendance($attendanceArray);
                    } 

                    $this->data['colday']  = 'a'.ltrim($dateArray[0],'0');
                    $this->data['attendance_date']  = $date;
                    $this->data['attendances']  = pluck($this->attendance_m->get_order_by_attendance(array('monthyear'=>$monthyear)),'obj','userID');
                }
            }
        }

        $this->data["subview"] = 'attendance/index';
        $this->load->view('_layout_main', $this->data);
	}

    public function view() 
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
            ),
            'js' => array(
                'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
                'assets/inilabs/attendance/view.js'
            )
        );
        
        $userID  = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$userID) {
            $user = $this->user_m->get_single_user(array('userID'=> $userID, 'delete_at' => 0, 'status' => 1, 'roleID !=' => 3));
            if(inicompute($user)) {
                $this->data['jsmanager']    = ['userID' => $userID];
                $this->data['roles']        = pluck($this->role_m->get_role(),'role', 'roleID');
                $this->data['designations'] = pluck($this->designation_m->get_designation(),'designation', 'designationID');
                $this->data['user']         = $user;
                $this->data["attendances"]  = pluck($this->attendance_m->get_order_by_attendance(array('year'=> date('Y'),'userID'=>$userID)), 'obj', 'monthyear');
                $this->data["subview"]      = 'attendance/view';
                $this->load->view('_layout_main', $this->data);
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function printpreview() 
    {
        if(permissionChecker('attendance_view')) {
            $userID  = htmlentities(escapeString($this->uri->segment('3')));
            if((int)$userID) {
                $user = $this->user_m->get_single_user(array('userID'=> $userID, 'delete_at' => 0, 'status' => 1, 'roleID !=' => 3));
                if(inicompute($user)) {
                    $this->data['roles']        = pluck($this->role_m->get_role(),'role', 'roleID');
                    $this->data['designations'] = pluck($this->designation_m->get_designation(),'designation', 'designationID');
                    $this->data['user']         = $user;
                    $this->data["attendances"]  = pluck($this->attendance_m->get_order_by_attendance(array('year'=> date('Y'),'userID'=>$userID)), 'obj', 'monthyear');

                    $this->report->reportPDF(['stylesheet' => 'attendancemodule.css', 'data' => $this->data, 'viewpath' => 'attendance/printpreview']);
                } else {
                    $this->data["subview"] = '_not_found';
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function save_attendance() 
    {
        $retArray = [];
        $retArray['status'] = FALSE;
        if(permissionChecker('attendance_add')) {
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray['message'] = validation_errors();
                    $retArray['status']  = FALSE;
                } else {
                    $date      = $this->input->post('date');
                    $dateArray = explode('-', $date);
                    $column    = 'a'.ltrim($dateArray[0],'0');
                    $attendances = $this->input->post('attendances');

                    $upattendanceArray = [];
                    if(is_array($attendances) && inicompute($attendances)) {
                        foreach($attendances as $attendanceKey => $attendance) {
                            $attendanceID = str_replace("attendance-", "", $attendanceKey);
                            $upattendanceArray[] = array(
                                'attendanceID' => $attendanceID,
                                $column => $attendance,
                            );
                        }
                    }

                    $this->attendance_m->update_batch_attendance($upattendanceArray, 'attendanceID');
                    $this->session->set_flashdata('success', 'Success');
                    $retArray['message'] = 'Success';
                    $retArray['status']  = TRUE;
                }
            } else {
                $retArray['message'] = 'Method does not allowed';
            }
        } else {
            $retArray['message'] = 'Permission does not allowed';
        }
        echo json_encode($retArray);
    }

    public function sendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('attendance_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $userID = $this->input->post('userID');
                    $user  = $this->user_m->get_single_user(array('userID'=> $userID));
                    if(inicompute($user) && ($userID != 1)) {
                        $email      = $this->input->post('to');
                        $subject    = $this->input->post('subject');
                        $message    = $this->input->post('message');

                        $this->data['roles']        = pluck($this->role_m->get_role(),'role', 'roleID');
                        $this->data['designations'] = pluck($this->designation_m->get_designation(),'designation', 'designationID');
                        $this->data['user']         = $user;
                        $this->data["attendances"]  = pluck($this->attendance_m->get_order_by_attendance(array('year'=> date('Y'),'userID'=>$userID)), 'obj', 'monthyear');

                        $this->report->reportSendToMail(['stylesheet' => 'attendancemodule.css', 'data' => $this->data, 'viewpath' => 'attendance/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                        $retArray['message'] = "Success";
                        $retArray['status']  = TRUE;
                    } else {
                        $retArray['message'] = $this->lang->line('attendance_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('attendance_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('attendance_permission');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("attendance_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("attendance_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("attendance_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("attendance_user"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function valid_date($date) 
    {
        if($date) {
            if(strlen($date) < 10) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                if(inicompute($arr) == 3) {
                    $dd = $arr[0];
                    $mm = $arr[1];
                    $yyyy = $arr[2];
                    if(checkdate($mm, $dd, $yyyy)) {
                        return TRUE;
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                        return FALSE;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                    return FALSE;
                }
            }
        } else {
            return TRUE;
        }
    }
    
    public function valid_future_date($date) 
    {
        $presentdate = strtotime(date('Y-m-d'));
        $date = strtotime($date);
        if($date > $presentdate) {
            $this->form_validation->set_message('valid_future_date','The %s field does not given future date');
            return FALSE;
        }
        return TRUE;
    }
}
