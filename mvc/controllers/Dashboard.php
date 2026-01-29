<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller
{
    protected $_versionCheckingUrl = 'http://demo.inilabs.net/autoupdate/update/index';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('income_m');
		$this->load->model('medicinesalepaid_m');
		$this->load->model('billpayment_m');
		$this->load->model('expense_m');
		$this->load->model('medicinepurchasepaid_m');
		$this->load->model('makepayment_m');
		$this->load->model('role_m');
		$this->load->model('designation_m');
		$this->load->model('attendance_m');
		$this->load->model('user_m');
		$this->load->model('loginlog_m');
		$this->load->model('notice_m');
		$this->load->model('event_m');
		$this->load->model('user_m');
		$this->load->model('patient_m');
		$this->load->model('menulog_m');
		$this->load->model('appointment_m');
		$this->load->model('admission_m');
		$this->load->model('prescription_m');
		$this->load->model('discharge_m');
		$this->load->model('bed_m');
		$this->load->model('income_m');
		$this->load->model('expense_m');
		$this->load->model('bill_m');
		$this->load->model('billpayment_m');
		$this->load->model('test_m');
		$this->load->model('medicinepurchase_m');
		$this->load->model('medicinesale_m');
		$this->load->model('blooddonor_m');
		$this->load->model('ambulancecall_m');
		$this->load->model('medicine_m');
		$this->load->model('testlabel_m');
		$this->load->model('testcategory_m');
		$this->load->model('heightweightbp_m');

		$language = $this->session->userdata('lang');
		$this->lang->load('dashboard', $language);
	}

	public function index()
	{
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/fullcalendar/lib/cupertino/jquery-ui.min.css',
				'assets/fullcalendar/fullcalendar.css',
			),
			'js' => array(
				'assets/highcharts/highcharts.js',
				'assets/highcharts/highcharts-more.js',
				'assets/highcharts/data.js',
				'assets/highcharts/drilldown.js',
				'assets/highcharts/exporting.js',
				'assets/fullcalendar/lib/jquery-ui.min.js',
				'assets/fullcalendar/lib/moment.min.js',
				'assets/fullcalendar/fullcalendar.min.js',
			)
		);

        $this->_tails();
		$this->_profile();
		$this->_incomeExpenseGraph();
		$this->_attendanceGraph();
		$this->_visitorGraph();
		$this->_update();

        $this->data["subview"] = 'dashboard/index';
        $this->load->view('_layout_main', $this->data);
	}

	private function _tails()
    {
        if($this->data['loginroleID'] == 2) {
            $appointments       = $this->appointment_m->get_select_appointment('appointmentID, patientID', ['doctorID' => $this->data['loginuserID'], 'DATE(appointmentdate)' => date('Y-m-d')]);
            $admissions         = $this->admission_m->get_select_admission('admissionID, patientID', ['doctorID' => $this->data['loginuserID'], 'status' => 0]);
            $prescriptions      = $this->prescription_m->get_select_prescription('prescriptionID, patientID', ['doctorID' => $this->data['loginuserID']]);
            $bills              = $this->bill_m->get_select_bill('billID, patientID', ['DATE(date)' => date('Y-m-d'), 'delete_at' => 0]);
            $billpayments       = $this->billpayment_m->get_select_billpayment('billpaymentID, patientID', ['patientID' => $this->data['loginuserID'], 'delete_at' => 0]);
            $tests              = $this->test_m->get_select_test('testID, billID', ['DATE(create_date)' => date('Y-m-d')]);
            $medicinesales      = $this->medicinesale_m->get_select_medicinesale('medicinesaleID, medicinesaledate', ['DATE(medicinesaledate)' => date('Y-m-d')]);
            $patients           = $this->patient_m->get_select_patient('patientID, name', ['delete_at' => 0, 'patienttypeID !=' => 5]);
            $blooddonors        = $this->blooddonor_m->get_select_blooddonor('blooddonorID, name', ['DATE(create_date)' => date('Y-m-d')]);
        } elseif($this->data['loginroleID'] == 3) {
            $appointments       = $this->appointment_m->get_select_appointment('appointmentID, patientID', ['patientID' => $this->data['loginuserID']]);
            $admissions         = $this->admission_m->get_select_admission('admissionID, patientID', ['patientID' => $this->data['loginuserID']]);
            $prescriptions      = $this->prescription_m->get_select_prescription('prescriptionID, patientID', ['patientID' => $this->data['loginuserID']]);
            $bills              = $this->bill_m->get_select_bill('billID, patientID', ['patientID' => $this->data['loginuserID'], 'delete_at' => 0]);
            $billpayments       = $this->billpayment_m->get_select_billpayment('billpaymentID, patientID', ['patientID' => $this->data['loginuserID'], 'delete_at' => 0]);
            $tests              = $this->test_m->get_select_test_with_bill('test.testID, test.billID', ['bill.patientID' => $this->data['loginuserID']]);
            $medicinesales      = $this->medicinesale_m->get_select_medicinesale('medicinesaleID, medicinesaledate', ['uhid' => $this->data['loginuserID']]);
            $patients           = $this->patient_m->get_select_patient('patientID, name', ['delete_at' => 0, 'patienttypeID !=' => 5, 'patientID' => $this->data['loginuserID']]);
            $blooddonors        = $this->blooddonor_m->get_select_blooddonor('blooddonorID, name', ['patientID' => $this->data['loginuserID']]);
        } else {
            $appointments       = $this->appointment_m->get_select_appointment('appointmentID, patientID', ['DATE(appointmentdate)' => date('Y-m-d')]);
            $admissions         = $this->admission_m->get_select_admission('admissionID, patientID', ['status' => 0]);
            $prescriptions      = $this->prescription_m->get_select_prescription('prescriptionID, patientID');
            $bills              = $this->bill_m->get_select_bill('billID, patientID', ['DATE(date)' => date('Y-m-d'), 'delete_at' => 0]);
            $billpayments       = $this->billpayment_m->get_select_billpayment('billpaymentID, patientID', ['DATE(paymentdate)' => date('Y-m-d'), 'delete_at' => 0]);
            $tests              = $this->test_m->get_select_test('testID, billID', ['DATE(create_date)' => date('Y-m-d')]);
            $medicinesales      = $this->medicinesale_m->get_select_medicinesale('medicinesaleID, medicinesaledate', ['DATE(medicinesaledate)' => date('Y-m-d')]);
            $patients           = $this->patient_m->get_select_patient('patientID, name', ['delete_at' => 0, 'patienttypeID !=' => 5]);
            $blooddonors        = $this->blooddonor_m->get_select_blooddonor('blooddonorID, name', ['DATE(create_date)' => date('Y-m-d')]);
        }

        $users              = $this->user_m->get_select_user('userID, name', ['roleID !=' => 3, 'delete_at' => 0]);
        $discharges         = $this->discharge_m->get_select_discharge('dischargeID, patientID');
        $beds               = $this->bed_m->get_select_bed('bedID, name', ['patientID' => 0, 'status' => 0]);
        $incomes            = $this->income_m->get_select_income('incomeID, name');
        $expenses           = $this->expense_m->get_select_expense('expenseID, name');
        $testcategorys      = $this->testcategory_m->get_select_testcategory('testcategoryID, name');
        $testlabels         = $this->testlabel_m->get_select_testlabel('testlabelID, name');
        $medicine           = $this->medicine_m->get_select_medicine('medicineID, name');
        $medicinepurchases  = $this->medicinepurchase_m->get_select_medicinepurchase('medicinepurchaseID, medicinepurchasedate', ['DATE(medicinepurchasedate)' => date('Y-m-d')]);
        $ambulancecalls     = $this->ambulancecall_m->get_select_ambulancecall('ambulancecallID, ambulanceID', ['DATE(date)' => date('Y-m-d')]);
        $notices            = $this->notice_m->get_order_by_notice(['year' => date('Y')]);
        $events             = $this->event_m->get_order_by_event(['YEAR(fdate)' => date('Y')]);
        $physicalconditions = $this->heightweightbp_m->get_select_heightweightbp('heightweightbpID, patientID');

        $this->data['dashboardwidget']['appointment']           = inicompute($appointments);
        $this->data['dashboardwidget']['admission']             = inicompute($admissions);
        $this->data['dashboardwidget']['physicalcondition']     = inicompute($physicalconditions);
        $this->data['dashboardwidget']['patient']               = inicompute($patients);
        $this->data['dashboardwidget']['user']                  = inicompute($users);
        $this->data['dashboardwidget']['prescription']          = inicompute($prescriptions);
        $this->data['dashboardwidget']['discharge']             = inicompute($discharges);
        $this->data['dashboardwidget']['bed']                   = inicompute($beds);
        $this->data['dashboardwidget']['income']                = inicompute($incomes);
        $this->data['dashboardwidget']['expense']               = inicompute($expenses);
        $this->data['dashboardwidget']['bill']                  = inicompute($bills);
        $this->data['dashboardwidget']['billpayment']           = inicompute($billpayments);
        $this->data['dashboardwidget']['testcategory']          = inicompute($testcategorys);
        $this->data['dashboardwidget']['testlabel']             = inicompute($testlabels);
        $this->data['dashboardwidget']['test']                  = inicompute($tests);
        $this->data['dashboardwidget']['medicine']              = inicompute($medicine);
        $this->data['dashboardwidget']['medicinepurchase']      = inicompute($medicinepurchases);
        $this->data['dashboardwidget']['medicinesale']          = inicompute($medicinesales);
        $this->data['dashboardwidget']['blooddonor']            = inicompute($blooddonors);
        $this->data['dashboardwidget']['ambulancecall']         = inicompute($ambulancecalls);
        $this->data['dashboardwidget']['notice']                = inicompute($notices);
        $this->data['dashboardwidget']['event']                 = inicompute($events);

        $this->data['notices']  = $notices;
        $this->data['events']   = $events;
        $this->data['menulogs'] = pluck($this->menulog_m->get_select_menulog('name, icon, link'),'obj', 'link');
    }

	private function _profile()
    {
        $this->data['profileinfouser']     = $this->user_m->get_single_user(array('userID' => $this->session->userdata('loginuserID')));
        if(inicompute($this->data['profileinfouser'])) {
            $this->data['profileinfouserdesignation'] = $this->designation_m->get_single_designation(['designationID' => $this->data['profileinfouser']->designationID]);
        } else {
            $this->data['designation'] = [];
        }
    }

	private function _incomeExpenseGraph()
    {
		$months = array(
		    '1'=>'January',
		    'February',
		    'March',
		    'April',
		    'May',
		    'June',
		    'July ',
		    'August',
		    'September',
		    'October',
		    'November',
		    'December',
		);

		$incomes            = $this->income_m->get_order_by_income(array('incomeyear'=> date('Y')));
        $medicinesalepaids  = $this->medicinesalepaid_m->get_order_by_medicinesalepaid_for_report([], FALSE);
        $billpayments       = $this->billpayment_m->get_order_by_billpayment(array('YEAR(paymentdate)'=>date('Y'), 'delete_at'=>0));
        $ambulancecalls     = $this->ambulancecall_m->get_order_by_ambulancecall(['YEAR(date)' => date('Y')]);

		$income_month_day_total = [];
		$income_month_total     = [];
		if(inicompute($incomes)) {
			foreach ($incomes as $income) {
				if(!isset($income_month_day_total[(int)$income->incomemonth][(string)$income->incomeday])) {
					$income_month_day_total[(int)$income->incomemonth][(string)$income->incomeday] = 0;
				}
				$income_month_day_total[(int)$income->incomemonth][(string)$income->incomeday] += $income->amount;

				if(!isset($income_month_total[(int)$income->incomemonth])) {
					$income_month_total[(int)$income->incomemonth] = 0;
				}
				$income_month_total[(int)$income->incomemonth] += $income->amount;
			}
		}

		if(inicompute($medicinesalepaids)) {
			foreach ($medicinesalepaids as $medicinesalepaid) {
				$medicinesalepaid_day   = date('d',  strtotime($medicinesalepaid->medicinesalepaiddate));
				$medicinesalepaid_month = date('m',  strtotime($medicinesalepaid->medicinesalepaiddate));

				if(!isset($income_month_day_total[(int)$medicinesalepaid_month][(string)$medicinesalepaid_day])) {
					$income_month_day_total[(int)$medicinesalepaid_month][(string)$medicinesalepaid_day] = 0;
				}
				$income_month_day_total[(int)$medicinesalepaid_month][(string)$medicinesalepaid_day] += $medicinesalepaid->medicinesalepaidamount;

				if(!isset($income_month_total[(int)$medicinesalepaid_month])) {
					$income_month_total[(int)$medicinesalepaid_month] = 0;
				}
				$income_month_total[(int)$medicinesalepaid_month] += $medicinesalepaid->medicinesalepaidamount;
			}
		}

		if(inicompute($billpayments)) {
			foreach ($billpayments as $billpayment) {
				$billpayment_day   = date('d',  strtotime($billpayment->paymentdate));
				$billpayment_month = date('m',  strtotime($billpayment->paymentdate));

				if(!isset($income_month_day_total[(int)$billpayment_month][(string)$billpayment_day])) {
					$income_month_day_total[(int)$billpayment_month][(string)$billpayment_day] = 0;
				}
				$income_month_day_total[(int)$billpayment_month][(string)$billpayment_day] += $billpayment->paymentamount;

				if(!isset($income_month_total[(int)$billpayment_month])) {
					$income_month_total[(int)$billpayment_month] = 0;
				}
				$income_month_total[(int)$billpayment_month] += $billpayment->paymentamount;
			}
		}

		if(inicompute($ambulancecalls)) {
			foreach ($ambulancecalls as $ambulancecall) {
				$ambulancecall_day   = date('d',  strtotime($ambulancecall->date));
				$ambulancecall_month = date('m',  strtotime($ambulancecall->date));

				if(!isset($income_month_day_total[(int)$ambulancecall_month][(string)$ambulancecall_day])) {
					$income_month_day_total[(int)$ambulancecall_month][(string)$ambulancecall_day] = 0;
				}
				$income_month_day_total[(int)$ambulancecall_month][(string)$ambulancecall_day] += $ambulancecall->amount;

				if(!isset($income_month_total[(int)$ambulancecall_month])) {
					$income_month_total[(int)$ambulancecall_month] = 0;
				}
				$income_month_total[(int)$ambulancecall_month] += $ambulancecall->amount;
			}
		}

		// Expense Code
		$expenses              = $this->expense_m->get_order_by_expense(array('expenseyear'=> date('Y')));
        $medicinepurchasepaids = $this->medicinepurchasepaid_m->get_order_by_medicinepurchasepaid_for_report([], FALSE);
        $makepayments          = $this->makepayment_m->get_order_by_makepayment(array('YEAR(create_date)'=>date('Y')));

        $expense_month_day_total = [];
		$expense_month_total     = [];
		if(inicompute($expenses)) {
			foreach ($expenses as $expense) {
				if(!isset($expense_month_day_total[(int)$expense->expensemonth][$expense->expenseday])) {
					$expense_month_day_total[(int)$expense->expensemonth][(string)$expense->expenseday] = 0;
				}
				$expense_month_day_total[(int)$expense->expensemonth][(string)$expense->expenseday] += $expense->amount;

				if(!isset($expense_month_total[(int)$expense->expensemonth])) {
					$expense_month_total[(int)$expense->expensemonth] = 0;
				}
				$expense_month_total[(int)$expense->expensemonth] += $expense->amount;
			}
		}

		if(inicompute($medicinepurchasepaids)) {
			foreach ($medicinepurchasepaids as $medicinepurchasepaid) {
				$medicinepurchasepaid_day   = date('d',  strtotime($medicinepurchasepaid->medicinepurchasepaiddate));
				$medicinepurchasepaid_month = date('m',  strtotime($medicinepurchasepaid->medicinepurchasepaiddate));

				if(!isset($expense_month_day_total[(int)$medicinepurchasepaid_month][$medicinepurchasepaid_day])) {
					$expense_month_day_total[(int)$medicinepurchasepaid_month][(string)$medicinepurchasepaid_day] = 0;
				}
				$expense_month_day_total[(int)$medicinepurchasepaid_month][(string)$medicinepurchasepaid_day] += $medicinepurchasepaid->medicinepurchasepaidamount;

				if(!isset($expense_month_total[(int)$medicinepurchasepaid_month])) {
					$expense_month_total[(int)$medicinepurchasepaid_month] = 0;
				}
				$expense_month_total[(int)$medicinepurchasepaid_month] += $medicinepurchasepaid->medicinepurchasepaidamount;
			}
		}

		if(inicompute($makepayments)) {
			foreach ($makepayments as $makepayment) {
				$makepayment_day   = date('d',  strtotime($makepayment->create_date));
				$makepayment_month = date('m',  strtotime($makepayment->create_date));
				if(!isset($expense_month_day_total[(int)$makepayment_month][$makepayment_day])) {
					$expense_month_day_total[(int)$makepayment_month][(string)$makepayment_day] = 0;
				}
				$expense_month_day_total[(int)$makepayment_month][(string)$makepayment_day] += $makepayment->payment_amount;

				if(!isset($expense_month_total[(int)$makepayment_month])) {
					$expense_month_total[(int)$makepayment_month] = 0;
				}
				$expense_month_total[(int)$makepayment_month] += $makepayment->payment_amount;
			}
		}

		$this->data["months"]  				   = $months;
		$this->data["income_month_day_total"]  = $income_month_day_total;
		$this->data["income_month_total"]      = $income_month_total;
		$this->data["expense_month_day_total"] = $expense_month_day_total;
		$this->data["expense_month_total"]     = $expense_month_total;
	}

	private function _attendanceGraph()
    {
		$attendances  = $this->attendance_m->get_order_by_attendance(array('year' => date('Y'), 'monthyear' => date('m-Y')));
		$role_wise_attendance = [];
		if(inicompute($attendances)) {
			foreach($attendances as $attendance) {
				for($i=1;$i<=31;$i++) {
					if($i > date('d')) break;

					$date = 'a'.$i;
					if(!isset($role_wise_attendance[$attendance->roleID][$i]['P'])) {
						$role_wise_attendance[$attendance->roleID][$i]['P'] = 0;
					}
					if(!isset($role_wise_attendance[$attendance->roleID][$i]['A'])) {
						$role_wise_attendance[$attendance->roleID][$i]['A'] = 0;
					}

					if($attendance->$date == 'P' || $attendance->$date == 'L' ||  $attendance->$date == 'LE') {
						$role_wise_attendance[$attendance->roleID][$i]['P']++;
					} else {
						$role_wise_attendance[$attendance->roleID][$i]['A']++;
					}
				}
			}
		}
		$today_attendance = [];
		if(inicompute($role_wise_attendance)) {
			foreach ($role_wise_attendance as $key => $value) {
				$today_attendance[$key] = $value[(int)date('d')];
			}
		}

		$this->data["roles"]   =  $this->role_m->get_order_by_filter_role([3]);
		$this->data['today_attendance']     = $today_attendance;
		$this->data['role_wise_attendance'] = $role_wise_attendance;
	}

	private function _visitorGraph()
    {
		$current_date        = strtotime(date('Y-m-d H:i:s'));
		$previous_seven_date = strtotime(date('Y-m-d 00:00:00', strtotime('-7 days')));
		$visitors            = $this->loginlog_m->get_order_by_loginlog(array('login <= ' => $current_date, 'login >= ' => $previous_seven_date));
		$show_chart_visitor = [];
		if(inicompute($visitors)) {
			foreach ($visitors as $visitor) {
				$date = date('j M',$visitor->login);
				if(!isset($show_chart_visitor[$date])) {
					$show_chart_visitor[$date] = 0;
				}
				$show_chart_visitor[$date]++;
			}
		}
		$this->data['show_chart_visitor'] = $show_chart_visitor;
	}

	public function dayWiseExpenseOrIncome()
    {
		$type     = $this->input->post('type');
		$monthID  = $this->input->post('monthID');
		$showChartData = [];
		if($type && $monthID) {
			$year = date('Y');
			$days = date('t', mktime(0, 0, 0, $monthID, 1, $year));
			$dayWiseData = json_decode($this->input->post('dayWiseData'), true);
			for ($i=1; $i <= $days; $i++) {
				if(!isset($dayWiseData[lzero($i)])) {
					$showChartData[$i] = 0;
				} else {
					$showChartData[$i] = isset($dayWiseData[lzero($i)]) ? $dayWiseData[lzero($i)] : 0;
				}
			}
		} else {
			for ($i=1; $i <= 31; $i++) {
				$showChartData[$i] = 0;
			}
		}
	    echo json_encode($showChartData);
	}

	public function getDayWiseAttendance()
    {
		$showChartData = [];
		if($this->input->post('dayWiseAttendance')) {
			$dayWiseAttendance = json_decode($this->input->post('dayWiseAttendance'), true);
			$type = $this->input->post('type');
			foreach ($dayWiseAttendance as $key => $value) {
				$showChartData[$key] = $value[$type];
			}
		}
		echo json_encode($showChartData);
	}

    private function _update()
    {
        if((config_item('demo') === FALSE) && ($this->data['generalsettings']->auto_update_notification == 1) && ($this->session->userdata('roleID') == 1) && ($this->session->userdata('loginuserID') == 1)) {
            if($this->session->userdata('updatestatus') === null) {
                $this->data['versionChecking'] = $this->_checkUpdate();
            } else {
                $this->data['versionChecking'] = 'none';
            }
        } else {
            $this->data['versionChecking'] = 'none';
        }
    }

    private function _checkUpdate()
    {
        $version = 'none';
        if($this->session->userdata('roleID') == 1 && $this->session->userdata('loginuserID') == 1) {
            if(inicompute($post = @$this->_postData())) {
                $versionChecking = $this->_versionChecking($post);
                if(isset($versionChecking->status)) {
                    $version = $versionChecking->version;
                } else {
                    $version = 'none';
                }
            }
        }
        return $version;
    }

    private function _postData()
    {
        $post = [];
        $this->load->model('update_m');
        $updates = $this->update_m->get_max_update();
        if(inicompute($updates)) {
            $post = array(
                'username'          => inicompute($this->data['generalsettings']) ? $this->data['generalsettings']->purchase_username : '',
                'purchasekey'       => inicompute($this->data['generalsettings']) ? $this->data['generalsettings']->purchase_code : '',
                'domainname'        => site_url(),
                'email'             => inicompute($this->data['generalsettings']) ? $this->data['generalsettings']->email : '',
                'currentversion'    => $updates->version,
                'projectname'       => 'hospital',
            );
        }
        return $post;
    }

    private function _versionChecking($postDatas)
    {
        $result = array(
            'status' => false,
            'message' => 'Error',
            'version' => 'none'
        );

        $postDataStrings = json_encode($postDatas);
        $ch = curl_init($this->_versionCheckingUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postDataStrings);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($postDataStrings)
            )
        );

        $serverResult = curl_exec($ch);
        curl_close($ch);
        if(inicompute($serverResult)) {
            $result = json_decode($serverResult, true);
        }
        return (object) $result;
    }

    public function update()
    {
        if($this->session->userdata('roleID') == 1 && $this->session->userdata('loginuserID') == 1) {
            $this->session->set_userdata('updatestatus', true);
            redirect(site_url('update/autoupdate'));
        }
        redirect(site_url('dashboard/index'));
    }

    public function remind()
    {
        if($this->session->userdata('roleID') == 1 && $this->session->userdata('loginuserID') == 1) {
            $this->session->set_userdata('updatestatus', false);
        }
        redirect(site_url('dashboard/index'));
    }
}
