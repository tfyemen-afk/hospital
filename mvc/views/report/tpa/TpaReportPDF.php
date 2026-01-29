<!DOCTYPE html>
<html>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('tpareport_report_for')?> - <?=$this->lang->line('tpareport_tpa')?></h3>
        </div>
        <div>
            <?php 
                $leftlabel  = "";
                $rightlabel = "";
                if($from_date && $to_date) { 
                    $leftlabel =  $this->lang->line('tpareport_from_date')." : ".$label_from_date;
                } elseif($tpaID) {
                    $leftlabel =  $this->lang->line('tpareport_tpa')." : ".$label_tpa;
                } elseif($typeID) {
                    $leftlabel =  $this->lang->line('tpareport_type')." : ".$label_type;
                }

                if($from_date && $to_date) {
                    $rightlabel = $this->lang->line('tpareport_to_date')." : ".$label_to_date;
                } elseif($from_date) {
                    $rightlabel = $this->lang->line('tpareport_from_date')." : ".$label_from_date;
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
            <?php if(inicompute($tpas)) { ?>
            <table>
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
                            <td><?=$i?></td>
                            <td><?=$tpa['tpa']?></td>
                            <td><?=$tpa['type']?></td>
                            <td><?=$tpa['patient']?></td>
                            <td><?=app_datetime($tpa['datetime'], false)?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('tpareport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>