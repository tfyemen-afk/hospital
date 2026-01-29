<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('managesalary_slno')?></th>
                <th><?=$this->lang->line('managesalary_photo')?></th>
                <th><?=$this->lang->line('managesalary_name')?></th>
                <th><?=$this->lang->line('managesalary_designation')?></th>
                <th><?=$this->lang->line('managesalary_email')?></th>
                <?php if(permissionChecker('managesalary_add') || permissionChecker('managesalary_edit') || permissionChecker('managesalary_delete')) { ?>
                    <th><?=$this->lang->line('managesalary_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($users)) { foreach($users as $user) { $i++;  ?>
                <tr>
                    <td data-title="<?=$this->lang->line('managesalary_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('managesalary_photo')?>"><img class="img-responsive table-image-size" src="<?=imagelink($user->photo, '/uploads/user/') ?>"/>
                    </td>
                    <td data-title="<?=$this->lang->line('managesalary_name')?>"><?=$user->name?></td>
                    <td data-title="<?=$this->lang->line('managesalary_designation')?>"><?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></td>
                    <td data-title="<?=$this->lang->line('managesalary_email')?>"><?=$user->email?></td>
                    <?php if(permissionChecker('managesalary_add') || permissionChecker('managesalary_edit') || permissionChecker('managesalary_delete')) { ?>
                        <td data-title="<?=$this->lang->line('managesalary_action')?>">
                           <?php if(isset($managesalarys[$user->userID])) { $managesalary = $managesalarys[$user->userID]?>
                                <?=btn_view('managesalary/view/'.$managesalary->managesalaryID, $this->lang->line('managesalary_view'))?>
                                <?=btn_modal_edit('managesalary/edit', $managesalary->managesalaryID, $this->lang->line('managesalary_edit'))?>
                                <?=btn_delete('managesalary/delete/'.$managesalary->managesalaryID, $this->lang->line('managesalary_delete'))?>
                            <?php } else { ?>
                                <?=btn_modal_add('managesalary/add', $user->userID, $this->lang->line('managesalary_add'))?>
                            <?php } ?>
                        </td>
                    <?php }  ?>
                </tr>
            <?php } }  ?>
        </tbody>
    </table>
</div>

<?php if(permissionChecker('managesalary_add')) { ?>
    <div class="modal" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('managesalary_add')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form role="form" method="POST" id="getFormData">
                    <div class="modal-body">
                        <div class="form-group" id="error_salary_add_div">
                            <label><?=$this->lang->line('managesalary_salary')?><span class="text-danger"> *</span></label>
                            <?php
                                $array = array(
                                    "0" => $this->lang->line('managesalary_select_salary'),
                                    '1' => $this->lang->line('managesalary_monthly_salary'),
                                    '2' => $this->lang->line('managesalary_hourly_salary')
                                );
                                echo form_dropdown("salary", $array, set_value("salary"), "id='salary' class='form-control select2'");
                            ?>
                            <span class="text-danger" id="error_salary"></span>
                        </div>
                        <div class="form-group" id="error_salary_template_add_div">
                            <label><?=$this->lang->line('managesalary_template')?><span class="text-danger"> *</span></label>
                            <?php
                                $array = array(0 => $this->lang->line("managesalary_select_template"));
                                echo form_dropdown("template", $array, set_value("template"), "id='template' class='form-control select2'");
                            ?>
                            <span class="text-danger" id="error_salary_template"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('managesalary_close')?></button>
                        <button type="button" class="btn btn-primary add_managesalary"><?=$this->lang->line('managesalary_add')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(permissionChecker('managesalary_edit')) { ?>
    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('managesalary_edit')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form role="form" method="POST">
                    <div class="modal-body">
                        <div class="form-group" id="error_salary_edit_div">
                            <label><?=$this->lang->line('managesalary_salary')?><span class="text-danger"> *</span></label>
                            <?php
                                $array = array(
                                    "0" => $this->lang->line('managesalary_select_salary'),
                                    '1' => $this->lang->line('managesalary_monthly_salary'),
                                    '2' => $this->lang->line('managesalary_hourly_salary')
                                );
                                echo form_dropdown("salary", $array, set_value("salary"), "id='salary_edit' class='form-control select2'");
                            ?>
                            <span class="text-danger" id="error_salary_edit"></span>
                        </div>
                        <div class="form-group" id="error_salary_template_edit_div">
                            <label><?=$this->lang->line('managesalary_template')?><span class="text-danger"> *</span></label>
                            <?php
                                $array = array(0 => $this->lang->line("managesalary_select_template"));
                                echo form_dropdown("template", $array, set_value("template"), "id='template_edit' class='form-control select2'");
                            ?>
                            <span class="text-danger" id="error_salary_template_edit"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('managesalary_close')?></button>
                        <button type="button" class="btn btn-primary edit_managesalary"><?=$this->lang->line('managesalary_update')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>