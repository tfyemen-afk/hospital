<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinepurchaseitem_m extends MY_Model {

    protected $_table_name = 'medicinepurchaseitem';
    protected $_primary_key = 'medicinepurchaseitemID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "medicinepurchaseitemID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_medicinepurchaseitem($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_select_medicinepurchaseitem($select = 'medicinepurchaseitemID, batchID', $array=[], $single = false) {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_order_by_medicinepurchaseitem($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_medicinepurchaseitem($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_medicinepurchaseitem($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_medicinepurchaseitem($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_medicinepurchaseitem($id){
        parent::delete($id);
    }

    public function insert_batch_medicinepurchaseitem($array) {
        return parent::insert_batch($array);
    }

    public function update_batch_medicinepurchaseitem($array, $column) {
        return parent::update_batch($array, $column);
    }

    public function delete_batch_medicinepurchaseitem($array){
        $this->db->where($array);
        $this->db->delete($this->_table_name);
    }

    public function delete_batch_medicinepurchaseitem_array($array){
        $this->db->where_in('medicinepurchaseitemID', $array);
        $this->db->delete($this->_table_name);
    }

    public function get_medicinepurchaseitem_quantity() {
        $string = 'SELECT SUM(medicinepurchaseitem.quantity) AS quantity, medicinepurchaseitem.medicineID AS medicineID FROM medicinepurchaseitem LEFT JOIN medicinepurchase on medicinepurchase.medicinepurchaseID = medicinepurchaseitem.medicinepurchaseID GROUP BY medicinepurchaseitem.medicineID';
        $query = $this->db->query($string);
        return $query->result();
    }

    public function get_where_in_medicinepurchaseitem($arrays, $key, $wherearray){
        return parent::get_where_in($arrays, $key, $wherearray);
    }
}