<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('bill_print'))?>
                <?=btn_sm_pdf('bill/printpreview/'.$bill->billID, $this->lang->line('bill_pdf_preview'))?>
                <?=btn_sm_edit('bill_edit', 'bill/edit/'.$bill->billID.'/'.$displayID, $this->lang->line('bill_edit'))?>
                <?=btn_sm_mail($this->lang->line('bill_send_pdf_to_mail'))?>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('bill/index/'.$displayID)?>"><?=$this->lang->line('menu_bill')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('bill_view')?></li>
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
                    <div class="col-sm-4 view-body-area-left">
                        <div class="view-body-area-left-profile-area">
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('bill_bill_no')?> </span>: <?=$bill->billID?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('bill_uhid')?> </span>: <?=$patient->patientID?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('bill_name')?> </span>: <?=$patient->name?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('bill_patient_type')?> </span>: <?=isset($patienttypes[$bill->patienttypeID]) ? $patienttypes[$bill->patienttypeID]  : '' ?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('bill_date')?> </span>: <?=app_datetime($bill->date)?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('bill_payment_status')?> </span>: <?=isset($billstatus[$bill->status]) ? $billstatus[$bill->status] : ''?></p>
                            </div>
                            <div class="view-body-area-left-profile-area-tab">
                                <p><span><?=$this->lang->line('bill_note')?> </span>: <?=$bill->note?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 view-body-area-right">
                        <div id="hide-table">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('bill_slno')?></th>
                                        <th><?=$this->lang->line('bill_name')?></th>
                                        <th><?=$this->lang->line('bill_discount')?>(%)</th>
                                        <th><?=$this->lang->line('bill_amount')?></th>
                                        <th><?=$this->lang->line('bill_subtotal')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(inicompute($billitems)) { $i = 0; $totalmainamount = 0; $totalsubtotal = 0;
                                    foreach($billitems as $billitem) { $i++; ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('bill_slno')?>"><?=$i?></td>
                                            <td data-title="<?=$this->lang->line('bill_name')?>"><?=isset($billlabels[$billitem->billlabelID]) ? $billlabels[$billitem->billlabelID]->name : ''?></td>
                                            <td data-title="<?=$this->lang->line('bill_discount')?>(%)"><?=$billitem->discount?></td>
                                            <td data-title="<?=$this->lang->line('bill_amount')?>"><?php echo number_format($billitem->amount, 2); $totalmainamount += $billitem->amount; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('bill_subtotal')?>">
                                                <?php
                                                    $discount = 0;
                                                    if($billitem->discount > 0) {
                                                        $discount = (($billitem->amount / 100) * $billitem->discount);
                                                    }
                                                    $subtotal = ($billitem->amount - $discount);
                                                    echo number_format($subtotal, 2);
                                                    $totalsubtotal += $subtotal;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('bill_total')?>" colspan="4" class="footer-text"><?=$this->lang->line('bill_total')?> <?=!empty($generalsettings->currency_code) ? '('.$generalsettings->currency_code.')' : ''?></td>
                                        <td class="font-weight" data-title="<?=$this->lang->line('bill_total')?>"><?=number_format($totalsubtotal,2)?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-4 offset-md-8">
                                <div class="created-by">
                                    <?=$this->lang->line('bill_created_by')?> : <?=inicompute($userName) ? $userName->name : ''?> <br/>
                                    <?=$this->lang->line('bill_date')?> : <?=app_date($bill->date)?>
                                </div>
                            </div>
                        </div>
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
                <h6 class="mdoal-title"><?=$this->lang->line('bill_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('bill_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('bill_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('bill_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('bill_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>