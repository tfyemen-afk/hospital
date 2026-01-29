<?php echo doctype("html5"); ?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Installer</title>
    <link href="<?php echo base_url('assets/install/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/install/install.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/install/font-awesome.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/install/datepicker.css'); ?>" rel="stylesheet">
    <link rel="icon" href="<?=base_url('assets/install/site.png')?>" type="image/gif" sizes="16x16" >

    <script type="text/javascript" src="<?php echo base_url('assets/install/jquery.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/install/datepicker.js'); ?>"></script>
</head>

<body class="bg-color">
    <div class="login-box">
        <div class="login">
            <div class="col-sm-8 col-sm-offset-2 ins-marg" style="text-align: center">
                <img width="100" height="100" src="<?=base_url('assets/install/site.png')?>" />
                <h4><strong class="text-red">Trust</strong><strong> Hospital </strong></h4>
            </div>
            <div class="col-sm-8 col-sm-offset-2 ins-marg">
                <?php $this->load->view($subview); ?>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url('assets/install/bootstrap.min.js'); ?>"></script>
</body>
</html>

