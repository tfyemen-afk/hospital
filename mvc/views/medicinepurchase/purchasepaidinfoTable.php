<?php    
    $paymentmethodArray['1']  = $this->lang->line('medicinepurchase_cash');
    $paymentmethodArray['2']  = $this->lang->line('medicinepurchase_cheque');
    $paymentmethodArray['3']  = $this->lang->line('medicinepurchase_credit_card');
    $paymentmethodArray['4']  = $this->lang->line('medicinepurchase_other');
?>

<table class="table table-striped table-bordered table-hover example">
    <thead>
        <tr>
            <th><?=$this->lang->line('medicinepurchase_slno')?></th>
            <th><?=$this->lang->line('medicinepurchase_date')?></th>
            <th><?=$this->lang->line('medicinepurchase_reference_no')?></th>
            <th><?=$this->lang->line('medicinepurchase_file')?></th>
            <th><?=$this->lang->line('medicinepurchase_payment_method')?></th>
            <th><?=$this->lang->line('medicinepurchase_amount')?></th>
            <?php if(permissionChecker('medicinepurchase_delete') && ($medicinepurchase->medicinepurchaserefund == 0)) { ?>
                <th><?=$this->lang->line('medicinepurchase_action')?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; if(inicompute($medicinepurchasepaids)) { foreach($medicinepurchasepaids as $medicinepurchasepaid) { $i++; ?>
            <tr>
                <td data-title="<?=$this->lang->line('medicinepurchase_slno')?>"><?=$i?></td>
                <td data-title="<?=$this->lang->line('medicinepurchase_date')?>"><?=app_date($medicinepurchasepaid->medicinepurchasepaiddate);?></td>
                <td data-title="<?=$this->lang->line('medicinepurchase_reference_no')?>"><?=$medicinepurchasepaid->medicinepurchasepaidreferenceno?></td>
                <td data-title="<?=$this->lang->line('medicinepurchase_file')?>">
                    <?php
                        if(permissionChecker('medicinepurchase_view')) {
                            if($medicinepurchasepaid->medicinepurchasepaidfile != '') {
                                echo btn_download(site_url('medicinepurchase/paymentdownload/'.$medicinepurchasepaid->medicinepurchasepaidID), $this->lang->line('medicinepurchase_download'));
                            }
                        }
                    ?>
                </td>
                <td data-title="<?=$this->lang->line('medicinepurchase_payment_method')?>"><?=isset($paymentmethodArray[$medicinepurchasepaid->medicinepurchasepaidpaymentmethod]) ? $paymentmethodArray[$medicinepurchasepaid->medicinepurchasepaidpaymentmethod] : ''?></td>
                <td data-title="<?=$this->lang->line('medicinepurchase_amount')?>"><?=number_format($medicinepurchasepaid->medicinepurchasepaidamount, 2);?></td>
                <?php if(permissionChecker('medicinepurchase_delete') && ($medicinepurchase->medicinepurchaserefund == 0)) { ?>
                    <td data-title="<?=$this->lang->line('medicinepurchase_action')?>">
                        <a href="<?=site_url('medicinepurchase/paymentdelete/'.$medicinepurchasepaid->medicinepurchasepaidID)?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" class="btn btn-danger btn-custom mrg" data-placement="top" data-toggle="tooltip" title="<?=$this->lang->line('medicinepurchase_delete')?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                <?php } ?>
            </tr>
        <?php } } ?>
    </tbody>
</table>