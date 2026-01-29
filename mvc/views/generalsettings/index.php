<article class="content buttons-page">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa fa-gears"></i> <?=$this->lang->line('panel_title')?></h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?= site_url('dashboard/index') ?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"> <?=$this->lang->line('menu_generalsettings')?></li>
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
                            <legend class="setting-legend"><?=$this->lang->line('generalsettings_site_confifuration')?></legend>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('system_name') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="system_name">
                                            <?=$this->lang->line('generalsettings_system_name')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set your site name here"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('system_name') ? 'is-invalid' : '' ?>" id="system_name" name="system_name"  value="<?=set_value('system_name',$generalsettings->system_name)?>">
                                        <span ><?=form_error('system_name')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('phone') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="phone">
                                            <?=$this->lang->line('generalsettings_phone')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set site phone number here"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('phone') ? 'is-invalid' : '' ?>" id="phone" name="phone"  value="<?=set_value('phone',$generalsettings->phone)?>">
                                        <span><?=form_error('phone')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('email') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="email">
                                            <?=$this->lang->line('generalsettings_email')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set site email address here"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('email') ? 'is-invalid' : '' ?>" id="email" name="email"  value="<?=set_value('email',$generalsettings->email)?>">
                                        <span><?=form_error('email')?></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('address') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="address">
                                            <?=$this->lang->line('generalsettings_address')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set site address here"></i>
                                        </label>
                                        <textarea name="address" class="form-control <?=form_error('address') ? 'is-invalid' : '' ?>" id="address" rows="2"><?=set_value('address', $generalsettings->address)?></textarea>
                                        <span><?=form_error('address')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('footer_text') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="footer_text">
                                            <?=$this->lang->line('generalsettings_footer_text')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set site footer text here"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('footer_text') ? 'is-invalid' : '' ?>" id="footer_text" name="footer_text" value="<?=set_value('footer_text', $generalsettings->footer_text)?>">
                                        <span><?=form_error('footer_text')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('currency_code') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="currency_code">
                                            <?=$this->lang->line('generalsettings_currency_code')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set site currency code like USD or GBP"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('currency_code') ? 'is-invalid' : '' ?>" id="currency_code" name="currency_code"  value="<?=set_value('currency_code',$generalsettings->currency_code)?>">
                                        <span><?=form_error('currency_code')?></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('currency_symbol') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="currency_symbol">
                                            <?=$this->lang->line('generalsettings_currency_symbol')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set site currency system here like $ or £"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('currency_symbol') ? 'is-invalid' : '' ?>" id="currency_symbol" name="currency_symbol"  value="<?=set_value('currency_symbol',$generalsettings->currency_symbol)?>">
                                        <span><?=form_error('currency_symbol')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('frontend') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="frontend">
                                            <?=$this->lang->line('generalsettings_frontend')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Enable/Disable frontend site"></i>
                                        </label>
                                        <?php
                                            $frontendArray[1] = $this->lang->line('generalsettings_enable');
                                            $frontendArray[0] = $this->lang->line('generalsettings_disable');
                                            echo form_dropdown("frontend", $frontendArray, set_value("frontend", $generalsettings->frontend), "id='frontend' class='form-control select2'");
                                        ?>
                                        <span ><?=form_error('frontend')?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('time_zone') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="time_zone">
                                            <?=$this->lang->line('generalsettings_timezone')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Select your region time zone. We define a time zone as a region where the same standard time is used"></i>
                                        </label>
                                       <?php
                                        $path = APPPATH."config/timezones_class.php";
                                        if(@include($path)) {
                                            $timezones_cls = new Timezones();
                                            $timezones = $timezones_cls->get_timezones();
                                            unset($timezones['']);
                                            $selectTimeZone['none'] = $this->lang->line('generalsettings_select');
                                            $timeZones = array_merge($selectTimeZone, $timezones);

                                            echo form_dropdown("time_zone", $timeZones, set_value("time_zone", $generalsettings->time_zone), "id='time_zone' class='form-control select2 ".(form_error('time_zone') ? 'is-invalid' : '')."'");
                                        }
                                    ?>
                                        <span ><?=form_error('time_zone')?></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label" for="medicine_expire_limit_day">
                                            <?=$this->lang->line('generalsettings_medicine_expire_limit_day')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set here medicine expire limit day"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('medicine_expire_limit_day') ? 'is-invalid' : '' ?>" id="medicine_expire_limit_day" name="medicine_expire_limit_day"  value="<?=set_value('medicine_expire_limit_day',$generalsettings->medicine_expire_limit_day)?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group <?=form_error('logo') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="logo">
                                            <?=$this->lang->line('generalsettings_logo')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set site logo here"></i>
                                        </label>
                                        <div class="custom-file">
                                            <input type="file" name="logo" class="custom-file-input file-upload-input" id="file-upload">
                                            <label class="custom-file-label label-text-hide" for="file-upload">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        
                        <fieldset class="setting-fieldset">
                            <legend class="setting-legend"><?=$this->lang->line('generalsettings_patient')?></legend>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group <?=form_error('patient_credit_limit') ? 'text-danger' : ''?>">
                                        <label class="control-label" for="patient_credit_limit">
                                            <?=$this->lang->line("generalsettings_credit_limit")?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set here patient credit limit"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('patient_credit_limit') ? 'is-invalid' : '' ?>" id="patient_credit_limit" name="patient_credit_limit" value="<?=set_value('patient_credit_limit', $generalsettings->patient_credit_limit)?>" >
                                        <span><?=form_error('patient_credit_limit')?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group <?=form_error('patient_password') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="patient_password">
                                            <?=$this->lang->line("generalsettings_password")?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set here default patient password"></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('patient_password') ? 'is-invalid' : '' ?>" id="patient_password" name="patient_password" value="<?=set_value('patient_password', $generalsettings->patient_password)?>" >
                                        <span><?=form_error('patient_password')?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="setting-fieldset">
                            <legend class="setting-legend"><?=$this->lang->line('generalsettings_auto_update')?></legend>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group <?=form_error('auto_update_notification') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="auto_update_notification">
                                            <?=$this->lang->line('generalsettings_autoupdatenotification')?>
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Enable/Disable for auto update notification. only main system admin can see the update notification"></i>
                                        </label>
                                        <?php
                                        $autoupdateArray[1] = $this->lang->line('generalsettings_enable');
                                        $autoupdateArray[0] = $this->lang->line('generalsettings_disable');
                                        echo form_dropdown("auto_update_notification", $autoupdateArray, set_value("auto_update_notification", $generalsettings->auto_update_notification), "id='auto_update_notification' class='form-control select2'");
                                        ?>
                                        <span ><?=form_error('auto_update_notification')?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="setting-fieldset">
                            <legend class="setting-legend"><?=$this->lang->line('generalsettings_captcha')?></legend>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group <?=form_error('captcha_status') ? 'text-danger' : '' ?>">
                                        <label class="control-label" for="captcha_status">
                                            <?=$this->lang->line('generalsettings_captcha')?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Enable/Disable in captcha for login. reCAPTCHA type : v2"></i>
                                        </label>
                                        <?php
                                            $captchaArray[1] = $this->lang->line('generalsettings_enable');
                                            $captchaArray[0] = $this->lang->line('generalsettings_disable');
                                            echo form_dropdown("captcha_status", $captchaArray, set_value("captcha_status", $generalsettings->captcha_status), "id='captcha_status' class='form-control select2'");
                                        ?>
                                        <span ><?=form_error('captcha_status')?></span>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group <?=form_error('recaptcha_site_key') ? 'text-danger' : '' ?>" id="recaptcha_site_key_id">
                                        <label class="control-label" for="recaptcha_site_key">
                                            <?=$this->lang->line("generalsettings_recaptcha_site_key")?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set recaptcha site key. Becareful If it's invalid then you cann't login."></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('recaptcha_site_key') ? 'is-invalid' : '' ?>" id="recaptcha_site_key" name="recaptcha_site_key" value="<?=set_value('recaptcha_site_key', $generalsettings->recaptcha_site_key)?>" >
                                        <span><?=form_error('recaptcha_site_key')?></span>
                                    </div>
                                </div>
                                
                                <div class="col-sm-4">
                                    <div class="form-group <?=form_error('recaptcha_secret_key') ? 'text-danger' : ''?>" id="recaptcha_secret_key_id" >
                                        <label class="control-label" for="recaptcha_secret_key">
                                            <?=$this->lang->line("generalsettings_recaptcha_secret_key")?> 
                                            <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Set recaptcha secret key. Becareful If it's invalid then you cann't login."></i>
                                        </label>
                                        <input type="text" class="form-control <?=form_error('recaptcha_secret_key') ? 'is-invalid' : '' ?>" id="recaptcha_secret_key" name="recaptcha_secret_key" value="<?=set_value('recaptcha_secret_key', $generalsettings->recaptcha_secret_key)?>" >
                                        <span><?=form_error('recaptcha_secret_key')?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?=$this->lang->line('generalsettings_update')?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</article>