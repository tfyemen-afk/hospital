<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Makepayment_m extends MY_Model
{
    protected $_table_name = 'makepayment';
    protected $_primary_key = 'makepaymentID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "makepaymentID asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_makepayment($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_makepayment($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_makepayment($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_makepayment($array)
    {
        $error = parent::insert($array);
        return true;
    }

    public function update_makepayment($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_makepayment($id)
    {
        parent::delete($id);
    }

    public function get_all_salary_for_report($queryArray) {
        $this->db->select('*');
        $this->db->from('makepayment');
        if(isset($queryArray['roleID']) && (int)$queryArray['roleID']) {
            $this->db->where('roleID', $queryArray['roleID']);
            if(isset($queryArray['userID']) && (int)$queryArray['userID']) {
                $this->db->where('userID', $queryArray['userID']);
            }
        }

        if((isset($queryArray['from_date']) && $queryArray['from_date'] != '') && (isset($queryArray['to_date']) && $queryArray['to_date'] != '')) {
            $from_date = date('Y-m-d',strtotime($queryArray['from_date']))." 00:00:00";
            $to_date   = date('Y-m-d',strtotime($queryArray['to_date']))." 23:59:59";

            $this->db->where('create_date >=', $from_date);
            $this->db->where('create_date <=', $to_date);
        }

        if((isset($queryArray['month']) && $queryArray['month'] != '')) {
            $this->db->where('month',$queryArray['month']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_makepayment_amount_order_by_date($array) {
        $this->db->select_sum('payment_amount');
        $this->db->from($this->_table_name);
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('create_date >=',$array['from_date']);
            $this->db->where('create_date <=',$array['to_date']);
        }
        return $this->db->get()->row();
    }

    public function get_order_by_makepayment_for_report($array) {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        if(isset($array['from_date']) && isset($array['to_date'])) {
            $this->db->where('create_date >=',$array['from_date']);
            $this->db->where('create_date <=',$array['to_date']);
        }
        return $this->db->get()->result();
    }

}