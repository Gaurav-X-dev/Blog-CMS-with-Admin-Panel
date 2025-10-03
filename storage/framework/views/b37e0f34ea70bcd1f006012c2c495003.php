<?php $__env->startSection('content'); ?>
    <!-- Layout Category Section -->
    
    <!-- Hero Showcase Section (New Style) -->
    <header class="hero-showcase-new d-flex flex-column">
        <div class="container-fluid flex-grow-1 d-flex flex-column">
            <!-- First horizontal half -->
            <div class="row flex-grow-1 align-items-center py-4 py-md-0">
                <div class="col-md-6 d-flex justify-content-center align-items-center order-md-1 order-1">
                    <div class="hero-text-content text-center text-md-start">
                        <h3 class="hero-title-new">Discover & Organize Your Web</h3>
                        <p class="hero-subtitle-new">Effortlessly save, categorize, and share your favorite online resources
                            with our intuitive platform.</p>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-center align-items-center order-md-2 order-2">
                    <div class="hero-links-card-light p-4 w-100">
                        <h3 class="mb-3 text-dark">My Bookmarking Websites</h3>
                        <div class="hero-links-carousel-wrapper">
                            <ul class="list-unstyled hero-links-carousel" id="my-bookmarks-carousel">
                                <?php $__empty_1 = true; $__currentLoopData = $stories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <li>
                                        <a href="<?php echo e(Str::startsWith($story->page_link, ['http://', 'https://']) ? $story->page_link : 'https://' . $story->page_link); ?>"
                                            target="_blank" class="hero-link-item-dark">
                                            <i class="bi bi-link-45deg me-1"></i>
                                            <?php echo e(\Illuminate\Support\Str::words(strip_tags($story->title ?? $story->page_link), 10, '...')); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <li>No approved bookmarks found.</li>
                                <?php endif; ?>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Second horizontal half -->
            <div class="row flex-grow-1 align-items-center py-4 py-md-0">
                <div class="col-md-6 d-flex justify-content-center align-items-center order-md-1 order-1">
                    <div class="hero-links-card-light p-4 w-100">
                        <h3 class="mb-3 text-dark">Latest Links <span class="badge bg-primary ms-2">NEW</span></h3>
                        <div class="hero-links-carousel-wrapper">
                            <ul class="list-unstyled hero-links-carousel" id="my-bookmarks-carousel">
                                <?php $__empty_1 = true; $__currentLoopData = $stories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <li>
                                        <a href="<?php echo e(Str::startsWith($story->page_link, ['http://', 'https://']) ? $story->page_link : 'https://' . $story->page_link); ?>"
                                            target="_blank" class="hero-link-item-dark">
                                            <i class="bi bi-link-45deg me-1"></i>
                                            <?php echo e(\Illuminate\Support\Str::limit(strip_tags($story->link ?? $story->page_link), 60, '...')); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <li>No approved bookmarks found.</li>
                                <?php endif; ?>
                            </ul>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-center align-items-center order-md-2 order-2">
                    <div class="hero-text-content text-center text-md-start">
                        <h3 class="hero-title-new">Stay Ahead with Curated Content</h3>
                        <p class="hero-subtitle-new">Explore a vibrant community of shared knowledge and never miss a trend
                            in your field.</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <h2 class="text-center mb-5 section-title-dark">Explore Bookmarks</h2>
        <div class="row">
            <div class="col-lg-8">
                <div class="row bookmark-grid" id="bookmark-grid-container">
                    <?php $__currentLoopData = $stories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $story): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bookmark-item">
                            <h5 class="bookmark-title">
                                <a href="<?php echo e(route('story.details', $story->slug)); ?>"><?php echo e($story->title); ?></a>
                            </h5>

                            <div class="bookmark-meta-line">
                                <span>👤 <?php echo e($story->member->username); ?></span>
                                <span>🕒 <?php echo e($story->created_at->diffForHumans()); ?></span>
                                <span>👁️ <?php echo e($story->views); ?> views</span>

                                <?php if($story->tag): ?>
                                    <span>🏷️ <?php echo e($story->tag); ?></span>
                                <?php endif; ?>

                                <?php if($story->page_link): ?>
                                    <span>🔗 <a href="<?php echo e($story->page_link); ?>" target="_blank">Source</a></span>
                                <?php endif; ?>
                            </div>

                            
                            <p class="bookmark-description">
                                <?php echo e(\Illuminate\Support\Str::words(strip_tags($story->description), 20, '...')); ?>

                            </p>

                            <div class="bookmark-actions">
                                <a href="<?php echo e(route('story.details', $story->slug)); ?>" class="bookmark-readmore-btn">Read
                                    more</a>
                                <span class="bookmark-category-badge"><?php echo e($story->category->name); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
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
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/index.blade.php ENDPATH**/ ?>