<div class="col-md-10 column">
	<ul class="nav nav-tabs">
	  <li <?=in_array($active,array('user_index')) ? 'class="active"' : ''?>><a href="/user/index/">个人资料</a></li>
	  <li <?=in_array($active,array('user_avater')) ? 'class="active"' : ''?>><a href="/user/avater/">修改头像</a></li>
	  <li <?=in_array($active,array('user_password')) ? 'class="active"' : ''?>><a href="/user/password/">修改密码</a></li>
	</ul>
</div>