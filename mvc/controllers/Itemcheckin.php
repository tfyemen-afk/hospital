<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemcheckin extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("itemcheckin_m");
		$this->load->model("itemsupplier_m");
		$this->load->model("item_m");
		$this->load->model("itemstore_m");
		$this->load->model("itemcategory_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('itemcheckin', $language);
	}

	public function index() 
	{
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
                'assets/datetimepicker/css/datetimepicker.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/datetimepicker/js/datetimepicker.js',
                'assets/inilabs/itemcheckin/index.js',
            )
        );

        $this->data['itemsetter']       = 0;
		$this->data['itemcheckins']     = $this->itemcheckin_m->get_itemcheckin();
		$this->data['itemcategory']     = $this->itemcategory_m->get_itemcategory();
		$this->data['items']            = pluck($this->item_m->get_select_item('itemID, name'), 'obj', 'itemID');
		$this->data['itemsuppliers']    = pluck($this->itemsupplier_m->get_select_itemsupplier('itemsupplierID, companyname'), 'obj', 'itemsupplierID');
		$this->data['itemstores']       = $this->itemstore_m->get_select_itemstore('itemstoreID, name');

		if($this->input->post('categoryID')) {
            $queryArray = ['categoryID' => $this->input->post('categoryID')];
            $this->data['itempotions'] = $this->item_m->get_select_item('itemID, name', $queryArray);
        } else {
            $this->data['itempotions'] = $this->data['items'];
        }

		if(permissionChecker('itemcheckin_add')) {

			if($_POST) {
			    $this->data['itemsetter'] = $this->input->post('itemID');
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'itemcheckin/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['itemID']        = $this->input->post('itemID');
                    $array['supplierID']    = $this->input->post('supplierID');
                    $array['storeID']       = $this->input->post('storeID');
                    $array['quantity']      = $this->input->post('quantity');
                    $array['date']          = date("Y-m-d H:i:s", strtotime($this->input->post('date')));
                    $array['description']   = $this->input->post('description');
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');

					$this->itemcheckin_m->insert_itemcheckin($array);
					$this->session->set_flashdata('success','Success');
					redirect(site_url("itemcheckin/index"));
				}
			} else {
				$this->data["subview"] = "itemcheckin/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "itemcheckin/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$itemcheckinID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$itemcheckinID) {
			$this->data['itemcheckin'] 	= $this->itemcheckin_m->get_single_itemcheckin(array('itemcheckinID' => $itemcheckinID));
			if(inicompute($this->data['itemcheckin'])) {
				$this->data['headerassets'] = array(
		            'css' => array(
		                'assets/select2/css/select2.css',
		                'assets/select2/css/select2-bootstrap.css',
		                'assets/datetimepicker/css/datetimepicker.css',
		            ),
		            'js' => array(
		                'assets/select2/select2.js',
		                'assets/datetimepicker/js/datetimepicker.js',
                        'assets/inilabs/itemcheckin/index.js',
		            )
		        );

				$this->data['itemcheckins']  = $this->itemcheckin_m->get_itemcheckin();
				$this->data['itemcategory']  = $this->itemcategory_m->get_itemcategory();
				$this->data['items']         = pluck($this->item_m->get_select_item('itemID, name'), 'obj', 'itemID');
				$this->data['itemsuppliers'] = pluck($this->itemsupplier_m->get_select_itemsupplier('itemsupplierID, companyname'), 'obj', 'itemsupplierID');
				$this->data['itemstores']    = $this->itemstore_m->get_select_itemstore('itemstoreID, name');

                if($this->input->post('categoryID')) {
                    $queryArray = ['categoryID' => $this->input->post('categoryID')];
                    $this->data['itempotions'] = $this->item_m->get_select_item('itemID, name', $queryArray);
                } else {
                    $this->data['itempotions'] = $this->data['items'];
                }

				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
	                    $this->data["subview"] = 'itemcheckin/edit';
	                    $this->load->view('_layout_main', $this->data);    
	                } else {
	                    $array['itemID']      = $this->input->post('itemID');
	                    $array['supplierID']  = $this->input->post('supplierID');
	                    $array['storeID']     = $this->input->post('storeID');
	                    $array['quantity']    = $this->input->post('quantity');
	                    $array['date']        = date("Y-m-d H:i:s", strtotime($this->input->post('date')));
	                    $array['description'] = $this->input->post('description');
	                    $array["modify_date"] = date("Y-m-d H:i:s");

						$this->itemcheckin_m->update_itemcheckin($array, $itemcheckinID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("itemcheckin/index"));
					}
				} else {
					$this->data["subview"] = "itemcheckin/edit";
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
        $itemcheckinID              = $this->input->post('itemcheckinID');
        $this->data["itemcheckin"]  = [];
		$this->data['item']         = [];
		$this->data['itemsupplier'] = [];
		$this->data['itemstore']    = [];

        if((int)$itemcheckinID) {
            $itemcheckin = $this->itemcheckin_m->get_single_itemcheckin(array('itemcheckinID'=> $itemcheckinID));
            if(inicompute($itemcheckin)) {
            	$this->data["itemcheckin"]  = $itemcheckin;
            	$this->data['item']         = $this->item_m->get_select_item('itemID,name', array('itemID'=>$itemcheckin->itemID), TRUE);
				$this->data['itemsupplier'] = $this->itemsupplier_m->get_select_itemsupplier('itemsupplierID,companyname', array('itemsupplierID'=>$itemcheckin->supplierID), TRUE);
				$this->data['itemstore']    = $this->itemstore_m->get_select_itemstore('itemstoreID,name', array('itemstoreID'=>$itemcheckin->storeID), TRUE);
            }
        }
        $this->load->view('itemcheckin/view', $this->data);
    }

	public function delete() 
	{
        $itemcheckinID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$itemcheckinID) {
        	$itemcheckin = $this->itemcheckin_m->get_single_itemcheckin(array('itemcheckinID' => $itemcheckinID));
        	if(inicompute($itemcheckin)) {
	            $this->itemcheckin_m->delete_itemcheckin($itemcheckinID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('itemcheckin/index'));
        	} else {
	            $this->data["subview"] = '_not_found';
	            $this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function get_item()
    {
    	echo "<option value='0'>— ".$this->lang->line('itemcheckin_please_select')." — </option>";
        $itemcategoryID       = $this->input->post('itemcategoryID');
        if((int)$itemcategoryID || $itemcategoryID == 0) {
            $array = [];
            if((int)$itemcategoryID) {
                $array = ['categoryID' => $itemcategoryID];
            }
            $items = $this->item_m->get_order_by_item($array);
            if(inicompute($items)) {
            	foreach ($items as $item) {
            		echo "<option value='".$item->categoryID."'>".$item->name."</option>";
            	}
            }
        }
    }

	protected function rules() 
	{
		$rules = array(
			array(
				'field' => 'itemID',
				'label' => $this->lang->line("itemcheckin_item"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
			),
			array(
				'field' => 'supplierID',
				'label' => $this->lang->line("itemcheckin_supplier"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
			),
			array(
				'field' => 'storeID',
				'label' => $this->lang->line("itemcheckin_store"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
			),
			array(
				'field' => 'quantity',
				'label' => $this->lang->line("itemcheckin_quantity"),
				'rules' => 'trim|required|max_length[11]|numeric|is_natural_no_zero'
			),
			array(
				'field' => 'date',
				'label' => $this->lang->line("itemcheckin_date"),
				'rules' => 'trim|required|max_length[19]'
			),
			array(
				'field' => 'description',
				'label' => $this->lang->line("itemcheckin_description"),
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
        }
        return TRUE;
    }
}