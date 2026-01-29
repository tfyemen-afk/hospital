<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leaveassign_m extends MY_Model {

    protected $_table_name = 'leaveassign';
    protected $_primary_key = 'leaveassignID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "leaveassignID desc";

    function __construct() {
        parent::__construct();
    }

    function get_leaveassign($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_leaveassign($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_single_leaveassign($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_leaveassign($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_leaveassign($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_leaveassign($id){
        parent::delete($id);
    }
}
