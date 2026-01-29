    <aside class="sidebar">
        <div class="sidebar-container">
            <div class="sidebar-header">
                <div class="brand">
                    <div class="logo">
                        <img src="<?=base_url('uploads/general/'.$generalsettings->logo)?>">
                    </div>
                    <?=namesorting($generalsettings->system_name, 18)?>
                </div>
            </div>

            <nav class="menu">
                <ul class="sidebar-menu metismenu" id="sidebar-menu">
                    <?php
                        if(inicompute($dbMenus)) {
                            $menuDesign = '';
                            display_menu($dbMenus, $menuDesign);
                            echo $menuDesign;
                        }
                    ?>
                </ul>
            </nav>
        </div>
    </aside>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>
    <div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
    <div class="mobile-menu-handle"></div>