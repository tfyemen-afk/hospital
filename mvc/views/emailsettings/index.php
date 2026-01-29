<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-emailsetting"></i> <?=$this->lang->line('panel_title')?></h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard/index') ?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_emailsettings')?></li>
              </ol>
            </nav>
        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-block">
                    <form role="form" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                        <fieldset class="setting-fieldset">
                            <legend class="setting-legend"><?=$this->lang->line('emailsettings_emailconfigaration')?></legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('email_engine') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="email_engine"><?=$this->lang->line('emailsettings_email_engine')?> <span class="text-danger">*</span></label>
                                            <?php
                                                $array = array(
                                                    "select"   => $this->lang->line("emailsettings_select"), 
                                                    "sendmail" => $this->lang->line("emailsettings_send_mail"), 
                                                    "smtp"     => $this->lang->line("emailsettings_smtp")
                                                );
                                                $erorrClass = form_error('email_engine') ? 'is-invalid' : '';
                                                echo form_dropdown("email_engine", $array, set_value("email_engine",$emailsettings->email_engine), "id='email_engine' class='form-control select2 ".$erorrClass."'");
                                            ?>
                                        <span><?=form_error('email_engine')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 mainsmtpDIV">
                                    <div class="form-group <?=form_error('smtp_username') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="smtp_username"><?=$this->lang->line('emailsettings_smtp_username')?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?=form_error('smtp_username') ? 'is-invalid' : '' ?>" id="smtp_username" name="smtp_username"  value="<?=set_value('smtp_username'), $emailsettings->smtp_username; ?>">
                                        <span ><?=form_error('smtp_username')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 mainsmtpDIV">
                                    <div class="form-group <?=form_error('smtp_password') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="smtp_password"><?=$this->lang->line('emailsettings_smtp_password')?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?=form_error('smtp_password') ? 'is-invalid' : '' ?>" id="smtp_password" name="smtp_password"  value="<?=set_value('smtp_password', $emailsettings->smtp_password)?>">
                                        <span ><?=form_error('smtp_password')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 mainsmtpDIV">
                                    <div class="form-group <?=form_error('smtp_server') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="smtp_server"><?=$this->lang->line('emailsettings_smtp_server')?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?=form_error('smtp_server') ? 'is-invalid' : '' ?>" id="smtp_server" name="smtp_server"  value="<?=set_value('smtp_server', $emailsettings->smtp_server)?>">
                                        <span ><?=form_error('smtp_server')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 mainsmtpDIV">
                                    <div class="form-group <?=form_error('smtp_port') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="smtp_port"><?=$this->lang->line('emailsettings_smtp_port')?> <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?=form_error('smtp_port') ? 'is-invalid' : '' ?>" id="smtp_port" name="smtp_port"  value="<?=set_value('smtp_port', $emailsettings->smtp_port)?>">
                                        <span ><?=form_error('smtp_port')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 mainsmtpDIV">
                                    <div class="form-group">
                                        <label class="control-label" for="smtp_security"><?=$this->lang->line('emailsettings_smtp_security')?></label>
                                        <input type="text" class="form-control" id="smtp_security" name="smtp_security" placeholder="tls/ssl"  value="<?=set_value('smtp_security', $emailsettings->smtp_security)?>">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('emailsettings_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</article>

