<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class bloodstock extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('patient_m');
        $this->load->model('bloodbag_m');
        $this->load->model('bloodgroup_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('bloodstock', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'bloodgroupID',
                'label' => $this->lang->line("bloodstock_blood_group"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'bagno',
                'label' => $this->lang->line("bloodstock_bagNo"),
                'rules' => 'trim|required|callback_unique_bagno'
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
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/inilabs/select2.js',
            )
        );

        $this->bloodgroup_m->order('bloodgroupID asc');
        $this->data["bloodgroups"] = pluck($this->bloodgroup_m->get_select_bloodgroup('bloodgroupID, bloodgroup'),'obj','bloodgroupID');
        $this->data['bloodstocks'] = $this->bloodstocksinicompute();
        if(permissionChecker('bloodstock_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'bloodstock/index';
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $bloodbagArray['donorID']       = 0;
                    $bloodbagArray['donortypeID']   = 3;
                    $bloodbagArray['bloodgroupID']  = $this->input->post('bloodgroupID');
                    $bloodbagArray['patientID']     = 0;
                    $bloodbagArray['bagno']         = $this->input->post('bagno');
                    $bloodbagArray['status']        = 0;
                    $bloodbagArray['date']          = date('Y-m-d H:i:s');

                    $this->bloodbag_m->insert_bloodbag($bloodbagArray);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('bloodstock/index'));
                }
            } else {
    		    $this->data["subview"] = 'bloodstock/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'bloodstock/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit()
    {
        $bloodbagID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$bloodbagID) {
            $this->data['bloodbag'] = $this->bloodbag_m->get_single_bloodbag(array('bloodbagID' => $bloodbagID));
            if (inicompute($this->data['bloodbag'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/inilabs/select2.js',
                    )
                );

                $this->data["bloodgroups"] = pluck($this->bloodgroup_m->get_select_bloodgroup('bloodgroupID, bloodgroup'),'obj','bloodgroupID');
                $this->data['bloodstocks'] = $this->bloodstocksinicompute();
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "bloodstock/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = [
                            'bloodgroupID'  => $this->input->post('bloodgroupID'),
                            'bagno'         => $this->input->post('bagno'),
                        ];

                        $this->session->set_flashdata('success', 'Success');
                        $this->bloodbag_m->update_bloodbag($array, $bloodbagID);
                        redirect(site_url('bloodstock/index'));
                    }
                } else {
                    $this->data["subview"] = "bloodstock/edit";
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

    public function delete()
    {
        $bloodbagID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int)$bloodbagID) {
            $bloodbag = $this->bloodbag_m->get_single_bloodbag(array('bloodbagID' => $bloodbagID));
            if (inicompute($bloodbag)) {
                $this->bloodbag_m->delete_bloodbag($bloodbagID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('bloodstock/index'));
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
        $bloodgroupID = escapeString($this->uri->segment('3'));
        if((int)$bloodgroupID) {
            $this->data["bloodgroup"]       = $this->bloodgroup_m->get_single_bloodgroup(array('bloodgroupID' => $bloodgroupID));
            if(inicompute($this->data["bloodgroup"])) {
                $this->data['headerassets'] = [
                    'css' => [
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ],
                    'js'  => [
                        'assets/select2/select2.js',
                        'assets/inilabs/bloodstock/view.js'
                    ]
                ];

                $this->data['bloodstocks']      = $this->bloodstocksinicompute();
                $this->bloodbag_m->order('bloodbagID desc');
                $this->data['bloodbags']        = $this->bloodbag_m->get_order_by_bloodbag(array('bloodgroupID' => $bloodgroupID));

                $this->patient_m->order('patientID asc');
                $this->data['patients']    = pluck($this->patient_m->get_select_patient('patientID, name', array('delete_at' => 0)),'obj','patientID');

                $this->data["bloodgroupID"]   = $bloodgroupID;
                $this->data["subview"]        = 'bloodstock/view';
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

    public function getbloodbaginfo()
    {
        $bloodbagID = $this->input->post('bloodbagID');
        $retArray['status'] = false;
        $retArray['message'] = 'Error';
        $retArray['data'] = [];
        if((int)$bloodbagID) {
            $bloodbag = $this->bloodbag_m->get_single_bloodbag(array('bloodbagID' => $bloodbagID));
            if(inicompute($bloodbag)) {
                $retArray['data'] = $bloodbag;
                $retArray['status'] = true;
                $retArray['message'] = 'Success';
            }
        }
        echo json_encode($retArray);
    }

    protected function bloodstockupdate_rules()
    {
        $rules = array(
            array(
                'field' => 'status',
                'label' => $this->lang->line("bloodstock_status"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'patientID',
                'label' => $this->lang->line("bloodstock_patient"),
                'rules' => 'trim|required|callback_unique_patientID'
            )
        );
        return $rules;
    }

    public function bloodstockupdate()
    {
        $retArray = []; 
        $retArray['statuss']  = FALSE; 
        $retArray['message'] = ''; 
        if($_POST) {
            if(permissionChecker('bloodstock_edit')) {
                $bloodbagID = $this->input->post('bloodbagid');
                if((int)$bloodbagID) {
                    $bloodbag  = $this->bloodbag_m->get_single_bloodbag(array('bloodbagID' => $bloodbagID));
                    if(inicompute($bloodbag)) {
                        $rules = $this->bloodstockupdate_rules();
                        $this->form_validation->set_rules($rules);
                        if($this->form_validation->run() == FALSE) {
                            $retArray = $this->form_validation->error_array();
                            $retArray['statuss']  = FALSE; 
                        } else {
                            $status = $this->input->post('status');
                            $array  = array(
                                'status'     => $status-1 ,
                                'patientID'  => $this->input->post('patientID')
                            );
                            $this->bloodbag_m->update_bloodbag($array, $bloodbagID);
                            $retArray['statuss']  = TRUE; 
                            $this->session->set_flashdata('success','Success');
                        }
                    } else {
                        $retArray['message'] = $this->lang->line('managesalary_data_not_found'); 
                    }
                } else {
                    $retArray['message'] = $this->lang->line('managesalary_data_not_found'); 
                }
            } else {
                $retArray['message'] = $this->lang->line('managesalary_permission_not_allowed'); 
            }
        } else {
            $retArray['message'] = $this->lang->line('managesalary_method_not_allowed'); 
        }
        echo json_encode($retArray);
    }

    public function bloodstocksinicompute()
    {
        $bloodbags = $this->bloodbag_m->get_select_bloodbag('bloodbagID, bloodgroupID, status');

        $retArray = [];
        if(inicompute($bloodbags)) {
            foreach ($bloodbags as $bloodbag) {
                if(isset($retArray[$bloodbag->bloodgroupID])) {
                    $retArray[$bloodbag->bloodgroupID]['total'] += 1;
                    $retArray[$bloodbag->bloodgroupID]['reserve'] += ($bloodbag->status == 0) ? 1 : 0;
                    $retArray[$bloodbag->bloodgroupID]['release'] += ($bloodbag->status == 1) ? 1 : 0;
                } else {
                    $retArray[$bloodbag->bloodgroupID]['total'] = 1;
                    $retArray[$bloodbag->bloodgroupID]['reserve'] = ($bloodbag->status == 0) ? 1 : 0;
                    $retArray[$bloodbag->bloodgroupID]['release'] = ($bloodbag->status == 1) ? 1 : 0;
                }
            }
        }
        return $retArray;
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

    public function unique_bagno($data)
    {
        $bloodbagID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$bloodbagID){
            $bloodbag = $this->bloodbag_m->get_order_by_bloodbag(array("bagno" => $data, "bloodbagID !=" => $bloodbagID));
            if(inicompute($bloodbag)) {
                $this->form_validation->set_message("unique_bagno", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $bloodbag = $this->bloodbag_m->get_order_by_bloodbag(array("bagno" => $data));
            if(inicompute($bloodbag)) {
                $this->form_validation->set_message("unique_bagno", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function unique_patientID()
    {
        $status = $this->input->post('status');
        if($status == 2) {
            $patientID = $this->input->post('patientID');
            if($patientID == 0) {
                $this->form_validation->set_message("unique_patientID", "This %s field is required.");
                return false;
            }
            return true;
        }
        return true;
    }
}
