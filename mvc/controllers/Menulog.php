<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menulog extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('menulog_m');
        $language = $this->session->userdata('lang');;
        $this->lang->load('menulog', $language);
         redirect(site_url('dashboard/index'));
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("menulog_name"),
                'rules' => 'trim|required|max_length[128]'
            ),
            array(
                'field' => 'parentmenulogID',
                'label' => $this->lang->line("menulog_parent"),
                'rules' => 'trim|required|numeric|max_length[11]'
            ),
            array(
                'field' => 'link',
                'label' => $this->lang->line("menulog_link"),
                'rules' => 'trim|required|max_length[512]'
            ),
            array(
                'field' => 'icon',
                'label' => $this->lang->line("menulog_icon"),
                'rules' => 'trim|max_length[128]'
            ),
            array(
                'field' => 'priority',
                'label' => $this->lang->line("menulog_priority"),
                'rules' => 'trim|required|max_length[11]'
            ),
            array(
                'field' => 'status',
                'label' => $this->lang->line("menulog_status"),
                'rules' => 'trim|required|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'pullright',
                'label' => $this->lang->line("menulog_pullright"),
                'rules' => 'trim|max_length[255]'
            )
        );
        return $rules;
    }

	public function index() 
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
                'assets/inilabs/select2.js',
                'assets/inilabs/menulog/index.js',
            )
        );
        $this->data['menulogs'] = pluck($this->menulog_m->get_menulog(),'obj','menulogID');

        if($_POST) {
            $rules  = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->data["subview"] = 'menulog/index';
                $this->load->view('_layout_main', $this->data);
            } else {
                $array['name']   = $this->input->post('name');
                $array['parentID'] = $this->input->post('parentmenulogID');
                $array['link']   = $this->input->post('link');
                $array['icon']   = $this->input->post('icon');
                $array['priority'] = $this->input->post('priority');
                $array['status'] = $this->input->post('status');
                $array['pullright'] = $this->input->post('pullright');
                $this->menulog_m->insert_menulog($array);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('menulog/index'));
            }
        } else {
            $this->data["subview"] = 'menulog/index';
            $this->load->view('_layout_main', $this->data);
        }
	}

    public function edit() 
    {
        $menulogID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$menulogID) {
            $this->data['menulog'] = $this->menulog_m->get_single_menulog(array('menulogID'=> $menulogID));
            if(inicompute($this->data['menulog'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/inilabs/select2.js',
                        'assets/inilabs/menulog/index.js',
                    )
                );
                $this->data['menulogs'] = $this->menulog_m->get_menulog();
                if($_POST) {
                    $rules  = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $this->data["subview"] = 'menulog/edit';
                        $this->load->view('_layout_main', $this->data);    
                    } else {
                        $array['name']   = $this->input->post('name');
                        $array['parentID'] = $this->input->post('parentmenulogID');
                        $array['link']   = $this->input->post('link');
                        $array['icon']   = $this->input->post('icon');
                        $array['priority'] = $this->input->post('priority');
                        $array['status'] = $this->input->post('status');
                        $array['pullright'] = $this->input->post('pullright');
                        $this->menulog_m->update_menulog($array, $menulogID);
                        $this->session->set_flashdata('success','Success');
                        redirect(site_url('menulog/index'));
                    }
                } else {
                    $this->data["subview"] = 'menulog/edit';
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete() 
    {
        $menulogID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$menulogID) {
            $menulog = $this->menulog_m->get_single_menulog(array('menulogID'=> $menulogID));
            if(inicompute($menulog)) {
                $this->menulog_m->delete_menulog($menulogID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('menulog/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function status() 
    {
        $f = FALSE;
        if(config_item('demo') && ENVIRONMENT == 'development') {
            if($_POST) {
                $menulogID  = $this->input->post('menulogID');
                $status     = $this->input->post('status');
                if((int)$menulogID) {
                    $menulog = $this->menulog_m->get_single_menulog(array('menulogID' => $menulogID));
                    if(inicompute($menulog)) {
                        if($status == 'checked') {
                            $this->menulog_m->update_menulog(array('status' => 1), $menulogID);
                            $f = TRUE;
                        } elseif($status == 'unchecked') {
                            $this->menulog_m->update_menulog(array('status' => 2), $menulogID);
                            $f = TRUE;
                        }
                    }
                }
            }
        }

        if($f) {
            echo 'Success';
        } else {
            echo "Error";
        }
    }

    public function required_no_zero($data) 
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
}
