<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("notification_m");
    }

    public function index()
    {
        $type   = htmlentities(escapeString($this->uri->segment(3)));
        $id     = htmlentities(escapeString($this->uri->segment(4)));
        if($type && (int)$id) {
            if($type == 'notice') {
                if(permissionChecker('notice_view')) {
                    $this->notification_m->insert_notification(['itemID' => $id, 'userID' => $this->session->userdata('loginuserID'), 'itemname' => 'notice']);
                    redirect(site_url('notice/view/'.$id.'/4'));
                } else {
                    $this->session->set_flashdata('error', 'Permission deny');
                    redirect(site_url('dashboard'));
                }
            } elseif($type == 'event') {
                if(permissionChecker('event_view')) {
                    $this->notification_m->insert_notification(['itemID' => $id, 'userID' => $this->session->userdata('loginuserID'), 'itemname' => 'event']);
                    redirect(site_url('event/view/'.$id.'/4'));
                } else {
                    $this->session->set_flashdata('error', 'Permission deny');
                    redirect(site_url('dashboard'));
                }
            }
        }
        redirect(site_url('dashboard'));
    }
}