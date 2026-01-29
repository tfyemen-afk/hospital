<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Managesalary_m extends MY_Model {

    protected $_table_name = 'managesalary';
    protected $_primary_key = 'managesalaryID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "managesalaryID asc";

    function __construct() {
        parent::__construct();
    }

    function get_managesalary($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_order_by_managesalary($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function get_single_managesalary($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function insert_managesalary($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    function update_managesalary($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_managesalary($id){
        parent::delete($id);
    }
}