<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billlabel extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("billlabel_m");
		$this->load->model("billcategory_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('billlabel', $language);
	}

	protected function rules() 
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("billlabel_name"),
				'rules' => 'trim|required|max_length[128]'
			),
			array(
				'field' => 'billcategoryID',
				'label' => $this->lang->line("billlabel_bill_category"),
				'rules' => 'trim|required|callback_required_no_zero'
			),
			array(
				'field' => 'discount',
				'label' => $this->lang->line("billlabel_discount"),
				'rules' => 'trim|numeric|max_length[3]|callback_check_discount'
			),
			array(
				'field' => 'amount',
				'label' => $this->lang->line("billlabel_amount"),
				'rules' => 'trim|required|numeric|max_length[11]'
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
                'assets/inilabs/select2.js',
            )
        );

		$this->data['billlabels']    = $this->billlabel_m->get_billlabel();
		$this->billcategory_m->order('billcategoryID asc');
		$this->data['billcategorys'] = pluck($this->billcategory_m->get_select_billcategory('billcategoryID, name'), 'name', 'billcategoryID');
		if(permissionChecker('billlabel_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = "billlabel/index";
					$this->load->view('_layout_main', $this->data);
                } else {
                    $array['name']          = $this->input->post('name');
                    $array['billcategoryID']= $this->input->post('billcategoryID');
                    $array['discount']      = (empty($this->input->post('discount')) ? 0 : $this->input->post('discount'));
                    $array['amount']        = app_currency_format($this->input->post('amount'));
                    $array['create_date']   = date('Y-m-d H:i:s');
                    $array['modify_date']   = date('Y-m-d H:i:s');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_roleID'] = $this->session->userdata('roleID');

                    $this->billlabel_m->insert_billlabel($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('billlabel/index'));
                }
			} else {
				$this->data["subview"] = "billlabel/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "billlabel/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$billlabelID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$billlabelID) {
			$this->data['billlabel'] 	= $this->billlabel_m->get_single_billlabel(array('billlabelID' => $billlabelID));
			if(inicompute($this->data['billlabel'])) {
				$this->data['headerassets'] = array(
		            'css' => array(
		                'assets/select2/css/select2.css',
		                'assets/select2/css/select2-bootstrap.css',
		            ),
		            'js' => array(
		                'assets/select2/select2.js',
                        'assets/inilabs/select2.js',
		            )
		        );
				$this->data['billlabels'] 	 = $this->billlabel_m->get_billlabel();
				$this->data['billcategorys'] = pluck($this->billcategory_m->get_select_billcategory('billcategoryID, name'), 'name', 'billcategoryID');
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if($this->form_validation->run() == false) {
						$this->data["subview"] = "billlabel/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                    	$array['name']          = $this->input->post('name');
	                    $array['billcategoryID']= $this->input->post('billcategoryID');
	                    $array['discount']      = (empty($this->input->post('discount')) ? 0 : $this->input->post('discount'));
	                    $array['amount']        = app_currency_format($this->input->post('amount'));
	                    $array['modify_date']   = date('Y-m-d H:i:s');

						$this->billlabel_m->update_billlabel($array, $billlabelID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("billlabel/index"));
					}
				} else {
					$this->data["subview"] = "billlabel/edit";
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
        $billlabelID = escapeString($this->uri->segment('3'));
        if((int)$billlabelID) {
        	$billlabel = $this->billlabel_m->get_single_billlabel(array('billlabelID' => $billlabelID));
        	if(inicompute($billlabel)) {
	            $this->billlabel_m->delete_billlabel($billlabelID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('billlabel/index'));
        	} else {
	            $this->data["subview"] = '_not_found';
	            $this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function check_discount($data)
    {
    	if((int)$data) {
	    	if($data < 0 || $data > 100) {
		        $this->form_validation->set_message("check_discount", "Please Provide value between 0 to 100.");
	            return FALSE;
	    	} else {
	    		return TRUE;
	    	}
    	}
    	return TRUE;
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }
}