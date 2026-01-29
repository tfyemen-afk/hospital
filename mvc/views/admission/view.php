<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('admission_print'))?>
                <?=btn_sm_pdf('admission/printpreview/'.$admission->admissionID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('admission_pdf_preview'))?>
                <?=($admission->status == 0) ? btn_sm_edit('admission_edit', 'admission/edit/'.$admission->admissionID.'/'.$displayDateStrtotime.'/'.$displayDoctorID, $this->lang->line('admission_edit')) : ''?>
                <?=btn_sm_mail($this->lang->line('admission_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('admission/index/'.$displayDateStrtotime.'/'.$displayDoctorID)?>"> <?=$this->lang->line('menu_admission')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('admission_view')?></li>
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
                                        <b><?=$this->lang->line('admission_uhid')?></b> <a class="pull-right"><?=$patient->patientID?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('admission_type')?></b> <a class="pull-right"><?=($patient->patienttypeID == 0) ? $this->lang->line('admission_opd') : $this->lang->line('admission_ipd') ?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('admission_gender')?></b> <a class="pull-right"><?=($patient->gender == 1) ? $this->lang->line('admission_male') : $this->lang->line('admission_female')?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('admission_age')?></b> <a class="pull-right"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('admission_phone')?></b> <a class="pull-right"><?=$patient->phone?></a>
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
                                <a class="nav-link active" id="admission-tab" data-toggle="tab" href="#admission" role="tab" aria-controls="profile" aria-selected="true"><?=$this->lang->line('admission_admission')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active overflow-hidden" id="admission" role="tabpanel" aria-labelledby="admission-tab">
                                <div class="profile-view-dis">
                                    <div class="col-sm-12">
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_admissiondate')?> </span>: <?=app_datetime($admission->admissiondate)?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_doctor')?> </span>: <?=inicompute($doctor) ? $doctor->name : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_case')?> </span>: <?=$admission->pcase?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_casualty')?> </span>: <?=($admission->pcase == 1) ? $this->lang->line('admission_yes') : $this->lang->line('admission_no') ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_oldpatient')?> </span>: <?=($admission->oldpatient==1) ? $this->lang->line('admission_no') : $this->lang->line('admission_yes')?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_tpa')?> </span>: <?=inicompute($tpa) ? $tpa->name : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_reference')?> </span>: <?=$admission->reference?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_symptoms')?> </span>: <?=$admission->symptoms?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_allergies')?> </span>: <?=$admission->allergies?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_creditlimit')?> </span>: <?=$admission->creditlimit?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_bed')?> </span>: <?=inicompute($bed) ? $bed->name : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line('admission_status')?> </span>: 
                                                <?php 
                                                    if($admission->status==1) { ?>
                                                        <span class="text-danger"><?=$this->lang->line('admission_release')?></span>
                                                    <?php } else { ?>
                                                        <span class="text-success"><?=$this->lang->line('admission_admitted')?></span>
                                                <?php } ?>
                                            </p>
                                        </div>
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
                <h6 class="mdoal-title"><?=$this->lang->line('admission_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('admission_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('admission_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('admission_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('admission_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>