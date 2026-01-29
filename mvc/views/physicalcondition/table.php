<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('physicalcondition_slno')?></th>
                <th><?=$this->lang->line('physicalcondition_uhid')?></th>
                <th><?=$this->lang->line('physicalcondition_name')?></th>
                <th><?=$this->lang->line('physicalcondition_patient_type')?></th>
                <th><?=$this->lang->line('physicalcondition_date')?></th>
                <?php if(permissionChecker('physicalcondition_view') || permissionChecker('physicalcondition_edit') || permissionChecker('physicalcondition_delete')) { ?>
                    <th><?=$this->lang->line('physicalcondition_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($physicalconditions)) { foreach($physicalconditions as $physicalcondition) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('physicalcondition_slno')?>"><?=$i;?></td>
                    <td data-title="<?=$this->lang->line('physicalcondition_uhid')?>"><?=$physicalcondition->patientID;?></td>
                    <td data-title="<?=$this->lang->line('physicalcondition_name')?>"><?=$physicalcondition->name?></td>
                    <td data-title="<?=$this->lang->line('physicalcondition_patient_type')?>"><?=($physicalcondition->patienttypeID == 1) ? $this->lang->line('physicalcondition_ipd') : $this->lang->line('physicalcondition_opd');?></td>
                    <td data-title="<?=$this->lang->line('physicalcondition_date')?>"><?=date('d M Y h:i A', strtotime($physicalcondition->date)) ; ?></td>
                    <?php if(permissionChecker('physicalcondition_view') || permissionChecker('physicalcondition_edit') || permissionChecker('physicalcondition_delete')) { ?>
                        <td data-title="<?=$this->lang->line('physicalcondition_action')?>">
                            <?=btn_modal_view('physicalcondition/view', $physicalcondition->heightweightbpID, $this->lang->line('physicalcondition_view'))?>
                            <?=btn_edit('physicalcondition/edit/'.$physicalcondition->heightweightbpID, $this->lang->line('physicalcondition_edit'))?>
                            <?=btn_delete('physicalcondition/delete/'.$physicalcondition->heightweightbpID, $this->lang->line('physicalcondition_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($physicalconditions)) { if(permissionChecker('physicalcondition_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('physicalcondition_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('physicalcondition_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>