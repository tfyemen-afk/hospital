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
                <?=btn_sm_print($this->lang->line('appointment_print'))?>
                <?=btn_sm_pdf('appointment/printpreview/'.$appointment->appointmentID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('appointment_pdf_preview'))?>
                <?=($appointment->status == 0) ? btn_sm_edit('appointment_edit', 'appointment/edit/'.$appointment->appointmentID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('appointment_edit')) : '' ?>
                <?=btn_sm_mail($this->lang->line('appointment_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('appointment/index/'.$displayDateStrtotime.'/'.$displayDoctorID)?>"><?=$this->lang->line('menu_appointment')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('appointment_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="row">
                <?php if(inicompute($patient)) { ?>
                    <div class="col-sm-3 user-profile-box">
                        <div class="box box-primary">
                            <div class="box-body box-profile">
                                <img src="<?=imagelink($patient->photo,'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
                                <h3 class="profile-username text-center"><?=$patient->name?></h3>
                                <p class="text-muted text-center"><?=isset($designations[inicompute($user) ? $user->designationID : 2]) ? $designations[inicompute($user) ? $user->designationID : 2] : ''?></p>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('appointment_uhid')?></b> <a class="pull-right"><?=$patient->patientID?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('appointment_type')?></b> <a class="pull-right"><?=($patient->patienttypeID == 0) ? $this->lang->line('appointment_opd') : $this->lang->line('appointment_ipd') ?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('appointment_gender')?></b> <a class="pull-right"><?=($patient->gender == 1) ? $this->lang->line('appointment_male') : $this->lang->line('appointment_female')?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('appointment_age')?></b> <a class="pull-right"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('appointment_phone')?></b> <a class="pull-right"><?=$patient->phone?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="appointment-tab" data-toggle="tab" href="#appointment" role="tab" aria-controls="profile" aria-selected="true"><?=$this->lang->line('appointment_appointment')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active overflow-hidden" id="appointment" role="tabpanel" aria-labelledby="appointment-tab">
                                <div class="profile-view-dis">
                                    <div class="col-sm-12">
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_appointmentdate')?> </span>: <?=app_datetime($appointment->appointmentdate)?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_doctor')?> </span>: <?=inicompute($doctor) ? $doctor->name : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_case')?> </span>: <?=$appointment->pcase?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_casualty')?> </span>: <?=($appointment->pcase == 1) ? $this->lang->line('appointment_yes') : $this->lang->line('appointment_no') ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_oldpatient')?> </span>: <?=($appointment->oldpatient == 1) ? $this->lang->line('appointment_no') : $this->lang->line('appointment_yes')?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_tpa')?> </span>: <?=inicompute($tpa) ? $tpa->name : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_reference')?> </span>: <?=$appointment->reference?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_symptoms')?> </span>: <?=$appointment->symptoms?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_allergies')?> </span>: <?=$appointment->allergies?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_amount')?> </span>: <?=$appointment->amount?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_payment')?> </span>: <?=isset($paymentstatus[$appointment->paymentstatus]) ? $paymentstatus[$appointment->paymentstatus] : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('appointment_paymentmethod')?> </span>: <?=isset($paymentmethods[$appointment->paymentmethodID]) ? $paymentmethods[$appointment->paymentmethodID] : ''?></p>
                                        </div>
                                        <div  class="profile-view-tab">
                                            <p id="apptype"><span><?=$this->lang->line('appointment_type')?> </span>: <?=isset($appointmentType[$appointment->type]) ? $appointmentType[$appointment->type] : ''?></p>
                                        </div>
                                        <?php if($appointment->type==2 && !$appointment->status && $appointment->paymentstatus==1 ) {?>
                                            <div  class="profile-view-tab">
                                                <p><span><?=$this->lang->line('appointment_link')?> </span>: <?=$replace('<a class="btn btn-primary" href='. base_url('appointment/zoomview/'.$appointment->appointmentID.'/'.$displayDateStrtotime.'/'.$displayDoctorID).'>Join Here</a>')?></p>
                                            </div>

                                        <?php }?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

<div class="modal" id="mail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('appointment_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('appointment_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('appointment_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('appointment_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('appointment_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
