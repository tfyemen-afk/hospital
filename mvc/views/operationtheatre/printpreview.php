<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('operationtheatre_operation_name')?></td>
                            <td><?=$operationtheatre->operation_name?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('operationtheatre_operation_type')?></td>
                            <td><?=$operationtheatre->operation_type?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('operationtheatre_uhid')?></td>
                            <td><?=$operationtheatre->patientID?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('operationtheatre_patient')?></td>
                            <td><?=inicompute($patient) ? $patient->name : '&nbsp;'?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('operationtheatre_operation_date')?></td>
                            <td><?=app_datetime($operationtheatre->operation_date)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('operationtheatre_doctor')?></td>
                            <td><?=isset($doctors[$operationtheatre->doctorID]) ? $doctors[$operationtheatre->doctorID] : '&nbsp;'?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('operationtheatre_assistant_doctor_1')?></td>
                            <td><?=isset($doctors[$operationtheatre->assistant_doctor_1]) ? $doctors[$operationtheatre->assistant_doctor_1] : '&nbsp;'?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('operationtheatre_assistant_doctor_2')?></td>
                            <td><?=isset($doctors[$operationtheatre->assistant_doctor_2]) ? $doctors[$operationtheatre->assistant_doctor_2] : '&nbsp;'?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>