<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ward_m extends MY_Model
{
	protected $_table_name          = 'ward';
	protected $_primary_key         = 'wardID';
	protected $_primary_filter      = 'intval';
	protected $_order_by            = "wardID desc";

	public function __construct()
    {
		parent::__construct();
	}

	public function order($order)
    {
        parent::order($order);
    }

    public function get_select_ward($select = 'wardID', $array = [], $single = false)
    {
        return parent::get_select($select, $array, $single);
    }

    public function get_ward($array=NULL, $signal=FALSE)
    {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_ward($array)
    {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_ward($array=NULL)
    {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_ward($array)
    {
		parent::insert($array);
		return TRUE;
	}

	public function update_ward($data, $id = NULL)
    {
		parent::update($data, $id);
		return $id;
	}

	public function delete_ward($id)
    {
		parent::delete($id);
	}

	public function get_ward_with_room()
    {
        $this->db->select('ward.wardID, ward.name, room.name as rname');
        $this->db->from('ward');
        $this->db->join('room', 'ward.roomID = room.roomID');
        $this->db->order_by($this->_order_by);
        return $this->db->get()->result();
    }
}