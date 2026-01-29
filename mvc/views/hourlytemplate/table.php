<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('hourlytemplate_slno')?></th>
                <th><?=$this->lang->line('hourlytemplate_hourly_grades')?></th>
                <th><?=$this->lang->line('hourlytemplate_hourly_rate')?></th>
                <?php if(permissionChecker('hourlytemplate_edit') || permissionChecker('hourlytemplate_delete')) { ?>
                    <th><?=$this->lang->line('hourlytemplate_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if(inicompute($hourlytemplates)) {$i = 1; foreach($hourlytemplates as $hourlytemplate) { ?>
                <tr>
                    <td data-title="<?=$this->lang->line('hourlytemplate_slno')?>">
                        <?=$i; ?>
                    </td>
                    <td data-title="<?=$this->lang->line('hourlytemplate_hourly_grades')?>">
                        <?=$hourlytemplate->hourly_grades?>
                    </td>
                    <td data-title="<?=$this->lang->line('hourlytemplate_hourly_rate')?>">
                        <?=$hourlytemplate->hourly_rate?>
                    </td>
                    <?php if(permissionChecker('hourlytemplate_edit') || permissionChecker('hourlytemplate_delete')) { ?>
                        <td data-title="<?=$this->lang->line('hourlytemplate_action')?>">
                            <?=btn_edit('hourlytemplate/edit/'.$hourlytemplate->hourlytemplateID, $this->lang->line('hourlytemplate_edit'))?>
                            <?=btn_delete('hourlytemplate/delete/'.$hourlytemplate->hourlytemplateID, $this->lang->line('hourlytemplate_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php $i++; } } ?>
        </tbody>
    </table>
</div>