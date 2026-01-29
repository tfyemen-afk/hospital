<?php if(inicompute($patient)) { ?>
        <div class="box-body box-profile" style="padding-bottom: 20px;">
            <img src="<?=imagelink($patient->photo, 'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
            <h3 class="profile-username text-center"><?=$patient->name?></h3>
        </div>
    <div class="profile-view-dis">
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_uhid')?> </span>: <?=$patient->patientID?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_type')?> </span>: <?=$this->lang->line('bedmanager_ipd')?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_gender')?> </span>: <?=($patient->gender == '1')? $this->lang->line('bedmanager_male'): $this->lang->line('bedmanager_female')?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_age')?> </span>: <?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year);?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_phone')?> </span>: <?=$patient->phone?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_guardianname')?> </span>: <?=$patient->guardianname?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_maritalstatus')?> </span>: <?=isset($maritalstatus[$patient->maritalstatus]) ? $maritalstatus[$patient->maritalstatus] : '' ?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_bloodgroup')?> </span>: <?=isset($bloodgroups[$patient->bloodgroupID]) ? $bloodgroups[$patient->bloodgroupID] : ''?></p>
        </div>
        <div class="profile-view-tab">
            <p><span><?=$this->lang->line('bedmanager_address')?> </span>: <?=$patient->address?></p>
        </div>
    </div>
<?php } else { ?>
    <div class="error-card">
        <div class="error-title-block">
            <h1 class="error-title">404</h1>
            <h2 class="error-sub-title"> Sorry, data not found </h2>
        </div>
        <div class="error-container">
            <a class="btn btn-primary" href="<?=site_url('dashboard/index')?>">
            <i class="fa fa-angle-left"></i> Back to Dashboard</a>
        </div>
    </div>
<?php } ?>