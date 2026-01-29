<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-top">
                    <div class="view-main-area-top-left">
                        <img class="view-main-area-top-img" src="<?=pdfimagelink($user->photo,'uploads/user')?>" alt="">
                    </div>
                    <div class="view-main-area-top-right">
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('appointment_name')?></div>
                            <div class="single-user-info-value">: <?=$patient->name?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('appointment_designation')?></div>
                            <div class="single-user-info-value">: <?=isset($designations[inicompute($user) ? $user->designationID : 2]) ? $designations[inicompute($user) ? $user->designationID : 2] : ''?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('appointment_uhid')?></div>
                            <div class="single-user-info-value">: <?=$patient->patientID?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('appointment_type')?></div>
                            <div class="single-user-info-value">: <?=($patient->patienttypeID == 0) ? $this->lang->line('appointment_opd') : $this->lang->line('appointment_ipd') ?></div>
                        </div>
                        <div class="single-user-info-item">
                            <div class="single-user-info-label"><?=$this->lang->line('appointment_phone')?></div>
                            <div class="single-user-info-value">: <?=$patient->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_gender')?></td>
                            <td><?=($patient->gender == 1) ? $this->lang->line('appointment_male') : $this->lang->line('appointment_female')?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_age')?></td>
                            <td><?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_appointmentdate')?></td>
                            <td><?=app_datetime($appointment->appointmentdate)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_doctor')?></td>
                            <td><?=inicompute($doctor) ? $doctor->name : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_case')?></td>
                            <td><?=$appointment->pcase?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_casualty')?></td>
                            <td><?=($appointment->pcase == 1) ? $this->lang->line('appointment_yes') : $this->lang->line('appointment_no') ?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_oldpatient')?></td>
                            <td><?=($appointment->oldpatient == 1) ? $this->lang->line('appointment_no') : $this->lang->line('appointment_yes')?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_tpa')?></td>
                            <td><?=inicompute($tpa) ? $tpa->name : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_reference')?></td>
                            <td><?=$appointment->reference?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_symptoms')?></td>
                            <td><?=$appointment->symptoms?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_allergies')?></td>
                            <td><?=$appointment->allergies?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_amount')?></td>
                            <td><?=$appointment->amount?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_payment')?></td>
                            <td><?=isset($paymentstatus[$appointment->paymentstatus]) ? $paymentstatus[$appointment->paymentstatus] : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_paymentmethod')?></td>
                            <td><?=isset($paymentmethods[$appointment->paymentmethodID]) ? $paymentmethods[$appointment->paymentmethodID] : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('appointment_type')?></td>
                            <td><?=isset($appointmentType[$appointment->type]) ? $appointmentType[$appointment->type] : ''?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>