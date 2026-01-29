<div class="btn-group pull-right">
    <a href="<?=site_url('event/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('event_today')?></a>
    <a href="<?=site_url('event/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('event_month')?></a>
    <a href="<?=site_url('event/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('event_year')?></a>
    <a href="<?=site_url('event/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('event_all')?></a>
</div>
<br>
<br>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('event_slno')?></th>
                <th><?=$this->lang->line('event_title')?></th>
                <th><?=$this->lang->line('event_from_date')?></th>
                <th><?=$this->lang->line('event_to_date')?></th>
                <?php if(permissionChecker('event_edit') || permissionChecker('event_delete') || permissionChecker('event_view')) { ?>
                    <th><?=$this->lang->line('event_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($events)) { foreach($events as $event) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('event_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('event_title')?>"><?=namesorting($event->title, 25)?></td>
                    <td data-title="<?=$this->lang->line('event_from_date')?>"><?=app_date_time($event->fdate, $event->ftime)?></td>
                    <td data-title="<?=$this->lang->line('event_to_date')?>"><?=app_date_time($event->tdate, $event->ttime)?></td>
                    <?php if(permissionChecker('event_edit') || permissionChecker('event_delete') || permissionChecker('event_view')) { ?>
                        <td data-title="<?=$this->lang->line('event_action')?>">
                            <?=btn_view('event/view/'.$event->eventID.'/'.$displayID, $this->lang->line('event_view'))?>
                            <?=btn_edit('event/edit/'.$event->eventID.'/'.$displayID, $this->lang->line('event_edit'))?>
                            <?=btn_delete('event/delete/'.$event->eventID.'/'.$displayID, $this->lang->line('event_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>