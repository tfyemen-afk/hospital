<div class="btn-group pull-right">
<?php if(isset($medicinepurchaseID) && (int)$medicinepurchaseID) {?>
    <a href="<?=site_url('medicinepurchase/edit/'.$medicinepurchaseID.'/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('medicinepurchase_today')?></a>
    <a href="<?=site_url('medicinepurchase/edit/'.$medicinepurchaseID.'/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('medicinepurchase_month')?></a>
    <a href="<?=site_url('medicinepurchase/edit/'.$medicinepurchaseID.'/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('medicinepurchase_year')?></a>
    <a href="<?=site_url('medicinepurchase/edit/'.$medicinepurchaseID.'/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('medicinepurchase_all')?></a>
<?php } else { ?>
    <a href="<?=site_url('medicinepurchase/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('medicinepurchase_today')?></a>
    <a href="<?=site_url('medicinepurchase/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('medicinepurchase_month')?></a>
    <a href="<?=site_url('medicinepurchase/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('medicinepurchase_year')?></a>
    <a href="<?=site_url('medicinepurchase/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('medicinepurchase_all')?></a>
<?php } ?>
</div>
<br>
<br>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('medicinepurchase_slno')?></th>
                <th><?=$this->lang->line('medicinepurchase_reference_no')?></th>
                <th><?=$this->lang->line('medicinepurchase_warehouse')?></th>
                <th><?=$this->lang->line('medicinepurchase_purchase_date')?></th>
                <th><?=$this->lang->line('medicinepurchase_file')?></th>
                <th><?=$this->lang->line('medicinepurchase_grand_total')?></th>
                <th><?=$this->lang->line('medicinepurchase_paid')?></th>
                <th><?=$this->lang->line('medicinepurchase_balance')?></th>
                <?php if(permissionChecker('medicinepurchase_view') || permissionChecker('medicinepurchase_edit') || permissionChecker('medicinepurchase_delete')) { ?>
                    <th><?=$this->lang->line('medicinepurchase_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($medicinepurchases)) { foreach($medicinepurchases as $medicinepurchase) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('medicinepurchase_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('medicinepurchase_reference_no')?>"><?=$medicinepurchase->medicinepurchasereferenceno;?></td>
                    <td data-title="<?=$this->lang->line('medicinepurchase_warehouse')?>">
                        <?=isset($medicinewarehouses[$medicinepurchase->medicinewarehouseID]) ? $medicinewarehouses[$medicinepurchase->medicinewarehouseID]->name : ''?>
                        <?=($medicinepurchase->medicinepurchaserefund) ? '<span class="text-danger">('. $this->lang->line('medicinepurchase_refund') .')</span>' : ''?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinepurchase_purchase_date')?>"><?=app_date($medicinepurchase->medicinepurchasedate);?></td>
                    <td data-title="<?=$this->lang->line('medicinepurchase_file')?>">
                        <?php
                            if(permissionChecker('medicinepurchase_view')) {
                                if($medicinepurchase->medicinepurchasefile != '') {
                                    echo btn_download_file_only(site_url('medicinepurchase/download/'.$medicinepurchase->medicinepurchaseID), namesorting($medicinepurchase->medicinepurchasefileoriginalname, 10), $this->lang->line('medicinepurchase_download'));
                                }
                            }
                        ?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinepurchase_grand_total')?>"><?=number_format($medicinepurchase->totalamount, 2);?></td>
                    <td data-title="<?=$this->lang->line('medicinepurchase_paid')?>">
                        <?php 
                            $medicine_paid_amount = isset($medicinepurchasepaids[$medicinepurchase->medicinepurchaseID]) ? $medicinepurchasepaids[$medicinepurchase->medicinepurchaseID] : 0;
                            $balance_amount = ($medicinepurchase->totalamount - $medicine_paid_amount);
                            echo number_format($medicine_paid_amount, 2);
                        ?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinepurchase_balance')?>"><?=number_format($balance_amount, 2);?></td>
                    <?php if(permissionChecker('medicinepurchase_view') || permissionChecker('medicinepurchase_edit') || permissionChecker('medicinepurchase_delete')) { ?>
                        <td data-title="<?=$this->lang->line('medicinepurchase_action')?>">
                            <?=btn_view('medicinepurchase/view/'.$medicinepurchase->medicinepurchaseID.'/'.$displayID, $this->lang->line('medicinepurchase_view'))?>

                            <?php
                                if(($medicinepurchase->medicinepurchasestatus == 0) && ($medicinepurchase->medicinepurchaserefund == 0)) {
                                    echo btn_edit('medicinepurchase/edit/'.$medicinepurchase->medicinepurchaseID.'/'.$displayID, $this->lang->line('medicinepurchase_edit')). ' ';
                                    echo btn_delete('medicinepurchase/delete/'.$medicinepurchase->medicinepurchaseID.'/'.$displayID, $this->lang->line('medicinepurchase_delete'));
                                } else {
                                    if(permissionChecker('medicinepurchase_edit') && permissionChecker('medicinepurchase_delete') && ($medicinepurchase->medicinepurchaserefund == 0)) {
                                        echo btn_cancel('medicinepurchase/cancel/'.$medicinepurchase->medicinepurchaseID.'/'.$displayID, $this->lang->line('medicinepurchase_cancel'));
                                    }
                                }
                            ?>

                            <?php if(permissionChecker('medicinepurchase_add')) { if(($medicinepurchase->medicinepurchasestatus != 2) && ($medicinepurchase->medicinepurchaserefund == 0)) { ?>
                                <button id="<?=$medicinepurchase->medicinepurchaseID?>" href="#addpayment" class="btn btn-primary btn-custom mrg getPurchaseInfo" data-toggle="modal"><i class="fa fa-credit-card" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('medicinepurchase_add_payment')?>"></i></button>
                            <?php } } ?>
                            <?php if(permissionChecker('medicinepurchase_view')) { ?>
                                <button id="<?=$medicinepurchase->medicinepurchaseID?>" href="#viewayment" class="btn btn-info btn-custom mrg getPurchasePaidInfo" data-toggle="modal"><i class="fa fa-list-ul" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('medicinepurchase_view_payments')?>"></i></button>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<div class="modal" id="addpayment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title">
                    <?= $this->lang->line('medicinepurchase_add_payment') ?>
                </h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST" enctype="multipart/form-data" id="medicinePurchasePaymentData">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group <?= form_error('payment_date') ? 'text-danger' : '' ?>">
                                <label><?= $this->lang->line('medicinepurchase_date') ?>
                                    <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control datepicker <?= form_error('payment_date') ? 'is-invalid' : '' ?>" id="payment_date" name="payment_date" value="<?= set_value('payment_date') ?>">
                                <span class="text-danger" id="error_date"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?= form_error('reference_no') ? 'text-danger' : '' ?>">
                                <label><?= $this->lang->line('medicinepurchase_reference_no') ?></label>
                                <input type="text" class="form-control <?= form_error('reference_no') ? 'is-invalid' : '' ?>" id="reference_no" name="reference_no" value="<?= set_value('reference_no') ?>">
                                <span class="text-danger" id="error_reference_no"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?= form_error('payment_amount') ? 'text-danger' : '' ?>">
                                <label><?= $this->lang->line('medicinepurchase_amount') ?>
                                    <span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?= form_error('payment_amount') ? 'is-invalid' : '' ?>" id="medicinepurchasepaidamount" name="payment_amount" value="<?= set_value('payment_amount') ?>">
                                <span class="text-danger" id="error_amount"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?= form_error('payment_method') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="payment_method">
                                    <?= $this->lang->line('medicinepurchase_payment_method') ?>
                                    <span class="text-danger"> *</span>
                                </label>
                                <?php
                                    $paymentmethodArray['0'] = '— ' . $this->lang->line('medicinepurchase_please_select') . ' —';
                                    $paymentmethodArray['1'] = $this->lang->line('medicinepurchase_cash');
                                    $paymentmethodArray['2'] = $this->lang->line('medicinepurchase_cheque');
                                    $paymentmethodArray['3'] = $this->lang->line('medicinepurchase_credit_card');
                                    $paymentmethodArray['4'] = $this->lang->line('medicinepurchase_other');

                                    $errorClass = form_error('payment_method') ? 'is-invalid' : '';
                                    echo form_dropdown('payment_method', $paymentmethodArray,
                                        set_value('payment_method'),
                                        ' id="payment_method" class="form-control select2 ' . $errorClass . '" ') ?>
                                <span><?= form_error('payment_method') ?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?= form_error('payment_file') ? 'text-danger' : '' ?>">
                                <label><?= $this->lang->line('medicinepurchase_file') ?></label>
                                <div class="custom-file">
                                    <input type="file" name="payment_file" class="custom-file-input file-upload-input file" id="file-upload">
                                    <label class="custom-file-label label-text-hide" for="file-upload"><?= $this->lang->line('medicinepurchase_choose_file') ?></label>
                                </div>
                                <span class="text-danger" id="error_file"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= $this->lang->line('medicinepurchase_close') ?></button>
                    <button type="button" class="btn btn-primary getPaymentInfo"><?= $this->lang->line('medicinepurchase_add_payment') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="viewayment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title">
                    <?=$this->lang->line('medicinepurchase_view_payments')?>
                </h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="viewaymentView">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('medicinepurchase_close')?></button>
            </div>
        </div>
    </div>
</div>