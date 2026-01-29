<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medicinesale_m extends MY_Model
{
    protected $_table_name      = 'medicinesale';
    protected $_primary_key     = 'medicinesaleID';
    protected $_primary_filter  = 'intval';
    protected $_order_by        = "medicinesaleID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_medicinesale($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_select_medicinesale($select = NULL, $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_order_by_medicinesale($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_medicinesale($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_medicinesale($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_medicinesale($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_medicinesale($id)
    {
        return parent::delete($id);
    }

    public function get_medicinesale_for_report($queryArray)
    {
        $this->db->select('*');
        $this->db->from('medicinesale');

        if(isset($queryArray['patient_type'])) {
            $this->db->where('medicinesale.patient_type', $queryArray['patient_type']);
        }
        if(isset($queryArray['uhid']) && $queryArray['uhid'] != 0) {
            $this->db->where('medicinesale.uhid', $queryArray['uhid']);
        }
        if(isset($queryArray['statusID']) && $queryArray['statusID'] != 0) {
            if($queryArray['statusID'] == 1) {
                $this->db->where('medicinesale.medicinesalestatus', 0);
                $this->db->where('medicinesale.medicinesalerefund', 0);
            } elseif($queryArray['status'] == 2) {
                $this->db->where('medicinesale.medicinesalestatus', 1);
                $this->db->where('medicinesale.medicinesalerefund', 0);
            } elseif($queryArray['status'] == 3) {
                $this->db->where('medicinesale.medicinesalestatus', 2);
                $this->db->where('medicinesale.medicinesalerefund', 0);
            } elseif($queryArray['status'] == 4) {
                $this->db->where('medicinesale.medicinesalerefund', 1);
            }
        } else {
            $this->db->where('medicinesale.medicinesalerefund', 0);
        }

        if((isset($queryArray['from_date']) && $queryArray['from_date'] != 0) && (isset($queryArray['to_date']) && $queryArray['to_date'] != 0)) {
            $from_date = date('Y-m-d', strtotime($queryArray['from_date']));
            $to_date = date('Y-m-d', strtotime($queryArray['to_date']));
            $this->db->where('medicinesaledate >=', $from_date);
            $this->db->where('medicinesaledate <=', $to_date);
        }
        $this->db->order_by('medicinesaleID DESC');
        return $this->db->get()->result();
    }

}