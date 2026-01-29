<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Itemcheckout extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("itemcheckin_m");
		$this->load->model("itemcheckout_m");
		$this->load->model("item_m");
		$this->load->model("itemcategory_m");
		$this->load->model("user_m");
		$this->load->model("designation_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('itemcheckout', $language);
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
                'assets/inilabs/itemcheckout/index.js',
            )
        );

		$this->data['itemcheckouts']  = $this->itemcheckout_m->get_itemcheckout();
		$this->data['itemcategory']   = $this->itemcategory_m->get_itemcategory();
		$this->data['items']          = pluck($this->item_m->get_select_item('itemID, name'), 'obj', 'itemID');
		$this->data['users']          = pluck($this->user_m->get_select_user('userID, name, designationID', array('roleID !=' => 3, 'status' => 1, 'delete_at' => 0)), 'obj', 'userID');
		$this->data['designations']   = pluck($this->designation_m->get_select_designation('designationID, designation'), 'designation', 'designationID');

		$this->data['jsmanager'] = ['categoryID' => '', 'itemID' => ''];
        if(permissionChecker('itemcheckout_add')) {
            if($_POST) {
                $this->data['jsmanager'] = ['categoryID' => set_value('categoryID'), 'itemID' => set_value('itemID')];
                $rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'itemcheckout/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['userID']        = $this->input->post('userID');
                    $array['issuedate']     = date("Y-m-d H:i:s", strtotime($this->input->post('issuedate')));
                    $array['returndate']    = date("Y-m-d H:i:s", strtotime($this->input->post('returndate')));
                    $array['itemID']        = $this->input->post('itemID');
                    $array['quantity']      = $this->input->post('quantity');
                    $array['note']          = $this->input->post('note');
                    $array['status']        = 0;
                    $array["create_date"]   = date("Y-m-d H:i:s");
                    $array["modify_date"]   = date("Y-m-d H:i:s");
                    $array["create_userID"] = $this->session->userdata('loginuserID');
                    $array["create_roleID"] = $this->session->userdata('roleID');

					$this->itemcheckout_m->insert_itemcheckout($array);
					$this->update_itemcheckin_status();

					$this->session->set_flashdata('success','Success');
					redirect(site_url("itemcheckout/index"));
				}
			} else {
				$this->data["subview"] = "itemcheckout/index";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "itemcheckout/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$itemcheckoutID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$itemcheckoutID) {
			$this->data['itemcheckout'] 	= $this->itemcheckout_m->get_single_itemcheckout(array('itemcheckoutID' => $itemcheckoutID));
			if(inicompute($this->data['itemcheckout'])) {
				$this->data['headerassets'] = array(
		            'css' => array(
		                'assets/select2/css/select2.css',
		                'assets/select2/css/select2-bootstrap.css',
		                'assets/datetimepicker/css/datetimepicker.css',
		            ),
		            'js' => array(
		                'assets/select2/select2.js',
		                'assets/datetimepicker/js/datetimepicker.js',
                        'assets/inilabs/itemcheckout/index.js',
		            )
		        );

				$this->data['itemcheckouts'] = $this->itemcheckout_m->get_itemcheckout();
				$this->data['itemcategory']  = $this->itemcategory_m->get_itemcategory();
				$this->data['items']         = pluck($this->item_m->get_select_item('itemID, name'), 'obj', 'itemID');
				$this->data['users']         = pluck($this->user_m->get_select_user('userID, name, designationID', array('roleID !=' => 3, 'status' => 1, 'delete_at' => 0)), 'obj', 'userID');
                $this->data['designations']  = pluck($this->designation_m->get_select_designation('designationID, designation'), 'designation', 'designationID');

				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
	                    $this->data["subview"] = 'itemcheckout/edit';
	                    $this->load->view('_layout_main', $this->data);    
	                } else {
	                    $array['userID']        = $this->input->post('userID');
	                    $array['issuedate']     = date("Y-m-d H:i:s", strtotime($this->input->post('issuedate')));
	                    $array['returndate']    = date("Y-m-d H:i:s", strtotime($this->input->post('returndate')));
	                    $array['itemID']        = $this->input->post('itemID');
	                    $array['quantity']      = $this->input->post('quantity');
	                    $array['note']          = $this->input->post('note');
	                    $array["modify_date"]   = date("Y-m-d H:i:s");
                        if(strtotime($this->data['itemcheckout']->returndate) < strtotime($this->input->post('returndate'))) {
	                       $array['currentreturndate   ']  = NULL;
                           $array['status']                = 0;
                        }

						$this->itemcheckout_m->update_itemcheckout($array, $itemcheckoutID);
						$this->update_itemcheckin_status();

						$this->session->set_flashdata('success','Success');
						redirect(site_url("itemcheckout/index"));
					}
				} else {
					$this->data["subview"] = "itemcheckout/edit";
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
        $itemcheckoutID              = $this->input->post('itemcheckoutID');
        $this->data["itemcheckout"]  = [];
		$this->data['item']          = [];

        if((int)$itemcheckoutID) {
            $itemcheckout = $this->itemcheckout_m->get_single_itemcheckout(array('itemcheckoutID'=> $itemcheckoutID));
            if(inicompute($itemcheckout)) {
            	$this->data["itemcheckout"] = $itemcheckout;
            	$this->data['item']         = $this->item_m->get_select_item('itemID,name', array('itemID'=>$itemcheckout->itemID), TRUE);
            	$this->data['user']         = $this->user_m->get_select_user('userID, name', array('userID'=> $itemcheckout->userID, 'roleID !=' => 3, 'status' => 1, 'delete_at' => 0), TRUE);
            }
        }
        $this->load->view('itemcheckout/view', $this->data);
    }

	public function itemreturn() 
	{
	    if(permissionChecker('itemcheckout_edit')) {
            $itemcheckoutID = htmlentities(escapeString($this->uri->segment('3')));
            if((int)$itemcheckoutID) {
                $itemcheckout = $this->itemcheckout_m->get_single_itemcheckout(array('itemcheckoutID' => $itemcheckoutID));
                if(inicompute($itemcheckout) && $itemcheckout->status==0) {
                    $array['status']            = 1;
                    $array['currentreturndate'] = date("Y-m-d H:i:s");
                    $this->itemcheckout_m->update_itemcheckout($array,$itemcheckoutID);
                    $this->update_itemcheckin_status();
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('itemcheckout/index'));
                } else {
                    $this->data["subview"] = '_not_found';
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

	public function delete() 
	{
        $itemcheckoutID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$itemcheckoutID) {
        	$itemcheckout = $this->itemcheckout_m->get_single_itemcheckout(array('itemcheckoutID' => $itemcheckoutID));
        	if(inicompute($itemcheckout)) {
	            $this->update_itemcheckin_status();
	            $this->itemcheckout_m->delete_itemcheckout($itemcheckoutID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('itemcheckout/index'));
        	} else {
	            $this->data["subview"] = '_not_found';
	            $this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    private function update_itemcheckin_status()
    {
    	$itemID   = $this->input->post('itemID');
    	$quantity = ($this->input->post('quantity')) ? $this->input->post('quantity') : 0;

    	$itemcheckoutquantity = 0;
    	$itemcheckoutID       = htmlentities(escapeString($this->uri->segment('3')));
    	if((int)$itemcheckoutID) {
    		$itemcheckout   = $this->itemcheckout_m->get_single_itemcheckout(array('itemcheckoutID' => $itemcheckoutID));
    		$itemcheckoutquantity = inicompute($itemcheckout) ? $itemcheckout->quantity : 0;
			$itemID   = inicompute($itemcheckout) ? $itemcheckout->itemID : 0;
    	}

    	if((int)$itemID) {
    		$itemcheckin   = $this->itemcheckin_m->get_sum_itemcheckin('quantity', array('itemID' => $itemID));
    		$itemcheckout  = $this->itemcheckout_m->get_sum_itemcheckout('quantity', array('itemID' => $itemID, 'status'=> 0));
    		
    		$itemcheckinamount   = ($itemcheckin->quantity) ? $itemcheckin->quantity : 0;
    		if($itemcheckoutquantity > 0) {
    			$itemcheckoutamount  = ($itemcheckout->quantity) ? $itemcheckout->quantity : 0;
    			$itemcheckoutamount  -= $itemcheckoutquantity;
    		} else {
    			$itemcheckoutamount  = ($itemcheckout->quantity) ? $itemcheckout->quantity : 0;
    		}

    		$itemcheckoutamount_quantity = $itemcheckoutamount+$quantity;
    		
    		$array = [];
    		if($itemcheckinamount > $itemcheckoutamount_quantity) {
    			$array['status'] = 0;
    		} else {
    			$array['status'] = 1;
    		}
    		$this->itemcheckin_m->update_itemcheckin_itemID($array, $itemID);
    	}
    }

    public function get_item()
    {
    	echo "<option value='0'>— ".$this->lang->line('itemcheckout_please_select')." — </option>";
        $itemcategoryID       = $this->input->post('itemcategoryID');
        if((int)$itemcategoryID || $itemcategoryID == 0) {
            $array = [];
            if($itemcategoryID) {
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
				'field' => 'userID',
				'label' => $this->lang->line("itemcheckout_user"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
			),
			array(
				'field' => 'issuedate',
				'label' => $this->lang->line("itemcheckout_issue_date"),
				'rules' => 'trim|required|max_length[19]'
			),
			array(
				'field' => 'returndate',
				'label' => $this->lang->line("itemcheckout_return_date"),
				'rules' => 'trim|required|max_length[19]|callback_valid_return_date'
			),
			array(
				'field' => 'itemID',
				'label' => $this->lang->line("itemcheckout_item"),
				'rules' => 'trim|required|max_length[11]|numeric|callback_required_no_zero'
			),
			array(
				'field' => 'quantity',
				'label' => $this->lang->line("itemcheckout_quantity"),
				'rules' => 'trim|required|max_length[11]|numeric|is_natural_no_zero|callback_check_available_quantity'
			),
			array(
				'field' => 'note',
				'label' => $this->lang->line("itemcheckout_note"),
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

    public function valid_return_date()
    {
        $issuedate      = date('Y-m-d H:i:s',strtotime($this->input->post('issuedate')));
        $returndate     = date('Y-m-d H:i:s',strtotime($this->input->post('returndate')));
        if($returndate < $issuedate) {
            $this->form_validation->set_message('valid_return_date', "The %s cann't be small than issue date.");
            return FALSE;
        }
        return TRUE;
    }

    public function check_available_quantity()
    {
    	$itemID                 = $this->input->post('itemID');
    	$quantity               = $this->input->post('quantity');
    	$itemcheckoutquantity   = 0;
    	$itemcheckoutID         = htmlentities(escapeString($this->uri->segment('3')));
    	if((int)$itemcheckoutID) {
    		$itemcheckout           = $this->itemcheckout_m->get_single_itemcheckout(array('itemcheckoutID' => $itemcheckoutID));
    		$itemcheckoutquantity   = inicompute($itemcheckout) ? $itemcheckout->quantity : 0;
    	}

    	if((int)$itemID && (int)$quantity) {
    		$itemcheckin   = $this->itemcheckin_m->get_sum_itemcheckin('quantity', array('itemID' => $itemID));
    		$itemcheckout  = $this->itemcheckout_m->get_sum_itemcheckout('quantity', array('itemID' => $itemID, 'status'=> 0));
    		
    		$itemcheckinamount   = ($itemcheckin->quantity) ? $itemcheckin->quantity : 0;
    		if($itemcheckoutquantity > 0) {
    			$itemcheckoutamount  = ($itemcheckout->quantity) ? $itemcheckout->quantity : 0;
    			$itemcheckoutamount  -= $itemcheckoutquantity;
    		} else {
    			$itemcheckoutamount  = ($itemcheckout->quantity) ? $itemcheckout->quantity : 0;
    		}

    		$itemcheckoutamount_quantity = $itemcheckoutamount+$quantity;
    		$available_quantity          = $itemcheckinamount - $itemcheckoutamount;
    		if($itemcheckoutamount_quantity <= $itemcheckinamount) {
    			return TRUE;
    		} else {
    			$this->form_validation->set_message('check_available_quantity', "The %s of this item now available $available_quantity .");
            	return FALSE;
    		}
    	}
    	return TRUE;
    }
}