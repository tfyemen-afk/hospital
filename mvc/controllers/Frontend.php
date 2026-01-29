<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Frontend extends Frontend_Controller
{
    protected $_pageName;
    protected $_templateName;
    protected $_homepage;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('page_m');
        $this->load->model('media_gallery_m');
        $this->load->model('slider_m');
        $this->load->library('mail');
    }

    public function index()
    {
        $type   = htmlentities(escapeString($this->uri->segment(3)));
        $url    = htmlentities(escapeString($this->uri->segment(4)));
        if($type && $url) {
            redirect(site_url('frontend/'.$type.'/'.$url));
        } else {
            if(inicompute($this->data['homepage'])) {
                if(isset($this->data['homepage']->pageID)) {
                    $this->page($this->data['homepage']->url);
                } elseif(isset($this->data['homepage']->postID)) {
                    $this->post($this->data['homepage']->url);
                } else {
                    redirect(site_url('frontend/home'));
                }
            } else {
                redirect(site_url('frontend/home'));
            }
        }
    }

    public function home()
    {
        $this->bladeView->render('views/templates/homeempty');
    }

    public function page($url)
    {
        if($url) {
            if($url == 'login') {
                redirect(site_url('signin/index'));
            }

            $page = $this->page_m->get_single_page(array('url' => $url));
            $featured_image = [];
            if(inicompute($page)) {
                if(!empty($page->featured_image)) {
                    $featured_image = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $page->featured_image));
                }

                $sliders = $this->slider_m->get_slider_join_with_media_gallery($page->pageID);
                $this->_pageName = $page->title;
                $this->_templateName = $page->template;
                if($page->template == 'none') {
                    $this->bladeView->render('views/templates/none', compact('page', 'featured_image', 'sliders'));
                } elseif($page->template == 'blog') {
                    $posts = $this->post_m->get_order_by_post(array('status' => 1));
                    $featured_image = [];
                    if(inicompute($posts)) {
                        $featured_image = pluck($this->media_gallery_m->get_order_by_media_gallery(array('media_gallery_type' => 1)), 'obj', 'media_galleryID');
                    }
                    $this->bladeView->render('views/templates/'.$this->_templateName, compact('page', 'posts', 'featured_image', 'sliders'));
                } else {
                    $this->bladeView->render('views/templates/'.$this->_templateName, compact('page', 'featured_image', 'sliders'));
                }
            } else {
                $this->_templateName = 'page404';
                $this->bladeView->render('views/templates/'.$this->_templateName);
            }
        } else {
            $this->_templateName = 'page404';
            $this->bladeView->render('views/templates/'.$this->_templateName);
        }
    }

    public function post($url)
    {
        if($url) {
            if($url == 'login') {
                redirect(site_url('signin/index'));
            }

            $post = $this->post_m->get_single_post(array('url' => $url));
            $featured_image = [];
            if(inicompute($post)) {
                $this->load->model('user_m');
                $user = $this->user_m->get_single_user(['userID' => $post->create_userID]);
                $posts = $this->post_m->get_order_by_post(array('status' => 1));
                if(!empty($post->featured_image)) {
                    $featured_image = $this->media_gallery_m->get_single_media_gallery(array('media_galleryID' => $post->featured_image));
                }

                $this->_pageName = $post->title;
                $this->_templateName = 'postnone';

                $this->bladeView->render('views/templates/'.$this->_templateName, compact('post', 'user', 'posts', 'featured_image'));
            } else {
                $this->_templateName = 'page404';
                $this->bladeView->render('views/templates/'.$this->_templateName);
            }
        } else {
            $this->_templateName = 'page404';
            $this->bladeView->render('views/templates/'.$this->_templateName);
        }
    }

    public function event()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $eventView = $this->event_m->get_single_event(array('eventID' => $id));
            if(inicompute($eventView)) {
                $this->bladeView->render('views/templates/eventview', compact('eventView'));
            } else {
                $this->_templateName = 'page404';
                $this->bladeView->render('views/templates/'.$this->_templateName);
            }
        } else {
            $this->_templateName = 'page404';
            $this->bladeView->render('views/templates/'.$this->_templateName);
        }
    }

    public function eventGoing()
    {
        $status = FALSE;
        $id = $this->input->post('id');
        if((int)$id) {
            if($this->session->userdata('loggedin')) {
                $event = $this->event_m->get_single_event(array('eventID' => $id));
                if(inicompute($event)) {
                    $currentDate = strtotime(date("Y-m-d H:i:s"));
                    $toDate      = $event->tdate.' '.$event->ttime;
                    $toDate      = strtotime($toDate);
                    if($currentDate <= $toDate) {
                        $name       = $this->session->userdata("name");
                        $photo      = $this->session->userdata("photo");
                        $userID     = $this->session->userdata("loginuserID");
                        $roleID     = $this->session->userdata("roleID");

                        $this->load->model('eventcounter_m');
                        $have = $this->eventcounter_m->get_order_by_eventcounter(array("eventID" => $id, "userID" => $userID), TRUE);

                        if(inicompute($have)) {
                            $this->eventcounter_m->update(['status' => 1], $have[0]->eventcounterID);
                            $status     = TRUE;
                            $message    = 'You are add this event';
                        } else {
                            $array = array(
                                'eventID'       => $id,
                                'status'        => 1,
                                'name'          => $name,
                                'photo'         => $photo,
                                'userID'        => $userID,
                                'roleID'        => $roleID,
                            );
                            $this->eventcounter_m->insert($array);
                            $status         = TRUE;
                            $message        = 'You are add this event';
                        }
                    } else {
                        $message    = "Event is closed";
                    }

                } else {
                    $message    = 'Event id does not found';
                }
            } else {
                $message    = 'Please login';
            }
        } else {
            $message    = 'ID is not int';
        }

        $json = array(
            "message"   => $message,
            'status'    => $status,
        );
        header("Content-Type: application/json", true);
        echo json_encode($json);
        exit;
    }

    public function notice()
    {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$id) {
            $noticeView = $this->notice_m->get_single_notice(array('noticeID' => $id));
            if(inicompute($noticeView)) {
                $this->bladeView->render('views/templates/noticeview', compact('noticeView'));
            } else {
                $this->_templateName = 'page404';
                $this->bladeView->render('views/templates/'.$this->_templateName);
            }
        } else {
            $this->_templateName = 'page404';
            $this->bladeView->render('views/templates/'.$this->_templateName);
        }
    }

    public function contactMailSend()
    {
        $name       = $this->input->post('name');
        $email      = $this->input->post('email');
        $subject    = $this->input->post('subject');
        $message    = $this->input->post('message');
        if($name && $email && $subject && $message) {

            $this->load->model('emailsettings_m');
            $this->load->library('email');
            $emailsetting = $this->emailsettings_m->get_emailsetting();
            $this->email->set_mailtype("html");

            if(inicompute($emailsetting)) {
                if($emailsetting->email_engine == 'smtp') {
                    $config = array(
                        'protocol'     => 'smtp',
                        'smtp_host'    => $emailsetting->smtp_server,
                        'smtp_port'    => $emailsetting->smtp_port,
                        'smtp_user'    => $emailsetting->smtp_username,
                        'smtp_pass'    => $emailsetting->smtp_password,
                        'smtp_timeout' => 20,
                        'mailtype'     => 'html',
                        'charset'      => 'utf-8',
                        'wordwrap'     => TRUE,
                    );
                    $this->email->initialize($config);
                    $this->email->set_newline("\r\n");
                }                
                $fromEmail    =  $this->data['backend_setting']->email;
                
                $this->email->from($fromEmail,  $name);
                $this->email->to($fromEmail);
                $this->email->reply_to($email);
                $this->email->subject($subject);
                $this->email->message($message);
                if($this->email->send()) {
                    $this->session->set_flashdata('success', 'Email send successfully.');
                } else {
                    $this->session->set_flashdata('error', 'oops! Email not send!');
                }
            } else {
                $this->session->set_flashdata('error', 'oops! Email not send!');
            }
        } else {
            $this->session->set_flashdata('error', 'oops! Email not send!');
        }
    }
}