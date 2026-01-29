<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('billreport/pdf/'.$billcategoryID.'/'.$billlabelID.'/'.$uhid.'/'.$paymentstatus.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('billreport/xlsx/'.$billcategoryID.'/'.$billlabelID.'/'.$uhid.'/'.$paymentstatus.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('billreport',$pdf_preview_uri, $this->lang->line('billreport_pdf_preview'));
                echo btn_xlsxReport('billreport', $xlsx_preview_uri, $this->lang->line('billreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('billreport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
               
                <div class="col-sm-12">
                    <h6 class="pull-left report-pulllabel">
                        <?php $f=TRUE; if($from_date && $to_date) { $f=FALSE;
                            echo $this->lang->line('billreport_from_date')." : ".$label_from_date;
                        } elseif ($billcategoryID) {
                            $f=FALSE;
                            echo $this->lang->line('billreport_category')." : ".$label_category;
                        } elseif ($billlabelID) {
                            $f=FALSE;
                            echo $this->lang->line('billreport_label')." : ".$label_billlabel;
                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($from_date && $to_date) {
                            echo $this->lang->line('billreport_to_date')." : ".$label_to_date;
                        } elseif($uhid) {
                            echo $this->lang->line('billreport_patient_name')." : ".$label_patient;
                        } elseif($paymentstatus) {
                            echo $this->lang->line('billreport_payment_status')." : ".$label_payment_status;
                        } elseif($from_date) {
                            echo $this->lang->line('billreport_from_date')." : ".$label_from_date;
                        } ?>
                    </h6>
                </div>

                <div class="col-sm-12">
                    <?php  if(inicompute($bills)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('billreport_label')?></th>
                                    <th><?=$this->lang->line('billreport_category')?></th>
                                    <th><?=$this->lang->line('billreport_patient_name')?></th>
                                    <th><?=$this->lang->line('billreport_date')?></th>
                                    <th><?=$this->lang->line('billreport_payment_status')?></th>
                                    <th><?=$this->lang->line('billreport_discount')?> (%)</th>
                                    <th><?=$this->lang->line('billreport_amount')?></th>
                                    <th><?=$this->lang->line('billreport_total')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0;
                                    $total_amount = 0;
                                    $total_total  = 0;
                                    foreach($bills as $bill) { $i++; ?>
                                    <tr>
                                        <td data-title="#"><?=$i?></td>
                                        <td data-title="<?=$this->lang->line('billreport_label')?>"><?=$bill->billlabelname?></td>
                                        <td data-title="<?=$this->lang->line('billreport_category')?>"><?=isset($billcategorys[$bill->billcategoryID]) ? $billcategorys[$bill->billcategoryID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('billreport_patient_name')?>"><?=$bill->patientname?></td>
                                        <td data-title="<?=$this->lang->line('billreport_date')?>"><?=app_date($bill->create_date, FALSE)?></td>
                                        <td data-title="<?=$this->lang->line('billreport_payment_status')?>"><?=($bill->status) ? $this->lang->line('billreport_paid') : $this->lang->line('billreport_due')?></td>
                                        <td data-title="<?=$this->lang->line('billreport_discount')?> (%)"><?=$bill->discount?></td>
                                        <td data-title="<?=$this->lang->line('billreport_amount')?>"><?=number_format($bill->amount, 2)?></td>
                                        <td data-title="<?=$this->lang->line('billreport_total')?>">
                                            <?php 
                                                $billdiscount  = $bill->discount;
                                                $billamount    = $bill->amount;
                                                $total_amount += $billamount;
                                                $billtotal     = $billamount - (($billdiscount/100) * $billamount);
                                                $total_total  += $billtotal;
                                                echo number_format($billtotal, 2);
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('billreport_grand_total')?>" colspan="7"><?=$this->lang->line('billreport_grand_total')?> <span class="font-weight-bold"><?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?></span></td>
                                    <td data-title="<?=$this->lang->line('billreport_amount')?>"><?=number_format($total_amount, 2)?></td>
                                    <td data-title="<?=$this->lang->line('billreport_total')?>"><?=number_format($total_total, 2)?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('billreport_data_not_found')?></p>
                        </div>
                <?php } ?>
                </div>
                <div class="col-sm-12">
                    <?=reportfooter($generalsettings)?>
                </div>
            </div>
        </div>
    </div>
</section>