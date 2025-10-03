

<?php $__env->startSection('content'); ?>
<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="page-content container mt-4">
    <div class="row w-100">
        <!-- Left Column (col-8) -->
        <div class="col-md-8 pb-5">
            <h3 class="mb-4">Member Login</h3>
            <form method="POST" action="<?php echo e(route('user.getLogin')); ?>" class="shadow p-4 rounded bg-white">
                <?php echo csrf_field(); ?>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg" id="email"
                        placeholder="you@example.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control form-control-lg" id="password"
                            placeholder="Enter your password" required>
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                            data-target="#password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">Login</button>
                    <a href="<?php echo e(route('user.register')); ?>" class="btn btn-link">Not registered? Signup</a>
                </div>
            </form>
        </div>

        <!-- Right Column (col-4) -->
        <div class="col-lg-4">
            <div class="popular-bookmarks-sidebar">
                <h3 class="popular-bookmarks-heading">Popular Bookmarkings</h3>
                <div id="popular-bookmarks-container">
                    <?php $__currentLoopData = $stories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="popular-bookmark-item">
                            <h6>
                                <a href="<?php echo e(route('story.details', $story->slug)); ?>">
                                    <?php echo e(\Illuminate\Support\Str::limit($story->title, 80)); ?>

                                </a>
                            </h6>
                            <div class="popular-bookmark-item-meta">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <?php echo e($story->views); ?>

                                </span>
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <?php echo e($story->created_at->diffForHumans()); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Password Script -->
<script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const input = document.querySelector(this.dataset.target);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/login.blade.php ENDPATH**/ ?>