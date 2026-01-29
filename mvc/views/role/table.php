<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('role_slno')?></th>
                <th><?=$this->lang->line('role_role')?></th>
                <?php if(permissionChecker('role_edit') || permissionChecker('role_delete')) { ?>
                    <th><?=$this->lang->line('role_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($roles)) { foreach($roles as $role) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('role_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('role_role')?>"><?=$role->role;?></td>
                    <?php if(permissionChecker('role_edit') || permissionChecker('role_delete')) { ?>
                        <td data-title="<?=$this->lang->line('role_action')?>">
                            <?=btn_edit('role/edit/'.$role->roleID, $this->lang->line('role_edit'))?>
                            <?php 
                                if(!in_array($role->roleID, $notdeleteArray)) {
                                    echo btn_delete('role/delete/'.$role->roleID, $this->lang->line('role_delete'));
                                }
                            ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>