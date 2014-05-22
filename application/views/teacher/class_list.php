<!DOCTYPE html>
<html lang="en">
	<head>
		<title>那好教师端</title>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="那好教师端" />
		<meta name="keywords" content="那好教师端" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo static_url("/admin/css/bootstrap.css"); ?>" rel="stylesheet">
		<link href="<?php echo static_url("/admin/css/bootstrap-datetimepicker.min.css"); ?>" rel="stylesheet">
<style>
.navbar-brand{
display: block;
float: left;
font-size: 30px;
font-weight: 700;
color: #333;
text-shadow: 0 1px 0 #fff;
transition: all .3s linear;
-webkit-transition: all .3s linear;
-moz-transition: all .3s linear;
}
.navbar-brand:hover{
color: #fff;
}
.body{
padding:0px 15px;
}
.class-notice{
padding: 5px 0px 0px 30px;
}
.class-form{
padding: 5px 0px 15px 30px;
}
</style>
	</head>
	<body>
		<?=$nav?>
		<!--nav end-->
		<!--body-->
		
			<!--left-nav-start-->
	<div class="row body">
		<?=$siteBar?>
      	<div class="row col-md-10 ui-sortable class-notice">
      		<ul class="list-inline">
  				<li>课程数量：8</li>
  				<li>即将上课：3</li>
  				<li>待上课：3</li>
  				<li>已结课：2</li>
  				<li>已取消：0</li>
			</ul>
      	</div>
	    <div class="row col-md-10 class-form">
			<form class="form-inline" role="form">
			  <div class="form-group">
				<select class="form-control">
				  <option>课程名称</option>
				  <option>课程ID</option>
				</select>
			  </div>
			  <div class="form-group">
			    <label class="sr-only" for="course">Email address</label>
			    <input type="text" id="course" class="form-control" placeholder="Enter course">
			  </div>
			  <div class="form-group">
			  	<span>授课状态：</span>
			    <select class="form-control">
				  <option value="0">全部状态</option>
				  <option value="">即将开课</option>
				  <option value="">待上课</option>
				  <option value="">已结课</option>
				</select>
			  </div>
			  <div class="form-group">
			  	<span>课程类型：</span>
			    <select class="form-control">
				  <option value="0">全部</option>
				  <option value="">公开课</option>
				  <option value="">同步学习</option>
				  <option value="">素质教育</option>
				</select>
			  </div>
			  <div class="form-group">
			    <label> 上课日期：</label>
			    <div class="row">
			    	<div class="col-xs-2">
					    <input type="text" class="form-control" placeholder=".col-xs-2">
					</div>
			    	<div class="col-xs-2">
			    		<input type="text" class="form-control input-sm" placeholder="开始时间" value="">
			    		<input type="text" class="form-control input-sm" placeholder="结束时间" value="">
			    	</div>
			    </div>
			  </div>
			  <div class="form-group">
		  		<select class="form-control">
		          <option value="">课程评分排序</option>
		          <option value="">由高到低</option>
		          <option value="">由低到高</option>
		        </select>
			  </div>
			  <button type="submit" class="btn btn-default">搜索</button>
			</form>
	    </div>
      <div class="col-md-10 column ui-sortable table-responsive">
      	<table class="table table-hover table-bordered table-striped">
        <thead>
          <tr class="warning">
            <th>课程ID#</th>
            <th>课程名称</th>
            <th>小节名称</th>
            <th>上课日期</th>
            <th>上课时间</th>
            <th>课程进度</th>
            <th>科目</th>
            <th>类型</th>
            <th>授课状态</th>
            <th>课程平均分</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>0001</td>
            <td>2014年小升初暑假特训营</td>
            <td>第三讲：真金不怕火炼</td>
            <td>2014-06-14</td>
            <td>18：30-19：30</td>
            <td>3 / 5</td>
            <td>综合</td>
            <td>教材同步</td>
            <td>即将开课</td>
            <td>4.2</td>
            <td>查看课程章节</td>
          </tr>
          <tr>
            <td>0002</td>
            <td>小学五年级奥数精讲</td>
            <td>第十讲：牛吃草问题</td>
            <td>2014-06-11</td>
            <td>18：30-19：30</td>
            <td>10 / 10</td>
            <td>奥数</td>
            <td>公开课</td>
            <td>已结课</td>
            <td>4.4</td>
            <td>查看课程章节</td>
          </tr>
          <tr>
            <td>0003</td>
            <td>2014年小升初暑假特训营</td>
            <td>第一讲：行程问题</td>
            <td>2014-06-14</td>
            <td>18：30-19：30</td>
            <td>1 / 5</td>
            <td>综合</td>
            <td>教材同步</td>
            <td>待上课</td>
            <td>——</td>
            <td>查看课程章节</td>
          </tr>
          <tr>
            <td>0004</td>
            <td>小学语文作文之景物描写</td>
            <td>景物实体细节描写</td>
            <td>2014-06-15</td>
            <td>18：30-19：30</td>
            <td>2 / 10</td>
            <td>语文</td>
            <td>竞赛考级</td>
            <td>待上课</td>
            <td>4.5</td>
            <td>查看课程章节</td>
          </tr>
        </tbody>
      </table>
      </div>
  </div>
			<!--left-end-->
			<!--right-->
	</body>
	<script type="text/javascript" src="<?php echo STATIC_ADMIN_JS_JQUERY_MIN;?>"></script>
	<script type="text/javascript" src="<?php echo static_url(STATIC_ADMIN_JS_BOOTSTRAP_MIN); ?>"></script>
</html>