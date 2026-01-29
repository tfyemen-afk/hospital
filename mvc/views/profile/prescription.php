<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('profile_print'))?>
                <?=btn_sm_pdf('patient/prescriptionprintpreview/'.(isset($appointmentandadmissioninfo->appointmentID) ? '0/'.$appointmentandadmissioninfo->appointmentID : '1/'.$appointmentandadmissioninfo->admissionID), $this->lang->line('profile_pdf_preview'))?>
                <?=btn_sm_mail($this->lang->line('profile_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('profile/index')?>"><?=$this->lang->line('menu_profile')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('profile_prescription')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <div class="view-header-area prescription-header-area">
                    <div class="view-header-area-site-logo">
                        <img class="prescription-img" src="<?=base_url('uploads/general/'.$generalsettings->logo)?>" alt="">'
                    </div>
                    <div class="view-header-area-site-title">
                        <h2><?=$generalsettings->system_name?></h2>
                        <address class="prescription-address">
                            <?=$generalsettings->address?><br/>
                            <b><?=$this->lang->line('profile_email')?> : </b><?=$generalsettings->email?><br/>
                            <b><?=$this->lang->line('profile_phone')?> : </b><?=$generalsettings->phone?>
                        </address>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="view-header-area prescription-header-area">
                    <div class="profile-view-dis prescription-view-dis">
                        <div class="profile-view-tab prescription-view-tab">
                            <p><span><?=$this->lang->line('profile_uhid')?></span> : <?=$patientinfo->patientID?></p>
                        </div>
                        <div class="profile-view-tab prescription-view-tab">
                            <p><span><?=$this->lang->line('profile_visit_no')?></span> : <?=$prescription->visitno?></p>
                        </div>
                        <div class="profile-view-tab prescription-view-tab">
                            <p><span><?=$this->lang->line('profile_name')?></span> : <?=$patientinfo->name?></p>
                        </div>
                        <div class="profile-view-tab prescription-view-tab">
                            <p><span><?=$this->lang->line('profile_age')?> / <?=$this->lang->line('profile_sex')?></span> : <?=stringtoage($patientinfo->age_day, $patientinfo->age_month, $patientinfo->age_year)?> / <?=isset($gender[$patientinfo->gender]) ? $gender[$patientinfo->gender] : ''?></p>
                        </div>
                        <div class="profile-view-tab prescription-view-tab">
                            <p><span><?=$this->lang->line('profile_prescription_no')?></span> : <?=$prescription->prescriptionID?></p>
                        </div>
                        <div class="profile-view-tab prescription-view-tab">
                            <p><span><?=$this->lang->line('profile_prescription_date')?></span> : <?=app_datetime($prescription->create_date)?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="view-body-area">
            <div class="row">
                <div class="col-sm-4 view-body-area-left prescription-body-area-left">
                    <ul class="list-group prescription-list-group">
                        <li class="list-group-item prescription-list-group-item">
                            <p>
                                <span class="prescription-left-text"><?=$this->lang->line('profile_symptoms')?></span><br>
                                <?=$appointmentandadmissioninfo->symptoms?>
                            </p>
                        </li>
                        <li class="list-group-item prescription-list-group-item">
                            <p>
                                <span class="prescription-left-text"><?=$this->lang->line('profile_allergies')?></span><br>
                                <?=$appointmentandadmissioninfo->allergies?>
                            </p>
                        </li>
                        <li class="list-group-item prescription-list-group-item">
                            <p>
                                <span class="prescription-left-text"><?=$this->lang->line('profile_test')?></span><br>
                                <?=$appointmentandadmissioninfo->test?>
                            </p>
                        </li>
                        <li class="list-group-item prescription-list-group-item">
                            <p>
                                <span class="prescription-left-text"><?=$this->lang->line('profile_advice')?></span><br>
                                <?=$prescription->advice?>
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-8 view-body-area-right prescription-body-area-right">
                    <h2>R<sub>x</sub></h2>
                    <?php if(inicompute($prescriptionitems)) { ?>
                        <table class="prescription-table">
                            <tbody>
                            <?php $i = 0; foreach ($prescriptionitems as $prescriptionitem) { $i++; ?>
                                <tr>
                                    <td><?=$i?>.</td>
                                    <td>
                                        <span class="prescription-table-span-text"><?=$prescriptionitem->medicine?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>
                                        <?='&nbsp;&nbsp;&nbsp;'.$prescriptionitem->instruction?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="view-footer-area prescription-footer-area">
                    <p class="prescription-footer-area-details">[<?=$this->lang->line('profile_footer_description')?> <?=$this->lang->line('profile_hotline').' : '.$generalsettings->phone?>]</p>
                    <p class="prescription-footer-area-paragraph-body"><span class="prescription-left-create-by-text"><?=$this->lang->line('profile_prescribed_by')?> : <strong class="prescription-left-create-by-text"><?=inicompute($create) ? $create->name : ''?></strong></span><span class="prescription-right-printed-text"><?=$this->lang->line('profile_printed_date')?> : <strong class="prescription-right-printed-text"><?=date('d-m-Y h:i A')?></strong></span></p>
                </div>
            </div>
        </div>
    </section>
</article>