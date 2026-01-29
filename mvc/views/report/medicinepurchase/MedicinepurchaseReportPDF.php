<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('medicinepurchasereport_report_for')?> - <?=$this->lang->line('medicinepurchasereport_medicine_purchase')?></h3>
        </div>
        <div>
            <?php if($from_date && $to_date ) { ?>
                <h6 class="pull-left report-pulllabel">
                    <?=$this->lang->line('medicinepurchasereport_from_date')?> : <?=date('d M Y',$from_date)?>
                </h6>
                <h6 class="pull-right report-pulllabel">
                    <?=$this->lang->line('medicinepurchasereport_to_date')?> : <?=date('d M Y',$to_date)?>
                </h6>
            <?php } elseif($statusID != 0 ) { ?>
                <h6 class="pull-left report-pulllabel">
                    <?php
                        echo $this->lang->line('medicinepurchasereport_status')." : ";
                        if($statusID == 1) {
                            echo $this->lang->line("medicinepurchasereport_pending");
                        } elseif($statusID == 2) {
                            echo $this->lang->line("medicinepurchasereport_partial");
                        } elseif($statusID == 3) {
                            echo $this->lang->line("medicinepurchasereport_fully_paid");
                        } elseif($statusID == 4) {
                            echo $this->lang->line("medicinepurchasereport_refund");
                        }
                    ?>
                </h6>
            <?php } elseif($reference_no != '0') { ?>
                <h6 class="pull-left report-pulllabel">
                    <?=$this->lang->line('medicinepurchasereport_reference_no')." : ".$reference_no?>
                </h6>
            <?php } elseif($medicinewarehouseID != 0) { ?>
                <h6 class="pull-left report-pulllabel">
                    <?php
                        echo $this->lang->line('medicinepurchasereport_warehouse')." : ";
                        echo isset($medicinewarehouses[$medicinewarehouseID]) ? $medicinewarehouses[$medicinewarehouseID] : '';
                    ?>
                </h6>
            <?php } elseif($from_date) { ?>
                <h6 class="pull-left report-pulllabel">
                    <?=$this->lang->line('medicinepurchasereport_from_date')?> : <?=date('d M Y',$from_date)?>
                </h6>
            <?php } ?>
        </div>
        <div>
            <?php if(inicompute($medicinepurchases)) { ?>
                <table>
                    <thead>
                        <tr>
                            <th><?=$this->lang->line('medicinepurchasereport_slno')?></th>
                            <th><?=$this->lang->line('medicinepurchasereport_reference_no')?></th>
                            <th><?=$this->lang->line('medicinepurchasereport_warehouse')?></th>
                            <th><?=$this->lang->line('medicinepurchasereport_date')?></th>
                            <th><?=$this->lang->line('medicinepurchasereport_total')?></th>
                            <th><?=$this->lang->line('medicinepurchasereport_paid')?></th>
                            <th><?=$this->lang->line('medicinepurchasereport_balance')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalmedicinepurchaseprice         = 0;
                        $totalmedicinepurchasepaidamount    = 0;
                        $totalmedicinepurchasebalanceamount = 0;
                        $i=0; 
                        foreach($medicinepurchases as $medicinepurchase) { $i++; ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$medicinepurchase->medicinepurchasereferenceno?></td>
                            <td><?=isset($medicinewarehouses[$medicinepurchase->medicinewarehouseID]) ? $medicinewarehouses[$medicinepurchase->medicinewarehouseID] : ''?></td>
                            <td><?=app_date($medicinepurchase->medicinepurchasedate)?></td>
                            <td><?=number_format($medicinepurchase->totalamount, 2)?></td>
                            <td>
                                <?php 
                                    $paidamount = isset($medicinepurchasepaids[$medicinepurchase->medicinepurchaseID]) ? $medicinepurchasepaids[$medicinepurchase->medicinepurchaseID] : 0;
                                    echo number_format($paidamount, 2);
                                ?>    
                            </td>
                            <td>
                                <?php
                                    $balanceamount = $medicinepurchase->totalamount-$paidamount;
                                    echo number_format($balanceamount, 2);

                                    $totalmedicinepurchaseprice         += $medicinepurchase->totalamount;
                                    $totalmedicinepurchasepaidamount    += $paidamount;
                                    $totalmedicinepurchasebalanceamount += $balanceamount;
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="4" class="font-weight-bold">
                                <?=$this->lang->line('medicinepurchasereport_grand_total')?> 
                                <?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?>        
                            </td>
                            <td class="font-weight-bold">
                                <?=number_format($totalmedicinepurchaseprice,2)?>        
                            </td>
                            <td class="font-weight-bold">
                                <?=number_format($totalmedicinepurchasepaidamount,2)?>        
                            </td>
                            <td class="font-weight-bold">
                                <?=number_format($totalmedicinepurchasebalanceamount,2)?>        
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('medicinepurchasereport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>