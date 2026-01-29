<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page_m extends MY_Model
{
    protected $_table_name = 'page';
    protected $_primary_key = 'pageID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "pageID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_page($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_page($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_page($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_page($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_page($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_page($id)
    {
        parent::delete($id);
    }

    public function get_one($fmenu)
    {
        if(inicompute($fmenu)) {
            $this->db->select('*');
            $this->db->from('fmenu_relation');
            $this->db->where('fmenuID',  $fmenu->fmenuID);
            $query = $this->db->get();
            return $query->row();
        }
    }
}