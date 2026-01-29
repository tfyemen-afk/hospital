<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinesaleitem_m extends MY_Model {

    protected $_table_name = 'medicinesaleitem';
    protected $_primary_key = 'medicinesaleitemID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "medicinesaleitemID desc";

    function __construct() {
        parent::__construct();
    }

    public function get_medicinesaleitem($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_medicinesaleitem($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_medicinesaleitem($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_medicinesaleitem($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_medicinesaleitem($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_medicinesaleitem($id){
        parent::delete($id);
    }

    public function insert_batch_medicinesaleitem($array) {
        return parent::insert_batch($array);
    }

    public function update_batch_medicinesaleitem($array, $column) {
        return parent::update_batch($array, $column);
    }

    public function delete_batch_medicinesaleitem($array){
        $this->db->where($array);
        $this->db->delete($this->_table_name);
    }

    public function get_medicinesaleitem_quantity($medicinesaleID = 0) {
        if($medicinesaleID == 0) {
            $string = 'SELECT SUM(medicinesaleitem.medicinesalequantity) AS quantity, medicinesaleitem.medicineID AS medicineID FROM medicinesaleitem LEFT JOIN medicinesale on medicinesale.medicinesaleID = medicinesaleitem.medicinesaleID WHERE medicinesalerefund = 0 GROUP BY medicinesaleitem.medicineID';
        } else {
            $string = 'SELECT SUM(medicinesaleitem.medicinesalequantity) AS quantity, medicinesaleitem.medicineID AS medicineID FROM medicinesaleitem LEFT JOIN medicinesale on medicinesale.medicinesaleID = medicinesaleitem.medicinesaleID WHERE medicinesalerefund = 0 && medicinesaleitem.medicinesaleID = "'.$medicinesaleID.'" GROUP BY medicinesaleitem.medicineID';
        }

        $query = $this->db->query($string);
        return $query->result();
    }

}
