<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bill_m extends MY_Model {

    protected $_table_name = 'bill';
    protected $_primary_key = 'billID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "billID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_bill($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_select_bill($select = NULL, $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_order_by_bill($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_bill($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_bill($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_bill($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_bill($id)
    {
        parent::delete($id);
    }

    public function insert_batch_bill($array)
    {
        return parent::insert_batch($array);
    }

    public function update_batch_bill($array, $column)
    {
        return parent::update_batch($array, $column);
    }

    public function get_sum_bill($clmn, $array = [])
    {
        return parent::get_sum($clmn, $array);
    }

    public function get_select_bill_patient($select = '*', $array = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = bill.patientID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by('bill.billID desc');
        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function get_order_by_bill_for_report($queryArray) {
        $this->db->select('billlabel.name as  billlabelname,  billlabel.billcategoryID as billcategoryID, patient.name as patientname, billitems.create_date, billitems.status, billitems.discount, billitems.amount');
        $this->db->from('billitems');
        $this->db->join('billlabel', 'billitems.billlabelID=billlabel.billlabelID');
        $this->db->join('patient', 'patient.patientID=billitems.patientID');

        if(isset($queryArray['billcategoryID'])) {
            $this->db->where('billlabel.billcategoryID', $queryArray['billcategoryID']);
        }
        if(isset($queryArray['billlabelID'])) {
            $this->db->where('billitems.billlabelID', $queryArray['billlabelID']);
        }
        if(isset($queryArray['uhid'])) {
            $this->db->where('billitems.patientID', $queryArray['uhid']);
        }
        if(isset($queryArray['paymentstatus'])) {
            $this->db->where('billitems.status', $queryArray['paymentstatus']);
        }
        if(isset($queryArray['from_date']) && isset($queryArray['to_date'])) {
            $this->db->where('billitems.create_date >=',$queryArray['from_date']);
            $this->db->where('billitems.create_date <=',$queryArray['to_date']);
        } elseif(isset($queryArray['from_date'])) {
            $this->db->where('billitems.create_date >=',$queryArray['from_date']);
            $this->db->where('billitems.create_date <=',$queryArray['to_date']);
        }
        $this->db->where('billitems.delete_at', 0);
        $this->db->order_by('billitems.create_date DESC');
        return $this->db->get()->result();
    }

}