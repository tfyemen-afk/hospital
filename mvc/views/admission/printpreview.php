<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=pdfimagelink($user->photo,'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('admission_name')?></div>
                            <div class="single-user-info-value">: <?=$patient->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('admission_designation')?></div>
                            <div class="single-user-info-value">: <?=isset($designations[inicompute($user) ? $user->designationID : 2]) ? $designations[inicompute($user) ? $user->designationID : 2] : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('admission_uhid')?></div>
                            <div class="single-user-info-value">: <?=$patient->patientID?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('admission_type')?></div>
                            <div class="single-user-info-value">: <?=($patient->patienttypeID == 0) ? $this->lang->line('admission_opd') : $this->lang->line('admission_ipd') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('admission_phone')?></div>
                            <div class="single-user-info-value">: <?=$patient->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_gender')?></td>
                            <td><?=($patient->gender == 1) ? $this->lang->line('admission_male') : $this->lang->line('admission_female')?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_age')?></td>
                            <td><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_admissiondate')?></td>
                            <td><?=app_datetime($admission->admissiondate)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_doctor')?></td>
                            <td><?=inicompute($doctor) ? $doctor->name : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_case')?></td>
                            <td><?=$admission->pcase?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_casualty')?></td>
                            <td><?=($admission->pcase == 1) ? $this->lang->line('admission_yes') : $this->lang->line('admission_no') ?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_oldpatient')?></td>
                            <td><?=($admission->oldpatient == 1) ? $this->lang->line('admission_no') : $this->lang->line('admission_yes')?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_tpa')?></td>
                            <td><?=inicompute($tpa) ? $tpa->name : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_reference')?></td>
                            <td><?=$admission->reference?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_symptoms')?></td>
                            <td><?=$admission->symptoms?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_allergies')?></td>
                            <td><?=$admission->allergies?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_creditlimit')?></td>
                            <td><?=$admission->creditlimit?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_bed')?></td>
                            <td><?=inicompute($bed) ? $bed->name : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('admission_status')?></td>
                            <td>
                                <?php 
                                    if($admission->status==1) { ?>
                                        <span class="text-danger"><?=$this->lang->line('admission_release')?></span>
                                    <?php } else { ?>
                                        <span class="text-success"><?=$this->lang->line('admission_admitted')?></span>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>