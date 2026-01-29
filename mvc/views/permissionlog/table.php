<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('permissionlog_slno')?></th>
                <th><?=$this->lang->line('permissionlog_name')?></th>
                <th><?=$this->lang->line('permissionlog_description')?></th>
                <th><?=$this->lang->line('permissionlog_active')?></th>
                <?php if(permissionChecker('permissionlog_edit') || permissionChecker('permissionlog_delete')) { ?>
                    <th><?=$this->lang->line('permissionlog_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($permissionlogs)) { foreach($permissionlogs as $permissionlog) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('permissionlog_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('permissionlog_name')?>"><?=$permissionlog->name; ?></td>
                    <td data-title="<?=$this->lang->line('permissionlog_description')?>"><?=$permissionlog->description; ?></td>
                    <td data-title="<?=$this->lang->line('permissionlog_active')?>"><?=$permissionlog->active; ?></td>
                    <?php if(permissionChecker('permissionlog_edit') || permissionChecker('permissionlog_delete')) { ?>
                        <td data-title="<?=$this->lang->line('permissionlog_action')?>">
                            <?=btn_edit('permissionlog/edit/'.$permissionlog->permissionlogID, $this->lang->line('permissionlog_edit'))?>
                            <?=btn_delete('permissionlog/delete/'.$permissionlog->permissionlogID, $this->lang->line('permissionlog_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>