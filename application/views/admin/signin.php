<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
        <link rel="shortcut icon" href="">
        <title>那好管理系统-登录</title>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo static_url(STATIC_ADMIN_CSS_BOOTSTRAP); ?>" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="<?php echo static_url(STATIC_ADMIN_CSS_SIGNIN); ?>" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            <form class="form-signin" role="form" action="/passport/login" method="post">
                <h2 class="form-signin-heading">那好管理系统</h2>
                <input type="text" class="form-control" name="username" placeholder="Username" required autofocus>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">登    录</button>
            </form>
        </div>
    </body>
</html>