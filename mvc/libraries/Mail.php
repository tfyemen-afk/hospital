<?php 

class Mail {
	protected $CI;
	protected $data;
	
	public function __construct($array = [])
    {
		$this->CI = & get_instance();
	}

	public function sendmail($data= NULL, $email= NULL, $subject= NULL, $message= NULL)
    {
		$this->CI->load->model('emailsettings_m');
		$this->CI->load->library('email');
		$emailsetting = $this->CI->emailsettings_m->get_emailsetting();
		$this->CI->email->set_mailtype("html");

		if(inicompute($emailsetting)) {
			if($emailsetting->email_engine == 'smtp') {
				$config = array(
				    'protocol'     => 'smtp',
				    'smtp_host'    => $emailsetting->smtp_server,
				    'smtp_port'    => $emailsetting->smtp_port,
				    'smtp_user'    => $emailsetting->smtp_username,
				    'smtp_pass'    => $emailsetting->smtp_password,
				    'smtp_timeout' => 20,
				    'mailtype'     => 'html',
				    'charset'      => 'utf-8',
				    'wordwrap'      => TRUE,
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
			$this->CI->email->send();
		} else {
			$this->CI->session->set_flashdata('error', $this->CI->lang->line('topbar_mail_error'));
		}
	}
}