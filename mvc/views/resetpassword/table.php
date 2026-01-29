<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('resetpassword_slno')?></th>
                <th><?=$this->lang->line('resetpassword_name')?></th>
                <th><?=$this->lang->line('resetpassword_username')?></th>
                <th><?=$this->lang->line('resetpassword_role')?></th>
                <th><?=$this->lang->line('resetpassword_date')?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($resetpasswords)) {foreach($resetpasswords as $resetpassword) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('resetpassword_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('resetpassword_name')?>">
                        <?=isset($users[$resetpassword->userID]) ? $users[$resetpassword->userID]->name : "&nbsp"?>
                    </td>
                    <td data-title="<?=$this->lang->line('resetpassword_username')?>">
                        <?=isset($users[$resetpassword->userID]) ? $users[$resetpassword->userID]->username : "&nbsp"?>
                    </td>
                    <td data-title="<?=$this->lang->line('resetpassword_role')?>">
                        <?=isset($roles[$resetpassword->roleID]) ? $roles[$resetpassword->roleID]->role : "&nbsp"?>
                    </td>
                    <td data-title="<?=$this->lang->line('resetpassword_date')?>">
                        <?=app_datetime($resetpassword->create_date)?>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>