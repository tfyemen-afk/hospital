<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemstore extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("itemstore_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('itemstore', $language);
	}

	public function index() 
	{
	    $this->data['headerassets'] = [
	        'js' => [
	            'assets/inilabs/itemstore/index.js'
            ]
        ];

		$this->data['itemstores'] = $this->itemstore_m->get_itemstore();
		if(permissionChecker('itemstore_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'itemstore/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['name']          = $this->input->post('name');
                    $array['code']          = $this->input->post('code');
                    $array['incharge']      = $this->input->post('incharge');
                    $array['email']         = $this->input->post('email');
                    $array['phone']         = $this->input->post('phone');
                    $array['location']      = $this->input->post('location');
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');

					$this->itemstore_m->insert_itemstore($array);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("itemstore/index"));
				}
			} else {
				$this->data["subview"] = "itemstore/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "itemstore/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/itemstore/index.js'
            ]
        ];

		$itemstoreID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$itemstoreID) {
			$this->data['itemstore'] 	= $this->itemstore_m->get_single_itemstore(array('itemstoreID' => $itemstoreID));
			if(inicompute($this->data['itemstore'])) {
				$this->data['itemstores'] 	= $this->itemstore_m->get_itemstore();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
	                    $this->data["subview"] = 'itemstore/edit';
	                    $this->load->view('_layout_main', $this->data);    
	                } else {
	                    $array['name']          = $this->input->post('name');
	                    $array['code']          = $this->input->post('code');
	                    $array['incharge']      = $this->input->post('incharge');
	                    $array['email']         = $this->input->post('email');
	                    $array['phone']         = $this->input->post('phone');
	                    $array['location']      = $this->input->post('location');
	                    $array["modify_date"]   = date("Y-m-d H:i:s");

						$this->itemstore_m->update_itemstore($array, $itemstoreID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("itemstore/index"));
					}
				} else {
					$this->data["subview"] = "itemstore/edit";
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

	public function view()
    {
        $itemstoreID    = $this->input->post('itemstoreID');
        $this->data["itemstore"] = [];
        if((int)$itemstoreID) {
            $this->data["itemstore"] = $this->itemstore_m->get_single_itemstore(array('itemstoreID'=> $itemstoreID));;
        }
        $this->load->view('itemstore/view', $this->data);
    }

	public function delete() 
	{
        $itemstoreID = escapeString($this->uri->segment('3'));
        if((int)$itemstoreID) {
        	$itemstore = $this->itemstore_m->get_single_itemstore(array('itemstoreID' => $itemstoreID));
        	if(inicompute($itemstore)) {
	            $this->itemstore_m->delete_itemstore($itemstoreID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('itemstore/index'));
        	} else {
	            $this->data["subview"] = '_not_found';
	            $this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

	protected function rules() 
	{
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("itemstore_name"),
				'rules' => 'trim|required|max_length[40]|callback_unique_name'
			),
			array(
				'field' => 'code',
				'label' => $this->lang->line("itemstore_code"),
				'rules' => 'trim|required|max_length[40]|callback_unique_code'
			),
			array(
				'field' => 'incharge',
				'label' => $this->lang->line("itemstore_in_charge"),
				'rules' => 'trim|required|max_length[40]'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("itemstore_email"),
				'rules' => 'trim|max_length[40]|valid_email|callback_unique_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("itemstore_phone"),
				'rules' => 'trim|max_length[25]'
			),
			array(
				'field' => 'location',
				'label' => $this->lang->line("itemstore_location"),
				'rules' => 'trim|max_length[200]'
			)
		);
		return $rules;
	}

	public function unique_name($name)
    {
        $itemstoreID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$itemstoreID){
            $itemstore = $this->itemstore_m->get_single_itemstore(array("name" => $name, "itemstoreID !=" => $itemstoreID));
            if(inicompute($itemstore)) {
                $this->form_validation->set_message("unique_name", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $itemstore = $this->itemstore_m->get_single_itemstore(array("name" => $name));
            if(inicompute($itemstore)) {
                $this->form_validation->set_message("unique_name", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

	public function unique_code($code)
    {
        $itemstoreID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$itemstoreID){
            $itemstore = $this->itemstore_m->get_single_itemstore(array("code" => $code, "itemstoreID !=" => $itemstoreID));
            if(inicompute($itemstore)) {
                $this->form_validation->set_message("unique_code", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $itemstore = $this->itemstore_m->get_single_itemstore(array("code" => $code));
            if(inicompute($itemstore)) {
                $this->form_validation->set_message("unique_code", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

	public function unique_email($email)
    {
    	if($email) {
	        $itemstoreID = htmlentities(escapeString($this->uri->segment(3)));
	        if((int)$itemstoreID){
	            $itemstore = $this->itemstore_m->get_single_itemstore(array("email" => $email, "itemstoreID !=" => $itemstoreID));
	            if(inicompute($itemstore)) {
	                $this->form_validation->set_message("unique_email", "This %s already exists.");
	                return FALSE;
	            }
	            return TRUE;
	        } else {
	            $itemstore = $this->itemstore_m->get_single_itemstore(array("email" => $email));
	            if(inicompute($itemstore)) {
	                $this->form_validation->set_message("unique_email", "This %s already exists.");
	                return FALSE;
	            }
	            return TRUE;
	        }
    	}
	    return TRUE;
    }
}