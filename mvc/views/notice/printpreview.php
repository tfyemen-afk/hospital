<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="main-notice-area">
                <p><b><?=$this->lang->line('notice_date')?>:- </b><?=date("d M Y", strtotime($notice->date));?></p>
                <h3 class="main-notice-area-title"><?=$notice->title;?></h3>
                <p><?=$notice->notice;?></p>
            </div>
            <div class="notice-footer-area">
                <div class="notice-signature-area">
                    <div class="notice-user-name"><?=$userName?></div>
                    <div>(<?=$userRole?>, <?=$generalsettings->system_name?>)</div>
                </div>
            </div>
        </div>
    </body>
</html>