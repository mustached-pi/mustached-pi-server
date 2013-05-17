<?php

/*
 * (c)2013 The Mustached Pi Project
 */

require 'lib/core.php';
$me = $session->user();

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Mustached Pi Projects</title>
    <meta name="description" content="Let your Pi grow mustaches">
    <meta name="author" content="Alfio Emanuele Fresta <alfio.emanuele.f@gmail.com>,
          Angelo Lupo <angelolupo94@gmail.com>">
    <link rel="shortcut icon" href="/img/favicon.ico" />

    <!-- CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="css/font-awesome.min.css" rel="stylesheet" media="screen">
    <link href="css/main.css" rel="stylesheet" media="screen">
    <link href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" media="screen">
    
    <!-- JS -->
    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
  </head>
  <body>

      
    <div class="container-narrow">

      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li><a href="?p=home">Home</a></li>
          <li><a href="?p=project">The Project</a></li>
          <?php if ( $me ) { ?>
            <li><a href="?p=logout">Logout</a></li>
          <?php } else { ?>
            <li><a href="?p=login">Login</a></li>
          <?php } ?>
        </ul>
        <h3 class="muted">
            <img src="img/mustaches_small.png" />
            The Mustached Pi Project
        </h3>
      </div>

      <hr>
      
      <?php
      /*
       * Routing to the right page
       */
      if ( isset($_GET['p']) ) {
          $p = $_GET['p'];
      } else {
          $p = 'home';
      }
      if ( file_exists("./inc/{$p}.php") ) {
          require ("./inc/{$p}.php");
      } else {
          require ("./inc/404.php");
      }
      ?>

      
      <hr>

      <div class="footer">
        <p>&copy;<?php echo date('Y'); ?> The Mustached Pi Project
            <br />
            <span class='muted'>
                We're <a href="//github.com/AlfioEmanueleFresta">Fresta</a> and
                <a href="//github.com/AngeloLupo">Lupo</a>, btw.
            </span>
         </p>
      </div>

    </div>

  </body>
</html>