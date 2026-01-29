<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('postcategories_slno')?></th>
                <th><?=$this->lang->line('postcategories_name')?></th>
                <th><?=$this->lang->line('postcategories_description')?></th>
                <?php if(permissionChecker('postcategories_edit') || permissionChecker('postcategories_delete')) { ?>
                    <th><?=$this->lang->line('postcategories_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($postcategories)) {foreach($postcategories as $postcategorie) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('postcategories_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('postcategories_name')?>"><?=$postcategorie->postcategories?></td>
                    <td data-title="<?=$this->lang->line('postcategories_description')?>"><?=empty($postcategorie->postdescription) ? '&nbsp;' : $postcategorie->postdescription?></td>
                    <?php if(permissionChecker('postcategories_edit') || permissionChecker('postcategories_delete')) { ?>
                        <td data-title="<?=$this->lang->line('postcategories_action')?>">
                            <?=btn_edit('postcategories/edit/'.$postcategorie->postcategoriesID, $this->lang->line('postcategories_edit'))?>
                            <?=btn_delete('postcategories/delete/'.$postcategorie->postcategoriesID, $this->lang->line('postcategories_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>