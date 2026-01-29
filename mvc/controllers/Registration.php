<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_m');
        $this->load->model('patient_m');
        $this->load->model('bloodgroup_m');
        $this->load->model('designation_m');
        $this->load->model('appointment_m');
        $this->load->model('admission_m');
        $this->load->model('bed_m');
        $this->load->model('heightweightbp_m');
        $this->load->model('prescription_m');
        $this->load->model('prescriptionitem_m');
        $this->load->model('instruction_m');
        $this->load->library('report');
        $this->load->library('mail');
        $language = $this->session->userdata('lang');
        $this->lang->load('registration', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("registration_name"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'guardianname',
                'label' => $this->lang->line("registration_guardianname"),
                'rules' => 'trim|max_length[40]'
            ),
            array(
                'field' => 'gender',
                'label' => $this->lang->line("registration_gender"),
                'rules' => 'trim|required|max_length[3]|callback_required_no_zero'
            ),
            array(
                'field' => 'age_day',
                'label' => $this->lang->line("registration_day"),
                'rules' => 'trim|numeric|max_length[2]|callback_unique_day'
            ),
            array(
                'field' => 'age_month',
                'label' => $this->lang->line("registration_month"),
                'rules' => 'trim|numeric|max_length[2]|callback_unique_month'
            ),
            array(
                'field' => 'age_year',
                'label' => $this->lang->line("registration_year"),
                'rules' => 'trim|numeric|max_length[3]|callback_unique_year'
            ),
            array(
                'field' => 'bloodgroupID',
                'label' => $this->lang->line("registration_bloodgroup"),
                'rules' => 'trim|numeric|max_length[3]'
            ),
            array(
                'field' => 'maritalstatus',
                'label' => $this->lang->line("registration_marital_status"),
                'rules' => 'trim|max_length[2]'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("registration_phone"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line("registration_address"),
                'rules' => 'trim|max_length[60]'
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("registration_photo"),
                'rules' => 'trim|callback_photoupload'
            ),
            array(
                'field' => 'username',
                'label' => $this->lang->line("registration_username"),
                'rules' => 'trim|required|min_length[4]|max_length[40]|callback_unique_username'
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("registration_email"),
                'rules' => 'trim|valid_email'
            ),
            array(
                'field' => 'password',
                'label' => $this->lang->line("registration_password"),
                'rules' => 'trim|required|min_length[4]|max_length[40]'
            )
        );
        return $rules;
    }

    private function _generateUsername($patientID)
    {
        $user = $this->user_m->get_select_user('username', ['delete_at' => 0]);
        if(inicompute($user)) {
            $user = multiArraySortForIntData($user, 'username');
        }
        return generateUsernameForPatient($user, $patientID);
    }

	public function index() 
    {
        $displayID = htmlentities(escapeString($this->uri->segment(3)));
        if($displayID == 2) {
            $this->data['registrations'] = $this->patient_m->get_select_patient('patientID, name, photo, gender, phone', array('YEAR(create_date)' => date('Y'), 'MONTH(create_date)' => date('m'), 'delete_at' => 0));
        } elseif($displayID == 3) {
            $this->data['registrations'] = $this->patient_m->get_select_patient('patientID, name, photo, gender, phone', array('YEAR(create_date)' => date('Y'), 'delete_at' => 0));
        } elseif($displayID == 4) {
            $this->data['registrations'] = $this->patient_m->get_select_patient('patientID, name, photo, gender, phone', array('delete_at' => 0));
        } else {
            $displayID = 1;
            $this->data['registrations'] = $this->patient_m->get_select_patient('patientID, name, photo, gender, phone', array('DATE(create_date)' => date('Y-m-d'), 'delete_at' => 0));
        }

        $this->data['displayID']     = $displayID;
        if(permissionChecker('registration_add')) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/inilabs/registration/index.css',
                ),
                'js' => array(
                    'assets/select2/select2.js',
                    'assets/inilabs/registration/index.js',
                )
            );
            
            $getpatient = $this->patient_m->get_select_patient('patientID, name', [], true);
            if($getpatient == null) {
                $this->data['patientuhid']  = $this->_generateUsername(1001);
                $array['patientID']         = 1001;
            } else {
                $generatePatientID = ($getpatient->patientID + 1);
                $this->data['patientuhid']  = $this->_generateUsername($generatePatientID);
            }

            $this->data['bloodgroups'] = $this->bloodgroup_m->get_bloodgroup();
            $this->data['setPhoto']    = '';
            $this->data['jsmanager']   = [ 'setPhoto' => '' ];

            if($_POST) {
                if($this->input->post('photo') != '') {
                    $this->data['setPhoto']     = $_POST['photo'];
                    $this->data['jsmanager']   = [ 'setPhoto' => $_POST['photo'] ];
                }

                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'registration/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['name']          = $this->input->post('name');
                    $array['guardianname']  = $this->input->post('guardianname');
                    $array['gender']        = $this->input->post('gender');
                    $array['age_day']       = $this->input->post('age_day');
                    $array['age_month']     = $this->input->post('age_month');
                    $array['age_year']      = $this->input->post('age_year');
                    $array['maritalstatus'] = $this->input->post('maritalstatus');
                    $array['phone']         = $this->input->post('phone');
                    $array['address']       = $this->input->post('address');
                    $array['bloodgroupID']  = $this->input->post('bloodgroupID');
                    $array['email']         = $this->input->post('email');
                    $array['photo']         = $this->upload_data['photo'];
                    $array['patienttypeID'] = 5;
                    $array['delete_at']     = 0;
                    $array['create_date']   = date('Y-m-d H:i:s');
                    $array['modify_date']   = date('Y-m-d H:i:s');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_roleID'] = $this->session->userdata('roleID');

                    $this->patient_m->insert_patient($array);
                    $patientID = $this->db->insert_id();

                    $birthday = agetobirthday($this->input->post('age_day'), $this->input->post('age_month'), $this->input->post('age_year'));
                    $userArray['name']              = $this->input->post('name');
                    $userArray['designationID']     = 2;
                    $userArray['description']       = '';
                    $userArray['gender']            = $this->input->post('gender');
                    $userArray['dob']               = $birthday;
                    $userArray['jod']               = date('Y-m-d');
                    $userArray['email']             = $this->input->post('email');;
                    $userArray['phone']             = $this->input->post('phone');
                    $userArray['address']           = $this->input->post('address');
                    $userArray['roleID']            = 3;
                    $userArray['photo']             = $this->upload_data['photo'];
                    $userArray['username']          = $this->input->post('username');
                    $userArray['password']          = $this->user_m->hash($this->input->post('password'));
                    $userArray['create_date']       = date("Y-m-d H:i:s");
                    $userArray['modify_date']       = date("Y-m-d H:i:s");
                    $userArray['create_userID']     = $this->session->userdata('loginuserID');
                    $userArray['create_roleID']     = $this->session->userdata('roleID');
                    $userArray['status']            = 1;
                    $userArray['delete_at']         = 0;
                    $userArray['patientID']         = $patientID;

                    $this->_forEmailSend($userArray);

                    $this->user_m->insert_user($userArray);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('registration/index/'.$displayID));
                }
            } else {
    		    $this->data["subview"] = 'registration/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'registration/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    private function _forEmailSend($array)
    {
        $email     = $array['email'];
        if($email) {
            $passArray                      = $array;
            $passArray['password']          = $this->input->post('password');
            $passArray['generalsettings']   = $this->data['generalsettings'];

            $message   = $this->load->view('registration/mail', $passArray, TRUE);
            $message   = trim($message);
            $subject   = $this->lang->line('registration_patient')." ".$this->lang->line('registration_registration');
            @$this->mail->sendmail($this->data, $email, $subject, $message);
        }
    }

    public function edit() 
    {
        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$patientID) {
            $this->data['patient']   = $this->patient_m->get_single_patient(array('patientID' => $patientID, 'delete_at' => 0));
            $this->data['user']      = $this->user_m->get_single_user(array('patientID'=> $patientID));
            if(inicompute($this->data['patient']) && inicompute($this->data['user'])) {
                $displayID = htmlentities(escapeString($this->uri->segment(4)));
                if($displayID == 2) {
                    $this->data['registrations'] = $this->patient_m->get_select_patient('patientID, name, photo, gender, phone', array('YEAR(create_date)' => date('Y'), 'MONTH(create_date)' => date('m'), 'delete_at' => 0));
                } elseif($displayID == 3) {
                    $this->data['registrations'] = $this->patient_m->get_select_patient('patientID, name, photo, gender, phone', array('YEAR(create_date)' => date('Y'), 'delete_at' => 0));
                } elseif($displayID == 4) {
                    $this->data['registrations'] = $this->patient_m->get_select_patient('patientID, name, photo, gender, phone', array('delete_at' => 0));
                } else {
                    $displayID = 1;
                    $this->data['registrations'] = $this->patient_m->get_select_patient('patientID, name, photo, gender, phone', array('DATE(create_date)' => date('Y-m-d'), 'delete_at' => 0));
                }

                $this->data['displayID']     = $displayID;
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/inilabs/registration/index.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/inilabs/registration/edit.js',
                    )
                );

                $this->data['bloodgroups']   = $this->bloodgroup_m->get_bloodgroup();
                $this->data['setPhoto']      = '';
                $this->data['jsmanager']   = [ 'setPhoto' => '', 'image' => $this->data['patient']->photo ];

                if($_POST) {
                    if($this->input->post('photo') != '') {
                        $this->data['setPhoto']     = $_POST['photo'];
                        $this->data['jsmanager']   = [ 'setPhoto' => $_POST['photo'] ];
                    }
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'registration/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array['name']          = $this->input->post('name');
                        $array['guardianname']  = $this->input->post('guardianname');
                        $array['gender']        = $this->input->post('gender');
                        $array['age_day']       = $this->input->post('age_day');
                        $array['age_month']     = $this->input->post('age_month');
                        $array['age_year']      = $this->input->post('age_year');
                        $array['maritalstatus'] = $this->input->post('maritalstatus');
                        $array['phone']         = $this->input->post('phone');
                        $array['address']       = $this->input->post('address');
                        $array['bloodgroupID']  = $this->input->post('bloodgroupID');
                        $array['email']         = $this->input->post('email');
                        $array['photo']         = (empty($this->upload_data['photo']) ? 'default.png' : $this->upload_data['photo']);
                        $array['modify_date']   = date('Y-m-d H:i:s');

                        $this->patient_m->update_patient($array, $patientID);
                        $birthday = agetobirthday($this->input->post('age_day'), $this->input->post('age_month'), $this->input->post('age_year'));

                        $userArray['name']              = $this->input->post('name');
                        $userArray['gender']            = $this->input->post('gender');
                        $userArray['dob']               = $birthday;
                        $userArray['email']             = $this->input->post('email');
                        $userArray['phone']             = $this->input->post('phone');
                        $userArray['address']           = $this->input->post('address');
                        $userArray['username']          = $this->input->post('username');
                        $userArray['photo']             = (empty($this->upload_data['photo']) ? 'default.png' : $this->upload_data['photo']);
                        $userArray['password']          = $this->user_m->hash($this->input->post('password'));
                        $userArray['modify_date']       = date("Y-m-d H:i:s");

                        $this->user_m->update_user($userArray, $this->data['user']->userID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('registration/index/'.$displayID));
                    }
                } else {
                    $this->data["subview"] = 'registration/edit';
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
        $this->data['headerassets'] = array(
            'js' => array(
                'assets/inilabs/registration/view.js',
            )
        );

        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$patientID && (int)$displayID) {
            $this->data['jsmanager'] = ['patientID' => $patientID];
            $this->data['patient']  =  $this->patient_m->get_single_patient(array('patientID' => $patientID, 'delete_at' => 0));
            $this->data['user']     = $this->user_m->get_single_user(array('patientID'=> $patientID));
            if(inicompute($this->data['patient']) && inicompute($this->data['user'])) {
                $this->data['displayID'] = $displayID;
                $this->data['designation']   = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                $this->data['bloodgroup']   = $this->bloodgroup_m->get_single_bloodgroup(array('bloodgroupID' => $this->data['patient']->bloodgroupID));
                $this->data["subview"]       = 'registration/view';
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
        if(permissionChecker('registration_view')) {
            $patientID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$patientID) {
                $this->data['patient']  =  $this->patient_m->get_single_patient(array('patientID' => $patientID, 'delete_at' => 0));
                $this->data['user']     = $this->user_m->get_single_user(array('patientID'=> $patientID));
                if(inicompute($this->data['patient']) && inicompute($this->data['user'])) {
                    $this->data['designation']  = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                    $this->data['bloodgroup']   = $this->bloodgroup_m->get_single_bloodgroup(array('bloodgroupID' => $this->data['patient']->bloodgroupID));
                    $this->report->reportPDF(['stylesheet' => 'registrationviewmodule.css', 'data' => $this->data, 'viewpath' => 'registration/printpreview']);
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
        if(permissionChecker('registration_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $patientID = $this->input->post('patientID');
                    if((int)$patientID) {
                        $this->data['patient']  =  $this->patient_m->get_single_patient(array('patientID' => $patientID, 'delete_at' => 0));
                        $this->data['user']     = $this->user_m->get_single_user(array('patientID'=> $patientID));
                        if(inicompute($this->data['patient']) && inicompute($this->data['user'])) {
                            $this->data['designation']   = $this->designation_m->get_single_designation(array('designationID' => $this->data['user']->designationID));
                            $this->data['bloodgroup']   = $this->bloodgroup_m->get_single_bloodgroup(array('bloodgroupID' => $this->data['patient']->bloodgroupID));
                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');
                            
                            $this->report->reportSendToMail(['stylesheet' => 'registrationviewmodule.css', 'data' => $this->data, 'viewpath' => 'registration/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {    
                            $retArray['message'] = $this->lang->line('registration_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('registration_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('registration_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('registration_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("registration_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("registration_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("registration_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("registration_patient"),
                'rules' => 'trim|required|numeric'
            )
        );
        return $rules;
    }

    public function card() 
    {
        if(permissionChecker('registration_view')) {
            $patientID = htmlentities(escapeString($this->uri->segment(3)));
            $displayID = htmlentities(escapeString($this->uri->segment(4)));
            if((int)$patientID && (int)$displayID) {
                $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID, 'delete_at' => 0));
                if(inicompute($this->data['patient'])) {
                    $this->data['displayID']     = $displayID;
                    $this->data["subview"]       = 'registration/card';
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
    }

    public function frontendcard() 
    {
        if(permissionChecker('registration_view')) {
            $patientID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$patientID) {
                $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID, 'delete_at' => 0));
                if(inicompute($this->data['patient'])) {
                    $this->report->reportPDF(['stylesheet' => 'registrationmodule.css', 'data' => $this->data, 'viewpath' => 'registration/frontendcard', 'designnone' => 0]);
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

    public function backendcard() 
    {
        if(permissionChecker('registration_view')) {
            $patientID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$patientID) {
                $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID, 'delete_at' => 0));
                if(inicompute($this->data['patient'])) {
                    $this->report->reportPDF(['stylesheet' => 'registrationmodule.css', 'data' => $this->data, 'viewpath' => 'registration/backendcard', 'designnone' => 0]);
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

    public function delete() 
    {
        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$patientID && (int)$displayID) {
            $registration = $this->patient_m->get_single_patient(array('patientID' => $patientID, 'delete_at' => 0));
            if(inicompute($registration)) {
                $user = $this->user_m->get_single_user(array('patientID' => $patientID));
                if(inicompute($user)) {
                    $appointments =  $this->appointment_m->get_order_by_appointment(['patientID' => $patientID, 'status' => 0]);
                    if(inicompute($appointments)) {
                        foreach ($appointments as $appointment) {
                            $heightWeightBp = $this->heightweightbp_m->get_single_heightweightbp(['patienttypeID' => 0, 'appointmentandadmissionID' => $appointment->appointmentID]);
                            $this->heightweightbp_m->delete_heightweightbp($heightWeightBp->heightweightbpID);
                            $this->appointment_m->delete_appointment($appointment->appointmentID);
                        }
                    }

                    $admission = $this->admission_m->get_single_admission(['patientID' => $patientID, 'status' => 0]);
                    if(inicompute($admission)) {
                        $heightWeightBps = $this->heightweightbp_m->get_order_by_heightweightbp(['patienttypeID' => 1, 'appointmentandadmissionID' => $admission->admissionID]);
                        if(inicompute($heightWeightBps)) {
                            foreach ($heightWeightBps as $heightWeightBp) {
                                $this->heightweightbp_m->delete_heightweightbp($heightWeightBp->heightweightbpID);
                            }
                        }

                        $bed = $this->bed_m->get_single_bed(array('patientID' => $admission->patientID, 'bedID' => $admission->bedID, 'status' => 1));
                        if(inicompute($bed)) {
                            $this->bed_m->update(array('patientID' => 0, 'status' => 0), $bed->bedID);
                        }

                        if($admission->prescriptionstatus) {
                            $prescription = $this->prescription_m->get_single_prescription(['patienttypeID' => 1, 'appointmentandadmissionID' => $admission->admissionID]);
                            if(inicompute($prescription)) {
                                $prescriptionitems = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $prescription->prescriptionID]);
                                if(inicompute($prescriptionitems)) {
                                    foreach ($prescriptionitems as $prescriptionitem) {
                                        $this->prescriptionitem_m->delete_prescriptionitem($prescriptionitem->prescriptionitemID);
                                    }
                                }
                            }
                            $this->prescription_m->delete_prescription($prescription->prescriptionID);
                        }

                        $instructions = $this->instruction_m->get_order_by_instruction(['admissionID' => $admission->admissionID]);
                        if(inicompute($instructions)) {
                            foreach ($instructions as $instruction) {
                                $this->instruction_m->delete_instruction($instruction->instructionID);
                            }
                        }

                        $this->admission_m->delete_admission($admission->admissionID);
                    }

                    $this->patient_m->update_patient(['delete_at' => 1], $patientID);
                    $this->user_m->update_user(['delete_at' => 1, 'status' => 0], $user->userID);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('registration/index/'.$displayID));
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

    public function newuhid()
    {
        $patient = $this->patient_m->get_select_patient('patientID, name', [], true);
        if(inicompute($patient)) {
            $generatePatientID = ($patient->patientID + 1);
            echo $this->_generateUsername($generatePatientID);
        } else {
            echo '1001';
        }
    }

    public function unique_username($data) 
    {
        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$patientID && (int)$displayID) {
            $registration = $this->user_m->get_order_by_user(array("username" => $data, "patientID !=" => $patientID));
            if(inicompute($registration)) {
                $this->form_validation->set_message("unique_username", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $registration = $this->user_m->get_order_by_user(array("username" => $data));
            if(inicompute($registration)) {
                $this->form_validation->set_message("unique_username", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }
    
    public function photoupload($photo) 
    {
        $new_file = "default.png";
        if($photo != "") {
            $random     = random19();
            $file_name  = hash('sha512', $random . config_item("encryption_key"));
            $new_file   = $file_name.'.png';
            $decoded    = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photo));
            file_put_contents('uploads/user/'.$new_file, $decoded);
            $this->upload_data['photo'] =  $new_file;
            return TRUE;
        } else {
            $patientID = htmlentities(escapeString($this->uri->segment(3)));
            $displayID = htmlentities(escapeString($this->uri->segment(4)));
            if((int)$patientID && (int)$displayID) {
                $patient = $this->patient_m->get_single_patient(array('patientID' => $patientID));
                if (inicompute($patient)) {
                    $this->upload_data['photo'] = $patient->photo;
                    return TRUE;         
                } else{
                    $this->upload_data['photo'] = $new_file;
                    return TRUE;         
                }
            } else{
                $this->upload_data['photo'] = $new_file;
                return TRUE;
            }
        }
    }

    public function unique_email()
    {
        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$patientID && (int)$displayID) {
            if(!empty($this->input->post('email'))) {
               $user = $this->user_m->get_single_user(array('email' => $this->input->post('email'), 'patientID !=' => $patientID));
               if(inicompute($user)) {
                    $this->form_validation->set_message('unique_email', 'The %s has already existed.');
                    return false;
               }
               return true;
            }
            return true;
        } else {
            if(!empty($this->input->post('email'))) {
               $user = $this->user_m->get_single_user(array('email' => $this->input->post('email')));
               if(inicompute($user)) {
                    $this->form_validation->set_message('unique_email', 'The %s has already existed.');
                    return false;
               }
               return true;
            }
            return true;
        }
    }

    public function unique_day()
    {
        if($this->input->post('age_day')) {
            if($this->input->post('age_day') > 30) {
                $this->form_validation->set_message('unique_day', 'This %s can not be longer than 30 days');
                return false;
            }
            return true;
        }
        return true;
    }

    public function unique_month()
    {
        if($this->input->post('age_month')) {
            if($this->input->post('age_month') > 11) {
                $this->form_validation->set_message('unique_month', 'This %s can not be longer than 12 months');
                return false;
            }
            return true;
        }
        return true;
    }

    public function unique_year()
    {
        if($this->input->post('age_year')) {
            if($this->input->post('age_year') > 999) {
                $this->form_validation->set_message('unique_year', 'This %s can not be longer than 999 years');
                return false;
            }
            return true;
        }
        return true;
    }
}
