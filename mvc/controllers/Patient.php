<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends Admin_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('designation_m');
        $this->load->model('bloodgroup_m');
        $this->load->model('doctorinfo_m');
        $this->load->model('tpa_m');
        $this->load->model('user_m');
        $this->load->model('ward_m');
        $this->load->model('bed_m');
        $this->load->model('patient_m');
        $this->load->model('appointment_m');
        $this->load->model('admission_m');
        $this->load->model('heightweightbp_m');
        $this->load->model('prescription_m');
        $this->load->model('prescriptionitem_m');
        $this->load->model('discharge_m');
        $this->load->model('room_m');
        $this->load->model('floor_m');
        $this->load->model('billitems_m');
        $this->load->model('billlabel_m');
        $this->load->model('billpayment_m');
        $this->load->model('test_m');
        $this->load->model('testcategory_m');
        $this->load->model('testlabel_m');
        $this->load->model('instruction_m');
        $this->load->model('testfile_m'); 
        $this->load->library('report');
        $this->load->library('mail');

        $language = $this->session->userdata('lang');
        $this->lang->load('patient', $language);
        $this->load->helper('security');
    }

    private function rules($array = []) 
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("patient_name"),
                'rules' => 'trim|required|xss_clean|max_length[40]'
            ),
            array(
                'field' => 'username',
                'label' => $this->lang->line("patient_username"),
                'rules' => 'trim|required|xss_clean|min_length[4]|max_length[40]|callback_unique_username'
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("patient_email"),
                'rules' => 'trim|xss_clean|valid_email|min_length[4]|max_length[40]|callback_unique_email'
            ),
            array(
                'field' => 'password',
                'label' => $this->lang->line("patient_password"),
                'rules' => 'trim|required|xss_clean|min_length[4]|max_length[40]'
            ),
            array(
                'field' => 'guardianname',
                'label' => $this->lang->line("patient_guardianname"),
                'rules' => 'trim|xss_clean|max_length[40]'
            ),
            array(
                'field' => 'gender',
                'label' => $this->lang->line("patient_gender"),
                'rules' => 'trim|required|numeric|xss_clean|max_length[1]|callback_unique_data'
            ),
            array(
                'field' => 'maritalstatus',
                'label' => $this->lang->line("patient_maritalstatus"),
                'rules' => 'trim|numeric|xss_clean|max_length[1]'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("patient_phone"),
                'rules' => 'trim|xss_clean|min_length[5]|max_length[25]'
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line("patient_address"),
                'rules' => 'trim|xss_clean|max_length[200]'
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("patient_photo"),
                'rules' => 'trim|max_length[200]|xss_clean|callback_photoupload'
            ),
            array(
                'field' => 'age_day',
                'label' => $this->lang->line("patient_day"),
                'rules' => 'trim|numeric|max_length[2]|callback_unique_day'
            ),
            array(
                'field' => 'age_month',
                'label' => $this->lang->line("patient_month"),
                'rules' => 'trim|numeric|max_length[2]|callback_unique_month'
            ),
            array(
                'field' => 'age_year',
                'label' => $this->lang->line("patient_year"),
                'rules' => 'trim|numeric|max_length[3]|callback_unique_year'
            ),
            array(
                'field' => 'bloodgroupID',
                'label' => $this->lang->line("patient_bloodgroup"),
                'rules' => 'trim|numeric|xss_clean|max_length[1]'
            ),
            array(
                'field' => 'height',
                'label' => $this->lang->line("patient_height"),
                'rules' => 'trim|xss_clean|numeric|max_length[3]'
            ),
            array(
                'field' => 'weight',
                'label' => $this->lang->line("patient_weight"),
                'rules' => 'trim|xss_clean|numeric|max_length[3]'
            ),
            array(
                'field' => 'bp',
                'label' => $this->lang->line("patient_bp"),
                'rules' => 'trim|xss_clean|max_length[10]'
            ),
            array(
                'field' => 'symptoms',
                'label' => $this->lang->line("patient_symptoms"),
                'rules' => 'trim|xss_clean|max_length[1000]'
            ),
            array(
                'field' => 'allergies',
                'label' => $this->lang->line("patient_allergies"),
                'rules' => 'trim|xss_clean|max_length[1000]'
            ),
            array(
                'field' => 'note',
                'label' => $this->lang->line("patient_note"),
                'rules' => 'trim|xss_clean|max_length[1000]'
            ),
            array(
                'field' => 'patienttypeID',
                'label' => $this->lang->line("patient_patienttype"),
                'rules' => 'trim|required|numeric|xss_clean|max_length[1]'
            ),
            array(
                'field' => 'case',
                'label' => $this->lang->line("patient_case"),
                'rules' => 'trim|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'casualty',
                'label' => $this->lang->line("patient_casualty"),
                'rules' => 'trim|numeric|xss_clean|max_length[1]'
            ),
            array(
                'field' => 'oldpatient',
                'label' => $this->lang->line("patient_oldpatient"),
                'rules' => 'trim|required|xss_clean|max_length[1]|callback_unique_oldpatient'
            ),
            array(
                'field' => 'tpaID',
                'label' => $this->lang->line("patient_tpa"),
                'rules' => 'trim|numeric|xss_clean|max_length[11]'
            ),
            array(
                'field' => 'reference',
                'label' => $this->lang->line("patient_reference"),
                'rules' => 'trim|xss_clean|max_length[128]'
            ),
            array(
                'field' => 'doctorID',
                'label' => $this->lang->line("patient_doctor"),
                'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_data'
            ),
        );

        if(isset($array['patienttypeID'])) {
            if($array['patienttypeID'] == 0) {
                $rules[] = array(
                    'field' => 'appointmentdate',
                    'label' => $this->lang->line("patient_appointmentdate"),
                    'rules' => 'trim|required|xss_clean|min_length[19]|max_length[19]|callback_valid_date|callback_unique_appointmentdate'
                );
                $rules[] = array(
                    'field' => 'amount',
                    'label' => $this->lang->line("patient_amount"),
                    'rules' => 'trim|required|numeric|xss_clean|max_length[15]'
                );
                $rules[] = array(
                    'field' => 'paymentstatus',
                    'label' => $this->lang->line("patient_payment"),
                    'rules' => 'trim|required|numeric|max_length[1]|callback_unique_data'
                );

                if(isset($array['paymentstatus']) && ($array['paymentstatus'] == 1)) {
                    $rules[] = array(
                        'field' => 'paymentmethodID',
                        'label' => $this->lang->line("patient_paymentmethod"),
                        'rules' => 'trim|required|numeric|xss_clean|max_length[1]|callback_unique_data'
                    );
                }
            } else {
                $rules[] = array(
                    'field' => 'admissiondate',
                    'label' => $this->lang->line("patient_admissiondate"),
                    'rules' => 'trim|required|xss_clean|min_length[19]|max_length[19]|callback_valid_date|callback_unique_admissiondate'
                );
                $rules[] = array(
                    'field' => 'creditlimit',
                    'label' => $this->lang->line("patient_creditlimit"),
                    'rules' => 'trim|required|numeric|xss_clean|max_length[15]'
                );
                $rules[] = array(
                    'field' => 'wardID',
                    'label' => $this->lang->line("patient_ward"),
                    'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_data'
                );
                $rules[] = array(
                    'field' => 'bedID',
                    'label' => $this->lang->line("patient_bedno"),
                    'rules' => 'trim|required|numeric|xss_clean|max_length[11]|callback_unique_data|callback_unique_bed'
                );
            }
        }

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
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/datetimepicker/css/datetimepicker.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/datetimepicker/js/datetimepicker.js',
                'assets/inilabs/patient/index.js'
            )
        );

        $this->data['jsmanager'] = ['date' => date('Y-m-d'), 'myPatienttypeID' => 0];
        if($this->data['loginroleID'] == 3) {
            if(!permissionChecker('patient_view')) {
                $this->_profile($this->data['loginuserID']);
            }
        } else {
            $this->data['activelisttab']    = true;
            $this->data['activeaddtab']     = false;
            $this->data['patienttypeID']    = 0;
            $this->data['beds']             = [];

            $this->data['patients']         = $this->patient_m->get_order_by_patient(array('delete_at' => 0, 'patienttypeID !=' => 5));
            if(permissionChecker('patient_add')) {
                $this->data['bloodgroups']      = $this->bloodgroup_m->get_bloodgroup();
                $this->data['tpas']             = $this->tpa_m->get_select_tpa('tpaID, name');
                $this->data['doctors']          = $this->user_m->get_select_user('userID, name', array('roleID' => 2, 'status' => 1, 'delete_at' => 0));
                $this->data['wards']            = $this->ward_m->get_select_ward('wardID, name');
                $getpatient                     = $this->patient_m->get_select_patient('patientID, name', [], true);

                if($getpatient == null) {
                    $this->data['patientuhid']  = $this->_generateUsername(1001);
                } else {
                    $generatePatientID = ($getpatient->patientID + 1);
                    $this->data['patientuhid']  = $this->_generateUsername($generatePatientID);
                }

                if($_POST) {
                    $this->data['activelisttab']    = false;
                    $this->data['activeaddtab']     = true;
                    $this->data['patienttypeID']    = $this->input->post('patienttypeID');
                    $this->data['jsmanager']        = ['date' => date('Y-m-d'), 'myPatienttypeID' => $this->data['patienttypeID']];

                    if($this->input->post('wardID')) {
                        $this->data['beds'] = $this->bed_m->get_order_by(array('wardID' => $this->input->post('wardID'), 'status' => 0));
                    }

                    $rulesArray = [
                        'patienttypeID' => $this->input->post('patienttypeID'),
                        'paymentstatus' => $this->input->post('paymentstatus'),
                    ];

                    $rules  = $this->rules($rulesArray);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'patient/index';
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $appointmentandadmissionID  = 0;
                        if($getpatient == null) {
                            $patientArray['patientID']          = $this->_generateUsername(1001);
                        }

                        $patientArray['name']                   = $this->input->post('name');
                        $patientArray['guardianname']           = $this->input->post('guardianname');
                        $patientArray['gender']                 = $this->input->post('gender');
                        $patientArray['age_day']                = $this->input->post('age_day');
                        $patientArray['age_month']              = $this->input->post('age_month');
                        $patientArray['age_year']               = $this->input->post('age_year');
                        $patientArray['maritalstatus']          = $this->input->post('maritalstatus');
                        $patientArray['phone']                  = $this->input->post('phone');
                        $patientArray['address']                = $this->input->post('address');
                        $patientArray['bloodgroupID']           = $this->input->post('bloodgroupID');
                        $patientArray['patienttypeID']          = $this->input->post('patienttypeID');
                        $patientArray['email']                  = $this->input->post('email');
                        $patientArray['photo']                  = $this->upload_data['photo']['file_name'];
                        $patientArray['delete_at']              = 0;
                        $patientArray['create_date']            = date("Y-m-d H:i:s");
                        $patientArray['modify_date']            = date("Y-m-d H:i:s");
                        $patientArray['create_userID']          = $this->session->userdata('loginuserID');
                        $patientArray['create_roleID']          = $this->session->userdata('roleID');

                        $this->patient_m->insert_patient($patientArray);
                        $patientID = $this->db->insert_id();

                        if($this->data['patienttypeID'] == 0) {
                            $appointmentArray['patientID']          = $patientID;
                            $appointmentArray['appointmentdate']    = date('Y-m-d H:i:s',strtotime($this->input->post('appointmentdate')));
                            $appointmentArray['pcase']              = $this->input->post('case');
                            $appointmentArray['casualty']           = $this->input->post('casualty');
                            $appointmentArray['oldpatient']         = $this->input->post('oldpatient');
                            $appointmentArray['tpaID']              = $this->input->post('tpaID');
                            $appointmentArray['reference']          = $this->input->post('reference');
                            $appointmentArray['doctorID']           = $this->input->post('doctorID');
                            $appointmentArray['amount']             = $this->input->post('amount');
                            $appointmentArray['paymentstatus']      = $this->input->post('paymentstatus');
                            $appointmentArray['symptoms']           = $this->input->post('symptoms');
                            $appointmentArray['allergies']          = $this->input->post('allergies');
                            $appointmentArray['note']               = $this->input->post('note');
                            $appointmentArray['create_date']        = date("Y-m-d H:i:s");
                            $appointmentArray['modify_date']        = date("Y-m-d H:i:s");
                            $appointmentArray['create_userID']      = $this->session->userdata('loginuserID');
                            $appointmentArray['create_roleID']      = $this->session->userdata('roleID');
                            $appointmentArray['type']               = 1;
                            $appointmentArray['status']             = 0;

                            if($this->input->post('paymentstatus') == 1) {
                                $appointmentArray['paymentmethodID'] = $this->input->post('paymentmethodID');
                                $appointmentArray['paymentdate'] = date('Y-m-d H:i:s');
                            } else {
                                $appointmentArray['paymentmethodID'] = 0;
                                $appointmentArray['paymentdate'] = '0000-00-00 00:00:00';
                            }
                            $this->_forEmailSend($appointmentArray, 0);

                            $this->appointment_m->insert_appointment($appointmentArray);
                            $appointmentandadmissionID  = $this->db->insert_id();
                            $heightWeightBpDate         = date('Y-m-d H:i:s', strtotime($this->input->post('appointmentdate')));
                        } elseif($this->data['patienttypeID'] == 1) {
                            $admissionArray['patientID']            = $patientID;
                            $admissionArray['admissiondate']        = date('Y-m-d H:i:s',strtotime($this->input->post('admissiondate')));
                            $admissionArray['pcase']                = $this->input->post('case');
                            $admissionArray['casualty']             = $this->input->post('casualty');
                            $admissionArray['oldpatient']           = $this->input->post('oldpatient');
                            $admissionArray['tpaID']                = $this->input->post('tpaID');
                            $admissionArray['reference']            = $this->input->post('reference');
                            $admissionArray['doctorID']             = $this->input->post('doctorID');
                            $admissionArray['creditlimit']          = $this->input->post('creditlimit');
                            $admissionArray['wardID']               = $this->input->post('wardID');
                            $admissionArray['bedID']                = $this->input->post('bedID');
                            $admissionArray['symptoms']             = $this->input->post('symptoms');
                            $admissionArray['allergies']            = $this->input->post('allergies');
                            $admissionArray['note']                 = $this->input->post('note');
                            $admissionArray['create_date']          = date("Y-m-d H:i:s");
                            $admissionArray['modify_date']          = date("Y-m-d H:i:s");
                            $admissionArray['create_userID']        = $this->session->userdata('loginuserID');
                            $admissionArray['create_roleID']        = $this->session->userdata('roleID');
                            $admissionArray['status']               = 0;

                            $this->_forEmailSend($admissionArray, 1);

                            $this->admission_m->insert_admission($admissionArray);
                            $appointmentandadmissionID  = $this->db->insert_id();
                            $heightWeightBpDate         = date('Y-m-d H:i:s', strtotime($this->input->post('admissiondate')));
                            $this->bed_m->update(array('patientID' => $patientID, 'status' => 1), $this->input->post('bedID'));
                        }

                        $heightWeightBpArray['patientID']                   = $patientID;
                        $heightWeightBpArray['patienttypeID']               = $this->input->post('patienttypeID');
                        $heightWeightBpArray['appointmentandadmissionID']   = $appointmentandadmissionID;
                        $heightWeightBpArray['date']                        = $heightWeightBpDate;
                        $heightWeightBpArray['height']                      = $this->input->post('height');
                        $heightWeightBpArray['weight']                      = $this->input->post('weight');
                        $heightWeightBpArray['bp']                          = $this->input->post('bp');

                        $this->heightweightbp_m->insert_heightweightbp($heightWeightBpArray);

                        $birthday = agetobirthday($this->input->post('age_day'), $this->input->post('age_month'), $this->input->post('age_year'));
                        $userArray['name']              = $this->input->post('name');
                        $userArray['designationID']     = 2;
                        $userArray['description']       = '';
                        $userArray['gender']            = $this->input->post('gender');
                        $userArray['dob']               = $birthday;
                        $userArray['jod']               = date('Y-m-d');
                        $userArray['email']             = $this->input->post('email');
                        $userArray['phone']             = $this->input->post('phone');
                        $userArray['address']           = $this->input->post('address');
                        $userArray['roleID']            = 3;
                        $userArray['photo']             = $this->upload_data['photo']['file_name'];
                        $userArray['username']          = $this->input->post('username');
                        $userArray['password']          = $this->user_m->hash($this->input->post('password'));
                        $userArray['create_date']       = date("Y-m-d H:i:s");
                        $userArray['modify_date']       = date("Y-m-d H:i:s");
                        $userArray['create_userID']     = $this->session->userdata('loginuserID');
                        $userArray['create_roleID']     = $this->session->userdata('roleID');
                        $userArray['status']            = 1;
                        $userArray['delete_at']         = 0;
                        $userArray['patientID']         = $patientID;

                        $this->user_m->insert_user($userArray);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('patient/index'));
                    }
                } else {
                    $this->data["subview"] = 'patient/index';
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = 'patient/index';
                $this->load->view('_layout_main', $this->data);
            }
        }
	}

    private function _forEmailSend($array, $patienttype)
    {
        $patientID  = $array['patientID'];
        $patient    = $this->patient_m->get_single_patient(array('patientID'=> $patientID));
        if(inicompute($patient) && $patient->email) {
            $email     = $patient->email;
            if($patienttype == 0) {
                $doctorID  = $array['doctorID'];
                $userID    = $array['create_userID'];

                $designation = [];
                $doctor      = $this->user_m->get_single_user(array('userID'=> $doctorID));
                if(inicompute($doctor)) {
                    $designation = $this->designation_m->get_single_designation(array('designationID' =>$doctor->designationID));
                }
                $user      = $this->user_m->get_single_user(array('userID'=> $userID));

                $passArray                     = $array;
                $passArray['doctorName']       = inicompute($doctor) ? $doctor->name : '';
                $passArray['doctorDesignation']= inicompute($designation) ? $designation->designation : '';
                $passArray['userName']         = inicompute($user) ? $user->name : '';
                $passArray['patient']          = $patient;
                $passArray['generalsettings']  = $this->data['generalsettings'];

                $message   = $this->load->view('patient/appointmentmail', $passArray, TRUE);
                $message   = trim($message);
                $subject   = $this->lang->line('patient_patient')." ".$this->lang->line('patient_appointment');
                @$this->mail->sendmail($this->data, $email, $subject, $message);
            } elseif ($patienttype == 1) {
                $ward  = $this->ward_m->get_single_ward(array('wardID'=> $array['wardID']));
                $bed   = $this->bed_m->get_single_bed(array('bedID'=> $array['bedID']));

                $passArray                     = $array;
                $passArray['patient']          = $patient;
                $passArray['wardName']         = (inicompute($ward) ? $ward->name : '');
                $passArray['bedName']          = (inicompute($bed) ? $bed->name : '');
                $passArray['generalsettings']  = $this->data['generalsettings'];

                $message   = $this->load->view('patient/admissionmail', $passArray, TRUE);
                $message   = trim($message);
                $subject   = $this->lang->line('patient_patient')." ".$this->lang->line('patient_admission');
                @$this->mail->sendmail($this->data, $email, $subject, $message);
            }
        }

    }

    public function edit()
    {
        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$patientID) {
            $this->data['patient']  = $this->patient_m->get_single_patient(array('patientID' => $patientID, 'patientID !=' => 5, 'delete_at' => 0));
            $this->data['jsmanager'] = ['date' => date('Y-m-d'), 'myBedID' => 0, 'myPatienttypeID' => $this->data['patient']->patienttypeID];

            if(inicompute($this->data['patient']) && ($this->data['patient']->patienttypeID != 5 && $this->data['patient']->delete_at == 0)) {
                $this->data['activelisttab']    = false;
                $this->data['activeedittab']    = true;
                $this->data['patienttypeID']    = $this->data['patient']->patienttypeID;
                $this->data['oldpatient']       = 1;
                $this->data['beds']             = [];

                $this->data['patients']         = $this->patient_m->get_order_by_patient(array('delete_at' => 0, 'patienttypeID !=' => 5));
                if(permissionChecker('patient_edit')) {
                    $this->data['headerassets'] = array(
                        'css' => array(
                            'assets/select2/css/select2.css',
                            'assets/select2/css/select2-bootstrap.css',
                            'assets/datetimepicker/css/datetimepicker.css',
                        ),
                        'js' => array(
                            'assets/select2/select2.js',
                            'assets/datetimepicker/js/datetimepicker.js',
                            'assets/inilabs/patient/edit.js'
                        )
                    );

                    $this->data['bloodgroups']      = $this->bloodgroup_m->get_select_bloodgroup('bloodgroupID, bloodgroup');
                    $this->data['tpas']             = $this->tpa_m->get_select_tpa('tpaID, name');
                    $this->data['doctors']          = $this->user_m->get_select_user('userID, name, status, delete_at', array('roleID' => 2));
                    $this->data['wards']            = $this->ward_m->get_select_ward('wardID, name');

                    $this->heightweightbp_m->order('heightweightbpID desc');
                    $this->data['heightweightbp']   = $this->heightweightbp_m->get_single_heightweightbp(array('patientID' => $patientID, 'patienttypeID' => $this->data['patient']->patienttypeID));

                    if(!inicompute($this->data['heightweightbp'])) {
                        $this->data['heightweightbp'] = (object)['height' => '', 'weight' => '', 'bp' => ''];
                    }

                    if($this->data['patient']->patienttypeID == 0) {
                        $this->appointment_m->order('appointmentID desc');
                        $this->data['appointment'] = $this->appointment_m->get_single_appointment(array('patientID' => $patientID));
                    } else {
                        $this->admission_m->order('admissionID desc');
                        $this->data['admission'] = $this->admission_m->get_single_admission(array('patientID' => $patientID));
                        $patientwardID  = $this->data['admission']->wardID;
                        $this->data['beds'] = $this->bed_m->get_order_by_bed(array('wardID' => $patientwardID));
                    }
                    $this->data['user'] = $this->user_m->get_single_user(array('patientID' => $patientID));
                    $this->data['jsmanager'] = ['date' => (($this->data['patient']->patienttypeID) ? ((strtotime(date('Y-m-d')) < strtotime($this->data['admission']->admissiondate)) ? date('Y-m-d') : date('Y-m-d', strtotime($this->data['admission']->admissiondate))) : ((strtotime(date('Y-m-d')) < strtotime($this->data['appointment']->appointmentdate)) ? date('Y-m-d') : date('Y-m-d', strtotime($this->data['appointment']->appointmentdate)))), 'myBedID' => (($this->data['patient']->patienttypeID == 1) ? $this->data['admission']->bedID : 0), 'myPatienttypeID' => $this->data['patient']->patienttypeID];

                    if($_POST) {
                        $this->data['patienttypeID']    = $this->input->post('patienttypeID');
                        $this->data['jsmanager']['myPatienttypeID'] = $this->data['patienttypeID'];
                        if($this->input->post('wardID')) {
                            $this->data['beds'] = $this->bed_m->get_order_by(array('wardID' => $this->input->post('wardID')));
                        }

                        $rulesArray = [
                            'patienttypeID' => $this->input->post('patienttypeID'),
                            'paymentstatus' => $this->input->post('paymentstatus'),
                        ];

                        $rules  = $this->rules($rulesArray);
                        unset($rules[3]);
                        $this->form_validation->set_rules($rules);
                        if ($this->form_validation->run() == FALSE) {
                            $this->data["subview"] = 'patient/edit';
                            $this->load->view('_layout_main', $this->data);
                        } else {
                            $patientArray['name']                   = $this->input->post('name');
                            $patientArray['guardianname']           = $this->input->post('guardianname');
                            $patientArray['gender']                 = $this->input->post('gender');
                            $patientArray['maritalstatus']          = $this->input->post('maritalstatus');
                            $patientArray['phone']                  = $this->input->post('phone');
                            $patientArray['address']                = $this->input->post('address');
                            $patientArray['age_day']                = $this->input->post('age_day');
                            $patientArray['age_month']              = $this->input->post('age_month');
                            $patientArray['age_year']               = $this->input->post('age_year');
                            $patientArray['bloodgroupID']           = $this->input->post('bloodgroupID');
                            $patientArray['patienttypeID']          = $this->input->post('patienttypeID');
                            $patientArray['email']                  = $this->input->post('email');
                            $patientArray['photo']                  = $this->upload_data['photo']['file_name'];
                            $patientArray['modify_date']            = date("Y-m-d H:i:s");

                            $this->patient_m->update_patient($patientArray, $patientID);

                            if($this->input->post('patienttypeID') == 0) {
                                $appointmentArray['patientID']          = $patientID;
                                $appointmentArray['appointmentdate']    = date('Y-m-d H:i:s',strtotime($this->input->post('appointmentdate')));
                                $appointmentArray['pcase']              = $this->input->post('case');
                                $appointmentArray['casualty']           = $this->input->post('casualty');
                                $appointmentArray['oldpatient']         = $this->input->post('oldpatient');
                                $appointmentArray['tpaID']              = $this->input->post('tpaID');
                                $appointmentArray['reference']          = $this->input->post('reference');
                                $appointmentArray['doctorID']           = $this->input->post('doctorID');
                                $appointmentArray['amount']             = $this->input->post('amount');
                                $appointmentArray['paymentstatus']      = $this->input->post('paymentstatus');
                                $appointmentArray['paymentmethodID']    = $this->input->post('paymentmethodID');
                                $appointmentArray['symptoms']           = $this->input->post('symptoms');
                                $appointmentArray['allergies']          = $this->input->post('allergies');
                                $appointmentArray['note']               = $this->input->post('note');

                                if($this->input->post('paymentstatus') == 1) {
                                    $appointmentArray['paymentmethodID'] = $this->input->post('paymentmethodID');
                                } else {
                                    $appointmentArray['paymentmethodID'] = 0;
                                }

                                if($this->input->post('paymentstatus') == 1 && $this->data['appointment']->paymentdate == '0000-00-00 00:00:00') {
                                    $appointmentArray['paymentdate'] = date('Y-m-d H:i:s');
                                } else {
                                    $appointmentArray['paymentdate'] = '0000-00-00 00:00:00';
                                }

                                $this->appointment_m->update_appointment($appointmentArray, $this->data['appointment']->appointmentID);
                                $appointmentandadmissionID  = $this->data['appointment']->appointmentID;

                                $heightweightbp = $this->heightweightbp_m->get_single_heightweightbp(array('patienttypeID' => 0, 'appointmentandadmissionID' => $this->data['appointment']->appointmentID));
                                if(inicompute($heightweightbp)) {
                                    $heightWeightBpArray['patientID']                   = $patientID;
                                    $heightWeightBpArray['patienttypeID']               = $this->input->post('patienttypeID');
                                    $heightWeightBpArray['appointmentandadmissionID']   = $appointmentandadmissionID;
                                    $heightWeightBpArray['date']                        = date('Y-m-d H:i:s',strtotime($this->input->post('appointmentdate')));
                                    $heightWeightBpArray['height']                      = $this->input->post('height');
                                    $heightWeightBpArray['weight']                      = $this->input->post('weight');
                                    $heightWeightBpArray['bp']                          = $this->input->post('bp');
                                    $this->heightweightbp_m->update_heightweightbp($heightWeightBpArray, $heightweightbp->heightweightbpID);
                                } else {
                                    $heightWeightBpArray['patientID']                   = $patientID;
                                    $heightWeightBpArray['patienttypeID']               = $this->input->post('patienttypeID');
                                    $heightWeightBpArray['appointmentandadmissionID']   = $appointmentandadmissionID;
                                    $heightWeightBpArray['date']                        = date('Y-m-d H:i:s',strtotime($this->input->post('appointmentdate')));
                                    $heightWeightBpArray['height']                      = $this->input->post('height');
                                    $heightWeightBpArray['weight']                      = $this->input->post('weight');
                                    $heightWeightBpArray['bp']                          = $this->input->post('bp');
                                    $this->heightweightbp_m->insert_heightweightbp($heightWeightBpArray);
                                }
                            } elseif($this->input->post('patienttypeID') == 1) {
                                $admissionArray['patientID']            = $patientID;
                                $admissionArray['admissiondate']        = date('Y-m-d H:i:s',strtotime($this->input->post('admissiondate')));
                                $admissionArray['pcase']                = $this->input->post('case');
                                $admissionArray['casualty']             = $this->input->post('casualty');
                                $admissionArray['oldpatient']           = $this->input->post('oldpatient');
                                $admissionArray['tpaID']                = $this->input->post('tpaID');
                                $admissionArray['reference']            = $this->input->post('reference');
                                $admissionArray['doctorID']             = $this->input->post('doctorID');
                                $admissionArray['creditlimit']          = $this->input->post('creditlimit');
                                $admissionArray['wardID']               = $this->input->post('wardID');
                                $admissionArray['bedID']                = $this->input->post('bedID');
                                $admissionArray['symptoms']             = $this->input->post('symptoms');
                                $admissionArray['allergies']            = $this->input->post('allergies');
                                $admissionArray['note']                 = $this->input->post('note');
                                $admissionArray['modify_date']          = date("Y-m-d H:i:s");

                                $this->admission_m->update_admission($admissionArray, $this->data['admission']->admissionID);
                                $appointmentandadmissionID  = $this->data['admission']->admissionID;

                                if($this->data['admission']->bedID != $this->input->post('bedID')) {
                                    $bed = $this->bed_m->get_single_bed(array('patientID' => $this->data['admission']->patientID, 'status' => 1, 'bedID' => $this->data['admission']->bedID));
                                    if(inicompute($bed)) {
                                        $this->bed_m->update(array('patientID' => 0, 'status' => 0), $bed->bedID);
                                    }
                                    $this->bed_m->update(array('patientID' => $this->data['admission']->patientID, 'status' => 1), $this->input->post('bedID'));
                                } else {
                                    $this->bed_m->update(array('patientID' => $this->data['admission']->patientID, 'status' => 1), $this->input->post('bedID'));
                                }

                                $heightweightbp = $this->heightweightbp_m->get_order_by_heightweightbp(array('patienttypeID' => 1, 'appointmentandadmissionID' => $this->data['admission']->admissionID));
                                if(inicompute($heightweightbp)) {
                                    $d                      = 1;
                                    $oneheightweightbpID    = 0;
                                    foreach ($heightweightbp as $heightweightbpVal) {
                                        if($d == 1) {
                                            $oneheightweightbpID = $heightweightbpVal->heightweightbpID;
                                        } else {
                                            $this->heightweightbp_m->delete_heightweightbp($heightweightbpVal->heightweightbpID);
                                        }
                                        $d++;
                                    }

                                    $heightWeightBpArray['patientID']                   = $patientID;
                                    $heightWeightBpArray['patienttypeID']               = $this->input->post('patienttypeID');
                                    $heightWeightBpArray['appointmentandadmissionID']   = $appointmentandadmissionID;
                                    $heightWeightBpArray['date']                        = date('Y-m-d H:i:s',strtotime($this->input->post('admissiondate')));
                                    $heightWeightBpArray['height']                      = $this->input->post('height');
                                    $heightWeightBpArray['weight']                      = $this->input->post('weight');
                                    $heightWeightBpArray['bp']                          = $this->input->post('bp');
                                    $this->heightweightbp_m->update_heightweightbp($heightWeightBpArray, $oneheightweightbpID);
                                } else {
                                    $heightWeightBpArray['patientID']                   = $patientID;
                                    $heightWeightBpArray['patienttypeID']               = $this->input->post('patienttypeID');
                                    $heightWeightBpArray['appointmentandadmissionID']   = $appointmentandadmissionID;
                                    $heightWeightBpArray['date']                        = date('Y-m-d H:i:s',strtotime($this->input->post('admissiondate')));
                                    $heightWeightBpArray['height']                      = $this->input->post('height');
                                    $heightWeightBpArray['weight']                      = $this->input->post('weight');
                                    $heightWeightBpArray['bp']                          = $this->input->post('bp');
                                    $this->heightweightbp_m->insert_heightweightbp($heightWeightBpArray);
                                }

                                if($this->data['admission']->prescriptionstatus == 1) {
                                    if($this->data['admission']->doctorID != $this->input->post('doctorID')) {
                                        $prescription = $this->prescription_m->get_single_prescription(['patienttypeID' => 1, 'appointmentandadmissionID' => $this->data['admission']->admissionID]);
                                        if(inicompute($prescription)) {
                                            $this->prescription_m->update_prescription(['doctorID' => $this->input->post('doctorID')], $prescription->prescriptionID);
                                        }
                                    }
                                }
                            }

                            $birthday = agetobirthday($this->input->post('age_day'), $this->input->post('age_month'), $this->input->post('age_year'));
                            $userArray['name']              = $this->input->post('name');
                            $userArray['designationID']     = 2;
                            $userArray['description']       = '';
                            $userArray['gender']            = $this->input->post('gender');
                            $userArray['dob']               = $birthday;
                            $userArray['jod']               = date('Y-m-d');
                            $userArray['email']             = $this->input->post('email');
                            $userArray['phone']             = $this->input->post('phone');
                            $userArray['address']           = $this->input->post('address');
                            $userArray['roleID']            = 3;
                            $userArray['photo']             = $this->upload_data['photo']['file_name'];
                            $userArray['username']          = $this->input->post('username');
                            $userArray['modify_date']       = date("Y-m-d H:i:s");

                            $this->user_m->update_user($userArray, $this->data['user']->userID);
                            $this->session->set_flashdata('success','Success');
                            redirect(site_url('patient/index'));
                        }
                    } else {
                        $this->data["subview"] = 'patient/edit';
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

    public function view()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/patient/view.js'
            ]
        ];

        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$patientID) {
            $this->data['jsmanager'] = ['patientID' => $patientID];
            $this->_profile($patientID);
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function _profile($patientID)
    {

        $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID));
        if(inicompute($this->data['patient']) && $this->data['patient']->patienttypeID != 5) {
            $this->data['maritalstatus']    = [
                0   => '',
                1   => $this->lang->line('patient_single'),
                2   => $this->lang->line('patient_married'),
                3   => $this->lang->line('patient_separated'),
                4   => $this->lang->line('patient_divorced')
            ];
            $this->data['paymentmethods']   = [
                0  => '',
                1  => $this->lang->line('patient_cash'),
                2  => $this->lang->line('patient_cheque'),
                3  => $this->lang->line('patient_credit_card'),
                4  => $this->lang->line('patient_other')
            ];
            $this->data['designations']     = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
            $this->data['bloodgroups']      = pluck($this->bloodgroup_m->get_bloodgroup(), 'bloodgroup', 'bloodgroupID');
            $this->data['doctors']          = pluck($this->user_m->get_select_user('userID, name', array('roleID' => 2)), 'name', 'userID');
            $this->data['user']             = $this->user_m->get_single_user(array('patientID' => $patientID));
            $this->data['appointments']     = $this->appointment_m->get_order_by_appointment(array('patientID' => $patientID, 'status' => 1));
            $this->data['liveappointments']     = $this->appointment_m->get_order_by_appointment(array('patientID' => $patientID, 'status' => 0,'type'=>2,'paymentstatus'=>1));
            $this->data['admissions']       = $this->admission_m->get_order_by_admission(array('patientID' => $patientID, 'status' => 1));
            $this->data['heightweightbps']  = $this->heightweightbp_m->get_order_by(array('patientID' => $patientID));
            $this->data['billlabels']       = pluck($this->billlabel_m->get_billlabel(),'obj','billlabelID');
            $this->data['billitems']        = $this->billitems_m->get_order_by_billitems(['patientID' => $patientID, 'delete_at' => 0]);
            $this->billpayment_m->order('create_date desc');
            $this->data['billpayments']     = $this->billpayment_m->get_order_by_billpayment(['patientID' => $patientID, 'delete_at' => 0]);
            $this->data['testcategorys']    = pluck($this->testcategory_m->get_testcategory(), 'name', 'testcategoryID');
            $this->data['testlabels']       = pluck($this->testlabel_m->get_testlabel(), 'name', 'testlabelID');
            $this->data['tests']            = $this->test_m->get_select_test_with_bill_patient('test.testID, test.testlabelID, test.testcategoryID, test.billID, patient.patientID, test.create_date', ['patient.patientID' => $patientID, 'bill.delete_at' => 0]);
            $this->data['instructions']     = $this->instruction_m->get_instruction_with_user(['instruction.patientID' => $patientID]);

            $this->data['subview'] = 'patient/view';
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function printpreview()
    {
        if(permissionChecker('patient_view')) {
            $patientID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$patientID) {
                $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID));
                if(inicompute($this->data['patient']) && $this->data['patient']->patienttypeID != 5) {
                    $this->data['maritalstatus']    = [
                        0   => '',
                        1   => $this->lang->line('patient_single'),
                        2   => $this->lang->line('patient_married'),
                        3   => $this->lang->line('patient_separated'),
                        4   => $this->lang->line('patient_divorced')
                    ];
                    $this->data['designations']     = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
                    $this->data['bloodgroups']      = pluck($this->bloodgroup_m->get_bloodgroup(), 'bloodgroup', 'bloodgroupID');
                    $this->data['user']             = $this->user_m->get_single_user(array('patientID' => $patientID));

                    $this->report->reportPDF(['stylesheet' => 'patientmodule.css', 'data' => $this->data, 'viewpath' => 'patient/printpreview']);
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
        if(permissionChecker('patient_view')) {
            if($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $patientID = $this->input->post('patientID');
                    if((int)$patientID) {
                        $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID' => $patientID));
                        if(inicompute($this->data['patient']) && $this->data['patient']->patienttypeID != 5) {
                            $email      = $this->input->post('to');
                            $subject    = $this->input->post('subject');
                            $message    = $this->input->post('message');

                            $this->data['maritalstatus']    = [
                                0   => '',
                                1   => $this->lang->line('patient_single'),
                                2   => $this->lang->line('patient_married'),
                                3   => $this->lang->line('patient_separated'),
                                4   => $this->lang->line('patient_divorced')
                            ];
                            $this->data['designations']     = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
                            $this->data['bloodgroups']      = pluck($this->bloodgroup_m->get_bloodgroup(), 'bloodgroup', 'bloodgroupID');
                            $this->data['user']             = $this->user_m->get_single_user(array('patientID' => $patientID));

                            $this->report->reportSendToMail(['stylesheet' => 'patientmodule.css', 'data' => $this->data, 'viewpath' => 'patient/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                            $retArray['message'] = "Success";
                            $retArray['status']  = TRUE;
                        } else {
                            $retArray['message'] = $this->lang->line('patient_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('patient_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('patient_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('patient_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("patient_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("patient_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("patient_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("patient_patient"),
                'rules' => 'trim|numeric'
            )
        );
        return $rules;
    }

    public function prescription()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/patient/prescription.js'
            ]
        ];
        if(permissionChecker('patient_view') || (($this->data['loginroleID'] == 3) && (!permissionChecker('patient_view')))) {
            $appointmentAndAdmissionTypeID      = htmlentities(escapeString($this->uri->segment(3)));
            $appointmentAndAdmissionID          = htmlentities(escapeString($this->uri->segment(4)));
            $patientID                          = htmlentities(escapeString($this->uri->segment(5)));
            if((int)$appointmentAndAdmissionID && (int)$patientID && ($appointmentAndAdmissionTypeID == 0 || $appointmentAndAdmissionTypeID == 1)) {
                $this->data['prescription'] = $this->prescription_m->get_single_prescription(['patientID' => $patientID, 'patienttypeID' => $appointmentAndAdmissionTypeID, 'appointmentandadmissionID' => $appointmentAndAdmissionID]);
                if(inicompute($this->data['prescription'])) {
                    if(($this->data['loginroleID'] == 3 && $this->data['prescription']->patientID == $this->data['loginuserID']) || $this->data['loginroleID'] != 3) {
                        $this->data['patientinfo'] = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                        $this->data['gender'] = [0 => '', 1 => 'M', 2 => 'F'];
                        if ($this->data['prescription']->patienttypeID == 0) {
                            $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                            $this->data['jsmanager'] = ['myAppointmentAndAdmissionTypeID' => 0, 'myAppointmentAndAdmissionID' => $this->data['appointmentandadmissioninfo']->appointmentID, 'myPatientID' => $this->data['appointmentandadmissioninfo']->patientID];
                        } else {
                            $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                            $this->data['jsmanager'] = ['myAppointmentAndAdmissionTypeID' => 1, 'myAppointmentAndAdmissionID' => $this->data['appointmentandadmissioninfo']->admissionID, 'myPatientID' => $this->data['appointmentandadmissioninfo']->patientID];
                        }
                        $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                        $this->prescriptionitem_m->order('prescriptionitemID asc');
                        $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $this->data['prescription']->prescriptionID]);
                        $this->data["subview"] = 'patient/prescription';
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
    }

    public function prescriptionprintpreview()
    {
        if(permissionChecker('patient_view') || (($this->data['loginroleID'] == 3) && (!permissionChecker('patient_view')))) {
            $appointmentAndAdmissionTypeID      = htmlentities(escapeString($this->uri->segment(3)));
            $appointmentAndAdmissionID          = htmlentities(escapeString($this->uri->segment(4)));
            $patientID                          = htmlentities(escapeString($this->uri->segment(5)));
            if((int)$appointmentAndAdmissionID && (int)$patientID && ($appointmentAndAdmissionTypeID == 0 || $appointmentAndAdmissionTypeID == 1)) {
                $this->data['prescription'] = $this->prescription_m->get_single_prescription(['patientID' => $patientID, 'patienttypeID' => $appointmentAndAdmissionTypeID, 'appointmentandadmissionID' => $appointmentAndAdmissionID]);
                if(inicompute($this->data['prescription'])) {
                    if(($this->data['loginroleID'] == 3 && $this->data['prescription']->patientID == $this->data['loginuserID']) || $this->data['loginroleID'] != 3) {
                        $this->data['patientinfo'] = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                        $this->data['gender'] = [0 => '', 1 => 'M', 2 => 'F'];
                        if ($this->data['prescription']->patienttypeID == 0) {
                            $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                        } else {
                            $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                        }
                        $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                        $this->prescriptionitem_m->order('prescriptionitemID asc');
                        $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $this->data['prescription']->prescriptionID]);
                        
                        $this->report->reportPDF(['stylesheet' => 'prescriptionprintpreviewmodule.css', 'data' => $this->data, 'viewpath' => 'patient/prescriptionprintpreview']);
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

    public function prescriptionsendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('patient_view') || (($this->data['loginroleID'] == 3) && (!permissionChecker('patient_view')))) {
            if($_POST) {
                $rules = $this->prescriptionsendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $appointmentAndAdmissionTypeID  = $this->input->post('appointmentAndAdmissionTypeID');
                    $appointmentAndAdmissionID      = $this->input->post('appointmentAndAdmissionID');
                    $patientID                      = $this->input->post('patientID');
                    if((int)$appointmentAndAdmissionID && (int)$patientID && ($appointmentAndAdmissionTypeID == 0 || $appointmentAndAdmissionTypeID == 1)) {
                        $this->data['prescription'] = $this->prescription_m->get_single_prescription(['patientID' => $patientID, 'patienttypeID' => $appointmentAndAdmissionTypeID, 'appointmentandadmissionID' => $appointmentAndAdmissionID]);
                        if(inicompute($this->data['prescription'])) {
                            if(($this->data['loginroleID'] == 3 && $this->data['prescription']->patientID == $this->data['loginuserID']) || $this->data['loginroleID'] != 3) {
                                $this->data['patientinfo'] = $this->patient_m->get_single_patient(['patientID' => $this->data['prescription']->patientID]);
                                $this->data['gender'] = [0 => '', 1 => 'M', 2 => 'F'];
                                if ($this->data['prescription']->patienttypeID == 0) {
                                    $this->data['appointmentandadmissioninfo'] = $this->appointment_m->get_single_appointment(['appointmentID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                                } else {
                                    $this->data['appointmentandadmissioninfo'] = $this->admission_m->get_single_admission(['admissionID' => $this->data['prescription']->appointmentandadmissionID, 'patientID' => $this->data['prescription']->patientID]);
                                }
                                $this->data['create'] = $this->user_m->get_single_user(['userID' => $this->data['prescription']->create_userID]);
                                $this->prescriptionitem_m->order('prescriptionitemID asc');
                                $this->data['prescriptionitems'] = $this->prescriptionitem_m->get_order_by_prescriptionitem(['prescriptionID' => $this->data['prescription']->prescriptionID]);
                                
                                $email      = $this->input->post('to');
                                $subject    = $this->input->post('subject');
                                $message    = $this->input->post('message');

                                $this->report->reportSendToMail(['stylesheet' => 'prescriptionprintpreviewmodule.css', 'data' => $this->data, 'viewpath' => 'patient/prescriptionprintpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                                $retArray['message'] = "Success";
                                $retArray['status']  = TRUE;
                            } else {
                                $retArray['message'] = $this->lang->line('patient_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('patient_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('patient_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('patient_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('patient_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function prescriptionsendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("patient_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("patient_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("patient_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'appointmentAndAdmissionTypeID',
                'label' => $this->lang->line("patient_appointmentAndAdmissionType"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'appointmentAndAdmissionID',
                'label' => $this->lang->line("patient_appointmentAndAdmission"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("patient_patient"),
                'rules' => 'trim|numeric'
            )
        );
        return $rules;
    }

    public function discharge()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/patient/discharge.js'
            ]
        ];

        if(permissionChecker('patient_view') || (($this->data['loginroleID'] == 3) && (!permissionChecker('patient_view')))) {
            $admissionID    = htmlentities(escapeString($this->uri->segment(3)));
            $patientID      = htmlentities(escapeString($this->uri->segment(4)));
            if (((int)$admissionID && (int)$patientID)) {
                $this->data['jsmanager'] = ['admissionID' => $admissionID, 'patientID' => $patientID];
                $this->data['discharge'] = $this->discharge_m->get_single_discharge(['admissionID' => $admissionID, 'patientID' => $patientID]);
                if (inicompute($this->data['discharge'])) {
                    if(($this->data['loginroleID'] == 3 && $this->data['discharge']->patientID == $this->data['loginuserID']) || $this->data['loginroleID'] != 3) {
                        $this->data['patient'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name, patient.age_day, patient.age_month, patient.age_year, patient.gender, admission.admissiondate, admission.wardID, admission.bedID', ['patient.patientID' => $this->data['discharge']->patientID, 'admissionID' =>  $this->data['discharge']->admissionID], true);
                        if(inicompute($this->data['patient'])) {
                            $this->data['genders']      = [0 => '', 1 => $this->lang->line('patient_male'), 2 => $this->lang->line('patient_female')];
                            $this->data['conditions']   = [1 => $this->lang->line('patient_stable'), 2 => $this->lang->line('patient_almost_stable'), 3 => $this->lang->line('patient_own_risk'), 4 => $this->lang->line('patient_unstable')];
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
                            $this->data["subview"] = 'patient/discharge';
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

    public function dischargeprintpreview()
    {
        if(permissionChecker('patient_view') || (($this->data['loginroleID'] == 3) && (!permissionChecker('patient_view')))) {
            $admissionID    = htmlentities(escapeString($this->uri->segment(3)));
            $patientID      = htmlentities(escapeString($this->uri->segment(4)));
            if (((int)$admissionID && (int)$patientID)) {
                $this->data['discharge'] = $this->discharge_m->get_single_discharge(['admissionID' => $admissionID, 'patientID' => $patientID]);
                if (inicompute($this->data['discharge'])) {
                    if(($this->data['loginroleID'] == 3 && $this->data['discharge']->patientID == $this->data['loginuserID']) || $this->data['loginroleID'] != 3) {
                        $this->data['patient'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name, patient.age_day, patient.age_month, patient.age_year, patient.gender, admission.admissiondate, admission.wardID, admission.bedID', ['patient.patientID' => $this->data['discharge']->patientID, 'admissionID' =>  $this->data['discharge']->admissionID], true);
                        if(inicompute($this->data['patient'])) {
                            $this->data['genders']      = [0 => '', 1 => $this->lang->line('patient_male'), 2 => $this->lang->line('patient_female')];
                            $this->data['conditions']   = [1 => $this->lang->line('patient_stable'), 2 => $this->lang->line('patient_almost_stable'), 3 => $this->lang->line('patient_own_risk'), 4 => $this->lang->line('patient_unstable')];
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
                            
                            $this->report->reportPDF(['stylesheet' => 'dischargeprintpreviewmodule.css', 'data' => $this->data, 'viewpath' => 'patient/dischargeprintpreview', 'pagetype'=>'landscape']);
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

    public function dischargesendmail()
    {
        $retArray['message'] = '';
        $retArray['status']  = FALSE;
        if(permissionChecker('patient_view')) {
            if($_POST) {
                $rules = $this->dischargesendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status']  = FALSE;
                } else {
                    $admissionID    = $this->input->post('admissionID');;
                    $patientID      = $this->input->post('patientID');;
                    if (((int)$admissionID && (int)$patientID)) {
                        $this->data['discharge'] = $this->discharge_m->get_single_discharge(['admissionID' => $admissionID, 'patientID' => $patientID]);
                        if (inicompute($this->data['discharge'])) {
                            if(($this->data['loginroleID'] == 3 && $this->data['discharge']->patientID == $this->data['loginuserID']) || $this->data['loginroleID'] != 3) {
                                $this->data['patient'] = $this->admission_m->get_select_admission_patient('patient.patientID, patient.name, patient.age_day, patient.age_month, patient.age_year, patient.gender, admission.admissiondate, admission.wardID, admission.bedID', ['patient.patientID' => $this->data['discharge']->patientID, 'admissionID' =>  $this->data['discharge']->admissionID], true);
                                if(inicompute($this->data['patient'])) {
                                    $this->data['genders']      = [0 => '', 1 => $this->lang->line('patient_male'), 2 => $this->lang->line('patient_female')];
                                    $this->data['conditions']   = [1 => $this->lang->line('patient_stable'), 2 => $this->lang->line('patient_almost_stable'), 3 => $this->lang->line('patient_own_risk'), 4 => $this->lang->line('patient_unstable')];
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

                                    $email      = $this->input->post('to');
                                    $subject    = $this->input->post('subject');
                                    $message    = $this->input->post('message');
                                    
                                    $this->report->reportSendToMail(['stylesheet' => 'dischargeprintpreviewmodule.css', 'data' => $this->data, 'viewpath' => 'patient/dischargeprintpreview', 'email' => $email, 'subject' => $subject, 'message' => $message, 'pagetype'=> 'landscape']);
                                    $retArray['message'] = "Success";
                                    $retArray['status']  = TRUE;
                                } else {
                                    $retArray['message'] = $this->lang->line('patient_data_not_found');
                                }
                            } else {
                                $retArray['message'] = $this->lang->line('patient_data_not_found');
                            }
                        } else {
                            $retArray['message'] = $this->lang->line('patient_data_not_found');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('patient_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('patient_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('patient_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function dischargesendmail_rules() 
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("patient_to"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("patient_subject"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("patient_message"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'admissionID',
                'label' => $this->lang->line("patient_admission"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("patient_patient"),
                'rules' => 'trim|numeric'
            )
        );
        return $rules;
    }

    public function delete()
    {
        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$patientID) {
            $patient = $this->patient_m->get_single_patient(['patientID' => $patientID, 'delete_at' => 0]);
            if (inicompute($patient) && $patient->patienttypeID != 5) {
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
                $user = $this->user_m->get_single_user(array('patientID' => $patientID, 'delete_at' => 0));
                if(inicompute($user)) {
                    $this->user_m->update_user(['delete_at' => 1, 'status' => 0], $user->userID);
                }
                $this->session->set_flashdata('success','Success');
                redirect(site_url('patient/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function bedcall()
    {
        echo '<option value="0">— '.$this->lang->line('patient_please_select').' —</option>';
        if($_POST) {
            $wardID = $this->input->post('wardID');
            $bedID  = $this->input->post('bedID');

            if((int)$wardID) {
                $beds = $this->bed_m->get_select_bed('bedID, name, status', array('wardID' => $wardID));
                if(inicompute($beds)) {
                    foreach ($beds as $bed) {
                        if(!empty($bedID)) {
                            if($bed->status == 0 || $bed->bedID == $bedID) {
                                echo "<option value='".$bed->bedID."'>".$bed->name."</option>";
                            }
                        } else {
                            if($bed->status == 0) {
                                echo "<option value='".$bed->bedID."'>".$bed->name."</option>";
                            }
                        }
                    }
                }
            }
        }
    }

    public function unique_data($data)
    {
        if($data == 0) {
            $this->form_validation->set_message('unique_data', 'The %s field is required.');
            return false;
        }
        return true;
    }

    public function valid_date($date) 
    {
        if($date) {
            if(strlen($date) < 19) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                return false;
            } else {
                $arr = explode("-", $date);
                if(inicompute($arr) > 2) {
                    $dd = $arr[0];
                    $mm = $arr[1];
                    $yyyy = explode(" ", $arr[2]);
                    if(inicompute($yyyy) > 1) {
                        $yyyy = $yyyy[0];
                        if(checkdate($mm, $dd, $yyyy)) {
                            $patientID = htmlentities(escapeString($this->uri->segment(3)));
                            if((int)$patientID) {
                                if($this->data['patient']->patienttypeID) {
                                    if(strtotime($date) < strtotime($this->data['admission']->admissiondate) && strtotime($date) < strtotime(date('Y-m-d'))) {
                                        $this->form_validation->set_message("valid_date", "The %s can not take past date.");
                                        return false;
                                    }
                                    return true;
                                } else {
                                    if(strtotime($date) < strtotime($this->data['appointment']->appointmentdate) && strtotime($date) < strtotime(date('Y-m-d'))) {
                                        $this->form_validation->set_message("valid_date", "The %s can not take past date.");
                                        return false;
                                    }
                                    return true;
                                }
                            } else {
                                if(strtotime(date('d-m-Y')) > strtotime($date)) {
                                    $this->form_validation->set_message("valid_date", "The %s can not take past date.");
                                    return false;
                                }
                                return true;
                            }
                        } else {
                            $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy.");
                            return false;
                        }
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is invalid.");
                        return false;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is invalid.");
                    return false;
                }
            }
        }
        return true;
    }

    public function photoupload() 
    {
        $new_file = "default.png";
        if($_FILES["photo"]['name'] != "") {
            $file_name = $_FILES["photo"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/user";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name'] = $new_file;
                $config['max_size'] = '1024';
                $config['max_width'] = '3000';
                $config['max_height'] = '3000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['photo'] =  $this->upload->data();
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return FALSE;
            }
        } else {
            $userID = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$userID) {
                $patient = $this->patient_m->get_single_patient(array('patientID' => $userID));
                if (inicompute($patient)) {
                    $this->upload_data['photo'] = array('file_name' => $patient->photo);
                    return TRUE;
                } else{
                    $this->upload_data['photo'] = array('file_name' => $new_file);
                    return TRUE;
                }
            } else{
                $this->upload_data['photo'] = array('file_name' => $new_file);
                return TRUE;
            }
        }
    }

    public function unique_appointmentdate()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $appointmentdate    = date('Y-m-d', strtotime($this->input->post('appointmentdate')));
            $doctorID           = $this->input->post('doctorID');
            $this->appointment_m->order('appointmentID desc');
            $appointment        = $this->appointment_m->get_single_appointment(array('patientID' => $id, 'DATE(appointmentdate)' => $appointmentdate, 'doctorID' => $doctorID));
            if(inicompute($appointment) ) {
                if(isset($this->data['appointment']) && inicompute($this->data['appointment'])) {
                    if($appointment->appointmentID != $this->data['appointment']->appointmentID) {
                        $this->form_validation->set_message('unique_appointmentdate', 'The appointment was made before.');
                        return false;
                    }
                    return true;
                } else {
                    $this->form_validation->set_message('unique_appointmentdate', 'The appointment was made before.');
                    return false;
                }
            }
            return true;
        } else {
            $appointmentdate    = date('Y-m-d', strtotime($this->input->post('appointmentdate')));
            $doctorID           = $this->input->post('doctorID');
            $this->appointment_m->order('appointmentID desc');
            $appointment        = $this->appointment_m->get_single_appointment(array('patientID' => $id, 'DATE(appointmentdate)' => $appointmentdate, 'doctorID' => $doctorID,  'status' => 0));
            if(inicompute($appointment)) {
                $this->form_validation->set_message('unique_appointmentdate', 'The appointment was made before.');
                return false;
            }
        }
        return true;
    }

    public function unique_admissiondate()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $this->admission_m->order('admissionID desc');
            $admission = $this->admission_m->get_single_admission(array('patientID' => $id, 'status' => 0));
            if(inicompute($admission)) {
                if(isset($this->data['admission']) && inicompute($this->data['admission'])) {
                    if($admission->admissionID != $this->data['admission']->admissionID) {
                        $this->form_validation->set_message('unique_admissiondate', 'The patient has already admitted.');
                        return false;
                    }
                    return true;
                } else {
                    $this->form_validation->set_message('unique_admissiondate', 'The patient has already admitted.');
                    return false;
                }
            }
            return true;
        } else {
            $this->admission_m->order('admissionID desc');
            $admission = $this->admission_m->get_single_admission(array('patientID' => $id, 'status' => 0));
            if(inicompute($admission)) {
                $this->form_validation->set_message('unique_admissiondate', 'The patient has already admitted.');
                return false;
            }
            return true;
        }
        return true;
    }

    public function unique_email()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            if(!empty($this->input->post('email'))) {
               $user = $this->user_m->get_single_user(array('email' => $this->input->post('email'), 'patientID !=' => $id, 'delete_at' => 0));
               if(inicompute($user)) {
                    $this->form_validation->set_message('unique_email', 'The %s has already existed.');
                    return false;
               }
               return true;
            }
            return true;
        } else {
            if(!empty($this->input->post('email'))) {
               $user = $this->user_m->get_single_user(array('email' => $this->input->post('email'), 'delete_at' => 0));
               if(inicompute($user)) {
                    $this->form_validation->set_message('unique_email', 'The %s has already existed.');
                    return false;
               }
               return true;
            }
            return true;
        }
    }

    public function unique_username()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $user = $this->user_m->get_single_user(array('username' => $this->input->post('username'), 'patientID !=' => $id, 'delete_at' => 0));
            if(inicompute($user)) {
                $this->form_validation->set_message('unique_username', 'The %s has already existed.');
                return false;
            }
            return true;
        } else {
            $user = $this->user_m->get_single_user(array('username' => $this->input->post('username'), 'delete_at' => 0));
            if(inicompute($user)) {
                $this->form_validation->set_message('unique_username', 'The %s has already existed.');
                return false;
            }
            return true;
        }
    }

    public function unique_oldpatient()
    {
        $patientID   = htmlentities(escapeString($this->uri->segment(3)));
        if($this->input->post('oldpatient') == 0) {
            if((int)$patientID) {
                if($this->input->post('patienttypeID') == 0) {
                    $appointment = $this->appointment_m->get_single_appointment(array('patientID' => $patientID, 'status' => 1, 'appointmentID !=' => $this->data['appointment']->appointmentID));
                    if (!inicompute($appointment)) {
                        $this->form_validation->set_message('unique_oldpatient', 'This is the new patient.');
                        return false;
                    }
                    return true;
                } else {
                    $admission = $this->admission_m->get_single_admission(array('patientID' => $patientID, 'status' => 1, 'admissionID !=' => $this->data['admission']->admissionID));
                    if (!inicompute($admission)) {
                        $this->form_validation->set_message('unique_oldpatient', 'This is the new patient.');
                        return false;
                    }
                    return true;
                }
            }
            return true;
        }
        return true;
    }

    public function unique_bed()
    {
        $patientID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$patientID) {
            if($this->data['patient']->patienttypeID == 1) {
                if(inicompute($this->data['admission']) && $this->data['admission']->bedID != $this->input->post('bedID')) {
                    $bed = $this->bed_m->get_single_bed(array('bedID' => $this->input->post('bedID'), 'status' => 1));
                    if(inicompute($bed)) {
                        $this->form_validation->set_message('unique_bed', 'The %s has already exist');
                        return false;
                    }
                    return true;
                }
                return true;
            } else {
                $bed = $this->bed_m->get_single_bed(array('bedID' => $this->input->post('bedID'), 'status' => 1));
                if(inicompute($bed)) {
                    $this->form_validation->set_message('unique_bed', 'The %s has already exist');
                    return false;
                }
                return true;
            }
        } else {
            $bed = $this->bed_m->get_single_bed(array('bedID' => $this->input->post('bedID'), 'status' => 1));
            if(inicompute($bed)) {
                $this->form_validation->set_message('unique_bed', 'The %s has already existed.');
                return false;
            }
            return true;
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

    public function testfile()
    {
        if(permissionChecker('patient_view') || (($this->data['loginroleID'] == 3) && (!permissionChecker('patient_view')))) {
            $testID = $this->input->post('testID');
            if((int)$testID) {
                $this->data['testfiles'] = $this->testfile_m->get_order_by_testfile(array('testID'=> $testID));
                $this->load->view('patient/testfile', $this->data);
            } else {
                echo $this->lang->line('patient_data_not_found');
            }
        } else {
            echo $this->lang->line('patient_data_not_found');
        }
    }

    public function filedownload()
    {
        if(permissionChecker('patient_view') || (($this->data['loginroleID'] == 3) && (!permissionChecker('patient_view')))) {
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
                        redirect(site_url('patient/index'));
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

    public function getAmount()
    {
        if ($_POST) {
            $userID = $this->input->post('userID');
            if ($userID) {
                $queryArray['userID'] = $userID;
                $doctorinfo           = $this->doctorinfo_m->get_single_doctorinfo($queryArray);
                if (inicompute($doctorinfo)) {
                    echo $doctorinfo->visit_fee;
                }
            }
        }
    }

}
