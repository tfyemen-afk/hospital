<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('medicineunit_slno')?></th>
                <th><?=$this->lang->line('medicineunit_unit')?></th>
                <?php if(permissionChecker('medicineunit_edit') || permissionChecker('medicineunit_delete')) { ?>
                    <th><?=$this->lang->line('medicineunit_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($medicineunits)) { foreach($medicineunits as $medicineunit) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('medicineunit_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('medicineunit_unit')?>"><?=$medicineunit->medicineunit?></td>
                    <?php if(permissionChecker('medicineunit_edit') || permissionChecker('medicineunit_delete')) { ?>
                        <td data-title="<?=$this->lang->line('medicineunit_action')?>">
                            <?=btn_edit('medicineunit/edit/'.$medicineunit->medicineunitID, $this->lang->line('medicineunit_edit'))?>
                            <?=btn_delete('medicineunit/delete/'.$medicineunit->medicineunitID, $this->lang->line('medicineunit_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>