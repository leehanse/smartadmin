<div class="navbar-content">
        <!-- start: SIDEBAR -->
        <div class="main-navigation navbar-collapse collapse">
                <!-- start: MAIN MENU TOGGLER BUTTON -->
                <div class="navigation-toggler">
                        <i class="clip-chevron-left"></i>
                        <i class="clip-chevron-right"></i>
                </div>
                <!-- end: MAIN MENU TOGGLER BUTTON -->
                <!-- start: MAIN NAVIGATION MENU -->
                <?php 
                    $currentRouteName = Route::getCurrentRoute()->getName();
                    $admin_navs       = Config::get('core::backend-nav-menu');                    
                ?>
                <ul class="main-navigation-menu">
                    <?php if(count($admin_navs)):?>
                        <?php foreach($admin_navs as $nav):?>
                            <?php 
                                $url = isset($nav['url']) ? $nav['url'] : '';
                                $route = isset($nav['route']) ? $nav['route'] : '';
                                $icon = isset($nav['icon']) ? $nav['icon'] : '';
                                $sub_menus = isset($nav['sub-menus']) ? $nav['sub-menus'] : array();
                                $nav_title = isset($nav['title']) ? $nav['title'] : '';
                                $references_routes = isset($nav['references-routes']) ? $nav['references-routes'] : array();
                                $permission   = isset($nav['permission']) ? $nav['permission'] : null;
                                
                                // check active in sub_menus
                                $check_nav_item_active = false;
                                if($route == $currentRouteName) $check_nav_item_active = true;
                                
                                $display = true;
                                
                                $check_permission = true;
                                
                                if($permission){
                                    $arr_permission = explode(',',$permission);                                    
                                    foreach($arr_permission as $p){
                                        if(!Sentry::getUser()->hasAccess($p)) $check_permission = false;
                                    }
                                }
                                
                                $count_permissions = 0;
                                $count_check_permission_failed = 0;
                                
                                if(count($sub_menus)){
                                    foreach($sub_menus as $sm_item){                                        
                                        if(isset($sm_item['route']) && $sm_item['route'] == $currentRouteName){
                                            $check_nav_item_active = true;
                                            break;
                                        }
                                    }
                                }                                
                                
                                if(count($sub_menus)){
                                    foreach($sub_menus as $sm_item){                                        
                                        if(isset($sm_item['permission']) && $sm_item['permission']){
                                            $count_permissions++;
                                            $arr_sm_item_permissions = explode(',',$sm_item['permission']);
                                            foreach($arr_sm_item_permissions as $sm_p){
                                                if(!Sentry::getUser()->hasAccess($sm_p)) $count_check_permission_failed++;
                                                break;
                                            }
                                        }
                                    }
                                }                                
                                
                                if(!$check_permission) $display = false;
                                
                                if($count_check_permission_failed == $count_permissions && $count_permissions > 0){
                                    $display = false;
                                }
                                
                                if(count($references_routes) && in_array($currentRouteName, $references_routes)) 
                                    $check_nav_item_active = true;
                                //echo "<li>{$count_check_permission_failed}>>{$count_permissions}>>{$check_permission}--{$display}--{$nav_title}</li>";
                            ?>
                            <?php if($display):?>
                                <li <?php if($check_nav_item_active) echo 'class="active open"';?>>
                                    <?php 
                                        if($url) $href = $url;
                                        if($route) $href = URL::route($route);
                                        if(count($sub_menus)) $href = 'javascript:void(0)';
                                    ?>
                                    <a href="<?php echo $href;?>">
                                        <?php if($icon):?>
                                            <i class="<?php echo $icon;?>"></i>
                                        <?php endif;?>
                                        <?php if($nav_title):?>
                                            <span class="title"><?php echo $nav_title;?></span>
                                        <?php endif;?>
                                    </a> 
                                    <?php if(count($sub_menus)):?>
                                        <ul class="sub-menu">
                                            <?php foreach($sub_menus as $sub_menu):?>
                                                <?php
                                                    $sm_url = isset($sub_menu['url']) ? $sub_menu['url'] : '';
                                                    $sm_route = isset($sub_menu['route']) ? $sub_menu['route'] : '';
                                                    $sm_icon = isset($sub_menu['icon']) ? $sub_menu['icon'] : '';
                                                    $sm_sub_menus = isset($sub_menu['sub-menus']) ? $sub_menu['sub-menus'] : array();
                                                    $sm_nav_title = isset($sub_menu['title']) ? $sub_menu['title'] : '';
                                                    $sm_references_routes = isset($sub_menu['references-routes']) ? $sub_menu['references-routes'] : array();
                                                    $sm_permission = isset($sub_menu['permission']) ? $sub_menu['permission'] : null;

                                                    if($sm_url) $sm_href = $url;
                                                    if($sm_route) $sm_href = URL::route($sm_route);
                                                    if(count($sm_sub_menus)) $sm_href = 'javascript:void(0)';

                                                    $sm_check_nav_item_active = false;
                                                    if($sm_route == $currentRouteName) $sm_check_nav_item_active = true;
                                                    if(count($sm_sub_menus)){
                                                        foreach($sm_sub_menus as $sm_sm_item){
                                                            if(isset($sm_sm_item['route']) && $sm_sm_item['route'] == $currentRouteName){
                                                                $sm_check_nav_item_active = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    if(count($sm_references_routes) && in_array($currentRouteName, $sm_references_routes)) 
                                                        $sm_check_nav_item_active = true; 

                                                    $sm_check_permission = true;
                                                    if($sm_permission){
                                                        $arr_sm_permissions = $sm_permission = explode(',',$sm_permission);
                                                        if(count($arr_sm_permissions)){
                                                            foreach($arr_sm_permissions as $sm_p){
                                                                if(!Sentry::getUser()->hasAccess($sm_p)) $sm_check_permission = false;
                                                            }
                                                        }
                                                    }
                                                ?>
                                                <?php if($sm_check_permission):?>
                                                    <li <?php if($sm_check_nav_item_active) echo 'class="active open"';?>>
                                                        <a href="<?php echo $sm_href;?>">
                                                            <?php if($sm_icon):?>
                                                                <i class="<?php echo $sm_icon;?>"></i>
                                                            <?php endif;?>
                                                            <?php if($sm_nav_title):?>
                                                                <span class="title"><?php echo $sm_nav_title;?></span>
                                                            <?php endif;?>
                                                        </a>                                            
                                                    </li>      
                                                <?php endif;?>
                                            <?php endforeach;?>                                                
                                        </ul> 
                                    <?php endif;?>
                                </li>
                            <?php endif;?>
                        <?php endforeach;?>
                    <?php endif;?>
                </ul>                
                <!-- end: MAIN NAVIGATION MENU -->
        </div>
        <!-- end: SIDEBAR -->
</div>