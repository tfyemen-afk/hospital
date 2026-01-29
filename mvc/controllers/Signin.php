<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signin extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('updatechecker');
        $this->load->model('loginlog_m');
        $this->load->model('user_m');
        $this->load->model('role_m');
        $this->load->model('designation_m');
        $data = [
            "lang" => $this->data["generalsettings"]->default_language,
        ];
        $this->session->set_userdata($data);
        $language = $this->session->userdata('lang');
        $this->lang->load('signin', $language);
    }

    public function index()
    {
        if ( $this->data['generalsettings']->captcha_status ) {
            $this->load->library('recaptcha');
            $this->data['recaptcha'] = [
                'widget' => $this->recaptcha->getWidget(),
                'script' => $this->recaptcha->getScriptTag(),
            ];
        }

        $this->signin_m->loggedin() == false || redirect(site_url('dashboard/index'));
        $this->data['form_validation'] = 'No';
        if ( $_POST ) {
            $this->_setCookie();
            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ( $this->form_validation->run() == false ) {
                $this->data['form_validation'] = validation_errors();
                $this->load->view('signin/index', $this->data);
            } else {
                $checkArray = $this->_signInChecker();
                if ( $checkArray['return'] == true ) {
                    redirect(site_url('dashboard/index'));
                } else {
                    $this->data['form_validation'] = $checkArray['message'];
                    $this->load->view('signin/index', $this->data);
                }
            }
        } else {
            $this->load->view('signin/index', $this->data);
            $this->session->sess_destroy();
        }
    }

    private function _setCookie()
    {
        if ( isset($_POST['remember']) ) {
            set_cookie('remember_username', $this->input->post('username'), time() + ( 86400 * 30 ));
            set_cookie('remember_password', $this->input->post('password'), time() + ( 86400 * 30 ));
        } else {
            delete_cookie('remember_username');
            delete_cookie('remember_password');
        }
    }

    private function rules()
    {
        $rules = [
            [
                'field' => 'username',
                'label' => $this->lang->line("signin_username"),
                'rules' => 'trim|required|min_length[4]'
            ],
            [
                'field' => 'password',
                'label' => $this->lang->line("signin_password"),
                'rules' => 'trim|required|min_length[4]'
            ]
        ];

        if ( $this->data["generalsettings"]->captcha_status ) {
            $rules[] = [
                'field' => 'g-recaptcha-response',
                'label' => "captcha",
                'rules' => 'trim|required'
            ];
        }
        return $rules;
    }

    private function _signInChecker()
    {
        if ( config_item('demo') == false ) {
            $updateChecker = $this->_updateCodeChecker();
                $verifyValidUser = true;
                $returnArray     = [ 'return' => true, 'message' => 'Success' ];
        } else {
            $returnArray     = [ 'return' => true, 'message' => 'Success' ];
            $verifyValidUser = true;
        }

        $generalsettings = $this->data['generalsettings'];
        $lang            = $generalsettings->default_language;
        $username        = $this->input->post('username');
        $password        = $this->user_m->hash($this->input->post('password'));
        $user            = $this->user_m->get_user_with_user_email($username, $password, true);

        if ( isset($this->data['generalsettings']->captcha_status) && $this->data['generalsettings']->captcha_status ) {
            $captchaResponse = $this->recaptcha->verifyResponse($this->input->post('g-recaptcha-response'));
        } else {
            $captchaResponse = [ 'success' => true ];
        }

        if ( $returnArray['return'] == true ) {
            if ( $captchaResponse['success'] == true ) {
                if ( inicompute($user) ) {
                    $role        = $this->role_m->get_single_role([ 'roleID' => $user->roleID ]);
                    $designation = $this->designation_m->get_single_designation([ 'designationID' => $user->designationID ]);
                    if ( inicompute($role) ) {
                        if ( $user->status == 1 ) {
                            $sessionArray = [
                                'loginuserID'     => $user->userID,
                                'name'            => $user->name,
                                'username'        => $user->username,
                                'email'           => $user->email,
                                'role'            => $role->role,
                                'roleID'          => $role->roleID,
                                'designation'     => inicompute($designation) ? $designation->designation : 'N/A',
                                'designationID'   => $user->designationID,
                                'patientID'       => $user->patientID,
                                'photo'           => $user->photo,
                                'lang'            => $lang,
                                'varifyvaliduser' => $verifyValidUser,
                                'loggedin'        => true
                            ];

                            $browser        = $this->updatechecker->getBrowser();
                            $getPreviusData = $this->loginlog_m->get_single_loginlog([
                                'userID'  => $user->userID,
                                'roleID'  => $user->roleID,
                                'ip'      => $this->updatechecker->getUserIP(),
                                'browser' => $browser['name'],
                                'logout'  => null
                            ]);

                            if ( inicompute($getPreviusData) ) {
                                $loginLogUpdateArray = [
                                    'logout' => ( $getPreviusData->login + ( 60 * 5 ) )
                                ];
                                $this->loginlog_m->update_loginlog($loginLogUpdateArray, $getPreviusData->loginlogID);
                            }

                            $loginLog = [
                                'ip'              => $this->updatechecker->getUserIP(),
                                'browser'         => $browser['name'],
                                'operatingsystem' => $browser['platform'],
                                'login'           => strtotime(date('Ymdhis')),
                                'roleID'          => $user->roleID,
                                'userID'          => $user->userID,
                            ];

                            $this->loginlog_m->insert_loginlog($loginLog);
                            $this->session->set_userdata($sessionArray);

                            $returnArray = [ 'return' => true, 'message' => 'Success' ];
                        } else {
                            $returnArray = [ 'return' => false, 'message' => 'You are blocked' ];
                        }
                    } else {
                        $returnArray = [ 'return' => false, 'message' => 'This user role does not exist' ];
                    }
                } else {
                    $returnArray = [ 'return' => false, 'message' => 'Incorrect Signin' ];
                }
            } else {
                $captchaResponseError = ( is_array($captchaResponse['error-codes']) ) ? $captchaResponse['error-codes'][0] : $captchaResponse['error-codes'];
                $returnArray          = [ 'return' => false, 'message' => $captchaResponseError ];
            }
        } else {
            $returnArray = [ 'return' => false, 'message' => $returnArray['message'] ];
        }

        return $returnArray;
    }

    private function _updateCodeChecker()
    {
        return $this->updatechecker->verifyValidUser();
    }

    public function signout()
    {
        $browser        = $this->updatechecker->getBrowser();
        $getPreviusData = $this->loginlog_m->get_single_loginlog([ 'userID'  => $this->session->userdata('loginuserID'),
                                                                   'roleID'  => $this->session->userdata('roleID'),
                                                                   'ip'      => $this->updatechecker->getUserIP(),
                                                                   'browser' => $browser['name'],
                                                                   'logout'  => null
        ]);
        if ( inicompute($getPreviusData) ) {
            $loginLogUpdateArray = [
                'logout' => strtotime(date('Ymdhis'))
            ];
            $this->loginlog_m->update_loginlog($loginLogUpdateArray, $getPreviusData->loginlogID);
        }
        $this->session->sess_destroy();

        if ( $this->data["generalsettings"]->frontend === '1' ) {
            redirect(site_url('frontend/index'));
        } else {
            redirect(site_url("signin/index"));
        }
    }

}
