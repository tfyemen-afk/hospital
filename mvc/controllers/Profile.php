<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_m');
        $this->load->model('role_m');
        $this->load->model('designation_m');
        $this->load->model('attendance_m');
        $this->load->model('makepayment_m');
        $this->load->model('managesalary_m');
        $this->load->model('salarytemplate_m');
        $this->load->model('hourlytemplate_m');
        $this->load->model('salaryoption_m');
        $this->load->model('document_m');
        $this->load->model('patient_m');
        $this->load->model('bloodgroup_m');
        $this->load->model('appointment_m');
        $this->load->model('admission_m');
        $this->load->model('instruction_m');
        $this->load->model('heightweightbp_m');
        $this->load->model('billlabel_m');
        $this->load->model('billitems_m');
        $this->load->model('billpayment_m');
        $this->load->model('testcategory_m');
        $this->load->model('testlabel_m');
        $this->load->model('test_m');
        $this->load->model('prescription_m');
        $this->load->model('prescriptionitem_m');
        $this->load->model('discharge_m');
        $this->load->model('ward_m');
        $this->load->model('room_m');
        $this->load->model('floor_m');
        $this->load->model('bed_m');
        $this->load->model('testfile_m');

        $this->load->library('report');

        $language = $this->session->userdata('lang');;
        $this->lang->load('profile', $language);
    }

    public function index() 
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
            ),
            'js' => array(
                'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js'
            )
        );
        $userID = $this->session->userdata('loginuserID');;
        if((int)$userID) {
            $this->data['user'] = $this->user_m->get_single_user(array('userID'=>$userID));
            if(inicompute($this->data['user'])) {
                $this->pluckInfo();
                if($this->data['loginroleID'] == 3) {
                    $this->data['headerassets']['js'][] = 'assets/inilabs/profile/index_patient.js';
                    $this->patientInfo();
                    $this->data["subview"] = 'profile/index_patient';
                } else {
                    $this->data['headerassets']['js'][] = 'assets/inilabs/profile/index.js';
                    $this->basicInfo($userID);
                    $this->attendanceInfo($userID);
                    $this->salaryInfo();
                    $this->paymentInfo($userID);
                    $this->documentInfo($userID);
                    $this->data["subview"] = 'profile/index';
                }
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
        $userID = $this->session->userdata('loginuserID');
        if((int)$userID) {
            $this->data['user'] = $this->user_m->get_single_user(array('userID'=>$userID));
            if(inicompute($this->data['user'])) {
                $this->pluckInfo();
                if($this->data['loginroleID'] == 3) {
                    $patientID             = $this->data['loginuserID'];
                    $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID));
                    if(inicompute($this->data['patient'])) {
                        $this->data['maritalstatus']    = [
                            0   => '',
                            1   => $this->lang->line('profile_single'),
                            2   => $this->lang->line('profile_married'),
                            3   => $this->lang->line('profile_separated'),
                            4   => $this->lang->line('profile_divorced')
                        ];
                        $this->data['bloodgroups']      = pluck($this->bloodgroup_m->get_bloodgroup(), 'bloodgroup', 'bloodgroupID');
                        $this->report->reportPDF(['stylesheet' => 'profilemodule.css', 'data' => $this->data, 'viewpath' => 'profile/printpreview_patient']);
                    } else {
                        $this->data["subview"] = '_not_found';
                        $this->load->view('_layout_main', $this->data);
                    }
                } else {
                    $this->report->reportPDF(['stylesheet' => 'profilemodule.css', 'data' => $this->data, 'viewpath' => 'profile/printpreview']);
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
        if($_POST) {
            $rules = $this->sendmail_rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $retArray = $this->form_validation->error_array();
                $retArray['status']  = FALSE;
            } else {
                $userID = $this->session->userdata('loginuserID');
                if((int)$userID) {
                    $this->data['user'] = $this->user_m->get_single_user(array('userID'=>$userID));
                    if(inicompute($this->data['user'])) {
                        $email      = $this->input->post('to');
                        $subject    = $this->input->post('subject');
                        $message    = $this->input->post('message');

                        $this->pluckInfo();
                        if($this->data['loginroleID'] == 3) {
                            $patientID             = $this->data['loginuserID'];
                            $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID));
                            if(inicompute($this->data['patient'])) {
                                $this->data['maritalstatus']    = [
                                    0   => '',
                                    1   => $this->lang->line('profile_single'),
                                    2   => $this->lang->line('profile_married'),
                                    3   => $this->lang->line('profile_separated'),
                                    4   => $this->lang->line('profile_divorced')
                                ];
                                $this->data['bloodgroups']      = pluck($this->bloodgroup_m->get_bloodgroup(), 'bloodgroup', 'bloodgroupID');
                                $this->report->reportSendToMail(['stylesheet' => 'profilemodule.css', 'data' => $this->data, 'viewpath' => 'profile/printpreview_patient', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                $retArray['message'] = "Success";
                                $retArray['status']  = TRUE;
                            } else {
                                $retArray['message'] = $this->lang->line('profile_data_not_found');
                            }
                        } else {
                            $this->report->reportSendToMail(['stylesheet' => 'profilemodule.css', 'data' => $this->data, 'viewpath' => 'profile/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('profile_data_not_found');
                    }
                } else {
                    $retArray['message'] = $this->lang->line('profile_data_not_found');
                }
            }
        } else {
            $retArray['message'] = $this->lang->line('profile_method_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("profile_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("profile_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("profile_message"),
                'rules' => 'trim'
            )
        );
        return $rules;
    }

    public function prescription()
    {
        $appointmentAndAdmissionTypeID      = htmlentities(escapeString($this->uri->segment(3)));
        $appointmentAndAdmissionID          = htmlentities(escapeString($this->uri->segment(4)));
        $patientID                          = $this->data['loginuserID'];
        if((int)$appointmentAndAdmissionID && (int)$patientID && ($appointmentAndAdmissionTypeID == 0 || $appointmentAndAdmissionTypeID == 1)) {
            $this->data['prescription'] = $this->prescription_m->get_single_prescription(['patientID' => $patientID, 'patienttypeID' => $appointmentAndAdmissionTypeID, 'appointmentandadmissionID' => $appointmentAndAdmissionID]);
            if(inicompute($this->data['prescription'])) {
                $this->data['patientinfo']      = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                $this->data['gender']           = [0 => '', 1 => 'M', 2 => 'F'];
                if($this->data['prescription']->patienttypeID == 0) {
                    $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                } else {
                    $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                }
                $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                $this->prescriptionitem_m->order('prescriptionitemID asc');
                $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $this->data['prescription']->prescriptionID]);
                $this->data["subview"] = 'profile/prescription';
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

    public function discharge()
    {
        $admissionID    = htmlentities(escapeString($this->uri->segment(3)));
        $patientID      = $this->data['loginuserID'];
        if (((int)$admissionID && (int)$patientID)) {
            $this->data['discharge'] = $this->discharge_m->get_single_discharge(['admissionID' => $admissionID, 'patientID' => $patientID]);
            if (inicompute($this->data['discharge'])) {
                $this->data['patient'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name, patient.age_day, patient.age_month, patient.age_year, patient.gender, admission.admissiondate, admission.wardID, admission.bedID', ['patient.patientID' => $this->data['discharge']->patientID, 'admissionID' =>  $this->data['discharge']->admissionID], true);
                if(inicompute($this->data['patient'])) {
                    $this->data['genders']      = [0 => '', 1 => $this->lang->line('profile_male'), 2 => $this->lang->line('profile_female')];
                    $this->data['conditions']   = [1 => $this->lang->line('profile_stable'), 2 => $this->lang->line('profile_almost_stable'), 3 => $this->lang->line('profile_own_risk'), 4 => $this->lang->line('profile_unstable')];
                    $this->data['ward'] = $this->ward_m->get_single_ward(['wardID' => $this->data['patient']->wardID]);
                    if(inicompute($this->data['ward'])) {
                        $this->data['room'] = $this->room_m->get_single_room(['roomID' => $this->data['ward']->roomID]);
                    } else {
                        $this->data['room'] = [];
                    }
                    if(inicompute($this->data['ward'])) {
                        $this->data['floor'] = $this->floor_m->get_single_floor(['floorID' => $this->data['ward']->floorID]);
                    } else {
                        $this->data['floor'] = [];
                    }
                    $this->data['bed'] = $this->bed_m->get_single_bed(['bedID' => $this->data['patient']->bedID]);
                    $this->data["subview"] = 'profile/discharge';
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

    public function pluckInfo()
    {
        $this->data['roles']        = pluck($this->role_m->get_role(),'role', 'roleID');
        $this->data['designations'] = pluck($this->designation_m->get_designation(),'designation', 'designationID');
    }

    private function patientInfo()
    {
        $this->data['maritalstatus']    = [
            0   => '',
            1   => $this->lang->line('profile_single'),
            2   => $this->lang->line('profile_married'),
            3   => $this->lang->line('profile_separated'),
            4   => $this->lang->line('profile_divorced')
        ];
        $this->data['paymentmethods']   = [
            0  => '',
            1  => $this->lang->line('profile_cash'),
            2  => $this->lang->line('profile_cheque'),
            3  => $this->lang->line('profile_credit_card'),
            4  => $this->lang->line('profile_other')
        ];
        $patientID             = $this->data['loginuserID'];
        $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID));
        $this->data['bloodgroups']      = pluck($this->bloodgroup_m->get_bloodgroup(), 'bloodgroup', 'bloodgroupID');

        $this->data['doctors']          = pluck($this->user_m->get_select_user('userID, name', array('roleID' => 2)), 'name', 'userID');
        $this->data['appointments']     = $this->appointment_m->get_order_by_appointment(array('patientID' => $patientID, 'status' => 1));
        $this->data['admissions']       = $this->admission_m->get_order_by_admission(array('patientID' => $patientID, 'status' => 1));
        $this->data['heightweightbps']  = $this->heightweightbp_m->get_order_by(array('patientID' => $patientID));
        $this->data['instructions']     = $this->instruction_m->get_instruction_with_user(['instruction.patientID' => $patientID]);
        $this->data['billlabels']       = pluck($this->billlabel_m->get_billlabel(),'obj','billlabelID');
        $this->data['billitems']        = $this->billitems_m->get_order_by_billitems(['patientID' => $patientID, 'delete_at' => 0]);

        $this->billpayment_m->order('create_date desc');
        $this->data['billpayments']     = $this->billpayment_m->get_order_by_billpayment(['patientID' => $patientID, 'delete_at' => 0]);
        $this->data['testcategorys']    = pluck($this->testcategory_m->get_testcategory(), 'name', 'testcategoryID');
        $this->data['testlabels']       = pluck($this->testlabel_m->get_testlabel(), 'name', 'testlabelID');
        $this->data['tests']            = $this->test_m->get_select_test_with_bill_patient('test.testID, test.testlabelID, test.testcategoryID, test.billID, test.create_date', ['patient.patientID' => $patientID, 'bill.delete_at' => 0]);
    }

    public function testfile()
    {
        $testID = $this->input->post('testID');
        if((int)$testID) {
            $this->data['testfiles'] = $this->testfile_m->get_order_by_testfile(array('testID'=> $testID));
            $this->load->view('profile/testfile', $this->data);
        } else {
            echo $this->lang->line('profile_data_not_found');
        }
    }

    public function basicInfo($userID)
    {
        $this->data['managesalary']  = $this->managesalary_m->get_single_managesalary(array('userID'=>$userID));
    }

    public function attendanceInfo($userID)
    {
        $this->data["attendances"]  = pluck($this->attendance_m->get_order_by_attendance(array('year'=> date('Y'),'userID'=>$userID)), 'obj', 'monthyear');
    }

    public function salaryInfo()
    {
        if(inicompute($this->data['managesalary'])) {
            if ($this->data['managesalary']->salary == '1') {
                $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $this->data['managesalary']->template));
                if(inicompute($this->data['salarytemplate'])) {
                    $this->db->order_by("salaryoptionID", "asc");
                    $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $this->data['managesalary']->template));

                    $grosssalary      = 0;
                    $totaldeduction   = 0;
                    $netsalary        = $this->data['salarytemplate']->basic_salary;
                    $orginalNetsalary = $this->data['salarytemplate']->basic_salary;

                    if(inicompute($this->data['salaryoptions'])) {
                        foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                            if($salaryOption->option_type == 1) {
                                $netsalary += $salaryOption->label_amount;
                                $grosssalary += $salaryOption->label_amount;
                            } elseif($salaryOption->option_type == 2) {
                                $netsalary -= $salaryOption->label_amount;
                                $totaldeduction += $salaryOption->label_amount;
                            }
                        }
                    }
                    
                    $this->data['grosssalary']    = $grosssalary+$orginalNetsalary;
                    $this->data['totaldeduction'] = $totaldeduction;
                    $this->data['netsalary']      = $netsalary;
                }  
            } elseif($this->data['managesalary']->salary == '2') {
                $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID'=> $this->data['managesalary']->template));
                if(inicompute($this->data['hourly_salary'])) {
                    $this->data['grosssalary'] = 0;
                    $this->data['totaldeduction'] = 0;
                    $this->data['netsalary'] = $this->data['hourly_salary']->hourly_rate;
                }
            }
        }
    }

    public function paymentInfo($userID) 
    {
        $this->db->order_by('makepaymentID', 'DESC');
        $this->data["makepayments"]  = $this->makepayment_m->get_order_by_makepayment(array('year'=> date('Y'),'userID'=>$userID));
    }

    public function documentInfo($userID) 
    {
        $this->data['documents'] = $this->document_m->get_order_by_document(array('userID' => $userID));
    }

    public function downloaddocument() 
    {
        $documentID = htmlentities(escapeString($this->uri->segment(3)));
        $userID     = $this->data['loginuserID'];
        if((int)$documentID && (int)$userID) {
            $document = $this->document_m->get_single_document(array('documentID' => $documentID, 'userID'=> $userID));
            if(inicompute($document)) {
                $file = realpath('uploads/files/'.$document->file);
                if (file_exists($file)) {
                    $expFileName = explode('.', $file);
                    $originalname = ($document->title).'.'.end($expFileName);
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
                    redirect(site_url('profile/index'));
                }
            } else {
                redirect(site_url('profile/index'));
            }
        } else {
            redirect(site_url('profile/index'));
        }
    }

    public function filedownload()
    {
        $testfileID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$testfileID) {
            $testfile = $this->testfile_m->get_single_testfile(array('testfileID'=>$testfileID));
            if(inicompute($testfile)) {
                $file = realpath('uploads/files/'.$testfile->filename);
                if (file_exists($file)) {
                    $originalname = $testfile->fileoriginalname;
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
                    redirect(site_url('profile/index'));
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
}
