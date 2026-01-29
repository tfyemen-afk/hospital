<?php if($loginroleID != 3) { ?>
<div class="btn-group pull-right">
    <a href="<?=site_url('billpayment/index/1/'.$displayuhID)?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('billpayment_today')?></a>
    <a href="<?=site_url('billpayment/index/2/'.$displayuhID)?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('billpayment_month')?></a>
    <a href="<?=site_url('billpayment/index/3/'.$displayuhID)?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('billpayment_year')?></a>
    <a href="<?=site_url('billpayment/index/4/'.$displayuhID)?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('billpayment_all')?></a>
</div>
<br>
<br>
<?php } ?>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('billpayment_slno')?></th>
                <th><?=$this->lang->line('billpayment_uhid')?></th>
                <th><?=$this->lang->line('billpayment_name')?></th>
                <th><?=$this->lang->line('billpayment_date')?></th>
                <th><?=$this->lang->line('billpayment_method')?></th>
                <th><?=$this->lang->line('billpayment_amount')?></th>
                <?php if(permissionChecker('billpayment_view') || permissionChecker('billpayment_edit') || permissionChecker('billpayment_delete')) { ?>
                    <th><?=$this->lang->line('billpayment_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($billpayments)) { foreach($billpayments as $billpayment) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('billpayment_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('billpayment_uhid')?>"><?=$billpayment->patientID?></td>
                    <td data-title="<?=$this->lang->line('billpayment_name')?>"><?=$billpayment->name?></td>
                    <td data-title="<?=$this->lang->line('billpayment_date')?>"><?=app_datetime($billpayment->paymentdate)?></td>
                    <td data-title="<?=$this->lang->line('billpayment_method')?>"><?=isset($paymentmethods[$billpayment->paymentmethod]) ? $paymentmethods[$billpayment->paymentmethod] : ''?></td>
                    <td data-title="<?=$this->lang->line('billpayment_amount')?>"><?=number_format($billpayment->paymentamount, 2)?></td>
                    <?php if(permissionChecker('billpayment_view') || permissionChecker('billpayment_edit') || permissionChecker('billpayment_delete')) { ?>
                        <td data-title="<?=$this->lang->line('billpayment_action')?>">
                            <?=btn_view('billpayment/view/'.$billpayment->billpaymentID.'/'.$displayID.'/'.$displayuhID, $this->lang->line('billpayment_view'))?>
                            <?=btn_edit('billpayment/edit/'.$billpayment->billpaymentID.'/'.$displayID.'/'.$displayuhID, $this->lang->line('billpayment_edit'))?>
                            <?=btn_delete('billpayment/delete/'.$billpayment->billpaymentID.'/'.$displayID.'/'.$displayuhID, $this->lang->line('billpayment_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>