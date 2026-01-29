<?php if ( !defined('BASEPATH') ) {
    exit('No direct script access allowed');
}

class Ward extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("ward_m");
        $this->load->model("floor_m");
        $this->load->model("room_m");
        $language = $this->session->userdata('lang');;
        $this->lang->load('ward', $language);
    }

    public function index()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ],
            'js'  => [
                'assets/select2/select2.js',
                'assets/inilabs/ward/index.js'
            ]
        ];
        $this->data['wards']        = $this->ward_m->get_ward();
        $this->data['floors']       = pluck($this->floor_m->get_floor(), 'name', 'floorID');
        $this->data['rooms']        = pluck($this->room_m->get_room(), 'name', 'roomID');
        if ( permissionChecker('ward_add') ) {

            if ( $_POST ) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);
                if ( $this->form_validation->run() == false ) {
                    $this->data["subview"] = "ward/index";
                    $this->load->view('_layout_main', $this->data);
                } else {
                    $array['name']          = $this->input->post('name');
                    $array['floorID']       = $this->input->post('floorID');
                    $array['roomID']        = $this->input->post('roomID');
                    $array['description']   = $this->input->post('description');
                    $array['create_date']   = date('Y-m-d H:i:s');
                    $array['modify_date']   = date('Y-m-d H:i:s');
                    $array['create_userID'] = $this->session->userdata('loginuserID');
                    $array['create_roleID'] = $this->session->userdata('roleID');

                    $this->ward_m->insert_ward($array);
                    $this->session->set_flashdata('success', 'Success');
                    redirect(site_url("ward/index"));
                }
            } else {
                $this->data["subview"] = "ward/index";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "ward/index";
            $this->load->view('_layout_main', $this->data);
        }
    }

    protected function rules()
    {
        $rules = [
            [
                'field' => 'name',
                'label' => $this->lang->line("ward_name"),
                'rules' => 'trim|required|max_length[60]|callback_unique_ward'
            ],
            [
                'field' => 'floorID',
                'label' => $this->lang->line("ward_floor"),
                'rules' => 'trim|required|max_length[11]|callback_required_no_zero'
            ],
            [
                'field' => 'roomID',
                'label' => $this->lang->line("ward_room"),
                'rules' => 'trim|required|max_length[11]|callback_required_no_zero'
            ],
            [
                'field' => 'description',
                'label' => $this->lang->line("ward_description"),
                'rules' => 'trim|max_length[255]'
            ]
        ];
        return $rules;
    }

    public function edit()
    {
        $this->data['headerassets'] = [
            'css' => [
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ],
            'js'  => [
                'assets/select2/select2.js',
                'assets/inilabs/ward/index.js'
            ]
        ];
        $wardID                     = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $wardID ) {
            $this->data['ward'] = $this->ward_m->get_single_ward([ 'wardID' => $wardID ]);
            if ( inicompute($this->data['ward']) ) {


                $this->data['wards']  = $this->ward_m->get_ward();
                $this->data['floors'] = pluck($this->floor_m->get_floor(), 'name', 'floorID');
                $this->data['rooms']  = pluck($this->room_m->get_room(), 'name', 'roomID');
                if ( $_POST ) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ( $this->form_validation->run() == false ) {
                        $this->data["subview"] = "ward/edit";
                        $this->load->view('_layout_main', $this->data);
                    } else {
                        $array                = [];
                        $array['name']        = $this->input->post('name');
                        $array['floorID']     = $this->input->post('floorID');
                        $array['roomID']      = $this->input->post('roomID');
                        $array['description'] = $this->input->post('description');
                        $array['modify_date'] = date('Y-m-d H:i:s');

                        $this->ward_m->update_ward($array, $wardID);
                        $this->session->set_flashdata('success', 'Success');
                        redirect(site_url("ward/index"));
                    }
                } else {
                    $this->data["subview"] = "ward/edit";
                    $this->load->view('_layout_main', $this->data);
                }
            } else {
                $this->data["subview"] = "_not_found";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "_not_found";
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function view()
    {
        if ( permissionChecker('ward_view') ) {
            $wardID              = $this->input->post('wardID');
            $this->data['ward']  = [];
            $this->data['floor'] = [];
            $this->data['room']  = [];
            if ( (int) $wardID ) {
                $this->data['ward'] = $this->ward_m->get_single_ward([ 'wardID' => $wardID ]);
                if ( inicompute($this->data['ward']) ) {
                    $ward                = $this->data['ward'];
                    $this->data['floor'] = $this->floor_m->get_single_floor([ 'floorID' => $ward->floorID ]);
                    $this->data['room']  = $this->room_m->get_single_room([ 'roomID' => $ward->roomID ]);
                    $this->load->view('ward/view', $this->data);
                } else {
                    $this->errorViewLoad();
                }
            } else {
                $this->errorViewLoad();
            }
        } else {
            $this->errorViewLoad();
        }
    }

    private function errorViewLoad()
    { ?>
        <div class="error-card">
            <div class="error-title-block">
                <h1 class="error-title">404</h1>
                <h2 class="error-sub-title"> Sorry, data not found </h2>
            </div>
            <div class="error-container">
                <a class="btn btn-primary" href="<?= site_url('dashboard/index') ?>">
                    <i class="fa fa-angle-left"></i> Back to Dashboard</a>
            </div>
        </div>
        <?php
    }

    public function delete()
    {
        $wardID = escapeString($this->uri->segment('3'));
        if ( (int) $wardID ) {
            $ward = $this->ward_m->get_single_ward([ 'wardID' => $wardID ]);
            if ( inicompute($ward) ) {
                $this->ward_m->delete_ward($wardID);
                $this->session->set_flashdata('success', 'Success');
                redirect(site_url('ward/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_ward( $name )
    {
        $roomID = $this->input->post('roomID');
        $wardID = htmlentities(escapeString($this->uri->segment(3)));
        if ( (int) $wardID ) {
            $ward = $this->ward_m->get_order_by_ward([ "name" => $name, 'roomID' => $roomID, "wardID !=" => $wardID ]);
            if ( inicompute($ward) ) {
                $this->form_validation->set_message("unique_ward", "This %s already exists.");
                return false;
            }
            return true;
        } else {
            $ward = $this->ward_m->get_order_by_ward([ "name" => $name, 'roomID' => $roomID ]);
            if ( inicompute($ward) ) {
                $this->form_validation->set_message("unique_ward", "This %s already exists.");
                return false;
            }
            return true;
        }
    }

    public function required_no_zero( $data )
    {
        if ( $data == '0' ) {
            $this->form_validation->set_message("required_no_zero", "The %s field is required.");
            return false;
        }
        return true;
    }
}