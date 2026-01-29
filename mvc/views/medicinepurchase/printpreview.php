<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('medicinepurchase_warehouse')?></div>
                            <div class="single-user-info-value">: <?=isset($medicinewarehouses[$medicinepurchase->medicinewarehouseID]) ? $medicinewarehouses[$medicinepurchase->medicinewarehouseID]->name : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('medicinepurchase_reference_no')?></div>
                            <div class="single-user-info-value">: <?=$medicinepurchase->medicinepurchasereferenceno?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('medicinepurchase_purchase_date')?></div>
                            <div class="single-user-info-value">: <?=app_date($medicinepurchase->medicinepurchasedate)?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('medicinepurchase_description')?></div>
                            <div class="single-user-info-value">: <?=$medicinepurchase->medicinepurchasedescription?></div>
                        </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('medicinepurchase_slno')?></th>
                                <th><?=$this->lang->line('medicinepurchase_name')?></th>
                                <th><?=$this->lang->line('medicinepurchase_batchID')?></th>
                                <th><?=$this->lang->line('medicinepurchase_expire_date')?></th>
                                <th><?=$this->lang->line('medicinepurchase_unit_price')?></th>
                                <th><?=$this->lang->line('medicinepurchase_quantity')?></th>
                                <th><?=$this->lang->line('medicinepurchase_subtotal')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0; $totalPaidAmount = 0; $totalMainAmount = 0; $totalBalanceAmount= 0;
                            if(inicompute($medicinepurchaseItems)) {
                             foreach($medicinepurchaseItems as $medicinepurchaseItem) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('medicinepurchase_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('medicinepurchase_name')?>"><?=isset($medicines[$medicinepurchaseItem->medicineID]) ? $medicines[$medicinepurchaseItem->medicineID]->name : ''?></td>
                                    <td data-title="<?=$this->lang->line('medicinepurchase_batchID')?>"><?=$medicinepurchaseItem->batchID?></td>
                                    <td data-title="<?=$this->lang->line('medicinepurchase_expire_date')?>"><?=app_date($medicinepurchaseItem->expire_date)?></td>
                                    <td data-title="<?=$this->lang->line('medicinepurchase_unit_price')?>"><?=$medicinepurchaseItem->unit_price?></td>
                                    <td data-title="<?=$this->lang->line('medicinepurchase_quantity')?>"><?=$medicinepurchaseItem->quantity?></td>
                                    <td data-title="<?=$this->lang->line('medicinepurchase_subtotal')?>">
                                        <?php
                                            echo number_format($medicinepurchaseItem->subtotal, 2);
                                            $totalMainAmount  += $medicinepurchaseItem->subtotal;
                                            $totalPaidAmount  = ($medicinepurchasepaid->medicinepurchasepaidamount) ? $medicinepurchasepaid->medicinepurchasepaidamount : 0;
                                            $totalBalanceAmount = $totalMainAmount - $totalPaidAmount;
                                        ?>
                                    </td>
                                </tr>
                            <?php } } ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('medicinepurchase_total_amount')?>" colspan="6" class="footer-text"><?=$this->lang->line('medicinepurchase_total_amount')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                <td data-title="<?=$this->lang->line('medicinepurchase_subtotal')?>" style="font-weight: bold"><?=number_format($totalMainAmount,2)?></td>
                            </tr>
                            <tr>
                                <td data-title="<?=$this->lang->line('medicinepurchase_paid')?>" colspan="6" class="footer-text"><?=$this->lang->line('medicinepurchase_paid')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                <td data-title="<?=$this->lang->line('medicinepurchase_subtotal')?>" style="font-weight: bold"><?=number_format($totalPaidAmount,2)?></td>
                            </tr>
                            <tr>
                                <td data-title="<?=$this->lang->line('medicinepurchase_balance')?>" colspan="6" class="footer-text"><?=$this->lang->line('medicinepurchase_balance')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                <td data-title="<?=$this->lang->line('medicinepurchase_subtotal')?>" style="font-weight: bold"><?=number_format($totalBalanceAmount,2)?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="view-bottom-created-area">    
                        <div class="view-bottom-created-area-created-by float-left">
                            <?=$this->lang->line('medicinepurchase_payment_status')?> : <span class="text-success font-weight-bold">
                                <?php if($medicinepurchase->medicinepurchasestatus == 1) {
                                    echo $this->lang->line('medicinepurchase_partial_paid');
                                } elseif ($medicinepurchase->medicinepurchasestatus == 2) {
                                    echo $this->lang->line('medicinepurchase_fully_paid');
                                } else {
                                    echo $this->lang->line('medicinepurchase_pending');
                                } ?>
                            </span>
                        </div>
                        <div class="view-bottom-created-area-created-by float-right">
                            <?=$this->lang->line('medicinepurchase_created_by')?> : <?=inicompute($userName) ? $userName->name : ''?> <br/>
                            <?=$this->lang->line('medicinepurchase_date')?> : <?=app_date($medicinepurchase->medicinepurchasedate)?>
                        </div>
                    </div>
                </div>
                <?php if($medicinepurchase->medicinepurchaserefund == 1) { ?>
                    <div class="view-bottom-created-area-refund-by" style="transform:rotate(-300deg)">
                        <?=$this->lang->line('medicinepurchase_refund')?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>