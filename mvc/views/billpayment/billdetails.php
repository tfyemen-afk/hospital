<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('billpayment_print'))?>
                <?=btn_sm_pdf('billpayment/detailsprintpreview/'.$displayuhID.'/1', $this->lang->line('billpayment_pdf_preview'))?>
                <?=btn_sm_mail($this->lang->line('billpayment_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('billpayment/index/'.$displayID.'/'.$displayuhID)?>"><?=$this->lang->line('menu_billpayment')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('billpayment_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-3 user-profile-box">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <img src="<?=imagelink($patient->photo, 'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
                            <h3 class="profile-username text-center"><?=$patient->name?></h3>
                            <p class="text-muted text-center"><?=inicompute($designation) ? $designation->designation : ''?></p>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('billpayment_uhid')?></b> <a class="pull-right"><?=$patient->patientID?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('billpayment_type')?></b> <a class="pull-right"><?php if($patient->patienttypeID == 0) { echo $this->lang->line('billpayment_opd'); } elseif($patient->patienttypeID == 1) { echo $this->lang->line('billpayment_ipd'); } else { echo $this->lang->line('billpayment_register'); } ?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('billpayment_gender')?></b> <a class="pull-right"><?=($patient->gender == '1')? $this->lang->line('billpayment_male'): $this->lang->line('billpayment_female')?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('billpayment_age')?></b> <a class="pull-right"><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year);?></a>
                                </li>
                                <li class="list-group-item list-group-item-background">
                                    <b><?=$this->lang->line('billpayment_phone')?></b> <a class="pull-right"><?=$patient->phone?></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9 user-profile-details">
                    <div class="card">
                        <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link ghref active" id="bill-tab" data-toggle="tab" href="#bill" role="tab" aria-controls="bill" aria-selected="true"><?=$this->lang->line('billpayment_bill')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link ghref" id="billitem-tab" data-toggle="tab" href="#billitem" role="tab" aria-controls="billitem" aria-selected="true"><?=$this->lang->line('billpayment_billitem')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link ghref" id="payment-tab" data-toggle="tab" href="#payment" role="tab" aria-controls="payment" aria-selected="true"><?=$this->lang->line('billpayment_payment')?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link ghref" id="payment-tab" data-toggle="tab" href="#bill-summary" role="tab" aria-controls="bill-summary" aria-selected="true"><?=$this->lang->line('billpayment_bill_summary')?></a>
                            </li>
                        </ul>

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active overflow-hidden" id="bill" role="tabpanel" aria-labelledby="bill-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('billpayment_billno')?></th>
                                            <th><?=$this->lang->line('billpayment_date')?></th>
                                            <th><?=$this->lang->line('billpayment_totalamount')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $totalBillAmount = 0; if(inicompute($bills)) { $i = 0;
                                            foreach($bills as $bill) { $i++; ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('billpayment_billno')?>"><?=$i?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_date')?>"><?=app_datetime($bill->create_date)?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_totalamount')?>"><?=app_currency_format($bill->totalamount)?></td>
                                                        <?php
                                                            $totalBillAmount += $bill->totalamount;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } } ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('billpayment_total')?>" colspan="2" class="font-weight"><?=$this->lang->line('billpayment_total')?></td>
                                            <td data-title="<?=$this->lang->line('billpayment_amount')?>" class="font-weight"><?=number_format($totalBillAmount,2)?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade show overflow-hidden" id="billitem" role="tabpanel" aria-labelledby="billitem-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('billpayment_slno')?></th>
                                            <th><?=$this->lang->line('billpayment_date')?></th>
                                            <th><?=$this->lang->line('billpayment_name')?></th>
                                            <th><?=$this->lang->line('billpayment_discount')?>(%)</th>
                                            <th><?=$this->lang->line('billpayment_amount')?></th>
                                            <th><?=$this->lang->line('billpayment_subtotal')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $totalMainBillAmount = 0; $totalBillSubTotal = 0; if(inicompute($billitems)) { $i = 0;
                                            foreach($billitems as $billitem) { $i++; ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('billpayment_slno')?>"><?=$i?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_date')?>"><?=app_datetime($billitem->create_date)?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_name')?>"><?=isset($billlabels[$billitem->billlabelID]) ? $billlabels[$billitem->billlabelID] : ''?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_discount')?>(%)"><?=app_currency_format($billitem->discount)?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_amount')?>">
                                                        <?php
                                                            $mainAmount = $billitem->amount;
                                                            echo app_currency_format($mainAmount);
                                                            $totalMainBillAmount +=$mainAmount;
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('billpayment_subtotal')?>">
                                                        <?php
                                                            $subTotal = ($billitem->amount - (($billitem->amount/100) * $billitem->discount));
                                                            echo app_currency_format($subTotal);
                                                            $totalBillSubTotal += $subTotal;
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } } ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('billpayment_total')?>" colspan="4" class="font-weight"><?=$this->lang->line('billpayment_total')?></td>
                                            <td data-title="<?=$this->lang->line('billpayment_amount')?>" class="font-weight"><?=number_format($totalMainBillAmount,2)?></td>
                                            <td data-title="<?=$this->lang->line('billpayment_subtotal')?>" class="font-weight"><?=number_format($totalBillSubTotal,2)?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade show overflow-hidden" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th><?=$this->lang->line('billpayment_slno')?></th>
                                            <th><?=$this->lang->line('billpayment_date')?></th>
                                            <th><?=$this->lang->line('billpayment_method')?></th>
                                            <th><?=$this->lang->line('billpayment_amount')?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php  $totalPaymentAmount = 0; if(inicompute($billpayments)) { $i = 0;
                                            foreach($billpayments as $billpayment) { $i++; ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('billpayment_slno')?>"><?=$i?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_date')?>"><?=app_datetime($billpayment->create_date)?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_method')?>"><?=isset($paymentmethods[$billpayment->paymentmethod]) ? $paymentmethods[$billpayment->paymentmethod] : ''?></td>
                                                    <td data-title="<?=$this->lang->line('billpayment_amount')?>">
                                                        <?php
                                                            $totalPaymentAmount += $billpayment->paymentamount;
                                                            echo app_currency_format($billpayment->paymentamount);
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php } } ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('billpayment_total')?>" colspan="3" class="font-weight"><?=$this->lang->line('billpayment_total')?></td>
                                            <td data-title="<?=$this->lang->line('billpayment_subtotal')?>" class="font-weight"><?=number_format($totalPaymentAmount, 2)?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane fade show overflow-hidden" id="bill-summary" role="tabpanel" aria-labelledby="payment-tab">
                                <table class="col-sm-6 table">
                                    <tbody>
                                        <tr>
                                            <td class="border-font-weight"><?=$this->lang->line('billpayment_total_bill')?></td>
                                            <td class="border-none"><?=number_format($totalBillSubTotal, 2)?></td>
                                        </tr>
                                        <tr>
                                            <td class="border-font-weight"><?=$this->lang->line('billpayment_total_payment')?></td>
                                            <td class="border-none"><?=number_format($totalPaymentAmount, 2)?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('billpayment_total_due')?></td>
                                            <td><?=number_format(($totalBillSubTotal - $totalPaymentAmount), 2)?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

<div class="modal" id="mail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('billpayment_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('billpayment_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('billpayment_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('billpayment_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('billpayment_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>