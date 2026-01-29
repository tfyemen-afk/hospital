<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-medicine"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item"><a href="<?=site_url('medicine/index')?>"><?=$this->lang->line('menu_medicine')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('medicine_edit')?></li>
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
                            <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('medicine_name')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $singlemedicine->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('medicinecategoryID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="medicinecategoryID"><?=$this->lang->line('medicine_category')?><span class="text-danger"> *</span></label>
                                 <?php
                                    $medicinecategoryArray['0'] = '— '.$this->lang->line('medicine_please_select').' —';
                                    if(inicompute($medicinecategorys)) {
                                        foreach ($medicinecategorys as $medicinecategoryKey => $medicinecategory) {
                                            $medicinecategoryArray[$medicinecategoryKey] = $medicinecategory;
                                        }
                                    }
                                    $errorClass = form_error('medicinecategoryID') ? 'is-invalid' : '';
                                    echo form_dropdown('medicinecategoryID', $medicinecategoryArray,  set_value('medicinecategoryID', $singlemedicine->medicinecategoryID), ' class="form-control select2 '.$errorClass.'"'); ?>
                                <span><?=form_error('medicinecategoryID')?></span>
                            </div>
                            <div class="form-group <?=form_error('medicinemanufacturerID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="medicinemanufacturerID"><?=$this->lang->line('medicine_manufacturer')?><span class="text-danger"> *</span></label>
                                 <?php
                                    $medicinemenufactureArray['0'] = '— '.$this->lang->line('medicine_please_select').' —';
                                    if(inicompute($medicinemanufacturers)) {
                                        foreach ($medicinemanufacturers as $medicinemanufacturerKey => $medicinemanufacturer) {
                                            $medicinemenufactureArray[$medicinemanufacturerKey] = $medicinemanufacturer;
                                        }
                                    }
                                    $errorClass = form_error('medicinemanufacturerID') ? 'is-invalid' : '';
                                    echo form_dropdown('medicinemanufacturerID', $medicinemenufactureArray,  set_value('medicinemanufacturerID', $singlemedicine->medicinemanufacturerID), ' class="form-control select2 '.$errorClass.'"'); ?>
                                <span><?=form_error('medicinemanufacturerID')?></span>
                            </div>
                            <div class="form-group <?=form_error('medicineunitID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="medicineunitID"><?=$this->lang->line('medicine_medicineunit')?><span class="text-danger"> *</span></label>
                                 <?php
                                    $medicineunitArray['0'] = '— '.$this->lang->line('medicine_please_select').' —';
                                    if(inicompute($medicineunits)) {
                                        foreach ($medicineunits as $medicineunit) {
                                            $medicineunitArray[$medicineunit->medicineunitID] = $medicineunit->medicineunit;
                                        }
                                    }
                                    $errorClass = form_error('medicineunitID') ? 'is-invalid' : '';
                                    echo form_dropdown('medicineunitID', $medicineunitArray,  set_value('medicineunitID', $singlemedicine->medicineunitID), ' class="form-control select2 '.$errorClass.'"'); ?>
                                <span><?=form_error('medicineunitID')?></span>
                            </div>
                            <div class="form-group <?=form_error('buying_price') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="buying_price"><?=$this->lang->line('medicine_buying_price')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('buying_price') ? 'is-invalid' : '' ?>" id="buying_price" name="buying_price"  value="<?=set_value('buying_price', $singlemedicine->buying_price)?>">
                                <span><?=form_error('buying_price')?></span>
                            </div>
                            <div class="form-group <?=form_error('selling_price') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="selling_price"><?=$this->lang->line('medicine_selling_price')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('selling_price') ? 'is-invalid' : '' ?>" id="selling_price" name="selling_price"  value="<?=set_value('selling_price', $singlemedicine->selling_price)?>">
                                <span><?=form_error('selling_price')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('medicine_update')?></button>
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
                        <?php $this->load->view('medicine/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
                
