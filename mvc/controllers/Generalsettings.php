<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generalsettings extends Admin_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('generalsettings_m');
        $this->load->helper('frontenddata');

        $language = $this->session->userdata('lang');
        $this->lang->load('generalsettings', $language);
    }

    private function rules() 
    {
        $rules = array(
            array(
                'field' => 'system_name',
                'label' => $this->lang->line("generalsettings_system_name"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line("generalsettings_address"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("generalsettings_phone"),
                'rules' => 'trim|required|max_length[40]|numeric'
            ), 
            array(
                'field' => 'email',
                'label' => $this->lang->line("generalsettings_email"),
                'rules' => 'trim|required|max_length[40]|valid_email'
            ),
            array(
                'field' => 'currency_code',
                'label' => $this->lang->line("generalsettings_currency_code"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'currency_symbol',
                'label' => $this->lang->line("generalsettings_currency_symbol"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'frontend',
                'label' => $this->lang->line("generalsettings_frontend"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'time_zone',
                'label' => $this->lang->line("generalsettings_timezone"),
                'rules' => 'trim|required|callback_unique_time_zone'
            ),
            array(
                'field' => 'logo',
                'label' => $this->lang->line("generalsettings_logo"),
                'rules' => 'trim|max_length[40]|callback_logo_upload'
            ),
            array(
                'field' => 'auto_update_notification',
                'label' => $this->lang->line("generalsettings_autoupdatenotification"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'captcha_status',
                'label' => $this->lang->line("generalsettings_captcha"),
                'rules' => 'trim|required'
            ),
            array(
                'field' => 'footer_text',
                'label' => $this->lang->line("generalsettings_footer_text"),
                'rules' => 'trim|required|max_length[100]'
            ),
            array(
                'field' => 'patient_password',
                'label' => $this->lang->line("generalsettings_password"),
                'rules' => 'trim|required|max_length[40]'
            ),
            array(
                'field' => 'patient_credit_limit',
                'label' => $this->lang->line("generalsettings_credit_limit"),
                'rules' => 'trim|required|numeric|max_length[40]'
            ), 
            array(
                'field' => 'medicine_expire_limit_day',
                'label' => $this->lang->line("generalsettings_medicine_expire_limit_day"),
                'rules' => 'trim|required|numeric'
            ) 
        );

        if($this->input->post('captcha_status') == '1') {
            $rules[] = array(
                'field' => 'recaptcha_site_key',
                'label' => $this->lang->line("generalsettings_recaptcha_site_key"),
                'rules' => 'trim|required|max_length[255]'
            );

            $rules[] = array(
                'field' => 'recaptcha_secret_key',
                'label' => $this->lang->line("generalsettings_recaptcha_secret_key"),
                'rules' => 'trim|required|max_length[255]'
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
                'assets/inilabs/generalsettings/index.js'
            )
        );

        $this->data['generalsettings'] = $this->generalsettings_m->get_generalsettings();
        $this->data['captchastatus'] = $this->data['generalsettings']->captcha_status;
        $this->data['jsmanager'] = ['captchastatus' => $this->data['captchastatus']];
        if($_POST) {
            $this->data['captchastatus'] = $this->input->post('captcha_status');
            $this->data['jsmanager'] = ['captchastatus' => $this->data['captchastatus']];
            $rules  = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = 'generalsettings/index';
                $this->load->view('_layout_main', $this->data);    
            } else {
                $array = [];
                if(inicompute($rules)) {
                    foreach($rules as $rule) {
                        $array[$rule['field']] = $this->input->post($rule['field']);
                    }
                }

                $array['logo'] = $this->upload_data['logo']['file_name'];

                if(isset($array['frontend']) && $array['frontend']) {
                    @$this->_frontendSetUp();
                } else {
                    @$this->_backendSetUp();
                }

                $this->generalsettings_m->insertorupdate($array);
                frontenddata::get_backend_delete();
                frontenddata::get_backend();

                $this->session->set_flashdata('success','Success');
                redirect(site_url('generalsettings/index'));
            }
        } else {
            $this->data["subview"] = 'generalsettings/index';
            $this->load->view('_layout_main', $this->data);      
        }
	}

    public function unique_time_zone()
    {
        $timezone = $this->input->post('time_zone');
        if($timezone == 'none') {
            $this->form_validation->set_message('unique_time_zone', 'The %s field is required.');
            return FALSE;
        } else {
            if($this->data['generalsettings']->time_zone != $this->input->post('time_zone')) {
                $timeZone = $this->input->post('time_zone');
                $indexPath = getcwd()."/index.php";
                @chmod($indexPath, 0777);
                $filecontent = "date_default_timezone_set('". $timeZone ."');";
                $fileArray = array(2 => $filecontent);

                $this->replace_lines($indexPath, $fileArray);
                @chmod($indexPath, 0644);
            }
            return TRUE;
        }
        return TRUE;
    }

    private function replace_lines($file, $new_lines, $source_file = NULL) 
    {
        $response = 0;
        $tab = chr(9);
        $lbreak = chr(13) . chr(10);
        if ($source_file) {
            $lines = file($source_file);
        }
        else {
            $lines = file($file);
        }
        foreach ($new_lines as $key => $value) {
            $lines[--$key] = $tab . $value . $lbreak;
        }
        $new_content = implode('', $lines);
        if ($h = fopen($file, 'w')) {
            if (fwrite($h, $new_content)) {
                $response = 1;
            }
            fclose($h);
        }
        return $response;
    }

    public function logo_upload() 
    {
        if($_FILES["logo"]['name'] != "") {
            $file_name = $_FILES["logo"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random . config_item("encryption_key"));
            $file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/general";
                $config['allowed_types'] = "gif|jpg|png|jpeg";
                $config['file_name'] = $new_file;
                $config['max_size'] = '500';
                $config['max_width'] = '1000';
                $config['max_height'] = '1000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("logo")) {
                    $this->form_validation->set_message("logo_upload", $this->upload->display_errors());
                    return FALSE;
                } else {
                    $this->upload_data['logo'] = $this->upload->data();
                    return TRUE;
                }
            } else {
                $this->form_validation->set_message("logo_upload", "Invalid file");
                return FALSE;
            }
        } else {
            $this->upload_data['logo'] = array('file_name' => $this->data['generalsettings']->logo);
            return TRUE;
        }
    }

    private function _frontendSetUp()
    {
        $file = APPPATH.'config/routes.php';
        $file = fopen($file, "r");
        while (!feof($file)) {
            $string = trim(preg_replace('/\s+/', '', fgets($file)));
            $mypattern[] = trim(preg_replace('/\s+/', '', '$route[\'default_controller\'] = \'signin/index\';'));
            $mypattern[] = trim(preg_replace('/\s+/', '', '$route[\'default_controller\'] = \'Signin/Index\';'));
            $mypattern[] = trim(preg_replace('/\s+/', '', '$route[\'default_controller\'] = \'Signin/index\';'));
            $mypattern[] = trim(preg_replace('/\s+/', '', '$route[\'default_controller\'] = \'signin/Index\';'));
            foreach ($mypattern as $pattern) {
                if($pattern == $string) {
                    $routesFile = APPPATH.'config/routes.php';
                    $config_file = file_get_contents($routesFile);
                    $config_file = trim($config_file);
                    $pattern = '/\$route\[\\\''.'default_controller'.'\\\'\]\s+=\s+[^\;]+/';
                    $replace = "\$route['default_controller'] = 'frontend/index'";
                    $config_file = preg_replace($pattern, $replace, $config_file);
                    $fp = fopen($routesFile, FOPEN_WRITE_CREATE_DESTRUCTIVE);

                    flock($fp, LOCK_EX);
                    fwrite($fp, $config_file, strlen($config_file));
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
            }
        }
        fclose($file);
    }

    private function _backendSetUp()
    {
        $file = APPPATH.'config/routes.php';

        $file = fopen($file, "r");
        while (!feof($file)) {
            $string = trim(preg_replace('/\s+/', '', fgets($file)));
            $mypattern[] = trim(preg_replace('/\s+/', '', '$route[\'default_controller\'] = \'frontend/index\';'));
            $mypattern[] = trim(preg_replace('/\s+/', '', '$route[\'default_controller\'] = \'Frontend/Index\';'));
            $mypattern[] = trim(preg_replace('/\s+/', '', '$route[\'default_controller\'] = \'Frontend/index\';'));
            $mypattern[] = trim(preg_replace('/\s+/', '', '$route[\'default_controller\'] = \'frontend/Index\';'));
            foreach ($mypattern as $pattern) {
                if($pattern == $string) {
                    $routesFile = APPPATH.'config/routes.php';
                    $config_file = file_get_contents($routesFile);
                    $config_file = trim($config_file);
                    $pattern = '/\$route\[\\\''.'default_controller'.'\\\'\]\s+=\s+[^\;]+/';
                    $replace = "\$route['default_controller'] = 'signin/index'";
                    $config_file = preg_replace($pattern, $replace, $config_file);
                    $fp = fopen($routesFile, FOPEN_WRITE_CREATE_DESTRUCTIVE);

                    flock($fp, LOCK_EX);
                    fwrite($fp, $config_file, strlen($config_file));
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
            }
        }
        fclose($file);
    }
}
