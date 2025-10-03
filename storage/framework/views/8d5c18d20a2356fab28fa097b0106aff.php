
<?php $__env->startSection('content'); ?>
    <div class="page-content container mt-4">
        <div class="row">
            <!-- Left Column (Form Section) -->
            <div class="col-lg-8 mb-5">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-header text-white rounded-top-4" style="background-color: #0d6efd;">
                        <h4 class="mb-1">✍️ Share Your Story</h4>
                        <small class="text-light">Let the world hear your voice — add your unique story.</small>
                    </div>
                    <div class="card-body bg-light rounded-bottom-4">
                        <form action="<?php echo e(route('story.add')); ?>" method="POST" class="px-2 py-3">
                            <?php echo csrf_field(); ?>

                            
                            <div class="mb-4">
                                <label for="category_id" class="form-label fw-semibold text-secondary">📂 Category <span
                                        class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select form-select-lg select2 w-75"
                                    required>
                                    <option value="">-- Select Category --</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            
                            <div class="mb-4">
                                <label for="title" class="form-label fw-semibold text-secondary">📝 Story Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="title" id="title"
                                    class="form-control form-control-lg border-primary"
                                    placeholder="e.g. Tortured by the Taliban, Locked Up in the US" required>
                            </div>

                            
                            <div class="mb-4">
                                <label for="page_link" class="form-label fw-semibold text-secondary">🔗 Page Link</label>
                                <input type="url" name="page_link" id="page_link"
                                    class="form-control form-control-lg border-secondary" placeholder="https://example.com"
                                    required>
                            </div>

                            
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold text-secondary">📖 Story Description
                                    <span class="text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control border-dark-subtle" rows="6"
                                    placeholder="Describe your experience..." required></textarea>
                            </div>

                            
                            <div class="mb-4">
                                <label for="tag" class="form-label fw-semibold text-secondary">🏷️ Tags
                                    (comma-separated)</label>
                                <input type="text" name="tag" id="tag"
                                    class="form-control form-control-lg border-info" placeholder="e.g. tech, life, career">
                            </div>

                            
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-send-plus me-2"></i> Submit Story
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <!-- Right Column (Popular Bookmarks Sidebar) -->
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/createStory.blade.php ENDPATH**/ ?>