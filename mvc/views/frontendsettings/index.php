<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-asterisk"></i> <?=$this->lang->line('panel_title')?></h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index') ?>"><i class="fa fa-laptop"> </i><?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$this->lang->line('menu_frontendsettings')?></li>
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
                            <legend class="setting-legend"><?=$this->lang->line('frontendsettings_configaration')?></legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('login_menu_status') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="login_menu_status"><?=$this->lang->line('frontendsettings_login_menu_status')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable login menu for frontend top menu"></i>
                                        </label>
                                        <?php
                                            $loginMenuStatusArray[1] = $this->lang->line('frontendsettings_enable');
                                            $loginMenuStatusArray[0] = $this->lang->line('frontendsettings_disable');
                                            echo form_dropdown("login_menu_status", $loginMenuStatusArray, set_value("login_menu_status", $frontendsettings->login_menu_status), "id='login_menu_status' class='form-control select2'");
                                        ?>
                                        <span ><?=form_error('login_menu_status')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('doctor_email_status') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="doctor_email_status"><?=$this->lang->line('frontendsettings_doctor_email')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable User email for frontend teahcer list"></i>
                                        </label>
                                        <?php
                                            $userEmailStatusArray[1] = $this->lang->line('frontendsettings_enable');
                                            $userEmailStatusArray[0] = $this->lang->line('frontendsettings_disable');
                                            echo form_dropdown("doctor_email_status", $userEmailStatusArray, set_value("doctor_email_status", $frontendsettings->doctor_email_status), "id='doctor_email_status' class='form-control select2'");
                                        ?>
                                        <span><?=form_error('doctor_email_status')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('doctor_phone_status') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="doctor_phone_status"><?=$this->lang->line('frontendsettings_doctor_phone')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Enable/Disable User phone for frontend teahcer list"></i>
                                        </label>
                                        <?php
                                            $userPhoneStatusArray[1] = $this->lang->line('frontendsettings_enable');
                                            $userPhoneStatusArray[0] = $this->lang->line('frontendsettings_disable');
                                            echo form_dropdown("doctor_phone_status", $userPhoneStatusArray, set_value("doctor_phone_status", $frontendsettings->doctor_phone_status), "id='doctor_phone_status' class='form-control select2'");
                                        ?>
                                        <span><?=form_error('doctor_phone_status')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('description') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="description"><?=$this->lang->line('frontendsettings_description')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="right" title="Set frontend footer short description"></i>
                                        </label>
                                        <textarea class="form-control" id="description" name="description"><?=set_value('description', $frontendsettings->description)?></textarea>
                                        <span><?=form_error('description')?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="setting-fieldset">
                            <legend class="setting-legend"><?=$this->lang->line('frontendsettings_social')?></legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('facebook') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="facebook"><?=$this->lang->line('frontendsettings_facebook')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Facebook Link for frontend"></i>
                                        </label>
                                        <input type="text" class="form-control" id="facebook" name="facebook"  value="<?=set_value('facebook',$frontendsettings->facebook)?>">
                                        <span><?=form_error('facebook')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('twitter') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="twitter"><?=$this->lang->line('frontendsettings_twitter')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Twitter Link for frontend"></i>
                                        </label>
                                        <input type="text" class="form-control" id="twitter" name="twitter" value="<?=set_value('twitter',$frontendsettings->twitter)?>">
                                        <span><?=form_error('twitter')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('linkedin') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="linkedin"><?=$this->lang->line('frontendsettings_linkedin')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Linkedin Link for frontend"></i>
                                        </label>
                                        <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?=set_value('linkedin',$frontendsettings->linkedin)?>">
                                        <span><?=form_error('linkedin')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('youtube') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="youtube"><?=$this->lang->line('frontendsettings_youtube')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Youtube Link for frontend"></i>
                                        </label>
                                         <input type="text" class="form-control" id="youtube" name="youtube" value="<?=set_value('youtube',$frontendsettings->youtube)?>">
                                        <span><?=form_error('youtube')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('google') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="google"><?=$this->lang->line('frontendsettings_google')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Google + Link for frontend"></i>
                                        </label>
                                        <input type="text" class="form-control" id="google" name="google" value="<?=set_value('google',$frontendsettings->google)?>">
                                        <span><?=form_error('google')?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('frontendsettings_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</article>