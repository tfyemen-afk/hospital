<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-itemcheckout"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('itemcheckout/index')?>"><?=$this->lang->line('menu_itemcheckout')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('itemcheckout_edit')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-4">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-edit"></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('userID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="userID"><?=$this->lang->line('itemcheckout_user')?><span class="text-danger"> *</span></label>
                                    <?php
                                    $userArray['0'] = "— ".$this->lang->line('itemcheckout_please_select')." —";
                                    if(inicompute($users)) {
                                        foreach ($users as $user) {
                                            if(isset($designations[$user->designationID])) {
                                                $userArray[$user->userID] = $user->name.' - '.($designations[$user->designationID]);
                                            } else {
                                                $userArray[$user->userID] = $user->name;
                                            }
                                        }
                                    }
                                    $errorClass = form_error('userID') ? 'is-invalid' : '';
                                    echo form_dropdown('userID', $userArray,  set_value('userID', $itemcheckout->userID), ' id="userID" class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('userID')?></span>
                            </div>
                            <div class="form-group <?=form_error('issuedate') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="issuedate"><?=$this->lang->line('itemcheckout_issue_date')?><span class="text-danger"> *</span></label>
                                <input type="text" autocomplete="off" class="form-control datetimepicker <?=form_error('issuedate') ? 'is-invalid' : '' ?>" id="issuedate" name="issuedate"  value="<?=set_value('issuedate', date('d-m-Y h:i A', strtotime($itemcheckout->issuedate)))?>">
                                <span><?=form_error('issuedate')?></span>
                            </div>
                            <div class="form-group <?=form_error('returndate') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="returndate"><?=$this->lang->line('itemcheckout_return_date')?><span class="text-danger"> *</span></label>
                                <input type="text" autocomplete="off" class="form-control datetimepicker <?=form_error('returndate') ? 'is-invalid' : '' ?>" id="returndate" name="returndate"  value="<?=set_value('returndate', date('d-m-Y h:i A', strtotime($itemcheckout->returndate)))?>">
                                <span><?=form_error('returndate')?></span>
                            </div>
                            <div class="form-group <?=form_error('categoryID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="categoryID"><?=$this->lang->line('itemcheckout_category')?></label>
                                    <?php
                                    $itemcategoryArray['0'] = "— ".$this->lang->line('itemcheckout_please_select')." —";
                                    if(inicompute($itemcategory)) {
                                        foreach ($itemcategory as $category) {
                                            $itemcategoryArray[$category->itemcategoryID] = $category->name;
                                        }
                                    }
                                    $errorClass = form_error('categoryID') ? 'is-invalid' : '';
                                    echo form_dropdown('categoryID', $itemcategoryArray,  set_value('categoryID'), ' id="categoryID" class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('categoryID')?></span>
                            </div>
                            <div class="form-group <?=form_error('itemID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="itemID"><?=$this->lang->line('itemcheckout_item')?><span class="text-danger"> *</span></label>
                                    <?php
                                    $itemArray['0'] = "— ".$this->lang->line('itemcheckout_please_select')." —";
                                    if(inicompute($items)) {
                                        foreach ($items as $item) {
                                            $itemArray[$item->itemID] = $item->name;
                                        }
                                    }
                                    $errorClass = form_error('itemID') ? 'is-invalid' : '';
                                    echo form_dropdown('itemID', $itemArray,  set_value('itemID', $itemcheckout->itemID), ' id="itemID" class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('itemID')?></span>
                            </div>
                            <div class="form-group <?=form_error('quantity') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="quantity"><?=$this->lang->line('itemcheckout_quantity')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('quantity') ? 'is-invalid' : '' ?>" id="quantity" name="quantity"  value="<?=set_value('quantity', $itemcheckout->quantity)?>">
                                <span><?=form_error('quantity')?></span>
                            </div>
                            <div class="form-group <?=form_error('note') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="note"><?=$this->lang->line('itemcheckout_note')?></label>
                                <textarea type="text" class="form-control <?=form_error('note') ? 'is-invalid' : '' ?>" id="note" name="note" rows="3"><?=set_value('note', $itemcheckout->note)?></textarea>
                                <span><?=form_error('note')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('itemcheckout_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('itemcheckout/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>