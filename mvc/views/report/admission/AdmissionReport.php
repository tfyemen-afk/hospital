<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('admissionreport/pdf/'.$doctorID.'/'.$patientID.'/'.$wardID.'/'.$bedID.'/'.$casualty.'/'.$status.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('admissionreport/xlsx/'.$doctorID.'/'.$patientID.'/'.$bedID.'/'.$bedID.'/'.$casualty.'/'.$status.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('admissionreport',$pdf_preview_uri, $this->lang->line('admissionreport_pdf_preview'));
                echo btn_xlsxReport('admissionreport', $xlsx_preview_uri, $this->lang->line('admissionreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('admissionreport_filter_data')?></p>
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
                            echo $this->lang->line('admissionreport_from_date')." : ".date('d M Y',$from_date);
                        } elseif($doctorID) { $f=FALSE;
                            echo $this->lang->line('admissionreport_doctor')." : ".(isset($doctors[$doctorID]) ? $doctors[$doctorID] : '&nbsp;');
                        } elseif($wardID) { $f=FALSE;
                            echo $this->lang->line('admissionreport_ward')." : ".(isset($wards[$wardID]) ? $wards[$wardID] : '&nbsp;');
                        } elseif($casualty) { $f=FALSE;
                            echo $this->lang->line('admissionreport_casualty')." : ".(($casualty==2) ? $this->lang->line('admissionreport_yes') : $this->lang->line('admissionreport_no'));
                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($to_date ) {
                            echo $this->lang->line('admissionreport_to_date')." : ".date('d M Y',$to_date);
                        } elseif($patientID) {
                            echo $this->lang->line('admissionreport_patient')." : ".(isset($patients[$patientID]) ? $patients[$patientID] : '&nbsp;');
                        } elseif($bedID) {
                            echo $this->lang->line('admissionreport_bed')." : ".(isset($beds[$bedID]) ? $beds[$bedID] : '&nbsp;');
                        } elseif($status) {
                            echo $this->lang->line('admissionreport_status')." : ".(($status==2) ? $this->lang->line('admissionreport_release') : $this->lang->line('admissionreport_admitted'));
                        } ?>
                    </h6>
                </div>
                <div class="col-sm-12">
                    <?php  if(inicompute($admissions)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('admissionreport_doctor')?></th>
                                    <th><?=$this->lang->line('admissionreport_patient')?></th>
                                    <th><?=$this->lang->line('admissionreport_ward')?></th>
                                    <th><?=$this->lang->line('admissionreport_bed')?></th>
                                    <th><?=$this->lang->line('admissionreport_casualty')?></th>
                                    <th><?=$this->lang->line('admissionreport_status')?></th>
                                    <th><?=$this->lang->line('admissionreport_admission_date')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; foreach($admissions as $admission) { $i++; ?>
                                <tr>
                                    <td data-title="#"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('admissionreport_doctor')?>"><?=isset($doctors[$admission->doctorID]) ? $doctors[$admission->doctorID] : '&nbsp;'?></td>
                                    <td data-title="<?=$this->lang->line('admissionreport_patient')?>"><?=isset($patients[$admission->patientID]) ? $patients[$admission->patientID] : '&nbsp;'?></td>
                                    <td data-title="<?=$this->lang->line('admissionreport_ward')?>"><?=isset($wards[$admission->wardID]) ? $wards[$admission->wardID] : '&nbsp;'?></td>
                                    <td data-title="<?=$this->lang->line('admissionreport_bed')?>"><?=isset($beds[$admission->bedID]) ? $beds[$admission->bedID] : '&nbsp;'?></td>
                                    <td data-title="<?=$this->lang->line('admissionreport_casualty')?>"><?=($admission->casualty==1) ? $this->lang->line('admissionreport_yes') : $this->lang->line('admissionreport_no') ?></td>
                                    <td data-title="<?=$this->lang->line('admissionreport_status')?>"><?=($admission->status==1) ? $this->lang->line('admissionreport_release') : $this->lang->line('admissionreport_admitted')?></td>
                                    <td data-title="<?=$this->lang->line('admissionreport_admission_date')?>"><?=app_datetime($admission->admissiondate)?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('admissionreport_data_not_found')?></p>
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