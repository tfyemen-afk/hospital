<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Medicine extends Admin_Controller
{
	public function __construct()
    {
		parent::__construct();
		$this->load->model("medicine_m");
        $this->load->model("medicinecategory_m");
        $this->load->model("medicinemanufacturer_m");
        $this->load->model("medicineunit_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('medicine', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'name',
				'label' => $this->lang->line("medicine_name"),
				'rules' => 'trim|required|max_length[128]|callback_unique_medicine_name'
			),
            array(
                'field' => 'medicinecategoryID',
                'label' => $this->lang->line("medicine_category"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'medicinemanufacturerID',
                'label' => $this->lang->line("medicine_manufacturer"),
                'rules' => 'trim|required|numeric|max_length[128]|callback_required_no_zero'
            ),
            array(
                'field' => 'medicineunitID',
                'label' => $this->lang->line("medicine_medicineunit"),
                'rules' => 'trim|required|numeric|max_length[128]|callback_required_no_zero'
            ),
            array(
                'field' => 'buying_price',
                'label' => $this->lang->line("medicine_buying_price"),
                'rules' => 'trim|required|max_length[16]|numeric'
            ),
            array(
                'field' => 'selling_price',
                'label' => $this->lang->line("medicine_selling_price"),
                'rules' => 'trim|required|max_length[16]|numeric'
            )
		);
		return $rules;
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
                'assets/inilabs/medicine/index.js'
            ]
        ];

        $this->data['medicines']                = $this->medicine_m->get_medicine();
        $this->data['medicinecategorys']        = pluck($this->medicinecategory_m->get_medicinecategory(),'name','medicinecategoryID');
        $this->data['medicinemanufacturers']    = pluck($this->medicinemanufacturer_m->get_medicinemanufacturer(), 'name', 'medicinemanufacturerID');
        $this->data['medicineunits']            = $this->medicineunit_m->get_select_medicineunit();

        if(permissionChecker('medicine_add')) {
    		if($_POST) {
    			$rules = $this->rules();
    			$this->form_validation->set_rules($rules);
    			if ($this->form_validation->run() == FALSE) {
    				$this->data["subview"] = "medicine/index";
    				$this->load->view('_layout_main', $this->data);
    			} else {
    				$array['name'] 			            = $this->input->post('name');
    				$array['medicinecategoryID'] 	    = $this->input->post('medicinecategoryID');
                    $array['medicinemanufacturerID']    = $this->input->post('medicinemanufacturerID');
                    $array['medicineunitID']            = $this->input->post('medicineunitID');
                    $array['buying_price']              = $this->input->post('buying_price');
                    $array['selling_price']             = $this->input->post('selling_price');
    				$array['create_date'] 	            = date('Y-m-d H:i:s');
    				$array['modify_date'] 	            = date('Y-m-d H:i:s');
    				$array['create_userID']             = $this->session->userdata('loginuserID');
    				$array['create_roleID']             = $this->session->userdata('roleID');

    				$this->medicine_m->insert_medicine($array);
    				$this->session->set_flashdata('success','Success');
    				redirect(site_url("medicine/index"));
    			}
    		} else {
    			$this->data["subview"] = "medicine/index";
    			$this->load->view('_layout_main', $this->data);
    		}
        } else {
            $this->data["subview"] = "medicine/index";
            $this->load->view('_layout_main', $this->data);
        }
	}

	public function edit()
    {
		$medicineID = htmlentities(escapeString($this->uri->segment(3)));
		if((int)$medicineID) {
    		$this->data['singlemedicine']      = $this->medicine_m->get_single_medicine(array('medicineID'=>$medicineID));
			if(inicompute($this->data['singlemedicine'])) {
                $this->data['headerassets'] = [
                    'css' => [
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ],
                    'js'  => [
                        'assets/select2/select2.js',
                        'assets/inilabs/medicine/index.js'
                    ]
                ];

                $this->data['medicines']             = $this->medicine_m->get_medicine();
                $this->data['medicinecategorys']     = pluck($this->medicinecategory_m->get_medicinecategory(),'name','medicinecategoryID');
                $this->data['medicinemanufacturers'] = pluck($this->medicinemanufacturer_m->get_medicinemanufacturer(), 'name', 'medicinemanufacturerID');
                $this->data['medicineunits']         = $this->medicineunit_m->get_select_medicineunit();
				if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "medicine/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                        $array['name']                      = $this->input->post('name');
                        $array['medicinecategoryID']        = $this->input->post('medicinecategoryID');
                        $array['medicinemanufacturerID']    = $this->input->post('medicinemanufacturerID');
                        $array['medicineunitID']            = $this->input->post('medicineunitID');
                        $array['buying_price']              = $this->input->post('buying_price');
                        $array['selling_price']             = $this->input->post('selling_price');
                        $array['modify_date']               = date('Y-m-d H:i:s');

						$this->medicine_m->update_medicine($array, $medicineID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("medicine/index"));
					}
				} else {
					$this->data["subview"] = "medicine/edit";
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
        $medicineID = $this->input->post('medicineID');
        if((int)$medicineID) {
            $this->data['medicine'] = $this->medicine_m->get_single_medicine(array('medicineID'=> $medicineID));
            if(inicompute($this->data['medicine'])) {
                $this->data['medicinecategory']      = $this->medicinecategory_m->get_single_medicinecategory(array('medicinecategoryID'=> $this->data['medicine']->medicinecategoryID));
                $this->data['medicinemanufacturer']  = $this->medicinemanufacturer_m->get_single_medicinemanufacturer(array('medicinemanufacturerID'=> $this->data['medicine']->medicinemanufacturerID));
                $this->data['medicineunit']  = $this->medicineunit_m->get_single_medicineunit(array('medicineunitID'=> $this->data['medicine']->medicineunitID));
                $this->load->view('medicine/view', $this->data);    
            } else {
                $this->load->view('modal_not_found', $this->data);    
            }
        } else {
            $this->load->view('modal_not_found', $this->data);    
        }
    }

	public function delete()
    {
        $medicineID = htmlentities(escapeString($this->uri->segment('3')));
        if((int)$medicineID) {
            $medicine = $this->medicine_m->get_single_medicine(array('medicineID'=>$medicineID));
            if(inicompute($medicine)) {
                $this->medicine_m->delete_medicine($medicineID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('medicine/index'));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function unique_medicine_name($name)
    {
        $medicineID = htmlentities(escapeString($this->uri->segment(3)));
        if((int)$medicineID) {
            $medicine = $this->medicine_m->get_order_by_medicine(array("name" => $name, "medicineID !=" => $medicineID));
            if(inicompute($medicine)) {
                $this->form_validation->set_message("unique_medicine_name", "This %s already exists.");
                return FALSE;
            }
            return TRUE;
        } else {
            $medicine = $this->medicine_m->get_order_by_medicine(array("name" => $name));
            if(inicompute($medicine)) {
                $this->form_validation->set_message("unique_medicine_name", "This %s already exists.");
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