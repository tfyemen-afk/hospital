<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinestock extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
        $this->load->model('medicine_m');
        $this->load->model('medicinecategory_m');
        $this->load->model('medicinepurchase_m');
        $this->load->model('medicinepurchaseitem_m');
        $this->load->model('medicinemanufacturer_m');
        $this->load->model('medicineunit_m');
        $this->load->model('damageandexpire_m');
		$language = $this->session->userdata('lang');;
		$this->lang->load('medicinestock', $language);
	}

	public function index()
    {
        $this->data['medicinecategorys']  = pluck($this->medicinecategory_m->get_select_medicinecategory(), 'name','medicinecategoryID');
        $this->data['medicines']          = pluck($this->medicine_m->get_medicine(), 'obj','medicineID');
        $this->data['medicinemanufacturers']  = pluck($this->medicinemanufacturer_m->get_select_medicinemanufacturer(), 'name','medicinemanufacturerID');
        $this->data['medicineunits']      = pluck($this->medicineunit_m->get_select_medicineunit(), 'medicineunit','medicineunitID');
        $this->data['medicinestatus']     = $this->_check_medicine_info();
        $this->data["subview"] = "medicinestock/index";
        $this->load->view('_layout_main', $this->data);
	}

    public function view()
    {
        if(permissionChecker('medicinestock')) {
            $medicineID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$medicineID) {
                $this->data['medicine'] = $this->medicine_m->get_single_medicine(array('medicineID' => $medicineID));
                if(inicompute($this->data['medicine'])) {
                    $medicine = $this->data['medicine'];
                    $this->data['medicinepurchase']      = pluck($this->medicinepurchase_m->get_select_medicinepurchase('medicinepurchaseID', ['medicinepurchaserefund' => 1]), 'obj', 'medicinepurchaseID');
                    $this->data['medicinecategory']      = $this->medicinecategory_m->get_single_medicinecategory(array('medicinecategoryID'=> $medicine->medicinecategoryID));
                    $this->data['medicinemanufacturer']  = $this->medicinemanufacturer_m->get_single_medicinemanufacturer(array('medicinemanufacturerID'=> $medicine->medicinemanufacturerID));
                    $this->data['medicineunit']          = $this->medicineunit_m->get_single_medicineunit(array('medicineunitID'=> $medicine->medicineunitID));
                    $this->data['medicinepurchaseitems'] = $this->medicinepurchaseitem_m->get_order_by_medicinepurchaseitem(array('medicineID' => $medicineID));
                    $this->data["setting_expire_date"]       = $this->_setting_expire_date();
                    $this->data["total_available_quantity"]  = $this->_get_medicine_info();

                    $damageandexpires = $this->damageandexpire_m->get_order_by_damageandexpire(['medicineID'=> $medicineID]);
                    $damageandexpireArray = [];
                    if(inicompute($damageandexpires)) {
                        foreach($damageandexpires as $damageandexpire) {
                            if($damageandexpire->type == 1) {
                                if(isset($damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID][1])) {
                                    $damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID][1]  += $damageandexpire->quantity;
                                } else {
                                    $damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID][1]  = $damageandexpire->quantity;
                                }
                            } elseif($damageandexpire->type == 2) {
                                if(isset($damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID][2])) {
                                    $damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID][2]  += $damageandexpire->quantity;
                                } else {
                                    $damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID][2]  = $damageandexpire->quantity;
                                }
                            }

                            if(isset($damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID]['totaldamgeandexpire'])) {
                                $damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID]['totaldamgeandexpire']  += $damageandexpire->quantity;
                            } else {
                                $damageandexpireArray[$damageandexpire->medicineID][$damageandexpire->batchID]['totaldamgeandexpire']  = $damageandexpire->quantity;
                            }
                        }
                    }
                    $this->data['damageandexpire'] = $damageandexpireArray;
                    $this->data["subview"] = "medicinestock/viewtable";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $this->data["subview"] = "_not_found";
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

    private function _check_medicine_info()
    {
        $medicines             = $this->data['medicines'];
        $medicinepurchaseitems = pluck_multi_array($this->medicinepurchaseitem_m->get_medicinepurchaseitem(),'obj','medicineID');
        $setting_expire_date   = $this->_setting_expire_date();
        
        $retArray  = [];
        if(inicompute($medicines)) {
            foreach($medicines as $medicine) {
                $expire    = FALSE;
                $available = FALSE;
                if(isset($medicinepurchaseitems[$medicine->medicineID])) {
                    $purchaseitems = $medicinepurchaseitems[$medicine->medicineID];
                    if(inicompute($purchaseitems)) {
                        foreach ($purchaseitems as $purchaseitem) {
                            if($purchaseitem->status == 0) {
                                $item_expire_date = strtotime($purchaseitem->expire_date);
                                if($item_expire_date > $setting_expire_date) {
                                    $available = TRUE;
                                } else {
                                    $expire    = TRUE;
                                }
                            }
                        }
                    }
                }

                // 1= available, 2=expire, 0=stockout
                if($available) {
                    $retArray[$medicine->medicineID] = 1;
                } elseif($expire) {
                    $retArray[$medicine->medicineID] = 2;
                } else {
                    $retArray[$medicine->medicineID] = 0;
                }
            }
        }
        return $retArray;
    }

    private function _get_medicine_info()
    {
        $medicinepurchaseitems = $this->data['medicinepurchaseitems'];
        $setting_expire_date   = $this->_setting_expire_date();
        $total_quantity     = 0;
        $stockout_quantity  = 0;
        $expire_quantity    = 0;
        $available_quantity = 0;
        if(inicompute($medicinepurchaseitems)) {
            foreach ($medicinepurchaseitems as $medicinepurchaseitem) { 
                if($medicinepurchaseitem->status == 0) {
                    $item_expire_date = strtotime($medicinepurchaseitem->expire_date);
                    if($item_expire_date > $setting_expire_date) {
                        $available_quantity += ($medicinepurchaseitem->quantity-$medicinepurchaseitem->salequantity);
                    } else {
                        // expire;
                    }
                } else {
                    // expire;
                }
                $total_quantity   += $medicinepurchaseitem->quantity;
            }
        }

        // 1= available, 2=expire, 0=stockout
        $retArray       = [];
        $retArray[0]    = $stockout_quantity;
        $retArray[1]    = $available_quantity;
        $retArray[2]    = $expire_quantity;
        $retArray[3]    = $total_quantity;
        return $retArray[1];
    }

    private function _setting_expire_date()
    {
        $medicine_expire_limit_day = isset($this->data['generalsettings']->medicine_expire_limit_day) ? $this->data['generalsettings']->medicine_expire_limit_day : 0;
        return strtotime("+$medicine_expire_limit_day days");
    }
}