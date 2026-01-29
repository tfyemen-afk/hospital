<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemsupplier extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("itemsupplier_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('itemsupplier', $language);
	}

	public function index() 
	{
	    $this->data['headerassets'] = [
	        'js' => [
	            'assets/inilabs/itemsupplier/index.js'
            ]
        ];

		$this->data['itemsuppliers'] = $this->itemsupplier_m->get_itemsupplier();
		if(permissionChecker('itemsupplier_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'itemsupplier/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = [];
                    $array['companyname']   = $this->input->post('companyname');
                    $array['suppliername']  = $this->input->post('suppliername');
                    $array['email']         = $this->input->post('email');
                    $array['phone']         = $this->input->post('phone');
                    $array['address']       = $this->input->post('address');
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');

					$this->itemsupplier_m->insert_itemsupplier($array);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("itemsupplier/index"));
				}
			} else {
				$this->data["subview"] = "itemsupplier/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "itemsupplier/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/itemsupplier/index.js'
            ]
        ];

		$itemsupplierID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$itemsupplierID) {
			$this->data['itemsupplier'] 	= $this->itemsupplier_m->get_single_itemsupplier(array('itemsupplierID' => $itemsupplierID));
			if(inicompute($this->data['itemsupplier'])) {
				$this->data['itemsuppliers'] 	= $this->itemsupplier_m->get_itemsupplier();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
	                    $this->data["subview"] = 'itemsupplier/edit';
	                    $this->load->view('_layout_main', $this->data);    
	                } else {
	                    $array = [];
	                    $array['companyname']   = $this->input->post('companyname');
	                    $array['suppliername']  = $this->input->post('suppliername');
	                    $array['email']         = $this->input->post('email');
	                    $array['phone']         = $this->input->post('phone');
	                    $array['address']       = $this->input->post('address');
	                    $array["modify_date"]   = date("Y-m-d H:i:s");

						$this->itemsupplier_m->update_itemsupplier($array, $itemsupplierID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("itemsupplier/index"));
					}
				} else {
					$this->data["subview"] = "itemsupplier/edit";
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
        $itemsupplierID    = $this->input->post('itemsupplierID');
        $this->data["itemsupplier"] = [];
        if((int)$itemsupplierID) {
            $this->data["itemsupplier"] = $this->itemsupplier_m->get_single_itemsupplier(array('itemsupplierID'=> $itemsupplierID));;
        }
        $this->load->view('itemsupplier/view', $this->data);
    }

	public function delete() 
	{
        $itemsupplierID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$itemsupplierID) {
        	$itemsupplier = $this->itemsupplier_m->get_single_itemsupplier(array('itemsupplierID' => $itemsupplierID));
        	if(inicompute($itemsupplier)) {
	            $this->itemsupplier_m->delete_itemsupplier($itemsupplierID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('itemsupplier/index'));
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
				'field' => 'companyname',
				'label' => $this->lang->line("itemsupplier_company_name"),
				'rules' => 'trim|required|max_length[128]|callback_unique_companyname'
			),
			array(
				'field' => 'suppliername',
				'label' => $this->lang->line("itemsupplier_supplier_name"),
				'rules' => 'trim|required|max_length[40]'
			),
			array(
				'field' => 'email',
				'label' => $this->lang->line("itemsupplier_email"),
				'rules' => 'trim|max_length[40]|valid_email'
			),
			array(
				'field' => 'phone',
				'label' => $this->lang->line("itemsupplier_phone"),
				'rules' => 'trim|max_length[20]'
			),
			array(
				'field' => 'address',
				'label' => $this->lang->line("itemsupplier_address"),
				'rules' => 'trim|max_length[200]'
			)
		);
		return $rules;
	}

	public function unique_companyname($companyname)
    {
        $itemsupplierID     = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$itemsupplierID){
            $itemsupplier = $this->itemsupplier_m->get_single_itemsupplier(array("companyname" => $companyname, "itemsupplierID !=" => $itemsupplierID));
            if(inicompute($itemsupplier)) {
                $this->form_validation->set_message("unique_companyname", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $itemsupplier = $this->itemsupplier_m->get_single_itemsupplier(array("companyname" => $companyname));
            if(inicompute($itemsupplier)) {
                $this->form_validation->set_message("unique_companyname", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

}