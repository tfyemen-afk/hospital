<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-testreport"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_testreport')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="card card-custom">
            <div class="card-header">
                <div class="header-block">
                    <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('testreport_filter')?></p>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-md-4 <?=form_error('testcategoryID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="testcategoryID"><?=$this->lang->line('testreport_test_category')?></label>
                            <?php
                            $testcategoryArray['0'] = '— '.$this->lang->line('testreport_please_select').' —';
                            if(inicompute($testcategorys)) {
                                foreach ($testcategorys as $testcategory) {
                                    $testcategoryArray[$testcategory->testcategoryID] = $testcategory->name;
                                }
                            }
                            $errorClass = form_error('testcategoryID') ? 'is-invalid' : '';
                            echo form_dropdown('testcategoryID', $testcategoryArray,  set_value('testcategoryID'), ' id="testcategoryID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('testcategoryID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('testlabelID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="testlabelID"><?=$this->lang->line('testreport_test_label')?></label>
                            <?php
                            $testlabelArray['0'] = '— '.$this->lang->line('testreport_please_select').' —';
                            $errorClass = form_error('testlabelID') ? 'is-invalid' : '';
                            echo form_dropdown('testlabelID', $testlabelArray,  set_value('testlabelID'), ' id="testlabelID" class="form-control select2 '.$errorClass.'"')?>
                        <span><?=form_error('testlabelID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('billID') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="billID"><?=$this->lang->line('testreport_bill_no')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('billID') ? 'is-invalid' : '' ?>" id="billID" name="billID"  value="<?=set_value('billID')?>">
                        <span><?=form_error('billID')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('from_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="from_date"><?=$this->lang->line('testreport_from_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('from_date') ? 'is-invalid' : '' ?> datepicker" id="from_date" name="from_date"  value="<?=set_value('from_date')?>">
                        <span><?=form_error('from_date')?></span>
                    </div>
                    <div class="form-group col-md-4 <?=form_error('to_date') ? 'text-danger' : '' ?>">
                        <label class="control-label" for="to_date"><?=$this->lang->line('testreport_to_date')?></label>
                        <input autocomplete="off" type="text" class="form-control <?=form_error('to_date') ? 'is-invalid' : '' ?> datepicker" id="to_date" name="to_date"  value="<?=set_value('to_date')?>">
                        <span><?=form_error('to_date')?></span>
                    </div>
                    <div class="col-md-4">
                        <button id="get_testreport" class="btn btn-success get-report-button"> <?=$this->lang->line('testreport_get_report')?></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="load_testreport"></div>
</article>
