
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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('setting create')): ?>
                <a href="<?php echo e(route('setting.create')); ?>">
                <button class="btn btn-block btn-default btn-sm float-sm-right col-sm-2"><i class="fa fa-plus"></i> Add Setting</button>
               </a>
               <?php endif; ?>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Logo</th>
                    <th>Footer Logo</th>
                    <th>Company</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Contact No.</th>
                    <th>Website</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php if($data): ?>
                      <tr>
                        <td><img src="<?php echo e(url('/')); ?>/admin/uploads/logo/<?php echo e(($data->logo)?$data->logo:'nopreview.png'); ?>" class="img" style="width:200px;"></td>
                        <td><img src="<?php echo e(url('/')); ?>/admin/uploads/logo/<?php echo e(($data->footer_logo)?$data->footer_logo:'nopreview.png'); ?>" class="img" style="width:200px;"></td>
                        <td><?php echo e($data->company_name); ?></td>
                        <td><?php echo e($data->address); ?></td>
                        <td><?php echo e($data->email); ?></td>
                        <td><?php echo e($data->mobile); ?>,<?php echo e($data->landline); ?></td>
                        <td><?php echo e($data->website); ?></td>
                        <td>
                          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit setting')): ?>
                          <a href="<?php echo e(route('setting.edit',$data->id)); ?>"><i class="fa fa-edit text-green"></i></a>
                          <?php endif; ?>
                        </td>
                      </tr>
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


<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/admin/setting/index.blade.php ENDPATH**/ ?>