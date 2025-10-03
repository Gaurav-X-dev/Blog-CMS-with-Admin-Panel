
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
          <li class="breadcrumb-item"><a href="<?php echo e(route('category.index')); ?>">List</a></li>
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
          </div>
          <!-- /.card-header -->
          <form action="<?php echo e(route('category.store')); ?>" method="POST">
              <?php echo csrf_field(); ?>
              <div class="card-body">
                  <div class="row">
                      <!-- Category Name -->
                      <div class="col-md-6 col-sm-6 col-lg-6">
                          <div class="form-group">
                              <label for="name">Name</label>
                              <div class="form-controls">
                                  <input type="text" name="name" id="name" placeholder="Please enter Name" class="form-control" required>
                              </div>
                          </div>
                      </div>
                      <!-- Parent Category Selection -->
                      <div class="col-md-6 col-sm-6 col-lg-6">
                          <div class="form-group">
                              <label for="parent_id">Parent Category (optional)</label>
                              <div class="form-controls">
                                  <select name="parent_id" id="parent_id" class="form-control select2">
                                      <option value="">-- Base Category --</option>
                                      <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </select>
                              </div>
                          </div>
                      </div>

                      <!-- Submit Button -->
                      <div class="col-md-12 p-4">
                          <div class="card-footer">
                              <input type="submit" name="submit" id="submit" class="btn btn-success float-sm-right" value="Save Category">
                          </div>
                      </div>
                  </div>
              </div>
          </form>

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
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/admin/category/create.blade.php ENDPATH**/ ?>