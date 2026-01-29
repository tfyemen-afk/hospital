<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Damageandexpire extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model("medicine_m");
		$this->load->model("damageandexpire_m");
        $this->load->model("medicinecategory_m");
        $this->load->model("medicinepurchaseitem_m");
        $this->load->model("medicineunit_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('damageandexpire', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'type',
				'label' => $this->lang->line("damageandexpire_type"),
				'rules' => 'trim|required|numeric|max_length[1]|callback_required_no_zero'
			),
            array(
                'field' => 'medicinecategoryID',
                'label' => $this->lang->line("damageandexpire_category"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'medicineID',
                'label' => $this->lang->line("damageandexpire_medicine"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'batchID',
                'label' => $this->lang->line("damageandexpire_batchID"),
                'rules' => 'trim|required|max_length[32]|callback_required_no_zero'
            ),
            array(
                'field' => 'quantity',
                'label' => $this->lang->line("damageandexpire_quantity"),
                'rules' => 'trim|required|numeric|max_length[11]|integer|greater_than[0]|callback_check_quantity'
            )
		);
		return $rules;
	}

	public function index()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ],
            'js'  => [
                'assets/select2/select2.js',
                'assets/inilabs/damageandexpire/index.js',
            ]
        ];

        $displayID                  = escapeString($this->uri->segment('3'));
        if($displayID == 2) {
            $this->data['damageandexpires']  = $this->damageandexpire_m->get_order_by_damageandexpire(array('YEAR(create_date)' => date('Y'), 'MONTH(create_date)' => date('m')));
        } elseif($displayID == 3) {
            $this->data['damageandexpires']  = $this->damageandexpire_m->get_order_by_damageandexpire(array('YEAR(create_date)' => date('Y')));
        } elseif($displayID == 4) {
            $this->data['damageandexpires']  = $this->damageandexpire_m->get_damageandexpire();
        } else {
            $displayID = 1;
            $this->data['damageandexpires']  = $this->damageandexpire_m->get_order_by_damageandexpire(array('DATE(create_date)' => date('Y-m-d')));
        }
        $this->data['displayID']          = $displayID;

        $this->data['medicinecategorys'] = $this->medicinecategory_m->get_medicinecategory();
        $this->data['medicines']         = pluck($this->medicine_m->get_medicine(), 'name', 'medicineID');

        $medicinecategoryID = $this->input->post('medicinecategoryID');
        $medicineID         = $this->input->post('medicineID');
        $this->data['medicineitems']     = [];
        if((int)$medicinecategoryID) {
            $this->data['medicineitems'] = $this->medicine_m->get_select_medicine('medicineID, name', array('medicinecategoryID'=> $medicinecategoryID));
        }
        $this->data['medicinepurchaseitems'] = [];
        if((int)$medicineID) {
            $this->data['medicinepurchaseitems'] = $this->medicinepurchaseitem_m->get_select_medicinepurchaseitem('medicinepurchaseitemID, batchID', array('medicineID'=> $medicineID, 'status'=>0));
        }

        if(permissionChecker('damageandexpire_add')) {
    		if($_POST) {
    			$rules = $this->rules();
    			$this->form_validation->set_rules($rules);
    			if ($this->form_validation->run() == FALSE) {
    				$this->data["subview"] = "damageandexpire/index";
    				$this->load->view('_layout_main', $this->data);
    			} else {
                    $batchID         = $this->input->post('batchID');
                    $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem(array('medicineID'=> $medicineID, 'batchID'=>$batchID, 'status'=>0));
                    if(inicompute($medicinepurchaseitem)) {
                        $current_sale_quantity = $medicinepurchaseitem->salequantity + $this->input->post('quantity');

                        $updateArray['salequantity'] = $current_sale_quantity;
                        if($medicinepurchaseitem->quantity == $current_sale_quantity) {
                            $updateArray['status']   = 1;
                        }
                        $this->medicinepurchaseitem_m->update_medicinepurchaseitem($updateArray, $medicinepurchaseitem->medicinepurchaseitemID);
                    }

    				$array['type'] 			= $this->input->post('type');
    				$array['medicinecategoryID'] = $this->input->post('medicinecategoryID');
                    $array['medicineID']    = $this->input->post('medicineID');
                    $array['batchID']       = $this->input->post('batchID');
                    $array['quantity']      = $this->input->post('quantity');
                    $array['create_date'] 	= date('Y-m-d H:i:s');
    				$array['modify_date'] 	= date('Y-m-d H:i:s');
    				$array['create_userID'] = $this->session->userdata('loginuserID');
    				$array['create_roleID'] = $this->session->userdata('roleID');

    				$this->damageandexpire_m->insert_damageandexpire($array);
    				$this->session->set_flashdata('success','Success');
    				redirect(site_url("damageandexpire/index"));
    			}
    		} else {
    			$this->data["subview"] = "damageandexpire/index";
    			$this->load->view('_layout_main', $this->data);
    		}
        } else {
            $this->data["subview"] = "damageandexpire/index";
            $this->load->view('_layout_main', $this->data);
        }
	}

	public function edit()
    {
		$damageandexpireID  = htmlentities(escapeString($this->uri->segment(3)));
		$displayID          = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$damageandexpireID && (int)$displayID) {
    		$this->data['damageandexpire']      = $this->damageandexpire_m->get_single_damageandexpire(array('damageandexpireID'=>$damageandexpireID));
			if(inicompute($this->data['damageandexpire'])) {
                $this->data['maindamageandexpireID']      = $damageandexpireID;
                $this->data['headerassets'] = [
                    'css' => [
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ],
                    'js'  => [
                        'assets/select2/select2.js',
                        'assets/inilabs/damageandexpire/index.js',
                    ]
                ];

                if($displayID == 2) {
                    $this->data['damageandexpires']  = $this->damageandexpire_m->get_order_by_damageandexpire(array('YEAR(create_date)' => date('Y'), 'MONTH(create_date)' => date('m')));
                } elseif($displayID == 3) {
                    $this->data['damageandexpires']  = $this->damageandexpire_m->get_order_by_damageandexpire(array('YEAR(create_date)' => date('Y')));
                } elseif($displayID == 4) {
                    $this->data['damageandexpires']  = $this->damageandexpire_m->get_damageandexpire();
                } else {
                    $displayID = 1;
                    $this->data['damageandexpires']  = $this->damageandexpire_m->get_order_by_damageandexpire(array('DATE(create_date)' => date('Y-m-d')));
                }
                $this->data['displayID']          = $displayID;

                $this->data['medicines']         = pluck($this->medicine_m->get_medicine(), 'name', 'medicineID');
                $this->data['medicinecategorys'] = $this->medicinecategory_m->get_medicinecategory();

                if($_POST) {
                    $medicinecategoryID = $this->input->post('medicinecategoryID');
                    $medicineID         = $this->input->post('medicineID');
                } else {
                    $medicinecategoryID = $this->data['damageandexpire'] ? $this->data['damageandexpire']->medicinecategoryID : 0;
                    $medicineID         = $this->data['damageandexpire'] ? $this->data['damageandexpire']->medicineID : 0;
                }

                $this->data['medicineitems']     = [];
                if((int)$medicinecategoryID) {
                    $this->data['medicineitems'] = $this->medicine_m->get_select_medicine('medicineID, name', array('medicinecategoryID'=> $medicinecategoryID));
                }
                $this->data['medicinepurchaseitems']     = [];
                if((int)$medicineID) {
                    $this->data['medicinepurchaseitems'] = $this->medicinepurchaseitem_m->get_select_medicinepurchaseitem('medicinepurchaseitemID, batchID', array('medicineID'=> $medicineID, 'status'=>0));
                }

				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "damageandexpire/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                        $batchID              = $this->input->post('batchID');
                        $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem(array('medicineID'=> $medicineID, 'batchID'=>$batchID));
                        if(inicompute($medicinepurchaseitem)) {
                            $damageandexpire = $this->data['damageandexpire'];
                            if(inicompute($damageandexpire)) {
                                if(($medicinepurchaseitem->medicineID == $damageandexpire->medicineID) && ($medicinepurchaseitem->batchID == $damageandexpire->batchID)) {
                                    $current_sale_quantity = ($medicinepurchaseitem->salequantity-$damageandexpire->quantity) + $this->input->post('quantity');
                                    $updateArray['salequantity'] = $current_sale_quantity;
                                    if($medicinepurchaseitem->quantity == $current_sale_quantity) {
                                        $updateArray['status']   = 1;
                                    } else {
                                        $updateArray['status']   = 0;
                                    }
                                    $this->medicinepurchaseitem_m->update_medicinepurchaseitem($updateArray, $medicinepurchaseitem->medicinepurchaseitemID);
                                } else {
                                    $current_sale_quantity = $medicinepurchaseitem->salequantity + $this->input->post('quantity');
                                    $updateArray['salequantity'] = $current_sale_quantity;
                                    if($medicinepurchaseitem->quantity == $current_sale_quantity) {
                                        $updateArray['status']   = 1;
                                    } else {
                                        $updateArray['status']   = 0;
                                    }
                                    $this->medicinepurchaseitem_m->update_medicinepurchaseitem($updateArray, $medicinepurchaseitem->medicinepurchaseitemID);

                                    $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem(array('medicineID'=> $damageandexpire->medicineID, 'batchID'=>$damageandexpire->batchID));
                                    if(inicompute($medicinepurchaseitem)) {
                                        $newsalequantity = $medicinepurchaseitem->salequantity-$damageandexpire->quantity;
                                        $againupdateArray['salequantity'] = $newsalequantity;
                                        if($medicinepurchaseitem->quantity == $newsalequantity) {
                                            $againupdateArray['status']   = 1;
                                        } else {
                                            $againupdateArray['status']   = 0;
                                        }
                                        $this->medicinepurchaseitem_m->update_medicinepurchaseitem($againupdateArray, $medicinepurchaseitem->medicinepurchaseitemID);
                                    }
                                }
                            }
                        }

                        $array['type']          = $this->input->post('type');
                        $array['medicineID']    = $this->input->post('medicineID');
                        $array['batchID']        = $this->input->post('batchID');
                        $array['quantity']      = $this->input->post('quantity');
                        $array['modify_date']   = date('Y-m-d H:i:s');
						$this->damageandexpire_m->update_damageandexpire($array, $damageandexpireID);

						$this->session->set_flashdata('success','Success');
						redirect(site_url("damageandexpire/index/".$displayID));
					}
				} else {
					$this->data["subview"] = "damageandexpire/edit";
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
        $damageandexpireID  = htmlentities(escapeString($this->uri->segment('3')));
        $displayID          = htmlentities(escapeString($this->uri->segment('4')));
        if((int)$damageandexpireID && (int)$displayID) {
            $damageandexpire = $this->damageandexpire_m->get_single_damageandexpire(array('damageandexpireID'=>$damageandexpireID));
            if(inicompute($damageandexpire)) {
                $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem(array('medicineID'=> $damageandexpire->medicineID, 'batchID'=>$damageandexpire->batchID));
                if(inicompute($medicinepurchaseitem)) {
                    $newsalequantity = $medicinepurchaseitem->salequantity-$damageandexpire->quantity;
                    $againupdateArray['salequantity'] = $newsalequantity;
                    if($medicinepurchaseitem->quantity == $newsalequantity) {
                        $againupdateArray['status']   = 1;
                    } else {
                        $againupdateArray['status']   = 0;
                    }
                    $this->medicinepurchaseitem_m->update_medicinepurchaseitem($againupdateArray, $medicinepurchaseitem->medicinepurchaseitemID);
                }
                $this->damageandexpire_m->delete_damageandexpire($damageandexpireID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('damageandexpire/index/'.$displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function view()
    {
        $damageandexpireID = $this->input->post('damageandexpireID');
        if((int)$damageandexpireID) {
            $this->data['damageandexpire'] = $this->damageandexpire_m->get_single_damageandexpire(array('damageandexpireID'=> $damageandexpireID));
            if(inicompute($this->data['damageandexpire'])) {
                $this->data['medicine']         = $this->medicine_m->get_single_medicine(array('medicineID'=>$this->data['damageandexpire']->medicineID));
                $this->data['medicinecategory'] = $this->medicinecategory_m->get_single_medicinecategory(array('medicinecategoryID'=>$this->data['damageandexpire']->medicinecategoryID));
                if(inicompute($this->data['medicine'])) {
                    $this->data['medicineunit']  = $this->medicineunit_m->get_single_medicineunit(array('medicineunitID'=>$this->data['medicine']->medicineunitID));
                } else {
                    $this->data['medicineunit']  = [];
                }
                $this->load->view('damageandexpire/view', $this->data);    
            } else {
                $this->load->view('modal_not_found', $this->data);    
            }
        } else {
            $this->load->view('modal_not_found', $this->data);    
        }
    }

    public function get_medicine()
    {
        echo "<option value='0'>— ".$this->lang->line('damageandexpire_please_select')." —</option>";
        if($_POST) {
            $medicinecategoryID = $this->input->post('medicinecategoryID');
            if((int)$medicinecategoryID) {
                $medicines = $this->medicine_m->get_select_medicine('medicineID, name', array('medicinecategoryID'=> $medicinecategoryID));
                if(inicompute($medicines)) {
                    foreach ($medicines as $medicine) {
                        echo "<option value='".$medicine->medicineID."'>".$medicine->name."</option>";
                    }
                }
            }
        }
    }

    public function get_medicine_batch()
    {
        echo "<option value='0'>— ".$this->lang->line('damageandexpire_please_select')." —</option>";
        if($_POST) {
            $medicineID         = $this->input->post('medicineID');
            if((int)$medicineID) {
                $medicinepurchaseitems = $this->medicinepurchaseitem_m->get_select_medicinepurchaseitem('medicinepurchaseitemID, batchID', array('medicineID'=> $medicineID, 'status'=>0));
                if(inicompute($medicinepurchaseitems)) {
                    foreach ($medicinepurchaseitems as $medicinepurchaseitem) {
                        echo "<option value='".$medicinepurchaseitem->batchID."'>".$medicinepurchaseitem->batchID."</option>";
                    }
                }
            }
        }
    }

    public function required_no_zero($data)
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }

    public function check_quantity()
    {
        $damageandexpireID = htmlentities(escapeString($this->uri->segment('3')));
        if($_POST) {
            $medicineID = $this->input->post('medicineID');
            $batchID    = $this->input->post('batchID');
            $quantity   = $this->input->post('quantity');
            if((int)$medicineID && (string)$batchID) {
                if((int)$damageandexpireID) {
                    $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem(array('medicineID'=> $medicineID, 'batchID'=>$batchID));
                    if(inicompute($medicinepurchaseitem)) {
                        $damageandexpire    = $this->damageandexpire_m->get_single_damageandexpire(array('damageandexpireID'=>$damageandexpireID));
                        $available_quantity = $medicinepurchaseitem->quantity - $medicinepurchaseitem->salequantity;

                        if(inicompute($damageandexpire)) {
                            if($medicinepurchaseitem->batchID == $damageandexpire->batchID) {
                                $available_quantity += $damageandexpire->quantity;
                            }
                        }
                        
                        if($quantity > $available_quantity) {
                            $this->form_validation->set_message("check_quantity", "Your available medicine quantity is $available_quantity.");
                            return FALSE;
                        } 
                        return TRUE;
                    } else {
                        $this->form_validation->set_message("check_quantity", "This medicine does not available.");
                        return FALSE;
                    }
                } else {
                    $medicinepurchaseitem = $this->medicinepurchaseitem_m->get_single_medicinepurchaseitem(array('medicineID'=> $medicineID, 'batchID'=>$batchID, 'status'=>0));
                    if(inicompute($medicinepurchaseitem)) {
                        $available_quantity = $medicinepurchaseitem->quantity - $medicinepurchaseitem->salequantity;
                        if($quantity > $available_quantity) {
                            $this->form_validation->set_message("check_quantity", "Your available medicine quantity is $available_quantity.");
                            return FALSE;
                        } 
                        return TRUE;
                    } else {
                        $this->form_validation->set_message("check_quantity", "This medicine does not available.");
                        return FALSE;
                    }
                }
            } else {
                $this->form_validation->set_message("check_quantity", "The Medicine and Batch field is required.");
                return FALSE;
            }
        }
        $this->form_validation->set_message("check_quantity", "Post method are invalid.");
        return FALSE;
    }
}