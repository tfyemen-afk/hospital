<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends Admin_Controller
{

	public function __construct()
    {
		parent::__construct();
		$this->load->model("test_m");
        $this->load->model("testcategory_m");
        $this->load->model("testlabel_m");
        $this->load->model("bill_m");
        $this->load->model("testfile_m");
        $this->load->model("patient_m");
		$language = $this->session->userdata('lang');;
		$this->lang->load('test', $language);
	}

	protected function rules()
    {
		$rules = array(
			array(
				'field' => 'billID',
				'label' => $this->lang->line("test_bill_no"),
				'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero|callback_check_billID'
			),
            array(
                'field' => 'testcategoryID',
                'label' => $this->lang->line("test_category"),
                'rules' => 'trim|required|numeric|callback_required_no_zero'
            ),
            array(
                'field' => 'testlabelID',
                'label' => $this->lang->line("test_label"),
                'rules' => 'trim|required|numeric|max_length[11]|callback_required_no_zero'
            ),
            array(
                'field' => 'testnote',
                'label' => $this->lang->line("test_note"),
                'rules' => 'trim|max_length[265]'
            )
		);
		return $rules;
	}

	private function _displayManager($displayID)
    {
        if($displayID == 2) {
            $displayArray['YEAR(create_date)']      = date('Y');
            $displayArray['MONTH(create_date)']     = date('m');
        } elseif($displayID == 3) {
            $displayArray['YEAR(create_date)']      = date('Y');
        } elseif($displayID == 4) {
            $displayArray                           = [];
        } else {
            $displayID = 1;
            $displayArray['DATE(create_date)']      = date('Y-m-d');
        }
        $this->data['displayID']    = $displayID;
        $this->data['tests']        = $this->test_m->get_order_by_test($displayArray);
        $this->data['bills']        = $this->bill_m->get_select_bill_patient('bill.billID, patient.patientID, patient.name');
        if($this->data['loginroleID'] == 3) {
            $patienQueryArray['bill.patientID'] = $this->data['loginuserID'];
            $this->data['tests']                = $this->test_m->get_select_test_with_bill('test.testID, test.testcategoryID, test.testlabelID, test.billID, test.create_date', $patienQueryArray);
            $this->data['bills']                = $this->bill_m->get_select_bill_patient('bill.billID, patient.patientID, patient.name', $patienQueryArray);
        }

        return $displayArray;
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
                'assets/inilabs/test/index.js',
            )
        );

        $displayID      = htmlentities(escapeString($this->uri->segment(3)));
        $this->_displayManager($displayID);

        $this->data['testcategorys']    = pluck($this->testcategory_m->get_select_testcategory(), 'name', 'testcategoryID');
        $this->data['testlabels']       = pluck($this->testlabel_m->get_select_testlabel(), 'name', 'testlabelID');

        $testcategoryID = $this->input->post('testcategoryID');
        $this->data['settestlabels']        = [];
        if((int)$testcategoryID) {
            $this->data['settestlabels']    = $this->testlabel_m->get_order_by_testlabel(array('testcategoryID'=>$testcategoryID));
        }

        if(permissionChecker('test_add')) {
    		if($_POST) {
    			$rules = $this->rules();
    			$this->form_validation->set_rules($rules);
    			if ($this->form_validation->run() == FALSE) {
    				$this->data["subview"] = "test/index";
    				$this->load->view('_layout_main', $this->data);
    			} else {
    				$array['billID'] 			= $this->input->post('billID');
    				$array['testcategoryID'] 	= $this->input->post('testcategoryID');
                    $array['testlabelID']       = $this->input->post('testlabelID');
                    $array['testnote']          = $this->input->post('testnote');
    				$array['create_date'] 	    = date('Y-m-d H:i:s');
    				$array['modify_date'] 	    = date('Y-m-d H:i:s');
    				$array['create_userID']     = $this->session->userdata('loginuserID');
    				$array['create_roleID']     = $this->session->userdata('roleID');

    				$this->test_m->insert_test($array);
    				$this->session->set_flashdata('success','Success');
    				redirect(site_url("test/index"));
    			}
    		} else {
    			$this->data["subview"] = "test/index";
    			$this->load->view('_layout_main', $this->data);
    		}
        } else {
            $this->data["subview"] = "test/index";
            $this->load->view('_layout_main', $this->data);
        }
	}

	public function edit()
    {
		$testID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayID  = htmlentities(escapeString($this->uri->segment(4)));
		if((int)$testID && (int)$displayID) {
		    $testQueryArray['test.testID'] = $testID;
		    if($this->data['loginroleID'] == 3) {
                $testQueryArray['bill.patientID'] = $this->data['loginuserID'];
            }
    		$this->data['test']      = $this->test_m->get_select_test_with_bill('test.*, bill.patientID', $testQueryArray, true);

			if(inicompute($this->data['test'])) {
                $this->data['headerassets'] = array(
                    'css' => array(
                        'assets/select2/css/select2.css',
                        'assets/select2/css/select2-bootstrap.css',
                    ),
                    'js' => array(
                        'assets/select2/select2.js',
                        'assets/inilabs/test/index.js',
                    )
                );
                $this->data['testID']           = $testID;
                $this->_displayManager($displayID);

                $this->data['testcategorys']    = pluck($this->testcategory_m->get_select_testcategory(), 'name', 'testcategoryID');
                $this->data['testlabels']       = pluck($this->testlabel_m->get_select_testlabel(), 'name', 'testlabelID');

                $testcategoryID = $this->input->post('testcategoryID');
                $this->data['settestlabels']   = [];
                if((int)$testcategoryID) {
                    $this->data['settestlabels']   = $this->testlabel_m->get_order_by_testlabel(array('testcategoryID'=>$testcategoryID));
                } else {
                    $this->data['settestlabels']   = $this->testlabel_m->get_order_by_testlabel(array('testcategoryID'=>$this->data['test']->testcategoryID));
                }
				
                if($_POST) {
					$rules = $this->rules();
					$this->form_validation->set_rules($rules);
					if ($this->form_validation->run() == FALSE) {
						$this->data["subview"] = "test/edit";
						$this->load->view('_layout_main', $this->data);
					} else {
                        $array['billID']         = $this->input->post('billID');
                        $array['testcategoryID'] = $this->input->post('testcategoryID');
                        $array['testlabelID']    = $this->input->post('testlabelID');
                        $array['testnote']       = $this->input->post('testnote');
                        $array['modify_date']    = date('Y-m-d H:i:s');

						$this->test_m->update_test($array, $testID);
						$this->session->set_flashdata('success','Success');
						redirect(site_url("test/index/".$displayID));
					}
				} else {
					$this->data["subview"] = "test/edit";
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
        if(permissionChecker('test_view')) {
            $testID = $this->input->post('testID');
            if((int)$testID) {
                $testQueryArray['test.testID'] = $testID;
                if($this->data['loginroleID'] == 3) {
                    $testQueryArray['bill.patientID'] = $this->data['loginuserID'];
                }
                $this->data['test']             = $this->test_m->get_select_test_with_bill_patient('test.*, patient.name, patient.patientID', $testQueryArray, true);
                if(inicompute($this->data['test'])) {
                    $this->data['testcategory'] = $this->testcategory_m->get_select_testcategory('name', ['testcategoryID' => $this->data['test']->testcategoryID], true);
                    $this->data['testlabel']    = $this->testlabel_m->get_select_testlabel('name', ['testlabelID' => $this->data['test']->testlabelID], true);
                    $this->load->view('test/view', $this->data);
                }
            }
        }
    }

    public function save_test_file()
    {
        $retArray = [];
        $retArray['status']  = FALSE;
        $retArray['message'] = '';
        if(permissionChecker('test_add')) {
            if($_FILES) {
                $this->form_validation->set_rules('testID', $this->lang->line('test_testID'), 'trim|required|numeric');
                $this->form_validation->set_rules('file', $this->lang->line('test_file'), 'trim|callback_unique_file_upload');
                if ($this->form_validation->run() == FALSE) {
                    $retArray['message'] = validation_errors();
                } else {
                    $array['testID']            = $this->input->post('testID');
                    $array['filename']          = $this->upload_data['file']['file_name'];
                    $array['fileoriginalname']  = $this->upload_data['file']['client_name'];
                    $array["create_date"]       = date("Y-m-d H:i:s");
                    $array["modify_date"]       = date("Y-m-d H:i:s");
                    $array["create_userID"]     = $this->session->userdata('loginuserID');
                    $array["create_roleID"]     = $this->session->userdata('roleID');
                    
                    $this->testfile_m->insert_testfile($array);
                    $this->session->set_flashdata('success','Success');
                    $retArray['status']  = TRUE;
                }
            } else {
                $retArray['message'] = $this->lang->line('test_file_not_found');
            }
        } else {
            $retArray['message'] = $this->lang->line('test_permission_not_allowed');
        }
        echo json_encode($retArray);
    }

    public function get_test_file()
    {
        if(permissionChecker('test_view')) {
            $testID = $this->input->post('testID');
            if((int)$testID) {
                $this->data['testfiles'] = $this->testfile_m->get_order_by_testfile(array('testID'=> $testID));
                $this->load->view('test/get_test_file', $this->data);
            }
        }
    }

	public function delete()
    {
        $testID     = htmlentities(escapeString($this->uri->segment(3)));
        $displayID  = htmlentities(escapeString($this->uri->segment(4)));
        if((int)$testID && (int)$displayID) {
            $testQueryArray['test.testID'] = $testID;
            if($this->data['loginroleID'] == 3) {
                $testQueryArray['bill.patientID'] = $this->data['loginuserID'];
            }
            $test = $this->test_m->get_select_test_with_bill('test.*, bill.patientID', $testQueryArray, true);
            if(inicompute($test)) {
                $this->test_m->delete_test($testID);
                $this->session->set_flashdata('success','Success');
                redirect(site_url('test/index/'.$displayID));
            } else {
                $this->data["subview"] = '_not_found';
                $this->load->view('_layout_main', $this->data);    
            }
        } else {
            $this->data["subview"] = '_not_found';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function get_label()
    {
        echo "<option value='0'>— ".$this->lang->line('test_please_select')." —</option>";
        if($_POST) {
            $testcategoryID = $this->input->post('testcategoryID');
            if((int)$testcategoryID) {
                $testlabels = $this->testlabel_m->get_order_by_testlabel(array('testcategoryID' => $testcategoryID));
                if(inicompute($testlabels)) {
                    foreach ($testlabels as $testlabel) {
                        echo "<option value='".$testlabel->testlabelID."'>".$testlabel->name."</option>";
                    }
                }
            }
        }
    }

    public function filedelete()
    {
        if(permissionChecker('test_delete')) {
            $testfileID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$testfileID) {
                $testFileQueryArray['testfile.testfileID'] = $testfileID;
                if($this->data['loginroleID'] == 3) {
                    $testFileQueryArray['bill.patientID'] = $this->data['loginuserID'];
                }
                $testfile = $this->testfile_m->get_select_testfile_with_test_bill('testfile.*', $testFileQueryArray, true);
                if(inicompute($testfile)) {
                    if(($testfile->filename != '') && (config_item('demo') == FALSE)) {
                        if(file_exists(FCPATH.'uploads/files/'.$testfile->filename)) {
                            unlink(FCPATH.'uploads/files/'.$testfile->filename);
                        }
                    }
                    $this->testfile_m->delete_testfile($testfileID);
                    $this->session->set_flashdata('success','Success');
                    redirect(site_url('test/index'));
                } else {
                    $this->data["subview"] = '_not_found';
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

    public function filedownload()
    {
        if(permissionChecker('test_view')) {
            $testfileID = htmlentities(escapeString($this->uri->segment(3)));
            if((int)$testfileID) {
                $testFileQueryArray['testfile.testfileID'] = $testfileID;
                if($this->data['loginroleID'] == 3) {
                    $testFileQueryArray['bill.patientID'] = $this->data['loginuserID'];
                }
                $testfile = $this->testfile_m->get_select_testfile_with_test_bill('testfile.*', $testFileQueryArray, true);
                if(inicompute($testfile)) {
                    $file = realpath('uploads/files/'.$testfile->filename);
                    if (file_exists($file)) {
                        $originalname = $testfile->fileoriginalname;
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="'.basename($originalname).'"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($file));
                        readfile($file);
                        exit;
                    } else {
                        redirect(site_url('test/index'));
                    }
                } else {
                    $this->data["subview"] = '_not_found';
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

    public function unique_file_upload() 
    {
        if($_FILES["file"]['name'] !="") {
            $file_name = $_FILES["file"]['name'];
            $random = random19();
            $makeRandom = hash('sha512', $random.(strtotime(date('Y-m-d H:i:s'))). config_item("encryption_key"));
            $file_name_rename = 'test_'.$makeRandom;
            $explode = explode('.', $file_name);
            if(inicompute($explode) >= 2) {
                $new_file = $file_name_rename.'.'.end($explode);
                $config['upload_path'] = "./uploads/files";
                $config['allowed_types'] = "gif|jpg|png|jpeg|pdf|doc|xml|docx|GIF|JPG|PNG|JPEG|PDF|DOC|XML|DOCX|xls|xlsx|txt|ppt|csv";
                $config['file_name'] = $new_file;
                $config['max_size'] = '5120';
                $config['max_width'] = '10000';
                $config['max_height'] = '10000';
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload("file")) {
                    $this->form_validation->set_message("unique_file_upload", $this->upload->display_errors());
                    return false;
                } else {
                    $this->upload_data['file'] =  $this->upload->data();
                    return true;
                }
            } else {
                $this->form_validation->set_message("unique_file_upload", "Invalid File");
                return false;
            }
        } else {
            $this->form_validation->set_message("unique_file_upload", "The File is required.");
            return false;
        }
    }

    public function check_billID($billID)
    {
        if($billID != '' && (int)$billID) {
            $bill = $this->bill_m->get_single_bill(array("billID" => $billID, 'delete_at' => 0));
            if(!inicompute($bill)) {
                $this->form_validation->set_message("check_billID", "This billID not exists.");
                return FALSE;
            }
        }
        return TRUE;
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