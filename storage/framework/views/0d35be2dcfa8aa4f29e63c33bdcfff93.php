
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
              </div>
              <!-- /.card-header -->
              <form action="<?php echo e(route('profile.update',$users->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('put'); ?>
                <div class="card-body">
                  <div class="row">
                      <div class="col-md-6 col-sm-6 col-lg-6">
                        <div class="form-group"> 
                            <label for="role">You are</label>
                            <div class="form-controls">
                                <select class="form-control" name="role" id="role" aria-label="Select Role">
                                   <option value="" disabled selected><?php echo e($users->getRoleNames()->first()); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <div class="form-controls">
                              <input type="email" name="email" id="email" placeholder="User Email Id" class="form-control" required value="<?php echo e($users->email); ?>" readonly>
                            </div>
                        </div>
                       <div class="form-group">
                            <label for="name">Password</label>
                            <div class="form-controls">
                              <input type="text" name="password" id="password" class="form-control" required value="<?php echo e($users->core_password); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">Photograph</label>
                            <div class="form-controls">
                              <input type="file" name="photo" id="photo" class="form-control">
                            </div>
                        </div>
                        <span>
                          <img class="img img-thumbnail" src="<?php echo e(url('/')); ?>/admin/uploads/user/<?php echo e(($users->photo)?$users->photo:'user.jpeg'); ?>" style="width:30%;" />
                          <a href="<?php echo e(route('user.deletePhoto', $users->id)); ?>" class="delete-confirm-get" title="Delete Photograph">
                              <i class="fa fa-trash text-red"></i>
                          </a>
                        </span>
                      </div>
                      <div class="col-md-6 col-sm-6 col-lg-6">
                          <div class="form-group">
                              <label for="name">User Name:</label>
                              <div class="form-controls">
                                <input type="text" name="name" id="name" placeholder="User Name" class="form-control" required value="<?php echo e($users->name); ?>">
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="name">Profile Name:</label>
                              <div class="form-controls">
                                <input type="text" name="display_name" id="display_name" placeholder="Profile Name" class="form-control" required value="<?php echo e($users->display_name); ?>" readonly>
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="name">Mobile</label>
                              <div class="form-controls">
                                <input type="number" name="mobile" id="mobile" placeholder="User Phone number  " class="form-control" required value="<?php echo e($users->mobile); ?>">
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="name">Address</label>
                              <div class="form-controls">
                                <textarea name="address" id="address" class="form-control"><?php echo e($users->address); ?></textarea>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12 p-4">
                          <div class="form-group">
                              <label for="name">Description</label>
                              <div class="form-controls">
                                <textarea name="description" id="description" class="form-control"><?php echo $users->description; ?></textarea>
                              </div>
                          </div>
                          <div class="card-footer">
                              <input type="submit" name="submit" id="submit" class="btn btn-success float-sm-right" value="Save Profile">
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
 <script>
  $(document).ready(function () {
      $('#description').summernote({
          spellCheck: true, // Enable browser spell check
          toolbar: [
              ['style', ['style', 'bold', 'italic', 'underline', 'strikethrough', 'clear']],
              ['font', ['fontname', 'fontsize', 'color', 'superscript', 'subscript']],
              ['para', ['ul', 'ol', 'paragraph', 'height']],
              ['insert', ['picture', 'link', 'video', 'table', 'hr']],
              ['view', ['fullscreen', 'codeview', 'help']]
          ],
          height: 300, // Set the height of the editor
      });
  });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/admin/profile/edit.blade.php ENDPATH**/ ?>