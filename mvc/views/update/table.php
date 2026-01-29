<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
        <tr>
            <th><?=$this->lang->line('update_slno')?></th>
            <th><?=$this->lang->line('update_date')?></th>
            <th><?=$this->lang->line('update_version')?></th>
            <th><?=$this->lang->line('update_status')?></th>
            <th><?=$this->lang->line('update_action')?></th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 0; if(inicompute($updates)) {foreach($updates as $update) { $i++; ?>
            <tr>
                <td data-title="<?=$this->lang->line('update_slno')?>">
                    <?=$i?>
                </td>
                <td data-title="<?=$this->lang->line('update_date')?>">
                    <?=date("d M Y", strtotime($update->date));?>
                </td>
                <td data-title="<?=$this->lang->line('update_version')?>">
                    <?=$update->version?>
                </td>
                <td data-title="<?=$this->lang->line('update_status')?>">
                    <?=($update->status) ? '<span class="text-success">'.$this->lang->line('update_success').'</span>' : '<span class="text-danger">'.$this->lang->line('update_failed').'</span>'?>
                </td>
                <td data-title="<?=$this->lang->line('update_action')?>">
                    <a href="#log" id="<?=$update->updateID?>" class="btn btn-success btn-custom mrg getloginfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-check-square-o" data-toggle="tooltip" data-placement="top" data-original-title="<?=$this->lang->line('update_log')?>"></i></a>
                </td>
            </tr>
            <?php }} ?>
        </tbody>
    </table>
</div>

<div class="modal" id="log">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title">
                    <?=$this->lang->line('update_updatelog')?>
                </h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="logcontent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('update_close')?></button>
            </div>
        </div>
    </div>
</div>