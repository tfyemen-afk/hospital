<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('operationtheatre_print'))?>
                <?=btn_sm_pdf('operationtheatre/printpreview/'.$operationtheatre->operationtheatreID, $this->lang->line('operationtheatre_pdf_preview'))?>
                <?=btn_sm_edit('operationtheatre_edit', 'operationtheatre/edit/'.$operationtheatre->operationtheatreID.'/'.$displayID, $this->lang->line('operationtheatre_edit'))?>
                <?=btn_sm_mail($this->lang->line('operationtheatre_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('operationtheatre/index/'.$displayID)?>"><?=$this->lang->line('menu_operationtheatre')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('operationtheatre_view')?></li>
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
                                <p><span><?=$this->lang->line('operationtheatre_operation_name')?> </span>: <?=$operationtheatre->operation_name?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('operationtheatre_operation_type')?> </span>: <?=$operationtheatre->operation_type?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('operationtheatre_uhid')?> </span>: <?=$operationtheatre->patientID?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('operationtheatre_patient')?> </span>: <?=inicompute($patient) ? $patient->name : '&nbsp;'?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('operationtheatre_operation_date')?> </span>: <?=app_datetime($operationtheatre->operation_date)?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('operationtheatre_doctor')?> </span>: <?=isset($doctors[$operationtheatre->doctorID]) ? $doctors[$operationtheatre->doctorID] : '&nbsp;'?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('operationtheatre_assistant_doctor_1')?> </span>: <?=isset($doctors[$operationtheatre->assistant_doctor_1]) ? $doctors[$operationtheatre->assistant_doctor_1] : '&nbsp;'?></p>
                            </div>
                            <div class="profile-view-tab">
                                <p><span><?=$this->lang->line('operationtheatre_assistant_doctor_2')?> </span>: <?=isset($doctors[$operationtheatre->assistant_doctor_2]) ? $doctors[$operationtheatre->assistant_doctor_2] : '&nbsp;'?></p>
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
                <h6 class="mdoal-title"><?=$this->lang->line('operationtheatre_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('operationtheatre_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('operationtheatre_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('operationtheatre_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('operationtheatre_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>