<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Medicinewarehouse extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('medicinewarehouse_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('medicinewarehouse', $language);
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("medicinewarehouse_name"),
                'rules' => 'trim|required|max_length[120]|callback_unique_medicinewarehousename'
            ),
            array(
                'field' => 'code',
                'label' => $this->lang->line("medicinewarehouse_code"),
                'rules' => 'trim|required|max_length[40]|callback_unique_medicinewarehousecode'
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("medicinewarehouse_email"),
                'rules' => 'trim|valid_email|max_length[40]'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("medicinewarehouse_phone"),
                'rules' => 'trim|max_length[20]'
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line("medicinewarehouse_address"),
                'rules' => 'trim|max_length[200]'
            )
        );
        return $rules;
    }

	public function index()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/medicinewarehouse/index.js'
            ]
        ];

        $this->data['medicinewarehouses'] = $this->medicinewarehouse_m->get_medicinewarehouse();
        if(permissionChecker('medicinewarehouse_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'medicinewarehouse/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['name']    = $this->input->post('name');
                    $array['code']    = $this->input->post('code');
                    $array['email']   = $this->input->post('email');
                    $array['phone']   = $this->input->post('phone');
                    $array['address'] = $this->input->post('address');
                    $array['create_date']   = date('Y-m-d H:i:s');
                    $array['modify_date']   = date('Y-m-d H:i:s');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_roleID'] = $this->session->userdata('roleID');

                    $this->medicinewarehouse_m->insert_medicinewarehouse($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('medicinewarehouse/index'));
                }
            } else {
    		    $this->data["subview"] = 'medicinewarehouse/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'medicinewarehouse/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/medicinewarehouse/index.js'
            ]
        ];

        $medicinewarehouseID = escapeString($this->uri->segment('3'));
        if((int)$medicinewarehouseID) {
            $this->data['medicinewarehouse'] = $this->medicinewarehouse_m->get_single_medicinewarehouse(array('medicinewarehouseID'=>$medicinewarehouseID));
            if(inicompute($this->data['medicinewarehouse'])) {
                $this->data['medicinewarehouses'] = $this->medicinewarehouse_m->get_medicinewarehouse();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'medicinewarehouse/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array['name']    = $this->input->post('name');
                        $array['code']    = $this->input->post('code');
                        $array['email']   = $this->input->post('email');
                        $array['phone']   = $this->input->post('phone');
                        $array['address'] = $this->input->post('address');
                        $array['modify_date']      = date('Y-m-d H:i:s');

                        $this->medicinewarehouse_m->update_medicinewarehouse($array, $medicinewarehouseID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('medicinewarehouse/index'));
                    }
                } else {
                    $this->data["subview"] = 'medicinewarehouse/edit';
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
        $medicinewarehouseID = escapeString($this->uri->segment('3'));
        if((int)$medicinewarehouseID) {
            $medicinewarehouse = $this->medicinewarehouse_m->get_single_medicinewarehouse(array('medicinewarehouseID'=>$medicinewarehouseID));
            if(inicompute($medicinewarehouse)) {
                $this->medicinewarehouse_m->delete_medicinewarehouse($medicinewarehouseID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('medicinewarehouse/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_medicinewarehousename($name)
    {
        $medicinewarehouseID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$medicinewarehouseID){
            $medicinewarehouse = $this->medicinewarehouse_m->get_order_by_medicinewarehouse(array("name" => $name, "medicinewarehouseID !=" => $medicinewarehouseID));
            if(inicompute($medicinewarehouse)) {
                $this->form_validation->set_message("unique_medicinewarehousename", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $medicinewarehouse = $this->medicinewarehouse_m->get_order_by_medicinewarehouse(array("name" => $name));
            if(inicompute($medicinewarehouse)) {
                $this->form_validation->set_message("unique_medicinewarehousename", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }   

    public function unique_medicinewarehousecode($code)
    {
        $medicinewarehouseID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$medicinewarehouseID){
            $medicinewarehouse = $this->medicinewarehouse_m->get_order_by_medicinewarehouse(array("code" => $code, "medicinewarehouseID !=" => $medicinewarehouseID));
            if(inicompute($medicinewarehouse)) {
                $this->form_validation->set_message("unique_medicinewarehousecode", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            $medicinewarehouse = $this->medicinewarehouse_m->get_order_by_medicinewarehouse(array("code" => $code));
            if(inicompute($medicinewarehouse)) {
                $this->form_validation->set_message("unique_medicinewarehousecode", "This %s already exists.");
                return FALSE;
            } else {
                return TRUE;
            }
        }
    }

    public function view() {
        if(permissionChecker('medicinewarehouse_view')) {
            $medicinewarehouseID = $this->input->post('medicinewarehouseID');
            if((int)$medicinewarehouseID) {
                $this->data['medicinewarehouse']  = $this->medicinewarehouse_m->get_single_medicinewarehouse(array('medicinewarehouseID'=> $medicinewarehouseID));
                if(inicompute($this->data['medicinewarehouse'])) {
                    $this->load->view('medicinewarehouse/view', $this->data);
                } else {
                    $this->errorViewLoad();
                }
            } else {
                $this->errorViewLoad();
            }
        } else {
            $this->errorViewLoad();
        }
    }

    private function errorViewLoad() { ?>
        <div class="error-card">
            <div class="error-title-block">
                <h1 class="error-title">404</h1>
                <h2 class="error-sub-title"> Sorry, data not found </h2>
            </div>
            <div class="error-container">
                <a class="btn btn-primary" href="<?=site_url('dashboard/index')?>">
                <i class="fa fa-angle-left"></i> Back to Dashboard</a>
            </div>
        </div>
        <?php 
    }


}
