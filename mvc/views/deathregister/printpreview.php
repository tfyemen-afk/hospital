<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_name_of_deceased')?></td>
                            <td><?=$deathregister->name?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_relation_of_deceased')?></td>
                            <td><?=$deathregister->relation?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_guardian_name')?></td>
                            <td><?=$deathregister->guardian_name?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_permanent_address')?></td>
                            <td><?=$deathregister->permanent_address?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_gender')?></td>
                            <td><?=($deathregister->gender == 1) ? $this->lang->line('deathregister_male') : $this->lang->line('deathregister_female')?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_age_of_the_deceased')?></td>
                            <td><?=$deathregister->age?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_date_of_death')?></td>
                            <td><?=app_datetime($deathregister->death_date)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_nationality')?></td>
                            <td><?=$deathregister->nationality?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_cause_of_death')?></td>
                            <td><?=$deathregister->death_cause?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_name_of_the_doctor')?></td>
                            <td><?=inicompute($doctor) ? $doctor->name : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_death_confirm_date')?></td>
                            <td><?=app_datetime($deathregister->confirm_date)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('deathregister_patient')?></td>
                            <td><?=inicompute($patient) ? $patient->name : ''?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>