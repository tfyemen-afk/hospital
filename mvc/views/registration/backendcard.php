<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="idcard-backend">
        <div class="backend-item">
            <div class="backend-label"><?=$this->lang->line('registration_guardian')?></div>
            <div class="backend-value">: <?=$patient->guardianname?></div>
        </div>
        <div class="backend-item">
            <div class="backend-label"><?=$this->lang->line('registration_address')?></div>
            <div class="backend-value">: <?=$patient->address?></div>
        </div>
        <div class="backend-item">
            <div class="backend-label"><?=$this->lang->line('registration_email')?></div>
            <div class="backend-value">: <?=$patient->email?></div>
        </div>
        <div class="backend-item">
            <div class="backend-label"><?=$this->lang->line('registration_phone')?></div>
            <div class="backend-value">: <?=$patient->phone?></div>
        </div>
        <div class="backend-item backend-address">
            <?=$generalsettings->address?>, <?=$this->lang->line('registration_email')?>:- <?=$generalsettings->email?>, <?=$this->lang->line('registration_phone')?>:- <?=$generalsettings->phone?>
        </div>
    </div>
</body>
</html>