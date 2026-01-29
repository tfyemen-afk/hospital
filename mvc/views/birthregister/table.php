<div class="btn-group pull-right">
    <a href="<?=site_url('birthregister/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('birthregister_today')?></a>
    <a href="<?=site_url('birthregister/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('birthregister_month')?></a>
    <a href="<?=site_url('birthregister/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('birthregister_year')?></a>
    <a href="<?=site_url('birthregister/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('birthregister_all')?></a>
</div>
<br>
<br>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('birthregister_slno')?></th>
                <th><?=$this->lang->line('birthregister_name')?></th>
                <th><?=$this->lang->line('birthregister_mother_name')?></th>
                <th><?=$this->lang->line('birthregister_date')?></th>
                <?php if(permissionChecker('birthregister_view') || permissionChecker('birthregister_edit') || permissionChecker('birthregister_delete')) { ?>
                    <th><?=$this->lang->line('birthregister_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($birthregisters)) { foreach($birthregisters as $birthregister) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('birthregister_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('birthregister_name')?>"><?=$birthregister->name?></td>
                    <td data-title="<?=$this->lang->line('birthregister_mother_name')?>"><?=$birthregister->mother_name?></td>
                    <td data-title="<?=$this->lang->line('birthregister_date')?>"><?=app_datetime($birthregister->date)?></td>
                    <?php if(permissionChecker('birthregister_view') || permissionChecker('birthregister_edit') || permissionChecker('birthregister_delete')) { ?>
                        <td class="center" data-title="<?=$this->lang->line('birthregister_action')?>">
                            <?=btn_view('birthregister/view/'.$birthregister->birthregisterID.'/'.$displayID, $this->lang->line('birthregister_view'))?>
                            <?=btn_custom('birthregister_view', 'birthregister/certificate/'.$birthregister->birthregisterID.'/'.$displayID, $this->lang->line('birthregister_certificate'), 'fa fa-file-text-o', 'btn-primary')?>
                            <?=btn_edit('birthregister/edit/'.$birthregister->birthregisterID.'/'.$displayID, $this->lang->line('birthregister_edit'))?>
                            <?=btn_delete('birthregister/delete/'.$birthregister->birthregisterID.'/'.$displayID, $this->lang->line('birthregister_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>