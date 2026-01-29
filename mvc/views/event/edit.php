<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-calendar-check-o"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8">
            <nav class="breadcrumb-position" aria-label="breadcrumb">
              <ol class="breadcrumb themebreadcrumb pull-right">
                <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li class="breadcrumb-item"><a href="<?=site_url('event/index/'.$displayID)?>"> <?=$this->lang->line('menu_event')?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('event_edit')?></li>
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
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <div class="card-block">
                            <div class="form-group <?=form_error('title') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="title"><?=$this->lang->line('event_title')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('title') ? 'is-invalid' : '' ?>" id="title" name="title"  value="<?=set_value('title', $event->title)?>">
                                <span><?=form_error('title')?></span>
                            </div>
                            <div class="form-group <?=form_error('date') ? 'text-danger' : '' ?>">
                                <?php
                                    $fromdate = date('d/m/Y H:i: A', strtotime($event->fdate.' '.$event->ftime));
                                    $todate   = date('d/m/Y H:i: A', strtotime($event->tdate.' '.$event->ttime));
                                    $date     = $fromdate.' - '.$todate;
                                ?>
                                <label class="control-label" for="date"><?=$this->lang->line('event_date')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('date') ? 'is-invalid' : '' ?>" id="date" name="date"  value="<?=set_value('date', $date)?>">
                                <span><?=form_error('date')?></span>
                            </div>
                            <div class="form-group <?=form_error('photo') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="photo"><?=$this->lang->line('event_photo')?> </label>
                                <div class="custom-file">
                                    <input type="file" name="photo" class="custom-file-input file-upload-input <?=form_error('photo') ? 'is-invalid' : '' ?>" id="file-upload">
                                    <label class="custom-file-label label-text-hide" for="file-upload"><?=$this->lang->line('event_choose_file')?></label>
                                </div>
                                <span><?=form_error('photo')?></span>
                            </div>
                            <div class="form-group <?=form_error('description') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="description"><?=$this->lang->line('event_description')?> <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control <?=form_error('description') ? 'is-invalid' : '' ?>" cols="30" rows="10"><?=$event->description?></textarea>
                                <span><?=form_error('description')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('event_update')?></button>
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
                        <?php $this->load->view('event/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>