<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Income extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("income_m");
		$this->load->model("user_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('income', $language);
	}

	protected function rules() 
	{
		$rules = array(
			array(
				'field' => 'income_name',
				'label' => $this->lang->line("income_name"),
				'rules' => 'trim|required|max_length[128]'
			),
			array(
				'field' => 'income_date',
				'label' => $this->lang->line("income_date"),
				'rules' => 'trim|required|max_length[10]|callback_valid_date'
			),
			array(
				'field' => 'income_amount',
				'label' => $this->lang->line("income_amount"),
				'rules' => 'trim|required|numeric|max_length[10]|callback_valid_number'
			),
			array(
				'field' => 'income_file',
				'label' => $this->lang->line("income_file"),
				'rules' => 'trim|max_length[200]|callback_fileupload'
			),
			array(
				'field' => 'income_note',
				'label' => $this->lang->line("income_note"),
				'rules' => 'trim|max_length[128]'
			)
		);
		return $rules;
	}

	public function index() 
	{
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
            ),
            'js' => array(
                'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                'assets/inilabs/income/index.js'
            )
        );

		$this->data['incomes'] = $this->income_m->get_income();
		$this->data['users']   = pluck($this->user_m->get_select_user('userID, name', array('roleID !=' => 3)),'name','userID');
		if(permissionChecker('income_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "income/index";
					$this->load->view('_layout_main', $this->data);
				} else {
					$array = array(
						"name" 			=> $this->input->post('income_name'),
						"date" 			=> date("Y-m-d", strtotime($this->input->post("income_date"))),
						"incomeday"		=> date("d", strtotime($this->input->post("income_date"))),
						"incomemonth"	=> date("m", strtotime($this->input->post("income_date"))),
						"incomeyear"	=> date("Y", strtotime($this->input->post("income_date"))),
						"amount" 		=> $this->input->post('income_amount'),
						"file" 			=> $this->upload_data['income_file']['file_name'],
						"note" 			=> $this->input->post('income_note'),
						"create_date" 	=> date('Y-m-d H:i:s'),
						"modify_date" 	=> date('Y-m-d H:i:s'),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_roleID" => $this->session->userdata('roleID')
					);

					$this->income_m->insert_income($array);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("income/index"));
				}
			} else {
				$this->data["subview"] = "income/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "income/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit()
    {
		$incomeID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$incomeID) {
			$this->data['income']      = $this->income_m->get_single_income(array('incomeID' => $incomeID));
			if(inicompute($this->data['income'])) {
				$this->data['headerassets'] = array(
		            'css' => array(
		                'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
		            ),
		            'js' => array(
		                'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                        'assets/inilabs/income/index.js'
		            )
		        );
				$this->data['incomes'] = $this->income_m->get_income();
                $this->data['users']   = pluck($this->user_m->get_select_user('userID, name', array('roleID !=' => 3)),'name','userID');
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "income/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array(
							"name" 			=> $this->input->post('income_name'),
							"date" 			=> date("Y-m-d", strtotime($this->input->post("income_date"))),
							"incomeday"		=> date("d", strtotime($this->input->post("income_date"))),
							"incomemonth"	=> date("m", strtotime($this->input->post("income_date"))),
							"incomeyear"	=> date("Y", strtotime($this->input->post("income_date"))),
							"amount" 		=> $this->input->post('income_amount'),
							"file" 			=> $this->upload_data['income_file']['file_name'],
							"note" 			=> $this->input->post('income_note'),
							"modify_date" 	=> date("Y-m-d H:i:s"),
						);

						$this->income_m->update_income($array, $incomeID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("income/index"));
					}
				} else {
					$this->data["subview"] = "income/edit";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "_not_found";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "_not_found";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function delete() 
	{
        $incomeID = escapeString($this->uri->segment('3'));
        if((int)$incomeID) {
        	$income     = $this->income_m->get_single_income(array('incomeID' => $incomeID));
        	if(inicompute($income)) {
        		if(config_item('demo') == FALSE) {
                    if(file_exists(FCPATH.'uploads/files/'.$income->file)) {
                        unlink(FCPATH.'uploads/files/'.$income->file);
                    }
                }
	            $this->income_m->delete_income($incomeID);
	            $this->session->set_flashdata('success','Success');
	            redirect(site_url('income/index'));
        	} else {
        		$this->data["subview"] = '_not_found';
            	$this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function download() 
    {
    	if(permissionChecker('income')) {
			$incomeID = htmlentities(escapeString($this->uri->segment(3)));
			if((int)$incomeID) {
				$income = $this->income_m->get_single_income(array('incomeID' => $incomeID));
				if(inicompute($income)) {
					$fileName = $income->file;
					$expFileName = explode('.', $fileName);
					$originalname = $income->name.'.'.$expFileName[1];
					$file = realpath('uploads/files/'.$income->file);
				    if (file_exists($file)) {
				    	header('Content-Description: File Transfer');
					    header('Content-Type: application/octet-stream');
					    header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
					    header('Expires: 0');
					    header('Cache-Control: must-revalidate');
					    header('Pragma: public');
					    header('Content-Length: ' . filesize($file));
					    readfile($file);
					    exit;
				    } else {
				    	redirect(site_url('income/index'));
				    }
				} else {
				   	redirect(site_url('income/index'));
				}
			} else {
				redirect(site_url('income/index'));
			}
		} else {
			redirect(site_url('income/index'));
		}
	}

	public function valid_number($data) 
	{
		if($data <= 0) {
			$this->form_validation->set_message("valid_number", "The %s is invalid number");
			return FALSE;
		}
		return TRUE;
	}

    public function valid_date($date) 
    {
        if($date) {
            if(strlen($date) < 10) {
                $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                return FALSE;
            } else {
                $arr = explode("-", $date);
                if(inicompute($arr) == 3) {
                    $dd = $arr[0];
                    $mm = $arr[1];
                    $yyyy = $arr[2];
                    if(checkdate($mm, $dd, $yyyy)) {
                        return TRUE;
                    } else {
                        $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                        return FALSE;
                    }
                } else {
                    $this->form_validation->set_message("valid_date", "The %s is not valid dd-mm-yyyy");
                    return FALSE;
                }
            }
        } else {
            return TRUE;
        }
    }

	public function fileupload() 
	{
		$incomeID = htmlentities(escapeString($this->uri->segment(3)));
		$income = array();
		if((int)$incomeID) {
			$income = $this->income_m->get_single_income(array('incomeID' => $incomeID));
		}

		$new_file = "";
		if($_FILES["income_file"]['name'] !="") {
			$file_name = $_FILES["income_file"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.$this->input->post('name') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/files";
				$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
				$config['file_name']   = $new_file;
				$config['max_size']    = '5120';
				$config['max_width']   = '3000';
				$config['max_height']  = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("income_file")) {
					$this->form_validation->set_message("fileupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['income_file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("fileupload", "Invalid file");
	     		return FALSE;
			}
		} else {
			if(inicompute($income)) {
				$this->upload_data['income_file'] = array('file_name' => $income->file);
				return TRUE;
			} else {
				$this->upload_data['income_file'] = array('file_name' => $new_file);
				return TRUE;
			}
		}
	}
}