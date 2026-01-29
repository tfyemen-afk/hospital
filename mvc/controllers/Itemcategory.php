<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemcategory extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("itemcategory_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('itemcategory', $language);
	}

	public function index() 
	{
	    $this->data['headerassets'] = [
	        'js' => [
	            'assets/inilabs/itemcategory/index.js'
            ]
        ];

		$this->data['itemcategorys'] = $this->itemcategory_m->get_itemcategory();
		if(permissionChecker('itemcategory_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'itemcategory/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array = [];
                    $array['name']              = $this->input->post('name');
                    $array['description']       = $this->input->post('description');
                    $array["create_date"]       = date("Y-m-d H:i:s");
                    $array["modify_date"]       = date("Y-m-d H:i:s");
                    $array["create_userID"]     = $this->session->userdata('loginuserID');
                    $array["create_roleID"]     = $this->session->userdata('roleID');

					$this->itemcategory_m->insert_itemcategory($array);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("itemcategory/index"));
				}
			} else {
				$this->data["subview"] = "itemcategory/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "itemcategory/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/itemcategory/index.js'
            ]
        ];

		$itemcategoryID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$itemcategoryID) {
			$this->data['itemcategory'] 	= $this->itemcategory_m->get_single_itemcategory(array('itemcategoryID' => $itemcategoryID));
			if(inicompute($this->data['itemcategory'])) {
				$this->data['itemcategorys'] 	= $this->itemcategory_m->get_itemcategory();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
	                    $this->data["subview"] = 'itemcategory/edit';
	                    $this->load->view('_layout_main', $this->data);    
	                } else {
	                    $array['name']              = $this->input->post('name');
	                    $array['description']       = $this->input->post('description');
	                    $array["modify_date"]       = date("Y-m-d H:i:s");

						$this->itemcategory_m->update_itemcategory($array, $itemcategoryID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("itemcategory/index"));
					}
				} else {
					$this->data["subview"] = "itemcategory/edit";
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
        $itemcategoryID    = $this->input->post('itemcategoryID');
        $this->data["itemcategory"] = [];
        if((int)$itemcategoryID) {
            $this->data["itemcategory"] = $this->itemcategory_m->get_single_itemcategory(array('itemcategoryID'=> $itemcategoryID));;
        }
        $this->load->view('itemcategory/view', $this->data);
    }

	public function delete() 
	{
        $itemcategoryID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$itemcategoryID) {
        	$itemcategory = $this->itemcategory_m->get_single_itemcategory(array('itemcategoryID' => $itemcategoryID));
        	if(inicompute($itemcategory)) {
	            $this->itemcategory_m->delete_itemcategory($itemcategoryID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('itemcategory/index'));
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
				'label' => $this->lang->line("itemcategory_name"),
				'rules' => 'trim|required|max_length[40]|callback_unique_name'
			),
			array(
				'field' => 'description',
				'label' => $this->lang->line("itemcategory_description"),
				'rules' => 'trim|max_length[200]'
			)
		);
		return $rules;
	}

	public function unique_name($name)
    {
        $itemcategoryID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$itemcategoryID){
            $itemcategory = $this->itemcategory_m->get_single_itemcategory(array("name" => $name, "itemcategoryID !=" => $itemcategoryID));
            if(inicompute($itemcategory)) {
                $this->form_validation->set_message("unique_name", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $itemcategory = $this->itemcategory_m->get_single_itemcategory(array("name" => $name));
            if(inicompute($itemcategory)) {
                $this->form_validation->set_message("unique_name", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}