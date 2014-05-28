<div class="navbar navbar-default" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">那好管理</a>
    </div>

    <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <li class="dropdown<?php echo in_array($this->current['controller'],array('course','round')) ? ' active' : '';?>">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">课程体系<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="/course">课程管理</a></li>
                    <li><a href="/round">轮管理</a></li>
                </ul>
            </li>

<<<<<<< HEAD
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">学生管理<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="/student">学生管理</a></li>
                    <li><a href="/">订单管理</a></li>
                </ul>
            </li>
            <li><a href="/order">订单管理</a></li>
            <li>
=======
            <li <?php echo in_array($this->current['controller'],array('user')) ? 'class="active"' : '';?>><a href="/user">用户管理</a></li>
            <li <?php echo in_array($this->current['controller'],array('order')) ? 'class="active"' : '';?>><a href="/order">订单管理</a></li>
            <li <?php echo in_array($this->current['controller'],array('salary')) ? 'class="active"' : '';?>><a href="/salary">课酬管理</a></li>

            <li class="dropdown">
>>>>>>> remotes/origin/master
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">教师管理<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="/teacher">教师管理</a></li>
                    <li><a href="/teacher/amount">课酬管理</a></li>
                </ul>
            </li>

            <li class="dropdown<?php echo in_array($this->current['controller'],array('admin')) ? ' active' : '';?>" >
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">系统管理<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a href="/admin">管理员管理</a></li>
                    <li><a href="/group">组管理</a></li>
                    <li><a href="/admin/permission">权限管理</a></li>
                </ul>
            </li>
        </ul>

        <p class="navbar-text navbar-right"><?php echo $userinfo['username']?>,<a href="/passport/logout" class="navbar-link">退出</a></p>
    </div><!--/.nav-collapse -->
</div>