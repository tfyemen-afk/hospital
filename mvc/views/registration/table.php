<div class="btn-group pull-right">
    <a href="<?=site_url('registration/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('registration_today')?></a>
    <a href="<?=site_url('registration/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('registration_month')?></a>
    <a href="<?=site_url('registration/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('registration_year')?></a>
    <a href="<?=site_url('registration/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('registration_all')?></a>
</div>
<br>
<br>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('registration_slno')?></th>
                <th><?=$this->lang->line('registration_photo')?></th>
                <th><?=$this->lang->line('registration_name')?></th>
                <th><?=$this->lang->line('registration_uhid')?></th>
                <th><?=$this->lang->line('registration_gender')?></th>
                <th><?=$this->lang->line('registration_phone')?></th>
                <?php if(permissionChecker('registration_view') || permissionChecker('registration_edit') || permissionChecker('registration_delete')) { ?>
                    <th><?=$this->lang->line('registration_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php if(inicompute($registrations)) { $i = 1; foreach ($registrations as $registration) { ?>
                <tr>
                    <td data-title="<?=$this->lang->line('registration_slno')?>"><?=$i?></td>
                    <td data-title="<?=$this->lang->line('registration_photo')?>"><img class="img-responsive table-image-size" src="<?=imagelink($registration->photo, 'uploads/user/') ?>"/></td>
                    <td data-title="<?=$this->lang->line('registration_name')?>"><?=$registration->name?></td>
                    <td data-title="<?=$this->lang->line('registration_uhid')?>"><?=$registration->patientID?></td>
                    <td data-title="<?=$this->lang->line('registration_gender')?>"><?=(($registration->gender == 1) ? $this->lang->line('registration_male') : $this->lang->line('registration_female'))?></td>
                    <td data-title="<?=$this->lang->line('registration_phone')?>"><?=$registration->phone?></td>
                    <?php if(permissionChecker('registration_view') || permissionChecker('registration_edit') || permissionChecker('registration_delete')) { ?>
                        <td data-title="<?=$this->lang->line('registration_action')?>">
                            <?=btn_view('registration/view/'.$registration->patientID.'/'.$displayID, $this->lang->line('registration_view'))?>
                            <?=btn_edit('registration/edit/'.$registration->patientID.'/'.$displayID, $this->lang->line('registration_edit'))?>
                            <?=btn_delete('registration/delete/'.$registration->patientID.'/'.$displayID, $this->lang->line('registration_delete'))?>
                            <?php if(permissionChecker('registration_view')) { ?>
                                <a href="<?=site_url('registration/card/'.$registration->patientID.'/'.$displayID)?>" class="btn btn-success btn-custom mrg" data-placement="top" data-toggle="tooltip" title="" data-original-title="<?=$this->lang->line('registration_id_card')?>"><i class="fa fa-id-badge"></i></a>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php $i++; }} ?>
            
        </tbody>
    </table>
</div>