<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-menulog"></i><?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item"><a href="<?=site_url('menulog/index')?>"><?=$this->lang->line('menu_menulog')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menulog_edit')?></li>
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
                            <div class="form-group <?=form_error('name') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="name"><?=$this->lang->line('menulog_name')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $menulog->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('parentmenulogID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="parentmenulogID"><?=$this->lang->line('menulog_parent')?></label>
                                    <?php
                                    $menuparentArray['0'] = $this->lang->line('menulog_please_select');
                                    if(inicompute($menulogs)) {
                                        foreach ($menulogs as $singlemenulog) {
                                            $menuparentArray[$singlemenulog->menulogID] = $singlemenulog->name;
                                        }
                                    }
                                    $errorClass = form_error('parentmenulogID') ? 'is-invalid' : '';
                                    echo form_dropdown('parentmenulogID', $menuparentArray,  set_value('parentmenulogID', $menulog->parentID), ' class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('parentmenulogID')?></span>
                            </div>
                            <div class="form-group <?=form_error('link') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="link"><?=$this->lang->line('menulog_link')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('link') ? 'is-invalid' : '' ?>" id="link" name="link"  value="<?=set_value('link', $menulog->link)?>">
                                <span><?=form_error('link')?></span>
                            </div>
                            <div class="form-group <?=form_error('icon') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="icon"><?=$this->lang->line('menulog_icon')?></label>
                                <input type="text" class="form-control <?=form_error('icon') ? 'is-invalid' : '' ?>" id="icon" name="icon"  value="<?=set_value('icon', $menulog->icon)?>">
                                <span><?=form_error('icon')?></span>
                            </div>
                            <div class="form-group <?=form_error('priority') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="priority"><?=$this->lang->line('menulog_priority')?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?=form_error('priority') ? 'is-invalid' : '' ?>" id="priority" name="priority"  value="<?=set_value('priority', $menulog->priority)?>">
                                <span><?=form_error('priority')?></span>
                            </div>
                            <div class="form-group <?=form_error('status') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="status"><?=$this->lang->line('menulog_status')?> <span class="text-danger">*</span></label>
                                    <?php
                                    $statusArray['0'] = $this->lang->line('menulog_please_select');
                                    $statusArray['1'] = $this->lang->line('menulog_active');
                                    $statusArray['2'] = $this->lang->line('menulog_block');

                                    $errorClass = form_error('status') ? 'is-invalid' : '';
                                    echo form_dropdown('status', $statusArray,  set_value('status', $menulog->status), ' class="form-control select2 '.$errorClass.'"')?>
                                <span><?=form_error('status')?></span>
                            </div>
                            <div class="form-group <?=form_error('pullright') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="pullright"><?=$this->lang->line('menulog_pullright')?></label>
                                <input type="text" class="form-control <?=form_error('pullright') ? 'is-invalid' : '' ?>" id="pullright" name="pullright"  value="<?=set_value('pullright', $menulog->pullright)?>">
                                <span><?=form_error('pullright')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('menulog_update')?></button>
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
                        <?php $this->load->view('menulog/table')?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>
