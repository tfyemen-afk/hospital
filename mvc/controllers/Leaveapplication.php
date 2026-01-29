<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaveapplication extends Admin_Controller
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
        $this->lang->load('leaveapplication', $language);
    }

	public function index()
    {
        $roleID      = $this->session->userdata('roleID');
        $loginuserID = $this->session->userdata('loginuserID');

        if($roleID != 1) {
            $this->data['leaveapplications'] = $this->leaveapplication_m->get_order_by_leaveapplication(array('year' => date('Y')));
        } else {
            $this->data['leaveapplications'] = $this->leaveapplication_m->get_order_by_leaveapplication(array('applicationto_userID'=> $loginuserID, 'applicationto_roleID' => $roleID, 'year' => date('Y')));
        }

        $this->data['leavecategorys']       = pluck($this->leavecategory_m->get_leavecategory(),'obj','leavecategoryID');
        $this->data['designations']         = pluck($this->designation_m->get_select_designation('designationID, designation'),'designation','designationID');
        $this->data['users']                = pluck($this->user_m->get_select_user('userID, name, designationID', array('roleID != ' => 3)), 'obj', 'userID');
        $this->data["subview"] = 'leaveapplication/index';
        $this->load->view('_layout_main', $this->data);
	}

    public function view()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/leaveapplication/view.js'
            ]
        ];

        if(permissionChecker('leaveapplication')) {
            $leaveapplicationID = htmlentities(escapeString($this->uri->segment('3')));
            if ((int)$leaveapplicationID) {
                $roleID = $this->session->userdata('roleID');
                $loginuserID = $this->session->userdata('loginuserID');

                $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID, 'year' => date('Y')));
                if($leaveapplication) {
                    $this->data['user'] = $this->user_m->get_single_user(array('userID' => $leaveapplication->create_userID));
                    if (inicompute($leaveapplication) && inicompute($this->data['user'])) {
                        $this->data['jsmanager'] = ['myLeaveapplicationID' => $leaveapplicationID];
                        if ((($leaveapplication->applicationto_userID == $loginuserID) && ($leaveapplication->applicationto_roleID == $roleID)) || ($roleID == 1)) {
                            $this->data['designation'] = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                            $this->data['leavecategory'] = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $leaveapplication->leavecategoryID));

                            $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($leaveapplication->create_roleID, $leaveapplication->create_userID, $leaveapplication->leavecategoryID, date('Y'));
                            if (isset($availableleave->days) && $availableleave->days > 0) {
                                $availableleavedays = $availableleave->days;
                            } else {
                                $availableleavedays = 0;
                            }

                            $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('roleID' => $roleID, 'leavecategoryID' => $leaveapplication->leavecategoryID, 'year' => date('Y')));
                            if (inicompute($leaveassign)) {
                                $this->data['availableleavedays'] = ($leaveassign->leaveassignday - $availableleavedays);
                            } else {
                                $this->data['availableleavedays'] = $this->lang->line('leaveapplication_deleted');
                            }

                            $leavedaysCount = $this->leavedaysinicompute($leaveapplication->from_date, $leaveapplication->to_date);
                            $this->data["leave_days"] = isset($leavedaysCount['leavedayCount']) ? $leavedaysCount['leavedayCount'] : 0;

                            $this->data['leaveapplication'] = $leaveapplication;
                            $this->data["subview"] = 'leaveapplication/view';
                            $this->load->view('_layout_main', $this->data);
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
            $roleID           = $this->session->userdata('roleID');
            $loginuserID      = $this->session->userdata('loginuserID');

            $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID, 'year' => date('Y')));
            $this->data['user']  = $this->user_m->get_single_user(array('userID' => $leaveapplication->create_userID));
            if(inicompute($leaveapplication) && inicompute($this->data['user'])) {
                if((($leaveapplication->applicationto_userID == $loginuserID) && ($leaveapplication->applicationto_roleID == $roleID)) || ($roleID == 1)) {
                    $this->data['designation']   = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                    $this->data['leavecategory'] = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=> $leaveapplication->leavecategoryID));

                    $availableleave     = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($leaveapplication->create_roleID, $leaveapplication->create_userID,  $leaveapplication->leavecategoryID, date('Y'));
                    if(isset($availableleave->days) && $availableleave->days > 0) {
                        $availableleavedays = $availableleave->days;
                    } else {
                        $availableleavedays = 0;
                    }

                    $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('roleID' => $roleID, 'leavecategoryID' => $leaveapplication->leavecategoryID, 'year'=>date('Y')));
                    if(inicompute($leaveassign)) {
                        $this->data['availableleavedays']   = ($leaveassign->leaveassignday - $availableleavedays);
                    } else {
                        $this->data['availableleavedays']   = $this->lang->line('leaveapplication_deleted');
                    }

                    $leavedaysCount        = $this->leavedaysinicompute($leaveapplication->from_date, $leaveapplication->to_date);
                    $this->data["leave_days"]   = isset($leavedaysCount['leavedayCount']) ? $leavedaysCount['leavedayCount'] : 0;

                    $this->data['leaveapplication']   = $leaveapplication;
                    
                    $this->report->reportPDF(['stylesheet' => 'leaveapplicationmodule.css', 'data' => $this->data, 'viewpath' => 'leaveapplication/printpreview']);
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
                        $roleID           = $this->session->userdata('roleID');
                        $loginuserID      = $this->session->userdata('loginuserID');

                        $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID, 'year' => date('Y')));
                        $this->data['user']  = $this->user_m->get_single_user(array('userID' => $leaveapplication->create_userID));
                        if(inicompute($leaveapplication) && inicompute($this->data['user'])) {
                            
                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');

                            if((($leaveapplication->applicationto_userID == $loginuserID) && ($leaveapplication->applicationto_roleID == $roleID)) || ($roleID == 1)) {
                                $this->data['designation']   = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                                $this->data['leavecategory'] = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID'=> $leaveapplication->leavecategoryID));

                                $availableleave     = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($leaveapplication->create_roleID, $leaveapplication->create_userID,  $leaveapplication->leavecategoryID, date('Y'));
                                if(isset($availableleave->days) && $availableleave->days > 0) {
                                    $availableleavedays = $availableleave->days;
                                } else {
                                    $availableleavedays = 0;
                                }

                                $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('roleID' => $roleID, 'leavecategoryID' => $leaveapplication->leavecategoryID, 'year'=>date('Y')));
                                if(inicompute($leaveassign)) {
                                    $this->data['availableleavedays']   = ($leaveassign->leaveassignday - $availableleavedays);
                                } else {
                                    $this->data['availableleavedays']   = $this->lang->line('leaveapplication_deleted');
                                }

                                $leavedaysCount        = $this->leavedaysinicompute($leaveapplication->from_date, $leaveapplication->to_date);
                                $this->data["leave_days"]   = isset($leavedaysCount['leavedayCount']) ? $leavedaysCount['leavedayCount'] : 0;

                                $this->data['leaveapplication']   = $leaveapplication;
                                
                                $this->report->reportSendToMail(['stylesheet' => 'leaveapplicationmodule.css', 'data' => $this->data, 'viewpath' => 'leaveapplication/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                $retArray['message'] = "Success";
                                $retArray['status']  = TRUE;
                            } else {
                                $retArray['message'] = $this->lang->line('leaveapplication_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('leaveapplication_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('leaveapplication_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('leaveapplication_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('leaveapplication_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("leaveapplication_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("leaveapplication_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("leaveapplication_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'leaveapplicationID',
                'label' => $this->lang->line("leaveapplication_leave_application"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function status()
    {
        $roleID      = $this->session->userdata("roleID");
        $loginuserID = $this->session->userdata("loginuserID");
        $leaveapplicationID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$leaveapplicationID) {
            $leaveapplication = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $leaveapplicationID, 'year' => date('Y')));
            if (inicompute($leaveapplication)) {
                if($roleID == 1 || ($leaveapplication->applicationto_roleID == $roleID && $leaveapplication->applicationto_userID == $loginuserID)) {
                    if ($leaveapplication->status == 1) {
                        $array["status"] = 0;
                    } else {
                        $array["status"] = 1;
                    }
                    $array["modify_date"] = date("Y-m-d H:i:s");
                    $this->leaveapplication_m->update_leaveapplication($array, $leaveapplicationID);
                    $this->session->set_flashdata('message', 'Success');
                    redirect('leaveapplication/index');
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

    public function download()
    {
        if(permissionChecker('leaveapplication')) {
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
                        redirect(site_url('leaveapplication/index'));
                    }
                } else {
                    redirect(site_url('leaveapplication/index'));
                }
            } else {
                redirect(site_url('leaveapplication/index'));
            }
        } else {
            redirect(site_url('leaveapplication/index'));
        }
    }
}
