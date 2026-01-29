<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admission_m extends MY_Model 
{
    protected $_table_name = 'admission';
    protected $_primary_key = 'admissionID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "admissionID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function order($order) 
    {
        parent::order($order);
    }

    public function get_select_admission($select = 'admissionID', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_admission($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_admission($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_admission($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_admission($array) 
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_admission($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_admission($id)
    {
        parent::delete($id);
    }

    public function get_admission_patient($wherearray = [])
    {
        $this->db->select('patient.patientID as ppatientID,name');
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = admission.patientID');
        if(inicompute($wherearray)) {
            $this->db->where($wherearray);
        }
        return $this->db->get()->result();
    }

    public function get_select_admission_patient($select = '*', $wherearray = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = admission.patientID');
        if(inicompute($wherearray)) {
            $this->db->where($wherearray);
        }
        $this->db->order_by($this->_order_by);
        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function get_admission_for_report($queryArray)
    {
        $this->db->select('*');
        $this->db->from('admission');

        if(isset($queryArray['doctorID']) && $queryArray['doctorID']) {
            $this->db->where('doctorID', $queryArray['doctorID']);
        }
        if(isset($queryArray['patientID']) && $queryArray['patientID']) {
            $this->db->where('patientID', $queryArray['patientID']);
        }

        if(isset($queryArray['wardID']) && $queryArray['wardID']) {
            $this->db->where('wardID', $queryArray['wardID']);
            if(isset($queryArray['bedID']) && $queryArray['bedID']) {
                $this->db->where('bedID', $queryArray['bedID']);
            }
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
            $this->db->where('admissiondate >=', $from_date);
            $this->db->where('admissiondate <=', $to_date);
        } elseif((isset($queryArray['from_date']) && $queryArray['from_date'] != 0)) {
            $from_date  = $queryArray['from_date'];
            $this->db->where('DATE(admissiondate)', $from_date);
        }
        $this->db->order_by('admissionID DESC');
        return $this->db->get()->result();
    }

    public function get_admission_for_tpareport($queryArray)
    {
        $this->db->select('tpa.name as tpaname, patient.name as patientname, admission.admissiondate');
        $this->db->from('admission');
        $this->db->join('patient', 'patient.patientID=admission.patientID');
        $this->db->join('tpa', 'tpa.tpaID=admission.tpaID');
        if(isset($queryArray['tpaID']) && (int)$queryArray['tpaID']) {
            $this->db->where('admission.tpaID', $queryArray['tpaID']);
        }
        $this->db->where('admission.tpaID !=', 0);

        if(isset($queryArray['from_date']) && isset($queryArray['to_date'])) {
            $this->db->where('admissiondate >=',$queryArray['from_date']);
            $this->db->where('admissiondate <=',$queryArray['to_date']);
        } elseif(isset($queryArray['from_date'])) {
            $this->db->where('admissiondate >=',$queryArray['from_date']);
            $this->db->where('admissiondate <=',$queryArray['to_date']);
        }
        $this->db->order_by('admissionID DESC');
        return $this->db->get()->result();
    }

}