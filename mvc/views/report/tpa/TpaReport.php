<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('tpareport/pdf/'.$tpaID.'/'.$typeID.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('tpareport/xlsx/'.$tpaID.'/'.$typeID.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('tpareport',$pdf_preview_uri, $this->lang->line('tpareport_pdf_preview'));
                echo btn_xlsxReport('tpareport', $xlsx_preview_uri, $this->lang->line('tpareport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('tpareport_filter_data')?></p>
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
                            echo $this->lang->line('tpareport_from_date')." : ".$label_from_date;
                        } elseif ($tpaID) {
                            $f=FALSE;
                            echo $this->lang->line('tpareport_tpa')." : ".$label_tpa;
                        } elseif ($typeID) {
                            $f=FALSE;
                            echo $this->lang->line('tpareport_type')." : ".$label_type;
                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($from_date && $to_date) {
                            echo $this->lang->line('tpareport_to_date')." : ".$label_to_date;
                        } elseif($from_date) {
                            echo $this->lang->line('tpareport_from_date')." : ".$label_from_date;
                        } ?>
                    </h6>
                </div>

                <div class="col-sm-12">
                    <?php  if(inicompute($tpas)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('tpareport_tpa')?></th>
                                    <th><?=$this->lang->line('tpareport_type')?></th>
                                    <th><?=$this->lang->line('tpareport_patient_name')?></th>
                                    <th><?=$this->lang->line('tpareport_date')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0; 
                                    foreach($tpas as $tpa) { $i++; ?>
                                    <tr>
                                        <td data-title="#"><?=$i?></td>
                                        <td data-title="<?=$this->lang->line('tpareport_tpa')?>"><?=$tpa['tpa']?></td>
                                        <td data-title="<?=$this->lang->line('tpareport_type')?>"><?=$tpa['type']?></td>
                                        <td data-title="<?=$this->lang->line('tpareport_patient_name')?>"><?=$tpa['patient']?></td>
                                        <td data-title="<?=$this->lang->line('tpareport_date')?>"><?=app_datetime($tpa['datetime'], false)?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('tpareport_data_not_found')?></p>
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
