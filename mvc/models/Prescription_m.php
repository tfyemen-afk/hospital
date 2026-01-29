<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Prescription_m extends MY_Model
{
    protected $_table_name = 'prescription';
    protected $_primary_key = 'prescriptionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "prescriptionID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_prescription($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_prescription($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_prescription($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_prescription($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_prescription($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_prescription($id)
    {
        parent::delete($id);
    }

    public function insert_batch_prescription($array)
    {
        return parent::insert_batch($array);
    }

    public function update_batch_prescription($array, $column)
    {
        return parent::update_batch($array, $column);
    }

    public  function get_select_prescription($select = '*', $wherearray = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        if(inicompute($wherearray)) {
            $this->db->where($wherearray);
        }
        $this->db->order_by('prescriptionID', 'desc');

        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public  function get_select_appointment_patient($select = '*', $wherearray = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = prescription.patientID');
        if(inicompute($wherearray)) {
            $this->db->where($wherearray);
        }
        $this->db->order_by('prescription.prescriptionID', 'desc');

        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }
}