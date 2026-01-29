<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinewarehouse_m extends MY_Model {

    protected $_table_name = 'medicinewarehouse';
    protected $_primary_key = 'medicinewarehouseID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "medicinewarehouseID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_select_medicinewarehouse($select = 'medicinewarehouseID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    function get_medicinewarehouse($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_medicinewarehouse($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_single_medicinewarehouse($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_medicinewarehouse($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_medicinewarehouse($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_medicinewarehouse($id){
        parent::delete($id);
    }
}