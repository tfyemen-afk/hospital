<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('tpa_slno')?></th>
                <th><?=$this->lang->line('tpa_name')?></th>
                <th><?=$this->lang->line('tpa_code')?></th>
                <th><?=$this->lang->line('tpa_phone')?></th>
                <?php if(permissionChecker('tpa_view') || permissionChecker('tpa_edit') || permissionChecker('tpa_delete')) { ?>
                    <th><?=$this->lang->line('tpa_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($tpas)) {foreach($tpas as $tpa) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('tpa_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('tpa_name')?>">
                        <?=$tpa->name;?>
                    </td>
                    <td data-title="<?=$this->lang->line('tpa_code')?>">
                        <?=$tpa->code;?>
                    </td>
                    <td data-title="<?=$this->lang->line('tpa_phone')?>">
                        <?=$tpa->phone;?>
                    </td>
                    <?php if(permissionChecker('tpa_view') || permissionChecker('tpa_edit') || permissionChecker('tpa_delete')) { ?>
                        <td data-title="<?=$this->lang->line('tpa_action')?>">
                            <?=btn_modal_view('tpa/view', $tpa->tpaID, $this->lang->line('tpa_view'))?>
                            <?=btn_edit('tpa/edit/'.$tpa->tpaID, $this->lang->line('tpa_edit'))?>
                            <?=btn_delete('tpa/delete/'.$tpa->tpaID, $this->lang->line('tpa_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($tpas)) { if(permissionChecker('tpa_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('tpa_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('tpa_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>
