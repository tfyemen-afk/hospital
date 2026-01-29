<?php
    $maritalstatusArray       = []; 
    $maritalstatusArray['1']  = $this->lang->line('registration_single');
    $maritalstatusArray['2']  = $this->lang->line('registration_married');
    $maritalstatusArray['3']  = $this->lang->line('registration_separated');
    $maritalstatusArray['4']  = $this->lang->line('registration_divorced');

?>
<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('registration_print'))?>
                <?=btn_sm_pdf('registration/printpreview/'.$patient->patientID, $this->lang->line('registration_pdf_preview'))?>
                <?=btn_sm_edit('registration_edit', 'registration/edit/'.$patient->patientID, $this->lang->line('registration_edit'))?>
                <?=btn_sm_mail($this->lang->line('registration_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('registration/index/'.$displayID)?>"> <?=$this->lang->line('menu_registration')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('registration_view')?></li>
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
                            <img src="<?=imagelink($patient->photo,'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
                            <h3 class="profile-username text-center"><?=$patient->name?></h3>
                            <p class="text-muted text-center"><?=inicompute($designation) ? $designation->designation : ''?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('registration_uhid')?></b> <a class="pull-right"><?=$patient->patientID?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('registration_type')?></b>
                                    <a class="pull-right">
                                        <?php
                                            if($patient->patienttypeID == 0) {
                                                echo $this->lang->line('registration_opd');
                                            } elseif($patient->patienttypeID == 5) {
                                                echo $this->lang->line('registration_register');
                                            } else {
                                                echo $this->lang->line('registration_ipd');
                                            }
                                        ?>
                                    </a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('registration_gender')?></b> <a class="pull-right"><?=($patient->gender == '1')? $this->lang->line('registration_male'): $this->lang->line('registration_female')?></a>
                                </li>
                                <li class="list-group-item">
                                    <b><?=$this->lang->line('registration_age')?></b> <a class="pull-right"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('registration_phone')?></b> <a class="pull-right"><?=$patient->phone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#patient" role="tab" aria-controls="profile" aria-selected="true"><?=$this->lang->line('registration_profile')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active overflow-hidden" id="patient" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="profile-view-dis">
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('registration_guardianname')?> </span>: <?=$patient->guardianname?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('registration_marital_status')?> </span>: <?=isset($maritalstatusArray[$patient->maritalstatus]) ? $maritalstatusArray[$patient->maritalstatus] : ''?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('registration_bloodgroup')?> </span>: <?=inicompute($bloodgroup) ? $bloodgroup->bloodgroup : ''?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('registration_email')?> </span>: <?=$patient->email?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('registration_username')?> </span>: <?=$user->username?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line('registration_address')?> </span>: <?=$patient->address?></p>
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

<!--<form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST">-->
    <div class="modal" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title"><?=$this->lang->line('registration_send_pdf_to_mail')?></h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form role="form" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?=$this->lang->line('registration_to')?><span class="text-danger"> *</span></label> 
                            <input type="text" class="form-control" id="to">
                            <span class="text-danger" id="to_error"></span>
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('registration_subject')?><span class="text-danger"> *</span></label> 
                            <input type="text" class="form-control" id="subject">
                            <span class="text-danger" id="subject_error"></span>
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('registration_message')?></label> 
                            <textarea class="form-control" id="message" rows="3"></textarea>
                            <span class="text-danger" id="message_error"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('registration_send')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--</form>-->
