<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Generalsettings_m extends MY_Model 
{
    protected $_table_name = 'generalsettings';
    protected $_primary_key = 'fieldoption';
    protected $_primary_filter = 'intval';
    protected $_order_by = "fieldoption asc";

    public function __construct() 
    {
        parent::__construct();
    }

    public function get_generalsettings() 
    {
        $compress = [];
        $query = $this->db->get('generalsettings');
        foreach ($query->result() as $row) {
            $compress[$row->fieldoption] = $row->value;
        }
        return (object) $compress;
    }

    public function get_generalsettings_array() 
    {
        $compress = [];
        $query = $this->db->get('generalsettings');
        foreach ($query->result() as $row) {
            $compress[$row->fieldoption] = $row->value;
        }
        return $compress;
    }

    public function get_generalsettings_where($data) 
    {
        $this->db->where('fieldoption', $data);
        $query = $this->db->get('generalsettings');
        return $query->row();
    }

    public function insertorupdate($arrays) 
    {
        if(inicompute($arrays)) {
            foreach ($arrays as $key => $array) {
                $this->db->query("INSERT INTO generalsettings (fieldoption, value) VALUES ('".$key."', '".$array."') ON DUPLICATE KEY UPDATE fieldoption='".$key."' , value='".$array."'");
            }
        }
        return TRUE;
    }

    public function delete_generalsettings($optionname)
    {
        $this->db->delete('generalsettings', array('fieldoption' => $optionname));
        return TRUE;
    }
}

