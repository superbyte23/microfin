<!DOCTYPE HTML>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APPNAME ?></title> 
    <link rel="shortcut icon" href="<?php echo ASSETS ?>/dist/assets/compiled/svg/favicon.svg" type="image/x-icon"> 
    <link rel="stylesheet" href="<?php echo ASSETS ?>/dist/assets/extensions/simple-datatables/style.css">
    <link rel="stylesheet" href="<?php echo ASSETS ?>/dist/assets/compiled/css/app.css">
    <link rel="stylesheet" href="<?php echo ASSETS ?>/dist/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="<?php echo ASSETS ?>/dist/assets/compiled/css/iconly.css"> 
  </head>
  <body>
    <?php if (isset($_SESSION['isLoggedIn'])): ?>
      <div id="app" class="">
        <!-- SideBar -->
          <?php include_once APP_PATH.'/views/layouts/sidebar.php'; ?>
        <div id="main" class='layout-navbar navbar-fixed'>
        <!-- Header -->
          <?php include_once APP_PATH.'/views/layouts/header.php'; ?>
        <!-- main content -->
        <div id="main-content" class="pt-0">
          <?php include_once $content; ?> 
        </div> 
        <!-- Footer -->
          <?php include_once APP_PATH.'/views/layouts/footer.php'; ?> 
        </div>
      </div> 
    <?php else: ?>
      <?php include_once $content; ?> 
    <?php endif ?>


    <script src="<?php echo ASSETS ?>/dist/assets/static/js/components/dark.js"></script>
    <script src="<?php echo ASSETS ?>/dist/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?php echo ASSETS ?>/dist/assets/compiled/js/app.js"></script>
    <!-- Need: Apexcharts -->
    <script src="<?php echo ASSETS ?>/dist/assets/static/js/initTheme.js"></script>

    <script src="<?php echo ASSETS ?>/dist/assets/extensions/simple-datatables/umd/simple-datatables.js"></script>
    <script src="<?php echo ASSETS ?>/dist/assets/static/js/pages/simple-datatables.js"></script>
  </body>
</html> 