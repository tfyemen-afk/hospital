<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exceptionpage extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
	}

	public function index() 
	{
		$this->data["subview"] = "exceptionpage/index";
		$this->load->view('_layout_main', $this->data);
	}

	public function error() 
	{
		$this->data["subview"] = "exceptionpage/error";
		$this->load->view('_layout_main', $this->data);
	}
}