<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('medicinewarehouse_slno')?></th>
                <th><?=$this->lang->line('medicinewarehouse_name')?></th>
                <th><?=$this->lang->line('medicinewarehouse_code')?></th>
                <th><?=$this->lang->line('medicinewarehouse_email')?></th>
                <?php if(permissionChecker('medicinewarehouse_edit') || permissionChecker('medicinewarehouse_delete')) { ?>
                    <th><?=$this->lang->line('medicinewarehouse_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($medicinewarehouses)) { foreach($medicinewarehouses as $medicinewarehouse) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('medicinewarehouse_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('medicinewarehouse_name')?>"><?=$medicinewarehouse->name;?></td>
                    <td data-title="<?=$this->lang->line('medicinewarehouse_code')?>"><?=$medicinewarehouse->code;?></td>
                    <td data-title="<?=$this->lang->line('medicinewarehouse_email')?>"><?=$medicinewarehouse->email;?></td>
                    <?php if(permissionChecker('medicinewarehouse_view') ||  permissionChecker('medicinewarehouse_edit') || permissionChecker('medicinewarehouse_delete')) { ?>
                        <td data-title="<?=$this->lang->line('medicinewarehouse_action')?>">
                            <?=btn_modal_view('medicinewarehouse/view', $medicinewarehouse->medicinewarehouseID, $this->lang->line('medicinewarehouse_view'))?>
                            <?=btn_edit('medicinewarehouse/edit/'.$medicinewarehouse->medicinewarehouseID, $this->lang->line('medicinewarehouse_edit'))?>
                            <?=btn_delete('medicinewarehouse/delete/'.$medicinewarehouse->medicinewarehouseID, $this->lang->line('medicinewarehouse_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($medicinewarehouses)) { if(permissionChecker('medicinewarehouse_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('medicinewarehouse_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('medicinewarehouse_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } } ?>