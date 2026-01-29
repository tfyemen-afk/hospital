<!DOCTYPE html>
<html lang="en">
    <head></head>
    <body>
        <div class="report">
            <div class="view-main-area">
                <div class="view-main-area-bottom">
                    <table class="view-main-area-bottom-table">
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_name')?></td>
                            <td><?=$birthregister->name?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_father_name')?></td>
                            <td><?=$birthregister->father_name?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_mother_name')?></td>
                            <td><?=$birthregister->mother_name?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_gender')?></td>
                            <td><?=($birthregister->gender == 1) ? $this->lang->line('birthregister_male'): $this->lang->line('birthregister_female')?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_date')?></td>
                            <td><?=app_datetime($birthregister->date)?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_weight')?></td>
                            <td><?=$birthregister->weight?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_length')?></td>
                            <td><?=$birthregister->length?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_patient')?></td>
                            <td><?=inicompute($patient) ? $patient->name : ''?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_birth_place')?></td>
                            <td><?=$birthregister->birth_place?></td>
                        </tr>
                        <tr>
                            <td class="view-main-area-bottom-table-label"><?=$this->lang->line('birthregister_nationality')?></td>
                            <td><?=$birthregister->nationality?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>