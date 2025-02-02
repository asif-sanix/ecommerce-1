<div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="<?php echo e(route('admin.dashboard.index')); ?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?php echo e(asset(get_app_setting('favicon')??'assets/images/logo-sm.png')); ?>" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?php echo e(asset(get_app_setting('logo')??'assets/images/logo-dark.png')); ?>" alt="" height="17">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="<?php echo e(route('admin.dashboard.index')); ?>" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?php echo e(asset(get_app_setting('favicon')??'assets/images/logo-sm.png')); ?>" alt="" width="40">
                    </span>
                    <span class="logo-lg">
                        <img src="<?php echo e(asset(get_app_setting('logo')??'assets/images/logo-light.png')); ?>" alt="" width="200">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <?php
                $menus = \App\Models\Menu::select('name','slug','icon')
                ->where(function($query){
                    $query->whereNull('parent');
                    $query->whereStatus(1);
                    $query->whereHas('rolePermissions',function($query){
                        $query->where('role_permissions.role_id','=',auth('admin')->user()->role_id);
                        $query->whereRaw("role_permissions.permission_key = concat('browse_',menus.slug)");
                    });
                })
                ->orWhere(function($query){
                    $query->whereStatus(1);
                    $query->orderBy('ordering','asc');
                    $query->whereHas('childs',function($query){
                        $query->select('slug','parent','name');
                        $query->whereStatus(1);

                        $query->whereHas('rolePermissions',function($query){
                            $query->where('role_permissions.role_id','=',auth('admin')->user()->role_id);
                            $query->whereRaw("role_permissions.permission_key = concat('browse_',laravel_reserved_0.slug)");
                        });
                    });
                })->with(['childs'=>function($query){
                    $query->select('slug','parent','name');
                    $query->whereStatus(1);

                    $query->whereHas('rolePermissions',function($query){
                        $query->where('role_permissions.role_id','=',auth('admin')->user()->role_id);
                        $query->whereRaw("role_permissions.permission_key = concat('browse_',menus.slug)");
                    });
                }])
                ->orderBy('ordering','asc')
                ->get();
            ?>


            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">

                        <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$menu->childs->count() && Route::has("admin.".Str::slug($menu->slug, '-').".index")): ?>
                                <li class="nav-item <?php echo e(request()->segment(2) == Str::slug($menu->slug, '-')?'mm-active pr-active':''); ?>">
                                    <a href="<?php echo e(route("admin.".Str::slug($menu->slug, '-').".index")); ?>" class="nav-link <?php echo e(request()->segment(2) == Str::slug($menu->slug, '-')?'active':''); ?>">
                                        <i class="<?php echo e($menu->icon??'fa fa-arrow-right'); ?>"></i> 
                                        <span><?php echo e($menu->name); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if($menu->childs->count()): ?>


                            <li class="nav-item <?php echo e(($menu->childs->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count())?'mm-active pr-active':''); ?>">
                                <a href="#menu-<?php echo e($menu->slug); ?>" class="nav-link menu-link <?php echo e(($menu->childs->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count())?'collapsed active':''); ?>" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                                    <i class="<?php echo e($menu->icon??'fa fa-list'); ?>"></i>
                                    <span data-key="<?php echo e($menu->slug); ?>"><?php echo e($menu->name); ?></span>
                                </a>
                                <div class="collapse menu-dropdown <?php echo e(($menu->childs->whereIn('slug',str_replace('-', '_', request()->segment(2)))->count())?'show':''); ?>" id="menu-<?php echo e($menu->slug); ?>">
                                      <ul class="nav nav-sm flex-column" aria-expanded="false">

                                         <?php $__currentLoopData = $menu->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                             <?php if(Route::has("admin.".Str::slug($child->slug, '-').".index")): ?>
                                                <li class="nav-item <?php echo e(($child->slug == str_replace('-', '_', request()->segment(2)))?'mm-active pr-active':''); ?>">
                                                    <a data-key="<?php echo e($child->slug); ?>" class="nav-link <?php echo e(($child->slug == str_replace('-', '_', request()->segment(2)))?'active':''); ?>" href="<?php echo e(route('admin.'.Str::slug($child->slug, '-').'.index')); ?>"><?php echo e($child->name); ?></a>
                                                </li>
                                             <?php endif; ?>

                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>   
                                        
                                      </ul>
                                </div>
                            </li>

                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 


                    
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div><?php /**PATH /home/sanix/Documents/ecommerce/resources/views/admin/layouts/aside.blade.php ENDPATH**/ ?>