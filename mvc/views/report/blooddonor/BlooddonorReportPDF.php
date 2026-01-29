<!DOCTYPE html>
<html>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('blooddonorreport_report_for')?> - <?=$this->lang->line('blooddonorreport_blooddonor')?></h3>
        </div>
        <div>
            <?php 
                $leftlabel  = "";
                $rightlabel = "";
                if($from_date && $to_date) {
                    $leftlabel = $this->lang->line('blooddonorreport_from_date')." : ".$label_from_date;
                } elseif($bloodgroupID) { 
                    $leftlabel =  $this->lang->line('blooddonorreport_blood_group')." : ".$label_bloodgroup;
                } elseif ($blooddonorID) {
                    $leftlabel =  $this->lang->line('blooddonorreport_donor_name')." : ".$label_blooddonor;
                } elseif($patientID) {
                    $leftlabel =  $this->lang->line('blooddonorreport_patient_name')." : ".$label_patient;
                }

                if($from_date && $to_date) {
                    $rightlabel = $this->lang->line('blooddonorreport_to_date')." : ".$label_to_date;
                } elseif($statusID) {
                    $rightlabel = $this->lang->line('blooddonorreport_status')." : ".$label_status;
                } elseif($bagno) {
                    $rightlabel = $this->lang->line('blooddonorreport_bag_no')." : ".$label_bagno;
                } elseif($from_date) {
                    $rightlabel = $this->lang->line('blooddonorreport_date')." : ".$label_from_date;
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
            <?php  if(inicompute($blooddonors)) { ?>
                <table>
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
                                <td><?=$i?></td>
                                <td><?=$blooddonor->name?></td>
                                <td><?=$blooddonor->phone?></td>
                                <td><?=$blooddonor->email?></td>
                                <td><?=isset($bloodgroups[$blooddonor->bloodgroupID]) ? $bloodgroups[$blooddonor->bloodgroupID] : ''?></td>
                                <td><?=isset($patients[$blooddonor->patientID]) ? $patients[$blooddonor->patientID] : ''?></td>
                                <td><?=app_date($blooddonor->create_date)?></td>
                                <td>
                                    <?php 
                                        if($blooddonor->status==0) {
                                            echo $this->lang->line('blooddonorreport_reserve');
                                        } elseif($blooddonor->status==1) {
                                            echo $this->lang->line('blooddonorreport_release');
                                        }
                                    ?>
                                </td>
                                <td><?=$blooddonor->bagno?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                    <div class="report-not-found">
                        <p><?=$this->lang->line('blooddonorreport_data_not_found')?></p>
                    </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>