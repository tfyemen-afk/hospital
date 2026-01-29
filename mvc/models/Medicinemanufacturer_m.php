<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinemanufacturer_m extends MY_Model {

    protected $_table_name = 'medicinemanufacturer';
    protected $_primary_key = 'medicinemanufacturerID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "medicinemanufacturerID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_select_medicinemanufacturer($select = 'medicinemanufacturerID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_medicinemanufacturer($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_medicinemanufacturer($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_medicinemanufacturer($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_medicinemanufacturer($array) {
        return parent::insert($array);
    }

    public function update_medicinemanufacturer($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_medicinemanufacturer($id){
        return parent::delete($id);
    }
}