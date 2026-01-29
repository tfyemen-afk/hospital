<?php if(inicompute($patientinfo)) { ?>
    <div class="col-sm-4">
        <div class="box box-primary">
            <div class="box-body box-profile box-border">
                <img src="<?=imagelink($patientinfo->photo,'uploads/user')?>" class="profile-user-img profile-height mx-auto d-block rounded-circle" alt="">
                <h3 class="profile-username text-center text-size"><?=$patientinfo->name?></h3>
                <p class="text-muted text-center"><?=inicompute($designation) ? $designation->designation : ''?></p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('instruction_uhid')?></b> <a class="pull-right"><?=$patientinfo->patientID?></a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('instruction_type')?></b>
                        <a class="pull-right">
                            <?php
                            if($patientinfo->patienttypeID == 0) {
                                echo $this->lang->line('instruction_opd');
                            } elseif($patientinfo->patienttypeID == 5) {
                                echo $this->lang->line('instruction_register');
                            } else {
                                echo $this->lang->line('instruction_ipd');
                            }
                            ?>
                        </a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('instruction_gender')?></b> <a class="pull-right"><?=($patientinfo->gender == '1')? $this->lang->line('instruction_male'): $this->lang->line('instruction_female')?></a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('instruction_age')?></b> <a class="pull-right"><?=stringtoage($patientinfo->age_day, $patientinfo->age_month, $patientinfo->age_year)?></a>
                    </li>
                    <li class="list-group-item list-group-item-background">
                        <b><?=$this->lang->line('instruction_phone')?></b> <a class="pull-right"><?=$patientinfo->phone?></a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
<?php } ?>

<?php if(inicompute($instructions)) { ?>
    <div class="col-sm-8">
        <ul class="list-group list-group-unbordered">
            <li class="list-group-item list-group-item-background">
                <?php foreach($instructions as $instruction) { ?>
                    <div class="media media-margin" >
                        <img src="<?=imagelink($instruction->photo, 'uploads/user')?>" class="width mr-3">
                        <div class="media-body">
                            <h6 class="font-size mt-0">
                                <?=app_datetime($instruction->create_date)?>
                                <span class="pull-right">
                                    <?=btn_edit('instruction/edit/'.$instruction->instructionID.'/'.$displayID.'/'.$displayuhID, $this->lang->line('instruction_edit'))?>
                                    <?=btn_delete('instruction/delete/'.$instruction->instructionID.'/'.$displayID.'/'.$displayuhID, $this->lang->line('instruction_delete'))?>
                                </span>
                            </h6>
                            <span class="font-size"><?=$instruction->instruction?></span>
                        </div>
                    </div>
                <?php  } ?>
            </li>
        </ul>
    </div>
<?php } ?>