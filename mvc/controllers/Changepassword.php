<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Changepassword extends Admin_Controller
{
    protected $_userID;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('changepassword', $language);
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'oldpassword',
                'label' => $this->lang->line('changepassword_oldpassword'),
                'rules' => 'trim|required|max_length[40]|min_length[4]|callback_unique_oldpassword'
            ),
            array(
                'field' => 'newpassword',
                'label' => $this->lang->line('changepassword_newpassword'),
                'rules' => 'trim|required|max_length[40]|min_length[4]'
            ),
            array(
                'field' => 'repetpassword',
                'label' => $this->lang->line('changepassword_repetpassword'),
                'rules' => 'trim|required|max_length[40]|min_length[4]|matches[newpassword]'
            )
        );
        return $rules;
    }

    public function index()
    {
        if($_POST) {
            $rules  = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = 'changepassword/index';
                $this->load->view('_layout_main', $this->data);
            } else {
                $newPassword = $this->user_m->hash($this->input->post('newpassword'));
                $this->user_m->update_user(['password' => $newPassword], $this->_userID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('changepassword/index'));
            }
        } else {
            $this->data["subview"] = 'changepassword/index';
            $this->load->view('_layout_main', $this->data);
        }

    }

    public function unique_oldpassword()
    {
        if(!empty($this->input->post('oldpassword'))) {
            $oldPassword = $this->user_m->hash($this->input->post('oldpassword'));
            $user = $this->user_m->get_single_user(['username' => $this->session->userdata('username'), 'password' => $oldPassword]);
            if(inicompute($user)) {
                $this->_userID = $user->userID;
                return true;
            } else {
                $this->form_validation->set_message("unique_oldpassword", "%s does not match");
                return false;
            }
        } else {
            $this->form_validation->set_message("unique_oldpassword", "The %s field is required.");
            return false;
        }
    }
}