<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('medicinesale_print'))?>
                <?=btn_sm_pdf('medicinesale/printpreview/'.$medicinesale->medicinesaleID, $this->lang->line('medicinesale_pdf_preview'))?>
                <?php 
                    if(($medicinesale->medicinesalestatus == 0) && ($medicinesale->medicinesalerefund == 0)) {
                        echo btn_sm_edit('medicinesale_edit', 'medicinesale/edit/'.$medicinesale->medicinesaleID.'/'.$displayID, $this->lang->line('medicinesale_edit'));
                    }
                ?>
                <?=btn_sm_mail($this->lang->line('medicinesale_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('medicinesale/index/'.$displayID)?>"><?=$this->lang->line('menu_medicinesale')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('medicinesale_view')?></li>
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
                                <p><span><?=$this->lang->line('medicinesale_patient_type')?> </span>: 
                                    <?php if($medicinesale->patient_type == 0) {
                                        echo $this->lang->line('medicinesale_opd');
                                    } elseif($medicinesale->patient_type == 1) {
                                        echo $this->lang->line('medicinesale_ipd');
                                    } else {
                                        echo $this->lang->line('medicinesale_none');
                                    } ?>
                                </p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('medicinesale_uhid')?> </span>: <?=($medicinesale->uhid !=0) ? $medicinesale->uhid : ''?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('medicinesale_purchase_date')?> </span>: <?=app_date($medicinesale->medicinesaledate)?></p>
                            </div>
                            <?php if($medicinesale->medicinesalefile) { ?>
                                <div class="view-body-area-left-profile-area-tab">
                                    <p><span><?=$this->lang->line('medicinesale_file')?> </span>:  <?=btn_download_file_only(site_url('medicinesale/download/'.$medicinesale->medicinesaleID), namesorting($medicinesale->medicinesalefileoriginalname, 20))?></p>
                                </div>
                            <?php } ?>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('medicinesale_description')?> </span>: <?=$medicinesale->medicinesaledescription?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 view-body-area-right user-profile-details">
                        <div id="hide-table">
                            <table class="table table-striped table-bordered table-hover">
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
                                        <td data-title="<?=$this->lang->line('medicinesale_total_amount')?>" colspan="6" class="footer-text"><?=$this->lang->line('medicinesale_total_amount')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                        <td class="font-weight" data-title="<?=$this->lang->line('medicinesale_subtotal')?>"><?=number_format($total_main_amount,2)?></td>
                                    </tr>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('medicinesale_paid')?>" colspan="6" class="footer-text"><?=$this->lang->line('medicinesale_paid')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                        <td class="font-weight" data-title="<?=$this->lang->line('medicinesale_subtotal')?>"><?=number_format($total_paid_amount,2)?></td>
                                    </tr>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('medicinesale_balance')?>" colspan="6" class="footer-text"><?=$this->lang->line('medicinesale_balance')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                        <td class="font-weight" data-title="<?=$this->lang->line('medicinesale_subtotal')?>"><?=number_format($total_balance_amount,2)?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="created-by">
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
                            </div>
                            <div class="col-md-4 offset-md-4">
                                <div class="created-by">
                                    <?=$this->lang->line('medicinesale_created_by')?> : <?=inicompute($userName) ? $userName->name : ''?> <br/>
                                    <?=$this->lang->line('medicinesale_date')?> : <?=app_date($medicinesale->medicinesaledate)?>
                                </div>
                            </div>
                        </div>
                        <?php if($medicinesale->medicinesalerefund == 1) { ?>
                            <div class="refund-by">
                                <?=$this->lang->line('medicinesale_refund')?>
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
                <h6 class="mdoal-title"><?=$this->lang->line('medicinesale_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('medicinesale_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('medicinesale_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('medicinesale_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('medicinesale_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>