<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <title> <?=$generalsettings->system_name?> - <?=$this->lang->line('panel_title')?> </title>
        <meta name="description" content="<?=$generalsettings->system_name?>">
        <link rel="icon" href="<?=base_url('uploads/general/'.$generalsettings->logo)?>" type="image/gif" sizes="16x16" >
        <!-- Place favicon.ico in the root directory -->
        <link rel="stylesheet" href="<?=base_url('assets/css/vendor.css')?>">
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/css/app.css')?>">
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/datatable/css/jquery.dataTables.min.css')?>">
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/datatable/css/dataTables.bootstrap4.min.css')?>">
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/toastr/toastr.min.css')?>">
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/thinkbuzz/hidetable.css')?>">
        <!-- Theme initialization -->
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/css/custom.css')?>">
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/css/iniicon.css')?>">
        <link rel="stylesheet" id="theme-style" href="<?=base_url('assets/css/inistyle.css')?>">
        <script src="<?=base_url('assets/js/vendor.js')?>"></script>
        <script src="<?=base_url('assets/inilabs/header.js')?>"></script>
        <script>
            <?php jsStack($jsmanager); ?>
        </script>
        <?php headerAssets($headerassets); ?>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="app" id="app">
                <header class="header">
                    <div class="header-block header-block-collapse d-lg-none d-xl-none">
                        <button class="collapse-btn" id="sidebar-collapse-btn">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                    <div class="header-block header-block-search">
                        <form role="search" method="GET" action="<?=site_url('search/index')?>">
                            <div class="input-container text-danger">
                                <i class="fa fa-search"></i>
                                <input type="search" name="search" class="is-invalid" value="<?=$getsearch?>" placeholder="Search">
                                <div class="underline"></div>
                            </div>
                        </form>
                    </div>
                    <div class="header-block header-block-nav">
                        <ul class="nav-profile">
                            <li class="notifications new">
                                <a href="" data-toggle="dropdown">
                                    <a target="_blank" href="<?=site_url('frontend/index')?>"><i class="fa fa-globe"></i></a>
                                </a>
                            </li>
                            <li class="notifications new">
                                <a href="#" <?=(inicompute($notifications) === 0) ? '' : 'data-toggle="dropdown"'?>>
                                    <i class="fa fa-bell-o"></i>
                                    <sup>
                                        <span class="counter"><?=(inicompute($notifications) > 5) ? 5 : ((inicompute($notifications) == 0) ? '' : inicompute($notifications))?></span>
                                    </sup>
                                </a>
                                <div class="dropdown-menu notifications-dropdown-menu">
                                    <ul class="notifications-container">
                                        <?php if(inicompute($notifications)) { $i = 1; foreach ($notifications as $notification) { ?>
                                            <li>
                                                <a href="<?=site_url('notification/index/'.(isset($notification->noticeID) ? 'notice/'.$notification->noticeID : 'event/'.$notification->eventID))?>" class="notification-item">
                                                    <div class="img-col">
                                                        <div class="img" style="background-image: url(<?=base_url('uploads/user/'.$notification->photo)?>)"></div>
                                                    </div>
                                                    <div class="body-col">
                                                        <p>
                                                            <span class="accent"><?=$notification->name?></span> :
                                                            <span class="accent"><?=$notification->title?></span>.
                                                        </p>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php if($i == 5) { break; } $i++;} } ?>
                                    </ul>
                                </div>
                            </li>
                            <li class="profile dropdown">
                                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                                    <div class="img"> <img src="<?=base_url('uploads/user/'.$this->session->userdata('photo'))?>"></div>
                                    <span class="name"><?=$this->session->userdata('name')?></span>
                                </a>
                                <div class="dropdown-menu profile-dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <a class="dropdown-item" href="<?=site_url('profile/index')?>">
                                        <i class="fa fa-user icon"></i> <?=$this->lang->line('topbar_profile')?>
                                    </a>
                                    <a class="dropdown-item" href="<?=site_url('changepassword/index')?>">
                                        <i class="fa fa-lock icon"></i> <?=$this->lang->line('topbar_password')?>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="<?=site_url('signin/signout')?>">
                                        <i class="fa fa-power-off icon"></i> <?=$this->lang->line('topbar_logout')?>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </header>