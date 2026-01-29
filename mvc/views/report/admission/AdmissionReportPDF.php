<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('admissionreport_report_for')?> - <?=$this->lang->line('admissionreport_admission')?></h3>
        </div>
        <div>
            <?php 
            $f=TRUE; 
            $leftvalue = '';
            if($from_date) { $f=FALSE;
                $leftvalue =  $this->lang->line('admissionreport_from_date')." : ".date('d M Y',$from_date);
            } elseif($doctorID) { $f=FALSE;
                $leftvalue =  $this->lang->line('admissionreport_doctor')." : ".(isset($doctors[$doctorID]) ? $doctors[$doctorID] : '&nbsp;');
            } elseif($wardID) { $f=FALSE;
                $leftvalue =  $this->lang->line('admissionreport_ward')." : ".(isset($wards[$wardID]) ? $wards[$wardID] : '&nbsp;');
            } elseif($casualty) { $f=FALSE;
                $leftvalue =  $this->lang->line('admissionreport_casualty')." : ".(($casualty==2) ? $this->lang->line('admissionreport_yes') : $this->lang->line('admissionreport_no'));
            }

            $rightvalue = '';
            if($to_date ) {
                $rightvalue = $this->lang->line('admissionreport_to_date')." : ".date('d M Y',$to_date);
            } elseif($patientID) {
                $rightvalue = $this->lang->line('admissionreport_patient')." : ".(isset($patients[$patientID]) ? $patients[$patientID] : '&nbsp;');
            } elseif($bedID) {
                $rightvalue = $this->lang->line('admissionreport_bed')." : ".(isset($beds[$bedID]) ? $beds[$bedID] : '&nbsp;');
            } elseif($status) {
                $rightvalue = $this->lang->line('admissionreport_status')." : ".(($status==2) ? $this->lang->line('admissionreport_release') : $this->lang->line('admissionreport_admitted'));
            } ?>

            <?php if(!$f) { ?>
            <h6 class="pull-left report-pulllabel">
                <?=$leftvalue?>
            </h6>
            <?php } ?>
            <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                <?=$rightvalue?>
            </h6>
        </div>
        <div>
            <?php if(inicompute($admissions)) { ?>
                <table>
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
                            <td><?=$i?></td>
                            <td><?=isset($doctors[$admission->doctorID]) ? $doctors[$admission->doctorID] : ''?></td>
                            <td><?=isset($patients[$admission->patientID]) ? $patients[$admission->patientID] : ''?></td>
                            <td><?=isset($wards[$admission->wardID]) ? $wards[$admission->wardID] : ''?></td>
                            <td><?=isset($beds[$admission->bedID]) ? $beds[$admission->bedID] : ''?></td>
                            <td><?=($admission->casualty==1) ? $this->lang->line('admissionreport_yes') : $this->lang->line('admissionreport_no') ?></td>
                            <td><?=($admission->status==1) ? $this->lang->line('admissionreport_release') : $this->lang->line('admissionreport_admitted')?></td>
                            <td><?=app_datetime($admission->admissiondate)?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('admissionreport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>