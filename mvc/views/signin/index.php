<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title> <?=$generalsettings->system_name?> - <?=$this->lang->line('panel_title')?> </title>
        <meta name="description" content="<?=$generalsettings->system_name?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?=base_url('uploads/general/'.$generalsettings->logo)?>" type="image/gif" sizes="16x16" >
        <link rel="stylesheet" href="<?=base_url('assets/css/vendor.css')?>">
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/css/app.css')?>">
        <script src="<?=base_url('assets/js/vendor.js')?>"></script>
        <?php if(config_item('demo')) { ?>
            <script src="<?=base_url('assets/inilabs/signin/index.js')?>"></script>
        <?php } ?>
    </head>
    <body>
        <div class="auth">
            <div class="auth-container">
                <div class="card">
                    <header class="auth-header">
                        <img src="<?=base_url('uploads/general/'.$generalsettings->logo)?>">
                        <h1 class="auth-title"><?=$generalsettings->system_name?> </h1>
                    </header>
                    <div class="auth-content">
                        <p class="text-center" style="text-transform: uppercase"><?=$this->lang->line('signin_login_to_continue')?></p>
                        <?php
                            if($form_validation != "No"){
                                if(inicompute($form_validation)) {
                                    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                                        echo $form_validation;
                                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                                            echo '<span aria-hidden="true">&times;</span>';
                                        echo '</button>';
                                    echo '</div>';
                                }
                            }

                            if($this->session->flashdata('success')) {
                                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
                                    echo $this->session->flashdata('success');
                                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                                        echo '<span aria-hidden="true">&times;</span>';
                                    echo '</button>';
                                echo '</div>';
                            }
                        ?>

                        <form id="login-form" method="POST">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="form-group">
                                <label for="username"><?=$this->lang->line('signin_email')?>/<?=$this->lang->line('signin_username')?></label>
                                <input type="text" class="form-control underlined" name="username" id="username" placeholder="Your username" value="<?=get_cookie('remember_username') ? get_cookie('remember_username') : ''?>">
                            </div>
                            <div class="form-group">
                                <label for="password"><?=$this->lang->line('signin_password')?></label>
                                <input type="password" class="form-control underlined" name="password" id="password" placeholder="Your password" value="<?=get_cookie('remember_password') ? get_cookie('remember_password') : ''?>">
                            </div>
                            <div class="form-group">
                                <label for="remember">
                                    <input name="remember" class="checkbox" id="remember" type="checkbox" <?=get_cookie('remember_username') ? 'checked' : ''?>>
                                    <span><?=$this->lang->line('signin_remember_me')?></span>
                                </label>
                                <a style="text-decoration: none" href="<?=site_url('forgetpassword/index')?>" class="forgot-btn pull-right"><?=$this->lang->line('signin_forget_password')?></a>
                            </div>

                            <?php if(isset($generalsettings->captcha_status) && $generalsettings->captcha_status) { ?>
                                <div class="form-group">
                                    <?php echo $recaptcha['widget']; echo $recaptcha['script']; ?>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary"><?=$this->lang->line('signin_login')?></button>
                            </div>
                        </form>

                        <?php if(config_item('demo')) { ?>
                            <div class="col-md-4 col-md-offset-4 marg" style="margin-top:30px;">
                                <a class="navbar-brand" href="#" style="padding:10px;font-size:16px; text-decoration: none;text-align: center">
                                    For Quick Demo Login Click Below
                                </a>
                            </div>
                            <div class="col-md-6 col-md-offset-3" style="margin-top:10px">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-info" style="color:#000" id="admin">Admin</button>
                                    <button class="btn btn-sm btn-warning" style="color:#000" id="doctor">Doctor</button>
                                    <button class="btn btn-sm btn-success" style="color:#000" id="patient">Patient</button>
                                    <button class="btn btn-sm btn-info" style="color:#000" id="accountant">Accountant</button>
                                    <button class="btn btn-sm btn-warning" style="color:#000" id="biller">Biller</button>
                                </div>
                            </div>
                            <div class="col-md-6 col-md-offset-3" style="margin-top:10px">
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-success" style="color:#000" id="pharmacist">Pharmacist</button>
                                    <button class="btn btn-sm btn-info" style="color:#000" id="pathologist">Pathologist</button>
                                    <button class="btn btn-sm btn-warning" style="color:#000" id="radiologist">Radiologist</button>
                                    <button class="btn btn-sm btn-success" style="color:#000" id="receptionist">Receptionist</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>