<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
        <tr>
            <th><?= $this->lang->line('user_slno') ?></th>
            <th><?= $this->lang->line('user_photo') ?></th>
            <th><?= $this->lang->line('user_name') ?></th>
            <th><?= $this->lang->line('user_designation') ?></th>
            <?php if ( permissionChecker('user_edit') ) { ?>
                <th><?= $this->lang->line('user_status') ?></th>
            <?php } ?>
            <?php if ( permissionChecker('user_edit') || permissionChecker('user_delete') || permissionChecker('user_view') ) { ?>
                <th><?= $this->lang->line('user_action') ?></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php $i = 0;
            if ( inicompute($users) ) {
                foreach ( $users as $user ) {
                    $i++;
                    if ( $user->userID == 1 ) {
                        continue;
                    } ?>
                    <tr>
                        <td data-title="<?= $this->lang->line('user_slno') ?>"><?= $i ?></td>
                        <td data-title="<?= $this->lang->line('user_photo') ?>">
                            <img class="img-responsive table-image-size" src="<?= imagelink($user->photo,
                                '/uploads/user/') ?>"/>
                        </td>
                        <td data-title="<?= $this->lang->line('user_name') ?>"><?= namesorting($user->name, 40) ?></td>
                        <td data-title="<?= $this->lang->line('user_designation') ?>"><?= isset($designations[ $user->designationID ]) ? $designations[ $user->designationID ] : '' ?></td>
                        <?php if ( permissionChecker('user_edit') ) { ?>
                            <td data-title="<?= $this->lang->line('user_status') ?>">
                                <div class="on-off-switch-small" id="<?= $user->userID ?>">
                                    <input type="checkbox" id="myonoffswitch<?= $user->userID ?>"
                                           class="on-off-switch-small-checkbox" <?= ( $user->status === '1' ) ? 'checked' : '' ?>>
                                    <label for="myonoffswitch<?= $user->userID ?>" class="on-off-switch-small-label">
                                        <span class="on-off-switch-small-inner"></span>
                                        <span class="on-off-switch-small-switch"></span>
                                    </label>
                                </div>
                            </td>
                        <?php } ?>

                        <?php if ( permissionChecker('user_edit') || permissionChecker('user_delete') || permissionChecker('user_view') ) { ?>
                            <td data-title="<?= $this->lang->line('user_action') ?>">
                                <?= btn_view('user/view/' . $user->userID, $this->lang->line('user_view')) ?>
                                <?= btn_edit('user/edit/' . $user->userID, $this->lang->line('user_edit')) ?>
                                <?= btn_delete('user/delete/' . $user->userID, $this->lang->line('user_delete')) ?>
                            </td>
                        <?php } ?>
                    </tr>
                <?php }
            } ?>
        </tbody>
    </table>
</div>