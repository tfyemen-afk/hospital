<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinepurchase_m extends MY_Model {

    protected $_table_name = 'medicinepurchase';
    protected $_primary_key = 'medicinepurchaseID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "medicinepurchaseID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_medicinepurchase($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_select_medicinepurchase($select = NULL, $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_order_by_medicinepurchase($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_medicinepurchase($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_medicinepurchase($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_medicinepurchase($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_medicinepurchase($id){
        parent::delete($id);
    }

    public function get_medicinepurchase_for_report($queryArray) {
        $this->db->select('*');
        $this->db->from('medicinepurchase');

        if(isset($queryArray['medicinewarehouseID']) && $queryArray['medicinewarehouseID'] != 0) {
            $this->db->where('medicinepurchase.medicinewarehouseID', $queryArray['medicinewarehouseID']);
        }

        if(isset($queryArray['reference_no']) && $queryArray['reference_no']) {
            $this->db->where('medicinepurchase.medicinepurchasereferenceno', $queryArray['reference_no']);
        }

        if(isset($queryArray['status']) && $queryArray['status'] != 0) {
            if($queryArray['status'] == 1) {
                $this->db->where('medicinepurchase.medicinepurchasestatus', 0);
                $this->db->where('medicinepurchase.medicinepurchaserefund', 0);
            } elseif($queryArray['status'] == 2) {
                $this->db->where('medicinepurchase.medicinepurchasestatus', 1);
                $this->db->where('medicinepurchase.medicinepurchaserefund', 0);
            } elseif($queryArray['status'] == 3) {
                $this->db->where('medicinepurchase.medicinepurchasestatus', 2);
                $this->db->where('medicinepurchase.medicinepurchaserefund', 0);
            } elseif($queryArray['status'] == 4) {
                $this->db->where('medicinepurchase.medicinepurchaserefund', 1);
            }
        } else {
            $this->db->where('medicinepurchase.medicinepurchaserefund', 0);
        }

        if((isset($queryArray['from_date']) && $queryArray['from_date'] != 0) && (isset($queryArray['to_date']) && $queryArray['to_date'] != 0)) {
            $from_date = date('Y-m-d', strtotime($queryArray['from_date']));
            $to_date = date('Y-m-d', strtotime($queryArray['to_date']));
            $this->db->where('medicinepurchasedate >=', $from_date);
            $this->db->where('medicinepurchasedate <=', $to_date);
        }

        return $this->db->get()->result();
    }
}

/* End of file Medicinepurchase_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/Medicinepurchase_m.php */