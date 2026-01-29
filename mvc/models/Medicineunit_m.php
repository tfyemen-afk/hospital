<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicineunit_m extends MY_Model {

    protected $_table_name = 'medicineunit';
    protected $_primary_key = 'medicineunitID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "medicineunitID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_select_medicineunit($select = 'medicineunitID, medicineunit', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_medicineunit($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_medicineunit($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_medicineunit($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_medicineunit($array) {
        return parent::insert($array);
    }

    public function update_medicineunit($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_medicineunit($id){
        return parent::delete($id);
    }
}