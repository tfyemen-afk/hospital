<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('makepayment_slno')?></th>
                <th><?=$this->lang->line('makepayment_photo')?></th>
                <th><?=$this->lang->line('makepayment_name')?></th>
                <th><?=$this->lang->line('makepayment_designation')?></th>
                <th><?=$this->lang->line('makepayment_email')?></th>
                <th><?=$this->lang->line('makepayment_action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($users)) { foreach($users as $user) { $i++;
                if(!isset($managesalarys[$user->userID])) {
                    continue;
                }
                 ?>
                <tr>
                    <td data-title="<?=$this->lang->line('makepayment_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('makepayment_photo')?>">
                        <img class="img-responsive table-image-size" src="<?=imagelink($user->photo, '/uploads/user/') ?>"/>
                    </td>
                    <td data-title="<?=$this->lang->line('makepayment_name')?>"><?=$user->name?></td>
                    <td data-title="<?=$this->lang->line('makepayment_designation')?>"><?=isset($designations[$user->designationID]) ? $designations[$user->designationID] : ''?></td>
                    <td data-title="<?=$this->lang->line('makepayment_email')?>"><?=$user->email?></td>
                    <?php if(permissionChecker('makepayment')) { ?>
                        <td data-title="<?=$this->lang->line('makepayment_action')?>"><?=btn_invoice('makepayment/add/'.$user->userID, $this->lang->line('makepayment_make_payment'))?></td>
                    <?php } ?>
                </tr>
            <?php } }  ?>
        </tbody>
    </table>
</div>