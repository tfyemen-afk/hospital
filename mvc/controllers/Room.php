<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Room extends Admin_Controller 
{
	public function __construct() 
	{
		parent::__construct();
		$this->load->model("room_m");
		$language = $this->session->userdata('lang');
		$this->lang->load('room', $language);
	}

    protected function rules()
    {
        $rules = array(
            array(
                'field' => 'name',
                'label' => $this->lang->line("room_name"),
                'rules' => 'trim|required|max_length[40]|callback_unique_room'
            ),
            array(
                'field' => 'description',
                'label' => $this->lang->line("room_description"),
                'rules' => 'trim|max_length[200]'
            )
        );
        return $rules;
    }

	public function index() 
	{
	    $this->data['headerassets'] = [
	        'js' => [
	            'assets/inilabs/room/index.js'
            ]
        ];
		$this->data['rooms'] = $this->room_m->get_room();
		if(permissionChecker('room_add')) {
			if($_POST) {
                $rules  = $this->rules();
                $this->form_validation->set_rules($rules);
                if ($this->form_validation->run() == FALSE) {
                    $this->data["subview"] = 'room/index';
                    $this->load->view('_layout_main', $this->data);    
                } else {
                    $array['name']         = $this->input->post('name');
                    $array['description']  = $this->input->post('description');
                    $array['create_date']  = date('Y-m-d H:i:s');
                    $array['modify_date']  = date('Y-m-d H:i:s');
                    $array['create_userID']= $this->session->userdata('loginuserID');
                    $array['create_roleID']= $this->session->userdata('roleID');
                    
                    $this->room_m->insert_room($array);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('room/index'));
                }
            } else {
    		    $this->data["subview"] = 'room/index';
                $this->load->view('_layout_main', $this->data);
            }
		} else {
			$this->data["subview"] = "room/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function edit() 
	{
		$roomID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$roomID) {
            $this->data['headerassets'] = [
                'js' => [
                    'assets/inilabs/room/index.js'
                ]
            ];

			$this->data['room'] 	= $this->room_m->get_single_room(array('roomID' => $roomID));
			if(inicompute($this->data['room'])) {
				$this->data['rooms'] 	= $this->room_m->get_room();
				if($_POST) {
	                $rules  = $this->rules();
	                $this->form_validation->set_rules($rules);
	                if ($this->form_validation->run() == FALSE) {
	                    $this->data["subview"] = 'room/edit';
	                    $this->load->view('_layout_main', $this->data);    
	                } else {
	                    $array['name']         = $this->input->post('name');
	                    $array['description']  = $this->input->post('description');
	                    $array['modify_date']  = date('Y-m-d H:i:s');
	                    
	                    $this->room_m->update_room($array, $roomID);
	                    $this->session->set_flashdata('success','Success');
	                    redirect(site_url('room/index'));
	                }
	            } else {
	    		    $this->data["subview"] = 'room/edit';
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
        $roomID    = $this->input->post('roomID');
        $this->data["room"] = [];
        if((int)$roomID) {
            $this->data["room"] = $this->room_m->get_single_room(array('roomID'=> $roomID));;
        }
        $this->load->view('room/view', $this->data);
    }

	public function delete() 
	{
        $roomID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$roomID) {
        	$room = $this->room_m->get_single_room(array('roomID' => $roomID));
        	if(inicompute($room)) {
	            $this->room_m->delete_room($roomID);
	            $this->session->set_flashdata('success','Success');
            	redirect(site_url('room/index'));
        	} else {
	            $this->data["subview"] = '_not_found';
	            $this->load->view('_layout_main', $this->data);
        	}
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_room($name)
    {
        $roomID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$roomID){
            $room = $this->room_m->get_order_by_room(array("name" => $name, "roomID !=" => $roomID));
            if(inicompute($room)) {
                $this->form_validation->set_message("unique_room", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $room = $this->room_m->get_order_by_room(array("name" => $name));
            if(inicompute($room)) {
                $this->form_validation->set_message("unique_room", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

}