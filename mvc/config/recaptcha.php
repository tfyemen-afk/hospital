<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI =& get_instance();
$CI->load->database();
$CI->load->model('generalsettings_m');
$generalSetting = $CI->generalsettings_m->get_generalsettings();


// To use reCAPTCHA, you need to sign up for an API key pair for your site.
// link: http://www.google.com/recaptcha/admin
if($generalSetting->captcha_status) {
    $config['recaptcha_site_key'] = $generalSetting->recaptcha_site_key;
    $config['recaptcha_secret_key'] = $generalSetting->recaptcha_secret_key;

    // reCAPTCHA supported 40+ languages listed here:
    // https://developers.google.com/recaptcha/docs/language
    $config['recaptcha_lang'] = 'en';
}
