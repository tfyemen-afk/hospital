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
                    Birth Certificate
                </p>
            </div>
            <div class="body-certificate-area">
                <div class="body-certificate-top-area">
                    <strong>This certifies that</strong> <span><?=$birthregister->name?></span> Sex <span><?=($birthregister->gender==1) ? 'Male' : 'Female'?></span> was born</span> in this hospital at <span><?=date('H:i A', strtotime($birthregister->date))?></span> on <span><?=date('l', strtotime($birthregister->date))?></span> the <span><?=date('d', strtotime($birthregister->date))?><sup> <?=getNumberSuffix(date('d', strtotime($birthregister->date)))?></sup></span> day of <span><?=date('F, Y', strtotime($birthregister->date))?></span> Birth place <span><?=$birthregister->birth_place?></span> Nationality <span><?=$birthregister->nationality?></span> and parents <span><?=$birthregister->father_name?> and <?=$birthregister->mother_name?>
                </div>
                <div class="body-certificate-bottom-area">
                    <div class="body-certificate-bottom-text-area">
                        <strong>The witness whereof</strong> the said hospital has caused this certificate to be signed by its duly the authorized officers and its Corporate Seal to be hereunto affixed.
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