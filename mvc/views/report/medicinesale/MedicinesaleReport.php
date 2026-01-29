<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('medicinesalereport/pdf/'.$patient_type.'/'.$uhid.'/'.$statusID.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('medicinesalereport/xlsx/'.$patient_type.'/'.$uhid.'/'.$statusID.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('medicinesalereport',$pdf_preview_uri, $this->lang->line('medicinesalereport_pdf_preview'));
                echo btn_xlsxReport('medicinesalereport', $xlsx_preview_uri, $this->lang->line('medicinesalereport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('medicinesalereport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
                <div class="col-sm-12">
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
                <div class="col-sm-12">
                    <?php  if(inicompute($medicinesales)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('medicinesalereport_slno')?></th>
                                    <th><?=$this->lang->line('medicinesalereport_patient_type')?></th>
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
                                    <td data-title="<?=$this->lang->line('medicinesalereport_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('medicinesalereport_patient_type')?>">
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
                                    <td data-title="<?=$this->lang->line('medicinesalereport_uhid')?>">
                                        <?=($medicinesale->uhid != 0) ? $medicinesale->uhid : '&nbsp;'?>        
                                    </td>
                                    <td data-title="<?=$this->lang->line('medicinesalereport_date')?>"><?=app_date($medicinesale->medicinesaledate)?></td>
                                    <?php if($statusID == 0 && $statusID !=4) { ?>
                                        <td data-title="<?=$this->lang->line('medicinesalereport_status')?>">
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
                                    <td data-title="<?=$this->lang->line('medicinesalereport_total')?>"><?=number_format($medicinesale->medicinesaletotalamount, 2)?></td>
                                    <td data-title="<?=$this->lang->line('medicinesalereport_paid')?>">
                                        <?php 
                                            $paidamount = isset($medicinesalepaids[$medicinesale->medicinesaleID]) ? $medicinesalepaids[$medicinesale->medicinesaleID] : 0;
                                            echo number_format($paidamount, 2);
                                        ?>    
                                    </td>
                                    <td data-title="<?=$this->lang->line('medicinesalereport_balance')?>">
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
                                    <td data-title="<?=$this->lang->line('medicinesalereport_grand_total')?>" colspan="<?=($statusID == 0 && $statusID !=4) ? '5' : '4'?>" class="text-right font-weight-bold">
                                        <?=$this->lang->line('medicinesalereport_grand_total')?> 
                                        <?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?>        
                                    </td>
                                    <td data-title="<?=$this->lang->line('medicinesalereport_total_amount')?>" class="font-weight-bold">
                                        <?=number_format($totalmedicinesaleprice,2)?>        
                                    </td>
                                    <td data-title="<?=$this->lang->line('medicinesalereport_total_paid')?>" class="font-weight-bold">
                                        <?=number_format($totalmedicinesalepaidamount,2)?>        
                                    </td>
                                    <td data-title="<?=$this->lang->line('medicinesalereport_total_balance')?>" class="font-weight-bold">
                                        <?=number_format($totalmedicinesalebalanceamount,2)?>        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('medicinesalereport_data_not_found')?></p>
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