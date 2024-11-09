<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APPNAME ?></title> 

    <?php include_once APP_PATH .'/views/layouts/css_files.php'; ?>
  </head>
  <body>
    <div id="app">
      <nav class="navbar navbar-light">
        <div class="container d-block">
          <a class="btn" onclick="history.back()"><i class="bi bi-chevron-left"></i></a>
        </div>
      </nav>
      <div class="container">
        
        <?php include_once $content; ?>
      </div>
    </div>
    <?php include_once APP_PATH .'/views/layouts/script.php'; ?>
  </body>
</html>