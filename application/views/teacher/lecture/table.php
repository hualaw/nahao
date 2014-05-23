<div class="row col-md-10 class-form">
	    	<div class="bs-callout bs-callout-info" style="background-color: #f4f8fa;border-color: #bce8f1;">
      			<blockquote><h4><b>试讲申请</b></h4>
      			<cite>请先填写以下<code>试讲表单</code>信息，以便我们的工作人员更好的与您联系沟通</cite>
      			</blockquote>
    		</div>
    		<form class="form-horizontal" method="POST" role="form" action="/lectrue/doAdd/?<?=time()?>">
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">真实姓名：</label>
			    <div class="col-sm-10">
			      <input type="email" name="realname" class="form-control col-xs-3" placeholder="您身份证的姓名">
			      <small style="color:#a1a1a1">保密，方便我们与您电话沟通详细情况</small>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputPassword3" class="col-sm-2 control-label">所在地区：</label>
			    <div class="col-sm-10">
			    	<div class="col-xs-3">
				      	<select class="form-control">
				          <option name="province" value="请选择所在省份">请选择所在省份</option>
				          <option value="黑龙江省">黑龙江省</option>
				          <option value="吉林省">吉林省</option>
				          <option value="辽宁省">辽宁省</option>
				          <option value="湖南省">湖南省</option>
				          <option value="湖北省">湖北省</option>
				          <option value="甘肃省">甘肃省</option>
				        </select>
			        </div>
			        <div class="col-xs-3">
	          			<select name="city" class="form-control">
	            			<option selected="" value="请选择所在城市">请选择所在城市</option>
					          <option value="哈尔滨市">哈尔滨市</option>
					          <option value="齐齐哈尔市">齐齐哈尔市</option>
					          <option value="绥化市">绥化市</option>
					          <option value="牡丹江市">牡丹江市</option>
					          <option value="鹤岗市">鹤岗市</option>
	          			</select>
          			</div>
			        <div class="col-xs-3">
	          			<select name="area" class="form-control">
	            			<option selected="" value="请选择所在区县">请选择所在区县</option>
					          <option value="道外区">道外区</option>
					          <option value="道里区">道里区</option>
					          <option value="南岗区">南岗区</option>
					          <option value="香坊区">香坊区</option>
					          <option value="平房区">平房区</option>
					          <option value="动力区">动力区</option>
					          <option value="江北区">江北区</option>
	          			</select>
          			</div>
			    </div>
			  </div>
			  <!--seperate -->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">性 别：</label>
			    <div class="col-sm-10">
		    		<label class="checkbox-inline">
						<input type="radio" name="gender" id="optionsRadios1" value="1"> 男
					</label>
		    		<label class="checkbox-inline">
						<input type="radio" name="gender" id="optionsRadios1" value="2"> 女
					</label>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">教师职称：</label>
			    <div class="col-sm-10">
			       <div class="col-xs-3">
	          			<select name="area" class="form-control">
	            			<option value="正高级教师">正高级教师</option>
					          <option value="高级教师">高级教师</option>
					          <option value="一级教师">一级教师</option>
					          <option value="二级教师">二级教师</option>
					          <option value="三级教师">三级教师</option>
					          <option selected="" value="请选择职称">请选择职称</option>
	          			</select>
          			</div>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">实际教龄：</label>
			    <div class="col-sm-10">
			       <div class="col-xs-3">
	          			<select name="area" class="form-control">
	            			<option name="" value="请选择教龄">请选择教龄</option>
					          <option value="1年以内">1年以内</option>
					          <option value="2年">2年</option>
					          <option value="3年">3年</option>
					          <option value="4年">4年</option>
					          <option value="5年">5年</option>
					          <option value="6年">6年</option>
					          <option value="7年">7年</option>
					          <option value="8年">8年</option>
					          <option value="9年">9年</option>
					          <option value="10年">10年</option>
	          			</select>
          			</div>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">手机号：</label>
			    <div class="col-sm-10">
			      <input type="email" name="phone" class="form-control col-xs-3" placeholder="您的常用手机号">
			      <small style="color:#a1a1a1">保密，方便我们与您电话沟通详细情况</small>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">常用邮箱：</label>
			    <div class="col-sm-10">
			      <input type="email" name="realname" class="form-control col-xs-3" placeholder="您身份证的姓名">
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">QQ号：</label>
			    <div class="col-sm-10">
			      <input type="email" name="realname" class="form-control col-xs-3" placeholder="您常用的QQ号">
			      <small style="color:#a1a1a1">保密，方便我们与您电话沟通详细情况</small>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">讲课方式：</label>
			    <div class="col-sm-10">
			       <div class="col-xs-3">
	          			<select name="area" class="form-control">
	            			<option value="1对1教学">1对1教学</option>
					          <option value="小班教学（15人以内）">小班教学（15人以内）</option>
					          <option value="大班教学（不限人数）">大班教学（不限人数）</option>
					          <option selected="" value="请选择讲课方式">请选择讲课方式</option>
	          			</select>
          			</div>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">试讲科目：</label>
			    <div class="col-sm-10">
			       <div class="col-xs-3">
	          			<select name="area" class="form-control">
	            			<option selected="" value="请选择试讲科目">请选择试讲科目</option>
					          <option value="英语">英语</option>
					          <option value="语文">语文</option>
					          <option value="数学">数学</option>
					          <option value="物理">物理</option>
					          <option value="化学">化学</option>
					          <option value="生物">生物</option>
					          <option value="奥数">奥数</option>
					          <option value="地理">地理</option>
					          <option value="历史">历史</option>
	          			</select>
          			</div>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">预约时间：</label>
			    <div class="col-sm-10">
			    	<div class="col-xs-3">
			      		<input type="email" name="realname" class="form-control" placeholder="试讲开始时间">
			      	</div>
			      	<div class="col-xs-3">
			      		<input type="email" name="realname" class="form-control" placeholder="试讲结束时间">
			      	</div>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">课程名称：</label>
			    <div class="col-sm-10">
			      <input type="email" name="realname" class="form-control col-xs-3" placeholder="您想开的课程名">
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label">课程介绍：</label>
			    <div class="col-sm-10">
			      <textarea></textarea>
			    </div>
			  </div>
			  <!--seperate-->
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-success">提交申请</button>
			    </div>
			  </div>
			</form>
			
	    </div>