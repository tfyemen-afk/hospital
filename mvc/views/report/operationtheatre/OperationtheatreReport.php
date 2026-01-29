<section class="section report">
    <div class="row">
        <div class="col-sm-12" style="margin-bottom: 10px">
            <?php
                $pdf_preview_uri  = site_url('operationtheatrereport/pdf/'.$doctorID.'/'.$patientID.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('operationtheatrereport/xlsx/'.$doctorID.'/'.$patientID.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('operationtheatrereport',$pdf_preview_uri, $this->lang->line('operationtheatrereport_pdf_preview'));
                echo btn_xlsxReport('operationtheatrereport', $xlsx_preview_uri, $this->lang->line('operationtheatrereport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('operationtheatrereport_filter_data')?></p>
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
                            echo $this->lang->line('operationtheatrereport_from_date')." : ".$label_from_date;
                        } elseif ($doctorID) {
                            $f=FALSE;
                            echo $this->lang->line('operationtheatrereport_doctors')." : ".$label_doctor;
                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($from_date && $to_date) {
                            echo $this->lang->line('operationtheatrereport_to_date')." : ".$label_to_date;
                        } elseif($patientID) {
                            echo $this->lang->line('operationtheatrereport_patients')." : ".$label_patient;
                        } elseif($from_date) {
                            echo $this->lang->line('operationtheatrereport_from_date')." : ".$label_from_date;
                        }
                        ?>
                    </h6>
                </div>

                <div class="col-sm-12">
                    <?php  if(inicompute($operationtheatres)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('operationtheatrereport_doctor_name')?></th>
                                    <th><?=$this->lang->line('operationtheatrereport_patient_name')?></th>
                                    <th><?=$this->lang->line('operationtheatrereport_date')?></th>
                                    <th><?=$this->lang->line('operationtheatrereport_operation_name')?></th>
                                    <th><?=$this->lang->line('operationtheatrereport_operation_type')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0; 
                                    foreach($operationtheatres as $operationtheatre) { $i++; ?>
                                    <tr>
                                        <td data-title="#"><?=$i?></td>
                                        <td data-title="<?=$this->lang->line('operationtheatrereport_doctor_name')?>"><?=isset($doctors[$operationtheatre->doctorID]) ? $doctors[$operationtheatre->doctorID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('operationtheatrereport_patient_name')?>"><?=isset($patients[$operationtheatre->patientID]) ? $patients[$operationtheatre->patientID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('operationtheatrereport_date')?>"><?=app_date($operationtheatre->operation_date, false)?></td>
                                        <td data-title="<?=$this->lang->line('operationtheatrereport_operation_name')?>"><?=$operationtheatre->operation_name?></td>
                                        <td data-title="<?=$this->lang->line('operationtheatrereport_operation_type')?>"><?=$operationtheatre->operation_type?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('operationtheatrereport_data_not_found')?></p>
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