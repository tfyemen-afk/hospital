<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-billlabel"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_billlabel')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('billlabel_add')) { ?>
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
                                <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="name"><?=$this->lang->line('billlabel_name')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name')?>">
                                    <span><?=form_error('name')?></span>
                                </div>
                                <div class="form-group <?=form_error('billcategoryID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="billcategoryID"><?=$this->lang->line('billlabel_bill_category')?><span class="text-danger"> *</span></label>
                                        <?php
                                        $billcategoryArray['0'] = '— '.$this->lang->line('billlabel_please_select').' —';
                                        if(inicompute($billcategorys)) {
                                            foreach ($billcategorys as $billcategoryID=> $billcategoryName) {
                                                $billcategoryArray[$billcategoryID] = $billcategoryName;
                                            }
                                        }
                                        $errorClass = form_error('billcategoryID') ? 'is-invalid' : '';
                                        echo form_dropdown('billcategoryID', $billcategoryArray,  set_value('billcategoryID'), ' class="form-control select2 '.$errorClass.'"')?>
                                    <span><?=form_error('billcategoryID')?></span>
                                </div>
                                <div class="form-group <?=form_error('discount') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="discount"><?=$this->lang->line('billlabel_discount')?>(%)</label>
                                    <input type="text" class="form-control <?=form_error('discount') ? 'is-invalid' : '' ?>" id="discount" name="discount"  value="<?=set_value('discount')?>">
                                    <span><?=form_error('discount')?></span>
                                </div>
                                <div class="form-group <?=form_error('amount') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="amount"><?=$this->lang->line('billlabel_amount')?><span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control <?=form_error('amount') ? 'is-invalid' : '' ?>" id="amount" name="amount"  value="<?=set_value('amount')?>">
                                    <span><?=form_error('amount')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('billlabel_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('billlabel_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('billlabel/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

