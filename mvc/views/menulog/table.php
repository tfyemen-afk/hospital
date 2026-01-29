<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('menulog_slno')?></th>
                <th><?=$this->lang->line('menulog_name')?></th>
                <th><?=$this->lang->line('menulog_parent_menu')?></th>
                <th><?=$this->lang->line('menulog_status')?></th>
                <th><?=$this->lang->line('menulog_action')?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($menulogs)) { foreach($menulogs as $menulog) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('menulog_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('menulog_name')?>"><?=$menulog->name?></td>
                    <td data-title="<?=$this->lang->line('menulog_parent_menu')?>">
                        <?=isset($menulogs[$menulog->parentID]) ? $menulogs[$menulog->parentID]->name : '&nbsp;'?>
                    </td>
                    <td data-title="<?=$this->lang->line('menulog_status')?>">
                        <div class="on-off-switch-small" id="<?=$menulog->menulogID?>">
                          <input type="checkbox" id="myonoffswitch<?=$menulog->menulogID?>" class="on-off-switch-small-checkbox" <?=($menulog->status === '1') ? 'checked' : ''?>>
                          <label for="myonoffswitch<?=$menulog->menulogID?>" class="on-off-switch-small-label">
                              <span class="on-off-switch-small-inner"></span>
                              <span class="on-off-switch-small-switch"></span>
                          </label>
                        </div>
                    </td>
                    <td class="center" data-title="<?=$this->lang->line('menulog_action')?>">
                        <?=btn_edit_show('menulog/edit/'.$menulog->menulogID, $this->lang->line('menulog_edit'))?>
                        <?=btn_delete_show('menulog/delete/'.$menulog->menulogID, $this->lang->line('menulog_delete'))?>
                    </td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>