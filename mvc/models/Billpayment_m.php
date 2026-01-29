<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Billpayment_m extends MY_Model
{
    protected $_table_name      = 'billpayment';
    protected $_primary_key     = 'billpaymentID';
    protected $_primary_filter  = 'intval';
    protected $_order_by        = "billpaymentID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_billpayment($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_select_billpayment($select = NULL, $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_order_by_billpayment($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_billpayment($array) {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_billpayment($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_billpayment($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_billpayment($id)
    {
        parent::delete($id);
    }

    public function get_sum_billpayment($clmn, $array = [])
    {
        return parent::get_sum($clmn, $array);
    }

    public function get_order_by_billpayment_patient($select = '*', $array = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = billpayment.patientID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by($this->_order_by);
        if($single) {
            return $this->db->get()->row();
        }
        return $this->db->get()->result();
    }

    public function get_billpayment_amount_order_by_date($array)
    {
        $this->db->select_sum('paymentamount');
        $this->db->from($this->_table_name);
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('paymentdate >=',$array['from_date']);
            $this->db->where('paymentdate <=',$array['to_date']);
        }
        $this->db->where('delete_at',0);
        return $this->db->get()->row();
    }

    public function get_order_by_billpayment_for_report($array)
    {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('paymentdate >=',$array['from_date']);
            $this->db->where('paymentdate <=',$array['to_date']);
        }
        $this->db->where('delete_at',0);
        return $this->db->get()->result();
    }
}