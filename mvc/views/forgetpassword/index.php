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
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/toastr/toastr.min.css')?>">
        <script src="<?=base_url('assets/js/vendor.js')?>"></script>
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
                        <p class="text-center" style="text-transform: uppercase"><?=$this->lang->line('forgetpassword_forget_password')?></p>
                        <?php if(validation_errors()) { ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?=validation_errors()?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php } ?>
                        <form id="login-form" method="POST">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="form-group">
                                <label for="email"><?=$this->lang->line('forgetpassword_email')?></label>
                                <input type="text" class="form-control underlined" name="email" value="<?=set_value('email')?>" id="email" placeholder="Your email"> 
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary"><?=$this->lang->line('forgetpassword_send')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?=base_url('assets/toastr/toastr.min.js')?>"></script>
        <?php if ($this->session->flashdata('success')): ?>
            <script type="text/javascript">
                toastr["success"]("<?=$this->session->flashdata('success');?>");
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            </script>
        <?php endif ?>
        <?php if ($this->session->flashdata('error')): ?>
           <script type="text/javascript">
                toastr["error"]("<?=$this->session->flashdata('error');?>");
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            </script>
        <?php endif ?> 
    </body>
</html>