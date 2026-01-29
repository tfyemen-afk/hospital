<?php if($loginroleID != 3) { ?>
    <div class="btn-group pull-right">
    <?php if(isset($medicinesaleID) && (int)$medicinesaleID) {?>
        <a href="<?=site_url('medicinesale/edit/'.$medicinesaleID.'/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('medicinesale_today')?></a>
        <a href="<?=site_url('medicinesale/edit/'.$medicinesaleID.'/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('medicinesale_month')?></a>
        <a href="<?=site_url('medicinesale/edit/'.$medicinesaleID.'/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('medicinesale_year')?></a>
        <a href="<?=site_url('medicinesale/edit/'.$medicinesaleID.'/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('medicinesale_all')?></a>
    <?php } else { ?>
        <a href="<?=site_url('medicinesale/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('medicinesale_today')?></a>
        <a href="<?=site_url('medicinesale/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('medicinesale_month')?></a>
        <a href="<?=site_url('medicinesale/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('medicinesale_year')?></a>
        <a href="<?=site_url('medicinesale/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('medicinesale_all')?></a>
    <?php } ?>
    </div>
    <br>
    <br>
<?php } ?>

<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('medicinesale_slno')?></th>
                <th><?=$this->lang->line('medicinesale_patient_type')?></th>
                <th><?=$this->lang->line('medicinesale_uhid')?></th>
                <th><?=$this->lang->line('medicinesale_date')?></th>
                <th><?=$this->lang->line('medicinesale_file')?></th>
                <th><?=$this->lang->line('medicinesale_total_amount')?></th>
                <th><?=$this->lang->line('medicinesale_paid')?></th>
                <th><?=$this->lang->line('medicinesale_balance')?></th>
                <?php if(permissionChecker('medicinesale_view') || permissionChecker('medicinesale_edit') || permissionChecker('medicinesale_delete')) { ?>
                    <th><?=$this->lang->line('medicinesale_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($medicinesales)) { foreach($medicinesales as $medicinesale) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('medicinesale_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('medicinesale_patient_type')?>">
                        <?=isset($patient_types[$medicinesale->patient_type]) ? $patient_types[$medicinesale->patient_type] : ''?>
                        <?=($medicinesale->medicinesalerefund) ? '<span class="text-danger">('. $this->lang->line('medicinesale_refund') .')</span>' : ''?>
                        </td>
                    <td data-title="<?=$this->lang->line('medicinesale_uhid')?>"><?=($medicinesale->uhid !=0) ? $medicinesale->uhid : ''?></td>
                    <td data-title="<?=$this->lang->line('medicinesale_date')?>"><?=app_date($medicinesale->medicinesaledate);?></td>
                    <td data-title="<?=$this->lang->line('medicinesale_file')?>">
                        <?php
                            if(permissionChecker('medicinesale_view')) {
                                if($medicinesale->medicinesalefile != '') {
                                    echo btn_download_file_only(site_url('medicinesale/download/'.$medicinesale->medicinesaleID), namesorting($medicinesale->medicinesalefileoriginalname, 10), $this->lang->line('medicinesale_download'));
                                }
                            }
                        ?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinesale_total_amount')?>"><?=number_format($medicinesale->medicinesaletotalamount, 2);?></td>
                    <td data-title="<?=$this->lang->line('medicinesale_paid')?>">
                        <?php 
                            $medicinesalepaidamount    = isset($medicinesalepaids[$medicinesale->medicinesaleID]) ? $medicinesalepaids[$medicinesale->medicinesaleID] : 0;
                            echo number_format($medicinesalepaidamount, 2);
                            $medicinesalebalanceamount = $medicinesale->medicinesaletotalamount-$medicinesalepaidamount;
                        ?>
                    </td>
                    <td data-title="<?=$this->lang->line('medicinesale_balance')?>"><?=number_format($medicinesalebalanceamount, 2);?></td>
                    <?php if(permissionChecker('medicinesale_view') || permissionChecker('medicinesale_edit') || permissionChecker('medicinesale_delete')) { ?>
                        <td data-title="<?=$this->lang->line('medicinesale_action')?>">
                            <?=btn_view('medicinesale/view/'.$medicinesale->medicinesaleID.'/'.$displayID, $this->lang->line('medicinesale_view'))?>

                            <?php if(($medicinesale->medicinesalestatus == 0) && ($medicinesale->medicinesalerefund == 0)) {
                                echo btn_edit('medicinesale/edit/'.$medicinesale->medicinesaleID.'/'.$displayID, $this->lang->line('medicinesale_edit')).' ';
                                echo btn_delete('medicinesale/delete/'.$medicinesale->medicinesaleID.'/'.$displayID, $this->lang->line('medicinesale_delete'));
                            } else {
                                if(permissionChecker('medicinesale_edit') && permissionChecker('medicinesale_delete') && ($medicinesale->medicinesalerefund == 0)) {
                                    echo btn_cancel('medicinesale/cancel/'.$medicinesale->medicinesaleID.'/'.$displayID, $this->lang->line('medicinesale_cancel'));
                            } } ?>

                            <?php if(permissionChecker('medicinesale_add')) { if(($medicinesale->medicinesalestatus != 2) && ($medicinesale->medicinesalerefund == 0)) { ?>
                                <button id="<?=$medicinesale->medicinesaleID?>" href="#addpayment" class="btn btn-primary btn-custom mrg get_sale_info" data-toggle="modal"><i class="fa fa-credit-card" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('medicinesale_add_payment')?>"></i></button>
                            <?php } } ?>
                            <?php if(permissionChecker('medicinesale_view')) { ?>
                                <button id="<?=$medicinesale->medicinesaleID?>" href="#viewayment" class="btn btn-info btn-custom mrg get_sale_paid_info" data-toggle="modal"><i class="fa fa-list-ul" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('medicinesale_view_payments')?>"></i></button>
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
                    <?=$this->lang->line('medicinesale_add_payment')?>
                </h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" enctype="multipart/form-data" role="form" method="POST" id="medicinesalePaymentData">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group <?=form_error('payment_date') ? 'text-danger' : '' ?>">
                                <label><?=$this->lang->line('medicinesale_date')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control datepicker <?=form_error('payment_date') ? 'is-invalid' : '' ?>" id="payment_date" name="payment_date"  value="<?=set_value('payment_date')?>">
                                <span class="text-danger" id="error_date"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?=form_error('paidreference_no') ? 'text-danger' : '' ?>">
                                <label><?=$this->lang->line('medicinesale_reference_no')?></label>
                                <input type="text" class="form-control <?=form_error('paidreference_no') ? 'is-invalid' : '' ?>" id="paidreference_no" name="paidreference_no"  value="<?=set_value('paidreference_no')?>">
                                <span class="text-danger" id="error_paidreference_no"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?=form_error('payment_amount') ? 'text-danger' : '' ?>">
                                <label><?=$this->lang->line('medicinesale_amount')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('payment_amount') ? 'is-invalid' : '' ?>" id="medicinesalepaidamount" name="payment_amount"  value="<?=set_value('payment_amount')?>">
                                <span class="text-danger" id="error_amount"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?=form_error('paidpayment_method') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="paidpayment_method">
                                    <?=$this->lang->line('medicinesale_payment_method')?><span class="text-danger"> *</span>
                                </label>
                                    <?php
                                    $paymentmethodArray['0']  = '— '.$this->lang->line('medicinesale_please_select').' —';
                                    $paymentmethodArray['1']  = $this->lang->line('medicinesale_cash');
                                    $paymentmethodArray['2']  = $this->lang->line('medicinesale_cheque');
                                    $paymentmethodArray['3']  = $this->lang->line('medicinesale_credit_card');
                                    $paymentmethodArray['4']  = $this->lang->line('medicinesale_other');

                                    $errorClass = form_error('paidpayment_method') ? 'is-invalid' : '';
                                    echo form_dropdown('paidpayment_method', $paymentmethodArray,  set_value('paidpayment_method'), ' id="paidpayment_method" class="form-control select2 '.$errorClass.'" ')?>
                                <span><?=form_error('paidpayment_method')?></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group <?=form_error('payment_file') ? 'text-danger' : '' ?>">
                                <label><?=$this->lang->line('medicinesale_file')?></label>
                                <div class="custom-file">
                                    <input type="file" name="payment_file" class="custom-file-input file-upload-input file" id="file-upload">
                                    <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('medicinesale_choose_file')?></label>
                                </div>
                                <span class="text-danger" id="error_file"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('medicinesale_close')?></button>
                    <button type="button" class="btn btn-primary get_payment_info"><?=$this->lang->line('medicinesale_add_payment')?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal" id="viewayment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('medicinesale_view_payments')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="viewaymentView">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('medicinesale_close')?></button>
            </div>
        </div>
    </div>
</div>