<div class="col-md-2 column ui-sortable">
			<div class="list-group">
        		<a href="/" class="list-group-item <?=in_array($active,array('welcome_index')) ? 'active' : ''?>">
          			首页
          			<i class="glyphicon glyphicon-chevron-right" style="float:right"></i>
          			<span class="badge pull-right">42</span>
        		</a>
        		<a href="/round/index" class="list-group-item <?=in_array($active,array('round_index','round_class_list','round_question_add','round_answer_count','round_check_comment')) ? 'active' : ''?>">课程列表<i class="glyphicon glyphicon-chevron-right" style="float:right"></i></a>
        		<a href="/user/index" class="list-group-item <?=in_array($active,array('user_index','user_avater','user_password')) ? 'active' : ''?>">个人资料<i class="glyphicon glyphicon-chevron-right" style="float:right"></i></a>
        		<a href="/income/index" class="list-group-item <?=in_array($active,array('income_index','income_detail')) ? 'active' : ''?>">课酬结算<i class="glyphicon glyphicon-chevron-right" style="float:right"></i></a>
        		<a href="/bandBank/index" class="list-group-item <?=in_array($active,array('bandBank_index')) ? 'active' : ''?>">绑定银行账户<i class="glyphicon glyphicon-chevron-right" style="float:right"></i></a>
      		</div>
      	</div>