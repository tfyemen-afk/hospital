<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Discharge_m extends MY_Model
{
    protected $_table_name = 'discharge';
    protected $_primary_key = 'dischargeID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "dischargeID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_discharge($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_select_discharge($select = '*', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }


    public function get_order_by_discharge($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_discharge($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_discharge($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_discharge($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_discharge($id)
    {
        parent::delete($id);
    }

    public function get_select_discharge_patient($select = '*', $wherearray = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = discharge.patientID');
        if(inicompute($wherearray)) {
            $this->db->where($wherearray);
        }

        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function get_order_by_discharge_for_report($array) {
        $this->db->select('discharge.conditionofdischarge, discharge.date, patient.patientID, patient.name');
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID=discharge.patientID');
        if(isset($array['conditionofdischarge']) && (int)$array['conditionofdischarge']) {
            $this->db->where('conditionofdischarge', $array['conditionofdischarge']);
        }
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('date >=',$array['from_date']);
            $this->db->where('date <=',$array['to_date']);
        } elseif(isset($array['from_date'])) {
            $this->db->where('date >=',$array['from_date']);
            $this->db->where('date <=',$array['to_date']);
        }
        return $this->db->get()->result();
    }
}