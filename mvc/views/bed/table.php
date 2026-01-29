<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('bed_slno')?></th>
                <th><?=$this->lang->line('bed_name')?></th>
                <th><?=$this->lang->line('bed_bedtype')?></th>
                <th><?=$this->lang->line('bed_ward')?></th>
                <?php if(permissionChecker('bed_edit') || permissionChecker('bed_delete')) { ?>
                    <th><?=$this->lang->line('bed_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($beds)) {foreach($beds as $bed) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('bed_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('bed_name')?>">
                        <?=$bed->name;?>
                    </td>
                    <td data-title="<?=$this->lang->line('bed_bedtype')?>">
                        <?=isset($bedtypes[$bed->bedtypeID]) ? $bedtypes[$bed->bedtypeID] : "&nbsp"?>
                    </td>
                    <td data-title="<?=$this->lang->line('bed_ward')?>">
                        <?=isset($wards[$bed->wardID]) ? $wards[$bed->wardID] : "&nbsp"?>
                    </td>
                    <?php if(permissionChecker('bed_edit') || permissionChecker('bed_delete')) { ?>
                        <td data-title="<?=$this->lang->line('bed_action')?>">
                            <?=btn_edit('bed/edit/'.$bed->bedID, $this->lang->line('bed_edit'))?>
                            <?=btn_delete('bed/delete/'.$bed->bedID, $this->lang->line('bed_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>