<article class="content">
    <div class="row">
        <div class="col-sm-4">
            <h3 class="title"><i class="fa ini-frontendmenu"></i> <?=$this->lang->line('panel_title')?> </h3>
        </div>
        <div class="col-sm-8 pull-right">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pull-right themebreadcrumb">
                    <li class="breadcrumb-item"><a href="<?=site_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"> <?=$this->lang->line('menu_frontendmenu')?></a></li>
                </ol>
            </nav>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs" id="custom_tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="edit" data-toggle="tab" href="#edit-menus" role="tab" aria-controls="edit" aria-selected="true"><i class="fa fa-edit"></i> <?=$this->lang->line('frontendmenu_edit_menus')?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="location" data-toggle="tab" href="#manage-locations" role="tab" aria-controls="location" aria-selected="false"><i class="fa fa-plus-square-o"></i> <?=$this->lang->line('frontendmenu_manage_locations')?></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane bg-color fade show active" id="edit-menus" role="tabpanel" aria-labelledby="edit-menus-tab">
                        <div class="row">
                            <div class="col-sm-12">
                                <form class="form-inline wp-menu-form-inline" role="form" method="post" enctype="multipart/form-data" <?=!inicompute($fmenus) ? 'style="display: none"' : '' ?>>
                                    <div class="form-group">
                                        <label for="topbar-menu-select-property"><?=$this->lang->line('frontendmenu_select_a_menu_to_edit')?>:</label>
                                        <select class="form-control top-meu-select-property" id="topbar-menu-select-property">
                                            <?php if(inicompute($fmenus)) foreach ($fmenus as $fmenu) { ?>
                                                <option value="<?=$fmenu->fmenuID?>" <?=($fmenu->status == 1) ? 'selected= "selected"' : ''?>>
                                                    <?php
                                                    echo $fmenu->menu_name;
                                                    if($fmenu->topbar == 1 && $fmenu->social == 1) {
                                                        echo ' ('.$this->lang->line('frontendmenu_top_menu').', '.$this->lang->line('frontendmenu_social_links_menu').')';
                                                    } elseif($fmenu->topbar == 1) {
                                                        echo ' ('.$this->lang->line('frontendmenu_top_menu').')';
                                                    } elseif($fmenu->social == 1) {
                                                        echo ' ('.$this->lang->line('frontendmenu_social_links_menu').')';
                                                    }
                                                    ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        <span class="menu-create-option">
                                            <button type="button" class="btn btn-menu-add-type"><?=$this->lang->line('frontendmenu_select')?></button>
                                            <span >
                                                <?=$this->lang->line('frontendmenu_or')?> <a href="#" class="create-new-menu"><?=$this->lang->line('frontendmenu_create_a_new_menu')?></a href="#">
                                            </span>
                                        </span>
                                    </div>
                                </form>

                                <form class="form-inline wp-menu-form-inline" role="form" method="post" enctype="multipart/form-data" <?=inicompute($fmenus) ? 'style="display: none"' : '' ?>>
                                    <div class="form-group">
                                        <label class="wp-top-menu-color">
                                            <?=$this->lang->line('frontendmenu_edit_your_menu_below')?>,&nbsp;
                                            <span class="menu-create-option">
                                                <span >
                                                    <?=$this->lang->line('frontendmenu_or')?> <a href="#" class="create-new-menu"><?=$this->lang->line('frontendmenu_create_a_new_menu')?></a>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="panel-group <?=!inicompute($fmenus) ? 'disable-menu' : '' ?>" id="menu-settings" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="page-heading-pages">
                                            <h4 class="panel-title wp-panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#menu-settings" href="#menu-settings-collapse-pages" aria-expanded="true" aria-controls="menu-settings-collapse-pages" class="menu-click-button">
                                                    <?=$this->lang->line('frontendmenu_pages')?>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="menu-settings-collapse-pages" class=" panel-collapse in collapse show" role="tabpanel" aria-labelledby="page-heading-pages">
                                            <div class="panel-body">
                                                <ul class="nav nav-tabs bottom-border-none" id="custom_tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active wp-border-tab-color" id="edit" data-toggle="tab" href="#view-all" role="tab" aria-controls="view-all" aria-selected="true">
                                                            <?=$this->lang->line('frontendmenu_view_all')?>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane wp-border-box-color fade show active" id="most-recent" role="tabpanel" aria-labelledby="edit-menus-tab">
                                                        <div class="menu-settings-header pages-all-checkbox">
                                                            <?php if($pages) { foreach ($pages as $page) { if($page->status == 1) { ?>
                                                                <div class="form-group">
                                                                    <input class="pages-list-checked" type="checkbox" id="page-<?=$page->pageID?>">
                                                                    <label for="page-<?=$page->pageID?>"><?=namesorting($page->title, 35)?></label>
                                                                </div>
                                                            <?php } } } ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="menu-settings-footer clearfix">
                                                    <?php if(inicompute($fmenus)) { ?>
                                                        <a href="#" class="select-btn pull-left unchecked" id="pages-select-all"><?=$this->lang->line('frontendmenu_select_all')?></a>
                                                    <?php } ?>
                                                    <input <?=!inicompute($fmenus) ? 'disabled' : '' ?> type="button" class="submit-btn pull-right pages-add" value="<?=$this->lang->line('frontendmenu_add_to_menu')?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="page-heading-posts">
                                            <h4 class="panel-title wp-panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#menu-settings" href="#menu-settings-collapse-posts" aria-expanded="false" aria-controls="menu-settings-collapse-posts" class="menu-click-button">
                                                    <?=$this->lang->line('frontendmenu_posts')?>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="menu-settings-collapse-posts" class="panel-collapse collapse" role="tabpanel" aria-labelledby="page-heading-posts">
                                            <div class="panel-body">
                                                <ul class="nav nav-tabs bottom-border-none" id="custom_tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active wp-border-tab-color" id="edit" data-toggle="tab" href="#view-all" role="tab" aria-controls="view-all" aria-selected="true">
                                                            <?=$this->lang->line('frontendmenu_view_all')?>
                                                        </a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content">
                                                    <div class="tab-pane wp-border-box-color fade show active" id="most-recent" role="tabpanel" aria-labelledby="edit-menus-tab">
                                                        <div class="menu-settings-header posts-all-checkbox">
                                                            <?php if($posts) { foreach ($posts as $post) { if($post->status == 1) { ?>
                                                                <div class="form-group">
                                                                    <input class="posts-list-checked" type="checkbox" id="post-<?=$post->postID?>">
                                                                    <label for="post-<?=$post->postID?>"><?=namesorting($post->title, 35)?></label>
                                                                </div>
                                                            <?php } } } ?>
                                                        </div>
                                                    </div>

                                                    <div class="menu-settings-footer clearfix">
                                                        <?php if(inicompute($fmenus)) { ?>
                                                            <a href="#" class="select-btn pull-left unchecked" id="posts-select-all"><?=$this->lang->line('frontendmenu_select_all')?></a>
                                                        <?php } ?>
                                                        <input <?=!inicompute($fmenus) ? 'disabled' : '' ?> type="button" class="submit-btn pull-right posts-add" value="<?=$this->lang->line('frontendmenu_add_to_menu')?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="page-heading-links">
                                            <h4 class="panel-title wp-panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#menu-settings" href="#menu-settings-collapse-links" aria-expanded="false" aria-controls="menu-settings-collapse-links" class="menu-click-button">
                                                    <?=$this->lang->line('frontendmenu_custom_links')?>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="menu-settings-collapse-links" class="panel-collapse collapse" role="tabpanel" aria-labelledby="page-heading-links">
                                            <div class="panel-body">
                                                <div class="menu-settings-header">
                                                    <div class="form-group">
                                                        <label for="menu-settings-url"><?=$this->lang->line('frontendmenu_url')?></label>
                                                        <input type="text" name="menu-settings-url" id="menu-settings-url" class="form-control url-link-field" value="http://">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="menu-settings-link"><?=$this->lang->line('frontendmenu_link_text')?></label>
                                                        <input type="text" name="menu-settings-link" id="menu-settings-link" class="form-control url-link-text">
                                                    </div>
                                                </div>
                                                <div class="menu-settings-footer clearfix">
                                                    <input <?=!inicompute($fmenus) ? 'disabled' : '' ?> type="button" class="submit-btn pull-right links-add" value="<?=$this->lang->line('frontendmenu_add_to_menu')?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-8">
                                <div id="menu-management">
                                    <div id="create-new-menu-box" <?=inicompute($fmenus) ? 'style="display: none"' : '' ?>>
                                        <div class="menu-management-header">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="menu-label-italic-style" for="create-menu-name"><?=$this->lang->line('frontendmenu_menu_name')?></label>
                                                        <input type="text" name="" value="" class="form-control" id="create-menu-name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="publishing-menu-action pull-right">
                                                        <input type="button" class="save-menu submit-create-new-menu" value="<?=$this->lang->line('frontendmenu_create_menu')?>" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="menu-post-body">
                                            <p><?=$this->lang->line('frontendmenu_create_menu_desc')?></p>
                                            <div class="menu-management-footer clearfix">
                                                <input type="button" class="save-menu pull-right submit-create-new-menu" value="<?=$this->lang->line('frontendmenu_create_menu')?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div id="menu-manage-box" <?=!inicompute($fmenus) ? 'style="display: none"' : '' ?>>
                                        <div class="menu-management-header">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="menu-label-italic-style" for="menu-name"><?=$this->lang->line('frontendmenu_menu_name')?></label>
                                                        <input type="text" name="" value="<?=$getactivefmenuName?>" class="form-control" id="menu-name">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="publishing-menu-action pull-right">
                                                        <input type="button" <?=!inicompute($fmenus) ? 'disabled' : '' ?> name="save-name" class="save-menu submit-menu" value="<?=$this->lang->line('frontendmenu_save_menu')?>" >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="menu-post-body">
                                            <h3><?=$this->lang->line('frontendmenu_menu_structure')?></h3>
                                            <p><?=$this->lang->line('frontendmenu_menu_structure_desc')?></p>
                                            <div id="menu-edit">
                                                <div class="panel-group" id="menu-item" role="tablist" aria-multiselectable="true">
                                                    <ol class="sortable">
                                                        <?=$menushow?>
                                                    </ol>
                                                </div>
                                            </div>

                                            <div class="menu-settings">
                                                <h3><?=$this->lang->line('frontendmenu_menu_settings')?></h3>
                                                <fieldset class="menu-settings-list">
                                                    <legend class="menu-settings-list-name"><?=$this->lang->line('frontendmenu_display_location')?></legend>
                                                    <div class="menu-settings-input">
                                                        <input <?=inicompute($activefmenu) ? ($activefmenu->topbar == 1) ? ' checked="checked" ' : '' : ''?> type="checkbox"  name="menu-locations_top" id="locations-top" value="1">
                                                        <label for="locations-top"><?=$this->lang->line('frontendmenu_top_menu')?></label>
                                                    </div>
                                                    <div class="menu-settings-input">
                                                        <input <?=inicompute($activefmenu) ? ($activefmenu->social == 1) ? ' checked="checked" ' : '' : ''?>  type="checkbox" name="menu_locations_social" id="locations-social" value="1">
                                                        <label for="locations-social"><?=$this->lang->line('frontendmenu_social_links_menu')?></label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="menu-management-footer clearfix">
                                                <a href="#" class="delete-btn pull-left"><?=$this->lang->line('frontendmenu_delete_menu')?></a>
                                                <input <?=!inicompute($fmenus) ? 'disabled' : '' ?> type="button"  class="save-menu pull-right submit-menu" value="<?=$this->lang->line('frontendmenu_save_menu')?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane bg-color fad " id="manage-locations" role="tabpanel" aria-labelledby="manage-locations-tab">
                        <?php
                            $topBarMenuSelect = 0;
                            $socialMenuSelect = 0;
                            $allMenus['0'] = '— '.$this->lang->line('frontendmenu_select_a_menu').' —';
                            if(inicompute($fmenus)) {
                                foreach ($fmenus as $fmenu) {
                                    $allMenus[$fmenu->fmenuID] = $fmenu->menu_name;
                                    if($fmenu->topbar == 1) {
                                        $topBarMenuSelect = $fmenu->fmenuID;
                                    }

                                    if($fmenu->social == 1) {
                                        $socialMenuSelect = $fmenu->fmenuID;
                                    }
                                }
                            }
                        ?>
                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" action="<?=site_url('frontendmenu/managelocation')?>">
                            <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                            <div class="form-group">
                                <label for="top_menu_list" class="col-sm-2 control-label menu-label-for-mange-location">
                                    <?=$this->lang->line("frontendmenu_top_menu")?>
                                </label>
                                <div class="col-sm-3">
                                    <?php
                                        echo form_dropdown("top_menu_list", $allMenus, set_value("top_menu_list", $topBarMenuSelect), "id='top_menu_list' class='form-control'");
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="social_menu_list" class="col-sm-2 control-label menu-label-for-mange-location">
                                    <?=$this->lang->line("frontendmenu_social_links_menu")?>
                                </label>
                                <div class="col-sm-3">
                                    <?php
                                        echo form_dropdown("social_menu_list", $allMenus, set_value("social_menu_list", $socialMenuSelect), "id='social_menu_list' class='form-control'");
                                    ?>
                                </div>
                            </div>
                            <div>
                                <button <?=!inicompute($fmenus) ? 'disabled' : '' ?> type="submit" class="btn save-menu"><?=$this->lang->line('frontendmenu_save_changes')?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</article>