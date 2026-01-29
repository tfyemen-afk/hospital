<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailsettings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("emailsettings_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('emailsettings', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'email_engine',
                'label' => $this->lang->line("emailsettings_email_engine"),
                'rules' => 'trim|required|callback_email_engine'
            )
        );

        if($this->input->post('email_engine') == 'smtp') {
            $rules[] = array(
                'field' => 'smtp_username',
                'label' => $this->lang->line("emailsettings_smtp_username"),
                'rules' => 'trim|required|max_length[255]'
            );

            $rules[] = array(
                'field' => 'smtp_password',
                'label' => $this->lang->line("emailsettings_smtp_password"),
                'rules' => 'trim|required|max_length[255]'
            );

            $rules[] = array(
                'field' => 'smtp_server',
                'label' => $this->lang->line("emailsettings_smtp_server"),
                'rules' => 'trim|required|max_length[255]'
            );

            $rules[] = array(
                'field' => 'smtp_port',
                'label' => $this->lang->line("emailsettings_smtp_port"),
                'rules' => 'trim|required|max_length[255]'
            );

            $rules[] = array(
                'field' => 'smtp_security',
                'label' => $this->lang->line("emailsettings_smtp_security"),
                'rules' => 'trim|max_length[255]'
            );
        }
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
                'assets/inilabs/emailsettings/index.js'
            )
        );

        $this->data['emailsettings'] = $this->emailsettings_m->get_emailsetting();
        if(inicompute($this->data['emailsettings'])) {
            $this->data['jsmanager'] = ['my_set_email_engine' => set_value('email_engine'), 'email_engine' => $this->data['emailsettings']->email_engine];
            if($_POST) {
                $this->data['jsmanager'] = ['my_set_email_engine' => set_value('email_engine'), 'email_engine' => $this->data['emailsettings']->email_engine];
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "emailsettings/index";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array = array();
                    for($i=0; $i<inicompute($rules); $i++) {
                        if($this->input->post($rules[$i]['field']) == false) {
                            $array[$rules[$i]['field']] = '';
                        } else {
                            $array[$rules[$i]['field']] = $this->input->post($rules[$i]['field']);
                        }
                    }

                    $this->emailsettings_m->insertorupdate($array);
                    $this->session->set_flashdata('success', "Success");
                    redirect(site_url("emailsettings/index"));
                }
            } else {
                $this->data["subview"] = "emailsettings/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function email_engine()
    {
        $email_engine = $this->input->post('email_engine');
        if($email_engine == 'select') {
            $this->form_validation->set_message('email_engine', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }
}