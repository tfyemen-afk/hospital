<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Razorpay extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_m');
        $this->load->model('appointment_m');
        $this->load->model('payment_gateway_m');
        $this->load->model('payment_gateway_option_m');

        $language = $this->session->userdata('lang');
        $this->lang->load('razorpay', $language);
    }

    public function index($appointmentID = 0)
    {
        $appointment = $this->appointment_m->get_single_appointment(['appointmentID' => $appointmentID]);
        if (inicompute($appointment)) {
            $this->data['surl'] = base_url('razorpay/success/' . $appointmentID);
            $this->data['furl'] = base_url('razorpay/fail/' . $appointmentID);

            $payment_gateway = $this->payment_gateway_m->get_single_payment_gateway(['slug' => 'razorpay', 'status' => 1]);
            if (inicompute($payment_gateway)) {
                $payment_gateway_option = pluck($this->payment_gateway_option_m->get_order_by_payment_gateway_option(['payment_gateway_id' => $payment_gateway->id]), 'payment_value', 'payment_option');

                $this->data['user'] = $this->user_m->get_single_user(['delete_at' => 0, 'userID' => $this->session->userdata('loginuserID')]);

                $this->data['payment_gateway']        = $payment_gateway;
                $this->data['payment_gateway_option'] = $payment_gateway_option;
                $this->data['appointment']            = $appointment;

                $this->data["subview"] = 'appointment/razorpay';
                $this->load->view('_layout_main', $this->data);
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    // initialized cURL Request
    private function get_curl_handle($payment_id, $amount)
    {
        $payment_gateway = $this->payment_gateway_m->get_single_payment_gateway(['slug' => 'razorpay', 'status' => 1]);
        if (inicompute($payment_gateway)) {
            $payment_gateway_option = pluck($this->payment_gateway_option_m->get_order_by_payment_gateway_option(['payment_gateway_id' => $payment_gateway->id]), 'payment_value', 'payment_option');

            if (!inicompute($payment_gateway_option)) {
                return false;
            }
        }

        $url           = 'https://api.razorpay.com/v1/payments/' . $payment_id . '/capture';
        $key_id        = $payment_gateway_option['razorpay_key'];
        $key_secret    = $payment_gateway_option['razorpay_secret'];
        $fields_string = "amount=$amount";
        //cURL Request
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, $key_id . ':' . $key_secret);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        return $ch;
    }

    // callback method
    public function callback()
    {
        if (!empty($this->input->post('razorpay_payment_id')) && !empty($this->input->post('merchant_order_id'))) {

            $razorpay_payment_id = $this->input->post('razorpay_payment_id');
            $merchant_order_id   = $this->input->post('merchant_order_id');
            $amount              = $this->input->post('merchant_total');
            $success             = false;
            $error               = '';

            try {
                $ch = $this->get_curl_handle($razorpay_payment_id, $amount);
                //execute post
                $result      = curl_exec($ch);
                $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if ($result === false) {
                    $success = false;
                    $error   = 'Curl error: ' . curl_error($ch);
                } else {
                    $response_array = json_decode($result, true);
                    //Check success response
                    if ($http_status === 200 and isset($response_array['error']) === false) {
                        $success = true;
                    } else {
                        $success = false;
                        if (!empty($response_array['error']['code'])) {
                            $error = $response_array['error']['code'] . ':' . $response_array['error']['description'];
                        } else {
                            $error = 'RAZORPAY_ERROR:Invalid Response <br/>' . $result;
                        }
                    }
                }
                //close connection
                curl_close($ch);
            } catch (Exception $e) {
                $success = false;
                $error   = 'OPENCART_ERROR:Request to Razorpay Failed';
            }

            if ($success === true) {
                if (!empty($this->session->userdata('ci_subscription_keys'))) {
                    $this->session->unset_userdata('ci_subscription_keys');
                }
                redirect($this->input->post('merchant_surl_id'));
            } else {
                redirect($this->input->post('merchant_furl_id'));
            }
        } else {
            $this->session->set_flashdata('error', 'An error occured. Contact site administrator, please!');
            redirect(site_url('appointment/index'));
        }
    }

    public function success($appointmentID)
    {
        $payment_gateways = pluck($this->payment_gateway_m->get_payment_gateway(), 'id', 'slug');

        $appointmentUpArray['paymentstatus']   = 1;
        $appointmentUpArray['paymentmethodID'] = isset($payment_gateways['razorpay']) ? $payment_gateways['razorpay'] : 0;
        $appointmentUpArray['paymentdate']     = date("Y-m-d H:i:s");

        $this->appointment_m->update_appointment($appointmentUpArray, $appointmentID);

        $this->session->set_flashdata('success', 'The appointment payment successfully paid.');
        redirect(site_url('appointment/index'));
    }

    public function fail($appointmentID)
    {
        $this->session->set_flashdata('error', 'The appointment payment fail.');
        redirect(site_url('appointment/index'));
    }
}
