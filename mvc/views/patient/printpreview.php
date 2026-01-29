<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=pdfimagelink($patient->photo,'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('patient_name')?></div>
                            <div class="single-user-info-value">: <?=$patient->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('patient_uhid')?></div>
                            <div class="single-user-info-value">: <?=$patient->patientID?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('patient_designation')?></div>
                            <div class="single-user-info-value">: <?=isset($designations[inicompute($user) ? $user->designationID : 2]) ? $designations[inicompute($user) ? $user->designationID : 2] : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('patient_type')?></div>
                            <div class="single-user-info-value">: <?=($patient->patienttypeID == 0) ? $this->lang->line('patient_opd') : $this->lang->line('patient_ipd') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('patient_phone')?></div>
                            <div class="single-user-info-value">: <?=$patient->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('patient_gender')?></td>
                            <td><?=($patient->gender == 1) ? $this->lang->line('patient_male') : $this->lang->line('patient_female') ?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('patient_age')?></td>
                            <td><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year);?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('patient_guardianname')?></td>
                            <td><?=$patient->guardianname?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('patient_maritalstatus')?></td>
                            <td><?=isset($maritalstatus[$patient->maritalstatus]) ? $maritalstatus[$patient->maritalstatus] : '' ?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('patient_bloodgroup')?></td>
                            <td><?=isset($bloodgroups[$patient->bloodgroupID]) ? $bloodgroups[$patient->bloodgroupID] : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('patient_email')?></td>
                            <td><?=$user->email?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('patient_username')?></td>
                            <td><?=$user->username?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('patient_address')?></td>
                            <td><?=$patient->address?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>