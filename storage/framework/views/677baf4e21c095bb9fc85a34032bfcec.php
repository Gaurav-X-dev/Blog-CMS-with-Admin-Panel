<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link" target="_blank">Home</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
<!-- /.navbar -->
<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="<?php echo e(url('/')); ?>/admin/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo e(ucfirst(Auth::user()->name)); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <!-- Dashboard -->
              <li class="nav-item menu-open">
                  <a href="<?php echo e(route('dashboard')); ?>" class="nav-link active">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>Dashboard</p>
                  </a>
              </li>

              <!-- Master Settings -->
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['manage roles', 'manage permissions'])): ?> 
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-cogs"></i>
                      <p>
                          Core Configurations
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                    
                     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access settings')): ?>
                     <li class="nav-item">
                          <a href="<?php echo e(route('setting.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Setting</p>
                          </a>
                      </li>
                     <?php endif; ?>
                     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage permissions')): ?> 
                      <li class="nav-item">
                          <a href="<?php echo e(route('permissions.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Permissions</p>
                          </a>
                      </li>
                      <?php endif; ?>

                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage roles')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('roles.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Roles</p>
                          </a>
                      </li>
                      <?php endif; ?>
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage categories')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('category.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Category</p>
                          </a>
                      </li>
                      <?php endif; ?>
                  </ul>
              </li>
              <?php endif; ?>

              <!-- Master User -->
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          User Management
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage users')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('users.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Users</p>
                          </a>
                      </li>
                      <?php endif; ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('profile.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Users Profile</p>
                          </a>
                      </li>
                  </ul>
              </li>
               <!-- Master Member -->
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Member Management
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage member')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('member.list')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>See Member</p>
                          </a>
                      </li>
                      <?php endif; ?>
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage story')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('story.list')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>See Post Stories</p>
                          </a>
                      </li>
                      <?php endif; ?>
                  </ul>
              </li>
               <!-- Master Page -->
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage page')): ?>
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-edit"></i>
                      <p>
                          Page Management
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('page list')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('page.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Page</p>
                          </a>
                      </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu list')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('menu.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Menu</p>
                          </a>
                      </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('section list')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('section.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Section</p>
                          </a>
                      </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pagesection list')): ?>
                      <li class="nav-item">
                          <a href="<?php echo e(route('pagesection.index')); ?>" class="nav-link">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Manage Page Section</p>
                          </a>
                      </li>
                    <?php endif; ?>
                  </ul>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('list advertisement')): ?>
              <li class="nav-item">
                  <a href="<?php echo e(route('advertisement.index')); ?>" class="nav-link">
                      <i class="nav-icon fas fa-book"></i>
                      <p>
                          Manage Advertisement
                      </p>
                  </a>
              </li>
              <?php endif; ?>
              <!-- Administration -->
              <li class="nav-header">Administration</li>
              <li class="nav-item">
                  <a href="<?php echo e(route('admin.logout')); ?>" class="nav-link">
                      <i class="nav-icon fa fa-lock text-danger"></i>
                      <p class="text">Logout</p>
                  </a>
              </li>
          </ul>
      </nav>
      <!-- /.sidebar-menu -->

    </div>
    <!-- /.sidebar -->
  </aside><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/admin/layouts/navbar.blade.php ENDPATH**/ ?>