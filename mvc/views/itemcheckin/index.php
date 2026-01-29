<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-itemcheckin"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_itemcheckin')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('itemcheckin_add')) { ?>
                <div class="col-sm-4">
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="header-block">
                                <p class="title"> <i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_add')?></p>
                            </div>
                        </div>
                        <form role="form" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="card-block">
                                <div class="form-group <?=form_error('categoryID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="categoryID"><?=$this->lang->line('itemcheckin_category')?></label>
                                        <?php
                                        $itemcategoryArray['0'] = "— ".$this->lang->line('itemcheckin_please_select')." —";
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
                                    <label class="control-label" for="itemID"><?=$this->lang->line('itemcheckin_item')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $itemArray['0'] = "— ".$this->lang->line('itemcheckin_please_select')." —";
                                        if(inicompute($itempotions)) {
                                            foreach ($itempotions as $itempotion) {
                                                $itemArray[$itempotion->itemID] = $itempotion->name;
                                            }
                                        }
                                        $errorClass = form_error('itemID') ? 'is-invalid' : '';
                                        echo form_dropdown('itemID', $itemArray,  set_value('itemID', $itemsetter), ' id="itemID" class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('itemID')?></span>
                                </div>
                                <div class="form-group <?=form_error('supplierID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="supplierID"><?=$this->lang->line('itemcheckin_supplier')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $itemsupplierArray['0'] = "— ".$this->lang->line('itemcheckin_please_select')." —";
                                        if(inicompute($itemsuppliers)) {
                                            foreach ($itemsuppliers as $itemsupplier) {
                                                $itemsupplierArray[$itemsupplier->itemsupplierID] = $itemsupplier->companyname;
                                            }
                                        }
                                        $errorClass = form_error('supplierID') ? 'is-invalid' : '';
                                        echo form_dropdown('supplierID', $itemsupplierArray,  set_value('supplierID'), ' class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('supplierID')?></span>
                                </div>
                                <div class="form-group <?=form_error('storeID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="storeID"><?=$this->lang->line('itemcheckin_store')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $storeArray['0'] = "— ".$this->lang->line('itemcheckin_please_select')." —";
                                        if(inicompute($itemstores)) {
                                            foreach ($itemstores as $itemstore) {
                                                $storeArray[$itemstore->itemstoreID] = $itemstore->name;
                                            }
                                        }
                                        $errorClass = form_error('storeID') ? 'is-invalid' : '';
                                        echo form_dropdown('storeID', $storeArray,  set_value('storeID'), ' class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('storeID')?></span>
                                </div>
                                <div class="form-group <?=form_error('quantity') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="quantity"><?=$this->lang->line('itemcheckin_quantity')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('quantity') ? 'is-invalid' : '' ?>" id="quantity" name="quantity"  value="<?=set_value('quantity')?>">
                                    <span><?=form_error('quantity')?></span>
                                </div>
                                <div class="form-group <?=form_error('date') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="date"><?=$this->lang->line('itemcheckin_date')?><span class="text-danger"> *</span></label>
                                    <input type="text" autocomplete="off" class="form-control <?=form_error('date') ? 'is-invalid' : '' ?>" id="date" name="date"  value="<?=set_value('date')?>">
                                    <span><?=form_error('date')?></span>
                                </div>
                                <div class="form-group <?=form_error('description') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="description"><?=$this->lang->line('itemcheckin_description')?></label>
                                    <textarea type="text" class="form-control <?=form_error('description') ? 'is-invalid' : '' ?>" id="description" name="description" rows="3"><?=set_value('description')?></textarea>
                                    <span><?=form_error('description')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('itemcheckin_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('itemcheckin_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('itemcheckin/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>