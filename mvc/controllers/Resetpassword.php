<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Resetpassword extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
		$this->load->model("user_m");
        $this->load->model("role_m");
        $this->load->model("resetpassword_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('resetpassword', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'roleID',
				'label' => $this->lang->line("resetpassword_role"),
				'rules' => 'trim|required|callback_required_no_zero'
			),
            array(
                'field' => 'userID',
                'label' => $this->lang->line("resetpassword_username"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'newpassword',
                'label' => $this->lang->line("resetpassword_newpassword"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'repassword',
                'label' => $this->lang->line("resetpassword_repassword"),
                'rules' => 'trim|required|matches[newpassword]'
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
                'assets/inilabs/resetpassword/index.js',
            )
        );

        $this->data['jsmanager'] = ['resetpassword_please_select' => $this->lang->line('resetpassword_please_select')];
		$this->data['roles']     = pluck($this->role_m->get_role(), 'obj', 'roleID');
        $this->data['users']     = pluck($this->user_m->get_select_user('userID, name, username'), 'obj', 'userID');
        $this->data['resetpasswords']  = $this->resetpassword_m->get_resetpassword();

        if($this->input->post('roleID')) {
            $this->data['roleusers']  = $this->user_m->get_select_user('userID, username', array('roleID'=> $this->input->post('roleID'), 'userID !='=>1));
        } else {
            $this->data['roleusers']  = [];
        }

        if($_POST) {
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = "resetpassword/index";
                $this->load->view('_layout_main', $this->data);
            } else {
                $array['roleID'] 	    = $this->input->post('roleID');
                $array['userID'] 	    = $this->input->post('userID');
                $array['create_date'] 	= date('Y-m-d H:i:s');
                $array['modify_date'] 	= date('Y-m-d H:i:s');
                $array['create_userID'] = $this->session->userdata('loginuserID');
                $array['create_roleID'] = $this->session->userdata('roleID');

                $userArray['modify_date'] = date('Y-m-d H:i:s');
                $userArray['password']    = $this->user_m->hash($this->input->post('newpassword'));

                $this->user_m->update_user($userArray, $array['userID']);
                $this->resetpassword_m->insert_resetpassword($array);
                $this->session->set_flashdata('success','Success');
                redirect(site_url("resetpassword/index"));
            }
        } else {
            $this->data["subview"] = "resetpassword/index";
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function required_no_zero($data)
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }

    public function getuser()
    {
        $retArray = [];
        $retArray['status'] = FALSE;
        if($_POST) {
            $roleID  = $this->input->post('roleID');
            if((int)$roleID) {
                $users = $this->user_m->get_select_user('userID, username', array('roleID' => $roleID, 'userID !='=>1));
                if(inicompute($users)) {
                    $retArray['status'] = TRUE;
                    $options = '<option value="0">'.$this->lang->line('resetpassword_please_select').'</option>';
                    foreach($users as $user) {
                        $options .= '<option value="'.$user->userID.'">'.$user->username.'</option>';
                    }               
                    $retArray['data'] = $options;
                } else {
                    $retArray['message'] = $this->lang->line('resetpassword_user_not_found');
                }
            } else {
                $retArray['message'] = $this->lang->line('resetpassword_user_not_found');
            }
        } else {
            $retArray['message'] = $this->lang->line('resetpassword_method_not_allow');
        }
        echo json_encode($retArray);
    }
}