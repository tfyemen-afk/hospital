<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('profile_print'))?>
                <?=btn_sm_pdf('patient/dischargeprintpreview/'.$discharge->admissionID, $this->lang->line('profile_pdf_preview'))?>
                <?=btn_sm_mail($this->lang->line('profile_send_pdf_to_mail'))?>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('profile/index')?>"><?=$this->lang->line('menu_profile')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('profile_discharge')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
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
                            <b><?=$this->lang->line('profile_email')?> : </b><?=$generalsettings->email?><br/>
                            <b><?=$this->lang->line('profile_phone')?> : </b><?=$generalsettings->phone?>
                        </address>
                    </div>
                </div>

                <div class="receipt-body-div">
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="receipt-feature-header-title"><i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> &nbsp;&nbsp;<?=$this->lang->line('profile_discharge')?>&nbsp;&nbsp <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i> <i class="fa fa-star-o"></i></p>
                        </div>
                    </div>
                </div>

                <div class="receipt-body-div">
                    <div class="row">
                        <div class="col-sm-6">
                            <?=$this->lang->line('profile_discharge_no')?> : <?=$discharge->dischargeID?>
                        </div>
                        <div class="col-sm-6">
                            <span class="receipt-pull-right"><?=$this->lang->line('profile_date')?> : <?=date('d/m/Y')?></span>
                        </div>
                    </div>
                </div>

                <div class="receipt-body-div receipt-body-font-style">
                    <div class="row">
                        <div class="col-sm-4">
                            <span class="receipt-col-sm-3-5"><?=$this->lang->line('profile_uhid')?> :  &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=$patient->patientID?></span>
                        </div>
                        <div class="col-sm-4">
                            <span class="receipt-col-sm-3-5"><?=$this->lang->line('profile_name')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=$patient->name?></span>
                        </div>
                        <div class="col-sm-4">
                            <span class="receipt-col-sm-3-5"><?=$this->lang->line('profile_age')?> / <?=$this->lang->line('profile_sex')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?> / <?=isset($genders[$patient->gender]) ? $genders[$patient->gender] : ''?></span>
                        </div>
                    </div>
                </div>

                <div class="receipt-body-div receipt-body-font-style">
                    <div class="row">
                        <div class="col-sm-4">
                            <span class="receipt-col-sm-3-5"><?=$this->lang->line('profile_date_of_admission')?> :  &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=app_datetime($patient->admissiondate)?></span>
                        </div>
                        <div class="col-sm-4">
                            <span class="receipt-col-sm-3-5"><?=$this->lang->line('profile_date_of_discharge')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=app_datetime($discharge->date)?></span>
                        </div>
                        <div class="col-sm-4">
                            <span class="receipt-col-sm-3-5"><?=$this->lang->line('profile_bed_no')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=inicompute($bed) ? $bed->name : ''?></span>
                        </div>
                    </div>
                </div>

                <div class="receipt-body-div receipt-body-font-style">
                    <div class="row">
                        <div class="col-sm-4">
                            <span class="receipt-col-sm-3-5"><?=$this->lang->line('profile_room')?> / <?=$this->lang->line('profile_ward')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=inicompute($room) ? $room->name : ''?> / <?=inicompute($ward) ? $ward->name : ''?> - <?=inicompute($floor) ? $floor->name : ''?></span>
                        </div>
                        <div class="col-sm-8">
                            <span class="receipt-col-sm-3-5"><?=$this->lang->line('profile_condition_of_discharge')?> : &nbsp;&nbsp;</span> <span class="receipt-col-sm-6"><?=isset($conditions[$discharge->conditionofdischarge]) ? $conditions[$discharge->conditionofdischarge] : '' ?></span>
                        </div>
                    </div>
                </div>

                <div class="receipt-signature-div receipt-body-font-style">
                    <div class="row">
                        <div class="col-sm-12">
                            <span class="receipt-signature-col-sm-1-5"><?=$this->lang->line('profile_signature')?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>