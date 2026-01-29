<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-bill">
                    <div class="view-main-area-bill-left">
                        <div class="single-bill-info">
                            <span class="font-weight-bold"><?=$this->lang->line('bill_bill_no')?> </span>: <?=$bill->billID?>
                        </div>
                        <div class="single-bill-info">
                            <span class="font-weight-bold"><?=$this->lang->line('bill_uhid')?> </span>: <?=$patient->patientID?>
                        </div>
                        <div class="single-bill-info">
                            <span class="font-weight-bold"><?=$this->lang->line('bill_name')?> </span>: <?=$patient->name?>
                        </div>
                        <div class="single-bill-info">
                            <span class="font-weight-bold"><?=$this->lang->line('bill_patient_type')?> </span>: <?=isset($patienttypes[$bill->patienttypeID]) ? $patienttypes[$bill->patienttypeID]  : '' ?>
                        </div>
                        <div class="single-bill-info">
                            <span class="font-weight-bold"><?=$this->lang->line('bill_date')?> </span>: <?=app_datetime($bill->date)?>
                        </div>
                        <div class="single-bill-info">
                            <span class="font-weight-bold"><?=$this->lang->line('bill_payment_status')?> </span>: <?=isset($billstatus[$bill->status]) ? $billstatus[$bill->status] : ''?>
                        </div>
                        <div class="single-bill-info">
                            <span class="font-weight-bold"><?=$this->lang->line('bill_note')?> </span>: <?=$bill->note?>
                        </div>
                    </div>
                    <div class="view-main-area-bill-right">
                        <div class="bill-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?=$this->lang->line('bill_name')?></th>
                                        <th><?=$this->lang->line('bill_discount')?>(%)</th>
                                        <th><?=$this->lang->line('bill_amount')?></th>
                                        <th><?=$this->lang->line('bill_subtotal')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(inicompute($billitems)) { $i = 0; $totalmainamount = 0; $totalsubtotal = 0;
                                    foreach($billitems as $billitem) { $i++; ?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=isset($billlabels[$billitem->billlabelID]) ? $billlabels[$billitem->billlabelID]->name : ''?></td>
                                            <td><?=$billitem->discount?></td>
                                            <td><?php echo number_format($billitem->amount, 2); $totalmainamount += $billitem->amount; ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $discount = 0;
                                                    if($billitem->discount > 0) {
                                                        $discount = (($billitem->amount / 100) * $billitem->discount);
                                                    }
                                                    $subtotal = ($billitem->amount - $discount);
                                                    echo number_format($subtotal, 2);
                                                    $totalsubtotal += $subtotal;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                <tr>
                                    <td colspan="4"><?=$this->lang->line('bill_total')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                    <td class="font-weight-bold"><?=number_format($totalsubtotal,2)?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bill-created-by">
                            <?=$this->lang->line('bill_created_by')?> : <?=inicompute($userName) ? $userName->name : ''?> <br/>
                            <?=$this->lang->line('bill_date')?> : <?=app_date($bill->date)?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>