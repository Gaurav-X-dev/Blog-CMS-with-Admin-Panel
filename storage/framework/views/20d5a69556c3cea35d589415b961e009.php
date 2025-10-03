
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
<!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card card-teal">
              <div class="card-header">
                <h3 class="card-title"><?php echo e($label); ?></h3>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create user')): ?>
                <a href="<?php echo e(route('users.create')); ?>">
                <button class="btn btn-block btn-default btn-sm float-sm-right col-sm-2"><i class="fa fa-plus"></i> Add User</button>
               </a> 
               <?php endif; ?>
              </div>

              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.N.</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Profile</th>
                    <th>Created By</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php if(!empty($users)): ?>
                  <?php $i=1; ?>
                  <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td><?php echo e($i); ?></td>
                        <td><img class="img img-thumbnail" src="<?php echo e(url('/')); ?>/admin/uploads/user/<?php echo e(($user->photo)?$user->photo:'user.jpeg'); ?>" style="width:30%;" /></td>
                        <td><?php echo e($user->name); ?></td>
                        <td><?php echo e($user->display_name); ?></td>
                        <td><?php echo e($user->creator ? $user->creator->name : 'N/A'); ?></td>
                        <td><?php echo e($user->getRoleNames()->first()); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td><?php echo e(($user->mobile)?$user->mobile:'-----'); ?></td>
                        <td>
                          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit user')): ?>
                              <?php if(Auth::user()->can('edit user') || Auth::user()->hasRole('Super Admin')): ?>
                                  <?php if(!$user->hasRole('Super Admin')): ?> 
                                      <a href="<?php echo e(route('users.edit', $user->id)); ?>" style="margin-right: 8px;">
                                          <i class="fa fa-edit text-green"></i>
                                      </a>
                                  <?php endif; ?>
                              <?php endif; ?>
                          <?php endif; ?>

                          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete user')): ?>
                              <?php if(Auth::user()->can('delete user') || Auth::user()->hasRole('Super Admin')): ?>
                                  <?php if(!$user->hasRole('Super Admin')): ?> 
                                      <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST" class="delete-confirm" style="display:inline;">
                                          <?php echo csrf_field(); ?>
                                          <?php echo method_field('DELETE'); ?>
                                          <button type="submit" style="border: none; background: none; cursor: pointer; margin-right: 8px;">
                                              <i class="fa fa-trash text-red"></i>
                                          </button>
                                      </form>
                                  <?php endif; ?>
                              <?php endif; ?>
                          <?php endif; ?>

                          
                          <?php if(Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Vendor') || Auth::user()->can('manage permissions')): ?>
                              <a href="<?php echo e(route('users.permissions', $user->id)); ?>" title="Manage Permissions" style="font-size: 20px; display: inline-block; margin-right: 8px;">
                                  <i class="fa fa-shield"></i>
                              </a>
                          <?php endif; ?>
                        </td>

                      </tr>
                      <?php $i++;?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
<!-- /.content -->
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/admin/users/index.blade.php ENDPATH**/ ?>