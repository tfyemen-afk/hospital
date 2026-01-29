<?php

class Razorpay
{
    protected $CI;
    protected $data;

    public function __construct($array = [])
    {
        $this->CI   = &get_instance();
        $this->data = $array;
    }

    public function pay()
    {
        $appointmentID = $this->data['appointmentID'];
        redirect(site_url('razorpay/index/' . $appointmentID));
    }
}
