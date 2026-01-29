<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('deathregister_print'))?>
                <?=btn_sm_pdf('deathregister/printpreview/'.$deathregister->deathregisterID, $this->lang->line('deathregister_pdf_preview'))?>
                <?=btn_sm_edit('deathregister_edit', 'deathregister/edit/'.$deathregister->deathregisterID.'/'.$displayID, $this->lang->line('deathregister_edit'))?>
                <?=btn_sm_mail($this->lang->line('deathregister_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('deathregister/index/'.$displayID)?>"> <?=$this->lang->line('menu_deathregister')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('deathregister_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-block">
                        <div class="profile-view-dis">
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_name_of_deceased')?> </span>: <?=$deathregister->name?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_relation_of_deceased')?> </span>: <?=$deathregister->relation?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_guardian_name')?> </span>: <?=$deathregister->guardian_name?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_permanent_address')?> </span>: <?=$deathregister->permanent_address?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_gender')?> </span>: 
                                <?=($deathregister->gender == 1) ? $this->lang->line('deathregister_male') : $this->lang->line('deathregister_female')?>
                                </p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_age_of_the_deceased')?> </span>: <?=$deathregister->age?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_date_of_death')?> </span>: <?=app_datetime($deathregister->death_date)?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_nationality')?> </span>: <?=$deathregister->nationality?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_cause_of_death')?> </span>: <?=$deathregister->death_cause?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_name_of_the_doctor')?> </span>: <?=inicompute($doctor) ? $doctor->name : ''?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_death_confirm_date')?> </span>: <?=app_datetime($deathregister->confirm_date)?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('deathregister_patient')?> </span>: <?=inicompute($patient) ? $patient->name : ''?></p>
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
                <h6 class="mdoal-title"><?=$this->lang->line('deathregister_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('deathregister_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('deathregister_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('deathregister_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('deathregister_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>