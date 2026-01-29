<div class="btn-group pull-right">
    <a href="<?=site_url('notice/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('notice_today')?></a>
    <a href="<?=site_url('notice/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('notice_month')?></a>
    <a href="<?=site_url('notice/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('notice_year')?></a>
    <a href="<?=site_url('notice/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('notice_all')?></a>
</div>
<br>
<br>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('notice_slno')?></th>
                <th><?=$this->lang->line('notice_title')?></th>
                <th><?=$this->lang->line('notice_date')?></th>
                <th><?=$this->lang->line('notice_notice')?></th>
                <?php if(permissionChecker('notice_edit') || permissionChecker('notice_delete') || permissionChecker('notice_view')) { ?>
                    <th><?=$this->lang->line('notice_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($notices)) {foreach($notices as $notice) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('notice_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('notice_title')?>"><?=namesorting($notice->title,25)?></td>
                    <td data-title="<?=$this->lang->line('notice_date')?>"><?=app_date($notice->date)?></td>
                    <td data-title="<?=$this->lang->line('notice_notice')?>"><?=namesorting($notice->notice,30)?></td>
                    <?php if(permissionChecker('notice_edit') || permissionChecker('notice_delete') || permissionChecker('notice_view')) { ?>
                        <td data-title="<?=$this->lang->line('notice_action')?>">
                            <?=btn_view('notice/view/'.$notice->noticeID.'/'.$displayID, $this->lang->line('notice_view'))?>
                            <?=btn_edit('notice/edit/'.$notice->noticeID.'/'.$displayID, $this->lang->line('notice_edit'))?>
                            <?=btn_delete('notice/delete/'.$notice->noticeID.'/'.$displayID, $this->lang->line('notice_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>