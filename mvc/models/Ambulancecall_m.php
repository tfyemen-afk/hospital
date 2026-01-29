<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ambulancecall_m extends MY_Model 
{
    protected $_table_name          = 'ambulancecall';
    protected $_primary_key         = 'ambulancecallID';
    protected $_primary_filter      = 'intval';
    protected $_order_by            = "ambulancecallID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_ambulancecall($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_ambulancecall($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_ambulancecall($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_ambulancecall($array) 
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_ambulancecall($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_ambulancecall($id)
    {
        parent::delete($id);
    }

    public function order($orderBy)
    {
        parent::order($orderBy);
    }

    public function get_select_ambulancecall($select = 'ambulancecallID, name', $array=[], $single = false)
    {
        $query = parent::get_select($select, $array, $single);
        return $query;
    }

    public function get_ambulancecall_for_report($queryArray)
    {
        $this->db->select('*');
        $this->db->from('ambulancecall');

        if(isset($queryArray['ambulanceID']) && (int)$queryArray['ambulanceID']) {
            $this->db->where('ambulanceID', $queryArray['ambulanceID']);
        }
        if((isset($queryArray['from_date']) && $queryArray['from_date'] != 0) && (isset($queryArray['to_date']) && $queryArray['to_date'] != 0)) {
            $from_date = date('Y-m-d',strtotime($queryArray['from_date']))." 00:00:00";
            $to_date   = date('Y-m-d',strtotime($queryArray['to_date']))." 23:59:59";
            $this->db->where('date >=', $from_date);
            $this->db->where('date <=', $to_date);
        }
        $this->db->order_by('ambulancecallID DESC');
        return $this->db->get()->result();
    }

}