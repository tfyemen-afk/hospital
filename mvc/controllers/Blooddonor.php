<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blooddonor extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('blooddonor_m');
        $this->load->model('bloodgroup_m');
        $this->load->model('bloodbag_m');
        $this->load->model('patient_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('blooddonor', $language);
    }

    protected function rules($numberofbag)
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("blooddonor_name"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'gender',
                'label' => $this->lang->line("blooddonor_gender"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'age',
                'label' => $this->lang->line("blooddonor_age"),
                'rules' => 'trim|required|numeric|max_length[3]'
            ),
            array(
                'field' => 'bloodgroupID',
                'label' => $this->lang->line("blooddonor_bloodgroupID"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("blooddonor_phone"),
                'rules' => 'trim|numeric'
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("blooddonor_email"),
                'rules' => 'trim|valid_email|max_length[60]'
            ),
            array(
                'field' => 'charges',
                'label' => $this->lang->line("blooddonor_charges"),
                'rules' => 'trim|required|numeric|max_length[16]'
            ),
            array(
                'field' => 'lastdonationdate',
                'label' => $this->lang->line("blooddonor_lastdonationdate"),
                'rules' => 'trim|max_length[10]'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("blooddonor_uhid"),
                'rules' => 'trim'
            ),
            array(
                'field' => 'numberofbag',
                'label' => $this->lang->line("blooddonor_numberofbag"),
                'rules' => 'trim|required|numeric|callback_required_no_zero'
            )
        );

        if($numberofbag > 0) {
            $j = inicompute($rules);
            for($i = 1; $i <= $numberofbag; $i++) {
                $rules[$j] = array(
                    "field" => "bagNo".$i,
                    "label" => $this->lang->line("blooddonor_bagNo_".$i),
                    "rules" => "trim|required|max_length[25]|callback_unique_bagno"
                );
                $j++;
            }
        }

        return $rules;
    }

    private function _displayManager($displayID)
    {
        if($displayID == 2) {
            $displayArray['YEAR(create_date)']      = date('Y');
            $displayArray['MONTH(create_date)']     = date('m');
        } elseif($displayID == 3) {
            $displayArray['YEAR(create_date)']      = date('Y');
        } elseif($displayID == 4) {
            $displayArray                           = [];
        } else {
            $displayID = 1;
            $displayArray['DATE(create_date)']      = date('Y-m-d');
        }
        $this->data['displayID']    = $displayID;

        if($this->data['loginroleID'] == 3) {
            $displayArray              = [];
            $displayArray['patientID'] = $this->data['loginuserID'];
        }

        return $displayArray;
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
                'assets/inilabs/blooddonor/index.js'
            )
        );

        $displayID      = htmlentities(escapeString($this->uri->segment(3)));
        $displayArray   = $this->_displayManager($displayID);
        $this->data['blooddonors'] = $this->blooddonor_m->get_select_blooddonor('blooddonorID, name, gender, age, bloodgroupID, phone', $displayArray);
        $this->data['numberofbag'] = $this->input->post('numberofbag') ? $this->input->post('numberofbag') : 0;

        $this->data['jsmanager'] = [
            'myNumberOfBag' => $this->data['numberofbag'],
            'bagNoLang1' => $this->lang->line('blooddonor_bagNo_1'),
            'bagNoLang2' => $this->lang->line('blooddonor_bagNo_2'),
            'bagNoLang3' => $this->lang->line('blooddonor_bagNo_3')
        ];

        $patientQueryArray['delete_at'] = 0;
        if($this->data['loginroleID'] == 3) {
            $patientQueryArray['patientID'] = $this->data['loginuserID'];
        }
        $this->patient_m->order('patientID desc');
        $this->data['patients']    = $this->patient_m->get_select_patient('patientID, name', $patientQueryArray);
        $this->data["bloodgroups"] = pluck($this->bloodgroup_m->get_bloodgroup(),'obj','bloodgroupID');

        if(permissionChecker('blooddonor_add')) {
            if($_POST) {
                $rules  = $this->rules($this->input->post('numberofbag'));
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    if ( $this->input->post('numberofbag') > 0 ) {
                        for ( $i = 1; $i <= $this->input->post('numberofbag'); $i++ ) {
                            $this->data['jsmanager'][ 'setValue' . $i ]    = set_value('bagNo' . $i);
                            $this->data['jsmanager'][ 'errorLabel' . $i ]  = form_error('bagNo' . $i);
                            $this->data['jsmanager'][ 'errorClass1' . $i ] = ( form_error('bagNo' . $i) ? 'text-danger' : '' );
                            $this->data['jsmanager'][ 'errorClass2' . $i ] = ( form_error('bagNo' . $i) ? 'is-invalid' : '' );
                        }
                    }

                    $this->data["subview"] = 'blooddonor/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = array(
                        'name'              => $this->input->post('name'),
                        'gender'            => $this->input->post('gender'),
                        'age'               => $this->input->post('age'),
                        'bloodgroupID'      => $this->input->post('bloodgroupID'),
                        'phone'             => $this->input->post('phone'),
                        'email'             => $this->input->post('email'),
                        'charges'           => $this->input->post('charges'),
                        'patientID'         => $this->input->post('patientID'),
                        'donartypeID'       => $this->donortypeID($this->input->post('patientID')),
                        'lastdonationdate'  => (!empty($this->input->post('lastdonationdate')) ? date('Y-m-d', strtotime($this->input->post('lastdonationdate'))) : ''),
                        'numberofbag'       => $this->input->post('numberofbag'),
                        'create_date'       => date('Y-m-d H:i:s'),
                        'modify_date'       => date('Y-m-d H:i:s'),
                        'create_userID'     => $this->session->userdata('loginuserID'),
                        'create_roleID'     => $this->session->userdata('roleID'),
                    );
                    $this->blooddonor_m->insert_blooddonor($array);
                    $donorID = $this->db->insert_id();

                    $numberofbag = $this->input->post('numberofbag');
                    if($numberofbag > 0) {
                        for($i=1; $i<=$numberofbag; $i++) {
                            $bagNo = 'bagNo'.$i;

                            $bloodbagArray[$i]['donorID']      = $donorID;
                            $bloodbagArray[$i]['donortypeID']  = $this->donortypeID($this->input->post('patientID'));
                            $bloodbagArray[$i]['bloodgroupID'] = $this->input->post('bloodgroupID');
                            $bloodbagArray[$i]['bagno']        = $this->input->post($bagNo);
                            $bloodbagArray[$i]['status']       = 0;
                            $bloodbagArray[$i]['date']         = date('Y-m-d H:i:s');
                            $bloodbagArray[$i]['patientID']    = $this->input->post('patientID');
                        }
                        $this->bloodbag_m->insert_batch_bloodbag($bloodbagArray);
                    }

                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('blooddonor/index'));
                }
            } else {
    		    $this->data["subview"] = 'blooddonor/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'blooddonor/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $blooddonorID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$blooddonorID && (int)$displayID) {
            $bloodDonorQueryArray['blooddonorID'] = $blooddonorID;
            if($this->data['loginroleID'] == 3) {
                $bloodDonorQueryArray['patientID'] = $this->data['loginuserID'];
            }
            $this->data['blooddonor']  = $this->blooddonor_m->get_single_blooddonor($bloodDonorQueryArray);
            if(inicompute($this->data['blooddonor'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                        'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                        'assets/inilabs/blooddonor/index.js'
                    )
                );

                $displayArray   = $this->_displayManager($displayID);
                $this->data['blooddonors'] = $this->blooddonor_m->get_select_blooddonor('blooddonorID, name, gender, age, bloodgroupID, phone', $displayArray);
                $this->data["bloodgroups"] = pluck($this->bloodgroup_m->get_bloodgroup(),'obj', 'bloodgroupID');

                $patientQueryArray['delete_at'] = 0;
                if($this->data['loginroleID'] == 3) {
                    $patientQueryArray['patientID'] = $this->data['loginuserID'];
                }
                $this->patient_m->order('patientID desc');
                $this->data['patients']    = $this->patient_m->get_select_patient('patientID, name', $patientQueryArray);
                $this->data["bloodbags"]   = $this->bloodbag_m->get_order_by_bloodbag(array('donorID' => $blooddonorID));

                $this->data['jsmanager'] = [
                    'myNumberOfBag' => $this->data['blooddonor']->numberofbag,
                    'bagNoLang1' => $this->lang->line('blooddonor_bagNo_1'),
                    'bagNoLang2' => $this->lang->line('blooddonor_bagNo_2'),
                    'bagNoLang3' => $this->lang->line('blooddonor_bagNo_3')
                ];

                if(inicompute($this->data["bloodbags"])) {
                    if ( inicompute($this->data["bloodbags"]) > 0 ) {
                        $i = 1;
                        foreach ($this->data["bloodbags"] as $bloodbags) {
                            $this->data['jsmanager'][ 'setValue' . $i ]    = $bloodbags->bagno;
                            $this->data['jsmanager'][ 'errorLabel' . $i ]  = '';
                            $this->data['jsmanager'][ 'errorClass1' . $i ] = '';
                            $this->data['jsmanager'][ 'errorClass2' . $i ] = '';
                            $i++;
                        }
                    }
                }

                if($_POST) {
                    $rules  = $this->rules($this->data['blooddonor']->numberofbag);
                    unset($rules[9]);
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        if ( $this->data['blooddonor']->numberofbag > 0 ) {
                            for ( $i = 1; $i <= $this->data['blooddonor']->numberofbag; $i++ ) {
                                $this->data['jsmanager'][ 'setValue' . $i ]    = set_value('bagNo' . $i);
                                $this->data['jsmanager'][ 'errorLabel' . $i ]  = form_error('bagNo' . $i);
                                $this->data['jsmanager'][ 'errorClass1' . $i ] = ( form_error('bagNo' . $i) ? 'text-danger' : '' );
                                $this->data['jsmanager'][ 'errorClass2' . $i ] = ( form_error('bagNo' . $i) ? 'is-invalid' : '' );
                            }
                        }
                        $this->data["subview"] = 'blooddonor/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = array(
                            'name'              => $this->input->post('name'),
                            'gender'            => $this->input->post('gender'),
                            'age'               => $this->input->post('age'),
                            'bloodgroupID'      => $this->input->post('bloodgroupID'),
                            'phone'             => $this->input->post('phone'),
                            'email'             => $this->input->post('email'),
                            'charges'           => $this->input->post('charges'),
                            'patientID'         => $this->input->post('patientID'),
                            'donartypeID'       => $this->donortypeID($this->input->post('patientID')),
                            'lastdonationdate'  => ((!empty($this->input->post('lastdonationdate'))) ? (date('Y-m-d', strtotime($this->input->post('lastdonationdate')))) : ''),
                            'modify_date'       => date('Y-m-d H:i:s')
                        );
                        $this->blooddonor_m->update_blooddonor($array, $blooddonorID);

                        $numberofbag = $this->data['blooddonor']->numberofbag;
                        if($numberofbag > 0) {
                            $bloodbags = $this->data["bloodbags"];
                            for($i = 1; $i <= $numberofbag; $i++) {
                                $bagNo = 'bagNo'.$i;
                                $j = $i-1;

                                $bloodbagArray[$i]['bloodbagID']   = $bloodbags[$j]->bloodbagID;
                                $bloodbagArray[$i]['donorID']      = $blooddonorID;
                                $bloodbagArray[$i]['donortypeID']  = $this->donortypeID($this->input->post('patientID'));
                                $bloodbagArray[$i]['bloodgroupID'] = $this->input->post('bloodgroupID');
                                $bloodbagArray[$i]['bagno']        = $this->input->post($bagNo);
                                $bloodbagArray[$i]['date']         = date('Y-m-d H:i:s');
                                $bloodbagArray[$i]['patientID']    = $this->input->post('patientID');
                            }
                            $this->bloodbag_m->update_batch_bloodbag($bloodbagArray, 'bloodbagID');
                        }
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('blooddonor/index/'.$displayID));
                    }
                } else {
                    $this->data["subview"] = 'blooddonor/edit';
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
        $blooddonorID = htmlentities(escapeString($this->uri->segment(3)));
        $displayID    = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$blooddonorID && (int)$displayID) {
            $bloodDonorQueryArray['blooddonorID'] = $blooddonorID;
            if($this->data['loginroleID'] == 3) {
                $bloodDonorQueryArray['patientID'] = $this->data['loginuserID'];
            }
            $blooddonor = $this->blooddonor_m->get_single_blooddonor($bloodDonorQueryArray);
            if(inicompute($blooddonor)) {
                $bloodbags = $this->bloodbag_m->get_order_by_bloodbag(array('donorID' => $blooddonorID));
                if(inicompute($bloodbags)) {
                    foreach ($bloodbags as $bloodbag) {
                        $this->bloodbag_m->delete_bloodbag($bloodbag->bloodbagID);
                    }
                }
                $this->blooddonor_m->delete_blooddonor($blooddonorID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('blooddonor/index/'.$displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function donortypeID($data)
    {
        if ($data == '0') {
            $data = '1';
        } else {
            $data = '2';
        }
        return $data;
    }

    public function required_no_zero($data)
    {
        if($data != '') {
            if($data == '0') {
                $this->form_validation->set_message("required_no_zero", "The %s field is required.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
        return TRUE;
    }

    public function unique_bagno($data)
    {
        $blooddonorID   = htmlentities(escapeString($this->uri->segment(3)));
        $displayID      = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$blooddonorID && ($displayID)) {
            if(!empty($data)) {
                $bloodbags  = $this->bloodbag_m->get_order_by_bloodbag([ "donorID" => $blooddonorID ]);
                $blooddonor = $this->blooddonor_m->get_single_blooddonor([ "blooddonorID" => $blooddonorID ]);
                if ( inicompute($bloodbags) ) {
                    $numberofbag = $blooddonor->numberofbag;
                    $bagArray    = [];
                    for ( $i = 1; $i <= $numberofbag; $i++ ) {
                        $bagNo                    = 'bagNo' . $i;
                        $bagArray[ "bagno" . $i ] = $this->input->post($bagNo);
                    }
                    $bagcount = inicompute(array_unique($bagArray));
                    if ( $numberofbag == $bagcount ) {
                        $array   = pluck($bloodbags, 'bloodbagID', 'bloodbagID');
                        $results = $this->bloodbag_m->get_where_not_in_bloodbag($data, $array);
                        if ( inicompute($results) ) {
                            $this->form_validation->set_message("unique_bagno", "This %s already exists.");
                            return false;
                        } else {
                            return true;
                        }
                    } else {
                        $this->form_validation->set_message("unique_bagno", "This %s already exists.");
                        return false;
                    }
                } else {
                    $this->form_validation->set_message("unique_bagno", "This %s already exists.");
                    return false;
                }
            } else {
                $this->form_validation->set_message("unique_bagno", "The %s field is required.");
                return FALSE;
            }
        } else {
            if(!empty($data)) {
                $bloodbag = $this->bloodbag_m->get_order_by_bloodbag(array("bagno" => $data));
                if(inicompute($bloodbag)) {
                    $this->form_validation->set_message("unique_bagno", "This %s already exists.");
                    return FALSE;
                } else {
                    $numberofbag = $this->input->post('numberofbag');
                    $bagArray   = [];
                    for($i = 1; $i <= $numberofbag; $i++) {
                        $bagNo = 'bagNo'.$i;
                        $bagArray["bagno".$i] = $this->input->post($bagNo);
                    }
                    $bagcount = inicompute(array_unique($bagArray));
                    if($numberofbag == $bagcount) {
                        return TRUE;
                    } else {
                        $this->form_validation->set_message("unique_bagno", "This %s already exists.");
                        return FALSE;
                    }
                }
            } else {
                $this->form_validation->set_message("unique_bagno", "The %s field is required.");
                return FALSE;
            }

        }
    }

    public function view()
    {
        $blooddonorID = $this->input->post('blooddonorID');
        if((int)$blooddonorID) {
            $bloodDonorQueryArray['blooddonorID'] = $blooddonorID;
            if($this->data['loginroleID'] == 3) {
                $bloodDonorQueryArray['patientID'] = $this->data['loginuserID'];
            }
            $blooddonor = $this->blooddonor_m->get_single_blooddonor($bloodDonorQueryArray);
            if(inicompute($blooddonor)) {
                $genders        = [1 => $this->lang->line('blooddonor_male'), 2 => $this->lang->line('blooddonor_female')];
                $bloodgroups    = pluck($this->bloodgroup_m->get_bloodgroup(), 'bloodgroup', 'bloodgroupID');
                if($blooddonor->patientID > 0) {
                    $patient    = $this->patient_m->get_select_patient('patientID, name', ['patientID' => $blooddonor->patientID], true);
                } else {
                    $patient    = [];
                }
                $bloodbags      = $this->bloodbag_m->get_order_by_bloodbag(array('donorID' => $blooddonorID));

                echo '<div class="profile-view-dis">';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_name').'</span>: '.$blooddonor->name.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_gender').'</span>: '.(isset($genders[$blooddonor->gender]) ? $genders[$blooddonor->gender] : '').'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_bloodgroupID').'</span>: '.(isset($bloodgroups[$blooddonor->bloodgroupID]) ? $bloodgroups[$blooddonor->bloodgroupID] : '').'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_phone').'</span>: '.$blooddonor->phone.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_email').'</span>: '.$blooddonor->email.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_lastdonationdate').'</span>: '.$blooddonor->lastdonationdate.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_numberofbag').'</span>: '.$blooddonor->numberofbag.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_charges').'</span>: '.$blooddonor->charges.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_uhid').'</span>: '.(inicompute($patient) ? $patient->patientID : '').'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('blooddonor_patient').'</span>: '.(inicompute($patient) ? $patient->name : '').'</p>';
                    echo '</div>';
                echo '</div>';

                echo '<table class="table table-bordered">';
                    echo '<thead>';
                        echo '<tr>';
                            echo '<th>';
                                echo $this->lang->line('blooddonor_slno');
                            echo '</th>';
                            echo '<th>';
                                echo $this->lang->line('blooddonor_bagno');
                            echo '</th>';
                            echo '<th>';
                                echo $this->lang->line('blooddonor_blood');
                            echo '</th>';
                            echo '<th>';
                                echo $this->lang->line('blooddonor_status');
                            echo '</th>';
                        echo '<tr>';
                    echo '</thead>';

                    if(inicompute($bloodbags)) {
                        echo '<tbody>';
                            $i = 1;
                            foreach ($bloodbags as $bloodbag) {
                                echo '<tr>';
                                    echo '<td>';
                                        echo $i;
                                    echo '</td>';
                                    echo '<td>';
                                        echo $bloodbag->bagno;
                                    echo '</td>';
                                    echo '<td>';
                                        echo (($bloodbag->donortypeID == 2) ? '<span class="text-danger">'.$this->lang->line('blooddonor_private').'</span>' : '<span class="text-success">'.$this->lang->line('blooddonor_public').'<span>');
                                    echo '</td>';
                                    echo '<td>';
                                        echo (($bloodbag->status == 1) ? '<span class="text-danger">'.$this->lang->line('blooddonor_release').'</span>' : '<span class="text-success">'.$this->lang->line('blooddonor_reserve').'</span');
                                    echo '</td>';
                                echo '</tr>';
                                $i++;
                            }
                        echo '<tbody>';
                    }
                echo '<table>';
            } else {
                echo '<div class="error-card">';
                    echo '<div class="error-title-block">';
                        echo '<h1 class="error-title">404</h1>';
                        echo '<h2 class="error-sub-title"> Sorry, page not found </h2>';
                    echo '</div>';
                    echo '<div class="error-container">';
                        echo '<a class="btn btn-primary" href="'.site_url('dashboard/index').'">';
                        echo '<i class="fa fa-angle-left"></i> Back to Dashboard</a>';
                    echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="error-card">';
                echo '<div class="error-title-block">';
                    echo '<h1 class="error-title">404</h1>';
                    echo '<h2 class="error-sub-title"> Sorry, page not found </h2>';
                echo '</div>';
                echo '<div class="error-container">';
                    echo '<a class="btn btn-primary" href="'.site_url('dashboard/index').'">';
                    echo '<i class="fa fa-angle-left"></i> Back to Dashboard</a>';
                echo '</div>';
            echo '</div>';
        }
    }
}
