<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bedmanager extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ward_m');
        $this->load->model('bed_m');
        $this->load->model('bedtype_m');
        $this->load->model('patient_m');
        $this->load->model('bloodgroup_m');
        $this->load->model('room_m');

        $language = $this->session->userdata('lang');
        $this->lang->load('bedmanager', $language);
    }

	public function index()
    {
        $this->data['headerassets'] = [
                'js' => [
                        'assets/inilabs/bedmanager/index.js'
                ]
        ];
        $this->data['wards']    = $this->ward_m->get_ward();
        $this->data['beds']     = pluck_multi_array($this->bed_m->get_bed(), 'obj', 'wardID');
        $this->data['bedtypes'] = pluck($this->bedtype_m->get_bedtype(), 'name', 'bedtypeID');
        $this->data['rooms']    = pluck($this->room_m->get_room(), 'name', 'roomID');
	    $this->data["subview"]  = 'bedmanager/index';
        $this->load->view('_layout_main', $this->data);
	}

    public function view() {
        if(permissionChecker('bedmanager')) {
            $patientID = $this->input->post('patientID');
            $this->data['patient']     = [];
            if((int)$patientID) {
                $this->data['patient'] = $this->patient_m->get_single_patient(array('patientID'=> $patientID));
            }
            $this->data['bloodgroups']      = pluck($this->bloodgroup_m->get_bloodgroup(), 'bloodgroup', 'bloodgroupID');
            $this->data['maritalstatus']    = [
                1   => $this->lang->line('bedmanager_single'),
                2   => $this->lang->line('bedmanager_married'),
                3   => $this->lang->line('bedmanager_separated'),
                4   => $this->lang->line('bedmanager_divorced')
            ];
            $this->load->view('bedmanager/view', $this->data);
        } else { ?>
            <div class="error-card">
                <div class="error-title-block">
                    <h1 class="error-title">404</h1>
                    <h2 class="error-sub-title"> Sorry, data not found </h2>
                </div>
                <div class="error-container">
                    <a class="btn btn-primary" href="<?=site_url('dashboard/index')?>">
                    <i class="fa fa-angle-left"></i> Back to Dashboard</a>
                </div>
            </div>
        <?php }
    }
}
