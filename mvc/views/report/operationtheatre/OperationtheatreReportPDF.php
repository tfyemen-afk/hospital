<!DOCTYPE html>
<html>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('operationtheatrereport_report_for')?> - <?=$this->lang->line('operationtheatrereport_operation_theatre')?></h3>
        </div>
        <div>
            <?php 
                $leftlabel  = "";
                $rightlabel = "";
                if($from_date && $to_date) { 
                    $leftlabel =  $this->lang->line('operationtheatrereport_from_date')." : ".$label_from_date;
                } elseif ($doctorID) {
                    $leftlabel =  $this->lang->line('operationtheatrereport_doctors')." : ".$label_doctor;
                }

                if($from_date && $to_date) {
                    $rightlabel = $this->lang->line('operationtheatrereport_to_date')." : ".$label_to_date;
                } elseif($patientID) {
                    $rightlabel = $this->lang->line('operationtheatrereport_patients')." : ".$label_patient;
                } elseif($from_date) {
                    $rightlabel = $this->lang->line('operationtheatrereport_from_date')." : ".$label_from_date;
                } 
            ?>
            <?php $f=TRUE; if($leftlabel) { $f=FALSE; ?>
                <h6 class="pull-left report-pulllabel"><?=$leftlabel?></h6>
            <?php } ?>

            <?php if($rightlabel) { ?>
                <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel"><?=$rightlabel?></h6>
            <?php } ?>
        </div>
        <div>
            <?php if(inicompute($operationtheatres)) { ?>
                <table>
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
                                <td><?=$i?></td>
                                <td><?=isset($doctors[$operationtheatre->doctorID]) ? $doctors[$operationtheatre->doctorID] : ''?></td>
                                <td><?=isset($patients[$operationtheatre->patientID]) ? $patients[$operationtheatre->patientID] : ''?></td>
                                <td><?=app_date($operationtheatre->operation_date, false)?></td>
                                <td><?=$operationtheatre->operation_name?></td>
                                <td><?=$operationtheatre->operation_type?></td>
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
    </div>
</body>
</html>