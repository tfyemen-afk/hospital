<?php
$time = (function($time) {
    if($time > 60) {
        $hours  = (int)($time/60);
        $minute = ($time%60);
        return lzero($hours) . ':' .lzero($minute) .' M';
    }
    return lzero($time) .' M';
});


$replace = (function($url) {
    return str_replace('http:', 'https:', $url);
});
?>
<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('patient_print'))?>
                <?=btn_sm_pdf('patient/printpreview/'.$patient->patientID, $this->lang->line('patient_pdf_preview'))?>
                <?=btn_sm_edit('patient_edit', 'patient/edit/'.$patient->patientID, $this->lang->line('patient_edit'))?>
                <?=btn_sm_mail($this->lang->line('patient_send_pdf_to_mail'))?>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('patient/index')?>"> <?=$this->lang->line('menu_patient')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('patient_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-3 user-profile-box">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img src="<?=imagelink($patient->photo, 'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
                            <h3 class="profile-username text-center"><?=$patient->name?></h3>
                            <p class="text-muted text-center"><?=isset($designations[inicompute($user) ? $user->designationID : 2]) ? $designations[inicompute($user) ? $user->designationID : 2] : ''?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('patient_uhid')?></b> <a class="pull-right"><?=$patient->patientID?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('patient_type')?></b> <a class="pull-right"><?=($patient->patienttypeID == 0) ? $this->lang->line('patient_opd') : $this->lang->line('patient_ipd') ?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('patient_gender')?></b> <a class="pull-right"><?=($patient->gender == '1')? $this->lang->line('patient_male'): $this->lang->line('patient_female')?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('patient_age')?></b> <a class="pull-right"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year);?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('patient_phone')?></b> <a class="pull-right"><?=$patient->phone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><?=$this->lang->line('patient_profile')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="appointment-tab" data-toggle="tab" href="#appointment" role="tab" aria-controls="appointment" aria-selected="true"><?=$this->lang->line('patient_appointment')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="liveappointment-tab" data-toggle="tab" href="#liveappointment" role="tab" aria-controls="liveappointment" aria-selected="true"><?=$this->lang->line('patient_liveappointment')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="admission-tab" data-toggle="tab" href="#admission" role="tab" aria-controls="admission" aria-selected="true"><?=$this->lang->line('patient_admission')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="physicalcondition-tab" data-toggle="tab" href="#physicalcondition" role="tab" aria-controls="physicalcondition" aria-selected="true"><?=$this->lang->line('patient_physicalcondition')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="instruction-tab" data-toggle="tab" href="#instruction" role="tab" aria-controls="instruction" aria-selected="true"><?=$this->lang->line('patient_instruction')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bill-tab" data-toggle="tab" href="#bill" role="tab" aria-controls="bill" aria-selected="true"><?=$this->lang->line('patient_bill')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="true"><?=$this->lang->line('patient_payment')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="test-tab" data-toggle="tab" href="#test" role="tab" aria-controls="test" aria-selected="true"><?=$this->lang->line('patient_test')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active overflow-hidden" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="profile-view-dis">
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('patient_guardianname')?> </span>: <?=$patient->guardianname?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('patient_maritalstatus')?> </span>: <?=isset($maritalstatus[$patient->maritalstatus]) ? $maritalstatus[$patient->maritalstatus] : '' ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('patient_bloodgroup')?> </span>: <?=isset($bloodgroups[$patient->bloodgroupID]) ? $bloodgroups[$patient->bloodgroupID] : ''?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('patient_email')?> </span>: <?=$user->email?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('patient_username')?> </span>: <?=$user->username?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('patient_address')?> </span>: <?=$patient->address?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="appointment" role="tabpanel" aria-labelledby="appointment-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('patient_slno')?></th>
                                            <th><?=$this->lang->line('patient_appointmentdate')?></th>
                                            <th><?=$this->lang->line('patient_doctor')?></th>
                                            <th><?=$this->lang->line('patient_symptoms')?></th>
                                            <?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
                                                <th><?=$this->lang->line('patient_action')?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(inicompute($appointments)) { $i = 1; foreach ($appointments as $appointment) {  ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('patient_slno')?>">
                                                    <?=$i?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_appointmentdate')?>">
                                                    <?=app_datetime($appointment->appointmentdate)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_doctor')?>">
                                                    <?=isset($doctors[$appointment->doctorID]) ? $doctors[$appointment->doctorID] : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_symptoms')?>">
                                                    <?=$appointment->symptoms?>
                                                </td>
                                                <?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
                                                    <td data-title="<?=$this->lang->line('patient_action')?>">
                                                        <?=btn_custom('patient_view', 'patient/prescription/0/'.$appointment->appointmentID.'/'.$appointment->patientID, $this->lang->line('patient_prescription'), 'fa fa-file-text-o', 'btn-primary', false, '', true)?>
                                                        <?=(($loginroleID == 3) && (!permissionChecker('patient_view'))) ? btn_custom_show('patient/prescription/0/'.$appointment->appointmentID.'/'.$appointment->patientID, $this->lang->line('patient_prescription'), 'fa fa-file-text-o', 'btn-primary', false, '', true) : ''?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="liveappointment" role="tabpanel" aria-labelledby="liveappointment-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('patient_slno')?></th>
                                            <th><?=$this->lang->line('patient_appointmentdate')?></th>
                                            <th><?=$this->lang->line('patient_doctor')?></th>
                                            <th><?=$this->lang->line('patient_symptoms')?></th>
                                            <?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
                                                <th><?=$this->lang->line('patient_action')?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(inicompute($liveappointments)) { $i = 1; foreach ($liveappointments as $liveappointment) {  ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('patient_slno')?>">
                                                    <?=$i?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_appointmentdate')?>">
                                                    <?=app_datetime($liveappointment->appointmentdate)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_doctor')?>">
                                                    <?=isset($doctors[$liveappointment->doctorID]) ? $doctors[$liveappointment->doctorID] : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_symptoms')?>">
                                                    <?=$liveappointment->symptoms?>
                                                </td>
                                                <?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
                                                    <td data-title="<?=$this->lang->line('patient_action')?>">
                                                        <?=  $replace(btn_sm_global('appointment/zoomview/'.$liveappointment->appointmentID.'/'.strtotime($liveappointment->appointmentdate).'/'.$liveappointment->doctorID, $this->lang->line('join'), 'fa fa-video-camera'))?>

                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="admission" role="tabpanel" aria-labelledby="admission-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('patient_slno')?></th>
                                            <th><?=$this->lang->line('patient_admissiondate')?></th>
                                            <th><?=$this->lang->line('patient_doctor')?></th>
                                            <th><?=$this->lang->line('patient_symptoms')?></th>
                                            <?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
                                                <th><?=$this->lang->line('patient_action')?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(inicompute($admissions)) { $i = 1; foreach ($admissions as $admission) {  ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('patient_slno')?>">
                                                    <?=$i?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_admissiondate')?>">
                                                    <?=app_datetime($admission->admissiondate)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_doctor')?>">
                                                    <?=isset($doctors[$admission->doctorID]) ? $doctors[$admission->doctorID] : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_symptoms')?>">
                                                    <?=$admission->symptoms?>
                                                </td>
                                                <?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
                                                    <td data-title="<?=$this->lang->line('patient_action')?>">
                                                        <?php if($admission->prescriptionstatus) { ?>
                                                            <?=btn_custom('patient_view', 'patient/prescription/1/'.$admission->admissionID.'/'.$admission->patientID, $this->lang->line('patient_prescription'), 'fa fa-file-text-o', 'btn-primary', false, '', true)?>
                                                        <?php } ?>
                                                        <?=btn_custom('patient_view', 'patient/discharge/'.$admission->admissionID.'/'.$admission->patientID, $this->lang->line('patient_discharge'), 'fa fa-file-text-o', 'btn-danger', false, '', true)?>

                                                        <?=(($loginroleID == 3) && (!permissionChecker('patient_view')) && $admission->prescriptionstatus) ? btn_custom_show('patient/prescription/1/'.$admission->admissionID.'/'.$admission->patientID, $this->lang->line('patient_prescription'), 'fa fa-file-text-o', 'btn-primary', false, '', true) : ''?>

                                                        <?=(($loginroleID == 3) && (!permissionChecker('patient_view'))) ? btn_custom_show('patient/discharge/'.$admission->admissionID.'/'.$admission->patientID, $this->lang->line('patient_discharge'), 'fa fa-file-text-o', 'btn-danger', false, '', true) : ''?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="instruction" role="tabpanel" aria-labelledby="instruction-tab">
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item list-group-item-border-none">
                                        <?php foreach($instructions as $instruction) { ?>
                                            <div class="media media-margin">
                                                <img src="<?=imagelink($instruction->photo)?>" class="width-small mr-3">
                                                <div class="media-body">
                                                    <h6 class="font-size mt-0">
                                                        <?=app_datetime($instruction->create_date)?>
                                                    </h6>
                                                    <span class="font-size"><?=$instruction->instruction?></span>
                                                </div>
                                            </div>
                                        <?php  } ?>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="physicalcondition" role="tabpanel" aria-labelledby="physicalcondition-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('patient_slno')?></th>
                                            <th><?=$this->lang->line('patient_date')?></th>
                                            <th><?=$this->lang->line('patient_height')?></th>
                                            <th><?=$this->lang->line('patient_weight')?></th>
                                            <th><?=$this->lang->line('patient_bp')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(inicompute($heightweightbps)) { $i = 1; foreach ($heightweightbps as $heightweightbp) { if($heightweightbp->height != '' || $heightweightbp->weight != '' || $heightweightbp->bp != '' ) { ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('patient_slno')?>">
                                                    <?=$i?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_date')?>">
                                                    <?=app_datetime($heightweightbp->date)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_height')?>">
                                                    <?=($heightweightbp->height > 0) ? $heightweightbp->height : ' '?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_weight')?>">
                                                    <?=($heightweightbp->weight > 0) ? $heightweightbp->weight : ' '?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_bp')?>">
                                                    <?=($heightweightbp->bp > 0) ? $heightweightbp->bp : ' '?>
                                                </td>
                                            </tr>
                                            <?php $i++; } } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="bill" role="tabpanel" aria-labelledby="bill-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('patient_slno')?></th>
                                            <th><?=$this->lang->line('patient_date')?></th>
                                            <th><?=$this->lang->line('patient_name')?></th>
                                            <th><?=$this->lang->line('patient_discount')?>(%)</th>
                                            <th><?=$this->lang->line('patient_amount')?></th>
                                            <th><?=$this->lang->line('patient_subtotal')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $totalsubtotal = 0; if(inicompute($billitems)) {  $i = 1; foreach ($billitems as $billitem) { ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('patient_slno')?>">
                                                    <?=$i?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_date')?>">
                                                    <?=app_datetime($billitem->create_date)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_name')?>">
                                                    <?=isset($billlabels[$billitem->billlabelID]) ? $billlabels[$billitem->billlabelID]->name : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_discount')?>(%)">
                                                    <?=$billitem->discount?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_amount')?>">
                                                    <?=number_format($billitem->amount, 2)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_subtotal')?>">
                                                    <?php
                                                    $discount = 0;
                                                    if($billitem->discount > 0) {
                                                        $discount = (($billitem->amount / 100) * $billitem->discount);
                                                    }
                                                    $subtotal = ($billitem->amount - $discount);
                                                    echo number_format($subtotal, 2);
                                                    $totalsubtotal += $subtotal;
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php $i++; } } ?>
                                        <tr>
                                            <td class="font-weight" data-title="<?=$this->lang->line('patient_total')?>" colspan="5"><?=$this->lang->line('patient_total')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                            <td class="font-weight" data-title="<?=$this->lang->line('patient_total')?>"><?=number_format($totalsubtotal,2)?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('patient_slno')?></th>
                                            <th><?=$this->lang->line('patient_date')?></th>
                                            <th><?=$this->lang->line('patient_method')?></th>
                                            <th><?=$this->lang->line('patient_amount')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $totalPaymentAmount = 0; if(inicompute($billpayments)) { $i = 0; foreach($billpayments as $billpayment) { $i++; ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('patient_slno')?>"><?=$i?></td>
                                                <td data-title="<?=$this->lang->line('patient_date')?>"><?=app_datetime($billpayment->create_date)?></td>
                                                <td data-title="<?=$this->lang->line('patient_method')?>"><?=isset($paymentmethods[$billpayment->paymentmethod]) ? $paymentmethods[$billpayment->paymentmethod] : ''?></td>
                                                <td data-title="<?=$this->lang->line('patient_amount')?>">
                                                    <?php
                                                    $totalPaymentAmount += $billpayment->paymentamount;
                                                    echo app_currency_format($billpayment->paymentamount);
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } } ?>
                                        <tr>
                                            <td class="font-weight" data-title="<?=$this->lang->line('patient_total')?>" colspan="3"><?=$this->lang->line('patient_total')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                            <td class="font-weight" data-title="<?=$this->lang->line('patient_subtotal')?>"><?=number_format($totalPaymentAmount, 2)?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="test" role="tabpanel" aria-labelledby="test-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('patient_slno')?></th>
                                            <th><?=$this->lang->line('patient_date')?></th>
                                            <th><?=$this->lang->line('patient_testname')?></th>
                                            <th><?=$this->lang->line('patient_category')?></th>
                                            <th><?=$this->lang->line('patient_billno')?></th>
                                            <?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
                                                <th><?=$this->lang->line('patient_action')?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(inicompute($tests)) { $i = 1; foreach ($tests as $test) { ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('patient_slno')?>">
                                                    <?=$i?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_date')?>">
                                                    <?=app_datetime($test->create_date)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_testname')?>">
                                                    <?=isset($testlabels[$test->testlabelID]) ? $testlabels[$test->testlabelID] : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_category')?>">
                                                    <?=isset($testcategorys[$test->testcategoryID]) ? $testcategorys[$test->testcategoryID] : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('patient_billno')?>">
                                                    <?=$test->billID?>
                                                </td>
                                                <?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
                                                    <td data-title="<?=$this->lang->line('patient_action')?>">
                                                        <?=btn_modal_view('patient/view', $test->testID, $this->lang->line('patient_view'))?>
                                                        <?=(($loginroleID == 3) && (!permissionChecker('patient_view'))) ? btn_modal_view_show($test->testID, $this->lang->line('patient_view')) : ''?>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

<?php if(permissionChecker('patient_view') || (($loginroleID == 3) && (!permissionChecker('patient_view')))) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('patient_view')?> <?=$this->lang->line('patient_test')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body viewTestModal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('patient_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="modal" id="mail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('patient_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('patient_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('patient_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('patient_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('patient_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
