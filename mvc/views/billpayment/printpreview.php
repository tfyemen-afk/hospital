<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="receipt-feature-header-title text-center">
                    <?=$this->lang->line('billpayment_receipt')?>
                </div>
                <div class="receipt-feature-title">
                    <div class="pull-left">
                        <b><?=$this->lang->line('billpayment_receipt_no')?></b> : <?=$billpayment->billpaymentID?>
                    </div>
                    <div class="pull-right">
                        <b><?=$this->lang->line('billpayment_date')?></b> : <?=date('d/m/Y')?>
                    </div>
                </div>

                <div class="receipt-body">
                    <table>
                        <tr>
                            <td width="33.33%"><?=$this->lang->line('billpayment_uhid')?> : <?=$patient->patientID?></td>
                            <td width="33.33%"><?=$this->lang->line('billpayment_name')?> : <?=$patient->name?></td>
                            <td width="33.33%"><?=$this->lang->line('billpayment_amount')?> : <?=$billpayment->paymentamount?> <?=($generalsettings->currency_code != '') ? $generalsettings->currency_code : '' ?></td>
                        </tr>

                        <tr>
                            <td width="33.33%"><?=$this->lang->line('billpayment_method')?> : <?=isset($paymentmethods[$billpayment->paymentmethod]) ? $paymentmethods[$billpayment->paymentmethod] : ''?></td>
                            <td width="66.66%" colspan="2"><?=$this->lang->line('billpayment_payment_date')?> : <?=app_datetime($billpayment->paymentdate)?></td>
                        </tr>
                    </table>
                </div>

                <div class="receipt-signature-div">
                    <div class="receipt-signature pull-right"><?=$this->lang->line('billpayment_signature')?></div>
                </div>
            </div>
        </div>
    </body>
</html>