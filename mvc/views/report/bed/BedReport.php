<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('bedreport/pdf/'.$wardID.'/'.$bedtypeID.'/'.$bedID.'/'.$statusID);
                $xlsx_preview_uri = site_url('bedreport/xlsx/'.$wardID.'/'.$bedtypeID.'/'.$bedID.'/'.$statusID);
                echo btn_pdfPreviewReport('bedreport',$pdf_preview_uri, $this->lang->line('bedreport_pdf_preview'));
                echo btn_xlsxReport('bedreport', $xlsx_preview_uri, $this->lang->line('bedreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('bedreport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
               
                <div class="col-sm-12">
                    <h6 class="pull-left report-pulllabel">
                        <?php $f=TRUE; if($wardID) { $f=FALSE;
                            echo $this->lang->line('bedreport_ward')." : ".$label_ward;
                        } elseif ($bedtypeID) {
                            $f=FALSE;
                            echo $this->lang->line('bedreport_bed_type')." : ".$label_bedtype;
                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($bedID) {
                            echo $this->lang->line('bedreport_bed')." : ".$label_bed;
                        } elseif($statusID) {
                            echo $this->lang->line('bedreport_status')." : ".$label_status;
                        } ?>
                    </h6>
                </div>

                <div class="col-sm-12">
                    <?php  if(inicompute($beds)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('bedreport_bed_name')?></th>
                                    <th><?=$this->lang->line('bedreport_bed_type')?></th>
                                    <th><?=$this->lang->line('bedreport_ward')?></th>
                                    <th><?=$this->lang->line('bedreport_status')?></th>
                                    <?php if($statusID !=1) { ?>
                                        <th><?=$this->lang->line('bedreport_patient_name')?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0; 
                                    foreach($beds as $bed) { $i++; ?>
                                    <tr>
                                        <td data-title="#"><?=$i?></td>
                                        <td data-title="<?=$this->lang->line('bedreport_bed_name')?>"><?=$bed->name?></td>
                                        <td data-title="<?=$this->lang->line('bedreport_bed_type')?>"><?=isset($bedtypes[$bed->bedtypeID]) ? $bedtypes[$bed->bedtypeID] : '&nbsp;'?></td>
                                        <td data-title="<?=$this->lang->line('bedreport_ward')?>"><?=isset($wards[$bed->wardID]) ? $wards[$bed->wardID]->name : '&nbsp;'?> - <?=(isset($wards[$bed->wardID]) && isset($rooms[$wards[$bed->wardID]->roomID])) ? $rooms[$wards[$bed->wardID]->roomID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('bedreport_status')?>"><?=($bed->status) ? $statusArray[2] : $statusArray[1] ?></td>
                                        <?php if($statusID !=1) { ?>
                                            <td data-title="<?=$this->lang->line('bedreport_patient_name')?>"><?=$bed->patientname?></td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('bedreport_data_not_found')?></p>
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