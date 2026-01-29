<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('income_slno')?></th>
                <th><?=$this->lang->line('income_name')?></th>
                <th><?=$this->lang->line('income_date')?></th>
                <th><?=$this->lang->line('income_user')?></th>
                <th><?=$this->lang->line('income_amount')?></th>
                <th><?=$this->lang->line('income_file')?></th>
                <?php if(permissionChecker('income_edit') || permissionChecker('income_delete')) { ?>
                    <th><?=$this->lang->line('income_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($incomes)) {foreach($incomes as $income) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('income_slno')?>">
                        <?=$i; ?>
                    </td>

                    <td data-title="<?=$this->lang->line('income_name')?>">
                        <?=$income->name; ?>
                    </td>

                    <td data-title="<?=$this->lang->line('income_date')?>">
                        <?=app_date($income->date)?>
                    </td>

                    <td data-title="<?=$this->lang->line('income_user')?>">
                        <?=isset($users[$income->create_userID]) ? $users[$income->create_userID] : '' ?>
                    </td>

                    <td data-title="<?=$this->lang->line('income_amount')?>">
                        <?=number_format($income->amount, 2)?>
                    </td>
                    <td data-title="<?=$this->lang->line('income_file')?>">
                         <?php 
                            if($income->file) { 
                                echo btn_download_file('income/download/'.$income->incomeID, $this->lang->line('income_download'), $this->lang->line('income_download')); 
                            }
                        ?>
                    </td>
                    <?php if(permissionChecker('income_edit') || permissionChecker('income_delete')) { ?>
                        <td data-title="<?=$this->lang->line('income_action')?>">
                            <?=btn_edit('income/edit/'.$income->incomeID, $this->lang->line('income_edit'))?>
                            <?=btn_delete('income/delete/'.$income->incomeID, $this->lang->line('income_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>