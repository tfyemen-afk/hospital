<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Heightweightbp_m extends MY_Model 
{
    protected $_table_name = 'heightweightbp';
    protected $_primary_key = 'heightweightbpID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "heightweightbpID desc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function order($order)
    {
        parent::order($order);
    }

    public function get_select_heightweightbp($select = 'heightweightbpID', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_heightweightbp($array=NULL, $signal=FALSE) 
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_heightweightbp($array=NULL) 
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_heightweightbp($array) 
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_heightweightbp($array) 
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_heightweightbp($data, $id = NULL) 
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_heightweightbp($id)
    {
        parent::delete($id);
    }

    public function get_select_heightweightbp_with_patient($select = 'heightweightbp.heightweightbpID', $array = [], $single = false)
    {
        $this->db->select($select);
        $this->db->from($this->_table_name);
        $this->db->join('patient', 'patient.patientID = heightweightbp.patientID');
        if(inicompute($array)) {
            $this->db->where($array);
        }
        $this->db->order_by('heightweightbp.date desc');
        if($single) {
            return $this->db->get()->row();
        } else {
            return $this->db->get()->result();
        }
    }
}