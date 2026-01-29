<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('department_slno')?></th>
                <th><?=$this->lang->line('department_name')?></th>
                <th><?=$this->lang->line('department_description')?></th>
                <?php if(permissionChecker('department_edit') || permissionChecker('department_delete')) { ?>
                    <th><?=$this->lang->line('department_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($departments)) { foreach($departments as $department) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('department_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('department_name')?>"><?=namesorting($department->name, 25)?></td>
                    <td data-title="<?=$this->lang->line('department_description')?>"><?=namesorting($department->description, 25)?></td>
                    <?php if(permissionChecker('department_edit') || permissionChecker('department_delete')) { ?>
                        <td data-title="<?=$this->lang->line('department_action')?>">
                            <?=btn_view('department/view/'.$department->departmentID, $this->lang->line('department_view'))?>
                            <?=btn_edit('department/edit/'.$department->departmentID, $this->lang->line('department_edit'))?>
                            <?=btn_delete('department/delete/'.$department->departmentID, $this->lang->line('department_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>