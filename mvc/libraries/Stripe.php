<?php

require_once 'stripe-php/init.php';
use Stripe\Charge as StripeCharge;
use Stripe\Stripe as StripeStripe;

class Stripe
{
    protected $CI;
    protected $data;

    public function __construct($array = [])
    {
        $this->CI = &get_instance();
        $this->CI->load->model('appointment_m');
        $this->CI->load->model('payment_gateway_option_m');
        $this->data = $array;
    }

    public function pay()
    {

        $options = pluck($this->CI->payment_gateway_option_m->get_payment_gateway_option(), 'obj', 'payment_option');

        $stripe_secret = isset($options['stripe_secret']) ? $options['stripe_secret']->payment_value : '';
        $currency_code = $this->data['currency_code'];
        $amount        = $this->data['amount'];
        $stripeToken   = $this->data['stripeToken'];
        $appointmentID = $this->data['appointmentID'];

        StripeStripe::setApiKey($stripe_secret);
        StripeStripe::setVerifySslCerts(false);

        $retArray['status']  = false;
        $retArray['message'] = '';
        try {
            $charge = StripeCharge::create([
                "amount"      => $amount,
                "currency"    => $currency_code,
                "source"      => $stripeToken,
                "description" => "The patient appointment payment via stripe payment gateway.",
            ]);

            if ($charge->status == 'succeeded') {
                $retArray['status'] = true;
                $retArray['amount'] = $amount;

                $appointmentUpArray['paymentstatus']   = 1;
                $appointmentUpArray['paymentmethodID'] = isset($options['stripe_secret']) ? $options['stripe_secret']->payment_gateway_id : 0;
                $appointmentUpArray['paymentdate']     = date("Y-m-d H:i:s");
                $this->CI->appointment_m->update_appointment($appointmentUpArray, $appointmentID);
            }
        } catch (\Exception $e) {
            $retArray['message'] = $e->getMessage();
        }
        return $retArray;
    }
}
