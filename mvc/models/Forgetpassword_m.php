<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgetpassword_m extends MY_Model {

    protected $_table_name = 'forgetpassword';
    protected $_primary_key = 'forgetpasswordID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "forgetpasswordID desc";

    function __construct() {
        parent::__construct();
    }

    function get_forgetpassword($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_forgetpassword($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_forgetpassword($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_forgetpassword($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_forgetpassword($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_forgetpassword($id){
        parent::delete($id);
    }
    
    public function hash($string) {
        return parent::hash($string);
    }

    public function get_select_forgetpassword($select = 'forgetpasswordID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }
}