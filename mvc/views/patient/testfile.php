<table class="table table-striped table-bordered table-hover">
    <thead>
    <tr>
        <th><?=$this->lang->line('patient_slno')?></th>
        <th><?=$this->lang->line('patient_filename')?></th>
        <th><?=$this->lang->line('patient_action')?></th>
    </tr>
    </thead>
    <tbody>
    <?php $i = 0; if(inicompute($testfiles)) {foreach($testfiles as $testfile) { $i++; ?>
        <tr>
            <td data-title="<?=$this->lang->line('patient_slno')?>">
                <?=$i;?>
            </td>
            <td data-title="<?=$this->lang->line('patient_filename')?>">
                <?=$testfile->fileoriginalname;?>
            </td>
            <td data-title="<?=$this->lang->line('patient_action')?>">
                <?=btn_download('patient/filedownload/'.$testfile->testfileID, $this->lang->line('patient_download'))?>
            </td>
        </tr>
    <?php } } ?>
    </tbody>
</table>