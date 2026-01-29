<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicinemanufacturer extends Admin_Controller
{
    public function __construct()
     {
        parent::__construct();
        $this->load->model('medicinemanufacturer_m');
        $language = $this->session->userdata('lang');;
        $this->lang->load('medicinemanufacturer', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("medicinemanufacturer_name"),
                'rules' => 'trim|required|max_length[60]'
            ),
            array(
                'field' => 'supplier_name',
                'label' => $this->lang->line("medicinemanufacturer_supplier_name"),
                'rules' => 'trim|required|max_length[60]'
            ),
            array(
                'field' => 'email',
                'label' => $this->lang->line("medicinemanufacturer_email"),
                'rules' => 'trim|required|valid_email'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("medicinemanufacturer_phone"),
                'rules' => 'trim|required|max_length[20]|numeric'
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line("medicinemanufacturer_address"),
                'rules' => 'trim|required|max_length[60]'
            ),
            array(
                'field' => 'details',
                'label' => $this->lang->line("medicinemanufacturer_details"),
                'rules' => 'trim|max_length[60]'
            )
        );
        return $rules;
    }

	public function index()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/medicinemanufacturer/index.js'
            ]
        ];

        $this->data['medicinemanufacturers'] = $this->medicinemanufacturer_m->get_medicinemanufacturer();
        if(permissionChecker('medicinemanufacturer_add')) {
            if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'medicinemanufacturer/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array  = array(
                        'name'          => $this->input->post('name'),
                        'supplier_name' => $this->input->post('supplier_name'),
                        'email'         => $this->input->post('email'),
                        'phone'         => $this->input->post('phone'),
                        'address'       => $this->input->post('address'),
                        'details'       => $this->input->post('details'),
                        'create_date'   => date('Y-m-d H:i:s'),
                        'modify_date'   => date('Y-m-d H:i:s'),
                        'create_userID' => $this->session->userdata('loginuserID'),
                        'create_roleID' => $this->session->userdata('roleID')
                    );

                    $this->medicinemanufacturer_m->insert_medicinemanufacturer($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('medicinemanufacturer/index'));
                }
            } else {
    		    $this->data["subview"] = 'medicinemanufacturer/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'medicinemanufacturer/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit()
    {
        $medicinemanufacturerID = escapeString($this->uri->segment('3'));
        if((int)$medicinemanufacturerID) {
            $this->data['medicinemanufacturer'] = $this->medicinemanufacturer_m->get_single_medicinemanufacturer(array('medicinemanufacturerID' => $medicinemanufacturerID));
            if(inicompute($this->data['medicinemanufacturer'])) {
                $this->data['medicinemanufacturers'] = $this->medicinemanufacturer_m->get_medicinemanufacturer();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'medicinemanufacturer/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array = array(
                            'name'          => $this->input->post('name'),
                            'supplier_name' => $this->input->post('supplier_name'),
                            'email'         => $this->input->post('email'),
                            'phone'         => $this->input->post('phone'),
                            'address'       => $this->input->post('address'),
                            'details'       => $this->input->post('details'),
                            'modify_date'   => date('Y-m-d H:i:s'),
                        );
                        $this->medicinemanufacturer_m->update_medicinemanufacturer($array, $medicinemanufacturerID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('medicinemanufacturer/index'));
                    }
                } else {
                    $this->data["subview"] = 'medicinemanufacturer/edit';
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
        $medicinemanufacturerID    = escapeString($this->uri->segment('3'));
        if((int)$medicinemanufacturerID) {
            $medicinemanufacturer  = $this->medicinemanufacturer_m->get_single_medicinemanufacturer(array('medicinemanufacturerID'=>$medicinemanufacturerID));
            if(inicompute($medicinemanufacturer)) {
                $this->medicinemanufacturer_m->delete_medicinemanufacturer($medicinemanufacturerID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('medicinemanufacturer/index'));
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
        $medicinemanufacturerID = $this->input->post('medicinemanufacturerID');
        if((int)$medicinemanufacturerID) {
            $medicinemanufacturer = $this->medicinemanufacturer_m->get_single_medicinemanufacturer(array('medicinemanufacturerID'=>$medicinemanufacturerID));
            if(inicompute($medicinemanufacturer)) {
                echo '<div class="profile-view-dis">';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('medicinemanufacturer_name').'</span>: '.$medicinemanufacturer->name.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('medicinemanufacturer_supplier_name').'</span>: '.$medicinemanufacturer->supplier_name.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('medicinemanufacturer_email').'</span>: '.$medicinemanufacturer->email.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('medicinemanufacturer_phone').'</span>: '.$medicinemanufacturer->phone.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('medicinemanufacturer_address').'</span>: '.$medicinemanufacturer->address.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                         echo '<p><span>'.$this->lang->line('medicinemanufacturer_details').'</span>: '.$medicinemanufacturer->details.'</p>';
                    echo '</div>';
                echo '</div>';
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
