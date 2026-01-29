<?php if($loginroleID != 3) { ?>
<div class="btn-group pull-right">
    <a href="<?=site_url('bill/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('bill_today')?></a>
    <a href="<?=site_url('bill/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('bill_month')?></a>
    <a href="<?=site_url('bill/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('bill_year')?></a>
    <a href="<?=site_url('bill/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('bill_all')?></a>
</div>
<br>
<br>
<?php } ?>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('bill_slno')?></th>
                <th><?=$this->lang->line('bill_uhid')?></th>
                <th><?=$this->lang->line('bill_patient')?></th>
                <th><?=$this->lang->line('bill_patient_type')?></th>
                <th><?=$this->lang->line('bill_date')?></th>
                <th><?=$this->lang->line('bill_payment_status')?></th>
                <th><?=$this->lang->line('bill_total_amount')?></th>
                <?php if(permissionChecker('bill_view') || permissionChecker('bill_edit') || permissionChecker('bill_delete')) { ?>
                    <th><?=$this->lang->line('bill_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($bills)) { foreach($bills as $bill) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('bill_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('bill_uhid')?>"><?=$bill->patientID?></td>
                    <td data-title="<?=$this->lang->line('bill_patient')?>"><?=$bill->name?></td>
                    <td data-title="<?=$this->lang->line('bill_patient_type')?>"><?=isset($patienttypes[$bill->patienttypeID]) ? $patienttypes[$bill->patienttypeID] : ''?></td>
                    <td data-title="<?=$this->lang->line('bill_date')?>"><?=app_datetime($bill->date)?></td>
                    <td data-title="<?=$this->lang->line('bill_payment_status')?>"><?=isset($billstatus[$bill->status]) ? $billstatus[$bill->status] : ''?></td>
                    <td data-title="<?=$this->lang->line('bill_total_amount')?>"><?=number_format($bill->totalamount, 2)?></td>
                    <?php if(permissionChecker('bill_view') || permissionChecker('bill_edit') || permissionChecker('bill_delete')) { ?>
                        <td data-title="<?=$this->lang->line('bill_action')?>">
                            <?=btn_view('bill/view/'.$bill->billID.'/'.$displayID, $this->lang->line('bill_view'))?>
                            <?=btn_edit('bill/edit/'.$bill->billID.'/'.$displayID, $this->lang->line('bill_edit'))?>
                            <?=btn_delete('bill/delete/'.$bill->billID.'/'.$displayID, $this->lang->line('bill_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>