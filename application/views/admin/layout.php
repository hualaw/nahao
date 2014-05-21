<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <?php $this->load->view('admin/header'); ?>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('admin/nav'); ?>
            <?php echo $content_for_layout; ?>
        </div>
        <?php $this->load->view('admin/footer'); ?>
    </body>
</html>
