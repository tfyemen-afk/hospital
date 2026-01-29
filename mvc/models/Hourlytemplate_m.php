<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hourlytemplate_m extends MY_Model {

    protected $_table_name = 'hourlytemplate';
    protected $_primary_key = 'hourlytemplateID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "hourlytemplateID asc";

    function __construct() {
        parent::__construct();
    }

    public function get_hourlytemplate($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_single_hourlytemplate($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_order_by_hourlytemplate($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function insert_hourlytemplate($array) {
        $id = parent::insert($array);
        return $id;
    }

    public function update_hourlytemplate($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_hourlytemplate($id){
        parent::delete($id);
    }
}
