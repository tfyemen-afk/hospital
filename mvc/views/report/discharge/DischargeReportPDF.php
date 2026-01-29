<?php 
    $conditionofdischargeArray[1] = $this->lang->line('dischargereport_stable');
    $conditionofdischargeArray[2] = $this->lang->line('dischargereport_almost_stable');
    $conditionofdischargeArray[3] = $this->lang->line('dischargereport_own_risk');
    $conditionofdischargeArray[4] = $this->lang->line('dischargereport_unstable');
?>
<!DOCTYPE html>
<html>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('dischargereport_report_for')?> - <?=$this->lang->line('dischargereport_discharge')?></h3>
        </div>
        <div>
            <?php 
                $leftlabel  = "";
                $rightlabel = "";
                if($from_date && $to_date) { 
                    $leftlabel =  $this->lang->line('dischargereport_from_date')." : ".$label_from_date;;
                } elseif($conditionofdischarge) {
                    $leftlabel =  $this->lang->line('dischargereport_condition_of_discharge')." : ".$label_conditionofdischarge;;
                }

                if($from_date && $to_date) {
                    $rightlabel = $this->lang->line('dischargereport_to_date')." : ".$label_to_date;
                } elseif($from_date) {
                    $rightlabel = $this->lang->line('dischargereport_from_date')." : ".$label_from_date;
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
            <?php if(inicompute($discharges)) { ?>
            <table>
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
                            <td><?=$i?></td>
                            <td><?=$discharge->patientID?></td>
                            <td><?=$discharge->name?></td>
                            <td><?=isset($conditionofdischargeArray[$discharge->conditionofdischarge]) ? $conditionofdischargeArray[$discharge->conditionofdischarge] : ''?></td>
                            <td><?=app_date($discharge->date, false)?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('dischargereport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>