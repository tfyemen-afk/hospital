<!DOCTYPE html>
<html>
    <head></head>
<body>
    <div class="report">
        <div class="reportfor">
            <h3><?=$this->lang->line('appointmentreport_report_for')?> - <?=$this->lang->line('appointmentreport_appointment')?></h3>
        </div>
        <div>
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
        <div>
            <?php if(inicompute($appointments)) { ?>
                <table>
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
                            <td><?=$i?></td>
                            <td><?=isset($doctors[$appointment->doctorID]) ? $doctors[$appointment->doctorID] : '&nbsp;'?></td>
                            <td><?=isset($patients[$appointment->patientID]) ? $patients[$appointment->patientID] : '&nbsp;'?></td>
                            <td><?=($appointment->casualty==1) ? $this->lang->line('appointmentreport_yes') : $this->lang->line('appointmentreport_no')?></td>
                            <td><?=($appointment->paymentstatus==1) ? $this->lang->line('appointmentreport_paid') : $this->lang->line('appointmentreport_due')?></td>
                            <td><?=($appointment->status==1) ? $this->lang->line('appointmentreport_visited') : $this->lang->line('appointmentreport_not_visited')?></td>
                            <td><?=app_datetime($appointment->appointmentdate)?></td>
                            <td><?=number_format($appointment->amount, 2)?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="report-not-found">
                    <p><?=$this->lang->line('appointmentreport_data_not_found')?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</body>
</html>