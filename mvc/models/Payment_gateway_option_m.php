<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payment_gateway_option_m extends MY_Model
{
    protected $_table_name     = 'payment_gateway_option';
    protected $_primary_key    = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by       = "id asc";

    public function __construct()
    {
        parent::__construct();
    }

    public function get_payment_gateway_option($array = null, $signal = false)
    {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_payment_gateway_option($array = null)
    {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_payment_gateway_option($array)
    {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_payment_gateway_option($array)
    {
        parent::insert($array);
        return true;
    }

    public function update_payment_gateway_option($data, $id = null)
    {
        parent::update($data, $id);
        return $id;
    }

    public function update_batch_payment_gateway_option($array, $id)
    {
        parent::update_batch($array, $id);
        return $id;
    }

    public function delete_payment_gateway_option($id)
    {
        parent::delete($id);
    }

    public function insertorupdate($arrays)
    {
        foreach ($arrays as $key => $array) {
            $this->db->query("INSERT INTO payment_gateway_option (payment_option, payment_value) VALUES ('" . $key . "', '" . $array . "') ON DUPLICATE KEY UPDATE payment_option='" . $key . "' , payment_value='" . $array . "'");
        }
        return true;
    }

}
