<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th><?=$this->lang->line('test_slno')?></th>
            <th><?=$this->lang->line('test_file_name')?></th>
            <?php if(permissionChecker('test_view') || permissionChecker('test_delete')) { ?>
                <th><?=$this->lang->line('test_action')?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php $i = 0; if(inicompute($testfiles)) {foreach($testfiles as $testfile) { $i++; ?>
            <tr>
                <td data-title="<?=$this->lang->line('test_slno')?>">
                    <?=$i;?>
                </td>
                <td data-title="<?=$this->lang->line('test_file_name')?>">
                    <?=$testfile->fileoriginalname;?>
                </td>
                <?php if(permissionChecker('test_view') || permissionChecker('test_delete')) { ?>
                    <td data-title="<?=$this->lang->line('test_action')?>">
                        <?=btn_download('test/filedownload/'.$testfile->testfileID, $this->lang->line('test_download'))?>
                        <?=btn_delete_show('test/filedelete/'.$testfile->testfileID, $this->lang->line('test_delete'))?>
                    </td>
                <?php } ?>
            </tr>
        <?php } } ?>
    </tbody>
</table>