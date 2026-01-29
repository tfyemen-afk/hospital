<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=pdfimagelink($patient->photo,'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('billpayment_name')?></div>
                            <div class="single-user-info-value">: <?=$patient->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('billpayment_uhid')?></div>
                            <div class="single-user-info-value">: <?=$patient->patientID?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('billpayment_designation')?></div>
                            <div class="single-user-info-value">: <?=inicompute($designation) ? $designation->designation : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('billpayment_type')?></div>
                            <div class="single-user-info-value">: <?=($patient->patienttypeID == 0) ? $this->lang->line('billpayment_opd') : $this->lang->line('billpayment_ipd') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('billpayment_phone')?></div>
                            <div class="single-user-info-value">: <?=$patient->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <?php if($printpreviewID == 1) { ?>
                            <tr>
                                <td><?=$this->lang->line('billpayment_billno')?></td>
                                <td><?=$this->lang->line('billpayment_date')?></td>
                                <td><?=$this->lang->line('billpayment_totalamount')?></td>
                            </tr>
                            <?php $totalBillAmount = 0; 
                            if(inicompute($bills)) { $i = 0;
                            foreach($bills as $bill) { $i++; ?>
                                <tr>
                                    <td><?=$i?></td>
                                    <td><?=app_datetime($bill->create_date)?></td>
                                    <td><?=app_currency_format($bill->totalamount)?></td>
                                    <?php $totalBillAmount += $bill->totalamount; ?>
                                </tr>
                            <?php } } ?>
                            <tr>
                                <td colspan="2"><?=$this->lang->line('billpayment_total')?></td>
                                <td><?=number_format($totalBillAmount, 2)?></td>
                            </tr>
                        <?php } elseif($printpreviewID == 2) { ?>
                            <tr>
                                <td><?=$this->lang->line('billpayment_slno')?></td>
                                <td><?=$this->lang->line('billpayment_date')?></td>
                                <td><?=$this->lang->line('billpayment_name')?></td>
                                <td><?=$this->lang->line('billpayment_discount')?>(%)</td>
                                <td><?=$this->lang->line('billpayment_amount')?></td>
                                <td><?=$this->lang->line('billpayment_subtotal')?></td>
                            </tr>
                            <?php $totalMainBillAmount = 0; $totalBillSubTotal = 0; if(inicompute($billitems)) { $i = 0;
                                foreach($billitems as $billitem) { $i++; ?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=app_datetime($billitem->create_date)?></td>
                                        <td><?=isset($billlabels[$billitem->billlabelID]) ? $billlabels[$billitem->billlabelID] : ''?></td>
                                        <td><?=app_currency_format($billitem->discount)?></td>
                                        <td>
                                            <?php
                                                $mainAmount = $billitem->amount;
                                                echo app_currency_format($mainAmount);
                                                $totalMainBillAmount +=$mainAmount;
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                $subTotal = ($billitem->amount - (($billitem->amount/100) * $billitem->discount));
                                                echo app_currency_format($subTotal);
                                                $totalBillSubTotal += $subTotal;
                                            ?>
                                        </td>
                                    </tr>
                                <?php } } ?>
                            <tr>
                                <td colspan="4" style="font-weight: bold"><?=$this->lang->line('billpayment_total')?></td>
                                <td style="font-weight: bold"><?=number_format($totalMainBillAmount,2)?></td>
                                <td style="font-weight: bold"><?=number_format($totalBillSubTotal,2)?></td>
                            </tr>
                        <?php } elseif($printpreviewID ==3 ) { ?>
                                <tr>
                                    <td><?=$this->lang->line('billpayment_slno')?></td>
                                    <td><?=$this->lang->line('billpayment_date')?></td>
                                    <td><?=$this->lang->line('billpayment_method')?></td>
                                    <td><?=$this->lang->line('billpayment_amount')?></td>
                                </tr>
                                <?php  $totalPaymentAmount = 0; if(inicompute($billpayments)) { $i = 0;
                                foreach($billpayments as $billpayment) { $i++; ?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=app_datetime($billpayment->create_date)?></td>
                                        <td><?=isset($paymentmethods[$billpayment->paymentmethod]) ? $paymentmethods[$billpayment->paymentmethod] : ''?></td>
                                        <td>
                                            <?php
                                                $totalPaymentAmount += $billpayment->paymentamount;
                                                echo app_currency_format($billpayment->paymentamount);
                                            ?>
                                        </td>
                                    </tr>
                                <?php } } ?>
                                <tr>
                                    <td colspan="3" style="font-weight: bold"><?=$this->lang->line('billpayment_total')?></td>
                                    <td style="font-weight: bold"><?=number_format($totalPaymentAmount, 2)?></td>
                                </tr>    
                        <?php } elseif($printpreviewID == 4) { 
                            $totalPaymentAmount = 0; 
                            if(inicompute($billpayments)) { foreach($billpayments as $billpayment) {
                                $totalPaymentAmount += $billpayment->paymentamount;
                            } } 

                            $totalBillSubTotal = 0; if(inicompute($billitems)) {
                            foreach($billitems as $billitem) {
                                $subTotal = ($billitem->amount - (($billitem->amount/100) * $billitem->discount));
                                $totalBillSubTotal += $subTotal;
                            } }
                            ?>
                                <tr>
                                    <td style="font-weight: bold"><?=$this->lang->line('billpayment_total_bill')?></td>
                                    <td><?=number_format($totalBillSubTotal, 2)?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold"><?=$this->lang->line('billpayment_total_payment')?></td>
                                    <td><?=number_format($totalPaymentAmount, 2)?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold"><?=$this->lang->line('billpayment_total_due')?></td>
                                    <td style="font-weight: bold"><?=number_format(($totalBillSubTotal - $totalPaymentAmount), 2)?></td>
                                </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>