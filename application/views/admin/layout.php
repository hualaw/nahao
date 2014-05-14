<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <link rel="shortcut icon" href="">
        <title>那好管理系统</title>
        <link href="<?php echo static_url("/admin/css/bootstrap.css"); ?>" rel="stylesheet">
        <link href="<?php echo static_url("/admin/css/nav.css"); ?>" rel="stylesheet">
    </head>

    <body>

        <div class="container">
            <?php include(APPPATH . 'views/admin/nav.php') ?>
            <?php echo $content_for_layout; ?>
        </div>

        <!-- Bootstrap core JavaScript
            ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <script src="http://cdn.bootcss.com/jquery/1.10.2/jquery.min.js"></script>
        <script src="<?php echo static_url("/admin/js/bootstrap.min.js"); ?>"></script>
    </body>
</html>
