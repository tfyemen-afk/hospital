<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-profile">
                    <div class="profile-view-item">
                        <div class="profile-view-item-label"><?=$this->lang->line('prescription_uhid')?></div> <div class="profile-view-item-value">: <?=$patientinfo->patientID?></div>
                    </div>
                    <div class="profile-view-item">
                        <div class="profile-view-item-label"><?=$this->lang->line('prescription_visit_no')?></div> <div class="profile-view-item-value">: <?=$prescription->visitno?></div>
                    </div>
                    <div class="profile-view-item">
                        <div class="profile-view-item-label"><?=$this->lang->line('prescription_name')?></div> <div class="profile-view-item-value">: <?=$patientinfo->name?></div>
                    </div>
                    <div class="profile-view-item">
                        <div class="profile-view-item-label"><?=$this->lang->line('prescription_age')?> / <?=$this->lang->line('prescription_sex')?></div> <div class="profile-view-item-value">: <?=stringtoage($patientinfo->age_day, $patientinfo->age_month, $patientinfo->age_year)?> / <?=isset($gender[$patientinfo->gender]) ? $gender[$patientinfo->gender] : ''?></div>
                    </div>
                    <div class="profile-view-item">
                        <div class="profile-view-item-label"><?=$this->lang->line('prescription_prescription_no')?></div> <div class="profile-view-item-value">: <?=$prescription->prescriptionID?></div>
                    </div>
                    <div class="profile-view-item">
                        <div class="profile-view-item-label"><?=$this->lang->line('prescription_prescription_date')?></div> <div class="profile-view-item-value">: <?=app_datetime($prescription->create_date)?></div>
                    </div>
                </div>
                
                <div class="view-main-area-prescription">
                    <div class="view-main-area-prescription-left">
                        <div class="prescription-single-group-item">
                            <span class="prescription-left-text"><?=$this->lang->line('prescription_symptoms')?></span><br>
                            <?=$appointmentandadmissioninfo->symptoms?>
                        </div>
                        <div class="prescription-single-group-item">
                            <span class="prescription-left-text"><?=$this->lang->line('prescription_allergies')?></span><br>
                            <?=$appointmentandadmissioninfo->allergies?>
                        </div>
                        <div class="prescription-single-group-item">
                            <span class="prescription-left-text"><?=$this->lang->line('prescription_test')?></span><br>
                            <?=$appointmentandadmissioninfo->test?>
                        </div>
                        <div class="prescription-single-group-item">
                            <span class="prescription-left-text"><?=$this->lang->line('prescription_advice')?></span><br>
                            <?=$prescription->advice?>
                        </div>
                    </div>
                    <div class="view-main-area-prescription-right">
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
                
                <div class="view-main-area-bottom prescription-footer-area">
                    <div class="prescription-footer-area-details">
                        [ <?=$this->lang->line('prescription_footer_description')?> <?=$this->lang->line('prescription_hotline').' : '.$generalsettings->phone?> ]
                    </div>
                    <div class="prescription-footer-area-paragraph-body">
                        <div class="pull-left"><?=$this->lang->line('prescription_prescribed_by')?> : <strong><?=inicompute($create) ? $create->name : ''?></strong></div>
                        <div class="pull-right"><?=$this->lang->line('prescription_printed_date')?> : <strong><?=date('d-m-Y h:i A')?></strong></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>