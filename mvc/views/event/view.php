<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('event_print'))?>
                <?=btn_sm_pdf('event/printpreview/'.$event->eventID, $this->lang->line('event_pdf_preview'))?>
                <?=btn_sm_edit('event_edit', 'event/edit/'.$event->eventID.'/'.$displayID, $this->lang->line('event_edit'))?>
                <?=btn_sm_mail($this->lang->line('event_send_pdf_to_mail'))?>
            </div>
            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('event/index/'.$displayID)?>"><?=$this->lang->line('menu_event')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('event_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box">
                        <div class="event-view-head-cover" style="background-image: url(<?=base_url('uploads/files/'.$event->photo)?>);">
                            <span class="img-thumbnail event-from-date"><?=date('d M', strtotime($event->fdate))?></span>
                            <span class="img-thumbnail event-to-date"><?=date('d M', strtotime($event->tdate))?></span>
                        </div>
                        <div class="text-center">
                            <?php 
                                $current_date = strtotime(date("Y-m-d H:i:s"));
                                $to_date      = $event->tdate.' '.$event->ttime;
                                $to_date      = strtotime($to_date);

                                $disable = FALSE;
                                if($current_date > $to_date) {
                                    $disable = TRUE;
                                }
                            ?>
                            <div class="btn-group">
                                <button id="going" class="btn going-btn-disable" type="button" <?=($disable) ? 'disabled' : ''?>><?=$this->lang->line('event_going')?></button>
                                <button class="btn going-btn" data-toggle="modal" data-target="#goings" type="button"><?=inicompute($goings)?></button>
                            </div>
                            <div class="btn-group">
                                <button class="btn ignores-btn-disable" data-toggle="modal" data-target="#ignores" type="button"><?=inicompute($ignores)?></button>
                                <button id="ignore" class="btn ignores-btn" type="button" <?=($disable) ? 'disabled' : ''?>><?=$this->lang->line('event_ignore')?></button>
                            </div>
                        </div>
                        <div class="event-view-description">
                            <div class="text-center">
                                <h1><?=$event->title?></h1>
                                <h4><?=date('d M Y', strtotime($event->fdate))?> at <?=app_time($event->ftime)?> <b>to</b> <?=date('d M Y', strtotime($event->tdate))?> at <?=app_time($event->ttime)?> </h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6 offset-md-3"><?=$event->description?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

<div class="modal" id="goings">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title">
                    <?=$this->lang->line('event_going')?> (<?=inicompute($goings)?>)
                </h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td><?=$this->lang->line('event_photo')?></td>
                            <td><?=$this->lang->line('event_name')?></td>
                            <td><?=$this->lang->line('event_role')?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if(inicompute($goings)) { foreach($goings as $going) { ?>
                        <tr>
                            <td><?=$i?></td>
                            <td>
                                <img class="img-responsive table-image-size" src="<?=imagelink($going->photo, '/uploads/user/') ?>"/>
                            </td>
                            <td><?=$going->name?></td>
                            <td><?=isset($roles[$going->roleID]) ? $roles[$going->roleID] : ''?></td>
                        </tr>
                        <?php $i++; } } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('event_close')?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="ignores">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title">
                    <?=$this->lang->line('event_ignore')?> (<?=inicompute($ignores)?>)
                </h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td>#</td>
                            <td><?=$this->lang->line('event_photo')?></td>
                            <td><?=$this->lang->line('event_name')?></td>
                            <td><?=$this->lang->line('event_role')?></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if(inicompute($ignores)) { foreach($ignores as $ignore) { ?>
                        <tr>
                            <td><?=$i?></td>
                            <td>
                                <img class="img-responsive table-image-size" src="<?=imagelink($ignore->photo, '/uploads/user/') ?>"/>
                            </td>
                            <td><?=$ignore->name?></td>
                            <td><?=isset($roles[$ignore->roleID]) ? $roles[$ignore->roleID] : ''?></td>
                        </tr>
                        <?php $i++; } } ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('event_close')?></button>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="mail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('event_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('event_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('event_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('event_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('event_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>