<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Patient_m extends MY_Model 
{
    protected $_table_name = 'patient';
    protected $_primary_key = 'patientID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "patientID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_patient($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_patient($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_patient($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }


    public function insert_patient($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_patient($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_patient($id)
    {
        parent::delete($id);
    }
    
    public function get_select_patient($select = 'patientID, name', $array=[], $single = false) 
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_patient_by_billID()
    {
        $this->db->select('name, bill.billID');
        $this->db->from('patient');
        $this->db->join('bill', 'bill.patientID = patient.patientID');
        return $this->db->get()->result();
    }
}