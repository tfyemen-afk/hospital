<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('designation_slno')?></th>
                <th><?=$this->lang->line('designation_designation')?></th>
                <?php if(permissionChecker('designation_edit') || permissionChecker('designation_delete')) { ?>
                    <th><?=$this->lang->line('designation_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($designations)) { foreach($designations as $designation) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('designation_slno')?>"><?=$i;?></td>
                    <td data-title="<?=$this->lang->line('designation_designation')?>"><?=$designation->designation; ?></td>
                    <?php if(permissionChecker('designation_edit') || permissionChecker('designation_delete')) { ?>
                        <td data-title="<?=$this->lang->line('designation_action')?>">
                            <?=btn_edit('designation/edit/'.$designation->designationID, $this->lang->line('designation_edit'))?>
                            <?php if(!in_array($designation->designationID, $this->notdeleteArray)) {
                                echo btn_delete('designation/delete/'.$designation->designationID, $this->lang->line('designation_delete'));
                            } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>