<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hourlytemplate extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("hourlytemplate_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('hourlytemplate', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'hourly_grades',
                'label' => $this->lang->line("hourlytemplate_hourly_grades"),
                'rules' => 'trim|required|max_length[128]|callback_unique_hourly_grades'
            ),
            array(
                'field' => 'hourly_rate',
                'label' => $this->lang->line("hourlytemplate_hourly_rate"),
                'rules' => 'trim|numeric|required|max_length[11]'
            )
        );
        return $rules;
    }

    public function index()
    {
        $this->data['hourlytemplates'] = $this->hourlytemplate_m->get_order_by_hourlytemplate();
        if(permissionChecker('hourlytemplate_add')) {
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "hourlytemplate/index";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array = array(
                        "hourly_grades" => $this->input->post("hourly_grades"),
                        'hourly_rate'   => $this->input->post('hourly_rate'),
                        "create_date"   => date('Y-m-d H:i:s'),
                        "modify_date"   => date('Y-m-d H:i:s'),
                        "create_userID" => $this->session->userdata('loginuserID'),
                        "create_roleID" => $this->session->userdata('roleID')
                    );
                    $this->hourlytemplate_m->insert_hourlytemplate($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url("hourlytemplate/index"));
                }
            } else {
                $this->data["subview"] = "hourlytemplate/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "hourlytemplate/index";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit()
    {
        $hourlytemplateID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$hourlytemplateID) {
            $this->data['hourlytemplate'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID'=> $hourlytemplateID));
            if(inicompute($this->data['hourlytemplate'])) {
                $this->data['hourlytemplates'] = $this->hourlytemplate_m->get_order_by_hourlytemplate();
                if($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = "hourlytemplate/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array = array(
                            "hourly_grades" => $this->input->post("hourly_grades"),
                            'hourly_rate'   => $this->input->post('hourly_rate'),
                            "modify_date"   => date("Y-m-d H:i:s"),
                        );

                        $this->hourlytemplate_m->update_hourlytemplate($array, $hourlytemplateID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url("hourlytemplate/index"));
                    }
                } else {
                    $this->data["subview"] = "hourlytemplate/edit";
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
        $hourlytemplateID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$hourlytemplateID) {
            $this->data['hourlytemplate'] = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourlytemplateID' => $hourlytemplateID));
            if(inicompute($this->data['hourlytemplate'])) {
                $this->hourlytemplate_m->delete_hourlytemplate($hourlytemplateID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url("hourlytemplate/index"));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_hourly_grades($data)
    {
        $hourlytemplateID = htmlentities(escapeString($this->uri->segment(3)));
        if((int) $hourlytemplateID) {
            $hourly_grades = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourly_grades' => $data, 'hourlytemplateID !=' => $hourlytemplateID));
            if(inicompute($hourly_grades)) {
                $this->form_validation->set_message("unique_hourly_grades", "the %s already exists");
                return FALSE;
            }
            return TRUE;
        } else {
            $hourly_grades = $this->hourlytemplate_m->get_single_hourlytemplate(array('hourly_grades' => $data));
            if(inicompute($hourly_grades)) {
                $this->form_validation->set_message("unique_hourly_grades", "The %s already exists");
                return FALSE;
            }
            return TRUE;
        }
    }
}
