<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('bedtype_slno')?></th>
                <th><?=$this->lang->line('bedtype_name')?></th>
                <?php if(permissionChecker('bedtype_edit') || permissionChecker('bedtype_delete')) { ?>
                    <th><?=$this->lang->line('bedtype_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($bedtypes)) {foreach($bedtypes as $bedtype) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('bedtype_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('bedtype_name')?>">
                        <?=$bedtype->name;?>
                    </td>
                    <?php if(permissionChecker('bedtype_edit') || permissionChecker('bedtype_delete')) { ?>
                        <td data-title="<?=$this->lang->line('bedtype_action')?>">
                            <?=btn_edit('bedtype/edit/'.$bedtype->bedtypeID, $this->lang->line('bedtype_edit'))?>
                            <?=btn_delete('bedtype/delete/'.$bedtype->bedtypeID, $this->lang->line('bedtype_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>