<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("item_m");
		$this->load->model("itemcategory_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('item', $language);
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
                'assets/inilabs/item/index.js',
            )
        );
		$this->data['items']        = $this->item_m->get_item();
		$this->data['itemcategory'] = pluck($this->itemcategory_m->get_itemcategory(), 'obj', 'itemcategoryID');
		if(permissionChecker('item_add')) {
			if($_POST) {
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'item/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['name']            = $this->input->post('name');
                    $array['categoryID']      = $this->input->post('categoryID');
                    $array['description']     = $this->input->post('description');
                    $array["create_date"]     = date("Y-m-d H:i:s");
                    $array["modify_date"]     = date("Y-m-d H:i:s");
                    $array["create_userID"]   = $this->session->userdata('loginuserID');
                    $array["create_roleID"]   = $this->session->userdata('roleID');

					$this->item_m->insert_item($array);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("item/index"));
				}
			} else {
				$this->data["subview"] = "item/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "item/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$itemID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$itemID) {
			$this->data['item'] 	= $this->item_m->get_single_item(array('itemID' => $itemID));
			if(inicompute($this->data['item'])) {
				$this->data['headerassets'] = array(
		            'css' => array(
		                'assets/select2/css/select2.css',
		                'assets/select2/css/select2-bootstrap.css',
		            ),
		            'js' => array(
		                'assets/select2/select2.js',
                        'assets/inilabs/item/index.js',
		            )
		        );
				$this->data['items'] 	    = $this->item_m->get_item();
				$this->data['itemcategory'] = pluck($this->itemcategory_m->get_itemcategory(), 'obj', 'itemcategoryID');
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
	                    $this->data["subview"] = 'item/edit';
	                    $this->load->view('_layout_main', $this->data);    
	                } else {
	                    $array = [];
	                    $array['name']          = $this->input->post('name');
	                    $array['categoryID']    = $this->input->post('categoryID');
	                    $array['description']   = $this->input->post('description');
	                    $array["modify_date"]   = date("Y-m-d H:i:s");

						$this->item_m->update_item($array, $itemID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("item/index"));
					}
				} else {
					$this->data["subview"] = "item/edit";
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
        $itemID    = $this->input->post('itemID');
        $this->data["item"]         = [];
        $this->data["itemcategory"] = [];
        if((int)$itemID) {
            $this->data["item"] = $this->item_m->get_single_item(array('itemID'=> $itemID));
            if(inicompute($this->data["item"])) {
            	$this->data["itemcategory"] = $this->itemcategory_m->get_single_itemcategory(array('itemcategoryID'=> $this->data["item"]->categoryID));
            }
        }
        $this->load->view('item/view', $this->data);
    }

	public function delete() 
	{
        $itemID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$itemID) {
        	$item = $this->item_m->get_single_item(array('itemID' => $itemID));
        	if(inicompute($item)) {
	            $this->item_m->delete_item($itemID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('item/index'));
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
				'label' => $this->lang->line("item_name"),
				'rules' => 'trim|required|max_length[40]|callback_unique_name'
			),
			array(
				'field' => 'categoryID',
				'label' => $this->lang->line("item_category"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
			),
			array(
				'field' => 'description',
				'label' => $this->lang->line("item_description"),
				'rules' => 'trim|max_length[200]'
			)
		);
		return $rules;
	}

	public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function unique_name($name)
    {
        $itemID     = htmlentities(escapeString($this->uri->segment(3)));
        $categoryID = $this->input->post('categoryID');
        if((int)$itemID){
            $item = $this->item_m->get_single_item(array("name" => $name, "categoryID"=>$categoryID, "itemID !=" => $itemID));
            if(inicompute($item)) {
                $this->form_validation->set_message("unique_name", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $item = $this->item_m->get_single_item(array("name" => $name, "categoryID"=>$categoryID));
            if(inicompute($item)) {
                $this->form_validation->set_message("unique_name", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}