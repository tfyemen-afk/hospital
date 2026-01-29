<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Frontend_Controller extends MY_Controller
{
    private     $_frontendTheme             = '';
    private     $_frontendThemePath         = '';
    private     $_frontendThemeBasePath     = '';
    protected   $bladeView;

    public function __construct ()
    {
        parent::__construct();
        $this->load->driver('cache', array('adapter' => 'file'));
        $this->load->library('blade');
        $this->load->library("session");
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper('language');
        $this->load->helper('date');
        $this->load->helper('form');
        $this->load->model('fmenu_m');
        $this->load->model('generalsettings_m');
        $this->load->model('frontendsettings_m');
        $this->load->helper("frontenddata");
        $this->load->model('page_m');
        $this->load->model('post_m');
        $this->load->model('event_m');
        $this->load->model('notice_m');
        $this->load->model('user_m');
        $this->load->model('designation_m');
        $this->load->model('fmenu_relation_m');

        $this->data['backend_setting']      = $this->generalsettings_m->get_generalsettings();
        $this->data['frontend_setting']     = $this->frontendsettings_m->get_frontendsettings();
        $this->data['frontend_topbar']      = $this->fmenu_m->get_single_fmenu(array('topbar' => 1));
        $this->data['frontend_social']      = $this->fmenu_m->get_single_fmenu(array('social' => 1));

        /* Start All Data Call */
        $this->data['events']       = $this->event_m->get_order_by_event(array('YEAR(create_date)' => date('Y')));
        $this->data['notices']      = $this->notice_m->get_order_by_notice(array('YEAR(create_date)' => date('Y')));
        $this->data['doctors']      = $this->user_m->get_order_by_user(array('roleID' => 2, 'status' =>  1));
        $this->data['designations'] = pluck($this->designation_m->get_order_by_designation(), 'designation', 'designationID');
        /* Close All Data Call */

        $this->data['homepage']         = $this->page_m->get_one($this->data['frontend_topbar']);
        $this->data['forntend_pages']   = pluck($this->page_m->get_page(), 'obj', 'pageID');
        $this->data['forntend_posts']   = pluck($this->post_m->get_post(), 'obj', 'postID');
        $this->data['menu']             = $this->_callMenu();

        $this->_frontendTheme           = 'default';
        $this->_frontendThemePath       = 'frontend/'. $this->_frontendTheme.'/';
        $this->_frontendThemeBasePath   = FCPATH.'frontend/'. $this->_frontendTheme.'/';

        $this->blade->load_view_root($this->_frontendThemeBasePath);
        $this->bladeView = $this->blade;
        $this->bladeView->set('backend', $this->data['backend_setting']);
        $this->bladeView->set('frontend', $this->data['frontend_setting']);
        $this->bladeView->set('frontendThemePath', $this->_frontendThemePath);

        if(inicompute($this->data['homepage'])) {
            $this->data['homepageTitle']    = $this->data['homepage']->menu_label;
            $this->bladeView->set('homepageTitle', $this->data['homepage']->menu_label);
            if($this->data['homepage']->menu_typeID == 1) {
                $page = $this->page_m->get_single_page(array('pageID' => $this->data['homepage']->menu_pageID));
                $this->data['homepage']         = $page;
                $this->data['homepageType']     = 'page';
                $this->bladeView->set('homepage', $page);
                $this->bladeView->set('homepageType', 'page');
            } elseif($this->data['homepage']->menu_typeID == 2) {
                $post = $this->post_m->get_single_post(array('postID' => $this->data['homepage']->menu_pageID));
                $this->data['homepage']         = $post;
                $this->data['homepageType']     = 'post';
                $this->bladeView->set('homepage', $post);
                $this->bladeView->set('homepageType', 'post');
            } else {
                $nonehomepage = (object) array('url' => '');
                $this->data['homepage']     = $nonehomepage;
                $this->bladeView->set('homepage', $nonehomepage);
                $this->bladeView->set('homepageType', 'none');
            }
        } else {
            $nonehomepage = (object) array('url' => '');
            $this->data['homepage']         = $nonehomepage;
            $this->data['homepageTitle']    = '';
            $this->bladeView->set('homepage', $nonehomepage);
            $this->bladeView->set('homepageType', 'none');
            $this->bladeView->set('homepageTitle', $this->data['homepageTitle']);
        }

        $this->bladeView->set('fpages', $this->data['forntend_pages']);
        $this->bladeView->set('fposts', $this->data['forntend_posts']);
        $this->bladeView->set('menu', $this->data['menu']);

        $this->bladeView->set('events', $this->data['events']);
        $this->bladeView->set('notices', $this->data['notices']);
        $this->bladeView->set('doctors', $this->data['doctors']);
        $this->bladeView->set('designations', $this->data['designations']);
        $this->bladeView->set('jsmanager', []);
    }

    private function _callMenu()
    {
        $frontendTopbarMenu             = [];
        $frontendSocialMenu             = [];
        $frontendTopbarQueryMenus       = [];
        $frontendSocialQueryMenus       = [];
        if(inicompute($this->data['frontend_topbar'])) {
            $frontendTopbarQueryMenus   = $this->fmenu_relation_m->get_order_by_fmenu_relation(array('fmenuID' => $this->data['frontend_topbar']->fmenuID));
            $frontendTopbarMenu         = $this->_orderMenu($frontendTopbarQueryMenus);
        }

        if(inicompute($this->data['frontend_topbar'])) {
            if($this->data['frontend_topbar']->social == 1) {
                $frontendSocialMenu         = $frontendTopbarMenu;
                $frontendSocialQueryMenus   = $frontendTopbarQueryMenus;
            } else {
                if(inicompute($this->data['frontend_social'])) {
                    $frontendSocialQueryMenus   = $this->fmenu_relation_m->get_order_by_fmenu_relation(array('fmenuID' => $this->data['frontend_social']->fmenuID));
                    $frontendSocialMenu         = $this->_orderMenu($frontendSocialQueryMenus);
                }
            }
        } else {
            if(inicompute($this->data['frontend_social'])) {
                $frontendSocialQueryMenus   = $this->fmenu_relation_m->get_order_by_fmenu_relation(array('fmenuID' => $this->data['frontend_social']->fmenuID));
                $frontendSocialMenu         = $this->_orderMenu($frontendSocialQueryMenus);
            }
        }

        $returnArray = array('frontendTopbarMenus' => $frontendTopbarMenu, 'frontendSocialMenus' => $frontendSocialMenu, 'frontendTopbarQueryMenus' => $frontendTopbarQueryMenus, 'frontendSocialQueryMenus' => $frontendSocialQueryMenus);

        return $returnArray;
    }

    private function _orderMenu($elements)
    {
        $mergeelements = [];
        if(inicompute($elements)) {
            $elements = json_decode(json_encode($elements), true);
            if(inicompute($elements)) {
                foreach ($elements as $elementkey => $element) {
                    if($element['menu_parentID'] == 0) {
                        $mergeelements[] = $element;
                        unset($elements[$elementkey]);
                    }
                }

                foreach ($elements as $elementkey => $element) {
                    if(inicompute($mergeelements)) {
                        foreach ($mergeelements as $mergeelementkey =>  $mergeelement) {
                            if($element['menu_rand_parentID'] == $mergeelement['menu_rand']) {
                                $mergeelements[$mergeelementkey]['child'][] = $element;
                                unset($elements[$elementkey]);
                            }
                        }
                    }
                }

                foreach ($elements as $elementkey => $element) {
                    if(inicompute($mergeelements)) {
                        foreach ($mergeelements as $mergeelementkey =>  $mergeelement) {
                            if(isset($mergeelement['child'])) {
                                if(inicompute($mergeelement['child'])) {
                                    foreach ($mergeelement['child'] as $secandlayerkey => $secandlayer) {
                                        if($secandlayer['menu_rand'] == $element['menu_rand_parentID']) {
                                            $mergeelements[$mergeelementkey]['child'][$secandlayerkey]['child'][] = $element;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $mergeelements;
    }
}

