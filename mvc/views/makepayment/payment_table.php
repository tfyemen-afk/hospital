<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('makepayment_slno')?></th>
                <th><?=$this->lang->line('makepayment_month')?></th>
                <th><?=$this->lang->line('makepayment_date')?></th>
                <th><?=($managesalary->salary == 2) ? $this->lang->line('makepayment_net_salary_hourly') : $this->lang->line('makepayment_net_salary')?></th>
                <th><?=$this->lang->line('makepayment_payment_amount')?></th>
                <th><?=$this->lang->line('makepayment_action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(inicompute($makepayments)) { $i = 1; foreach($makepayments as $makepayment) { ?>
                <tr>
                    <td data-title="<?=$this->lang->line('makepayment_slno')?>">
                        <?=$i?>
                    </td>
                    <td data-title="<?=$this->lang->line('makepayment_month')?>">
                        <?php echo date("M Y", strtotime('01-'.$makepayment->month)); ?>
                    </td>
                    <td data-title="<?=$this->lang->line('makepayment_date')?>">
                        <?php echo date("d M Y", strtotime($makepayment->create_date)); ?>
                    </td>
                    <td data-title="<?=($managesalary->salary == 2) ? $this->lang->line('makepayment_net_salary_hourly') : $this->lang->line('makepayment_net_salary')?>">
                        <?php
                            if(isset($makepayment->total_hours)) {
                                $makepayment_payment_amount = number_format(($makepayment->total_hours * $makepayment->net_salary), 2);
                                echo '('.$makepayment->total_hours. 'X' . $makepayment->net_salary .') = '. $makepayment_payment_amount; 
                            } else {
                                echo number_format($makepayment->net_salary, 2); 
                            }
                        ?>
                    </td>
                    <td data-title="<?=$this->lang->line('makepayment_payment_amount')?>">
                        <?=number_format($makepayment->payment_amount,2)?>
                    </td>
                    <td data-title="<?=$this->lang->line('makepayment_action')?>">
                        <?=btn_view_show('makepayment/view/'.$makepayment->makepaymentID, $this->lang->line('makepayment_view'))?>
                        <?=btn_delete_show('makepayment/delete/'.$makepayment->makepaymentID, $this->lang->line('makepayment_delete'))?>
                    </td>
                </tr>
            <?php $i++; }} ?>
        </tbody>
    </table>
</div>