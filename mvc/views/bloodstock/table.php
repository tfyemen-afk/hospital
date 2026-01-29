<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('bloodstock_slno')?></th>
                <th><?=$this->lang->line('bloodstock_bagno')?></th>
                <th><?=$this->lang->line('bloodstock_date')?></th>
                <th><?=$this->lang->line('bloodstock_blood')?></th>
                <th><?=$this->lang->line('bloodstock_status')?></th>
                <?php if(permissionChecker('bloodstock_edit') || permissionChecker('bloodstock_delete')) { ?>
                    <th><?=$this->lang->line('bloodstock_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($bloodbags)) { foreach($bloodbags as $bloodbag) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('bloodstock_slno')?>"><?=$i;?></td>
                    <td data-title="<?=$this->lang->line('bloodstock_bagno')?>"><?=$bloodbag->bagno?></td>
                    <td data-title="<?=$this->lang->line('bloodstock_date')?>"><?=app_datetime($bloodbag->date)?></td>
                    <td data-title="<?=$this->lang->line('bloodstock_blood')?>">
                        <span class="<?=($bloodbag->donortypeID == 2) ? 'text-danger' : 'text-success'?> font-weight-bold"><?=($bloodbag->donortypeID==2) ? $this->lang->line('bloodstock_private') : $this->lang->line('bloodstock_public') ?></span>
                    </td>
                    <td data-title="<?=$this->lang->line('bloodstock_status')?>">
                        <span class="<?=($bloodbag->status==0) ? 'text-success' : 'text-danger'?> font-weight-bold"><?=($bloodbag->status==0) ? $this->lang->line('bloodstock_reserves') : $this->lang->line('bloodstock_release')?></span>
                    </td>
                    <?php if(permissionChecker('bloodstock_edit') || permissionChecker('bloodstock_delete')) { ?>
                        <td data-title="<?=$this->lang->line('bloodstock_action')?>">
                            <?=btn_modal_status('bloodstock/edit', $bloodbag->bloodbagID, $this->lang->line('bloodstock_status'))?>
                            <?php if($bloodbag->donortypeID == 3) { ?>
                                <?=btn_edit('bloodstock/edit/'.$bloodbag->bloodbagID, $this->lang->line('bloodstock_edit'))?>
                                <?=btn_delete('bloodstock/delete/'.$bloodbag->bloodbagID, $this->lang->line('bloodstock_delete'))?>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(permissionChecker('bloodstock_edit')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('bloodstock_update')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form role="form" method="POST" id="getFormData">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="hidden" name="bloodbagid" id="bloodbagid"/>
                            <label><?=$this->lang->line('bloodstock_status')?><span class="text-danger"> *</span></label>
                            <?php
                                $statusArray = array(
                                    "0" => $this->lang->line('bloodstock_please_select'),
                                    '1' => $this->lang->line('bloodstock_reserves'),
                                    '2' => $this->lang->line('bloodstock_release')
                                );
                                echo form_dropdown("status", $statusArray, set_value("status"), "id='status' class='form-control select2'");
                            ?>
                            <span class="text-danger" id="error_status"></span>
                        </div>
                        <div class="form-group">
                            <label><?=$this->lang->line('bloodstock_patient')?><span class="text-danger"> *</span></label>
                            <?php
                                $patientArray['0'] = $this->lang->line("bloodstock_please_select");
                                if(inicompute($patients)) {
                                    foreach ($patients as $patient) {
                                        $patientArray[$patient->patientID] = $patient->patientID.' - '.$patient->name;
                                    }
                                }

                                echo form_dropdown("patientID", $patientArray, set_value("patientID"), "id='patientID' class='form-control select2'");
                            ?>
                            <span class="text-danger" id="error_patientID"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" type="button" class="btn btn-default"><?=$this->lang->line('bloodstock_close')?></button>
                        <button type="button" class="btn btn-primary updatebloodstock"><?=$this->lang->line('bloodstock_update')?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>