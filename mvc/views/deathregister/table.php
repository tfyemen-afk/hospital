<div class="btn-group pull-right">
    <a href="<?=site_url('deathregister/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('deathregister_today')?></a>
    <a href="<?=site_url('deathregister/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('deathregister_month')?></a>
    <a href="<?=site_url('deathregister/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('deathregister_year')?></a>
    <a href="<?=site_url('deathregister/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('deathregister_all')?></a>
</div>
<br>
<br>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('deathregister_slno')?></th>
                <th><?=$this->lang->line('deathregister_name_of_deceased')?></th>
                <th><?=$this->lang->line('deathregister_date_of_death')?></th>
                <?php if(permissionChecker('deathregister_view') || permissionChecker('deathregister_edit') || permissionChecker('deathregister_delete')) { ?>
                    <th><?=$this->lang->line('deathregister_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($deathregisters)) { foreach($deathregisters as $deathregister) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('deathregister_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('deathregister_name_of_deceased')?>"><?=$deathregister->name?></td>
                    <td data-title="<?=$this->lang->line('deathregister_date_of_death')?>"><?=app_datetime($deathregister->death_date)?></td>
                    <?php if(permissionChecker('deathregister_view') || permissionChecker('deathregister_edit') || permissionChecker('deathregister_delete')) { ?>
                        <td class="center" data-title="<?=$this->lang->line('deathregister_action')?>">
                            <?=btn_view('deathregister/view/'.$deathregister->deathregisterID.'/'.$displayID, $this->lang->line('deathregister_view'))?>
                            <?=btn_custom('deathregister_view', 'deathregister/certificate/'.$deathregister->deathregisterID.'/'.$displayID, $this->lang->line('deathregister_certificate'), 'fa fa-file-text-o', 'btn-primary')?>
                            <?=btn_edit('deathregister/edit/'.$deathregister->deathregisterID.'/'.$displayID, $this->lang->line('deathregister_edit'))?>
                            <?=btn_delete('deathregister/delete/'.$deathregister->deathregisterID.'/'.$displayID, $this->lang->line('deathregister_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>