<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="<?php echo e($meta_description ?? ''); ?>">
    <meta name="keywords" content="<?php echo e($meta_keywords ?? ''); ?>">
    <meta name="author" content="<?php echo e($meta_author ?? ''); ?>">
    <meta name="google-site-verification" content="<?php echo e($settings->google_web); ?>" />
    <!-- Favicon Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(url('/')); ?>/front/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(url('/')); ?>/front/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(url('/')); ?>/front/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo e(url('/')); ?>/front/images/favicon/site.webmanifest">

    <title><?php echo e($title ?? config('app.name')); ?></title>
    <link href="<?php echo e(url('/')); ?>/front/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo e(url('/')); ?>/front/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo e(url('/')); ?>/frontend/css/style.css" rel="stylesheet">
    <script src="<?php echo e(url('/')); ?>/frontend/js/script.js"></script>


      
  <!-- Google tag (gtag.js) for GA4 -->

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-KZQC29WR');</script>
    <!-- End Google Tag Manager -->

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KZQC29WR"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

</head>
<body>
    
    <?php echo $__env->make('layouts.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('sweetalert::alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('layouts.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>

<?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/layouts/app.blade.php ENDPATH**/ ?>