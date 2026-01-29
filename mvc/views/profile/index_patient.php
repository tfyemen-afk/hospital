<?php
    $monthArray = array(
      "01" => "jan",
      "02" => "feb",
      "03" => "mar",
      "04" => "apr",
      "05" => "may",
      "06" => "jun",
      "07" => "jul",
      "08" => "aug",
      "09" => "sep",
      "10" => "oct",
      "11" => "nov",
      "12" => "dec"
    );
?>
<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('profile_print'))?>
                <?=btn_sm_pdf('profile/printpreview/'.$user->userID, $this->lang->line('profile_pdf_preview'))?>
                <?=btn_sm_edit('profile_edit', 'profile/edit/'.$user->userID, $this->lang->line('profile_edit'))?>
                <?=btn_sm_mail($this->lang->line('profile_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                  <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_profile')?></li>
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
                                    <b><?=$this->lang->line('profile_uhid')?></b> <a class="pull-right"><?=$patient->patientID?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('profile_type')?></b> <a class="pull-right"><?=($patient->patienttypeID == 0) ? $this->lang->line('profile_opd') : $this->lang->line('profile_ipd') ?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('profile_gender')?></b> <a class="pull-right"><?=($patient->gender == '1')? $this->lang->line('profile_male'): $this->lang->line('profile_female')?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('profile_age')?></b> <a class="pull-right"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year);?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('profile_phone')?></b> <a class="pull-right"><?=$patient->phone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true"><?=$this->lang->line('profile_profile')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="appointment-tab" data-toggle="tab" href="#appointment" role="tab" aria-controls="appointment" aria-selected="true"><?=$this->lang->line('profile_appointment')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="admission-tab" data-toggle="tab" href="#admission" role="tab" aria-controls="admission" aria-selected="true"><?=$this->lang->line('profile_admission')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="physicalcondition-tab" data-toggle="tab" href="#physicalcondition" role="tab" aria-controls="physicalcondition" aria-selected="true"><?=$this->lang->line('profile_physicalcondition')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="instruction-tab" data-toggle="tab" href="#instruction" role="tab" aria-controls="instruction" aria-selected="true"><?=$this->lang->line('profile_instruction')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="bill-tab" data-toggle="tab" href="#bill" role="tab" aria-controls="bill" aria-selected="true"><?=$this->lang->line('profile_bill')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="true"><?=$this->lang->line('profile_payment')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="test-tab" data-toggle="tab" href="#test" role="tab" aria-controls="test" aria-selected="true"><?=$this->lang->line('profile_test')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active overflow-hidden" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="profile-view-dis">
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('profile_guardianname')?> </span>: <?=$patient->guardianname?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('profile_maritalstatus')?> </span>: <?=isset($maritalstatus[$patient->maritalstatus]) ? $maritalstatus[$patient->maritalstatus] : '' ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('profile_bloodgroup')?> </span>: <?=isset($bloodgroups[$patient->bloodgroupID]) ? $bloodgroups[$patient->bloodgroupID] : ''?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('profile_email')?> </span>: <?=$user->email?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('profile_username')?> </span>: <?=$user->username?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('profile_address')?> </span>: <?=$patient->address?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="appointment" role="tabpanel" aria-labelledby="appointment-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('profile_slno')?></th>
                                                <th><?=$this->lang->line('profile_appointmentdate')?></th>
                                                <th><?=$this->lang->line('profile_doctor')?></th>
                                                <th><?=$this->lang->line('profile_symptoms')?></th>
                                                <th><?=$this->lang->line('profile_action')?></th>                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(inicompute($appointments)) { $i = 1; foreach ($appointments as $appointment) {  ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('profile_slno')?>">
                                                        <?=$i?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_appointmentdate')?>">
                                                        <?=app_datetime($appointment->appointmentdate)?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_doctor')?>">
                                                        <?=isset($doctors[$appointment->doctorID]) ? $doctors[$appointment->doctorID] : ''?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_symptoms')?>">
                                                        <?=$appointment->symptoms?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_action')?>">
                                                        <?=btn_custom_show('profile/prescription/0/'.$appointment->appointmentID, $this->lang->line('profile_prescription'), 'fa fa-file-text-o', 'btn-primary', false, '', true)?>
                                                    </td>
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
                                            <th><?=$this->lang->line('profile_slno')?></th>
                                            <th><?=$this->lang->line('profile_admissiondate')?></th>
                                            <th><?=$this->lang->line('profile_doctor')?></th>
                                            <th><?=$this->lang->line('profile_symptoms')?></th>
                                            <th><?=$this->lang->line('profile_action')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(inicompute($admissions)) { $i = 1; foreach ($admissions as $admission) {  ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('profile_slno')?>">
                                                    <?=$i?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_admissiondate')?>">
                                                    <?=app_datetime($admission->admissiondate)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_doctor')?>">
                                                    <?=isset($doctors[$admission->doctorID]) ? $doctors[$admission->doctorID] : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_symptoms')?>">
                                                    <?=$admission->symptoms?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_action')?>">
                                                    <?php if($admission->prescriptionstatus) { ?>
                                                        <?=btn_custom_show('profile/prescription/1/'.$admission->admissionID, $this->lang->line('profile_prescription'), 'fa fa-file-text-o', 'btn-primary', false, '', true)?>
                                                    <?php } ?>
                                                    <?=btn_custom_show('profile/discharge/'.$admission->admissionID, $this->lang->line('profile_discharge'), 'fa fa-file-text-o', 'btn-danger', false, '', true)?>
                                                </td>
                                            </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="physicalcondition" role="tabpanel" aria-labelledby="physicalcondition-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('profile_slno')?></th>
                                                <th><?=$this->lang->line('profile_date')?></th>
                                                <th><?=$this->lang->line('profile_height')?></th>
                                                <th><?=$this->lang->line('profile_weight')?></th>
                                                <th><?=$this->lang->line('profile_bp')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(inicompute($heightweightbps)) { $i = 1; foreach ($heightweightbps as $heightweightbp) { if($heightweightbp->height != '' || $heightweightbp->weight != '' || $heightweightbp->bp != '' ) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('profile_slno')?>">
                                                        <?=$i?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_date')?>">
                                                        <?=app_datetime($heightweightbp->date)?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_height')?>">
                                                        <?=($heightweightbp->height > 0) ? $heightweightbp->height : ' '?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_weight')?>">
                                                        <?=($heightweightbp->weight > 0) ? $heightweightbp->weight : ' '?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_bp')?>">
                                                        <?=($heightweightbp->bp > 0) ? $heightweightbp->bp : ' '?>
                                                    </td>
                                                </tr>
                                            <?php $i++; } } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="instruction" role="tabpanel" aria-labelledby="instruction-tab">
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item list-group-item-border-none">
                                        <?php if(inicompute($instructions)) { foreach($instructions as $instruction) { ?>
                                            <div class="media media-margin">
                                                <img src="<?=imagelink($instruction->photo)?>" class="width mr-3">
                                                <div class="media-body">
                                                    <h6 class="font-size mt-0">
                                                        <?=app_datetime($instruction->create_date)?>
                                                    </h6>
                                                    <span class="font-size"><?=$instruction->instruction?></span>
                                                </div>
                                            </div>
                                        <?php } } ?>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="bill" role="tabpanel" aria-labelledby="bill-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('profile_slno')?></th>
                                            <th><?=$this->lang->line('profile_date')?></th>
                                            <th><?=$this->lang->line('profile_name')?></th>
                                            <th><?=$this->lang->line('profile_discount')?>(%)</th>
                                            <th><?=$this->lang->line('profile_amount')?></th>
                                            <th><?=$this->lang->line('profile_subtotal')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(inicompute($billitems)) { $totalsubtotal = 0; $i = 1; foreach ($billitems as $billitem) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('profile_slno')?>">
                                                        <?=$i?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_date')?>">
                                                        <?=app_datetime($billitem->create_date)?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_name')?>">
                                                        <?=isset($billlabels[$billitem->billlabelID]) ? $billlabels[$billitem->billlabelID]->name : ''?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_discount')?>(%)">
                                                        <?=$billitem->discount?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_amount')?>">
                                                        <?=number_format($billitem->amount, 2)?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_subtotal')?>">
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
                                                <td class="font-weight" data-title="<?=$this->lang->line('profile_total')?>" colspan="5"><?=$this->lang->line('profile_total')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                                <td class="font-weight" data-title="<?=$this->lang->line('profile_total')?>"><?=number_format($totalsubtotal,2)?></td>
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
                                                <th><?=$this->lang->line('profile_slno')?></th>
                                                <th><?=$this->lang->line('profile_date')?></th>
                                                <th><?=$this->lang->line('profile_method')?></th>
                                                <th><?=$this->lang->line('profile_amount')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  $totalPaymentAmount = 0; if(inicompute($billpayments)) { $i = 0; foreach($billpayments as $billpayment) { $i++; ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('profile_slno')?>"><?=$i?></td>
                                                    <td data-title="<?=$this->lang->line('profile_date')?>"><?=app_datetime($billpayment->create_date)?></td>
                                                    <td data-title="<?=$this->lang->line('profile_method')?>"><?=isset($paymentmethods[$billpayment->paymentmethod]) ? $paymentmethods[$billpayment->paymentmethod] : ''?></td>
                                                    <td data-title="<?=$this->lang->line('profile_amount')?>">
                                                        <?php
                                                        $totalPaymentAmount += $billpayment->paymentamount;
                                                        echo app_currency_format($billpayment->paymentamount);
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } } ?>
                                        <tr>
                                            <td class="font-weight" data-title="<?=$this->lang->line('profile_total')?>" colspan="3"><?=$this->lang->line('profile_total')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                            <td class="font-weight" data-title="<?=$this->lang->line('profile_subtotal')?>"><?=number_format($totalPaymentAmount, 2)?></td>
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
                                                <th><?=$this->lang->line('profile_slno')?></th>
                                                <th><?=$this->lang->line('profile_date')?></th>
                                                <th><?=$this->lang->line('profile_testname')?></th>
                                                <th><?=$this->lang->line('profile_category')?></th>
                                                <th><?=$this->lang->line('profile_billno')?></th>
                                                <th><?=$this->lang->line('profile_action')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(inicompute($tests)) { $i = 1; foreach ($tests as $test) { ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('profile_slno')?>">
                                                    <?=$i?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_date')?>">
                                                    <?=app_datetime($test->create_date)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_testname')?>">
                                                    <?=isset($testlabels[$test->testlabelID]) ? $testlabels[$test->testlabelID] : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_category')?>">
                                                    <?=isset($testcategorys[$test->testcategoryID]) ? $testcategorys[$test->testcategoryID] : ''?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_billno')?>">
                                                    <?=$test->billID?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_action')?>">
                                                    <?=btn_modal_view_show($test->testID, $this->lang->line('profile_view'))?>
                                                </td>
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
    </section>
</article>


<?php if(inicompute($tests)) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('profile_view')?> <?=$this->lang->line('profile_test')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body viewTestModal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('profile_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST">
    <div class="modal" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title"><?=$this->lang->line('profile_send_pdf_to_mail')?></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form role="form" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?=$this->lang->line('profile_to')?><span class="text-danger"> *</span></label> 
                            <input type="text" class="form-control" id="to">
                            <span class="text-danger" id="to_error"></span>
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('profile_subject')?><span class="text-danger"> *</span></label> 
                            <input type="text" class="form-control" id="subject">
                            <span class="text-danger" id="subject_error"></span>
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('profile_message')?></label> 
                            <textarea class="form-control" id="message" rows="3"></textarea>
                            <span class="text-danger" id="message_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('profile_send')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</form>

