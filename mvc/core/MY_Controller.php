<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{
    public $data = array();

    public function __construct() 
    {
        parent::__construct();
        $this->load->config('iniconfig');
        $this->load->helper('url');
        $this->data['errors'] = array();

        if(!$this->config->config_install()) {
            redirect(site_url('install/index'));
        }
    }
}

