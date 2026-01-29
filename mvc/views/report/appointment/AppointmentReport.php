<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('appointmentreport/pdf/'.$doctorID.'/'.$patientID.'/'.$casualty.'/'.$payment.'/'.$status.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('appointmentreport/xlsx/'.$doctorID.'/'.$patientID.'/'.$casualty.'/'.$payment.'/'.$status.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('appointmentreport',$pdf_preview_uri, $this->lang->line('appointmentreport_pdf_preview'));
                echo btn_xlsxReport('appointmentreport', $xlsx_preview_uri, $this->lang->line('appointmentreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('appointmentreport_filter_data')?></p>
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
                            echo $this->lang->line('appointmentreport_from_date')." : ".date('d M Y',$from_date);
                        } elseif($doctorID) { $f=FALSE;
                            echo $this->lang->line('appointmentreport_doctor')." : ".(isset($doctors[$doctorID]) ? $doctors[$doctorID] : '&nbsp;');
                        } elseif($casualty) { $f=FALSE;
                            echo $this->lang->line('appointmentreport_casualty')." : ".(($casualty==2) ? $this->lang->line('appointmentreport_yes') : $this->lang->line('appointmentreport_no'));
                        } elseif($status) { $f=FALSE;
                            echo $this->lang->line('appointmentreport_status')." : ".(($status==2) ? $this->lang->line('appointmentreport_visited') : $this->lang->line('appointmentreport_not_visited'));

                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($to_date ) {
                            echo $this->lang->line('appointmentreport_to_date')." : ".date('d M Y',$to_date);
                        } elseif($patientID) {
                            echo $this->lang->line('appointmentreport_patient')." : ".(isset($patients[$patientID]) ? $patients[$patientID] : '&nbsp;');
                        } elseif($payment) {
                            echo $this->lang->line('appointmentreport_payment')." : ".(($payment==1) ? $this->lang->line('appointmentreport_paid') : $this->lang->line('appointmentreport_due'));
                        } ?>
                    </h6>
                </div>
                <div class="col-sm-12">
                    <?php  if(inicompute($appointments)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('appointmentreport_doctor')?></th>
                                    <th><?=$this->lang->line('appointmentreport_patient')?></th>
                                    <th><?=$this->lang->line('appointmentreport_casualty')?></th>
                                    <th><?=$this->lang->line('appointmentreport_payment')?></th>
                                    <th><?=$this->lang->line('appointmentreport_status')?></th>
                                    <th><?=$this->lang->line('appointmentreport_appointment_date')?></th>
                                    <th><?=$this->lang->line('appointmentreport_amount')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; foreach($appointments as $appointment) { $i++; ?>
                                <tr>
                                    <td data-title="#"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('appointmentreport_doctor')?>"><?=isset($doctors[$appointment->doctorID]) ? $doctors[$appointment->doctorID] : '&nbsp;'?></td>
                                    <td data-title="<?=$this->lang->line('appointmentreport_patient')?>"><?=isset($patients[$appointment->patientID]) ? $patients[$appointment->patientID] : '&nbsp;'?></td>
                                    <td data-title="<?=$this->lang->line('appointmentreport_casualty')?>"><?=($appointment->casualty==1) ? $this->lang->line('appointmentreport_yes') : $this->lang->line('appointmentreport_no') ?></td>
                                    <td data-title="<?=$this->lang->line('appointmentreport_payment')?>"><?=($appointment->paymentstatus==1) ? $this->lang->line('appointmentreport_paid') : $this->lang->line('appointmentreport_due')?></td>
                                    <td data-title="<?=$this->lang->line('appointmentreport_status')?>"><?=($appointment->status==1) ? $this->lang->line('appointmentreport_visited') : $this->lang->line('appointmentreport_not_visited')?></td>
                                    <td data-title="<?=$this->lang->line('appointmentreport_appointment_date')?>"><?=app_datetime($appointment->appointmentdate)?></td>
                                    <td data-title="<?=$this->lang->line('appointmentreport_amount')?>"><?=number_format($appointment->amount, 2)?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('appointmentreport_data_not_found')?></p>
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