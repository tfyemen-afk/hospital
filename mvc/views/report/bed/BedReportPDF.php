<!DOCTYPE html>
<html>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('bedreport_report_for')?> - <?=$this->lang->line('bedreport_bed')?></h3>
        </div>
        <div>
            <?php 
                $leftlabel  = "";
                $rightlabel = "";
                if($wardID) { 
                    $leftlabel =  $this->lang->line('bedreport_ward')." : ".$label_ward;
                } elseif($bedtypeID) {
                    $leftlabel =  $this->lang->line('bedreport_bed_type')." : ".$label_bedtype;
                }

                if($bedID) {
                    $rightlabel = $this->lang->line('bedreport_bed')." : ".$label_bed;
                } elseif($statusID) {
                    $rightlabel = $this->lang->line('bedreport_status')." : ".$label_status;
                } 
            
            $f=TRUE; 
            if($leftlabel) { $f=FALSE; ?>
                <h6 class="pull-left report-pulllabel"><?=$leftlabel?></h6>
            <?php } ?>

            <?php if($rightlabel) { ?>
                <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel"><?=$rightlabel?></h6>
            <?php } ?>
        </div>
        <div>
            <?php if(inicompute($beds)) { ?>
            <table>
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
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('bedreport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>