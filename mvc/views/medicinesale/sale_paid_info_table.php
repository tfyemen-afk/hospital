<?php    
    $paymentmethodArray['1']  = $this->lang->line('medicinesale_cash');
    $paymentmethodArray['2']  = $this->lang->line('medicinesale_cheque');
    $paymentmethodArray['3']  = $this->lang->line('medicinesale_credit_card');
    $paymentmethodArray['4']  = $this->lang->line('medicinesale_other');
?>

<table class="table table-striped table-bordered table-hover example">
    <thead>
        <tr>
            <th><?=$this->lang->line('medicinesale_slno')?></th>
            <th><?=$this->lang->line('medicinesale_date')?></th>
            <th><?=$this->lang->line('medicinesale_reference_no')?></th>
            <th><?=$this->lang->line('medicinesale_file')?></th>
            <th><?=$this->lang->line('medicinesale_payment_method')?></th>
            <th><?=$this->lang->line('medicinesale_amount')?></th>
            <?php if(permissionChecker('medicinesale_delete') && $medicinesale->medicinesalerefund == 0) { ?>
                <th><?=$this->lang->line('medicinesale_action')?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; if(inicompute($medicinesalepaids)) { foreach($medicinesalepaids as $medicinesalepaid) { $i++; ?>
            <tr>
                <td data-title="<?=$this->lang->line('medicinesale_slno')?>"><?=$i?></td>
                <td data-title="<?=$this->lang->line('medicinesale_date')?>"><?=app_date($medicinesalepaid->medicinesalepaiddate);?></td>
                <td data-title="<?=$this->lang->line('medicinesale_reference_no')?>"><?=$medicinesalepaid->medicinesalepaidreferenceno?></td>
                <td data-title="<?=$this->lang->line('medicinesale_file')?>">
                    <?php
                        if(permissionChecker('medicinesale_view')) {
                            if($medicinesalepaid->medicinesalepaidfile != '') {
                                echo btn_download(site_url('medicinesale/paymentdownload/'.$medicinesalepaid->medicinesalepaidID), $this->lang->line('medicinesale_download'));
                            }
                        }
                    ?>
                </td>
                <td data-title="<?=$this->lang->line('medicinesale_payment_method')?>"><?=isset($paymentmethodArray[$medicinesalepaid->medicinesalepaidpaymentmethod]) ? $paymentmethodArray[$medicinesalepaid->medicinesalepaidpaymentmethod] : ''?></td>
                <td data-title="<?=$this->lang->line('medicinesale_amount')?>"><?=number_format($medicinesalepaid->medicinesalepaidamount, 2);?></td>
                <?php if(permissionChecker('medicinesale_delete') && $medicinesale->medicinesalerefund == 0) { ?>
                    <td data-title="<?=$this->lang->line('medicinesale_action')?>">
                        <a href="<?=site_url('medicinesale/paymentdelete/'.$medicinesalepaid->medicinesalepaidID)?>" onclick="return confirm('you are about to delete a record. This cannot be undone. are you sure?')" class="btn btn-danger btn-custom mrg" data-placement="top" data-toggle="tooltip" title="<?=$this->lang->line('medicinesale_delete')?>"><i class="fa fa-trash-o"></i></a>
                    </td>
                <?php } ?>
            </tr>
        <?php } } ?>
    </tbody>
</table>