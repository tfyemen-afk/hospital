<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Salarytemplate_m extends MY_Model {

    protected $_table_name = 'salarytemplate';
    protected $_primary_key = 'salarytemplateID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "salarytemplateID asc";

    function __construct() {
        parent::__construct();
    }

    function get_salarytemplate($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    function get_single_salarytemplate($array) {
        $query = parent::get_single($array);
        return $query;
    }

    function get_order_by_salarytemplate($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    function insert_salarytemplate($array) {
        $id = parent::insert($array);
        return $id;
    }

    function update_salarytemplate($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_salarytemplate($id){
        parent::delete($id);
    }
}
