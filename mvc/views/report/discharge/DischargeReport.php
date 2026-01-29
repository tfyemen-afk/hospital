<?php 
    $conditionofdischargeArray[1] = $this->lang->line('dischargereport_stable');
    $conditionofdischargeArray[2] = $this->lang->line('dischargereport_almost_stable');
    $conditionofdischargeArray[3] = $this->lang->line('dischargereport_own_risk');
    $conditionofdischargeArray[4] = $this->lang->line('dischargereport_unstable');
?>
<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('dischargereport/pdf/'.$conditionofdischarge.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('dischargereport/xlsx/'.$conditionofdischarge.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('dischargereport',$pdf_preview_uri, $this->lang->line('dischargereport_pdf_preview'));
                echo btn_xlsxReport('dischargereport', $xlsx_preview_uri, $this->lang->line('dischargereport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('dischargereport_filter_data')?></p>
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
                            echo $this->lang->line('dischargereport_from_date')." : ".$label_from_date;
                        } elseif ($conditionofdischarge) {
                            $f=FALSE;
                            echo $this->lang->line('dischargereport_condition_of_discharge')." : ".$label_conditionofdischarge;
                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($from_date && $to_date) {
                            echo $this->lang->line('dischargereport_to_date')." : ".$label_to_date;
                        } elseif($from_date) {
                            echo $this->lang->line('dischargereport_from_date')." : ".$label_from_date;
                        } ?>
                    </h6>
                </div>

                <div class="col-sm-12">
                    <?php  if(inicompute($discharges)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('dischargereport_patientID')?></th>
                                    <th><?=$this->lang->line('dischargereport_name')?></th>
                                    <th><?=$this->lang->line('dischargereport_condition_of_discharge')?></th>
                                    <th><?=$this->lang->line('dischargereport_date')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0; 
                                    foreach($discharges as $discharge) { $i++; ?>
                                    <tr>
                                        <td data-title="#"><?=$i?></td>
                                        <td data-title="<?=$this->lang->line('dischargereport_patientID')?>"><?=$discharge->patientID?></td>
                                        <td data-title="<?=$this->lang->line('dischargereport_name')?>"><?=$discharge->name?></td>
                                        <td data-title="<?=$this->lang->line('dischargereport_condition_of_discharge')?>"><?=isset($conditionofdischargeArray[$discharge->conditionofdischarge]) ? $conditionofdischargeArray[$discharge->conditionofdischarge] : ''?></td>
                                        <td data-title="<?=$this->lang->line('dischargereport_date')?>"><?=app_date($discharge->date, false)?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('dischargereport_data_not_found')?></p>
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