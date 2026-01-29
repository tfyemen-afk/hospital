<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-ward"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('ward/index')?>"> <?=$this->lang->line('menu_ward')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('ward_edit')?></li>
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
                                <label class="control-label" for="name"><?=$this->lang->line('ward_name')?><span class="text-danger"> *</span></label>
                                <input type="text" class="form-control <?=form_error('name') ? 'is-invalid' : '' ?>" id="name" name="name"  value="<?=set_value('name', $ward->name)?>">
                                <span><?=form_error('name')?></span>
                            </div>
                            <div class="form-group <?=form_error('floorID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="floorID"><?=$this->lang->line('ward_floor')?><span class="text-danger"> *</span></label>
                                 <?php
                                    $floorArray['0'] = $this->lang->line('ward_please_select');
                                    if(inicompute($floors)) {
                                        foreach ($floors as $floorKey => $floor) {
                                            $floorArray[$floorKey] = $floor;
                                        }
                                    }
                                    $errorClass = form_error('floorID') ? 'is-invalid' : '';
                                    echo form_dropdown('floorID', $floorArray,  set_value('floorID', $ward->floorID), ' class="form-control select2 '.$errorClass.'"'); ?>
                                <span><?=form_error('floorID')?></span>
                            </div>
                            <div class="form-group <?=form_error('roomID') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="roomID"><?=$this->lang->line('ward_room')?><span class="text-danger"> *</span></label>
                                 <?php
                                    $roomArray['0'] = $this->lang->line('ward_please_select');
                                    if(inicompute($rooms)) {
                                        foreach ($rooms as $roomKey => $room) {
                                            $roomArray[$roomKey] = $room;
                                        }
                                    }
                                    $errorClass = form_error('roomID') ? 'is-invalid' : '';
                                    echo form_dropdown('roomID', $roomArray,  set_value('roomID', $ward->roomID), ' class="form-control select2 '.$errorClass.'"'); ?>
                                <span><?=form_error('roomID')?></span>
                            </div>
                            <div class="form-group <?=form_error('description') ? 'text-danger' : '' ?>">
                                <label class="control-label" for="control"><?=$this->lang->line('ward_description')?></label>
                                <textarea name="description" id="description" class="form-control <?=form_error('description') ? 'is-invalid' : '' ?>" cols="30" rows="5"><?=set_value('description', $ward->description)?></textarea>
                                <span><?=form_error('description')?></span>
                            </div>
                        </div>
                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('ward_update')?></button>
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
                        <?php $this->load->view('ward/table'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>