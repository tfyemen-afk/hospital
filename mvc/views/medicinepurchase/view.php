<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('medicinepurchase_print'))?>
                <?=btn_sm_pdf('medicinepurchase/printpreview/'.$medicinepurchase->medicinepurchaseID, $this->lang->line('medicinepurchase_pdf_preview'))?>
                <?php if(($medicinepurchase->medicinepurchasestatus == 0) && ($medicinepurchase->medicinepurchaserefund == 0)) {
                    echo btn_sm_edit('medicinepurchase_edit', 'medicinepurchase/edit/'.$medicinepurchase->medicinepurchaseID.'/'.$displayID, $this->lang->line('medicinepurchase_edit'));
                 } ?>
                <?=btn_sm_mail($this->lang->line('medicinepurchase_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('medicinepurchase/index/'.$displayID)?>"><?=$this->lang->line('menu_medicinepurchase')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('medicinepurchase_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-12">
                    <?php featureheader($generalsettings);?>
                </div>
            </div>
            <div class="view-body-area">
                <div class="row">
                    <div class="col-sm-4 view-body-area-left user-profile-box">
                        <div class="view-body-area-left-profile-area">
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('medicinepurchase_warehouse')?> </span>: <?=isset($medicinewarehouses[$medicinepurchase->medicinewarehouseID]) ? $medicinewarehouses[$medicinepurchase->medicinewarehouseID]->name : ''?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('medicinepurchase_reference_no')?> </span>: <?=$medicinepurchase->medicinepurchasereferenceno?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('medicinepurchase_purchase_date')?> </span>: <?=app_date($medicinepurchase->medicinepurchasedate)?></p>
                            </div>
                            <?php if($medicinepurchase->medicinepurchasefile) { ?>
                                <div class="view-body-area-left-profile-area-tab">
                                    <p><span><?=$this->lang->line('medicinepurchase_file')?> </span>:  <?=btn_download_file_only(site_url('medicinepurchase/download/'.$medicinepurchase->medicinepurchaseID), namesorting($medicinepurchase->medicinepurchasefileoriginalname, 20))?></p>
                                </div>
                            <?php } ?>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('medicinepurchase_description')?> </span>: <?=$medicinepurchase->medicinepurchasedescription?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 view-body-area-right user-profile-details">
                        <div id="hide-table">
                            <table class="table table-striped table-bordered table-hover">
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
                                        <td class="font-weight" data-title="<?=$this->lang->line('medicinepurchase_subtotal')?>"><?=number_format($totalMainAmount,2)?></td>
                                    </tr>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('medicinepurchase_paid')?>" colspan="6" class="footer-text"><?=$this->lang->line('medicinepurchase_paid')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                        <td class="font-weight" data-title="<?=$this->lang->line('medicinepurchase_subtotal')?>"><?=number_format($totalPaidAmount,2)?></td>
                                    </tr>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('medicinepurchase_balance')?>" colspan="6" class="footer-text"><?=$this->lang->line('medicinepurchase_balance')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                        <td class="font-weight" data-title="<?=$this->lang->line('medicinepurchase_subtotal')?>"><?=number_format($totalBalanceAmount,2)?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="created-by">
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
                            </div>
                            <div class="col-md-4 offset-md-4">
                                <div class="created-by">
                                    <?=$this->lang->line('medicinepurchase_created_by')?> : <?=inicompute($userName) ? $userName->name : ''?> <br/>
                                    <?=$this->lang->line('medicinepurchase_date')?> : <?=app_date($medicinepurchase->medicinepurchasedate)?>
                                </div>
                            </div>
                        </div>
                        <?php if($medicinepurchase->medicinepurchaserefund == 1) { ?>
                            <div class="refund-by">
                                <?=$this->lang->line('medicinepurchase_refund')?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php featurefooter($generalsettings);?>
                </div>
            </div>
        </div>
    </section>
</article>

<div class="modal" id="mail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('medicinepurchase_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('medicinepurchase_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('medicinepurchase_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('medicinepurchase_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('medicinepurchase_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>