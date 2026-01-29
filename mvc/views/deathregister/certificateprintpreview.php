<!DOCTYPE html>
<html lang="en">
    <head>
    </head>
    <body>
        <div class="transparent-bg-image" style="background: url('<?=site_url('uploads/general/'.$generalsettings->logo)?>'); background-repeat: no-repeat">&nbsp;</div>
        <div class="main-certificate-area">
            <div class="header-certificate-area">
                <h2><?=$generalsettings->system_name?></h2>
                <p><?=$generalsettings->address?></p>
                <p class="header-birth-certificate">
                    Death Certificate
                </p>
            </div>
            <div class="body-certificate-area">
                <div class="body-certificate-top-area">
                    <strong>This is to certify that</strong> <span><?=$deathregister->name?></span> Sex <span><?=($deathregister->gender==1) ? 'Male' : 'Female'?> </span> passed away at <strong><?=$generalsettings->system_name?></strong> on <span><?=date('d-m-Y', strtotime($deathregister->death_date))?></span> at <span><?=date('H:i A', strtotime($deathregister->death_date))?></span> in witness of  <?=($deathregister->gender==1) ? 'his' : 'her'?> <?=$deathregister->relation?> <span><?=$deathregister->guardian_name?></span>.
                </div>
                <div class="body-certificate-bottom-area">
                    <div class="body-certificate-bottom-text-area">
                        Confirmed this on <span><?=app_datetime($deathregister->death_date)?></span> by <span><?=inicompute($doctor) ? $doctor->name : ''?></span>
                    </div>
                </div>
            </div>
            <div class="footer-certificate-area">
                <div class="footer-certificate-bottom-signature-area">
                    <div class="footer-certificate-bottom-signature-left">
                        <span>Administrator</span>
                    </div>
                    <div class="footer-certificate-bottom-signature-right">
                        <span>Doctor</span>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>