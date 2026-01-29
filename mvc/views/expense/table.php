<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('expense_slno')?></th>
                <th><?=$this->lang->line('expense_name')?></th>
                <th><?=$this->lang->line('expense_date')?></th>
                <th><?=$this->lang->line('expense_user')?></th>
                <th><?=$this->lang->line('expense_amount')?></th>
                <th><?=$this->lang->line('expense_file')?></th>
                <?php if(permissionChecker('expense_edit') || permissionChecker('expense_delete')) { ?>
                    <th><?=$this->lang->line('expense_action')?></th>
                <?php }?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($expenses)) {foreach($expenses as $expense) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('expense_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('expense_name')?>">
                        <?php echo $expense->name; ?>
                    </td>
                    <td data-title="<?=$this->lang->line('expense_date')?>">
                        <?=date('d M Y',strtotime($expense->date))?>
                    </td>
                    <td data-title="<?=$this->lang->line('expense_user')?>">
                        <?=isset($users[$expense->create_userID]) ? $users[$expense->create_userID] : '' ?>
                    </td>
                    <td data-title="<?=$this->lang->line('expense_amount')?>">
                        <?=number_format($expense->amount, 2)?>
                    </td>
                    <td data-title="<?=$this->lang->line('expense_file')?>">
                         <?php 
                            if($expense->file) { 
                                echo btn_download_file('expense/download/'.$expense->expenseID, $this->lang->line('expense_download'), $this->lang->line('expense_download')); 
                            }
                        ?>
                    </td>
                    <?php if(permissionChecker('expense_edit') || permissionChecker('expense_delete')) { ?>
                        <td data-title="<?=$this->lang->line('expense_action')?>">
                            <?=btn_edit('expense/edit/'.$expense->expenseID, $this->lang->line('expense_edit'))?>
                            <?=btn_delete('expense/delete/'.$expense->expenseID, $this->lang->line('expense_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>