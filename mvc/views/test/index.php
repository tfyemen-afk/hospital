<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-test"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_test')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <?php if(permissionChecker('test_add')) { ?>
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
                                <div class="form-group <?=form_error('billID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="billID"><?=$this->lang->line('test_bill_no')?><span class="text-danger"> *</span></label>
                                    <?php
                                        $billArray['0'] = "- ".$this->lang->line('test_please_select')." -";
                                        if(inicompute($bills)) {
                                            foreach ($bills as $bill) {
                                                $billArray[$bill->billID] = $bill->billID .' - '. $bill->name;
                                            }
                                        }
                                        $errorClass = form_error('billID') ? 'is-invalid' : '';
                                        echo form_dropdown('billID', $billArray,  set_value('billID'), ' id="billID" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('billID')?></span>
                                </div>
                                <div class="form-group <?=form_error('testcategoryID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="testcategoryID"><?=$this->lang->line('test_category')?><span class="text-danger"> *</span></label>
                                    <?php
                                        $testcategoryArray['0'] = "- ".$this->lang->line('test_please_select')." -";
                                        if(inicompute($testcategorys)) {
                                            foreach ($testcategorys as $testcategoryKey => $testcategory) {
                                                $testcategoryArray[$testcategoryKey] = $testcategory;
                                            }
                                        }
                                        $errorClass = form_error('testcategoryID') ? 'is-invalid' : '';
                                        echo form_dropdown('testcategoryID', $testcategoryArray,  set_value('testcategoryID'), ' id="testcategoryID" class="form-control select2 '.$errorClass.'"');
                                    ?>
                                    <span><?=form_error('testcategoryID')?></span>
                                </div>
                                <div class="form-group <?=form_error('testlabelID') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="testlabelID"><?=$this->lang->line('test_label')?><span class="text-danger"> *</span></label>
                                     <?php
                                        $testlabelArray['0'] = "- ".$this->lang->line('test_please_select')." -";
                                        if(inicompute($settestlabels)) {
                                            foreach($settestlabels as $settestlabel) {
                                                $testlabelArray[$settestlabel->testlabelID] = $settestlabel->name;
                                            }
                                        }
                                        $errorClass = form_error('testlabelID') ? 'is-invalid' : '';
                                        echo form_dropdown('testlabelID', $testlabelArray,  set_value('testlabelID'), ' id="testlabelID" class="form-control select2 '.$errorClass.'"'); ?>
                                    <span><?=form_error('testlabelID')?></span>
                                </div>
                                <div class="form-group <?=form_error('testnote') ? 'text-danger' : '' ?>">
                                    <label class="control-label" for="testnote"><?=$this->lang->line('test_note')?></label>
                                    <?php $errorClass = form_error('testnote') ? 'is-invalid' : ''; ?>
                                    <textarea name="testnote" class="form-control <?=$errorClass?>" id="testnote" rows="5"><?=set_value('testnote')?></textarea>
                                    <span><?=form_error('testnote')?></span>
                                </div>
                            </div>
                            <div class="card-footer"> 
                                <button type="submit" class="btn btn-primary"><?=$this->lang->line('test_add')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="<?=(permissionChecker('test_add') ? 'col-sm-8' : 'col-sm-12')?>">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"><i class="fa fa-table"></i>&nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('test/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>