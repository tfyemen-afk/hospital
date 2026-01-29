<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('medicinesalereport_report_for')?> - <?=$this->lang->line('medicinesalereport_medicine_sale')?></h3>
        </div>
        <div>
            <?php if($from_date && $to_date ) { ?>
                <h6 class="pull-left report-pulllabel">
                    <?=$this->lang->line('medicinesalereport_from_date')?> : <?=date('d M Y',$from_date)?>
                </h6>
                <h6 class="pull-right report-pulllabel">
                    <?=$this->lang->line('medicinesalereport_to_date')?> : <?=date('d M Y',$to_date)?>
                </h6>
            <?php } elseif($patient_type ==1 || $patient_type == 2) { ?>
                <h6 class="pull-left report-pulllabel">
                    <?=$this->lang->line('medicinesalereport_patient_type')?> :
                    <?php 
                        if($patient_type == 1) {
                            echo $this->lang->line('medicinesalereport_opd');           
                        } elseif ($patient_type==2) {
                            echo $this->lang->line('medicinesalereport_ipd');
                        } else {
                            echo $this->lang->line('medicinesalereport_none');
                        }
                    ?>        
                </h6>
            <?php } elseif($statusID != 0 ) { ?>
                <h6 class="pull-left report-pulllabel">
                    <?php
                        echo $this->lang->line('medicinesalereport_status')." : ";
                        if($statusID == 1) {
                            echo $this->lang->line("medicinesalereport_pending");
                        } elseif($statusID == 2) {
                            echo $this->lang->line("medicinesalereport_partial");
                        } elseif($statusID == 3) {
                            echo $this->lang->line("medicinesalereport_fully_paid");
                        } elseif($statusID == 4) {
                            echo $this->lang->line("medicinesalereport_refund");
                        }
                    ?>
                </h6>
            <?php } elseif($from_date) { ?>
                <h6 class="pull-left report-pulllabel">
                    <?=$this->lang->line('medicinesalereport_from_date')?> : <?=date('d M Y',$from_date)?>
                </h6>
            <?php } ?>
        </div>
        <div>
            <?php if(inicompute($medicinesales)) { ?>
                <table>
                    <thead>
                        <tr>
                            <th><?=$this->lang->line('medicinesalereport_slno')?></th>
                            <th style="width: 100px"><?=$this->lang->line('medicinesalereport_patient_type')?></th>
                            <th><?=$this->lang->line('medicinesalereport_uhid')?></th>
                            <th><?=$this->lang->line('medicinesalereport_date')?></th>
                            <?php if($statusID == 0 && $statusID !=4) { ?>
                                <th><?=$this->lang->line('medicinesalereport_status')?></th>
                            <?php } ?>
                            <th><?=$this->lang->line('medicinesalereport_total')?></th>
                            <th><?=$this->lang->line('medicinesalereport_paid')?></th>
                            <th><?=$this->lang->line('medicinesalereport_balance')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalmedicinesaleprice         = 0;
                        $totalmedicinesalepaidamount    = 0;
                        $totalmedicinesalebalanceamount = 0;
                        $i=0; 
                        foreach($medicinesales as $medicinesale) { $i++; ?>
                        <tr>
                            <td><?=$i?></td>
                            <td>
                                <?php 
                                    if($medicinesale->patient_type == 0) {
                                        echo $this->lang->line('medicinesalereport_opd');           
                                    } elseif ($medicinesale->patient_type==1) {
                                        echo $this->lang->line('medicinesalereport_ipd');
                                    } else {
                                        echo $this->lang->line('medicinesalereport_none');
                                    }
                                ?> 
                            </td>
                            <td><?=($medicinesale->uhid != 0) ? $medicinesale->uhid : ''?></td>
                            <td><?=app_date($medicinesale->medicinesaledate)?></td>
                            <?php if($statusID == 0 && $statusID !=4) { ?>
                                <td>
                                    <?php
                                        if($medicinesale->medicinesalestatus == 0) {
                                            echo $this->lang->line("medicinesalereport_pending");
                                        } elseif($medicinesale->medicinesalestatus == 1) {
                                            echo $this->lang->line("medicinesalereport_partial");
                                        } elseif($medicinesale->medicinesalestatus == 2) {
                                            echo $this->lang->line("medicinesalereport_fully_paid");
                                        }
                                    ?>
                                </td>
                            <?php } ?>
                            <td><?=number_format($medicinesale->medicinesaletotalamount, 2)?></td>
                            <td>
                                <?php 
                                    $paidamount = isset($medicinesalepaids[$medicinesale->medicinesaleID]) ? $medicinesalepaids[$medicinesale->medicinesaleID] : 0;
                                    echo number_format($paidamount, 2);
                                ?>    
                            </td>
                            <td>
                                <?php
                                    $balanceamount = $medicinesale->medicinesaletotalamount-$paidamount;
                                    echo number_format($balanceamount, 2);

                                    $totalmedicinesaleprice         += $medicinesale->medicinesaletotalamount;
                                    $totalmedicinesalepaidamount    += $paidamount;
                                    $totalmedicinesalebalanceamount += $balanceamount;
                                ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="<?=($statusID == 0 && $statusID != 4) ? '5' : '4'?>" class="font-weight-bold">
                                <?=$this->lang->line('medicinesalereport_grand_total')?> 
                                <?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?>        
                            </td>
                            <td class="font-weight-bold">
                                <?=number_format($totalmedicinesaleprice,2)?>        
                            </td>
                            <td class="font-weight-bold">
                                <?=number_format($totalmedicinesalepaidamount,2)?>        
                            </td>
                            <td class="font-weight-bold">
                                <?=number_format($totalmedicinesalebalanceamount,2)?>        
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('medicinesalereport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>