<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Doctorinfo_m extends MY_Model {

    protected $_table_name = 'doctorinfo';
    protected $_primary_key = 'doctorinfoID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "doctorinfoID desc";

    function __construct() {
        parent::__construct();
    }

    function get_doctorinfo($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_doctorinfo($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_single_doctorinfo($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_doctorinfo($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_doctorinfo($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_doctorinfo($id){
        parent::delete($id);
    }
}