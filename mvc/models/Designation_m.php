<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Designation_m extends MY_Model {

    protected $_table_name = 'designation';
    protected $_primary_key = 'designationID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "designationID desc";

    function __construct() {
        parent::__construct();
    }

    function get_designation($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_designation($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_single_designation($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_designation($array) {
        parent::insert($array);
        return TRUE;
    }

    function update_designation($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_designation($id){
        parent::delete($id);
    }

    public function get_select_designation($select = 'designationID, designation', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }
}