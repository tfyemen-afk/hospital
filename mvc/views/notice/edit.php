<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('notice/index/'.$displayID)?>"> <?=$this->lang->line('menu_notice')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"> <?=$this->lang->line('notice_edit')?></li>
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
                            <div class="form-group <?=form_error('title') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('notice_title')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('title') ? 'is-invalid' : '' ?>" id="title" name="title"  value="<?=set_value('title', $notice->title)?>">
                                <span><?=form_error('title')?></span>
                            </div>

                            <div class="form-group <?=form_error('date') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('notice_date')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('date') ? 'is-invalid' : '' ?> datepicker" id="date" name="date"  value="<?=set_value('date', date('d-m-Y',strtotime($notice->date)))?>">
                                <span><?=form_error('date')?></span>
                            </div>

                            <div class="form-group <?=form_error('notice') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="notice"><?=$this->lang->line('notice_notice')?><span class="text-danger"> *</span></label>
                                <textarea type="text" class="form-control <?=form_error('notice') ? 'is-invalid' : '' ?>" id="notice" name="notice" rows="10"><?=set_value('notice', $notice->notice)?></textarea>
                                <span><?=form_error('notice')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('notice_update')?></button>
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
                        <?php $this->load->view('notice/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>