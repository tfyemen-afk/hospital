<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('birthregister_print'))?>
                <?=btn_sm_pdf('birthregister/printpreview/'.$birthregister->birthregisterID, $this->lang->line('birthregister_pdf_preview'))?>
                <?=btn_sm_edit('birthregister_edit', 'birthregister/edit/'.$birthregister->birthregisterID.'/'.$displayID, $this->lang->line('birthregister_edit'))?>
                <?=btn_sm_mail($this->lang->line('birthregister_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('birthregister/index/'.$displayID)?>"> <?=$this->lang->line('menu_birthregister')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('birthregister_view')?></li>
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
                                <p><span><?=$this->lang->line('birthregister_name')?> </span>: <?=$birthregister->name?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_father_name')?> </span>: <?=$birthregister->father_name?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_mother_name')?> </span>: <?=$birthregister->mother_name?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_gender')?> </span>: 
                                <?=($birthregister->gender == 1) ? $this->lang->line('birthregister_male'): $this->lang->line('birthregister_female')?>
                                </p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_date')?> </span>: <?=app_datetime($birthregister->date)?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_weight')?> </span>: <?=$birthregister->weight?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_length')?> </span>: <?=$birthregister->length?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_patient')?> </span>: <?=inicompute($patient) ? $patient->name : ''?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_birth_place')?> </span>: <?=$birthregister->birth_place?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('birthregister_nationality')?> </span>: <?=$birthregister->nationality?></p>
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
                <h6 class="mdoal-title"><?=$this->lang->line('birthregister_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('birthregister_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('birthregister_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('birthregister_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('birthregister_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
