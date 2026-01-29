<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="idcard-frontend">
        <div class="frontend-header">
            <div class="frontend-logo"><img class="frontend-logo-img" src="<?=imagelink($generalsettings->logo,'uploads/general')?>" alt=""></div>
            <div class="frontend-title"><span><?=$generalsettings->system_name?></span></div>
        </div>
        <div class="frontend-body">
            <h5 class="frontend-visiting-card"><?=$this->lang->line('registration_registration_card')?></h5>
            <div class="frontend-photo">
                <img class="frontend-photo-img" src="<?=imagelink($patient->photo,'uploads/user')?>" alt="">
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
</body>
</html>