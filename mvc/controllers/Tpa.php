<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tpa extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
		$this->load->model("tpa_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('tpa', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("tpa_name"),
				'rules' => 'trim|required|max_length[60]|callback_unique_name'
			),
			array(
				'field' => 'code',
				'label' => $this->lang->line("tpa_code"),
				'rules' => 'trim|required|max_length[60]'
			),
            array(
                'field' => 'email',
                'label' => $this->lang->line("tpa_email"),
                'rules' => 'trim|max_length[40]|valid_email|callback_unique_email'
            ),
            array(
                'field' => 'phone',
                'label' => $this->lang->line("tpa_phone"),
                'rules' => 'trim|max_length[20]'
            ),
            array(
                'field' => 'address',
                'label' => $this->lang->line("tpa_address"),
                'rules' => 'trim|max_length[255]'
            ),
            array(
                'field' => 'contact_person_name',
                'label' => $this->lang->line("tpa_contact_person_name"),
                'rules' => 'trim|max_length[60]'
            ),
            array(
                'field' => 'contact_person_phone',
                'label' => $this->lang->line("tpa_contact_person_phone"),
                'rules' => 'trim|max_length[20]'
            )
		);
		return $rules;
	}

	public function index()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/tpa/index.js'
            ]
        ];

		$this->data['tpas'] = $this->tpa_m->get_tpa();
        if(permissionChecker('tpa_add')) {
    		if($_POST) {
    			$rules = $this->rules();
    			$this->form_validation->set_rules($rules);
    			if ($this->form_validation->run() == FALSE) {
    				$this->data["subview"] = "tpa/index";
    				$this->load->view('_layout_main', $this->data);
    			} else {
    				$array['name']    = $this->input->post('name');
    				$array['code'] 	  = $this->input->post('code');
    				$array['email']   = $this->input->post('email');
                    $array['phone']   = $this->input->post('phone');
                    $array['address'] = $this->input->post('address');
                    $array['contact_person_name']    = $this->input->post('contact_person_name');
                    $array['contact_person_phone']   = $this->input->post('contact_person_phone');
    				$array['create_date'] 	= date('Y-m-d H:i:s');
    				$array['modify_date'] 	= date('Y-m-d H:i:s');
    				$array['create_userID'] = $this->session->userdata('loginuserID');
    				$array['create_roleID'] = $this->session->userdata('roleID');

    				$this->tpa_m->insert_tpa($array);
    				$this->session->set_flashdata('success','Success');
    				redirect(site_url("tpa/index"));
    			}
    		} else {
    			$this->data["subview"] = "tpa/index";
    			$this->load->view('_layout_main', $this->data);
    		}
        } else {
            $this->data["subview"] = "tpa/index";
            $this->load->view('_layout_main', $this->data);
        }
	}

	public function edit()
    {
        $this->data['headerassets'] = [
            'js' => [
                'assets/inilabs/tpa/index.js'
            ]
        ];

		$tpaID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$tpaID) {
			$this->data['tpa'] = $this->tpa_m->get_single_tpa(array('tpaID'=>$tpaID));
			if(inicompute($this->data['tpa'])) {
                $this->data['tpas'] = $this->tpa_m->get_tpa();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "tpa/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                        $array['name']    = $this->input->post('name');
                        $array['code']    = $this->input->post('code');
                        $array['email']   = $this->input->post('email');
                        $array['phone']   = $this->input->post('phone');
                        $array['address'] = $this->input->post('address');
                        $array['contact_person_name']    = $this->input->post('contact_person_name');
                        $array['contact_person_phone']   = $this->input->post('contact_person_phone');
                        $array['create_date']   = date('Y-m-d H:i:s');
                        $array['modify_date']   = date('Y-m-d H:i:s');
                        $array['create_userID'] = $this->session->userdata('loginuserID');
                        $array['create_roleID'] = $this->session->userdata('roleID');

						$this->tpa_m->update_tpa($array, $tpaID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("tpa/index"));
					}
				} else {
					$this->data["subview"] = "tpa/edit";
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
        $tpaID = escapeString($this->uri->segment('3'));
        if((int)$tpaID) {
            $tpa = $this->tpa_m->get_single_tpa(array('tpaID'=>$tpaID));
            if(inicompute($tpa)) {
                $this->tpa_m->delete_tpa($tpaID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('tpa/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function view()
    {
        $tpaID = $this->input->post('tpaID');
        if((int)$tpaID) {
            $tpa = $this->tpa_m->get_single_tpa(array('tpaID' => $tpaID));
            if(inicompute($tpa)) {
                echo '<div class="profile-view-dis">';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('tpa_name').'</span>: '.$tpa->name.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('tpa_code').'</span>: '.$tpa->code.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('tpa_email').'</span>: '.$tpa->email.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('tpa_phone').'</span>: '.$tpa->phone.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('tpa_contact_person_name').'</span>: '.$tpa->contact_person_name.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('tpa_contact_person_phone').'</span>: '.$tpa->contact_person_phone.'</p>';
                    echo '</div>';
                    echo '<div class="profile-view-tab">';
                        echo '<p><span>'.$this->lang->line('tpa_address').'</span>: '.$tpa->address.'</p>';
                    echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="error-card">';
                    echo '<div class="error-title-block">';
                        echo '<h1 class="error-title">404</h1>';
                        echo '<h2 class="error-sub-title"> Sorry, page not found </h2>';
                    echo '</div>';
                    echo '<div class="error-container">';
                        echo '<a class="btn btn-primary" href="'.site_url('dashboard/index').'">';
                        echo '<i class="fa fa-angle-left"></i> Back to Dashboard</a>';
                    echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<div class="error-card">';
                echo '<div class="error-title-block">';
                    echo '<h1 class="error-title">404</h1>';
                    echo '<h2 class="error-sub-title"> Sorry, page not found </h2>';
                echo '</div>';
                echo '<div class="error-container">';
                    echo '<a class="btn btn-primary" href="'.site_url('dashboard/index').'">';
                    echo '<i class="fa fa-angle-left"></i> Back to Dashboard</a>';
                echo '</div>';
            echo '</div>';
        }
    }

    public function unique_name($name)
    {
        $tpaID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$tpaID){
            $tpa = $this->tpa_m->get_order_by_tpa(array("name" => $name, "tpaID !=" => $tpaID));
            if(inicompute($tpa)) {
                $this->form_validation->set_message("unique_name", "This %s has already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $tpa = $this->tpa_m->get_order_by_tpa(array("name" => $name));
            if(inicompute($tpa)) {
                $this->form_validation->set_message("unique_name", "This %s has already exists.");
                return FALSE;
            }
            return TRUE;
        }
    }

    public function unique_email($email)
    {
        if($email) {
            $tpaID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$tpaID){
                $tpa = $this->tpa_m->get_order_by_tpa(array("email" => $email, "tpaID !=" => $tpaID));
                if(inicompute($tpa)) {
                    $this->form_validation->set_message("unique_email", "This %s has already exists.");
                    return FALSE;
                }
                return TRUE;
            } else {
                $tpa = $this->tpa_m->get_order_by_tpa(array("email" => $email));
                if(inicompute($tpa)) {
                    $this->form_validation->set_message("unique_email", "This %s has already exists.");
                    return FALSE;
                }
                return TRUE;
            }
        }
        return TRUE;
    }

}