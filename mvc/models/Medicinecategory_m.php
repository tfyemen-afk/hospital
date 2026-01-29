<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinecategory_m extends MY_Model {

    protected $_table_name = 'medicinecategory';
    protected $_primary_key = 'medicinecategoryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "medicinecategoryID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_select_medicinecategory($select = 'medicinecategoryID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_medicinecategory($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_medicinecategory($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_medicinecategory($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_medicinecategory($array) {
        return parent::insert($array);
    }

    public function update_medicinecategory($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_medicinecategory($id){
        return parent::delete($id);
    }
}