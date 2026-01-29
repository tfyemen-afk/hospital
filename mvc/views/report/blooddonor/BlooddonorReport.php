<section class="section report">
    <div class="row">
        <div class="col-sm-12 col-margin-bottom">
            <?php
                $pdf_preview_uri  = site_url('blooddonorreport/pdf/'.$bloodgroupID.'/'.$blooddonorID.'/'.$patientID.'/'.$statusID.'/'.$bagno.'/'.$from_date.'/'.$to_date);
                $xlsx_preview_uri = site_url('blooddonorreport/xlsx/'.$bloodgroupID.'/'.$blooddonorID.'/'.$patientID.'/'.$statusID.'/'.$bagno.'/'.$from_date.'/'.$to_date);
                echo btn_pdfPreviewReport('blooddonorreport',$pdf_preview_uri, $this->lang->line('blooddonorreport_pdf_preview'));
                echo btn_xlsxReport('blooddonorreport', $xlsx_preview_uri, $this->lang->line('blooddonorreport_xlsx'));
            ?>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-gray">
            <div class="header-block">
                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('blooddonorreport_filter_data')?></p>
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
                            echo $this->lang->line('blooddonorreport_from_date')." : ".$label_from_date;
                        } elseif($bloodgroupID) { 
                            $f=FALSE;
                            echo $this->lang->line('blooddonorreport_blood_group')." : ".$label_bloodgroup;
                        } elseif ($blooddonorID) {
                            $f=FALSE;
                            echo $this->lang->line('blooddonorreport_donor_name')." : ".$label_blooddonor;
                        } elseif($patientID) {
                            $f=FALSE;
                            echo $this->lang->line('blooddonorreport_patient_name')." : ".$label_patient;

                        } ?>
                    </h6>
                    <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                        <?php if($from_date && $to_date) {
                            echo $this->lang->line('blooddonorreport_to_date')." : ".$label_to_date;
                        } elseif($statusID ) {
                            echo $this->lang->line('blooddonorreport_status')." : ".$label_status;
                        } elseif($bagno) {
                            echo $this->lang->line('blooddonorreport_bag_no')." : ".$label_bagno;
                        } elseif($from_date) {
                            echo $this->lang->line('blooddonorreport_from_date')." : ".$label_from_date;
                        }

                        ?>
                    </h6>
                </div>
                <div class="col-sm-12">
                    <?php  if(inicompute($blooddonors)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('blooddonorreport_name')?></th>
                                    <th><?=$this->lang->line('blooddonorreport_phone')?></th>
                                    <th><?=$this->lang->line('blooddonorreport_email')?></th>
                                    <th><?=$this->lang->line('blooddonorreport_blood_group')?></th>
                                    <th><?=$this->lang->line('blooddonorreport_patient_name')?></th>
                                    <th><?=$this->lang->line('blooddonorreport_date')?></th>
                                    <th><?=$this->lang->line('blooddonorreport_status')?></th>
                                    <th><?=$this->lang->line('blooddonorreport_bag_no')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i = 0; 
                                    foreach($blooddonors as $blooddonor) { $i++; ?>
                                    <tr>
                                        <td data-title="#"><?=$i?></td>
                                        <td data-title="<?=$this->lang->line('blooddonorreport_name')?>"><?=$blooddonor->name?></td>
                                        <td data-title="<?=$this->lang->line('blooddonorreport_phone')?>"><?=$blooddonor->phone?></td>
                                        <td data-title="<?=$this->lang->line('blooddonorreport_email')?>"><?=$blooddonor->email?></td>
                                        <td data-title="<?=$this->lang->line('blooddonorreport_blood_group')?>"><?=isset($bloodgroups[$blooddonor->bloodgroupID]) ? $bloodgroups[$blooddonor->bloodgroupID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('blooddonorreport_patient_name')?>"><?=isset($patients[$blooddonor->patientID]) ? $patients[$blooddonor->patientID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('blooddonorreport_date')?>"><?=app_date($blooddonor->create_date)?></td>
                                        <td data-title="<?=$this->lang->line('blooddonorreport_status')?>">
                                            <?php 
                                                if($blooddonor->status==0) {
                                                    echo $this->lang->line('blooddonorreport_reserve');
                                                } elseif($blooddonor->status==1) {
                                                    echo $this->lang->line('blooddonorreport_release');
                                                }
                                            ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('blooddonorreport_bag_no')?>"><?=$blooddonor->bagno?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('blooddonorreport_data_not_found')?></p>
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