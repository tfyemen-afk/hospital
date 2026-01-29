<?php

class Payment
{
    protected $CI;
    protected $data;

    public function __construct($array = [])
    {
        $this->CI = &get_instance();
    }

    public function __call($method, $args)
    {
        $arguments = array_merge([$method], $args);
        return call_user_func_array([$this, 'gateway'], $arguments);
    }

    public function gateway()
    {
        $args    = func_get_args();

        $gateway = '';
        if (count($args) > 0) {
            $gateway = array_shift($args);
        }

        if (count($args) == 0) {
            $args = null;
        }
        $gateway = strtolower($gateway);

        if ($gateway) {
            $this->CI->load->library($gateway, $args[0]);
            return $this->CI->$gateway->pay();
        }
    }
}
