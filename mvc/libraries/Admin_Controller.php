<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Controller extends MY_Controller 
{    
    public function __construct() 
    {
        parent::__construct();
        $this->load->library("session");
        $this->load->database();
        $this->load->library('form_validation');
        $this->load->helper('html');

        $this->load->model('signin_m');
        $this->load->model('permissionlog_m');
        $this->load->model('menulog_m');
        $this->load->model('generalsettings_m');
        $this->data["headerassets"]     = [];
        $this->data["footerassets"]     = [];
        $this->data["jsmanager"]        = [];
        $this->data["generalsettings"]  = $this->generalsettings_m->get_generalsettings();
        $this->data['getsearch']        = "";
        $this->data['loginroleID']      = $this->session->userdata('roleID');

        if($this->data['loginroleID'] == 2) {
            $this->data['loginuserID']   = $this->session->userdata('loginuserID');
        } elseif($this->data['loginroleID'] == 3) {
            $this->data['loginuserID']   = $this->session->userdata('patientID');
        } else {
            $this->data['loginuserID']      = $this->session->userdata('loginuserID');
        }

        $language = $this->session->userdata('lang');
		$this->lang->load('topbar_menu', $language);
		
        $exception_uris = array(
            'signin',
			'signin/index',
			'signin/signout'
		);


        if(in_array(uri_string(), $exception_uris) == FALSE) {
            if($this->signin_m->loggedin() == FALSE) {
                if($this->data["generalsettings"]->frontend === '1') {
                    $this->load->model('fmenu_m');
                    $this->load->model('page_m');
                    $this->load->model('post_m');
                    $frontendTopbar = $this->fmenu_m->get_single_fmenu(array('topbar' => 1));
                    $homePage = $this->page_m->get_one($frontendTopbar);
                    $frontendRedirectURL = '';
                    $frontendRedirectMethod = 'home';
                    if(inicompute($homePage)) {
                        if($homePage->menu_typeID == 1) {
                            $page = $this->page_m->get_single_page(array('pageID' => $homePage->menu_pageID));
                            if(inicompute($page)) {
                                $frontendRedirectURL = $page->url;
                                $frontendRedirectMethod = 'page';
                            }
                        } elseif($homePage->menu_typeID == 2) {
                            $post = $this->post_m->get_single_post(array('postID' => $homePage->menu_pageID));
                            if(inicompute($post)) {
                                $frontendRedirectURL = $post->url;
                                $frontendRedirectMethod = 'post';
                            }
                        }
                    }
                    redirect(site_url('frontend/'.$frontendRedirectMethod.'/'.$frontendRedirectURL));
                } else {
					redirect(site_url("signin/index"));
			    }
			}
		}

		$module = $this->uri->segment(1);
		$action = $this->uri->segment(2);
		$this->_permissionSetUp($module, $action);
        $this->data['notifications'] = $this->_notification();
    }

    private function _permissionSetUp($module, $action)
    {
        if($action == 'index' || $action == false) {
            $permission = $module;
        } else {
            $permission = $module.'_'.$action;
        }

        $permissionset = [];
        $userdata = $this->session->userdata;

        if($this->session->userdata('roleID') == 1 && $this->session->userdata('loginuserID') == 1) {
            if(isset($userdata['loginuserID']) && !isset($userdata['get_permission'])) {
                $allmodules = $this->permissionlog_m->get_permissionlog();

                if(inicompute($allmodules)) {
                    foreach ($allmodules as $key => $allmodule) {
                        $permissionset['master_permission_set'][trim($allmodule->name)] = $allmodule->active;
                    }

                    $data = ['get_permission' => TRUE];
                    $this->session->set_userdata($data);
                    $this->session->set_userdata($permissionset);
                }
            }
        } else {
            if(isset($userdata['loginuserID']) && !isset($userdata['get_permission'])) {
                if(!$this->session->userdata($permission)) {
                    $user_permission = $this->permissionlog_m->get_permissionlog_with_feature($userdata['roleID']);
                    foreach ($user_permission as $value) {
                        $permissionset['master_permission_set'][$value->name] = $value->active;
                    }

                    $data = ['get_permission' => TRUE];
                    $this->session->set_userdata($data);
                    $this->session->set_userdata($permissionset);
                }
            }
        }

        $sessionPermission = $this->session->userdata('master_permission_set');
        $sessionPermission['search'] = 'yes';

        $this->menulog_m->order('priority desc');
        $dbMenus	= $this->_menuTree(json_decode(json_encode(pluck($this->menulog_m->get_order_by_menulog(['status' => 1]), 'obj', 'menulogID')), true) , $sessionPermission);
        $this->data['dbMenus'] = $dbMenus;

        if((isset($sessionPermission[$permission]) && $sessionPermission[$permission] == "no") ) {
            if($permission == 'dashboard' && $sessionPermission[$permission] == "no") {
                $url = 'exceptionpage/index';
                if(in_array('yes', $sessionPermission)) {
                    if($sessionPermission["dashboard"] == 'no') {
                        foreach ($sessionPermission as $key => $value) {
                            if($value == 'yes') {
                                $url = $key;
                                break;
                            }
                        }
                    }
                } else {
                    redirect(site_url('exceptionpage/index'));
                }
                redirect(site_url($url));
            } else {
                redirect(site_url('exceptionpage/error'));
            }
        }
    }
    
    private function _menuTree($dataset, $sessionPermission)
    {
    	$tree = [];
        foreach ($dataset as $id=>&$node) {
            if($node['link'] == '#' || (isset($sessionPermission[$node['link']]) && $sessionPermission[$node['link']] != "no")) {
	    		if ($node['parentID'] == 0) {
	    			$tree[$id]=&$node;
	    		} else {
					if (!isset($dataset[$node['parentID']]['child'])){
						$dataset[$node['parentID']]['child'] = [];
					}
					$dataset[$node['parentID']]['child'][$id] = &$node;
	    		}
			}
    	}
    	return $tree;
    }

    private function _notification()
    {
        $dbNotification = [];
        $this->load->model('notification_m');
        $notifications = $this->notification_m->get_select_notification_with_limit('*', ['userID' => $this->session->userdata('loginuserID')], 20);
        if(inicompute($notifications)) {
            foreach ($notifications as $notification) {
                $dbNotification[$notification->itemname][$notification->itemID] = $notification;
            }
        }

        $generateNotification = [];
        if(permissionChecker('notice_view')) {
            $this->load->model('notice_m');
            $notices = $this->notice_m->get_select_notice_with_user('notice.*, user.photo, user.name', [], 5);
            if (inicompute($notices)) {
                foreach ($notices as $notice) {
                    if (!isset($dbNotification['notice'][$notice->noticeID])) {
                        $generateNotification[] = $notice;
                    }
                }
            }
        }

        if(permissionChecker('event_view')) {
            $this->load->model('event_m');
            $events = $this->event_m->get_select_event_with_user('event.*, user.photo, user.name', [], 5);
            if(inicompute($events)) {
                foreach ($events as $event) {
                    if(!isset($dbNotification['event'][$event->eventID])) {
                        $generateNotification[] = $event;
                    }
                }
            }
        }

        if(inicompute($generateNotification)) {
            $generateNotification = json_decode(json_encode($generateNotification), TRUE);
            array_multisort(array_column($generateNotification, "create_date"), SORT_DESC, $generateNotification);
            $generateNotification = json_decode(json_encode($generateNotification), FALSE);
        }
        return $generateNotification;
    }
}
