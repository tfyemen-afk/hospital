<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Floor extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
		$this->load->model("floor_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('floor', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("floor_name"),
				'rules' => 'trim|required|max_length[60]|callback_unique_floor'
			),
			array(
				'field' => 'description',
				'label' => $this->lang->line("floor_description"),
				'rules' => 'trim|max_length[255]'
			)
		);
		return $rules;
	}

	public function index()
    {
		$this->data['floors'] = $this->floor_m->get_floor();
        if(permissionChecker('floor_add')) {
    		if($_POST) {
    			$rules = $this->rules();
    			$this->form_validation->set_rules($rules);
    			if ($this->form_validation->run() == FALSE) {
    				$this->data["subview"] = "floor/index";
    				$this->load->view('_layout_main', $this->data);
    			} else {
    				$array = [];
    				$array['name'] 			= $this->input->post('name');
    				$array['description'] 	= $this->input->post('description');
    				$array['create_date'] 	= date('Y-m-d H:i:s');
    				$array['modify_date'] 	= date('Y-m-d H:i:s');
    				$array['create_userID'] = $this->session->userdata('loginuserID');
    				$array['create_roleID'] = $this->session->userdata('roleID');

    				$this->floor_m->insert_floor($array);
    				$this->session->set_flashdata('success','Success');
    				redirect(site_url("floor/index"));
    			}
    		} else {
    			$this->data["subview"] = "floor/index";
    			$this->load->view('_layout_main', $this->data);
    		}
        } else {
            $this->data["subview"] = "floor/index";
            $this->load->view('_layout_main', $this->data);
        }
	}

	public function edit()
    {
		$floorID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$floorID) {
			$this->data['floor']  = $this->floor_m->get_single_floor(array('floorID'=>$floorID));
			if(inicompute($this->data['floor'])) {
			    $this->data['floors'] = $this->floor_m->get_floor();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "floor/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
						$array = [];
						$array['name'] 		  = $this->input->post('name');
						$array['description'] = $this->input->post('description');
						$array['modify_date'] = date('Y-m-d H:i:s');

						$this->floor_m->update_floor($array, $floorID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("floor/index"));
					}
				} else {
					$this->data["subview"] = "floor/edit";
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

	public function delete()
    {
        $floorID = escapeString($this->uri->segment('3'));
        if((int)$floorID) {
            $floor = $this->floor_m->get_single_floor(array('floorID'=>$floorID));
            if(inicompute($floor)) {
                $this->floor_m->delete_floor($floorID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('floor/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_floor($name)
    {
        $floorID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$floorID){
            $floor = $this->floor_m->get_order_by_floor(array("name" => $name, "floorID !=" => $floorID));
            if(inicompute($floor)) {
                $this->form_validation->set_message("unique_floor", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $floor = $this->floor_m->get_order_by_floor(array("name" => $name));
            if(inicompute($floor)) {
                $this->form_validation->set_message("unique_floor", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }
}