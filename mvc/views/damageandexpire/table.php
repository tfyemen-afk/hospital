<div class="btn-group pull-right">
<?php if(isset($maindamageandexpireID) && (int)$maindamageandexpireID) {?>
    <a href="<?=site_url('damageandexpire/edit/'.$maindamageandexpireID.'/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('damageandexpire_today')?></a>
    <a href="<?=site_url('damageandexpire/edit/'.$maindamageandexpireID.'/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('damageandexpire_month')?></a>
    <a href="<?=site_url('damageandexpire/edit/'.$maindamageandexpireID.'/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('damageandexpire_year')?></a>
    <a href="<?=site_url('damageandexpire/edit/'.$maindamageandexpireID.'/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('damageandexpire_all')?></a>
<?php } else { ?>
    <a href="<?=site_url('damageandexpire/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('damageandexpire_today')?></a>
    <a href="<?=site_url('damageandexpire/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('damageandexpire_month')?></a>
    <a href="<?=site_url('damageandexpire/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('damageandexpire_year')?></a>
    <a href="<?=site_url('damageandexpire/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('damageandexpire_all')?></a>
<?php } ?>
</div>
<br>
<br>

<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('damageandexpire_slno')?></th>
                <th><?=$this->lang->line('damageandexpire_type')?></th>
                <th><?=$this->lang->line('damageandexpire_medicine')?></th>
                <th><?=$this->lang->line('damageandexpire_quantity')?></th>
                <?php if(permissionChecker('damageandexpire_view') || permissionChecker('damageandexpire_edit') || permissionChecker('damageandexpire_delete')) { ?>
                    <th><?=$this->lang->line('damageandexpire_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($damageandexpires)) {foreach($damageandexpires as $damageandexpire) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('damageandexpire_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('damageandexpire_type')?>">
                        <?=($damageandexpire->type == 1) ? $this->lang->line('damageandexpire_damage') : $this->lang->line('damageandexpire_expire')?>
                    </td>
                    <td data-title="<?=$this->lang->line('damageandexpire_medicine')?>">
                        <?=isset($medicines[$damageandexpire->medicineID]) ? $medicines[$damageandexpire->medicineID] : "&nbsp"?>
                    </td>
                    <td data-title="<?=$this->lang->line('damageandexpire_quantity')?>">
                        <?=$damageandexpire->quantity?>
                    </td>
                    <?php if(permissionChecker('damageandexpire_view') || permissionChecker('damageandexpire_edit') || permissionChecker('damageandexpire_delete')) { ?>
                        <td data-title="<?=$this->lang->line('damageandexpire_action')?>">
                            <?=btn_modal_view('damageandexpire/view', $damageandexpire->damageandexpireID, $this->lang->line('damageandexpire_view'))?>
                            <?=btn_edit('damageandexpire/edit/'.$damageandexpire->damageandexpireID.'/'.$displayID, $this->lang->line('damageandexpire_edit'))?>
                            <?=btn_delete('damageandexpire/delete/'.$damageandexpire->damageandexpireID.'/'.$displayID, $this->lang->line('damageandexpire_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(inicompute($damageandexpires) && permissionChecker('damageandexpire_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">
                        <?=$this->lang->line('damageandexpire_view')?> <?=$this->lang->line('panel_title')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('damageandexpire_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>