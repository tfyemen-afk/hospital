<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('deathregister_print'))?>
                <?=btn_sm_pdf('deathregister/certificateprintpreview/'.$deathregister->deathregisterID, $this->lang->line('deathregister_pdf_preview'))?>
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
                    <div class="main-certificate-area deathregister-main-certificate-area">
                        <div class="header-certificate-area">
                            <h2><?=$generalsettings->system_name?></h2>
                            <p><?=$generalsettings->address?></p>
                            <p class="header-birth-certificate">
                                Death Certificate
                            </p>
                        </div>
                        <div class="body-certificate-area">
                            <div class="body-certificate-top-area">
                                <strong>This is to certify that</strong> <span><?=$deathregister->name?></span> Sex <span><?=($deathregister->gender==1) ? 'Male' : 'Female'?> </span> passed away at <strong><?=$generalsettings->system_name?></strong> on <span><?=date('d-m-Y', strtotime($deathregister->death_date))?></span> at <span><?=date('H:i A', strtotime($deathregister->death_date))?></span> in witness of  <?=($deathregister->gender==1) ? 'his' : 'her'?> <?=$deathregister->relation?> <span><?=$deathregister->guardian_name?></span>.
                            </div>
                            <div class="body-certificate-bottom-area">
                                <div class="deathregister-body-certificate-bottom-text-area">
                                    Confirmed this on <span><?=app_datetime($deathregister->death_date)?></span> by <span><?=inicompute($doctor) ? $doctor->name : ''?></span>
                                </div>
                            </div>
                        </div>
                        <div class="footer-certificate-area">
                            <div class="footer-certificate-bottom-signature-area">
                                <div class="footer-certificate-bottom-signature-left">
                                    <span>Administrator</span>
                                </div>
                                <div class="footer-certificate-bottom-signature-right">
                                    <span>Doctor</span>
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