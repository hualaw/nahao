<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <?php include(APPPATH . 'views/admin/header.php') ?>
    </head>

    <body>
        <div class="container">
            <?php include(APPPATH . 'views/admin/nav.php') ?>
            <?php echo $content_for_layout; ?>
        </div>
        <?php include(APPPATH . 'views/admin/footer.php') ?>
    </body>
</html>
