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
          <li class="breadcrumb-item"><a href="<?php echo e(route('setting.index')); ?>">List</a></li>
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
              <form action="<?php echo e(route('setting.update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('put'); ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <div class="form-group">
                                <label for="name">Company Name</label>
                                <div class="form-controls">
                                    <input type="text" name="company_name" id="company_name" placeholder="Your Company Name" class="form-control" required value="<?php echo e($data->company_name); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Email</label>
                                <div class="form-controls">
                                    <input type="email" name="email" id="email" placeholder="Email Id" class="form-control" required value="<?php echo e($data->email); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Contact No.</label>
                                <div class="form-controls">
                                    <input type="number" name="mobile" id="mobile" placeholder="Enter Contact No." class="form-control" required value="<?php echo e($data->mobile); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Company Logo</label>
                                <div class="form-controls">
                                    <input type="file" name="logo" id="logo" class="form-control">
                                </div>
                                <span>
                                    <img src="<?php echo e(url('/')); ?>/admin/uploads/logo/<?php echo e(($data->logo)?$data->logo:'nopreview.png'); ?>" class="img img-thumbnail" style="width:30%;">
                                    <a href="<?php echo e(route('deletePhoto', [$data->id,'logo'])); ?>" class="delete-confirm-get">
                                        <i class="fa fa-trash text-red"></i>
                                    </a>

                                </span>
                            </div>
                            <div class="form-group">
                                <label for="name">Facebook Link</label>
                                <div class="form-controls">
                                <input type="text" name="facebook" id="facebook" placeholder="Enter your facebook link  " class="form-control" value="<?php echo e($data->facebook); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Twitter Link</label>
                                <div class="form-controls">
                                <input type="text" name="twitter" id="twitter" placeholder="Enter your twitter link  " class="form-control" value="<?php echo e($data->twitter); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Instagram Link</label>
                                <div class="form-controls">
                                <input type="text" name="instagram" id="instagram" placeholder="Enter your instagram link  " class="form-control" value="<?php echo e($data->instagram); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-lg-6">
                            <div class="form-group">
                                <label for="name">Landline no.</label>
                                <div class="form-controls">
                                <input type="text" name="landline" id="landline" placeholder="Enter Landline Number  " class="form-control" value="<?php echo e($data->landline); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Website Address</label>
                                <div class="form-controls">
                                <input type="text" name="website" id="website" placeholder="Enter Website Address  " class="form-control" value="<?php echo e($data->website); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Address</label>
                                <div class="form-controls">
                                <textarea name="address" id="address" class="form-control" required><?php echo e($data->address); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Footer Logo</label>
                                <div class="form-controls">
                                    <input type="file" name="footer_logo" id="footer_logo" class="form-control">
                                </div>
                                <span>
                                    <img src="<?php echo e(url('/')); ?>/admin/uploads/logo/<?php echo e(($data->footer_logo)?$data->footer_logo:'nopreview.png'); ?>" class="img img-thumbnail" style="width:30%;">
                                    <a href="<?php echo e(route('deletePhoto', [$data->id,'footer'])); ?>" class="delete-confirm-get">
                                        <i class="fa fa-trash text-red"></i>
                                    </a>
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="name">Linkedin Link</label>
                                <div class="form-controls">
                                <input type="text" name="linkedin" id="linkedin" placeholder="Enter your linkedin link  " class="form-control" value="<?php echo e($data->linkedin); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Yputube Link</label>
                                <div class="form-controls">
                                <input type="text" name="youtube" id="youtube" placeholder="Enter your youtube link  " class="form-control" value="<?php echo e($data->youtube); ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Google Webmaster Key</label>
                                <div class="form-controls">
                                <input type="text" name="google_web" id="google_web" placeholder="Enter your verification code  " class="form-control" value="<?php echo e($data->google_web); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Google Analytics (GA4) Script</label>
                                <div class="form-controls">
                                <textarea name="google_a" id="google_a" class="form-control"  rows="10" >
                                    <?php echo $data->google_a; ?>

                                </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 p-4">
                            <div class="card-footer">
                                <input type="submit" name="submit" id="submit" class="btn btn-success float-sm-right" value="Save Settings">
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

<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/admin/setting/edit.blade.php ENDPATH**/ ?>