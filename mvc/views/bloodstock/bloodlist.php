<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('bloodstock_slno')?></th>
                <th><?=$this->lang->line('bloodstock_blood_group')?></th>
                <th><?=$this->lang->line('bloodstock_total')?></th>
                <th><?=$this->lang->line('bloodstock_reserves')?></th>
                <th><?=$this->lang->line('bloodstock_release')?></th>
                <?php if(permissionChecker('bloodstock_edit') || permissionChecker('bloodstock_delete')) { ?>
                    <th><?=$this->lang->line('bloodstock_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($bloodgroups)) { foreach($bloodgroups as $bloodgroup) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('bloodstock_slno')?>"><?=$i;?></td>
                    <td data-title="<?=$this->lang->line('bloodstock_blood_group')?>"><?=$bloodgroup->bloodgroup;?></td>
                    <td data-title="<?=$this->lang->line('bloodstock_total')?>">
                        <?=isset($bloodstocks[$bloodgroup->bloodgroupID]) ? $bloodstocks[$bloodgroup->bloodgroupID]['total'] : '0'?>
                    </td>
                    <td data-title="<?=$this->lang->line('bloodstock_reserves')?>">
                        <?=isset($bloodstocks[$bloodgroup->bloodgroupID]) ? $bloodstocks[$bloodgroup->bloodgroupID]['reserve'] : '0'?>
                    </td>
                    <td data-title="<?=$this->lang->line('bloodstock_release')?>">
                        <?=isset($bloodstocks[$bloodgroup->bloodgroupID]) ? $bloodstocks[$bloodgroup->bloodgroupID]['release'] : '0'?>
                    </td>
                    <?php if(permissionChecker('bloodstock_edit') || permissionChecker('bloodstock_delete')) { ?>
                        <td data-title="<?=$this->lang->line('bloodstock_action')?>">
                            <?=btn_view('bloodstock/view/'.$bloodgroup->bloodgroupID, $this->lang->line('bloodstock_view'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>