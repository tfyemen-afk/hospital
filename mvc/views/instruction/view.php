<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('instruction_print'))?>
                <?=btn_sm_pdf('instruction/printpreview/'.$admission->admissionID.'/'.$patient->patientID, $this->lang->line('instruction_pdf_preview'))?>
                <?=btn_sm_mail($this->lang->line('instruction_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('instruction/index/'.$displayID.'/'.$displayuhID)?>"> <?=$this->lang->line('menu_instruction')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('instruction_view')?></li>
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
                            <p class="text-muted text-center"><?=inicompute($designation) ? $designation->designation : ''?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('instruction_uhid')?></b> <a class="pull-right"><?=$patient->patientID?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('instruction_type')?></b> <a class="pull-right"><?=($patient->patienttypeID == 0) ? $this->lang->line('instruction_opd') : $this->lang->line('instruction_ipd') ?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('instruction_gender')?></b> <a class="pull-right"><?=($patient->gender == '1')? $this->lang->line('instruction_male'): $this->lang->line('instruction_female')?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('instruction_age')?></b> <a class="pull-right"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year);?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('instruction_phone')?></b> <a class="pull-right"><?=$patient->phone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#instruction" role="tab" aria-controls="instruction" aria-selected="true"><?=$this->lang->line('instruction_instruction')?></a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active overflow-hidden" id="instruction" role="tabpanel" aria-labelledby="profile-tab">
                                <?php foreach($instructions as $instruction) { ?>
                                    <div class="media media-margin">
                                        <img src="<?=imagelink($instruction->photo)?>" class="width-small mr-3">
                                        <div class="media-body">
                                            <h6 class="font-size mt-0">
                                                <?=app_datetime($instruction->create_date)?>
                                                <?php if($admission->status == 0) { ?>
                                                    <span class="pull-right">
                                                        <?=btn_edit('instruction/edit/'.$instruction->instructionID.'/'.$displayID.'/'.$patient->patientID, $this->lang->line('instruction_edit'))?>
                                                        <?=btn_delete('instruction/delete/'.$instruction->instructionID.'/'.$displayID.'/'.$patient->patientID, $this->lang->line('instruction_delete'))?>
                                                    </span>
                                                <?php } ?>
                                            </h6>
                                            <span class="font-size"><?=$instruction->instruction?></span>
                                        </div>
                                    </div>
                                <?php  } ?>
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
                <h6 class="mdoal-title"><?=$this->lang->line('instruction_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('instruction_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('instruction_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('instruction_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('instruction_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
