<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Updatechecker
{
    private function _dataMaker(&$array, $arrayMerge)
    {
        $data = [
            'purchase_code'     => '',
            'username'          => '',
            'email'             => '',
            'ip'                => $this->getUserIP(),
            'domain'            => $_SERVER['HTTP_HOST'],
            'purpose'           => 'update',
            'product_name'      => config_item('product_name'),
            'version'		    => config_item('iniversion'),
        ];

        if($arrayMerge) {
            $CI =& get_instance();
            $CI->load->model('generalsettings_m');
            $generalSetting = $CI->generalsettings_m->get_generalsettings();
            if (inicompute($generalSetting)) {
                $data['purchase_code'] = $generalSetting->purchase_code;
                $data['username'] = $generalSetting->purchase_username;
                $data['email'] = $generalSetting->email;
            }
        }
        $array = array_merge($data, $array);
    }

    public function verifyValidUser($array = [], $arrayMerge = true)
    {
        $this->_dataMaker($array, $arrayMerge);
        $apiCurl = $this->_apiCurl($array);
        return $apiCurl;
    }

    private function _apiCurl($data, $url = NULL)
    {
        if(is_null($url)) {
            $url = $this->_activeServer();
        }

        if(!$url) {
            return (object) array(
                'status'    => false,
                'message'   => 'Server Down',
                'for'       => 'purchasecode',
            );
        }

        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)]
        );
        $result = curl_exec($ch);
        if(inicompute($result)) {
            $jsonData = json_decode($result);
            return $jsonData;
        } else {
            return (object) array(
                'status'    => false,
                'message'   => 'Request can not found',
                'for'       => 'purchasecode',
            );
        }
    }

    private function _activeServer()
    {
        $allDomain = config_item('installDomain');
        if(inicompute($allDomain)) {
            foreach ($allDomain as $domainKey => $domain) {
                $url = parse_url($domain);
                if($this->checkInternetConnection($url['host'])) {
                    return $domain.'/api/check';
                }
            }
        }
        return FALSE;
    }

    public function checkInternetConnection($sCheckHost = 'www.google.com')
    {
        return (bool) @fsockopen($sCheckHost, 80, $iErrno, $sErrStr, 30);
    }

    public function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = ($remote == "::1" ? "127.0.0.1" : $remote) ;
        }

        return $ip;
    }

    public function getBrowser()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $bname = 'Unknown';
        $platform = 'Unknown';

        //First get the platform?
        if (preg_match('/linux/i', $u_agent)) {
            $platform = 'linux';
        } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
            $platform = 'mac';
        } elseif (preg_match('/windows|win32/i', $u_agent)) {
            $platform = 'windows';
        }

        // Next get the name of the useragent yes seperately and for good reason
        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        } elseif(preg_match('/Firefox/i',$u_agent)) {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        } elseif(preg_match('/Chrome/i',$u_agent)) {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        } elseif(preg_match('/Safari/i',$u_agent)) {
            $bname = 'Apple Safari';
            $ub = "Safari";
        } elseif(preg_match('/Opera/i',$u_agent)) {
            $bname = 'Opera';
            $ub = "Opera";
        } elseif(preg_match('/Netscape/i',$u_agent)) {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // finally get the correct version number
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // see how many we have
        $i = inicompute($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            } else {
                $version= $matches['version'][1];
            }
        } else {
            $version= $matches['version'][0];
        }

        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent'     => $u_agent,
            'name'          => $bname,
            'version'       => $version,
            'platform'      => $platform,
            'pattern'       => $pattern
        );
    }
}