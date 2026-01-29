<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('ambulancereport/pdf/'.$ambulanceID.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('ambulancereport/xlsx/'.$ambulanceID.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('ambulancereport',$pdf_preview_uri, $this->lang->line('ambulancereport_pdf_preview'));
                echo btn_xlsxReport('ambulancereport', $xlsx_preview_uri, $this->lang->line('ambulancereport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('ambulancereport_filter_data')?></p>
            </div>
        </div>
        <div class="card-block" id="printablediv">
            <div class="row printmargin">
                <div class="col-sm-12">
                    <?=reportheader($generalsettings)?>
                </div>
                <div class="col-sm-12">
                    <h6 class="pull-left report-pulllabel">
                        <?php $f=TRUE; if($from_date) { $f=FALSE;
                            echo $this->lang->line('ambulancereport_from_date')." : ".date('d M Y',$from_date);
                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($to_date ) {
                            echo $this->lang->line('ambulancereport_to_date')." : ".date('d M Y',$to_date);
                        } ?>
                    </h6>
                </div>
                <div class="col-sm-12">
                    <?php  if(inicompute($ambulancecalls)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('ambulancereport_ambulance_name')?></th>
                                    <th><?=$this->lang->line('ambulancereport_driver_name')?></th>
                                    <th><?=$this->lang->line('ambulancereport_patient_name')?></th>
                                    <th><?=$this->lang->line('ambulancereport_patient_contact')?></th>
                                    <th><?=$this->lang->line('ambulancereport_date')?></th>
                                    <th><?=$this->lang->line('ambulancereport_amount')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; $total_amount=0; foreach($ambulancecalls as $ambulancecall) { $i++; ?>
                                <tr>
                                    <td data-title="#"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('ambulancereport_ambulance_name')?>"><?=isset($ambulances[$ambulancecall->ambulanceID]) ? $ambulances[$ambulancecall->ambulanceID] : '&nbsp;'?></td>
                                    <td data-title="<?=$this->lang->line('ambulancereport_driver_name')?>"><?=$ambulancecall->drivername?></td>
                                    <td data-title="<?=$this->lang->line('ambulancereport_patient_name')?>"><?=$ambulancecall->patientname?></td>
                                    <td data-title="<?=$this->lang->line('ambulancereport_patient_contact')?>"><?=$ambulancecall->patientcontact?></td>
                                    <td data-title="<?=$this->lang->line('ambulancereport_date')?>"><?=app_date($ambulancecall->date)?></td>
                                    <td data-title="<?=$this->lang->line('ambulancereport_amount')?>"><?=number_format($ambulancecall->amount, 2)?></td>
                                </tr>
                                <?php $total_amount +=$ambulancecall->amount; } ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('ambulancereport_grand_total')?>" colspan="6"><?=$this->lang->line('ambulancereport_grand_total')?> <span class="font-weight-bold"><?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?></span></td>
                                    <td data-title="<?=$this->lang->line('ambulancereport_total_amount')?>" class="font-weight-bold">
                                        <?=number_format($total_amount, 2);?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('ambulancereport_data_not_found')?></p>
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