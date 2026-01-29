<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('permissions_m');
        $this->load->model('permissionlog_m');
        $this->load->model('role_m');
        $language = $this->session->userdata('lang');
        $this->lang->load('permissions', $language);
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
                'assets/inilabs/permissions/index.js',
            )
        );

        $roleID = htmlentities(escapeString($this->uri->segment('3')));
        $this->data['roles'] = $this->role_m->get_role();
        $this->data['jsmanager'] = ['permissions_please_select_role' => $this->lang->line('permissions_please_select_role')];
        if((int)$roleID) {
            $this->permissionlog_m->order('permissionlogID asc');
            $permissionlogs = $this->permissionlog_m->get_order_by_permissionlog(array('active' => 'yes'));
            if(inicompute($permissionlogs)) {
                $permissionlogArray = [];
                $permissionlogrowArray = [];
                foreach($permissionlogs as $permissionlog) {
                    if((strpos($permissionlog->name, '_add') == FALSE) && (strpos($permissionlog->name, '_edit') == FALSE) && (strpos($permissionlog->name, '_view') == FALSE) && (strpos($permissionlog->name, '_delete') == FALSE)) {
                        $permissionlogrowArray[$permissionlog->permissionlogID] = $permissionlog;
                    }
                    $permissionlogArray[$permissionlog->name] = $permissionlog->permissionlogID;
                }

                $this->data['permissionlogArray']    = $permissionlogArray;
                $this->data['permissionlogrowArray'] = $permissionlogrowArray;
                $this->data['permissions'] = pluck($this->permissions_m->get_order_by_permissions(array('roleID'=>$roleID)),'permissionlogID','permissionlogID');

                $this->data['roleID']  = $roleID;
                $this->data["subview"] = 'permissions/index';
                $this->load->view('_layout_main', $this->data);   
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data['roleID']       = 0;
            $this->data['permissions']  = [];
            $this->data['permissionlogArray']    = [];
            $this->data['permissionlogrowArray'] = [];

            $this->data["subview"] = 'permissions/index';
            $this->load->view('_layout_main', $this->data);      
        }
	}

    public function save()
    {
        if(permissionChecker('permissions')) {
            if($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->session->set_flashdata('error', trim(validation_errors()));
                    redirect('permissions');
                } else {
                    $roleID = $this->input->post('roleID');
                    unset($_POST['roleID']);
                    $array = [];
                    if(inicompute($_POST)) {
                        $i = 0;
                        foreach ($_POST as $post) {
                            $array[$i]['roleID'] = $roleID;
                            $array[$i]['permissionlogID'] = $post;
                            $i++;
                        }
                    }

                    $this->permissions_m->delete_permissions_by_role($roleID);
                    if(inicompute($array)) {
                        $this->permissions_m->insert_batch_permissions($array);
                    }
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url('permissions/index/'.$roleID));
                }
            } else {
                redirect(site_url('permissions/index'));
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'roleID',
                'label' => $this->lang->line("permissions_role"),
                'rules' => 'trim|required|callback_required_no_zero'
            )
        );
        return $rules;
    }

    public function required_no_zero($data)
    {
        if($data == '0') {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return FALSE;
        }
        return TRUE;
    }
}
