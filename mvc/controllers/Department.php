<?php
defined('BASEPATH') or exit('No direct script access allowed');

class department extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('department_m');

        $language = $this->session->userdata('lang');
        $this->lang->load('department', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("department_name"),
                'rules' => 'trim|required|max_length[128]',
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("department_description"),
                'rules' => 'trim|required',
            ),
        );
        return $rules;
    }

    public function index()
    {
        $this->data['departments'] = $this->department_m->get_department();
        if (permissionChecker('department_add')) {
            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = 'department/index';
                    $this->load->view('_layout_main', $this->data);
                } else {

                    $array["name"]          = $this->input->post("name");
                    $array["description"]   = $this->input->post("description");
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');
                    $this->department_m->insert_department($array);

                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('department/index'));
                }
            } else {
                $this->data["subview"] = 'department/index';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = 'department/index';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
        $departmentID = htmlentities(escapeString($this->uri->segment('3')));
        if ((int) $departmentID) {
            $this->data['department'] = $this->department_m->get_single_department(array('departmentID' => $departmentID));
            if (inicompute($this->data['department'])) {
                $this->data['departments'] = $this->department_m->get_department();
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == false) {
                        $this->data["subview"] = 'department/edit';
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array["name"]        = $this->input->post("name");
                        $array["description"] = $this->input->post("description");
                        $array["modify_date"] = date("Y-m-d H:i:s");
                        $this->department_m->update_department($array, $departmentID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(site_url('department/index'));
                    }
                } else {
                    $this->data["subview"] = 'department/edit';
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
        $departmentID = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $departmentID) {
            $department = $this->department_m->get_single_department(array('departmentID' => $departmentID));
            if (inicompute($department)) {
                if (config_item('demo') == false) {
                    if ($department->photo != 'holiday.png') {
                        if (file_exists(FCPATH . 'uploads/files/' . $department->photo)) {
                            unlink(FCPATH . 'uploads/files/' . $department->photo);
                        }
                    }
                }
                $this->department_m->delete_department($departmentID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('department/index'));
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
