<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notice extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_m");
		$this->load->model("role_m");
		$this->load->model("notice_m");
		$this->load->library('report');
		$language = $this->session->userdata('lang');
		$this->lang->load('notice', $language);
	}

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'title',
                'label' => $this->lang->line("notice_title"),
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'date',
                'label' => $this->lang->line("notice_date"),
                'rules' => 'trim|required|max_length[10]|callback_valid_date'
            ),
            array(
                'field' => 'notice',
                'label' => $this->lang->line("notice_notice"),
                'rules' => 'trim|required'
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
                'assets/inilabs/datepicker.js'
            )
        );

        $displayID = htmlentities(escapeString($this->uri->segment(3)));
        if($displayID == 2) {
            $this->data['notices']    = $this->notice_m->get_order_by_notice(array('YEAR(date)' => date('Y'), 'MONTH(date)' => date('m')));
        } elseif($displayID == 3) {
            $this->data['notices']    = $this->notice_m->get_order_by_notice(array('YEAR(date)' => date('Y')));
        } elseif($displayID == 4) {
            $this->data['notices']    = $this->notice_m->get_notice();
        } else {
            $displayID = 1;
            $this->data['notices']    = $this->notice_m->get_order_by_notice(array('DATE(date)' => date('Y-m-d')));
        }
        $this->data['displayID']     = $displayID;

		if(permissionChecker('notice_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if($this->form_validation->run()) {
					$array = array(
						'title'  		=> $this->input->post('title'),
						"notice" 		=> $this->input->post('notice'),
						"date"   		=> date("Y-m-d", strtotime($this->input->post("date"))),
						'year'			=> date('Y'),
						"create_date"   => date("Y-m-d H:i:s"),
						"modify_date"   => date("Y-m-d H:i:s"),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_roleID" => $this->session->userdata('roleID')
					);
					$this->notice_m->insert_notice($array);
                    $notificationArray = [
                        'itemID'    => $this->db->insert_id(),
                        'userID'    => $this->session->userdata('loginuserID'),
                        'itemname'  => 'notice'
                    ];
					$this->notification_m->insert_notification($notificationArray);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("notice/index"));
				}
			}
		}
		$this->data["subview"] = "notice/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function edit() 
	{
		$noticeID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayID  = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$noticeID && (int)$displayID) {
			$this->data['notice'] 	= $this->notice_m->get_single_notice(array('noticeID' => $noticeID));
			if(inicompute($this->data['notice'])) {
				$this->data['headerassets'] = array(
		            'css' => array(
		                'assets/datepicker/dist/css/bootstrap-datepicker.css',
		            ),
		            'js' => array(
		                'assets/datepicker/dist/js/bootstrap-datepicker.js',
                        'assets/inilabs/datepicker.js'
		            )
		        );
				
				$displayID = htmlentities(escapeString($this->uri->segment('4')));
		        if($displayID == 2) {
		            $this->data['notices']    = $this->notice_m->get_order_by_notice(array('YEAR(date)' => date('Y'), 'MONTH(date)' => date('m')));
		        } elseif($displayID == 3) {
		            $this->data['notices']    = $this->notice_m->get_order_by_notice(array('YEAR(date)' => date('Y')));
		        } elseif($displayID == 4) {
		            $this->data['notices']    = $this->notice_m->get_notice();
		        } else {
		            $displayID = 1;
		            $this->data['notices']    = $this->notice_m->get_order_by_notice(array('DATE(date)' => date('Y-m-d')));
		        }
		        $this->data['displayID']     = $displayID;

				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if($this->form_validation->run() == false) {
						$this->data["subview"] = "notice/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array(
							'title'  => $this->input->post('title'),
							'notice' => $this->input->post('notice'),
							'date'   => date('Y-m-d', strtotime($this->input->post('date'))),
							'modify_date' => date('Y-m-d H:i:s'),
						);

						$this->notice_m->update_notice($array, $noticeID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("notice/index/".$displayID));
					}
				} else {
					$this->data["subview"] = "notice/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "_not_found";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "_not_found";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function view() 
	{
	    $this->data['headerassets'] = [
	        'js' => [
	            'assets/inilabs/notice/view.js'
            ]
        ];

		$noticeID  = htmlentities(escapeString($this->uri->segment(3)));
		$displayID = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$noticeID && (int)$displayID) {
			$this->data['notice']    = $this->notice_m->get_single_notice(array('noticeID' => $noticeID));
			if(inicompute($this->data['notice'])) {
			    $this->data['jsmanager'] = ['myNoticeID' => $noticeID];
                $notificationArray = [
                    'itemID'    => $noticeID,
                    'userID'    => $this->session->userdata('loginuserID'),
                    'itemname'  => 'notice'
                ];
                $notification = $this->notification_m->get_single_notification($notificationArray);
                if(!inicompute($notification)) {
                    $this->notification_m->insert_notification($notificationArray);
                }

				$this->data['displayID'] = $displayID;
				$this->data["subview"]   = "notice/view";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "_not_found";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "_not_found";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() 
	{
        $noticeID  = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$noticeID && (int)$displayID) {
        	$notice = $this->notice_m->get_single_notice(array('noticeID' => $noticeID));
        	if(inicompute($notice)) {
	            $this->notice_m->delete_notice($noticeID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('notice/index/'.$displayID));
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
		if(permissionChecker('notice_view')) {
			$noticeID = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$noticeID) {
				$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $noticeID));
				if(inicompute($this->data['notice'])) {
					$userID     = $this->data['notice']->create_userID;
					$roleID     = $this->data['notice']->create_roleID;

					$user = $this->user_m->get_select_user('userID, name' ,array('userID'=>$userID), TRUE);
					$role = $this->role_m->get_single_role(array('roleID'=>$roleID));
					$this->data['userRole'] = inicompute($role) ? $role->role : '';
					$this->data['userName'] = inicompute($user) ? $user->name : '';

					$this->report->reportPDF(['stylesheet' => 'noticemodule.css', 'data' => $this->data, 'viewpath' => 'notice/printpreview']);
				} else {
					$this->data["subview"] = "_not_found";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "_not_found";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "_not_found";
			$this->load->view('_layout_main', $this->data);
		}
	}
    
	public function sendmail() {
		$retArray['message'] = '';
		$retArray['status']  = FALSE;
		if(permissionChecker('notice_view')) {
			if($_POST) {
				$rules = $this->sendmail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status']  = FALSE;
				} else {
					$noticeID = $this->input->post('noticeID');
					$this->data['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $noticeID));
					if(inicompute($this->data['notice'])) {
						$email      = $this->input->post('to');
						$subject    = $this->input->post('subject');
						$message    = $this->input->post('message');
						$userID     = $this->data['notice']->create_userID;
						$roleID     = $this->data['notice']->create_roleID;

						$user = $this->user_m->get_select_user('userID, name' ,array('userID'=>$userID), TRUE);
						$role = $this->role_m->get_single_role(array('roleID'=>$roleID));
						$this->data['userRole'] = inicompute($role) ? $role->role : '';
						$this->data['userName'] = inicompute($user) ? $user->name : '';

						$this->report->reportSendToMail(['stylesheet' => 'noticemodule.css', 'data' => $this->data, 'viewpath' => 'notice/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
						$retArray['message'] = "Success";
						$retArray['status']  = TRUE;
					} else {
						$retArray['message'] = $this->lang->line('notice_data_not_found');
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('notice_permissionmethod');
			}
		} else {
			$retArray['message'] = $this->lang->line('notice_permission');
		}
		echo json_encode($retArray);
		exit;
	}

	protected function sendmail_rules() 
	{
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("notice_to"),
				'rules' => 'trim|required|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("notice_subject"),
				'rules' => 'trim|required'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("notice_message"),
				'rules' => 'trim'
			),
			array(
				'field' => 'noticeID',
				'label' => $this->lang->line("notice_notice"),
				'rules' => 'trim|required|numeric'
			)
		);
		return $rules;
	}

	public function valid_date($date) 
	{
		if($date) {
			if(strlen($date) <10) {
				$this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
		     	return FALSE;
			} else {
		   		$arr = explode("-", $date);
		        $dd = $arr[0];
		        $mm = $arr[1];
		        $yyyy = $arr[2];
		      	if(checkdate($mm, $dd, $yyyy)) {
		      		return TRUE;
		      	} else {
		      		$this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
		     		return FALSE;
		      	}
		    }
		} else {
			return TRUE;
		}
	}
}