<section class="section report">
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 10px">
            <?php
                $pdf_preview_uri = site_url('idcardreport/pdf/'.$roleID.'/'.$userID.'/'.$type.'/'.$background);
                echo btn_pdfPreviewReport('idcardreport',$pdf_preview_uri, $this->lang->line('idcardreport_pdf_preview'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('idcardreport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?php if(inicompute($users)) { ?>
                    <div class="main-idcard-report">
                        <?php foreach($users as $user) { if($type==1) { ?>
                        <div class="idcard-frontend <?=($background == 1) ? 'mainbg' : ''?>">
                            <div class="idcard-frontend-header">
                                <div class="idcard-frontend-logo"><img class="idcard-frontend-logo-img" src="<?=imagelink($generalsettings->logo,'uploads/general')?>" alt=""></div>
                                <div class="idcard-frontend-title"><span><?=$generalsettings->system_name?></span></div>
                            </div>
                            <div class="idcard-frontend-body">
                                <h5 class="idcard-frontend-visiting-card"><?=$this->lang->line('idcardreport_id_card')?></h5>
                                <div class="idcard-frontend-photo">
                                    <img class="idcard-frontend-photo-img" src="<?=imagelink($user->photo,'uploads/user')?>" alt="">
                                </div>
                                <div class="idcard-frontend-details">
                                    <p><span><?=$this->lang->line('idcardreport_name')?> : </span><b><?=$user->name?></b></p>
                                    <p><span><?=$this->lang->line('idcardreport_designation')?> : </span><b><?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></b></p>
                                    <p><span><?=$this->lang->line('idcardreport_joining_date')?> : </span><b><?=app_date($user->jod)?></b></p>
                                </div>
                            </div>
                            <div class="idcard-frontend-footer">
                                <div class="idcard-frontend-footerleft"></div>
                                <div class="idcard-frontend-footerright"></div>
                            </div>
                        </div>
                        <?php } elseif($type==2) { 
                            $filepath = FCPATH.'uploads/idQRcode/'.$user->userID.'.png';
                            $text = 'ID:'.$user->userID;
                            if(!file_exists($filepath)) {
                                generate_qrcode($text,$user->userID);
                            }
                        ?>
                        <div class="idcard-backend">
                            <?php if($roleID==3) { ?>
                            <div class="idcard-backend-item">
                                <div class="idcard-backend-label"><?=$this->lang->line('idcardreport_guardian')?></div>
                                <div class="idcard-backend-value">: <?=isset($patients[$user->patientID]) ? $patients[$user->patientID]->guardianname : ''?></div>
                            </div>
                            <?php } ?>
                            <div class="idcard-backend-item">
                                <div class="idcard-backend-label"><?=$this->lang->line('idcardreport_address')?></div>
                                <div class="idcard-backend-value">: <?=$user->address?></div>
                            </div>
                            <div class="idcard-backend-item">
                                <div class="idcard-backend-label"><?=$this->lang->line('idcardreport_email')?></div>
                                <div class="idcard-backend-value">: <?=$user->email?></div>
                            </div>
                            <div class="idcard-backend-item">
                                <div class="idcard-backend-label"><?=$this->lang->line('idcardreport_phone')?></div>
                                <div class="idcard-backend-value">: <?=$user->phone?></div>
                            </div>
                            <div class="idcard-backend-item">
                                <img class="idcard-qrcode-img" src="<?=base_url('uploads/idQRcode/'.$user->userID.'.png')?>">
                            </div>
                            <div class="idcard-backend-item idcard-backend-address">
                                <?=$generalsettings->address?>, <?=$this->lang->line('idcardreport_email')?>:- <?=$generalsettings->email?>, <?=$this->lang->line('idcardreport_phone')?>:- <?=$generalsettings->phone?>
                            </div>
                        </div>
                    <?php } } ?>
                    </div>
                    <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('idcardreport_data_not_found')?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>
