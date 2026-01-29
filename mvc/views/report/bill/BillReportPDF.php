<!DOCTYPE html>
<html>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('billreport_report_for')?> - <?=$this->lang->line('billreport_bill')?></h3>
        </div>
        <div>
            <?php 
                $leftlabel  = "";
                $rightlabel = "";
                if($from_date && $to_date) { 
                    $leftlabel  =  $this->lang->line('billreport_from_date')." : ".$label_from_date;
                } elseif($billcategoryID) {
                    $leftlabel  =  $this->lang->line('billreport_category')." : ".$label_category;
                } elseif($billlabelID) {
                    $leftlabel  =  $this->lang->line('billreport_label')." : ".$label_billlabel;
                }

                if($from_date && $to_date) {
                    $rightlabel = $this->lang->line('billreport_to_date')." : ".$label_to_date;
                } elseif($uhid) {
                    $rightlabel = $this->lang->line('billreport_patient_name')." : ".$label_patient;
                } elseif($paymentstatus) {
                    $rightlabel = $this->lang->line('billreport_payment_status')." : ".$label_payment_status;
                } elseif($from_date) {
                    $rightlabel = $this->lang->line('billreport_from_date')." : ".$label_from_date;
                } 
            ?>
            <?php $f=TRUE; if($leftlabel) { $f=FALSE; ?>
                <h6 class="pull-left report-pulllabel"><?=$leftlabel?></h6>
            <?php } ?>

            <?php if($rightlabel) { ?>
                <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel"><?=$rightlabel?></h6>
            <?php } ?>
        </div>
        <div>
            <?php if(inicompute($bills)) { ?>
            <table>
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="10%"><?=$this->lang->line('billreport_label')?></th>
                        <th width="10%"><?=$this->lang->line('billreport_category')?></th>
                        <th width="10%"><?=$this->lang->line('billreport_patient_name')?></th>
                        <th width="10%"><?=$this->lang->line('billreport_date')?></th>
                        <th width="10%"><?=$this->lang->line('billreport_payment_status')?></th>
                        <th width="10%"><?=$this->lang->line('billreport_discount')?> (%)</th>
                        <th width="10%"><?=$this->lang->line('billreport_amount')?></th>
                        <th width="10%"><?=$this->lang->line('billreport_total')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $i = 0;
                        $total_amount = 0;
                        $total_total  = 0;
                        foreach($bills as $bill) { $i++; ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$bill->billlabelname?></td>
                            <td><?=isset($billcategorys[$bill->billcategoryID]) ? $billcategorys[$bill->billcategoryID] : ''?></td>
                            <td><?=$bill->patientname?></td>
                            <td><?=app_date($bill->create_date, FALSE)?></td>
                            <td><?=($bill->status) ? $this->lang->line('billreport_paid') : $this->lang->line('billreport_due')?></td>
                            <td><?=$bill->discount?></td>
                            <td><?=number_format($bill->amount, 2)?></td>
                            <td>
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
                        <td colspan="7"><?=$this->lang->line('billreport_grand_total')?> <span class="font-weight-bold"><?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?></span></td>
                        <td><?=number_format($total_amount, 2)?></td>
                        <td><?=number_format($total_total, 2)?></td>
                    </tr>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('billreport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>