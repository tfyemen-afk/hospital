<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaveapply extends Admin_Controller
{
    public function __construct()
     {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('user_m');
        $this->load->model('designation_m');
        $this->load->model('leaveassign_m');
        $this->load->model('leavecategory_m');
        $this->load->model('leaveapplication_m');

        $this->load->library('report');

        $language = $this->session->userdata('lang');;
        $this->lang->load('leaveapply', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("leaveapply_role"),
                'rules' => 'trim|required|callback_required_no_zero|callback_unique_role'
            ),
            array(
                'field' => 'applicationto_userID',
                'label' => $this->lang->line("leaveapply_application_to"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'leavecategoryID',
                'label' => $this->lang->line("leaveapply_category"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'schedule',
                'label' => $this->lang->line("leaveapply_schedule"),
                'rules' => 'trim|required|callback_date_schedule_valid'
            ),
            array(
                'field' => 'reason',
                'label' => $this->lang->line("leaveapply_reason"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'attachment',
                'label' => $this->lang->line("leaveapply_attachment"),
                'rules' => 'trim|callback_attachment_upload'
            )
        );
        return $rules;
    }

	public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/daterangepicker/css/daterangepicker.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/daterangepicker/js/moment.min.js',
                'assets/daterangepicker/js/daterangepicker.js',
                'assets/inilabs/leaveapply/index.js',
            )
        );

        $roleID      = $this->session->userdata('roleID');
        $loginuserID = $this->session->userdata('loginuserID');

        $this->data['jsmanager']      = ['leaveapply_please_select' => $this->lang->line('leaveapply_please_select')];
        $this->data['leaveapplys']    = $this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID' => $loginuserID, 'create_roleID'=> $roleID,'year' => date('Y')));
        $this->data['userName']       = pluck($this->user_m->get_select_user('userID, name', array('roleID !=' => 3)), 'name', 'userID');
        $this->data['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(), 'obj', 'leavecategoryID');
        $this->data['roles']          = pluck($this->role_m->get_order_by_role(array('roleID != ' => 3)), 'obj', 'roleID');
        $this->data['applyroles']     = $this->leaveapplication_m->get_leaveapplication_by_role('leaveapplication', 1);

        $this->data['users']     = [];
        if($this->input->post("roleID")) {
            $this->data['users'] = $this->user_m->get_order_by_user(array('roleID' => $this->input->post("roleID"), 'roleID !=' => 3));
        }

        $leaveassign         = $this->leaveassign_m->get_order_by_leaveassign(array('roleID' => $roleID, 'year' => date('Y')));
        $myleaveapplications = pluck($this->leaveapplication_m->get_sum_of_leave_days_by_user($roleID, $loginuserID, date('Y')), 'days', 'leavecategoryID');
        if(inicompute($leaveassign)) {
            foreach ($leaveassign as $item) {
                if (array_key_exists($item->leavecategoryID, $myleaveapplications)) {
                    $item->leaveassignday = $item->leaveassignday - $myleaveapplications[$item->leavecategoryID];
                }
            }
        }

        $this->data['leaveassigns'] = $leaveassign;

        if(permissionChecker('leaveapply_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"]    = 'leaveapply/index';
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $explode                            = explode('-', $this->input->post("schedule"));
                    $array["leavecategoryID"]           = $this->input->post("leavecategoryID");
                    $array["year"]                      = date('Y');
                    $array["apply_date"]                = date("Y-m-d H:i:s");
                    $array["from_date"]                 = date("Y-m-d", strtotime(trim($explode[0])));
                    $array["from_time"]                 = date('H:i:s');
                    $array["to_date"]                   = date("Y-m-d", strtotime(trim($explode[1])));
                    $array["to_time"]                   = date('H:i:s');
                    $array["reason"]                    = $this->input->post("reason");
                    $array["attachment"]                = $this->upload_data['attachment']['file_name'];
                    $array["attachmentorginalname"]     = $this->upload_data['attachment']['original_file_name'];
                    $array["create_date"]               = date("Y-m-d H:i:s");
                    $array["modify_date"]               = date("Y-m-d H:i:s");
                    $array["create_userID"]             = $loginuserID;
                    $array["create_roleID"]             = $roleID;
                    $array["applicationto_userID"]      = $this->input->post("applicationto_userID");
                    $array["applicationto_roleID"]      = $this->input->post("roleID");

                    $leavedaysCount                     = $this->leavedaysinicompute(trim($explode[0]), trim($explode[1]));
                    $array["leave_days"]                = isset($leavedaysCount['totaldayCount']) ? $leavedaysCount['totaldayCount'] : 0;

                    if($this->input->post("od_status")) {
                        $array["od_status"]             = $this->input->post("od_status");
                    }

                    $this->leaveapplication_m->insert_leaveapplication($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('leaveapply/index'));
                }
            } else {
    		    $this->data["subview"] = 'leaveapply/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'leaveapply/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit()
    {
        $leaveapplyID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$leaveapplyID) {
            $roleID      = $this->session->userdata('roleID');
            $loginuserID = $this->session->userdata('loginuserID');
            
            $this->data['leaveapply']   = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID'=> $leaveapplyID, 'create_userID'=>$loginuserID, 'create_roleID'=> $roleID, 'year' => date('Y')));
            if(inicompute($this->data['leaveapply']) && ($this->data['leaveapply']->status==NULL)) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/daterangepicker/css/daterangepicker.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/daterangepicker/js/moment.min.js',
                        'assets/daterangepicker/js/daterangepicker.js',
                        'assets/inilabs/leaveapply/index.js',
                    )
                );

                $this->data['jsmanager']      = ['leaveapply_please_select' => $this->lang->line('leaveapply_please_select')];
                $this->data['leaveapplys']    = pluck($this->leaveapplication_m->get_order_by_leaveapplication(array('create_userID'=>$loginuserID, 'create_roleID'=> $roleID, 'year'=>date('Y'))),'obj','leaveapplicationID');
                $this->data['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(),'obj','leavecategoryID');
                $this->data['userName']       = pluck($this->user_m->get_select_user('userID, name', array('roleID !=' => 3)), 'name', 'userID');
                $this->data['roles']          = pluck($this->role_m->get_order_by_role(array('roleID != ' => 3)), 'obj', 'roleID');
                $this->data['applyroles']     = $this->leaveapplication_m->get_leaveapplication_by_role('leaveapplication', 1);
                
                $this->data['users'] = [];
                if($this->input->post("roleID")) {
                    $this->data['users']   = $this->user_m->get_select_user('userID, name', array('roleID'=> $this->input->post("roleID") ));
                } elseif($this->data['leaveapply']->applicationto_roleID) {
                    $this->data['users']   = $this->user_m->get_select_user('userID, name', array('roleID'=> $this->data['leaveapply']->applicationto_roleID));
                }

                $leaveassign = $this->leaveassign_m->get_order_by_leaveassign(array('roleID'=>$roleID, 'year'=>date('Y')));
                $myleaveapplications = pluck($this->leaveapplication_m->get_sum_of_leave_days_by_user($roleID, $loginuserID, date('Y')), 'days', 'leavecategoryID');
                if(inicompute($leaveassign)) {
                    foreach ($leaveassign as $item) {
                        if (array_key_exists($item->leavecategoryID, $myleaveapplications)) {
                            $item->leaveassignday = $item->leaveassignday - $myleaveapplications[$item->leavecategoryID];
                        }
                    }
                }
                $this->data['leaveassigns'] = $leaveassign;

                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'leaveapply/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $explode    = explode('-', $this->input->post("schedule"));
                        $array["leavecategoryID"] = $this->input->post("leavecategoryID");
                        $array["apply_date"]      = date("Y-m-d H:i:s");
                        if($this->input->post("od_status")) {
                            $array["od_status"] = $this->input->post("od_status");
                        } else {
                            $array["od_status"] = 0;
                        }

                        $array["from_date"]             = date("Y-m-d", strtotime(trim($explode[0])));
                        $array["from_time"]             = date('H:i:s');
                        $array["to_date"]               = date("Y-m-d", strtotime(trim($explode[1])));
                        $array["to_time"]               = date('H:i:s');
                        $array["reason"]                = $this->input->post("reason");
                        $array["attachment"]            = $this->upload_data['attachment']['file_name'];
                        $array["attachmentorginalname"] = $this->upload_data['attachment']['original_file_name'];
                        $array["create_date"]           = date("Y-m-d H:i:s");
                        $array["modify_date"]           = date("Y-m-d H:i:s");
                        $array["create_userID"]         = $loginuserID;
                        $array["create_roleID"]         = $roleID;
                        $array["applicationto_userID"]  = $this->input->post("applicationto_userID");
                        $array["applicationto_roleID"]  = $this->input->post("roleID");

                        $leavedaysCount         = $this->leavedaysinicompute(trim($explode[0]), trim($explode[1]));
                        $array["leave_days"]    = isset($leavedaysCount['totaldayCount']) ? $leavedaysCount['totaldayCount'] : 0;

                        $this->leaveapplication_m->update_leaveapplication($array, $leaveapplyID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('leaveapply/index'));
                    }
                } else {
                    $this->data["subview"] = 'leaveapply/edit';
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

    public function view()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/leaveapply/view.js'
            ]
        ];

        $leaveapplicationID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$leaveapplicationID) {
            $roleID      = $this->session->userdata('roleID');
            $loginuserID = $this->session->userdata('loginuserID');
            $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID'=> $leaveapplicationID, 'create_userID'=>$loginuserID, 'create_roleID'=> $roleID, 'year' => date('Y')));

            if(inicompute($leaveapplication)) {
                $this->data['jsmanager']     = ['myLeaveapplicationID' => $leaveapplicationID];
                $this->data['user']          = $this->user_m->get_single_user(array('userID' => $loginuserID));
                $this->data['designation']   = $this->designation_m->get_single_designation(array('designationID'=>$this->data['user']->designationID));
                $this->data['leaveapply']    = $leaveapplication;
                $leavecategoryID             = $this->data['leaveapply']->leavecategoryID;
                $this->data['leavecategory'] = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=> $leavecategoryID));

                $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($roleID, $loginuserID,  $leavecategoryID, date('Y'));

                if(isset($availableleave->days) && $availableleave->days > 0) {
                    $availableleavedays = $availableleave->days;
                } else {
                    $availableleavedays = 0;    
                }

                $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('roleID'=>$roleID,'leavecategoryID' => $leavecategoryID, 'year'=>date('Y')));
                if(inicompute($leaveassign)) {
                    $this->data['availableleavedays']   = ($leaveassign->leaveassignday - $availableleavedays);
                } else {
                    $this->data['availableleavedays']   = $this->lang->line('leaveapply_deleted');
                }

                $leavedaysCount           = $this->leavedaysinicompute($leaveapplication->from_date, $leaveapplication->to_date);
                $this->data["leave_days"] = isset($leavedaysCount['leavedayCount']) ? $leavedaysCount['leavedayCount'] : 0;

                $this->data["subview"] = 'leaveapply/view';
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
        $leaveapplicationID = escapeString($this->uri->segment('3'));
        if((int)$leaveapplicationID) {
            $roleID      = $this->session->userdata('roleID');
            $loginuserID = $this->session->userdata('loginuserID');
            $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID'=> $leaveapplicationID, 'create_userID'=>$loginuserID, 'create_roleID'=> $roleID, 'year'=>date('Y')));

            if(inicompute($leaveapplication)) {
                $this->data['user']          = $this->user_m->get_single_user(array('userID' => $loginuserID));
                $this->data['designation']   = $this->designation_m->get_single_designation(array('designationID'=>$this->data['user']->designationID));
                $this->data['leaveapply']    = $leaveapplication;
                $leavecategoryID             = $this->data['leaveapply']->leavecategoryID;
                $this->data['leavecategory'] = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=> $leavecategoryID));

                $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($roleID, $loginuserID,  $leavecategoryID, date('Y'));

                if(isset($availableleave->days) && $availableleave->days > 0) {
                    $availableleavedays = $availableleave->days;
                } else {
                    $availableleavedays = 0;    
                }

                $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('roleID'=>$roleID,'leavecategoryID' => $leavecategoryID, 'year'=>date('Y')));
                if(inicompute($leaveassign)) {
                    $this->data['availableleavedays']   = ($leaveassign->leaveassignday - $availableleavedays);
                } else {
                    $this->data['availableleavedays']   = $this->lang->line('leaveapply_deleted');
                }

                $leavedaysCount           = $this->leavedaysinicompute($leaveapplication->from_date, $leaveapplication->to_date);
                $this->data["leave_days"] = isset($leavedaysCount['leavedayCount']) ? $leavedaysCount['leavedayCount'] : 0;

                $this->report->reportPDF(['stylesheet' => 'leaveapplymodule.css', 'data' => $this->data, 'viewpath' => 'leaveapply/printpreview']);
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function sendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('user_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $leaveapplicationID = $this->input->post('leaveapplicationID');
                    if((int)$leaveapplicationID) {
                        $roleID      = $this->session->userdata('roleID');
                        $loginuserID = $this->session->userdata('loginuserID');
                        $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID'=> $leaveapplicationID, 'create_userID'=>$loginuserID, 'create_roleID'=> $roleID, 'year'=>date('Y')));

                        if(inicompute($leaveapplication)) {
                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');


                            $this->data['user']          = $this->user_m->get_single_user(array('userID' => $loginuserID));
                            $this->data['designation']   = $this->designation_m->get_single_designation(array('designationID'=>$this->data['user']->designationID));
                            $this->data['leaveapply']    = $leaveapplication;
                            $leavecategoryID             = $this->data['leaveapply']->leavecategoryID;
                            $this->data['leavecategory'] = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=> $leavecategoryID));

                            $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($roleID, $loginuserID,  $leavecategoryID, date('Y'));

                            if(isset($availableleave->days) && $availableleave->days > 0) {
                                $availableleavedays = $availableleave->days;
                            } else {
                                $availableleavedays = 0;    
                            }

                            $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('roleID'=>$roleID,'leavecategoryID' => $leavecategoryID, 'year'=>date('Y')));
                            if(inicompute($leaveassign)) {
                                $this->data['availableleavedays']   = ($leaveassign->leaveassignday - $availableleavedays);
                            } else {
                                $this->data['availableleavedays']   = $this->lang->line('leaveapply_deleted');
                            }

                            $leavedaysCount           = $this->leavedaysinicompute($leaveapplication->from_date, $leaveapplication->to_date);
                            $this->data["leave_days"] = isset($leavedaysCount['leavedayCount']) ? $leavedaysCount['leavedayCount'] : 0;

                            $this->report->reportSendToMail(['stylesheet' => 'leaveapplymodule.css', 'data' => $this->data, 'viewpath' => 'leaveapply/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {
                            $retArray['message'] = $this->lang->line('leaveapply_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('leaveapply_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('leaveapply_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('leaveapply_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("leaveapply_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("leaveapply_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("leaveapply_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'leaveapplicationID',
                'label' => $this->lang->line("leaveapply_leave_apply"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function delete()
    {
        $leaveapplicationID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$leaveapplicationID) {
            $loginuserID    = $this->session->userdata('loginuserID');
            $roleID         = $this->session->userdata('roleID');

            $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID'=> $leaveapplicationID, 'create_userID'=>$loginuserID, 'create_roleID'=> $roleID, 'year'=>date('Y'),'status'=>NULL));
            if(inicompute($leaveapplication) && ($leaveapplication->status==NULL)) {
                if(config_item('demo') == FALSE) {
                    if(file_exists(FCPATH.'uploads/files/'.$leaveapplication->attachment)) {
                        unlink(FCPATH.'uploads/files/'.$leaveapplication->attachment);
                    }
                }
                $this->leaveapplication_m->delete_leaveapplication($leaveapplicationID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('leaveapply/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function getuser()
    {
        $retArray = [];
        $retArray['status'] = FALSE;
        if($_POST) {
            $roleID  = $this->input->post('roleID');
            if((int)$roleID) {
                $users = $this->user_m->get_select_user('userID, name', array('roleID' => $roleID));
                if(inicompute($users)) {
                    $userID = $this->session->userdata('loginuserID');
                    $retArray['status'] = TRUE;
                    $options = '<option value="0">'.$this->lang->line('leaveapply_please_select').'</option>';
                    foreach($users as $user) {
                        if(($userID != $user->userID) || ($userID == 1)) {
                            $options .= '<option value="'.$user->userID.'">'.$user->name.'</option>';
                        }
                    }
                    $retArray['data'] = $options;
                } else {
                    $retArray['message'] = $this->lang->line('leaveapply_user_not_found');
                }
            } else {
                $retArray['message'] = $this->lang->line('leaveapply_user_not_found');
            }
        } else {
            $retArray['message'] = $this->lang->line('leaveapply_method_not_allow');
        }
        echo json_encode($retArray);
    }

    private function leavedaysinicompute($fromdate, $todate)
    {
        $leavedays = get_day_using_two_date(strtotime($fromdate), strtotime($todate));

        $leavedayCount   = 0;
        $totaldayCount   = 0;
        $retArray = [];
        if(inicompute($leavedays)) {
            foreach($leavedays as $leaveday) {
                $leavedayCount++;
                $totaldayCount++;
            }
        }

        $retArray['fromdate']        = $fromdate;
        $retArray['todate']          = $todate;
        $retArray['leavedayCount']   = $leavedayCount;
        $retArray['totaldayCount']   = $totaldayCount;
        return $retArray;
    }

    public function date_schedule_valid($date)
    {
        if($date) {
            $dateLength = strlen($date);
            if($dateLength == 23) {
                $dataArray  = explode('-', $date);
                $from_date = trim($dataArray[0]);
                $to_date = trim($dataArray[1]);

                if($from_date) {
                    if(strlen($from_date) != 10) {
                        $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                        return FALSE;
                    } else {
                        $arr = explode("/", $from_date);
                        $dd = $arr[1];
                        $mm = $arr[0];
                        $yyyy = $arr[2];
                        if(checkdate($mm, $dd, $yyyy)) {
                            if($to_date) {
                                if(strlen($to_date) != 10) {
                                    $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                                    return FALSE;
                                } else {
                                    $arr = explode("/", $to_date);
                                    $dd = $arr[1];
                                    $mm = $arr[0];
                                    $yyyy = $arr[2];
                                    if(checkdate($mm, $dd, $yyyy)) {
                                        return TRUE;
                                    } else {
                                        $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                                        return FALSE;
                                    }
                                }
                            } else {
                                $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                                return FALSE;
                            }
                        } else {
                            $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                            return FALSE;
                        }
                    }
                } else {
                    $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                    return FALSE;
                }
            } else {
                $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                return FALSE;
            }
        } else {
            return TRUE;
        }
    }

    public function required_no_zero($data)
    {
        if($data != '') {
            if($data == '0') {
                $this->form_validation->set_message("required_no_zero", "The %s field is required.");
                return FALSE;
            }
            return TRUE;
        }
        return TRUE;
    }

    public function unique_role()
    {
        $role = $this->role_m->get_single_role(array($this->input->post('roleID') => 3));
        if(inicompute($role)) {
            $this->form_validation->set_message("unique_role", "This %s is deny.");
            return false;
        }
        return true;
    }

    public function attachment_upload()
    {
        $new_file = "";
        if($_FILES["attachment"]['name'] != "") {
            $file_name = $_FILES["attachment"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = 'leaveapplication_'.$makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/files";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '1024';
                $config['max_width'] = '3000';
                $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("attachment")) {
                    $this->form_validation->set_message("attachment_upload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['attachment'] =  $this->upload->data();
                    $this->upload_data['attachment']['original_file_name'] =  $file_name;
                    return true;
                }
            } else {
                $this->form_validation->set_message("attachment_upload", "Invalid file");
                return false;
            }
        } else {
            $leaveapplicationID = escapeString($this->uri->segment('3'));
            if ((int)$leaveapplicationID) {
                $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID'=> $leaveapplicationID));
                if(inicompute($leaveapplication)) {
                    $this->upload_data['attachment']['file_name']          = $leaveapplication->attachment;
                    $this->upload_data['attachment']['original_file_name'] = $leaveapplication->attachmentorginalname;
                    return true;
                } else{
                    $this->upload_data['attachment']['file_name']          = $new_file;
                    $this->upload_data['attachment']['original_file_name'] = $new_file;
                    return true;
                }
            } else{
                $this->upload_data['attachment']['file_name']          = $new_file;
                $this->upload_data['attachment']['original_file_name'] = $new_file;
                return true;
            }
        }
    }

    public function download()
    {
        if(permissionChecker('leaveapply_view')) {
            $leaveapplicationID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$leaveapplicationID) {
                $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID));
                if(inicompute($leaveapplication)) {
                    $fileName = $leaveapplication->attachment;
                    $originalname = $leaveapplication->attachmentorginalname;
                    $file = realpath('uploads/files/'.$fileName);
                    if (file_exists($file)) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                        exit;
                    } else {
                        redirect(site_url('leaveapply/index'));
                    }
                } else {
                    redirect(site_url('leaveapply/index'));
                }
            } else {
                redirect(site_url('leaveapply/index'));
            }
        } else {
            redirect(site_url('leaveapply/index'));
        }
    }
}
