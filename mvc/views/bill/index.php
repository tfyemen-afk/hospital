<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-bill"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active"><?=$this->lang->line('menu_bill')?></li>
                </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link <?=($activetab) ? 'active' : ''?>" id="list_tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true"><i class="fa fa-table"></i> <?=$this->lang->line('panel_list')?></a>
                    </li>
                    <?php if(permissionChecker('bill_add')) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?=($activetab) ? '' : 'active'?>" id="add_tab" data-toggle="tab" href="#add" role="tab" aria-controls="add" aria-selected="false"><i class="fa fa-plus-square-o"></i> <?=$this->lang->line('panel_add')?></a>
                        </li>
                    <?php } ?>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade bg-color <?=($activetab) ? 'show active' : ''?>" id="list" role="tabpanel" aria-labelledby="list_tab">
                        <?php $this->load->view('bill/table');?>
                    </div>
                    <?php if(permissionChecker('bill_add')) { ?>
                        <div class="tab-pane fade <?=($activetab) ? '' : 'show active'?>" id="add" role="tabpanel" aria-labelledby="add_tab">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="card card-custom">
                                        <div class="card-header">
                                            <div class="header-block">
                                                <p class="title"> <i class="fa fa-sliders"></i> &nbsp;<?=$this->lang->line('bill_filter')?></p>
                                            </div>
                                        </div>

                                        <form method="POST" id="billdata">
                                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                                            <div class="card-block">
                                                <div class="form-group uhidIDV <?=form_error('uhid') ? 'text-danger' : '' ?>">
                                                    <label class="control-label" for="uhid"><?=$this->lang->line('bill_uhid')?><span class="text-danger"> *</span></label>
                                                        <?php
                                                        $patientArray['0']  = '— '.$this->lang->line('bill_please_select').' —';
                                                        if(inicompute($patients)) {
                                                            foreach ($patients as $patient) {
                                                                $patientArray[$patient->patientID]  = $patient->patientID.' - '.$patient->name;
                                                            }
                                                        }
                                                        $errorClass = form_error('uhid') ? 'is-invalid' : '';
                                                        echo form_dropdown('uhid', $patientArray,  set_value('uhid'), ' id="uhid" class="form-control select2 '.$errorClass.'" ')?>
                                                    <span id="uhid-error"><?=form_error('uhid')?></span>
                                                </div>
                                                <div class="form-group <?=form_error('note') ? 'text-danger' : '' ?>">
                                                    <label class="control-label" for="note"><?=$this->lang->line('bill_note')?></label>
                                                    <textarea type="text" class="form-control <?=form_error('note') ? 'is-invalid' : '' ?>" id="note" name="note" rows="2" ><?=set_value('note')?></textarea>
                                                    <span><?=form_error('note')?></span>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary" id="billAdd"><?=$this->lang->line('bill_add')?></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <div class="col-sm-8">
                                    <div class="card card-custom">
                                        <div class="card-header">
                                            <div class="header-block">
                                                <p class="title"> <i class="fa fa-braille"></i> &nbsp;<?=$this->lang->line('bill_filter_data')?></p>
                                            </div>
                                        </div>
                                        <div class="card-block">
                                            <?php $this->load->view('bill/itemadd'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</article>