
<?php $__env->startSection('content'); ?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
          <li class="breadcrumb-item active"><?php echo e($label); ?></li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- Content Wrapper. Contains page content -->
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-teal">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo e($label); ?></h3>
                    </div>

                  <!-- /.card-header -->
                    <div class="card-body">
                        <!-- Content Header (Page header) -->
                    <h2>Role: <?php echo e($user->getRoleNames()->first()); ?> | <?php echo e($user->name); ?></h2>
            
                    <form action="<?php echo e(route('users.update.permissions', $user->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="permissions">Permissions</label>
                            <div class="row">
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="permissions[]" value="<?php echo e($permission->name); ?>"
                                                <?php if($user->hasPermissionTo($permission->name)): ?> checked <?php endif; ?> id="customCheckbox<?php echo e($permission->id); ?>">
                                            <label for="customCheckbox<?php echo e($permission->id); ?>" class="custom-control-label"><?php echo e($permission->name); ?></label>
                                        </div>
                                    </div>
                                    <?php if($loop->iteration % 3 == 0): ?>
                                        </div><div class="row">
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="col-md-12 p-4">
                          <div class="card-footer">
                              <input type="submit" name="submit" id="submit" class="btn btn-success float-sm-right" value="Update Permission">
                          </div>
                      </div>
                    </form>  
                    </div>
                
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/admin/users/permission.blade.php ENDPATH**/ ?>