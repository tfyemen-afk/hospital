<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="main-date-area">
                <div class="pull-left"><b><?=$this->lang->line('event_from_date')?>:- </b><?=app_date_time($event->fdate, $event->ftime);?></div>
                <div class="pull-right"><b><?=$this->lang->line('event_to_date')?>:- </b><?=app_date_time($event->tdate, $event->ttime);?></div>
            </div>
            <div class="main-event-area">
                <h3 class="main-event-area-title"><?=$event->title;?></h3>
                <p><?=$event->description;?></p>
            </div>
            <div class="event-footer-area">
                <div class="event-signature-area">
                    <div class="event-user-name"><?=$userName?></div>
                    <div>(<?=$userRole?>, <?=$generalsettings->system_name?>)</div>
                </div>
            </div>
        </div>
    </body>
</html>