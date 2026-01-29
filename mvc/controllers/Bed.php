<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bed extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
		$this->load->model("bed_m");
        $this->load->model("ward_m");
        $this->load->model("bedtype_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('bed', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("bed_name"),
				'rules' => 'trim|required|max_length[60]|callback_unique_bed'
			),
            array(
                'field' => 'bedtypeID',
                'label' => $this->lang->line("bed_bedtype"),
                'rules' => 'trim|required|callback_required_no_zero'
            ),
            array(
                'field' => 'wardID',
                'label' => $this->lang->line("bed_ward"),
                'rules' => 'trim|required|callback_required_no_zero'
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
                'assets/inilabs/bed/index.js',
            )
        );

		$this->data['beds']     = $this->bed_m->get_bed();
        $this->data['bedtypes'] = pluck($this->bedtype_m->get_bedtype(), 'name', 'bedtypeID');
        $this->data['wards']    = pluck($this->ward_m->get_ward(), 'name', 'wardID');
        if(permissionChecker('bed_add')) {
    		if($_POST) {
    			$rules = $this->rules();
    			$this->form_validation->set_rules($rules);
    			if ($this->form_validation->run() == FALSE) {
    				$this->data["subview"] = "bed/index";
    				$this->load->view('_layout_main', $this->data);
    			} else {
    				$array['name'] 			= $this->input->post('name');
    				$array['bedtypeID'] 	= $this->input->post('bedtypeID');
                    $array['wardID']        = $this->input->post('wardID');
    				$array['create_date'] 	= date('Y-m-d H:i:s');
    				$array['modify_date'] 	= date('Y-m-d H:i:s');
    				$array['create_userID'] = $this->session->userdata('loginuserID');
    				$array['create_roleID'] = $this->session->userdata('roleID');

    				$this->bed_m->insert_bed($array);
    				$this->session->set_flashdata('success','Success');
    				redirect(site_url("bed/index"));
    			}
    		} else {
    			$this->data["subview"] = "bed/index";
    			$this->load->view('_layout_main', $this->data);
    		}
        } else {
            $this->data["subview"] = "bed/index";
            $this->load->view('_layout_main', $this->data);
        }
	}

	public function edit()
    {
		$bedID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$bedID) {
    		$this->data['bed']      = $this->bed_m->get_single_bed(array('bedID'=>$bedID));
			if(inicompute($this->data['bed'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/inilabs/bed/index.js',
                    )
                );
                
                $this->data['bedtypes'] = pluck($this->bedtype_m->get_bedtype(), 'name', 'bedtypeID');
                $this->data['wards']    = pluck($this->ward_m->get_ward(), 'name', 'wardID');
			    $this->data['beds']     = $this->bed_m->get_bed();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "bed/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                        $array['name']        = $this->input->post('name');
                        $array['bedtypeID']   = $this->input->post('bedtypeID');
                        $array['wardID']      = $this->input->post('wardID');
                        $array['modify_date'] = date('Y-m-d H:i:s');

						$this->bed_m->update_bed($array, $bedID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("bed/index"));
					}
				} else {
					$this->data["subview"] = "bed/edit";
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
        $bedID = escapeString($this->uri->segment('3'));
        if((int)$bedID) {
            $bed = $this->bed_m->get_single_bed(array('bedID'=>$bedID));
            if(inicompute($bed)) {
                $this->bed_m->delete_bed($bedID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('bed/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_bed($name) {
        $bedID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$bedID) {
            $bed = $this->bed_m->get_order_by_bed(array("name" => $name, "bedID !=" => $bedID));
            if(inicompute($bed)) {
                $this->form_validation->set_message("unique_bed", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $bed = $this->bed_m->get_order_by_bed(array("name" => $name));
            if(inicompute($bed)) {
                $this->form_validation->set_message("unique_bed", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        }
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