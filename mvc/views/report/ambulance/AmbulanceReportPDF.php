<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('ambulancereport_report_for')?> - <?=$this->lang->line('ambulancereport_appointment')?></h3>
        </div>
        <div>
            <h6 class="pull-left report-pulllabel">
                <?php $f=TRUE; if($from_date) { $f=FALSE;
                    echo $this->lang->line('ambulancereport_from_date')." : ".date('d M Y',$from_date);
                } ?>
            </h6>
            <h6 class="<?=($f) ? 'pull-left' : 'pull-right'?> report-pulllabel">
                <?php if($to_date ) {
                    echo $this->lang->line('ambulancereport_to_date')." : ".date('d M Y',$to_date);
                } ?>
            </h6>
        </div>
        <div>
            <?php  if(inicompute($ambulancecalls)) { ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?=$this->lang->line('ambulancereport_ambulance_name')?></th>
                                    <th><?=$this->lang->line('ambulancereport_driver_name')?></th>
                                    <th><?=$this->lang->line('ambulancereport_patient_name')?></th>
                                    <th><?=$this->lang->line('ambulancereport_patient_contact')?></th>
                                    <th><?=$this->lang->line('ambulancereport_date')?></th>
                                    <th><?=$this->lang->line('ambulancereport_amount')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; $total_amount=0; foreach($ambulancecalls as $ambulancecall) { $i++; ?>
                                <tr>
                                    <td><?=$i?></td>
                                    <td><?=isset($ambulances[$ambulancecall->ambulanceID]) ? $ambulances[$ambulancecall->ambulanceID] : '&nbsp;'?></td>
                                    <td><?=$ambulancecall->drivername?></td>
                                    <td><?=$ambulancecall->patientname?></td>
                                    <td><?=$ambulancecall->patientcontact?></td>
                                    <td><?=app_date($ambulancecall->date)?></td>
                                    <td><?=number_format($ambulancecall->amount, 2)?></td>
                                </tr>
                                <?php $total_amount +=$ambulancecall->amount; } ?>
                                <tr>
                                    <td colspan="6"><?=$this->lang->line('ambulancereport_grand_total')?> <span class="font-weight-bold"><?=!empty($generalsettings->currency_code) ? "(".$generalsettings->currency_code.")" : ''?></span></td>
                                    <td class="font-weight-bold">
                                        <?=number_format($total_amount, 2);?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                <?php } else { ?>
                        <div class="report-not-found">
                            <p><?=$this->lang->line('ambulancereport_data_not_found')?></p>
                        </div>
                <?php } ?>
        </div>
    </div>
</body>
</html>