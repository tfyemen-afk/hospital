<?php if($loginroleID != 3) { ?>
<div class="btn-group pull-right">
    <?php if(isset($testID) && (int)$testID) { ?>
        <a href="<?=site_url('test/edit/'.$testID.'/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('test_today')?></a>
        <a href="<?=site_url('test/edit/'.$testID.'/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('test_month')?></a>
        <a href="<?=site_url('test/edit/'.$testID.'/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('test_year')?></a>
        <a href="<?=site_url('test/edit/'.$testID.'/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('test_all')?></a>
    <?php } else { ?>
        <a href="<?=site_url('test/index/1')?>" class="btn btn-secondary <?=($displayID == 1) ? 'active' : ''?>"><?=$this->lang->line('test_today')?></a>
        <a href="<?=site_url('test/index/2')?>" class="btn btn-secondary <?=($displayID == 2) ? 'active' : ''?>"><?=$this->lang->line('test_month')?></a>
        <a href="<?=site_url('test/index/3')?>" class="btn btn-secondary <?=($displayID == 3) ? 'active' : ''?>"><?=$this->lang->line('test_year')?></a>
        <a href="<?=site_url('test/index/4')?>" class="btn btn-secondary <?=($displayID == 4) ? 'active' : ''?>"><?=$this->lang->line('test_all')?></a>
    <?php } ?>
</div>
<br>
<br>
<?php } ?>
<div id="hide-table">
    <table class="table table-striped table-bordered table-hover example">
        <thead>
            <tr>
                <th><?=$this->lang->line('test_slno')?></th>
                <th><?=$this->lang->line('test_test_name')?></th>
                <th><?=$this->lang->line('test_category')?></th>
                <th><?=$this->lang->line('test_date')?></th>
                <th><?=$this->lang->line('test_bill_no')?></th>
                <?php if(permissionChecker('test_view') || permissionChecker('test_edit') || permissionChecker('test_delete')) { ?>
                    <th><?=$this->lang->line('test_action')?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php $i = 0; if(inicompute($tests)) {foreach($tests as $test) { $i++; ?>
                <tr>
                    <td data-title="<?=$this->lang->line('test_slno')?>">
                        <?=$i;?>
                    </td>
                    <td data-title="<?=$this->lang->line('test_test_name')?>">
                        <?=isset($testlabels[$test->testlabelID]) ? $testlabels[$test->testlabelID] : "&nbsp"?>
                    </td>
                    <td data-title="<?=$this->lang->line('test_category')?>">
                        <?=isset($testcategorys[$test->testcategoryID]) ? $testcategorys[$test->testcategoryID] : "&nbsp"?>
                    </td>
                    <td data-title="<?=$this->lang->line('test_date')?>">
                        <?=app_date($test->create_date, false)?>
                    </td>
                    <td data-title="<?=$this->lang->line('test_bill_no')?>">
                        <?=$test->billID?>
                    </td>
                    <?php if(permissionChecker('test_view') || permissionChecker('test_edit') || permissionChecker('test_delete')) { ?>
                        <td data-title="<?=$this->lang->line('test_action')?>">
                            <?=btn_modal_view('test/view', $test->testID, $this->lang->line('test_view'))?>
                            <?php if(permissionChecker('test_view')) { ?>
                                <button id="<?=$test->testID?>" data-toggle="modal" data-target="#uploadModal" class="btn btn-primary btn-custom mrg uploadModalBtn" ><span class="fa fa-upload" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('test_upload')?>"></span></button>
                            <?php } ?>
                            <?=btn_edit('test/edit/'.$test->testID.'/'.$displayID, $this->lang->line('test_edit'))?>
                            <?=btn_delete('test/delete/'.$test->testID.'/'.$displayID, $this->lang->line('test_delete'))?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<?php if(permissionChecker('test_view')) { ?>
    <div class="modal" id="viewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">               
                        <?=$this->lang->line('test_view_test')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body viewTestModal">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('test_close')?></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="uploadModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="mdoal-title">               
                        <?=$this->lang->line('test_test_upload')?>
                    </h6>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?php if(permissionChecker('test_add')) { ?>
                        <form role="form" method="POST" id="test_file">
                            <div class="row">
                                <input type="hidden" name="testID" id="testID">
                                <div class="form-group col-md-10 <?=form_error('file') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="file"><?=$this->lang->line('test_file')?></label>
                                    <div class="custom-file">
                                        <input type="file" name="file" class="custom-file-input file-upload-input <?=form_error('file') ? 'is-invalid' : '' ?>" id="fileuploadinput">
                                        <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('test_choose_file')?></label>
                                    </div>
                                    <span id="fileuploadinput-error"><?=form_error('file')?></span>
                                </div>
                                <div class="form-group col-md-2 <?=form_error('file') ? 'text-danger' : '' ?>">
                                    <button type="submit" class="btn btn-primary upload" id="save_test_file"><?=$this->lang->line('test_upload')?></button>
                                </div>
                            </div>
                        </form>
                    <?php } ?>

                    <div id="modalBodyTable">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('test_close')?></button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>