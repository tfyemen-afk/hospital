<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ambulance extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ambulance_m');
        $language = $this->session->userdata('lang');;
        $this->lang->load('ambulance', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("ambulance_name"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'number',
                'label' => $this->lang->line("ambulance_number"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'model',
                'label' => $this->lang->line("ambulance_model"),
                'rules' => 'trim|max_length[100]'
            ),
            array(
                'field' => 'color',
                'label' => $this->lang->line("ambulance_color"),
                'rules' => 'trim|max_length[40]'
            ),
            array(
                'field' => 'cc',
                'label' => $this->lang->line("ambulance_cc"),
                'rules' => 'trim|max_length[11]|numeric'
            ),
            array(
                'field' => 'weight',
                'label' => $this->lang->line("ambulance_weight"),
                'rules' => 'trim|max_length[11]'
            ),
            array(
                'field' => 'fueltype',
                'label' => $this->lang->line("ambulance_fuel_type"),
                'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
            ),
            array(
                'field' => 'drivername',
                'label' => $this->lang->line("ambulance_driver_name"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'driverlicence',
                'label' => $this->lang->line("ambulance_driver_licence"),
                'rules' => 'trim|max_length[100]'
            ),
            array(
                'field' => 'drivercontact',
                'label' => $this->lang->line("ambulance_driver_contact"),
                'rules' => 'trim|required|max_length[25]'
            ),
            array(
                'field' => 'type',
                'label' => $this->lang->line("ambulance_type"),
                'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
            ),
            array(
                'field' => 'note',
                'label' => $this->lang->line("ambulance_note"),
                'rules' => 'trim|max_length[100]'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("ambulance_status"),
                'rules' => 'trim|required|max_length[4]|numeric|callback_required_no_zero'
            )
        );
        return $rules;
    }

	public function index() 
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ],
            'js'  => [
                'assets/select2/select2.js',
                'assets/inilabs/ambulance/index.js',
            ]
        ];

        $this->data['ambulances'] = $this->ambulance_m->get_ambulance();
        if(permissionChecker('ambulance_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'ambulance/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = [];
                    $array['name']      = $this->input->post('name');
                    $array['number']    = $this->input->post('number');
                    $array['model']     = $this->input->post('model');
                    $array['color']     = $this->input->post('color');
                    $array['cc']        = $this->input->post('cc');
                    $array['weight']    = $this->input->post('weight');
                    $array['fueltype']  = $this->input->post('fueltype');
                    $array['drivername']    = $this->input->post('drivername');
                    $array['driverlicence'] = $this->input->post('driverlicence');
                    $array['drivercontact'] = $this->input->post('drivercontact');
                    $array['type']          = $this->input->post('type');
                    $array['note']          = $this->input->post('note');
                    $array['status']        = $this->input->post('status');
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');
                    $this->ambulance_m->insert_ambulance($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('ambulance/index'));
                }
            } else {
    		    $this->data["subview"] = 'ambulance/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'ambulance/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $ambulanceID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$ambulanceID) {
            $this->data['ambulance'] = $this->ambulance_m->get_single_ambulance(array('ambulanceID'=> $ambulanceID));
            if(inicompute($this->data['ambulance'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/inilabs/ambulance/index.js',
                    )
                );
                $this->data['ambulances'] = $this->ambulance_m->get_ambulance();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'ambulance/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = [];
                        $array['name']      = $this->input->post('name');
                        $array['number']    = $this->input->post('number');
                        $array['model']     = $this->input->post('model');
                        $array['color']     = $this->input->post('color');
                        $array['cc']        = $this->input->post('cc');
                        $array['weight']    = $this->input->post('weight');
                        $array['fueltype']  = $this->input->post('fueltype');
                        $array['drivername']    = $this->input->post('drivername');
                        $array['driverlicence'] = $this->input->post('driverlicence');
                        $array['drivercontact'] = $this->input->post('drivercontact');
                        $array['type']          = $this->input->post('type');
                        $array['note']          = $this->input->post('note');
                        $array['status']        = $this->input->post('status');
                        $array["create_date"]   = date("Y-m-d H:i:s");
                        $array["modify_date"]   = date("Y-m-d H:i:s");
                        $array["create_userID"] = $this->session->userdata('loginuserID');
                        $array["create_roleID"] = $this->session->userdata('roleID');
                        $this->ambulance_m->update_ambulance($array, $ambulanceID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('ambulance/index'));
                    }
                } else {
                    $this->data["subview"] = 'ambulance/edit';
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
        $ambulanceID    = $this->input->post('ambulanceID');
        $this->data["ambulance"] = [];
        if((int)$ambulanceID) {
            $this->data["ambulance"] = $this->ambulance_m->get_single_ambulance(array('ambulanceID'=> $ambulanceID));;
        }
        $this->load->view('ambulance/view', $this->data);
    }

    public function delete() 
    {
        $ambulanceID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$ambulanceID) {
            $ambulance = $this->ambulance_m->get_single_ambulance(array('ambulanceID'=> $ambulanceID));
            if(inicompute($ambulance)) {
                $this->ambulance_m->delete_ambulance($ambulanceID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('ambulance/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
