<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Expense extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
		$this->load->model("expense_m");
		$this->load->model("user_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('expense', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'expense_name',
				'label' => $this->lang->line("expense_name"),
				'rules' => 'trim|required|max_length[128]'
			),
			array(
				'field' => 'expense_date',
				'label' => $this->lang->line("expense_date"),
				'rules' => 'trim|required|max_length[10]|callback_valid_date'
			),
			array(
				'field' => 'expense_amount',
				'label' => $this->lang->line("expense_amount"),
				'rules' => 'trim|required|numeric|max_length[10]|callback_valid_number'
			),
			array(
				'field' => 'expense_file',
				'label' => $this->lang->line("expense_file"),
				'rules' => 'trim|max_length[200]|callback_fileupload'
			),
			array(
				'field' => 'expense_note',
				'label' => $this->lang->line("expense_note"),
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
                'assets/inilabs/expense/index.js'
            )
        );

		$this->data['expenses'] = $this->expense_m->get_expense();
        $this->data['users']    = pluck($this->user_m->get_select_user('userID, name', array('roleID !=' => 3)), 'name', 'userID');
		if(permissionChecker('expense_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$this->data["subview"] = "expense/index";
					$this->load->view('_layout_main', $this->data);
				} else {
					$array = array(
						"name" 			=> $this->input->post('expense_name'),
						"date" 			=> date("Y-m-d", strtotime($this->input->post("expense_date"))),
						"expenseday"    => date("d", strtotime($this->input->post("expense_date"))),
						"expensemonth"	=> date("m", strtotime($this->input->post("expense_date"))),
						"expenseyear"	=> date("Y", strtotime($this->input->post("expense_date"))),
						"amount" 		=> $this->input->post('expense_amount'),
						"file" 			=> $this->upload_data['expense_file']['file_name'],
						"note" 			=> $this->input->post('expense_note'),
						"create_date" 	=> date('Y-m-d H:i:s'),
						"modify_date" 	=> date('Y-m-d H:i:s'),
						"create_userID" => $this->session->userdata('loginuserID'),
						"create_roleID" => $this->session->userdata('roleID')
					);

					$this->expense_m->insert_expense($array);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("expense/index"));
				}
			} else {
				$this->data["subview"] = "expense/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "expense/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit()
    {
		$expenseID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$expenseID) {
			$this->data['expense']  = $this->expense_m->get_single_expense(array('expenseID' => $expenseID));
			if(inicompute($this->data['expense'])) {
				$this->data['headerassets'] = array(
		            'css' => array(
		                'assets/datepicker/dist/css/bootstrap-datepicker.min.css'
		            ),
		            'js' => array(
		                'assets/datepicker/dist/js/bootstrap-datepicker.min.js',
                        'assets/inilabs/expense/index.js'
		            )
		        );
				$this->data['expenses'] = $this->expense_m->get_expense();
                $this->data['users']    = pluck($this->user_m->get_select_user('userID, name', array('roleID !=' => 3)), 'name', 'userID');
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "expense/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = array(
							"name" 			=> $this->input->post('expense_name'),
							"date" 			=> date("Y-m-d", strtotime($this->input->post("expense_date"))),
							"expenseday"	=> date("d", strtotime($this->input->post("expense_date"))),
							"expensemonth"	=> date("m", strtotime($this->input->post("expense_date"))),
							"expenseyear"	=> date("Y", strtotime($this->input->post("expense_date"))),
							"amount" 		=> $this->input->post('expense_amount'),
							"file" 			=> $this->upload_data['expense_file']['file_name'],
							"note" 			=> $this->input->post('expense_note'),
							"modify_date" 	=> date("Y-m-d H:i:s"),
						);
						$this->expense_m->update_expense($array, $expenseID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("expense/index"));
					}
				} else {
					$this->data["subview"] = "expense/edit";
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
        $expenseID = escapeString($this->uri->segment('3'));
        if((int)$expenseID) {
			$expense  = $this->expense_m->get_single_expense(array('expenseID' => $expenseID));
			if(inicompute($expense)) {
				if(config_item('demo') == FALSE) {
                    if(file_exists(FCPATH.'uploads/files/'.$expense->file)) {
                        unlink(FCPATH.'uploads/files/'.$expense->file);
                    }
                }
	            $this->expense_m->delete_expense($expenseID);
	            $this->session->set_flashdata('success','Success');
	            redirect(site_url('expense/index'));
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
		$expenseID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$expenseID) {
			$expense = $this->expense_m->get_single_expense(array('expenseID' => $expenseID));
			if(inicompute($expense)) {
				$fileName = $expense->file;
				$expFileName = explode('.', $fileName);
				$originalname = $expense->name.'.'.$expFileName[1];
				$file = realpath('uploads/files/'.$expense->file);
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
			    	redirect(site_url('expense/index'));
			    }
			} else {
			   	redirect(site_url('expense/index'));
			}
		} else {
			redirect(site_url('expense/index'));
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
		$expenseID = htmlentities(escapeString($this->uri->segment(3)));
		$expense = array();
		if((int)$expenseID) {
			$expense = $this->expense_m->get_single_expense(array('expenseID' => $expenseID));
		}

		$new_file = "";
		if($_FILES["expense_file"]['name'] !="") {
			$file_name = $_FILES["expense_file"]['name'];
			$random = random19();
	    	$makeRandom = hash('sha512', $random.$this->input->post('name') . config_item("encryption_key"));
			$file_name_rename = $makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
	            $new_file = $file_name_rename.'.'.end($explode);
				$config['upload_path'] = "./uploads/files";
				$config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
				$config['file_name'] = $new_file;
				$config['max_size'] = '5120';
				$config['max_width'] = '3000';
				$config['max_height'] = '3000';
				$this->load->library('upload', $config);
				if(!$this->upload->do_upload("expense_file")) {
					$this->form_validation->set_message("fileupload", $this->upload->display_errors());
	     			return FALSE;
				} else {
					$this->upload_data['expense_file'] =  $this->upload->data();
					return TRUE;
				}
			} else {
				$this->form_validation->set_message("fileupload", "Invalid file");
	     		return FALSE;
			}
		} else {
			if(inicompute($expense)) {
				$this->upload_data['expense_file'] = array('file_name' => $expense->file);
				return TRUE;
			} else {
				$this->upload_data['expense_file'] = array('file_name' => $new_file);
			    return TRUE;
			}
		}
	}
}