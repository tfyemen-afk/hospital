<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-physicalcondition"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('physicalcondition/index')?>"><?=$this->lang->line('menu_physicalcondition')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('physicalcondition_edit')?></li>
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
                            <p class="title"><i class="fa fa-edit"></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group dateDiv <?=form_error('date') ? 'text-danger' : ''?>">
                                <label for="date"><?=$this->lang->line('physicalcondition_date')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control datepicker <?=form_error('date') ? 'is-invalid' : ''?>" name="date" id="date" value="<?=set_value('date', date('d-m-Y h:i A', strtotime($physicalcondition->date)))?>"/>
                                <span><?=form_error('date')?></span>
                            </div>
                            <div class="form-group <?=form_error('height') ? 'text-danger' : ''?>">
                                <label for="height"><?=$this->lang->line('physicalcondition_height')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('height') ? 'is-invalid' : ''?>" name="height" id="height" value="<?=set_value('height', ($physicalcondition->height == 0 ? '' : $physicalcondition->height))?>"/>
                                <span><?=form_error('height')?></span>
                            </div>
                            <div class="form-group <?=form_error('weight') ? 'text-danger' : ''?>">
                                <label for="weight"><?=$this->lang->line('physicalcondition_weight')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('weight') ? 'is-invalid' : ''?>" name="weight" id="weight" value="<?=set_value('weight', ($physicalcondition->weight == 0 ? '' : $physicalcondition->weight))?>"/>
                                <span><?=form_error('weight')?></span>
                            </div>
                            <div class="form-group <?=form_error('bp') ? 'text-danger' : ''?>">
                                <label for="bp"><?=$this->lang->line('physicalcondition_bp')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('bp') ? 'is-invalid' : ''?>" name="bp" id="bp" value="<?=set_value('bp', $physicalcondition->bp)?>"/>
                                <span><?=form_error('bp')?></span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('physicalcondition_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <div class="header-block">
                            <p class="title"> <i class="fa fa-table"></i> &nbsp;<?=$this->lang->line('panel_list')?></p>
                        </div>
                    </div>
                    <div class="card-block">
                        <?php $this->load->view('physicalcondition/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>