<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-clock-o"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pull-right themebreadcrumb">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('hourlytemplate/index')?>"><?=$this->lang->line('menu_hourlytemplate')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('hourlytemplate_edit')?></li>
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
                            <p class="title"> <i class="fa fa-plus-square-o"></i> &nbsp;<?=$this->lang->line('panel_edit')?></p>
                        </div>
                    </div>
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('hourly_grades') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('hourlytemplate_hourly_grades')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('hourly_grades') ? 'is-invalid' : '' ?>" id="hourly_grades" name="hourly_grades"  value="<?=set_value('hourly_grades', $hourlytemplate->hourly_grades)?>">
                                <span><?=form_error('hourly_grades')?></span>
                            </div>

                            <div class="form-group <?=form_error('hourly_rate') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('hourlytemplate_hourly_rate')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('hourly_rate') ? 'is-invalid' : '' ?>" id="hourly_rate" name="hourly_rate"  value="<?=set_value('hourly_rate', $hourlytemplate->hourly_rate)?>">
                                <span><?=form_error('hourly_rate')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('hourlytemplate_update')?></button>
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
                        <?php $this->load->view('hourlytemplate/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
                