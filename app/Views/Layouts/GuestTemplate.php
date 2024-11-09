<!DOCTYPE html> 
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo PUBLIC_URL; ?>/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title><?php echo APPNAME ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo PUBLIC_URL; ?>/assets/img/favicon/favicon.ico" />

     

    <!-- Icons. Uncomment required icon fonts -->  
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/vendor/css/theme-default.css"  />
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="<?php echo PUBLIC_URL; ?>/assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo PUBLIC_URL; ?>/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar layout-without-menu">
      <div class="layout-container"> 
        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <!-- include navbar here -->
          <?php include_once BASE_PATH.'/src/views/layouts/GuestNavbar.php'; ?>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
            <?php include_once $content; ?> 
            <!-- / Content --> 
            <!-- Footer -->
            <?php include_once BASE_PATH.'/src/views/layouts/footer.php'; ?>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div> 
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper --> 

    <!-- Core JS -->
     
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script> 
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    

    <!-- Main JS -->
    <!-- <script src="<?php echo PUBLIC_URL; ?>/assets/js/main.js"></script> -->

    
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/js/dropdown-hover.js"></script> 
    <script src="<?php echo PUBLIC_URL; ?>/assets/vendor/js/mega-dropdown.js"></script> 

    <!-- Page JS -->
    <script src="<?php echo PUBLIC_URL; ?>/assets/js/dashboards-analytics.js"></script>
    
    <script src="<?php echo PUBLIC_URL; ?>/assets/js/docs.js"></script>
    <script src="<?php echo PUBLIC_URL; ?>/assets/js/ui-navbar.js"></script>
  </body>
</html>