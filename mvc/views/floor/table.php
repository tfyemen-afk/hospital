<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('floor_slno')?></th>
                <th><?=$this->lang->line('floor_name')?></th>
                <th><?=$this->lang->line('floor_description')?></th>
                <?php if(permissionChecker('floor_edit') || permissionChecker('floor_delete')) { ?>
                    <th><?=$this->lang->line('floor_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($floors)) {foreach($floors as $floor) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('floor_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('floor_name')?>">
                        <?=$floor->name;?>
                    </td>
                    <td data-title="<?=$this->lang->line('floor_description')?>">
                        <?=empty($floor->description) ? '&nbsp;' : namesorting($floor->description, 50);?>
                    </td>
                    <?php if(permissionChecker('floor_edit') || permissionChecker('floor_delete')) { ?>
                        <td data-title="<?=$this->lang->line('floor_action')?>">
                            <?=btn_edit('floor/edit/'.$floor->floorID, $this->lang->line('floor_edit'))?>
                            <?=btn_delete('floor/delete/'.$floor->floorID, $this->lang->line('floor_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>