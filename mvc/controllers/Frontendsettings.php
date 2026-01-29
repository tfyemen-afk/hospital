<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontendsettings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("frontendsettings_m");
        $this->load->helper('frontenddata');
        
        $language = $this->session->userdata('lang');;
        $this->lang->load('frontendsettings', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'login_menu_status',
                'label' => $this->lang->line("frontendsettings_login_menu_status"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'doctor_email_status',
                'label' => $this->lang->line("frontendsettings_doctor_email"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'doctor_phone_status',
                'label' => $this->lang->line("frontendsettings_doctor_phone"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("frontendsettings_description"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'facebook',
                'label' => $this->lang->line("frontendsettings_facebook"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'twitter',
                'label' => $this->lang->line("frontendsettings_twitter"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'linkedin',
                'label' => $this->lang->line("frontendsettings_linkedin"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'youtube',
                'label' => $this->lang->line("frontendsettings_youtube"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'google',
                'label' => $this->lang->line("frontendsettings_google"),
                'rules' => 'trim|max_length[255]'
            )
        );
        return $rules;
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/inilabs/select2.js'
            )
        );

        $this->data['frontendsettings'] = $this->frontendsettings_m->get_frontendsettings();
        if(inicompute($this->data['frontendsettings'])) {
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"]  = "frontendsettings/index";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array = array(
                        'login_menu_status'   => $this->input->post('login_menu_status'),
                        'doctor_email_status' => $this->input->post('doctor_email_status'),
                        'doctor_phone_status' => $this->input->post('doctor_phone_status'),
                        'description' => $this->input->post('description'),
                        'facebook'    => $this->input->post('facebook'),
                        'twitter'     => $this->input->post('twitter'),
                        'linkedin'    => $this->input->post('linkedin'),
                        'youtube'     => $this->input->post('youtube'),
                        'google'      => $this->input->post('google'),
                    );

                    $this->frontendsettings_m->insertorupdate($array);
                    frontenddata::get_frontend_delete();
                    frontenddata::get_frontend();
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url("frontendsettings/index"));
                }
            } else {
                $this->data["subview"] = "frontendsettings/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }
}
