<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgetpassword extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper("form");
        $this->load->helper("url");
        $this->load->library("mail");
        $this->load->library("email");
        $this->load->library("session");
        $this->load->library("form_validation");
        $this->load->model("user_m");
        $this->load->model("forgetpassword_m");
        $this->load->model("generalsettings_m");

        if($this->session->userdata('loginuserID')) {
            redirect(site_url('dashboard/index'));
        }

        $this->data["generalsettings"] = $this->generalsettings_m->get_generalsettings();
        $data = array(
            "lang" => $this->data["generalsettings"]->default_language,
        );
        $this->session->set_userdata($data);
        $language = $this->session->userdata('lang');
        $this->lang->load('forgetpassword', $language);
    }

	public function index()
    {
        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            $this->form_validation->set_error_delimiters("","");
            if($this->form_validation->run() == FALSE) {
                $this->load->view('forgetpassword/index', $this->data);
            } else {
                $email          = $this->input->post('email');
                $user           = $this->user_m->get_select_user('userID, name, roleID, username', ['email'=> $email, 'delete_at' => 0], true);
                $rand           = random19();
                $forget_key     = $this->forgetpassword_m->hash($rand.date('Y-m-d H:i:s').$user->roleID.$user->username.$user->name);
                $forget_url     = site_url("forgetpassword/password/".$forget_key);
                
                $array['userID']        = $user->userID;
                $array['keyID']         = $forget_key;
                $array['email']         = $email;
                $array['expire_date']   = date("Y-m-d H:i:s", strtotime("+1 hours"));
                $message                = 'Click here to and reset your password ' .$forget_url;

                $forgetpassword         = $this->forgetpassword_m->get_single_forgetpassword(['userID' => $user->userID]);
                if(inicompute($forgetpassword)) {
                    $this->forgetpassword_m->update_forgetpassword($array, $forgetpassword->forgetpasswordID);
                }

                $this->mail->sendmail($this->data, $email, 'Forget Password', $message);
                $this->session->set_flashdata('success', "Go to your email and inspection this mail.");
                redirect(site_url('forgetpassword/index'));
            }
        } else {
            $this->load->view('forgetpassword/index', $this->data);
        }
	}

    public function password()
    {
        $forget_key = htmlentities(escapeString($this->uri->segment(3)));
        if(!empty($forget_key)) {
            $forgetpassword = $this->forgetpassword_m->get_single_forgetpassword(['keyID' => $forget_key]);
            if(inicompute($forgetpassword)) {
                $current_date = strtotime(date("Y-m-d H:i:s"));
                $expire_date  = strtotime($forgetpassword->expire_date);
                if($current_date > $expire_date) {
                    $this->session->set_flashdata('error', "You link is expire, Please try again.");
                    redirect(site_url('forgetpassword/index'));
                } else {
                    if($_POST) {
                        $rules = $this->rules_password();
                        $this->form_validation->set_rules($rules);
                        $this->form_validation->set_error_delimiters("","");
                        if ($this->form_validation->run() == FALSE) {
                            $this->load->view('forgetpassword/password', $this->data);
                        } else {
                            $password    = $this->input->post('password');
                            $newpassword = $this->forgetpassword_m->hash($password);

                            $this->user_m->update_user(['password' => $newpassword], $forgetpassword->userID);
                            $this->forgetpassword_m->delete_forgetpassword($forgetpassword->forgetpasswordID);
                            $this->session->set_flashdata('success', 'Password Reset Successfully.');        
                            redirect(site_url('signin/index'));
                        }
                    } else {
                        $this->load->view('forgetpassword/password', $this->data);
                    }
                }
            } else {
                $this->session->set_flashdata('error', 'Your key does not match.');
                redirect(site_url('forgetpassword/index'));
            }
        } else {
            $this->session->set_flashdata('error', 'The key is not found.');
            redirect(site_url('forgetpassword/index'));
        }
    }

    private function rules()
    {
        $rules = array(
            array(
                'field' => 'email',
                'label' => $this->lang->line("forgetpassword_email"),
                    'rules' => 'trim|required|valid_email|callback_check_email'
            )
        );
        return $rules;
    }

    private function rules_password()
    {
        $rules = array(
            array(
                'field' => 'password',
                'label' => $this->lang->line("forgetpassword_password"),
                'rules' => 'trim|required|min_length[4]'
            ),
            array(
                'field' => 'confirm_password',
                'label' => $this->lang->line("forgetpassword_confirm_password"),
                'rules' => 'trim|required|matches[password]|min_length[4]'
            )
        );
        return $rules;
    }

        public function check_email($email)
    {
        $user = $this->user_m->get_select_user('userID, name', ['email'=> $email, 'delete_at' => 0], true);
        if(!inicompute($user)) {
            $this->form_validation->set_message("check_email", "This email does not exits.");
            return FALSE;
        } 
        return TRUE;
    }
}
