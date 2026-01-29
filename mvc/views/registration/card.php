<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6 offset-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('registration/index/'.$displayID)?>"> <?=$this->lang->line('menu_registration')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('registration_card')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="mainidcard">
            <div class="row">
                <div class="col-md-6">
                    <div id="printabledivfront">
                        <div class="idcard-frontend">
                            <div class="frontend-header">
                                <div class="frontend-logo"><img src="<?=imagelink($generalsettings->logo,'uploads/general')?>" alt=""></div>
                                <div class="frontend-title"><span><?=$generalsettings->system_name?></span></div>
                            </div>
                            <div class="frontend-body">
                                <h5 class="frontend-visiting-card"><?=$this->lang->line('registration_registration_card')?></h5>
                                <div class="frontend-photo">
                                    <img src="<?=imagelink($patient->photo,'uploads/user')?>" alt="">
                                </div>
                                <div class="frontend-details">
                                    <p><span><?=$this->lang->line('registration_uhid')?> : </span><b><?=$patient->patientID?></b></p>
                                    <p><span><?=$this->lang->line('registration_name')?> : </span><b><?=$patient->name?></b></p>
                                    <p><span><?=$this->lang->line('registration_bloodgroup')?> : </span><b><?=isset($bloodgroups[$patient->bloodgroupID]) ? $bloodgroups[$patient->bloodgroupID] : ''?></b></p>
                                </div>
                            </div>
                            <div class="frontend-footer">
                                <div class="frontend-footerleft"></div>
                                <div class="frontend-footerright"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <button class="btn btn-primary px-3" onclick="javascript:printDiv('printabledivfront')"><?=$this->lang->line('registration_print')?></button>
                        <a target="_new" class="btn btn-color-white btn-primary px-3" href="<?=site_url('registration/frontendcard/'.$patient->patientID)?>"><?=$this->lang->line('registration_pdf')?></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="printabledivback">
                        <div class="idcard-backend">
                            <div class="idcard-backenditem">
                                <div class="backend-item">
                                    <span><?=$this->lang->line('registration_guardian')?></span>: <?=$patient->guardianname?>
                                </div>
                                <div class="backend-item">
                                    <span><?=$this->lang->line('registration_address')?></span>: <?=$patient->address?>
                                </div>
                                <div class="backend-item">
                                    <span><?=$this->lang->line('registration_email')?></span>: <?=$patient->email?>
                                </div>
                                <div class="backend-item">
                                    <span><?=$this->lang->line('registration_phone')?></span>: <?=$patient->phone?>
                                </div>
                                <div class="backend-item backend-address">
                                    <p><?=$generalsettings->address?>, <?=$this->lang->line('registration_email')?>:- <?=$generalsettings->email?>, <?=$this->lang->line('registration_phone')?>:- <?=$generalsettings->phone?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <button class="btn btn-primary px-3" onclick="javascript:printDiv('printabledivback')"><?=$this->lang->line('registration_print')?></button>
                        <a target="_new" class="btn btn-color-white btn-primary px-3" href="<?=site_url('registration/backendcard/'.$patient->patientID)?>"><?=$this->lang->line('registration_pdf')?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>