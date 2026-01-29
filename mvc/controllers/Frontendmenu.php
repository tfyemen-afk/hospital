<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Frontendmenu extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('fmenu_m');
        $this->load->model('page_m');
        $this->load->model("post_m");
        $this->load->model("fmenu_relation_m");
        $language = $this->session->userdata('lang');
        $this->lang->load('frontendmenu', $language);
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/wp-menu/assets/css/style.css',
            ),
            'js' => array(
                'assets/wp-menu/assets/js/jquery-ui-1.12.1/jquery-ui.min.js',
                'assets/nestedSortable/jquery.mjs.nestedSortable.js',
                'assets/inilabs/frontendmenu/index.js',
            )
        );

        $this->data['jsmanager'] = [
            'frontendmenu_error_label' => $this->lang->line('frontendmenu_error_label'),
            'frontendmenu_no_label' => $this->lang->line('frontendmenu_no_label'),
        ];

        $this->data['fmenus']       = $this->fmenu_m->get_fmenu();
        $this->data['activefmenu']  = $this->fmenu_m->get_single_fmenu(array('status' => 1));
        $this->data['pages']        = $this->page_m->get_page();
        $this->data['posts']        = $this->post_m->get_post();
        $this->data['pluckpages']   = pluck($this->data['pages'], 'obj', 'pageID');
        $this->data['pluckposts']   = pluck($this->data['posts'], 'obj', 'postID');

        $activefmenuID = 0;
        $activefmenuName = '';
        if(inicompute($this->data['activefmenu'])) {
            $activefmenuID      = $this->data['activefmenu']->fmenuID;
            $activefmenuName    = $this->data['activefmenu']->menu_name;
        } else {
            if(inicompute($this->data['fmenus'])) {
                $activefmenuID      = $this->data['fmenus'][0]->fmenuID;
                $activefmenuName    = $this->data['fmenus'][0]->menu_name;
            }
        }

        $this->data['getactivefmenuID']         = $activefmenuID;
        $this->data['jsmanager']['getactivefmenuID'] = $this->data['getactivefmenuID'];

        $this->data['getactivefmenuName']       = $activefmenuName;
        $this->data['menushow']                 = $this->_ordermenu($this->fmenu_relation_m->get_order_by_fmenu_relation(array('fmenuID' => $activefmenuID)));
        $this->data["subview"] = "frontendmenu/index";
        $this->load->view('_layout_main', $this->data);
    }

    public function getpage()
    {
        if($_POST) {
            $page           = $this->input->post('page');
            $pageArrays     = [];
            if(inicompute($page)) {
                foreach ($page as $pag) {
                    $expPage        = explode('-', $pag);
                    $pageID         = $expPage[1];
                    $pageArrays[]   = $this->page_m->get_single_page(array('pageID' => $pageID));
                }
                $this->data['pageArrays'] = $pageArrays;
                echo $this->load->view('frontendmenu/getPage', $this->data, true);
            }
        }
    }

    public function getpost()
    {
        if($_POST) {
            $post           = $this->input->post('post');
            $postArrays     = [];
            if(inicompute($post)) {
                foreach ($post as $pos) {
                    $expPost        = explode('-', $pos);
                    $postID         = $expPost[1];
                    $postArrays[]   = $this->post_m->get_single_post(array('postID' => $postID));
                }
                $this->data['postArrays'] = $postArrays;
                echo $this->load->view('frontendmenu/getPost', $this->data, true);
            }
        }
    }

    public function getlink()
    {
        if($_POST) {
            $urlLinkField   = xssRemove($this->input->post('url_link_field'));
            $urlLinkText    = xssRemove($this->input->post('url_link_text'));

            if(!empty($urlLinkField) && !empty($urlLinkText)) {
                if (strpos($urlLinkField, 'http://') === false && strpos($urlLinkField, 'https://') === false) {
                    if($urlLinkField != '#') {
                        $urlLinkField = 'http://'.$urlLinkField;
                    }
                }

                $this->data['urlLinkField']     = $urlLinkField;
                $this->data['urlLinkText']      = $urlLinkText;
                echo $this->load->view('frontendmenu/getLink', $this->data, true);
            }
        }
    }

    public function managelocation()
    {
        $fmenus = $this->fmenu_m->get_fmenu();
        if($_POST) {
            if(inicompute($this->fmenu_m->get_fmenu())) {
                $topMenuList        =  $this->input->post('top_menu_list');
                $socialMenuList     =  $this->input->post('social_menu_list');
                if(inicompute($fmenus)) {
                    foreach ($fmenus as $fmenu) {
                        $this->fmenu_m->update_fmenu(array('topbar' => 0), $fmenu->fmenuID);
                        $this->fmenu_m->update_fmenu(array('social' => 0), $fmenu->fmenuID);
                    }
                }
                $this->fmenu_m->update_fmenu(array('topbar' => 1), $topMenuList);
                $this->fmenu_m->update_fmenu(array('social' => 1), $socialMenuList);
                $this->session->set_flashdata('success', 'Success');
            }
        }
        redirect(site_url('frontendmenu/index'));
    }

    protected $_fmenuID = 0;
    public function savemenu()
    {
        $retArray['status'] = FALSE;
        if($_POST) {
            if(inicompute($this->fmenu_m->get_fmenu())) {
                $elements           = $this->input->post('elements');
                $locationtop        = $this->input->post('locationtop');
                $locationsocial     = $this->input->post('locationsocial');
                $editmenuID         = $this->input->post('editmenuID');
                $menuname           = $this->input->post('menuname');

                if(empty($locationtop) || !is_numeric($locationtop)) {
                    $locationtop        = 0;
                }

                if(empty($locationsocial) || !is_numeric($locationsocial)) {
                    $locationsocial     = 0;
                }

                if((int)$editmenuID && $editmenuID > 0) {
                    $this->_fmenuID     = $editmenuID;
                }

                if(inicompute($elements)) {
                    $validation = $this->_validation_save_menu($elements);
                    if(inicompute($validation)) {
                        $retArray['status']     = FALSE;
                        $retArray['errors']     = $validation;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $elements = $this->_validation_xss_clen($elements);
                        if(inicompute($elements)) {
                            $this->fmenu_relation_m->delete_fmenu_relation_by_array(array('fmenuID' => $this->_fmenuID));
                            if($locationtop) {
                                $this->fmenu_m->update_fmenu_by_array(array('topbar' => 0), array('topbar' => 1));
                            }

                            if($locationsocial) {
                                $this->fmenu_m->update_fmenu_by_array(array('social' => 0), array('social' => 1));
                            }

                            $this->fmenu_m->update_fmenu(array('topbar' => $locationtop, 'social' => $locationsocial, 'menu_name' => $menuname, 'status' => 1), $this->_fmenuID);
                            $this->fmenu_relation_m->insert_batch_fmenu_relation($elements);
                            $this->session->set_flashdata('success', 'Success');
                        }
                        $retArray['status'] = TRUE;
                        $retArray['message'] = 'Success';
                        echo json_encode($retArray);
                        exit;
                    }
                } else {
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['errors'][] = $this->lang->line('frontendmenu_error_menu');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['status'] = TRUE;
            echo json_encode($retArray);
            exit;
        }
    }

    private function _validation_save_menu($elements)
    {
        $validationerror = [];
        foreach ($elements as $key => $element) {
            if(strlen($element['menu_label']) > 253 ) {
                $validationerror[] = $this->lang->line('frontendmenu_error_label');
            }
        }
        return $validationerror;
    }

    private function _validation_xss_clen($elements) {
        $retArray = [];
        if(inicompute($elements)) {
            foreach ($elements as $key => $element) {
                $this->_fmenuID             = $element['fmenuID'];
                $element['menu_label']      = xssRemove($element['menu_label']);
                $element['menu_status']     = 1;
                if(!empty($element['menu_link'])) {
                    $element['menu_link']   = xssRemove($element['menu_link']);
                } else {
                    $element['menu_link']   = '#';
                }
                $retArray[]                 = $element;
            }
        }
        return $retArray;
    }

    private function _ordermenu($elements)
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

        $string = '';
        if(inicompute($mergeelements)) {
            foreach ($mergeelements as $mergeelement) {
                if($mergeelement['menu_typeID'] == 1) {
                    $this->data['mergeelement'] = $mergeelement;
                    $string .= $this->load->view('frontendmenu/generatePage', $this->data, true);
                } elseif($mergeelement['menu_typeID'] == 2) {
                    $this->data['mergeelement'] = $mergeelement;
                    $string .= $this->load->view('frontendmenu/generatePost', $this->data, true);
                } elseif($mergeelement['menu_typeID'] == 3) {
                    $this->data['mergeelement'] = $mergeelement;
                    $string .= $this->load->view('frontendmenu/generateLink', $this->data, true);
                }

                if(isset($mergeelement['child'])) {
                    $string .= '<ol>';
                    foreach ($mergeelement['child'] as $mergeelementsec) {
                        if($mergeelementsec['menu_typeID'] == 1) {
                            $this->data['mergeelement'] = $mergeelementsec;
                            $string .= $this->load->view('frontendmenu/generatePage', $this->data, true);
                        } elseif($mergeelementsec['menu_typeID'] == 2) {
                            $this->data['mergeelement'] = $mergeelementsec;
                            $string .= $this->load->view('frontendmenu/generatePost', $this->data, true);
                        } elseif($mergeelementsec['menu_typeID'] == 3) {
                            $this->data['mergeelement'] = $mergeelementsec;
                            $string .= $this->load->view('frontendmenu/generateLink', $this->data, true);
                        }

                        if(isset($mergeelementsec['child'])) {
                            $string .= '<ol>';
                            foreach ($mergeelementsec['child'] as $mergeelementthr) {
                                if($mergeelementthr['menu_typeID'] == 1) {
                                    $this->data['mergeelement'] = $mergeelementthr;
                                    $string .= $this->load->view('frontendmenu/generatePage', $this->data, true);
                                    $string .= '</li>';
                                } elseif($mergeelementthr['menu_typeID'] == 2) {
                                    $this->data['mergeelement'] = $mergeelementthr;
                                    $string .= $this->load->view('frontendmenu/generatePost', $this->data, true);
                                    $string .= '</li>';
                                } elseif($mergeelementthr['menu_typeID'] == 3) {
                                    $this->data['mergeelement'] = $mergeelementthr;
                                    $string .= $this->load->view('frontendmenu/generateLink', $this->data, true);
                                    $string .= '</li>';
                                }
                            }
                            $string .= '</ol>';
                        }
                        $string .= '</li>';
                    }
                    $string .= '</ol>';
                }
                $string .= '</li>';
            }
        }

        return $string;
    }

    public function editmenutoggle()
    {
        if($_POST) {
            $fmenuID = $this->input->post('fmenuID');
            if($fmenuID != '') {
                if((int)$fmenuID) {
                    $fmenus = $this->fmenu_m->get_fmenu();
                    if(inicompute($fmenus)) {
                        foreach ($fmenus as  $fmenu) {
                            $this->fmenu_m->update_fmenu(array('status' => 0), $fmenu->fmenuID);
                        }
                    }
                    $this->fmenu_m->update_fmenu(array('status' => 1), $fmenuID);
                }
            }
        }
    }

    public function deletefmenu()
    {
        if($_POST) {
            $fmenuID = $this->input->post('fmenuID');
            if($fmenuID != '') {
                if((int)$fmenuID) {
                    $this->fmenu_m->delete_fmenu($fmenuID);
                    $this->fmenu_relation_m->delete_fmenu_relation_by_array(array('fmenuID' => $fmenuID));
                }
            }
        }
    }

    public function createnewmenu()
    {
        $retArray['status'] = FALSE;
        if($_POST) {
            $menuName       = $this->input->post('menuname');
            if(!empty($menuName)) {
                $menuName   = xssRemove($menuName);
                if(strlen($menuName) <= 128) {
                    $fmenu  = $this->fmenu_m->get_fmenu();
                    if(inicompute($fmenu)) {
                        $this->fmenu_m->update_fmenu_by_array(array('status' => 0), array('status' => 1));
                    }

                    $this->fmenu_m->insert_fmenu(array('menu_name' => $menuName, 'status' => 1, 'topbar' => 0, 'social' => 0));
                    $retArray['status']     = TRUE;
                    $retArray['message']    = 'Success';
                } else {
                    $retArray['error']      = $this->lang->line('frontendmenu_error_menu_length');
                }
            } else {
                $retArray['error']          = $this->lang->line('frontendmenu_error_menu_required');
            }
        }

        echo json_encode($retArray);
        exit;
    }
}