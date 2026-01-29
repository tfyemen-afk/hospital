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
                            <div class="single-user-info-label"><?=$this->lang->line('registration_name')?></div>
                            <div class="single-user-info-value">: <?=$patient->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('registration_designation')?></div>
                            <div class="single-user-info-value">: <?=inicompute($designation) ? $designation->designation : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('registration_uhid')?></div>
                            <div class="single-user-info-value">: <?=$patient->patientID?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('registration_type')?></div>
                            <div class="single-user-info-value">: 
                                <?php
                                    if($patient->patienttypeID == 0) {
                                        echo $this->lang->line('registration_opd');
                                    } elseif($patient->patienttypeID == 5) {
                                        echo $this->lang->line('registration_register');
                                    } else {
                                        echo $this->lang->line('registration_ipd');
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('registration_phone')?></div>
                            <div class="single-user-info-value">: <?=$patient->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('registration_gender')?></td>
                            <td><?=($patient->gender == 1) ? $this->lang->line('registration_male') : $this->lang->line('registration_female') ?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('registration_age')?></td>
                            <td><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('registration_guardianname')?></td>
                            <td><?=$patient->guardianname?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('registration_marital_status')?></td>
                            <td><?=isset($maritalstatusArray[$patient->maritalstatus]) ? $maritalstatusArray[$patient->maritalstatus] : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('registration_bloodgroup')?></td>
                            <td><?=inicompute($bloodgroup) ? $bloodgroup->bloodgroup : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('registration_email')?></td>
                            <td><?=$patient->email?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('registration_username')?></td>
                            <td><?=$user->username?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('registration_address')?></td>
                            <td><?=$patient->address?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>