<?php 

require_once 'Mhtml2pdf.php';

class Report extends Mhtml2pdf
{
	protected $_data;
	
	public function __construct($array = []) 
	{
		parent::__construct();
		$this->_data = $array;
	}

	private function _arrayModify(&$array) 
	{
		$initialArray 	= [
			'stylesheet' 	=> NULL, 
			'data' 			=> NULL, 
			'viewpath' 		=> NULL, 
			'mode' 			=> 'view', 
			'pagesize' 		=> 'a4', 
			'pagetype' 		=> 'portrait', 
			'headerdesign'	=> 1, 
			'footerdesign'	=> 1,
			'designnone'	=> 1,
		];
		$array = array_merge($initialArray, $array);
	}

	public function reportPDF($array) 
	{
		$this->_arrayModify($array);
		extract($array);

		$designType 				  = 'LTR';
		$this->_data 				  = $data;
		$this->_data['panel_title']   = $this->CI->lang->line('panel_title');
		$this->folder('uploads/report/');
		$this->filename('Report');
		$this->paper($pagesize, $pagetype);
		$this->html($this->CI->load->view($viewpath, $this->_data, true));

		if(!empty($stylesheet)) {
			$stylesheet = file_get_contents(base_url('assets/pdf/'.$designType.'/'.$stylesheet));
		}
		return $this->create_advance($mode, $this->_data['panel_title'], $stylesheet, $this->_getHeaderDesign($headerdesign), $this->_getFooterDesign($footerdesign), $designnone);
	}

	public function reportSendToMail($array)
    {
		$this->CI->load->model('emailsettings_m');
		$this->_arrayModify($array);
		extract($array);

		$designType                 = 'LTR';
	    $rand                       = random19() . date('y-m-d h:i:s');
	    $sharand                    = hash('sha512', $rand);
	    $this->_data                = $data;
	    $this->_data['panel_title'] = $this->CI->lang->line('panel_title');

	    $this->folder('uploads/report/');
	    $this->filename($sharand);
	    $this->paper($pagesize, $pagetype);
		$this->html($this->CI->load->view($viewpath, $this->_data, true));

		if(!empty($stylesheet)) {
			$stylesheet = file_get_contents(base_url('assets/pdf/'.$designType.'/'.$stylesheet));
		}

		$this->CI->load->library('email');
		$this->CI->email->set_mailtype("html");

		$emailsetting = $this->CI->emailsettings_m->get_emailsetting();
		if(inicompute($emailsetting)) {
			if($path = @$this->create_advance('save', $this->_data['panel_title'], $stylesheet, $this->_getHeaderDesign($headerdesign), $this->_getFooterDesign($footerdesign), $designnone)) {
				if($emailsetting->email_engine == 'smtp') {
					$config = array(
					    'protocol'  => 'smtp',
					    'smtp_host' => $emailsetting->smtp_server,
					    'smtp_port' => $emailsetting->smtp_port,
					    'smtp_user' => $emailsetting->smtp_username,
					    'smtp_pass' => $emailsetting->smtp_password,
					    'mailtype'  => 'html',
					    'charset'   => 'utf-8'
					);
					$this->CI->email->initialize($config);
					$this->CI->email->set_newline("\r\n");
				}
				$fromEmail    = $data['generalsettings']->email;
				$reply_to     = $data['generalsettings']->email;
				if($this->CI->session->userdata('email') != '') {
					$reply_to = $this->CI->session->userdata('email');
				}
				$this->CI->email->from($fromEmail, $data['generalsettings']->system_name);
				$this->CI->email->to($email);
				$this->CI->email->reply_to($reply_to);
				$this->CI->email->subject($subject);
				$this->CI->email->message($message);
				$this->CI->email->attach($path);
				if($this->CI->email->send()) {
					$this->CI->session->set_flashdata('success', $this->CI->lang->line('topbar_mail_success'));
				} else {
					$this->CI->session->set_flashdata('error', $this->CI->lang->line('topbar_mail_error'));
				}
			}
		} else {
			$this->CI->session->set_flashdata('error', $this->CI->lang->line('topbar_mail_error'));
		}
	}

	private function _getHeaderDesign($headerdesign=1)
    {
		$generalsettings  = $this->_data['generalsettings'];
		$ret = '';
		if($headerdesign == 1) {
			$ret = '<div class="report-header-area"><div class="report-logo-area"><img class="report-logo-img" src="'.base_url('uploads/general/'.$generalsettings->logo).'" alt=""></div><div class="report-title-area"><h2>'.$generalsettings->system_name.'</h2><address>'.$generalsettings->address.'<br/><b>'.$this->CI->lang->line('topbar_email').':</b> '.$generalsettings->email.'<br/><b>'.$this->CI->lang->line('topbar_phone').':</b> '.$generalsettings->phone.'</address></div></div>';
		}
		return $ret;
	}

	private function _getFooterDesign($footerdesign=1)
    {
		$generalsettings  = $this->_data['generalsettings'];
		$ret = '';
		if($footerdesign == 1) {
			$ret = '<div class="report-footer-area"><img class="report-footer-img" src="'.base_url('uploads/general/'.$generalsettings->logo).'" alt=""><p class="report-footer-copyright">'.$generalsettings->footer_text.' | '.$this->CI->lang->line('topbar_hotline').'<b> : </b>'.$generalsettings->phone.'</p></div>';
		}
		return $ret;
	}
}