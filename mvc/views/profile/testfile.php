<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th><?=$this->lang->line('profile_slno')?></th>
        <th><?=$this->lang->line('profile_filename')?></th>
        <th><?=$this->lang->line('profile_action')?></th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 0; if(inicompute($testfiles)) { foreach($testfiles as $testfile) { $i++; ?>
        <tr>
            <td data-title="<?=$this->lang->line('profile_slno')?>">
                <?=$i;?>
            </td>
            <td data-title="<?=$this->lang->line('profile_filename')?>">
                <?=$testfile->fileoriginalname;?>
            </td>
            <td data-title="<?=$this->lang->line('profile_action')?>">
                <?=btn_download('profile/filedownload/'.$testfile->testfileID, $this->lang->line('profile_download'))?>
            </td>
        </tr>
    <?php } } else { ?> 
        <tr><td colspan="3"><?=$this->lang->line('profile_data_not_found')?></td></tr>
    <?php } ?>
    </tbody>
</table>