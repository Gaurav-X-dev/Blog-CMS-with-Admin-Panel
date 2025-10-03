<?php
    $topAds = $advertisements->where('position', 'top')->values();
?>
<style>
    /* Remove global dropdown override */
    .dropdown:hover .dropdown-menu-end {
        display: block;
        margin-top: 3px;
        right: 0;
        left: auto;
    }

</style>
<?php if($topAds->count()): ?>
  <div class="w-100 bg-light py-3 d-flex justify-content-center">
    <div id="ad-slider" class="top-ad-slider">
        <?php $__currentLoopData = $topAds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="ad-slide" style="display: <?php echo e($index === 0 ? 'block' : 'none'); ?>;">
                <a href="<?php echo e($ad->link); ?>" target="_blank">
                    <img src="<?php echo e(asset('admin/uploads/advertisement/' . $ad->image)); ?>"
                         alt="<?php echo e($ad->title); ?>"
                         class="top-ad-image rounded shadow">
                </a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
  <style>
   .top-ad-slider {
        position: relative;
        width: 100%;
        max-width: 1000px;
        height: 140px;
    }

    @media (max-width: 576px) {
        .top-ad-slider {
            height: 100px; /* Shrink for smaller screens */
        }
    }

    .ad-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .top-ad-image {
        width: 100%;
        height: 100%;
        object-fit: contain; /* Full image visible, no crop */
        background-color: #f9f9f9; /* Optional: clean background around image */
        box-shadow: none !important;
    }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
        const slides = document.querySelectorAll('#ad-slider .ad-slide');
        let currentAdIndex = 0;

        setInterval(() => {
            slides[currentAdIndex].style.display = 'none';
            currentAdIndex = (currentAdIndex + 1) % slides.length;
            slides[currentAdIndex].style.display = 'block';
        }, 3000); // every 3 seconds
    });
  </script>
<?php endif; ?>
<!-- Upper Navbar: Animated Running Categories -->
<div class="top-navbar py-2">
    <div class="container-fluid">
        <div class="running-categories-wrapper">
            <div class="running-categories-content">
                <span class="me-3 fw-bold">Trending Categories:</span>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="running-category-item"><?php echo e($item->name); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>



<!-- Sticky Header -->
<div class="sticky-header">
    <div class="container-fluid header-top">
        <div class="d-flex header-row justify-content-between align-items-center flex-md-row flex-wrap py-2 px-3 shadow-sm bg-white">
            
            <!-- Logo -->
            <div class="logo" id="site-logo">
                <a href="<?php echo e(route('index')); ?>" class="img d-block">
                    <?php if(!empty($settings->logo)): ?>
                        <img src="<?php echo e(url('/')); ?>/admin/uploads/logo/<?php echo e($settings->logo); ?>" alt="Logo" style="width: 290px; height: auto;">
                    <?php else: ?>
                        <span class="h4 text-dark text-decoration-none"><?php echo e($settings->company_name); ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Desktop Buttons & Auth Links -->
            <div class="d-flex align-items-center gap-2 ms-auto">

                <?php if(Auth::guard('member')->check()): ?>
                    <!-- Submit Story -->
                    <a href="<?php echo e(route('user.story')); ?>">
                        <button class="btn btn-success animate-btn icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                <path d="M14.5 4L19 8.5V20a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h5.5L14.5 4z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="12" y1="17" x2="12" y2="11"/>
                                <line x1="9" y1="14" x2="15" y2="14"/>
                            </svg>
                            Submit Story
                        </button>
                    </a>

                    <!-- Authenticated Dropdown -->
                    <div class="dropdown d-none d-md-inline">
                        <button class="btn btn-outline-primary animate-btn icon-button dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                            <?php echo e(Auth::guard('member')->user()->name); ?>

                        </button>
                        <?php
                            $userid = Crypt::encrypt(Auth::guard('member')->user()->id);
                        ?>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('user.userStory', $userid)); ?>">Your Stories</a></li>
                            <li><a class="dropdown-item" href="<?php echo e(route('user.logout')); ?>">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- Guest: Register -->
                    <a href="<?php echo e(route('user.register')); ?>">
                        <button class="btn btn-primary animate-btn icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            Register
                        </button>
                    </a>

                    <!-- Guest: Login -->
                    <a href="<?php echo e(route('user.login')); ?>">
                        <button class="btn btn-outline-primary animate-btn icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10 17 15 12 10 7"/>
                                <line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                            Login
                        </button>
                    </a>

                    <!-- Submit Story (still visible) -->
                    <a href="<?php echo e(route('user.story')); ?>">
                        <button class="btn btn-success animate-btn icon-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="me-1">
                                <path d="M14.5 4L19 8.5V20a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h5.5L14.5 4z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="12" y1="17" x2="12" y2="11"/>
                                <line x1="9" y1="14" x2="15" y2="14"/>
                            </svg>
                            Submit Story
                        </button>
                    </a>
                <?php endif; ?>

                <!-- Mobile Menu Icon -->
                <button class="btn btn-outline-secondary rounded-circle d-md-none" onclick="toggleDrawer()" style="width: 40px; height: 40px;">
                    ☰
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Category Navbar -->
<nav class="category-navbar bg-light shadow-sm py-2 d-none d-lg-block">
    <div class="container d-flex justify-content-center flex-wrap">
        <?php $__currentLoopData = $categories->where('parent_id', null); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($category->children->count()): ?>
                <!-- Parent Category with Children -->
                <div class="dropdown">
                    <span class="category-nav-item dropdown-toggle" data-bs-toggle="dropdown" role="button">
                        <?php echo e($category->name); ?>

                    </span>
                    <ul class="dropdown-menu">
                        <?php $__currentLoopData = $category->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <span class="dropdown-item">
                                    <?php echo e($child->name); ?><br>
                                    <small><?php echo e($child->hi_name); ?></small>
                                </span>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php else: ?>
                <!-- Parent Category with No Children -->
                <span class="category-nav-item">
                    <?php echo e($category->name); ?><br>
                    <small><?php echo e($category->hi_name); ?></small>
                </span>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>