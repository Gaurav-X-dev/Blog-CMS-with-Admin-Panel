
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
                <a href="<?php echo e(route('roles.create')); ?>">
                <button class="btn btn-block btn-default btn-sm float-sm-right col-sm-2"><i class="fa fa-plus"></i> Add Role</button>
               </a> 
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>S.N.</th>
                    <th>Name</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php $i=1; ?>
                  <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>
                        <td><?php echo e($i); ?></td>
                        <td><?php echo e($role->name); ?></td>
                        <td>
                          <a href="<?php echo e(route('roles.edit',$role->id)); ?>"><i class="fa fa-edit text-green"></i></a>
                            <form action="<?php echo e(route('roles.destroy', $role->id)); ?>" method="POST" class="delete-form" style="display:inline;">
                              <?php echo csrf_field(); ?>
                              <?php echo method_field('DELETE'); ?>
                              <button type="submit" style="border: none; background: none; cursor: pointer;" class="delete-confirm">
                                  <i class="fa fa-trash text-red"></i>
                              </button>
                          </form>

                        </td>
                      </tr>
                      <?php $i++;?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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


<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/admin/roles/index.blade.php ENDPATH**/ ?>