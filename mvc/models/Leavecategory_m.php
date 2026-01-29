<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leavecategory_m extends MY_Model {

    protected $_table_name = 'leavecategory';
    protected $_primary_key = 'leavecategoryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "leavecategoryID desc";

    function __construct() {
        parent::__construct();
    }

    function get_leavecategory($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_leavecategory($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_single_leavecategory($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_leavecategory($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_leavecategory($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_leavecategory($id){
        parent::delete($id);
    }
}
