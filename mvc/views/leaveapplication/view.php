<article class="content">
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <?=btn_sm_print($this->lang->line('leaveapplication_print'))?>
                <?=btn_sm_pdf('leaveapplication/printpreview/'.$leaveapplication->leaveapplicationID, $this->lang->line('leaveapplication_pdf_preview'))?>
                <?php 
                    if($leaveapplication->attachment) {
                        echo btn_sm_download('leaveapplication', 'leaveapplication/download/'.$leaveapplication->leaveapplicationID, $leaveapplication->attachment, $this->lang->line('leaveapplication_download'));
                    }
                ?>
                <?=btn_sm_mail($this->lang->line('leaveapplication_send_pdf_to_mail'))?>
            </div>

            <div class="col-sm-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb pull-right themebreadcrumb">
                        <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                        <li class="breadcrumb-item" aria-current="page"><a href="<?=site_url('leaveapplication/index')?>"><?=$this->lang->line('menu_leaveapplication')?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('leaveapplication_view')?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div id="printablediv">
            <div class="row">
                <?php if(inicompute($user)) { ?>
                    <div class="col-sm-3 user-profile-box">
                        <div class="box box-primary">
                            <div class="box-body box-profile">
                                <img src="<?=imagelink($user->photo,'uploads/user')?>" class="profile-user-img mx-auto d-block rounded-circle" alt="">
                                <h3 class="profile-username text-center"><?=$user->name?></h3>
                                <p class="text-muted text-center"><?=inicompute($designation) ? $designation->designation : ''?></p>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('leaveapplication_gender')?></b> <a class="pull-right"><?=($user->gender == '1')? $this->lang->line('leaveapplication_male'): $this->lang->line('leaveapplication_female')?></a>
                                    </li>
                                    <li class="list-group-item list-group-item-background">
                                        <b><?=$this->lang->line('leaveapplication_dob')?></b> <a class="pull-right"><?=app_date($user->dob)?></a>
                                    </li>
                                    <li class="list-group-itemlist-group-item-background ">
                                        <b><?=$this->lang->line('leaveapplication_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-6 user-profile-box">
                    <div class="box box-primary box-height">
                        <div class="box-body box-profile">
                            <h5><?=$this->lang->line('leaveapplication_date')?> - <?=app_date($leaveapplication->apply_date);?></h5>
                            <p><?=$leaveapplication->reason?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3 user-profile-box">
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            <div class="single_box">
                                <strong><?=$this->lang->line('leaveapplication_schedule');?></strong>
                                <p class="text-muted"><?=app_date($leaveapplication->from_date);?> - <?=app_date($leaveapplication->to_date);?></p>
                            </div>
                            <div class="single_box">
                                <strong><?=$this->lang->line('leaveapplication_available_leave_day');?></strong>
                                <p class="text-muted"><?=$availableleavedays;?></p>
                            </div>
                            <div class="single_box">
                                <strong><?=$this->lang->line('leaveapplication_leaveday');?></strong>
                                <p class="text-muted"><?=$leave_days;?></p>
                            </div>
                            <div class="single_box">
                                <strong><?=$this->lang->line('leaveapplication_category');?></strong>
                                <p class="text-muted"><?=inicompute($leavecategory) ? $leavecategory->leavecategory : ''?></p>
                            </div>
                            <div class="single_box">
                                <strong><?=$this->lang->line('leaveapplication_on_duty_leave');?></strong>
                                <p class="text-muted">
                                    <?php if($leaveapplication->od_status == null || $leaveapplication->od_status == 0) { ?>
                                        <?=$this->lang->line('leaveapplication_no');?>
                                    <?php } else { ?>
                                        <?=$this->lang->line('leaveapplication_yes');?>
                                    <?php } ?>
                                </p>
                            </div>
                            <div class="single_box">
                                <strong><?=$this->lang->line('leaveapplication_application_status');?></strong>
                                <p class="text-muted">
                                    <?php if($leaveapplication->status == null) { 
                                      echo $this->lang->line('leaveapplication_pending');
                                     } elseif($leaveapplication->status == 1) { 
                                      echo $this->lang->line('leaveapplication_approved');
                                     } else { 
                                      echo $this->lang->line('leaveapplication_declined');
                                    } ?>
                                </p>
                            </div>
                            <div class="single_box">
                                <?php if($leaveapplication->status == null || $leaveapplication->status == 0) { ?>
                                    <?php echo btn_md_global('leaveapplication/status/'.$leaveapplication->leaveapplicationID, $this->lang->line('leaveapplication_approved'), $this->lang->line('leaveapplication_approved'), 'btn btn-primary btn-block'); ?>
                                <?php } else { ?>
                                    <?php echo btn_md_global('leaveapplication/status/'.$leaveapplication->leaveapplicationID, $this->lang->line('leaveapplication_declined'), $this->lang->line('leaveapplication_declined'), 'btn btn-danger btn-block'); ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>

<div class="modal" id="mail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="mdoal-title"><?=$this->lang->line('leaveapplication_send_pdf_to_mail')?></h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form role="form" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?=$this->lang->line('leaveapplication_to')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="to">
                        <span class="text-danger" id="to_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('leaveapplication_subject')?><span class="text-danger"> *</span></label>
                        <input type="text" class="form-control" id="subject">
                        <span class="text-danger" id="subject_error"></span>
                    </div>
                    <div class="form-group">
                        <label><?=$this->lang->line('leaveapplication_message')?></label>
                        <textarea class="form-control" id="message" rows="3"></textarea>
                        <span class="text-danger" id="message_error"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="sendpdf" class="btn btn-primary"><?=$this->lang->line('leaveapplication_send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>