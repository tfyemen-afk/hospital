<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="receipt-feature-header-title text-center">
                    <?=$this->lang->line('panel_title')?>
                </div>
                <div class="receipt-feature-title">
                    <div class="pull-left">
                        <b><?=$this->lang->line('discharge_discharge_no')?></b> : <?=$discharge->dischargeID?>
                    </div>
                    <div class="pull-right">
                        <b><?=$this->lang->line('discharge_date')?></b> : <?=date('d/m/Y')?>
                    </div>
                </div>

                <div class="receipt-body">
                    <table>
                        <tr>
                            <td width="33.33%"><?=$this->lang->line('discharge_uhid')?> : <?=$patient->patientID?></td>
                            <td width="33.33%"><?=$this->lang->line('discharge_name')?> : <?=$patient->name?></td>
                            <td width="33.33%"><?=$this->lang->line('discharge_age')?> / <?=$this->lang->line('discharge_sex')?> : <?=stringtoage($patient->age_day, $patient->age_month, $patient->age_year)?> / <?=isset($genders[$patient->gender]) ? $genders[$patient->gender] : ''?></td>
                        </tr>
                        <tr>
                            <td width="33.33%"><?=$this->lang->line('discharge_date_of_admission')?> : <?=app_datetime($patient->admissiondate)?></td>
                            <td width="33.33%"><?=$this->lang->line('discharge_date_of_discharge')?> : <?=app_datetime($discharge->date)?></td>
                            <td width="33.33%"><?=$this->lang->line('discharge_bed_no')?> : <?=inicompute($bed) ? $bed->name : ''?></td>
                        </tr>

                        <tr>
                            <td width="33.33%"><?=$this->lang->line('discharge_room')?> / <?=$this->lang->line('discharge_ward')?> : <?=inicompute($room) ? $room->name : ''?> / <?=inicompute($ward) ? $ward->name : ''?> - <?=inicompute($floor) ? $floor->name : ''?></td>
                            <td width="66.66%" colspan="2"><?=$this->lang->line('discharge_condition_of_discharge')?> : <?=isset($conditions[$discharge->conditionofdischarge]) ? $conditions[$discharge->conditionofdischarge] : '' ?></td>
                        </tr>
                    </table>
                </div>

                <div class="receipt-signature-div">
                    <div class="receipt-signature pull-right"><?=$this->lang->line('discharge_signature')?></div>
                </div>
            </div>
        </div>
    </body>
</html>