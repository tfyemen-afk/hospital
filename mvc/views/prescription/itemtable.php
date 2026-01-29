<?php if(inicompute($patientinfo)) { ?>
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-body box-profile box-border">
                <img src="<?=imagelink($patientinfo->photo,'uploads/user')?>" class="profile-user-img profile-height mx-auto d-block rounded-circle">
                <h3 class="profile-username text-center text-size"><?=$patientinfo->name?></h3>
                <p class="text-muted text-center"><?=inicompute($designation) ? $designation->designation : ''?></p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('prescription_visit_no')?></b> <a class="pull-right"><?=$visitno?></a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('prescription_uhid')?></b> <a class="pull-right"><?=$patientinfo->patientID?></a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('prescription_type')?></b>
                        <a class="pull-right">
                            <?php
                            if($patientinfo->patienttypeID == 0) {
                                echo $this->lang->line('prescription_opd');
                            } elseif($patientinfo->patienttypeID == 5) {
                                echo $this->lang->line('prescription_register');
                            } else {
                                echo $this->lang->line('prescription_ipd');
                            }
                            ?>
                        </a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('prescription_gender')?></b> <a class="pull-right"><?=($patientinfo->gender == '1')? $this->lang->line('prescription_male'): $this->lang->line('prescription_female')?></a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('prescription_age')?></b> <a class="pull-right"><?=stringtoage($patientinfo->age_day, $patientinfo->age_month, $patientinfo->age_year)?></a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('prescription_phone')?></b> <a class="pull-right"><?=$patientinfo->phone?></a>
                    </li>
                    <?php if(permissionChecker('patient_view')) { ?>
                        <li class="list-group-item list-group-item-background">
                            <a target="_blank" href="<?=site_url('patient/view/'.$displayuhID)?>" class="btn btn-primary btn-sm btn-block"><?=$this->lang->line('prescription_details')?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>

<div class="col-sm-8">
    <div class="row">
        <div class="col-sm-12">
            <ul class="list-group list-group-unbordered">
                <li class="list-group-item list-group-item-background">
                    <div id="medicine-div" class="form-group <?=form_error('medicine') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="medicine"><?=$this->lang->line('prescription_medicine')?><span class="text-danger"> *</span></label>
                        <input class="form-control" type="text" name="medicine" id="medicine" value="<?=set_value('medicine')?>">
                        <span id="medicine-error"><?=form_error('medicine')?></span>
                    </div>

                    <div id="instruction-div" class="form-group <?=form_error('instruction') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="instruction"><?=$this->lang->line('prescription_instruction')?><span class="text-danger"> *</span></label>
                        <textarea class="form-control <?=form_error('instruction') ? 'is-invalid' : '' ?>" id="instruction" name="instruction"><?=set_value('instruction')?></textarea>
                        <span id="instruction-error"><?=form_error('instruction')?></span>
                    </div>
                    <button type="button" class="btn btn-primary" id="addMedicine"><?=$this->lang->line('prescription_add')?></button>
                </li>
            </ul>
        </div>

        <div class="col-sm-12">
            <input class="display-none" type="text" id="counter" value="<?=set_value('counter', $counter)?>" name="counter">
            <table  class="table table-striped table-margin table-bordered table-hover simple-table">
                <thead>
                    <tr>
                        <th class="small-size"><?=$this->lang->line('prescription_slno')?></th>
                        <th><?=$this->lang->line('prescription_medicine')?></th>
                        <th><?=$this->lang->line('prescription_instruction')?></th>
                        <th class="small-size"><?=$this->lang->line('prescription_action')?></th>
                    </tr>
                </thead>
                <tbody id="medicineList">
                    <?php if($counter > 0) { for($i = 1; $i <= $counter; $i++) { ?>
                        <tr id="tr_<?=$i?>" trID="<?=$i?>">
                            <td>
                                <?=$i?>
                            </td>
                            <td id="medicine_<?=$i?>">
                                <?=set_value('medicine_'.$i)?>
                                <input name="medicine_<?=$i?>" class="display-none" type="text" value="<?=set_value('medicine_'.$i)?>">
                            </td>

                            <td id="instruction_<?=$i?>">
                                <?=set_value('instruction_'.$i)?>
                                <input name="instruction_<?=$i?>" class="display-none" type="text" value="<?=set_value('instruction_'.$i)?>">
                            </td>

                            <td>
                                <button type="button" class="btn btn-danger btn-sm btn-medicine-delete btn-padding " id="<?=$i?>">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } } ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary" id="save"><?=$this->lang->line('prescription_save')?></button>
        </div>
    </div>
</div>