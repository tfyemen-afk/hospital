<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('itemcheckout_slno')?></th>
                <th><?=$this->lang->line('itemcheckout_item')?></th>
                <th><?=$this->lang->line('itemcheckout_issue_date')?></th>
                <th><?=$this->lang->line('itemcheckout_quantity')?></th>
                <th><?=$this->lang->line('itemcheckout_status')?></th>
                <?php if(permissionChecker('itemcheckout_view') || permissionChecker('itemcheckout_edit') || permissionChecker('itemcheckout_delete')) { ?>
                    <th><?=$this->lang->line('itemcheckout_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($itemcheckouts)) {foreach($itemcheckouts as $itemcheckout) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('itemcheckout_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('itemcheckout_item')?>"><?=isset($items[$itemcheckout->itemID]) ? $items[$itemcheckout->itemID]->name : ''?></td>
                    <td data-title="<?=$this->lang->line('itemcheckout_issue_date')?>"><?=app_datetime($itemcheckout->issuedate)?></td>
                    <td data-title="<?=$this->lang->line('itemcheckout_quantity')?>"><?=$itemcheckout->quantity?></td>
                    <td data-title="<?=$this->lang->line('itemcheckout_status')?>">
                        <?php 
                            if($itemcheckout->status) {
                                echo $this->lang->line('itemcheckout_return');
                            } elseif(strtotime(date('d-m-Y H:i A')) > strtotime($itemcheckout->returndate)) {
                                echo $this->lang->line('itemcheckout_overdue');
                            } else {
                                echo $this->lang->line('itemcheckout_due');
                            }
                        ?>
                    </td>
                    <?php if(permissionChecker('itemcheckout_view') || permissionChecker('itemcheckout_edit') || permissionChecker('itemcheckout_delete')) { ?>
                        <td data-title="<?=$this->lang->line('itemcheckout_action')?>">
                            <?=btn_modal_view('itemcheckout/view', $itemcheckout->itemcheckoutID, $this->lang->line('itemcheckout_view'))?>
                            <?php 
                                if($itemcheckout->status == 0) {
                                    echo btn_custom('itemcheckout_edit', site_url('itemcheckout/itemreturn/'.$itemcheckout->itemcheckoutID), $this->lang->line('itemcheckout_return'), 'fa fa-mail-forward', 'btn-primary', TRUE, 'you are about to return the record. This cannot be undone. are you sure?');
                                }
                            ?>
                            <?=btn_edit('itemcheckout/edit/'.$itemcheckout->itemcheckoutID, $this->lang->line('itemcheckout_edit'))?>
                            <?=btn_delete('itemcheckout/delete/'.$itemcheckout->itemcheckoutID, $this->lang->line('itemcheckout_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($itemcheckouts) && permissionChecker('itemcheckout_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('itemcheckout_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('itemcheckout_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>