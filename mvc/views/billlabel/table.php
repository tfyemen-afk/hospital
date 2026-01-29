<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('billlabel_slno')?></th>
                <th><?=$this->lang->line('billlabel_name')?></th>
                <th><?=$this->lang->line('billlabel_bill_category')?></th>
                <th><?=$this->lang->line('billlabel_discount')?></th>
                <th><?=$this->lang->line('billlabel_amount')?></th>
                <?php if(permissionChecker('billlabel_edit') || permissionChecker('billlabel_delete')) { ?>
                    <th><?=$this->lang->line('billlabel_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($billlabels)) {foreach($billlabels as $billlabel) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('billlabel_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('billlabel_name')?>"><?=namesorting($billlabel->name, 30)?></td>
                    <td data-title="<?=$this->lang->line('billlabel_bill_category')?>"><?=isset($billcategorys[$billlabel->billcategoryID]) ? $billcategorys[$billlabel->billcategoryID] : ''?></td>
                    <td data-title="<?=$this->lang->line('billlabel_discount')?>"><?=$billlabel->discount?></td>
                    <td data-title="<?=$this->lang->line('billlabel_amount')?>"><?=$billlabel->amount?></td>
                    <?php if(permissionChecker('billlabel_edit') || permissionChecker('billlabel_delete')) { ?>
                        <td data-title="<?=$this->lang->line('billlabel_action')?>">
                            <?=btn_edit('billlabel/edit/'.$billlabel->billlabelID, $this->lang->line('billlabel_edit'))?>
                            <?=btn_delete('billlabel/delete/'.$billlabel->billlabelID, $this->lang->line('billlabel_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>