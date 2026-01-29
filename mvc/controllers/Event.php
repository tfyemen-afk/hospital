<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends Admin_Controller
{
    public function __construct()
     {
        parent::__construct();
        $this->load->model('role_m');
        $this->load->model('user_m');
        $this->load->model('event_m');
        $this->load->model('eventcounter_m');

        $this->load->library('report');
        $language = $this->session->userdata('lang');;
        $this->lang->load('event', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("event_title"),
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("event_date"),
                'rules' => 'trim|required|callback_date_schedule_valid'
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("event_photo"),
                'rules' => 'trim|requiredmax_length[200]|callback_photoupload'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("event_description"),
                'rules' => 'trim|required'
            )
        );
        return $rules;
    }

	public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/daterangepicker/css/daterangepicker.css',
            ),
            'js' => array(
                'assets/daterangepicker/js/moment.min.js',
                'assets/daterangepicker/js/daterangepicker.js',
                'assets/inilabs/event/index.js',
            )
        );
        
        $displayID = htmlentities(escapeString($this->uri->segment('3')));
        if($displayID == 2) {
            $this->data['events']    = $this->event_m->get_order_by_event(array('YEAR(create_date)' => date('Y'), 'MONTH(create_date)' => date('m')));
        } elseif($displayID == 3) {
            $this->data['events']    = $this->event_m->get_order_by_event(array('YEAR(create_date)' => date('Y')));
        } elseif($displayID == 4) {
            $this->data['events']    = $this->event_m->get_event();
        } else {
            $displayID = 1;
            $this->data['events']    = $this->event_m->get_order_by_event(array('DATE(create_date)' => date('Y-m-d')));
        }
        $this->data['displayID']     = $displayID;

        if(permissionChecker('event_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"]    = 'event/index';
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $explode   = explode('-', $this->input->post("date"));
                    $from_date = trim($explode[0]);
                    $to_date   = trim($explode[1]);
                    $from_date = str_replace('/', '-', $from_date);
                    $to_date   = str_replace('/', '-', $to_date);
                    $from_date = strtotime($from_date);
                    $to_date   = strtotime($to_date);

                    $array["title"]          = $this->input->post("title");
                    $array["fdate"]          = date("Y-m-d", $from_date);
                    $array["ftime"]          = date('H:i:s', $from_date);
                    $array["tdate"]          = date("Y-m-d", $to_date);
                    $array["ttime"]          = date('H:i:s', $to_date);
                    $array["description"]    = $this->input->post("description");
                    $array["photo"]          = $this->upload_data['photo']['file_name'];
                    $array["create_date"]    = date("Y-m-d H:i:s");
                    $array["modify_date"]    = date("Y-m-d H:i:s");
                    $array["create_userID"]  = $this->session->userdata('loginuserID');
                    $array["create_roleID"]  = $this->session->userdata('roleID');
                    $this->event_m->insert_event($array);

                    $notificationArray = [
                        'itemID'    => $this->db->insert_id(),
                        'userID'    => $this->session->userdata('loginuserID'),
                        'itemname'  => 'event'
                    ];
                    $this->notification_m->insert_notification($notificationArray);

                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('event/index'));
                }
            } else {
    		    $this->data["subview"] = 'event/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'event/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit()
    {
        $eventID    = htmlentities(escapeString($this->uri->segment('3')));
        $displayID  = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$eventID && (int)$displayID) {
            $this->data['event']   = $this->event_m->get_single_event(array('eventID'=> $eventID));
            if(inicompute($this->data['event'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/daterangepicker/css/daterangepicker.css',
                    ),
                    'js' => array(
                        'assets/daterangepicker/js/moment.min.js',
                        'assets/daterangepicker/js/daterangepicker.js',
                        'assets/inilabs/event/index.js',
                    )
                );
                if($displayID == 2) {
                    $this->data['events']    = $this->event_m->get_order_by_event(array('YEAR(create_date)' => date('Y'), 'MONTH(create_date)' => date('m')));
                } elseif($displayID == 3) {
                    $this->data['events']    = $this->event_m->get_order_by_event(array('YEAR(create_date)' => date('Y')));
                } elseif($displayID == 4) {
                    $this->data['events']    = $this->event_m->get_event();
                } else {
                    $displayID = 1;
                    $this->data['events']    = $this->event_m->get_order_by_event(array('DATE(create_date)' => date('Y-m-d')));
                }
                $this->data['displayID']     = $displayID;

                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'event/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $explode   = explode('-', $this->input->post("date"));
                        $from_date = trim($explode[0]);
                        $to_date   = trim($explode[1]);
                        $from_date = str_replace('/', '-', $from_date);
                        $to_date   = str_replace('/', '-', $to_date);
                        $from_date = strtotime($from_date);
                        $to_date   = strtotime($to_date);

                        $array["title"]        = $this->input->post("title");
                        $array["fdate"]        = date("Y-m-d", $from_date);
                        $array["ftime"]        = date('H:i:s', $from_date);
                        $array["tdate"]        = date("Y-m-d", $to_date);
                        $array["ttime"]        = date('H:i:s', $to_date);
                        $array["description"]  = $this->input->post("description");
                        $array["photo"]        = $this->upload_data['photo']['file_name'];
                        $array["modify_date"]  = date("Y-m-d H:i:s");
                        $this->event_m->update_event($array, $eventID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('event/index/'.$displayID));
                    }
                } else {
                    $this->data["subview"] = 'event/edit';
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
                'assets/inilabs/event/view.js'
            ]
        ];

        $eventID    = htmlentities(escapeString($this->uri->segment('3')));
        $displayID  = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$eventID && (int)$displayID) {
            $this->data["event"] = $this->event_m->get_single_event(array('eventID'=> $eventID));
            if(inicompute($this->data["event"])) {
                $this->data['jsmanager'] = ['myEventID' => $eventID];
                $notificationArray = [
                    'itemID'    => $eventID,
                    'userID'    => $this->session->userdata('loginuserID'),
                    'itemname'  => 'event'
                ];
                $notification = $this->notification_m->get_single_notification($notificationArray);
                if(!inicompute($notification)) {
                    $this->notification_m->insert_notification($notificationArray);
                }
                $this->data["goings"]    = $this->eventcounter_m->get_order_by_eventcounter(array('eventID'=> $eventID, 'status'=>1));

                $this->data["ignores"]   = $this->eventcounter_m->get_order_by_eventcounter(array('eventID'=> $eventID, 'status'=>0));
                $this->data['roles']     = pluck($this->role_m->get_role(), 'role', 'roleID');
                $this->data['displayID'] = $displayID;
                $this->data["subview"]   = 'event/view';
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
        if(permissionChecker('event_view')) {
            $eventID = htmlentities(escapeString($this->uri->segment('3')));
            if((int)$eventID) {
                $this->data["event"] = $this->event_m->get_single_event(array('eventID'=> $eventID));
                if(inicompute($this->data["event"])) {
                    $userID     = $this->data['event']->create_userID;
                    $roleID     = $this->data['event']->create_roleID;
                    $user = $this->user_m->get_select_user('userID, name' ,array('userID'=>$userID), TRUE);
                    $role = $this->role_m->get_single_role(array('roleID'=>$roleID));
                    $this->data['userRole'] = inicompute($role) ? $role->role : '';
                    $this->data['userName'] = inicompute($user) ? $user->name : '';

                    $this->report->reportPDF(['stylesheet' => 'eventmodule.css', 'data' => $this->data, 'viewpath' => 'event/printpreview']);
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
        if(permissionChecker('event_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $eventID = $this->input->post('eventID');
                    $this->data['event'] = $this->event_m->get_single_event(array('eventID' => $eventID));
                    if(inicompute($this->data['event'])) {
                        $email      = $this->input->post('to');
                        $subject    = $this->input->post('subject');
                        $message    = $this->input->post('message');

                        $userID     = $this->data['event']->create_userID;
                        $roleID     = $this->data['event']->create_roleID;
                        $user = $this->user_m->get_select_user('userID, name' ,array('userID'=>$userID), TRUE);
                        $role = $this->role_m->get_single_role(array('roleID'=>$roleID));
                        $this->data['userRole'] = inicompute($role) ? $role->role : '';
                        $this->data['userName'] = inicompute($user) ? $user->name : '';

                        $this->report->reportSendToMail(['stylesheet' => 'eventmodule.css', 'data' => $this->data, 'viewpath' => 'event/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                        $retArray['message'] = "Success";
                        $retArray['status']  = TRUE;
                    } else {
                        $retArray['message'] = $this->lang->line('event_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('event_permissionmethod');
            }
        } else {
            $retArray['message'] = $this->lang->line('event_permission');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("event_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("event_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("event_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'eventID',
                'label' => $this->lang->line("event_event"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function delete()
    {
        $eventID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$eventID && (int)$displayID) {
            $event = $this->event_m->get_single_event(array('eventID'=> $eventID));
            if(inicompute($event)) {
                if(config_item('demo') == FALSE) {
                    if($event->photo != 'holiday.png') {
                        if(file_exists(FCPATH.'uploads/files/'.$event->photo)) {
                            unlink(FCPATH.'uploads/files/'.$event->photo);
                        }
                    }
                }
                $this->event_m->delete_event($eventID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('event/index/'.$displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function eventcounter()
    {
        $retArray = [];
        $retArray['status']  = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('event_view')) {
            if($_POST) {
                $eventID = $this->input->post('eventID');
                $roleID  = $this->session->userdata("roleID");
                $userID  = $this->session->userdata("loginuserID");
                if((int)$eventID) {
                    $event        = $this->event_m->get_single_event(array('eventID'=>$eventID));
                    if(inicompute($event)) {
                        $current_date = strtotime(date("Y-m-d H:i:s"));
                        $to_date      = $event->tdate.' '.$event->ttime;
                        $to_date      = strtotime($to_date);

                        $disable = FALSE;
                        if($current_date > $to_date) {
                            $disable = TRUE;
                        }

                        if(!$disable) {
                            $eventcounter = $this->eventcounter_m->get_single_eventcounter(array("eventID" => $eventID, "userID" => $userID, "roleID" => $roleID));
                            $array['name']    = $this->session->userdata("name");
                            $array['photo']   = $this->session->userdata("photo");
                            $array['status']  = $this->input->post('status');
                            if(inicompute($eventcounter)) {
                                $this->eventcounter_m->update($array, $eventcounter->eventcounterID);
                            } else {
                                dump('on');
                                $array['eventID']  = $eventID;
                                $array['roleID']   = $roleID;
                                $array['userID']   = $userID;
                                $this->eventcounter_m->insert($array);
                            }
                            $retArray['status'] = TRUE;
                            $this->session->set_flashdata('success', 'Success');
                        } else {
                            $retArray['message'] = $this->lang->line('event_event_expire_expire');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('event_data_not_found');
                    }
                } else {
                    $retArray['message'] = $this->lang->line('event_data_not_found');
                }
            } else {
                $retArray['message'] = $this->lang->line('event_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('event_permission_not_allowed');
        }
        echo json_encode($retArray);
    }

    public function date_schedule_valid($date)
    {
        if($date) {
            $dateLength = strlen($date);
            if($dateLength == 41) {
                $dataArray  = explode('-', $date);
                $from_date  = trim($dataArray[0]);
                $to_date    = trim($dataArray[1]);

                if($from_date) {
                    if(strlen($from_date) != 19) {
                        $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                        return FALSE;
                    } else {
                        $arr  = explode("/", $from_date);
                        $dd   = $arr[0];
                        $mm   = $arr[1];
                        $yyyy = $this->_get_year($arr[2]);
                        if(checkdate($mm, $dd, $yyyy)) {
                            if($to_date) {
                                if(strlen($to_date) != 19) {
                                    $this->form_validation->set_message("date_schedule_valid", "The %s is not valid dd-mm-yyyy.");
                                    return FALSE;
                                } else {
                                    $arr    = explode("/", $to_date);
                                    $dd     = $arr[0];
                                    $mm     = $arr[1];
                                    $yyyy   = $this->_get_year($arr[2]);
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

    public function photoupload() 
    {
        $new_file = "holiday.png";
        if($_FILES["photo"]['name'] != "") {
            $file_name = $_FILES["photo"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = 'event_'.$makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path']   = "./uploads/files";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name']     = $new_file;
                $config['max_size']      = '1024';
                $config['max_width']     = '3000';
                $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['photo'] =  $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
            $eventID   = htmlentities(escapeString($this->uri->segment('3')));
            $displayID = htmlentities(escapeString($this->uri->segment('4')));
            if ((int)$eventID && (int)$displayID) {
                $event = $this->event_m->get_single_event(array('eventID' => $eventID));
                if (inicompute($event)) {
                    $this->upload_data['photo'] = array('file_name' => $event->photo);
                    return true;
                } else{
                    $this->upload_data['photo'] = array('file_name' => $new_file);
                    return true;
                }
            } else{
                $this->upload_data['photo'] = array('file_name' => $new_file);
                return true;
            }
        }
    }

    private function _get_year($date)
    {
        $arr = explode(' ', $date);
        return $arr[0];
    }

}
