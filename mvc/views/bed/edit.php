<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-bed"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('bed/index')?>"> <?=$this->lang->line('menu_bed')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('bed_edit')?></li>
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
                                <label class="control-label" for="name"><?=$this->lang->line('bed_name')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $bed->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('bedtypeID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="bedtypeID"><?=$this->lang->line('bed_bedtype')?><span class="text-danger"> *</span></label>
                                 <?php
                                    $bedtypeArray['0'] = $this->lang->line('bed_please_select');
                                    if(inicompute($bedtypes)) {
                                        foreach ($bedtypes as $bedtypeKey => $bedtype) {
                                            $bedtypeArray[$bedtypeKey] = $bedtype;
                                        }
                                    }
                                    $errorClass = form_error('bedtypeID') ? 'is-invalid' : '';
                                    echo form_dropdown('bedtypeID', $bedtypeArray,  set_value('bedtypeID', $bed->bedtypeID), ' class="form-control select2 '.$errorClass.'"'); ?>
                                <span><?=form_error('bedtypeID')?></span>
                            </div>
                            <div class="form-group <?=form_error('wardID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="wardID"><?=$this->lang->line('bed_ward')?><span class="text-danger"> *</span></label>
                                 <?php
                                    $wardArray['0'] = $this->lang->line('bed_please_select');
                                    if(inicompute($wards)) {
                                        foreach ($wards as $wardKey => $ward) {
                                            $wardArray[$wardKey] = $ward;
                                        }
                                    }
                                    $errorClass = form_error('wardID') ? 'is-invalid' : '';
                                    echo form_dropdown('wardID', $wardArray,  set_value('wardID', $bed->wardID), ' class="form-control select2 '.$errorClass.'"'); ?>
                                <span><?=form_error('wardID')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('bed_update')?></button>
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
                        <?php $this->load->view('bed/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>