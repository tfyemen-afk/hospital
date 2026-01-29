<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('discharge_print'))?>
                <?=btn_sm_pdf('discharge/printpreview/'.$discharge->dischargeID, $this->lang->line('discharge_pdf_preview'))?>
                <?=btn_sm_edit('discharge_edit', 'discharge/edit/'.$discharge->dischargeID.'/'.$displayID, $this->lang->line('discharge_edit'))?>
                <?=btn_sm_mail($this->lang->line('discharge_send_pdf_to_mail'))?>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('discharge/index/'.$displayID)?>"><?=$this->lang->line('menu_discharge')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('discharge_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="card card-block">
                <div class="receipt-main-div">
                    <div class="receipt-header-div">
                        <div class="receipt-header-img-div">
                            <img class="receipt-header-img" src="<?=base_url('uploads/general/'.$generalsettings->logo)?>" alt="">
                        </div>
                        <div class="receipt-header-title-div">
                            <h6><?=$generalsettings->system_name?></h6>
                            <address>
                                <?=$generalsettings->address?><br/>
                                <b><?=$this->lang->line('discharge_email')?> : </b><?=$generalsettings->email?><br/>
                                <b><?=$this->lang->line('discharge_phone')?> : </b><?=$generalsettings->phone?>
                            </address>
                        </div>
                    </div>

                    <div class="receipt-body-div">
                        <div class="row">
                            <div class="col-sm-12">
                                <p class="receipt-feature-header-title"><i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> &nbsp;&nbsp;<?=$this->lang->line('panel_title')?>&nbsp;&nbsp <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i></p>
                            </div>
                        </div>
                    </div>

                    <div class="receipt-body-div">
                        <div class="row">
                            <div class="col-sm-6">
                                <?=$this->lang->line('discharge_discharge_no')?> : <?=$discharge->dischargeID?>
                            </div>
                            <div class="col-sm-6">
                                <span class="receipt-pull-right"><?=$this->lang->line('discharge_date')?> : <?=date('d/m/Y')?></span>
                            </div>
                        </div>
                    </div>

                    <div class="receipt-body-div receipt-body-font-style">
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="receipt-col-sm-3-5"><?=$this->lang->line('discharge_uhid')?> :  &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=$patient->patientID?></span>
                            </div>
                            <div class="col-sm-4">
                                <span class="receipt-col-sm-3-5"><?=$this->lang->line('discharge_name')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=$patient->name?></span>
                            </div>
                            <div class="col-sm-4">
                                <span class="receipt-col-sm-3-5"><?=$this->lang->line('discharge_age')?> / <?=$this->lang->line('discharge_sex')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?> / <?=isset($genders[$patient->gender]) ? $genders[$patient->gender] : ''?></span>
                            </div>
                        </div>
                    </div>

                    <div class="receipt-body-div receipt-body-font-style">
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="receipt-col-sm-3-5"><?=$this->lang->line('discharge_date_of_admission')?> :  &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=app_datetime($patient->admissiondate)?></span>
                            </div>
                            <div class="col-sm-4">
                                <span class="receipt-col-sm-3-5"><?=$this->lang->line('discharge_date_of_discharge')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=app_datetime($discharge->date)?></span>
                            </div>
                            <div class="col-sm-4">
                                <span class="receipt-col-sm-3-5"><?=$this->lang->line('discharge_bed_no')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=inicompute($bed) ? $bed->name : ''?></span>
                            </div>
                        </div>
                    </div>

                    <div class="receipt-body-div receipt-body-font-style">
                        <div class="row">
                            <div class="col-sm-4">
                                <span class="receipt-col-sm-3-5"><?=$this->lang->line('discharge_room')?> / <?=$this->lang->line('discharge_ward')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=inicompute($room) ? $room->name : ''?> / <?=inicompute($ward) ? $ward->name : ''?> - <?=inicompute($floor) ? $floor->name : ''?></span>
                            </div>
                            <div class="col-sm-8">
                                <span class="receipt-col-sm-3-5"><?=$this->lang->line('discharge_condition_of_discharge')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=isset($conditions[$discharge->conditionofdischarge]) ? $conditions[$discharge->conditionofdischarge] : '' ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="receipt-signature-div receipt-body-font-style">
                        <div class="row">
                            <div class="col-sm-12">
                                <span class="receipt-signature-col-sm-1-5"><?=$this->lang->line('discharge_signature')?>
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
                <h6 class="mdoal-title"><?=$this->lang->line('discharge_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('discharge_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('discharge_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('discharge_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('discharge_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>