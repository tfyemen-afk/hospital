<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <?php reportheader($generalsettings);?>
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=pdfimagelink($patient->photo,'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_name')?></div>
                            <div class="single-user-info-value">: <?=$patient->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_uhid')?></div>
                            <div class="single-user-info-value">: <?=$patient->patientID?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_designation')?></div>
                            <div class="single-user-info-value">: <?=isset($designations[inicompute($user) ? $user->designationID : 2]) ? $designations[inicompute($user) ? $user->designationID : 2] : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_type')?></div>
                            <div class="single-user-info-value">: <?=($patient->patienttypeID == 0) ? $this->lang->line('profile_opd') : $this->lang->line('profile_ipd') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('profile_phone')?></div>
                            <div class="single-user-info-value">: <?=$patient->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_gender')?></td>
                            <td><?=($patient->gender == 1) ? $this->lang->line('profile_male') : $this->lang->line('profile_female') ?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_age')?></td>
                            <td><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year);?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_guardianname')?></td>
                            <td><?=$patient->guardianname?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_maritalstatus')?></td>
                            <td><?=isset($maritalstatus[$patient->maritalstatus]) ? $maritalstatus[$patient->maritalstatus] : '' ?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_bloodgroup')?></td>
                            <td><?=isset($bloodgroups[$patient->bloodgroupID]) ? $bloodgroups[$patient->bloodgroupID] : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_email')?></td>
                            <td><?=$user->email?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_username')?></td>
                            <td><?=$user->username?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('profile_address')?></td>
                            <td><?=$patient->address?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php reportfooter($generalsettings);?>
        </div>
    </body>
</html>