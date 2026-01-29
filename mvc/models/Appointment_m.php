<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appointment_m extends MY_Model 
{
    protected $_table_name          = 'appointment';
    protected $_primary_key         = 'appointmentID';
    protected $_primary_filter      = 'intval';
    protected $_order_by            = "appointmentID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function order($order) 
    {
        parent::order($order);
    }

    public function get_select_appointment($select = 'appointmentID', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_appointment($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_appointment($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_appointment($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function get_single_appointment_date($date, $array) 
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->like('appointmentdate', $date);
        $this->db->where($array);
        return $this->db->get()->row();
    }

    public function insert_appointment($array) 
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_appointment($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_appointment($id)
    {
        parent::delete($id);
    }

    public function get_appointment_patient($wherearray)
    {
        $this->db->select('patient.patientID as ppatientID,name');
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = appointment.patientID');
        $this->db->where($wherearray);
        return $this->db->get()->result();
    }

    public function get_select_appointment_patient($select = '*', $wherearray = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = appointment.patientID');
        if(inicompute($wherearray)) {
            $this->db->where($wherearray);
        }

        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function get_appointment_for_report($queryArray)
    {
        $this->db->select('*');
        $this->db->from('appointment');

        if(isset($queryArray['doctorID']) && $queryArray['doctorID']) {
            $this->db->where('doctorID', $queryArray['doctorID']);
        }
        if(isset($queryArray['patientID']) && $queryArray['patientID']) {
            $this->db->where('patientID', $queryArray['patientID']);
        }
        if(isset($queryArray['casualty'])) {
            $this->db->where('casualty', $queryArray['casualty']);
        }
        if(isset($queryArray['payment']) && $queryArray['payment']) {
            $this->db->where('paymentstatus', $queryArray['payment']);
        }
        if(isset($queryArray['status'])) {
            $this->db->where('status', $queryArray['status']);
        }

        if((isset($queryArray['from_date']) && $queryArray['from_date'] != 0) && (isset($queryArray['to_date']) && $queryArray['to_date'] != 0)) {
            $from_date  = date('Y-m-d', strtotime($queryArray['from_date']))." 00:00:00";
            $to_date    = date('Y-m-d', strtotime($queryArray['to_date']))." 23:59:59";
            $this->db->where('appointmentdate >=', $from_date);
            $this->db->where('appointmentdate <=', $to_date);
        } elseif((isset($queryArray['from_date']) && $queryArray['from_date'] != 0)) {
            $from_date  = $queryArray['from_date'];
            $this->db->where('DATE(appointmentdate)', $from_date);
        }

        $this->db->order_by('appointmentID DESC');
        return $this->db->get()->result();
    }

    public function get_appointment_for_tpareport($queryArray)
    {
        $this->db->select('tpa.name as tpaname, patient.name as patientname, appointment.appointmentdate');
        $this->db->from('appointment');
        $this->db->join('patient', 'patient.patientID=appointment.patientID');
        $this->db->join('tpa', 'tpa.tpaID=appointment.tpaID');
        if(isset($queryArray['tpaID']) && (int)$queryArray['tpaID']) {
            $this->db->where('appointment.tpaID', $queryArray['tpaID']);
        }
        $this->db->where('appointment.tpaID !=', 0);

        if(isset($queryArray['from_date']) && isset($queryArray['to_date'])) {
            $this->db->where('appointmentdate >=',$queryArray['from_date']);
            $this->db->where('appointmentdate <=',$queryArray['to_date']);
        } elseif(isset($queryArray['from_date'])) {
            $this->db->where('appointmentdate >=',$queryArray['from_date']);
            $this->db->where('appointmentdate <=',$queryArray['to_date']);
        }

        $this->db->order_by('appointmentID DESC');
        return $this->db->get()->result();
    }
}