<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-damageandexpire"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_damageandexpire')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('damageandexpire_add')) { ?>
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
                                <div class="form-group <?=form_error('type') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="type"><?=$this->lang->line('damageandexpire_type')?><span class="text-danger"> *</span></label>
                                     <?php
                                        $typeArray['0'] = "— ".$this->lang->line('damageandexpire_please_select')." —";
                                        $typeArray['1'] = $this->lang->line('damageandexpire_damage');
                                        $typeArray['2'] = $this->lang->line('damageandexpire_expire');
                                        
                                        $errorClass = form_error('type') ? 'is-invalid' : '';
                                        echo form_dropdown('type', $typeArray,  set_value('type'), ' class="form-control select2 '.$errorClass.'" id="type"'); ?>
                                    <span id="type-error"><?=form_error('type')?></span>
                                </div>
                                <div class="form-group <?=form_error('medicinecategoryID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="medicinecategoryID"><?=$this->lang->line('damageandexpire_category')?><span class="text-danger"> *</span></label>
                                     <?php
                                        $categoryArray['0'] = "— ".$this->lang->line('damageandexpire_please_select')." —";
                                        if(inicompute($medicinecategorys)) {
                                            foreach ($medicinecategorys as $medicinecategory) {
                                                $categoryArray[$medicinecategory->medicinecategoryID] = $medicinecategory->name;
                                            }
                                        }
                                        $errorClass = form_error('medicinecategoryID') ? 'is-invalid' : '';
                                        echo form_dropdown('medicinecategoryID', $categoryArray,  set_value('medicinecategoryID'), ' class="form-control select2 '.$errorClass.'" id="medicinecategoryID"'); ?>
                                    <span id="medicinecategoryID-error"><?=form_error('medicinecategoryID')?></span>
                                </div>
                                <div class="form-group <?=form_error('medicineID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="medicineID"><?=$this->lang->line('damageandexpire_medicine')?><span class="text-danger"> *</span></label>
                                     <?php
                                        $medicineArray['0'] = "— ".$this->lang->line('damageandexpire_please_select')." —";
                                        if(inicompute($medicineitems)) {
                                            foreach ($medicineitems as $medicineitem) {
                                                $medicineArray[$medicineitem->medicineID] = $medicineitem->name;
                                            }
                                        }
                                        $errorClass = form_error('medicineID') ? 'is-invalid' : '';
                                        echo form_dropdown('medicineID', $medicineArray,  set_value('medicineID'), ' class="form-control select2 '.$errorClass.'" id="medicineID"'); ?>
                                    <span id="medicineID-error"><?=form_error('medicineID')?></span>
                                </div>
                                <div class="form-group <?=form_error('batchID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="batchID"><?=$this->lang->line('damageandexpire_batchID')?><span class="text-danger"> *</span></label>
                                     <?php
                                        $batchArray['0'] = "— ".$this->lang->line('damageandexpire_please_select')." —";
                                        if(inicompute($medicinepurchaseitems)) {
                                            foreach ($medicinepurchaseitems as $medicinepurchaseitem) {
                                                $batchArray[$medicinepurchaseitem->batchID] = $medicinepurchaseitem->batchID;
                                            }
                                        }
                                        $errorClass = form_error('batchID') ? 'is-invalid' : '';
                                        echo form_dropdown('batchID', $batchArray,  set_value('batchID'), ' class="form-control select2 '.$errorClass.'" id="batchID"'); ?>
                                    <span id="batchID-error"><?=form_error('batchID')?></span>
                                </div>
                                <div class="form-group <?=form_error('quantity') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="quantity"><?=$this->lang->line('damageandexpire_quantity')?><span class="text-danger"> *</span></label>
                                    <input type="text" name="quantity" value="<?=set_value('quantity')?>" class="form-control <?=form_error('quantity') ? 'is-invalid' : ''?>">
                                    <span id="quantity-error"><?=form_error('quantity')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary" id="damageandexpireAdd"><?=$this->lang->line('damageandexpire_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('damageandexpire_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('damageandexpire/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>