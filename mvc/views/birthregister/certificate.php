<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('birthregister_print'))?>
                <?=btn_sm_pdf('birthregister/certificateprintpreview/'.$birthregister->birthregisterID, $this->lang->line('birthregister_pdf_preview'))?>
                <?=btn_sm_edit('birthregister_edit', 'birthregister/edit/'.$birthregister->birthregisterID.'/'.$displayID, $this->lang->line('birthregister_edit'))?>
                <?=btn_sm_mail($this->lang->line('birthregister_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('birthregister/index/'.$displayID)?>"> <?=$this->lang->line('menu_birthregister')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('birthregister_certificate')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-12">
                    <div class="main-certificate-area">
                        <div class="header-certificate-area">
                            <h2><?=$generalsettings->system_name?></h2>
                            <p><?=$generalsettings->address?></p>
                            <p class="header-birth-certificate">
                                Birth Certificate
                            </p>
                        </div>
                        <div class="body-certificate-area">
                            <div class="body-certificate-top-area">
                                <strong>This certifies that</strong> <span><?=$birthregister->name?></span> Sex <span><?=($birthregister->gender==1) ? 'Male' : 'Female'?></span> was born in this hospital at <span><?=date('H:i A', strtotime($birthregister->date))?></span> on <span><?=date('l', strtotime($birthregister->date))?></span> the <span><?=date('d', strtotime($birthregister->date))?><sup> <?=getNumberSuffix(date('d', strtotime($birthregister->date)))?></sup></span> day of <span><?=date('F, Y', strtotime($birthregister->date))?></span> Birth place <span><?=$birthregister->birth_place?></span> Nationality <span><?=$birthregister->nationality?></span> and parents <span><?=$birthregister->father_name?> and <?=$birthregister->mother_name?></span>
                            </div>
                            <div class="body-certificate-bottom-area">
                                <div class="body-certificate-bottom-text-area">
                                    <strong>The witness whereof</strong> the said hospital has caused this certificate to be signed by its duly the authorized officers and its Corporate Seal to be hereunto affixed.
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