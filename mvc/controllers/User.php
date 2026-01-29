<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Admin_Controller
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
        $this->load->model('doctorinfo_m');
        $this->load->model('department_m');
        $this->load->library('mail');
        $this->load->library('report', $this->data);

        $language = $this->session->userdata('lang');
        $this->lang->load('user', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("user_name"),
                'rules' => 'trim|required|max_length[40]',
            ),
            array(
                'field' => 'designationID',
                'label' => $this->lang->line("user_designation"),
                'rules' => 'trim|required|callback_required_no_zero|callback_unique_designation',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("user_description"),
                'rules' => 'trim|max_length[255]',
            ),
            array(
                'field' => 'dob',
                'label' => $this->lang->line("user_dob"),
                'rules' => 'trim|required|callback_valid_date',
            ),
            array(
                'field' => 'gender',
                'label' => $this->lang->line("user_gender"),
                'rules' => 'trim|callback_required_no_zero',
            ),
            array(
                'field' => 'religion',
                'label' => $this->lang->line("user_religion"),
                'rules' => 'trim|max_length[30]',
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("user_email"),
                'rules' => 'trim|required|valid_email|callback_unique_email',
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("user_phone"),
                'rules' => 'trim|required|numeric|max_length[20]',
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line("user_address"),
                'rules' => 'trim|max_length[50]',
            ),
            array(
                'field' => 'jod',
                'label' => $this->lang->line("user_jod"),
                'rules' => 'trim|required|callback_valid_date',
            ),
            array(
                'field' => 'photo',
                'label' => $this->lang->line("user_photo"),
                'rules' => 'trim|callback_photoupload',
            ),
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("user_role"),
                'rules' => 'trim|required|callback_required_no_zero|callback_unique_role',
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("user_status"),
                'rules' => 'trim|required|callback_required_no_zero',
            ),
            array(
                'field' => 'username',
                'label' => $this->lang->line("user_username"),
                'rules' => 'trim|required|min_length[4]|callback_unique_username',
            ),
            array(
                'field' => 'password',
                'label' => $this->lang->line("user_password"),
                'rules' => 'trim|required|min_length[4]',
            ),
        );

        if ($this->input->post('designationID') == 3) {
            $doctorRules = array(
                array(
                    'field' => 'departmentID',
                    'label' => $this->lang->line("user_department"),
                    'rules' => 'trim|required|numeric',
                ),
                array(
                    'field' => 'online_consultation',
                    'label' => $this->lang->line("user_online_consultation"),
                    'rules' => 'trim|required|numeric|callback_required_no_zero',
                ),
                array(
                    'field' => 'visit_fee',
                    'label' => $this->lang->line("user_visit_fee"),
                    'rules' => 'trim|required|numeric',
                )
            );

            if ($this->input->post('online_consultation') == 1) {
                $doctorRules[] = [
                    'field' => 'consultation_fee',
                    'label' => $this->lang->line("user_consultation_fee"),
                    'rules' => 'trim|required|numeric',
                ];
            } else {
                $doctorRules[] = [
                    'field' => 'consultation_fee',
                    'label' => $this->lang->line("user_consultation_fee"),
                    'rules' => 'trim|numeric',
                ];
            }

            $rules = array_merge($rules, $doctorRules);
        }

        return $rules;
    }

    public function index()
    {
        $this->data['users']        = $this->user_m->get_order_by_user(array('userID !=' => 1, 'roleID !=' => 3, 'delete_at' => 0));
        $this->data['roles']        = pluck($this->role_m->get_order_by_role(array('roleID !=' => 3)), 'role', 'roleID');
        $this->data['designations'] = pluck($this->designation_m->get_order_by_designation(array('designationID != ' => 2)), 'designation', 'designationID');
        $this->data['departments']  = $this->department_m->get_department();
        if (permissionChecker('user_add')) {
            $this->data['headerassets'] = array(
                'css' => array(
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css',
                    'assets/datepicker/dist/css/bootstrap-datepicker.min.css',
                ),
                'js'  => array(
                    'assets/select2/select2.js',
                    'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                ),
            );

            $this->data['footerassets'] = array(
                'js' => array(
                    'assets/inilabs/user/index.js',
                    'assets/inilabs/user/table.js',
                ),
            );

            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = 'user/index';
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array                  = [];
                    $array['name']          = $this->input->post('name');
                    $array['designationID'] = $this->input->post('designationID');
                    $array['description']   = $this->input->post('description');
                    $array['gender']        = $this->input->post('gender');
                    $array['dob']           = date('Y-m-d', strtotime($this->input->post('dob')));
                    $array['religion']      = $this->input->post('religion');
                    $array['email']         = $this->input->post('email');
                    $array['phone']         = $this->input->post('phone');
                    $array['address']       = $this->input->post('address');
                    $array['jod']           = date('Y-m-d', strtotime($this->input->post('jod')));
                    $array['photo']         = $this->upload_data['photo']['file_name'];
                    $array['roleID']        = $this->input->post('roleID');
                    $array['status']        = $this->input->post('status');
                    $array['username']      = $this->input->post('username');
                    $array['password']      = $this->user_m->hash($this->input->post('password'));
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');

                    $this->_forEmailSend($array);

                    $this->user_m->insert_user($array);

                    if ($array['designationID'] == 3) {
                        $doctorArray['userID']              = $this->db->insert_id();
                        $doctorArray['visit_fee']           = $this->input->post('visit_fee');
                        $doctorArray['departmentID']        = $this->input->post('departmentID');
                        $doctorArray['online_consultation'] = $this->input->post('online_consultation');
                        $doctorArray['consultation_fee']    = $this->input->post('consultation_fee');

                        $this->doctorinfo_m->insert_doctorinfo($doctorArray);
                    }
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('user/index'));
                }
            } else {
                $this->data["subview"] = 'user/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'user/index';
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function _forEmailSend($array)
    {
        $email = $array['email'];
        if ($email) {
            $passArray                    = $array;
            $passArray['password']        = $this->input->post('password');
            $passArray['generalsettings'] = $this->data['generalsettings'];

            $message = $this->load->view('user/mail', $passArray, true);
            $message = trim($message);

            $subject = $this->lang->line('user_user') . " " . $this->lang->line('user_registration');
            @$this->mail->sendmail($this->data, $email, $subject, $message);
        }
    }

    private function _getDoctorInfo($user)
    {
        $doctorinfo = $this->doctorinfo_m->get_single_doctorinfo(['userID' => $user->userID]);
        if (inicompute($user) && inicompute($doctorinfo)) {
            $department = $this->department_m->get_single_department(['departmentID' => $doctorinfo->departmentID]);
            if (inicompute($department)) {
                $doctorinfo->departmentName = $department->name;
            } else {
                $doctorinfo->departmentName = "";

            }
            return $doctorinfo;
        } else {
            $doctorArray['departmentID']        = 0;
            $doctorArray['departmentName']      = '';
            $doctorArray['visit_fee']           = '';
            $doctorArray['online_consultation'] = '';
            $doctorArray['consultation_fee']    = '';

            return (object) $doctorArray;
        }
    }

    public function edit()
    {
        $userID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $userID) {
            $this->data['user'] = $this->user_m->get_single_user(array('userID' => $userID, 'userID !=' => 1, 'roleID !=' => 3, 'delete_at' => 0));
            if (inicompute($this->data['user'])) {

                $this->data['doctorinfo'] = $this->_getDoctorInfo($this->data['user']);

                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datepicker/dist/css/bootstrap-datepicker.min.css',
                    ),
                    'js'  => array(
                        'assets/select2/select2.js',
                        'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                    ),
                );

                $this->data['footerassets'] = array(
                    'js' => array(
                        'assets/inilabs/user/index.js',
                        'assets/inilabs/user/table.js',
                    ),
                );

                $this->data['users']        = $this->user_m->get_order_by_user(array('userID !=' => 1, 'roleID !=' => 3, 'delete_at' => 0));
                $this->data['roles']        = pluck($this->role_m->get_order_by_role(array('roleID !=' => 3)), 'role', 'roleID');
                $this->data['designations'] = pluck($this->designation_m->get_order_by_designation(array('designationID !=' => 2)), 'designation', 'designationID');
                $this->data['departments']  = $this->department_m->get_department();

                if ($_POST) {
                    $rules = $this->rules();
                    unset($rules[14]);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = 'user/edit';
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array                  = [];
                        $array['name']          = $this->input->post('name');
                        $array['designationID'] = $this->input->post('designationID');
                        $array['description']   = $this->input->post('description');
                        $array['gender']        = $this->input->post('gender');
                        $array['dob']           = date('Y-m-d', strtotime($this->input->post('dob')));
                        $array['religion']      = $this->input->post('religion');
                        $array['email']         = $this->input->post('email');
                        $array['phone']         = $this->input->post('phone');
                        $array['address']       = $this->input->post('address');
                        $array['jod']           = date('Y-m-d', strtotime($this->input->post('jod')));
                        $array['photo']         = $this->upload_data['photo']['file_name'];
                        $array['roleID']        = $this->input->post('roleID');
                        $array['status']        = $this->input->post('status');
                        $array['username']      = $this->input->post('username');
                        $array["modify_date"]   = date("Y-m-d H:i:s");

                        $this->user_m->update_user($array, $userID);

                        if ($array['designationID'] == 3) {
                            $doctorinfo = $this->doctorinfo_m->get_single_doctorinfo(['userID' => $userID]);
                            if (inicompute($doctorinfo)) {
                                $doctorArray['userID']              = $userID;
                                $doctorArray['departmentID']        = $this->input->post('departmentID');
                                $doctorArray['visit_fee']           = $this->input->post('visit_fee');
                                $doctorArray['online_consultation'] = $this->input->post('online_consultation');
                                $doctorArray['consultation_fee']    = $this->input->post('consultation_fee');

                                $this->doctorinfo_m->update_doctorinfo($doctorArray, $doctorinfo->doctorinfoID);
                            }
                        }

                        $this->session->set_flashdata('success', 'Success');
                        redirect(site_url('user/index'));
                    }
                } else {
                    $this->data["subview"] = 'user/edit';
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
                'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
                'assets/inilabs/user/view.js',
            ),
        );

        $userID = htmlentities(escapeString($this->uri->segment('3')));
        if ((int) $userID) {
            $this->data['jsmanager'] = ['userID' => $userID];
            $user                    = $this->user_m->get_single_user(array('userID' => $userID, 'userID !=' => 1, 'roleID !=' => 3, 'delete_at' => 0));
            $this->pluckInfo();
            $this->basicInfo($user);
            $this->attendanceInfo($user);
            $this->salaryInfo($user);
            $this->paymentInfo($user);
            $this->documentInfo($user);

            if (inicompute($user)) {
                $this->data["subview"] = 'user/view';
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

    private function pluckInfo()
    {
        $this->data['roles']        = pluck($this->role_m->get_role(), 'role', 'roleID');
        $this->data['designations'] = pluck($this->designation_m->get_designation(), 'designation', 'designationID');
    }

    private function basicInfo($user)
    {
        if (inicompute($user)) {
            $this->data['profile'] = $user;
        } else {
            $this->data['profile'] = [];
        }
        $this->data['doctorinfo'] = $this->_getDoctorInfo($user);
    }

    private function attendanceInfo($user)
    {
        if (inicompute($user)) {
            $this->data["attendances"] = pluck($this->attendance_m->get_order_by_attendance(array('year' => date('Y'), 'userID' => $user->userID)), 'obj', 'monthyear');
        } else {
            $this->data["attendances"] = [];
        }
    }

    private function salaryInfo($user)
    {
        if (inicompute($user)) {
            $this->data['managesalary'] = $this->managesalary_m->get_single_managesalary(array('userID' => $user->userID));
            if (inicompute($this->data['managesalary'])) {
                if ($this->data['managesalary']->salary == '1') {
                    $this->data['salarytemplate'] = $this->salarytemplate_m->get_single_salarytemplate(array('salarytemplateID' => $this->data['managesalary']->template));
                    if (inicompute($this->data['salarytemplate'])) {
                        $this->salaryoption_m->order("salaryoptionID asc");
                        $this->data['salaryoptions'] = $this->salaryoption_m->get_order_by_salaryoption(array('salarytemplateID' => $this->data['managesalary']->template));

                        $grosssalary      = 0;
                        $totaldeduction   = 0;
                        $netsalary        = $this->data['salarytemplate']->basic_salary;
                        $orginalNetsalary = $this->data['salarytemplate']->basic_salary;

                        if (inicompute($this->data['salaryoptions'])) {
                            foreach ($this->data['salaryoptions'] as $salaryOptionKey => $salaryOption) {
                                if ($salaryOption->option_type == 1) {
                                    $netsalary += $salaryOption->label_amount;
                                    $grosssalary += $salaryOption->label_amount;
                                } elseif ($salaryOption->option_type == 2) {
                                    $netsalary -= $salaryOption->label_amount;
                                    $totaldeduction += $salaryOption->label_amount;
                                }
                            }
                        }

                        $this->data['grosssalary']    = $grosssalary + $orginalNetsalary;
                        $this->data['totaldeduction'] = $totaldeduction;
                        $this->data['netsalary']      = $netsalary;
                    } else {
                        $this->data['salarytemplate'] = [];
                        $this->data['salaryoptions']  = [];
                        $this->data['grosssalary']    = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary']      = 0;
                    }
                } elseif ($this->data['managesalary']->salary == '2') {
                    $this->data['hourly_salary'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID' => $this->data['managesalary']->template));
                    if (inicompute($this->data['hourly_salary'])) {
                        $this->data['grosssalary']    = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary']      = $this->data['hourly_salary']->hourly_rate;
                    } else {
                        $this->data['hourly_salary']  = [];
                        $this->data['grosssalary']    = 0;
                        $this->data['totaldeduction'] = 0;
                        $this->data['netsalary']      = 0;
                    }
                }
            } else {
                $this->data['managesalary']    = [];
                $this->data['salary_template'] = [];
                $this->data['salaryoptions']   = [];
                $this->data['hourly_salary']   = [];
                $this->data['grosssalary']     = 0;
                $this->data['totaldeduction']  = 0;
                $this->data['netsalary']       = 0;
            }
        } else {
            $this->data['managesalary']    = [];
            $this->data['salary_template'] = [];
            $this->data['salaryoptions']   = [];
            $this->data['hourly_salary']   = [];
            $this->data['grosssalary']     = 0;
            $this->data['totaldeduction']  = 0;
            $this->data['netsalary']       = 0;
        }
    }

    private function paymentInfo($user)
    {
        if (inicompute($user)) {
            $this->makepayment_m->order("makepaymentID desc");
            $this->data["makepayments"] = $this->makepayment_m->get_order_by_makepayment(array('year' => date('Y'), 'userID' => $user->userID));
        } else {
            $this->data['makepayments'] = [];
        }
    }

    private function documentInfo($user)
    {
        if (inicompute($user)) {
            $this->data['documents'] = $this->document_m->get_order_by_document(array('userID' => $user->userID));
        } else {
            $this->data['documents'] = [];
        }
    }

    public function status()
    {
        $f = false;
        if (permissionChecker('user_edit')) {
            if ($_POST) {
                $userID = $this->input->post('userid');
                $status = $this->input->post('status');
                if ((int) $userID) {
                    $user = $this->user_m->get_single_user(array('userID' => $userID, 'userID !=' => 1));
                    if (inicompute($user)) {
                        if ($status == 'checked') {
                            $this->user_m->update_user(array('status' => 1), $userID);
                            $f = true;
                        } elseif ($status == 'unchecked') {
                            $this->user_m->update_user(array('status' => 2), $userID);
                            $f = true;
                        }
                    }
                }
            }
        }
        if ($f) {
            echo "Success";
        } else {
            echo "Error";
        }
    }

    public function delete()
    {
        $userID = htmlentities(escapeString($this->uri->segment('3')));
        if ((int) $userID) {
            $user = $this->user_m->get_single_user(array('userID' => $userID, 'userID !=' => 1, 'roleID !=' => 3, 'delete_at' => 0));
            if (inicompute($user)) {
                $this->user_m->update_user(['delete_at' => 1, 'status' => 0], $userID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('user/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function downloaddocument()
    {
        $documentID = htmlentities(escapeString($this->uri->segment(3)));
        $userID     = htmlentities(escapeString($this->uri->segment(4)));
        if ((int) $documentID && (int) $userID) {
            if ((permissionChecker('user_add') && permissionChecker('user_delete')) || ($this->session->userdata('loginuserID') == $userID)) {
                $document = $this->document_m->get_single_document(array('documentID' => $documentID));
                if (inicompute($document)) {
                    $file = realpath('uploads/files/' . $document->file);
                    if (file_exists($file)) {
                        $expFileName  = explode('.', $file);
                        $originalname = ($document->title) . '.' . end($expFileName);
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . basename($originalname) . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                        exit;
                    } else {
                        redirect(site_url('user/view/' . $userID));
                    }
                } else {
                    redirect(site_url('user/view/' . $userID));
                }
            } else {
                redirect(site_url('user/view/' . $userID));
            }
        } else {
            redirect(site_url('user/index'));
        }
    }

    public function deletedocument()
    {
        $documentID = htmlentities(escapeString($this->uri->segment(3)));
        $userID     = htmlentities(escapeString($this->uri->segment(4)));
        if ((int) $documentID && (int) $userID) {
            if (permissionChecker('user_add') && permissionChecker('user_delete')) {
                $document = $this->document_m->get_single_document(array('documentID' => $documentID));
                if (inicompute($document)) {
                    if (config_item('demo') == false) {
                        if (file_exists(FCPATH . 'uploads/files/' . $document->file)) {
                            unlink(FCPATH . 'uploads/files/' . $document->file);
                        }
                    }
                    $this->document_m->delete_document($documentID);
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('user/view/' . $userID));
                } else {
                    redirect(site_url('user/view/' . $userID));
                }
            } else {
                redirect(site_url('user/view/' . $userID));
            }
        } else {
            redirect(site_url('user/index'));
        }
    }

    public function printpreview()
    {
        if (permissionChecker('user_view')) {
            $userID = escapeString($this->uri->segment('3'));
            if ((int) $userID) {
                $user = $this->user_m->get_single_user(array('userID' => $userID, 'userID !=' => 1, 'roleID !=' => 3));
                if (inicompute($user)) {
                    $this->data['user']       = $user;
                    $this->data['doctorinfo'] = $this->_getDoctorInfo($user);
                    $this->pluckInfo();
                    $this->report->reportPDF(['stylesheet' => 'usermodule.css', 'data' => $this->data, 'viewpath' => 'user/printpreview']);
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
        $retArray['status']  = false;
        if (permissionChecker('user_view')) {
            if ($_POST) {
                $rules = $this->sendmail_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $userID = $this->input->post('userID');
                    $user   = $this->user_m->get_single_user(array('userID' => $userID, 'userID !=' => 1, 'roleID !=' => 3));
                    if (inicompute($user)) {
                        $email   = $this->input->post('to');
                        $subject = $this->input->post('subject');
                        $message = $this->input->post('message');

                        $this->data['user']       = $user;
                        $this->data['doctorinfo'] = $this->_getDoctorInfo($user);
                        $this->pluckInfo();

                        $this->report->reportSendToMail(['stylesheet' => 'usermodule.css', 'data' => $this->data, 'viewpath' => 'user/printpreview', 'email' => $email, 'subject' => $subject, 'message' => $message]);
                        $retArray['message'] = "Success";
                        $retArray['status']  = true;
                    } else {
                        $retArray['message'] = $this->lang->line('user_data_not_found');
                    }
                }
            } else {
                $retArray['message'] = $this->lang->line('user_method_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('user_permission_not_allowed');
        }
        echo json_encode($retArray);
        exit;
    }

    protected function sendmail_rules()
    {
        $rules = array(
            array(
                'field' => 'to',
                'label' => $this->lang->line("user_to"),
                'rules' => 'trim|required|valid_email',
            ),
            array(
                'field' => 'subject',
                'label' => $this->lang->line("user_subject"),
                'rules' => 'trim|required',
            ),
            array(
                'field' => 'message',
                'label' => $this->lang->line("user_message"),
                'rules' => 'trim',
            ),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("user_user"),
                'rules' => 'trim|required|numeric',
            ),
        );
        return $rules;
    }

    protected function uploaddocument_rules()
    {
        $rules = array(
            array(
                'field' => 'document_title',
                'label' => $this->lang->line("user_document_title"),
                'rules' => 'trim|required|max_length[40]',
            ),
            array(
                'field' => 'document_file',
                'label' => $this->lang->line("user_document_file"),
                'rules' => 'trim|callback_unique_document_upload',
            ),
        );
        return $rules;
    }

    public function uploaddocument()
    {
        $retArray            = [];
        $retArray['status']  = false;
        $retArray['message'] = '';
        if ($_POST) {
            if (permissionChecker('user_add')) {
                $rules = $this->uploaddocument_rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $retArray           = $this->form_validation->error_array();
                    $retArray['status'] = false;
                } else {
                    $array = array(
                        'title'         => $this->input->post('document_title'),
                        'file'          => $this->upload_data['document_file']['file_name'],
                        'userID'        => $this->input->post('userID'),
                        'create_date'   => date('Y-m-d H:i:s'),
                        'modify_date'   => date('Y-m-d H:i:s'),
                        'create_userID' => $this->session->userdata('loginuserID'),
                        'create_roleID' => $this->session->userdata('roleID'),
                    );

                    $this->document_m->insert_document($array);
                    $this->session->set_flashdata('success', 'Success');
                    $retArray['status'] = true;
                }
            } else {
                $retArray['message'] = $this->lang->line('user_permission_not_allowed');
            }
        } else {
            $retArray['message'] = $this->lang->line('managesalary_method_not_allowed');
        }
        echo json_encode($retArray);
    }

    public function photoupload()
    {
        $new_file = "default.png";
        if ($_FILES["photo"]['name'] != "") {
            $file_name        = $_FILES["photo"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode          = explode('.', $file_name);
            if (inicompute($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/user";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name']     = $new_file;
                $config['max_size']      = '1024';
                $config['max_width']     = '3000';
                $config['max_height']    = '3000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("photo")) {
                    $this->form_validation->set_message("photoupload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['photo'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("photoupload", "Invalid file");
                return false;
            }
        } else {
            $userID = escapeString($this->uri->segment('3'));
            if ((int) $userID) {
                $user = $this->user_m->get_single_user(array('userID' => $userID));
                if (inicompute($user)) {
                    $this->upload_data['photo'] = array('file_name' => $user->photo);
                    return true;
                } else {
                    $this->upload_data['photo'] = array('file_name' => $new_file);
                    return true;
                }
            } else {
                $this->upload_data['photo'] = array('file_name' => $new_file);
                return true;
            }
        }
    }

    public function unique_document_upload()
    {
        if ($_FILES["document_file"]['name'] != "") {
            $file_name        = $_FILES["document_file"]['name'];
            $random           = random19();
            $makeRandom       = hash('sha512', $random . (strtotime(date('Y-m-d H:i:s'))) . config_item("encryption_key"));
            $file_name_rename = 'document_' . $makeRandom;
            $explode          = explode('.', $file_name);
            if (inicompute($explode) >= 2) {
                $new_file                = $file_name_rename . '.' . end($explode);
                $config['upload_path']   = "./uploads/files";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name']     = $new_file;
                $config['max_size']      = '5120';
                $config['max_width']     = '10000';
                $config['max_height']    = '10000';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload("document_file")) {
                    $this->form_validation->set_message("unique_document_upload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['document_file'] = $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("unique_document_upload", "Invalid File");
                return false;
            }
        } else {
            $this->form_validation->set_message("unique_document_upload", "The File is required.");
            return false;
        }
    }

    public function required_no_zero($data)
    {
        if ($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return false;
        } else {
            return true;
        }
    }

    public function unique_designation()
    {
        $designation = $this->designation_m->get_single_designation(array($this->input->post('designationID') => 2));
        if (inicompute($designation)) {
            $this->form_validation->set_message("unique_designation", "This %s is deny.");
            return false;
        }
        return true;
    }

    public function unique_role()
    {
        $role = $this->role_m->get_single_role(array($this->input->post('roleID') => 3));
        if (inicompute($role)) {
            $this->form_validation->set_message("unique_role", "This %s is deny.");
            return false;
        }
        return true;
    }

    public function valid_date($date)
    {
        if ($date) {
            if (strlen($date) < 10) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                return false;
            } else {
                $arr = explode("-", $date);
                if (inicompute($arr) == 3) {
                    $dd   = $arr[0];
                    $mm   = $arr[1];
                    $yyyy = $arr[2];
                    if (checkdate($mm, $dd, $yyyy)) {
                        return true;
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                        return false;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                    return false;
                }
            }
        } else {
            return true;
        }
    }

    public function unique_email()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            if (!empty($this->input->post('email'))) {
                $user = $this->user_m->get_single_user(["email" => $this->input->post('email'), 'delete_at' => 0, 'userID !=' => $id]);
                if (inicompute($user)) {
                    $this->form_validation->set_message("unique_email", "The %s already exists");
                    return false;
                }
                return true;
            }
            return true;
        } else {
            if (!empty($this->input->post('email'))) {
                $user = $this->user_m->get_single_user(["email" => $this->input->post('email'), 'delete_at' => 0]);
                if (inicompute($user)) {
                    $this->form_validation->set_message("unique_email", "The %s already exists");
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
        if ((int) $id) {
            if (!empty($this->input->post('username'))) {
                $user = $this->user_m->get_single_user(["username" => $this->input->post('username'), 'delete_at' => 0, 'userID !=' => $id]);
                if (inicompute($user)) {
                    $this->form_validation->set_message("unique_username", "The %s already exists");
                    return false;
                }
                return true;
            }
            return true;
        } else {
            if (!empty($this->input->post('username'))) {
                $user = $this->user_m->get_single_user(["username" => $this->input->post('username'), 'delete_at' => 0]);
                if (inicompute($user)) {
                    $this->form_validation->set_message("unique_username", "The %s already exists");
                    return false;
                }
                return true;
            }
            return true;
        }
    }

}
