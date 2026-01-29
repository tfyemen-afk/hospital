<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post_m extends MY_Model
{
    protected $_table_name = 'post';
    protected $_primary_key = 'postID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "postID desc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_post($array=NULL, $signal=FALSE)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_post($array=NULL)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_post($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_post($array)
    {
        parent::insert($array);
        return TRUE;
    }

    public function update_post($data, $id = NULL)
    {
        parent::update($data, $id);
        return $id;
    }

    public function delete_post($id)
    {
        parent::delete($id);
    }
}