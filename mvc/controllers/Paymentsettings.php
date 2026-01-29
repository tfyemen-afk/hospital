<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Paymentsettings extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payment_gateway_m');
        $this->load->model('payment_gateway_option_m');

        $language = $this->session->userdata('lang');
        $this->lang->load('paymentsettings', $language);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js'  => array(
                'assets/select2/select2.js',
            ),
        );

        $this->data['payment_gateways'] = $this->payment_gateway_m->get_payment_gateway();
        $gateway_options                = $this->payment_gateway_option_m->get_payment_gateway_option();

        $this->data['payment_gateway_options'] = pluck_multi_array($gateway_options, 'obj', 'payment_gateway_id');


        if (inicompute($this->data['payment_gateways'])) {
            if ($_POST) {
                $rules = $this->rules($this->data['payment_gateways']);
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == false) {
                    $this->data["subview"] = "paymentsettings/index";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $gateway_options    = pluck($gateway_options, 'obj', 'payment_option');
                    $array              = [];
                    $j                  = 0;

                    for ($i = 0; $i < inicompute($rules); $i++) {
                        $key = $rules[$i]['field'];

                        if ($gateway_options[$key]) {
                            $array[$j]['id']                 = $gateway_options[$key]->id;
                            $array[$j]['payment_gateway_id'] = $gateway_options[$key]->payment_gateway_id;
                            $array[$j]['payment_option']     = $gateway_options[$key]->payment_option;
                            $array[$j]['payment_value']      = $this->input->post($key);
                            $j++;
                        }
                    }
                    $this->payment_gateway_option_m->update_batch_payment_gateway_option($array, 'id');

                    if(inicompute($this->data['payment_gateways'])) {
                        foreach($this->data['payment_gateways'] as $payment_gateway) {
                            if(!is_null($this->input->post($payment_gateway->slug.'_status'))) {
                                $this->payment_gateway_m->update_payment_gateway(['status' => $this->input->post($payment_gateway->slug.'_status')], $payment_gateway->id);
                            }
                        }
                    }

                    $this->session->set_flashdata('success', "Success");
                    redirect(site_url("paymentsettings/index"));
                }
            } else {
                $this->data["subview"] = "paymentsettings/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    protected function rules($paymentGateways)
    {
        if(inicompute($paymentGateways)) {
            foreach($paymentGateways as $paymentGateway) {
                if(isset($_POST[$paymentGateway->slug.'_status'])) {
                    $library = $paymentGateway->name.'Rule';
                    $gateway = new $library();
                    return $gateway->rule();
                }
            }
        }
    }
}
