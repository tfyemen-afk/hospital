<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="single-user-info-item">
                        <div class="single-user-info-label"><?=$this->lang->line('medicinesale_patient_type')?></div>
                        <div class="single-user-info-value">:
                            <?php if($medicinesale->patient_type == 0) {
                                echo $this->lang->line('medicinesale_opd');
                            } elseif($medicinesale->patient_type == 1) {
                                echo $this->lang->line('medicinesale_ipd');
                            } else {
                                echo $this->lang->line('medicinesale_none');
                            } ?>
                        </div>
                    </div>
                    <div class="single-user-info-item">
                        <div class="single-user-info-label"><?=$this->lang->line('medicinesale_uhid')?></div>
                        <div class="single-user-info-value">: <?=($medicinesale->uhid !=0) ? $medicinesale->uhid : ''?></div>
                    </div>
                    <div class="single-user-info-item">
                        <div class="single-user-info-label"><?=$this->lang->line('medicinesale_sale_date')?></div>
                        <div class="single-user-info-value">: <?=app_date($medicinesale->medicinesaledate)?></div>
                    </div>
                    <div class="single-user-info-item">
                        <div class="single-user-info-label"><?=$this->lang->line('medicinesale_description')?></div>
                        <div class="single-user-info-value">: <?=$medicinesale->medicinesaledescription?></div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('medicinesale_slno')?></th>
                                <th><?=$this->lang->line('medicinesale_name')?></th>
                                <th><?=$this->lang->line('medicinesale_batchID')?></th>
                                <th><?=$this->lang->line('medicinesale_expire_date')?></th>
                                <th><?=$this->lang->line('medicinesale_unit_price')?></th>
                                <th><?=$this->lang->line('medicinesale_quantity')?></th>
                                <th><?=$this->lang->line('medicinesale_subtotal')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0; $total_paid_amount = 0; $total_main_amount = 0; $total_balance_amount= 0;
                            if(inicompute($medicinesaleitems)) {
                             foreach($medicinesaleitems as $medicinesaleitem) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('medicinesale_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('medicinesale_name')?>"><?=isset($medicines[$medicinesaleitem->medicineID]) ? $medicines[$medicinesaleitem->medicineID]->name : ''?></td>
                                    <td data-title="<?=$this->lang->line('medicinesale_batchID')?>"><?=$medicinesaleitem->medicinebatchID?></td>
                                    <td data-title="<?=$this->lang->line('medicinesale_expire_date')?>"><?=app_date($medicinesaleitem->medicineexpiredate)?></td>
                                    <td data-title="<?=$this->lang->line('medicinesale_unit_price')?>"><?=$medicinesaleitem->medicinesaleunitprice?></td>
                                    <td data-title="<?=$this->lang->line('medicinesale_quantity')?>"><?=$medicinesaleitem->medicinesalequantity?></td>
                                    <td data-title="<?=$this->lang->line('medicinesale_subtotal')?>">
                                        <?php
                                            echo number_format($medicinesaleitem->medicinesalesubtotal, 2);
                                            $total_main_amount   += $medicinesaleitem->medicinesalesubtotal;
                                            $total_paid_amount    = ($medicinesalepaid->medicinesalepaidamount) ? $medicinesalepaid->medicinesalepaidamount : 0;
                                            $total_balance_amount = $total_main_amount - $total_paid_amount;
                                        ?>
                                    </td>
                                </tr>
                            <?php } } ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('medicinesale_total_amount')?>" colspan="6" class="footerLabel"><?=$this->lang->line('medicinesale_total_amount')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                <td data-title="<?=$this->lang->line('medicinesale_subtotal')?>" style="font-weight: bold"><?=number_format($total_main_amount,2)?></td>
                            </tr>
                            <tr>
                                <td data-title="<?=$this->lang->line('medicinesale_paid')?>" colspan="6" class="footerLabel"><?=$this->lang->line('medicinesale_paid')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                <td data-title="<?=$this->lang->line('medicinesale_subtotal')?>" style="font-weight: bold"><?=number_format($total_paid_amount,2)?></td>
                            </tr>
                            <tr>
                                <td data-title="<?=$this->lang->line('medicinesale_balance')?>" colspan="6" class="footerLabel"><?=$this->lang->line('medicinesale_balance')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                <td data-title="<?=$this->lang->line('medicinesale_subtotal')?>" style="font-weight: bold"><?=number_format($total_balance_amount,2)?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="view-bottom-created-area">    
                        <div class="view-bottom-created-area-created-by float-left">
                            <?=$this->lang->line('medicinesale_payment_status')?> : <span class="text-success font-weight-bold">
                                <?php if($medicinesale->medicinesalestatus == 1) {
                                    echo $this->lang->line('medicinesale_partial_paid');
                                } elseif ($medicinesale->medicinesalestatus == 2) {
                                    echo $this->lang->line('medicinesale_fully_paid');
                                } else {
                                    echo $this->lang->line('medicinesale_pending');
                                } ?>
                            </span>
                        </div>
                        <div class="view-bottom-created-area-created-by float-right">
                            <?=$this->lang->line('medicinesale_created_by')?> : <?=inicompute($userName) ? $userName->name : ''?> <br/>
                            <?=$this->lang->line('medicinesale_date')?> : <?=app_date($medicinesale->medicinesaledate)?>
                        </div>
                    </div>
                </div>
                <?php if($medicinesale->medicinesalerefund == 1) { ?>
                    <div class="view-bottom-created-area-refund-by">
                        <?=$this->lang->line('medicinesale_refund')?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </body>
</html>