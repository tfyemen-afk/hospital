<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Operationtheatre_m extends MY_Model 
{
    protected $_table_name = 'operationtheatre';
    protected $_primary_key = 'operationtheatreID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "operationtheatreID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_operationtheatre($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_operationtheatre($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_operationtheatre($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_operationtheatre($array) 
    {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_operationtheatre($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_operationtheatre($id)
    {
        parent::delete($id);
    }

    public function order($orderBy)
    {
        parent::order($orderBy);
    }

    public function get_select_operationtheatre_patient($select = '*', $array = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from('operationtheatre');
        $this->db->join('patient','patient.patientID=operationtheatre.patientID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by('operationtheatre.operation_date');
        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function get_patient_by_doctor($array)
    {
        $this->db->select('name,operationtheatre.patientID');
        $this->db->from('operationtheatre');
        $this->db->join('patient','patient.patientID=operationtheatre.patientID');
        if(isset($array['doctorID'])) {
            $this->db->where('operationtheatre.doctorID',$array['doctorID']);
        }
        return $this->db->get()->result();
    }

    public function get_order_by_operationtheatre_for_report($array)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        if(isset($array['doctorID']) && (int)$array['doctorID']) {
            $this->db->where('doctorID',$array['doctorID']);
            
            if(isset($array['patientID']) && (int)$array['patientID']) {
                $this->db->where('patientID',$array['patientID']);
            }
        }
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('operation_date >=',$array['from_date']);
            $this->db->where('operation_date <=',$array['to_date']);
        } elseif(isset($array['from_date'])) {
            $this->db->where('operation_date >=',$array['from_date']);
            $this->db->where('operation_date <=',$array['to_date']);
        }
        return $this->db->get()->result();
    }

}