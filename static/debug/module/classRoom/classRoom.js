define(function (require,exports){
	var _popinside = require('module/common/method/popUp');
	//做题选答案 (加背景色)
	exports.options = function (){
		$(".answerList li").live('click',function (){
			$(".answerList li").removeClass("curAnswer");
			$(this).addClass("curAnswer");
		})
	}
	//获取未出过的练习题
	exports.load_questions = function (){
		$(".unclick").show();
		$(".do_publish_questions").hide();
			//弹框
			$.tiziDialog({
	            title:false,
	            ok:false,
	            icon:false,
	            id: 'exerciseHtml',
	            padding:0,
	            content:$('#exerciseHtml').html(),
	        });
		//初始化弹出数据
		$('.itemCurNum').html(0);
		$('.publish_questions').html();
		$('.publish_questions_index').html();
		var classroom_id = $('#nahaoModule').attr('classroom-data');
		var url = "/classroom/teacher_get_exercise_page/"+classroom_id+'/?'+((new Date).valueOf());
		var itemControll = this;
		$.get(url,function(response){
			if(response.status=='ok'){
				$(".clickBtn").show();
				var q_html = q_i_html = '';
				$.each(response.data, function(key, val) {
					//题目
					q_html += '<li class="fl itemTabList" rel="'+val.id+'"><div>'+(key+1)+'.'+val.question+'</div>';
					 $.each(val.options, function(k, v) {
					 	q_html += '<div class="cf eAns"><strong class="fl">'+k+'</strong><p class="fl">'+v.value+'</p></div>';
					 });
					q_html += '</li>';
					//题目索引
					q_i_html += '<a href="javascript:void(0)" data='+val.id+'>'+(key+1)+'</a>';
					$('.publish_questions').html(q_html);
					$('.publish_questions_index').html(q_i_html);
				});
				itemControll.itemClick();
			}else{
				$(".clickBtn").hide();
				$('.publish_questions_index').html("");
				$('.publish_questions').html('<li>'+response.msg+'</li>');
				$(".itemTabBox").height($(".aui_content .publish_questions li").outerHeight(true));
			}
		});
	}
	//选择练习题  左右点击切换 题目选中
	exports.itemClick = function (){
		$(".itemTabBox ul").css("left",0);

		var iniW = $(".itemTabList").eq(0).outerWidth(true),
			iniH = $(".itemTabList").eq(0).outerHeight(true),
			ind = 0;
		//初始高度	
		document.title = iniH;
		$(".itemTabBox").height(iniH);

		//选题
		$(".itemTabList").click(function (){
			var _this = $(".itemTabList").index($(this));
			if($(this).hasClass("itemOn")){
				$(this).removeClass("itemOn");
				$(".Titem a").eq(_this).removeClass("titemOn");
				$(".itemNum").html($(".itemNum").html()-1);
				$(".itemCurNum").html($(".itemCurNum").html()-1);
				if($(".itemOn").length==0){
					$(".do_publish_questions").hide();
					$(".unclick").show();
				}
            }else{
				$(this).addClass("itemOn");
				$(".Titem a").eq(_this).addClass("titemOn");
				$(".itemNum").html(($(".itemNum").html()-"")+1);
				$(".itemCurNum").html(($(".itemCurNum").html()-"")+1);
				$(".do_publish_questions").show();
				$(".unclick").hide();
            }
		});
		//发布
		$('.do_publish_questions').click(function(){
			var question_id = ''; 
			$('li.itemOn').each(function(){
				question_id += $(this).attr('rel')+',';
			})
			var classroom_id = $('#nahaoModule').attr('classroom-data');
			var url = "/classroom/teacher_publish_questions/"+classroom_id+'/?tmp='+((new Date).valueOf());
			
			var data = {
				question_id: question_id
			};
			$.post(url, data, function (response) {
				if (response.status == "ok") {
//					$.tiziDialog.list['exerciseHtml'].close();
					alert('第'+response.sequence+'批题'+response.msg);
					$.tiziDialog({ id: 'exerciseHtml' }).close();
				}
			}, "json");
		});
		//左右切换
		function roll(ind){
			$(".itemTabBox").height($(".aui_content .itemTabList").eq(ind).outerHeight(true));
			$(".itemTabBox ul").stop().animate({left:-ind*iniW});
		}
		$(".clickL").click(function (){
			ind--;
			if(ind<0){
				ind = 0;
			}
			roll(ind);
		});
		$(".clickR").click(function (){
			ind++;
			document.title = ind;
			console.log($(".aui_content .itemTabList").length-1)
			if(ind>$(".aui_content .itemTabList").length-1){
				ind = $(".aui_content .itemTabList").length-1;
			}
			roll(ind);
		});
		$(".Titem a").click(function (){
			roll($(".Titem a").index($(this)));
		})
	}
	exports.load_questions_count = function(){
		
		var classroom_id = $('#nahaoModule').attr('classroom-data');
		var url = "/classroom/teacher_checkout_question_answer/"+classroom_id+'/?'+((new Date).valueOf());
		
		$.get(url,function(response){
			if(response.status=='ok'){
				$('.countTitle').html(response.data.total_html);
				$('.suquence_total').html(response.data.html_head);
				$('.CitemCon').html(response.data.html);
				//临时修改样式
				$('.aui_content').css({'max-height':'600px','overflow-y':'scroll'});
				//弹框
				$.tiziDialog({
		            title:false,
		            ok:false,
		            icon:false,
		            id: 'ansCountHtml',
		            padding:0,
		            content:$('#ansCountHtml').html(),
		        });
		        $('.cbutton').click(function(){
		        	$(this).attr('class','cbutton redBtn').siblings().attr('class','cbutton countBtn');
		        	cur_sequence = $(this).attr('rel');
		        	$('.CitemList').fadeOut(500,function(){
		        		$('.sequence-'+cur_sequence).fadeIn();
		        	});
		        });
			}
		});
	}
	//选择题目 切换内容
	exports.curItem	= function (){
		//初始--选的题目内容显示，左侧对应列表高亮 
		//var ind = 2;
		//$(".itemscore .scoreBoxList").eq(0).removeClass("undis");
		//点击时切换
		$(".itemscore .sconl li").click(function (){
			$(".result").removeClass("resultArrow");
			$(".aui_content .Hideresult").hide();
			$(".aui_content .Showresult").show();

			var _this = $(".itemscore .sconl li").index($(this));
			$(".itemscore .scoreBoxList").addClass("undis");
			$(".itemscore .scoreBoxList").eq(_this).removeClass("undis");
		});

		$(".aui_content .itemscore .result").click(function (){
			$(this).addClass("resultArrow");
			$(".aui_content .Hideresult").show();
			$(".aui_content .Showresult").hide();
		});
	}

	//评论 几颗星
	exports.starClick = function (){
		//var ind = true;
		$(".evalu .starBg span").click(function (){
			//if(ind){
				var _index = $(".evalu .starBg span").index($(this));
				for(var i=0;i<_index+1;i++){
					$(".evalu .starBg span").eq(i).addClass("cStar");
				}
				//ind = false;
			//}
		});
	}

	//题目展示
	exports.show_question = function (){
		 var html='';
		 var url = '/classroom/get_exercise/?'+((new Date).valueOf());;
		 var class_id = $('#nahaoModule').attr('class-data');
		 var data = {
				 class_id: class_id
		 };
		 $.post(url, data, function (response) {
			 if (response.status == "error") {
 				$.dialog({
				    content:response.msg,
				    icon:null
				});
			 } else if(response.status == "ok"){
				 
				 $.each(response.data, function(key, val) {
					 if(key == '0'){
						 html+='<div class="doWorkList" >';
					 } else{
						 html+='<div class="doWorkList undis">';
					 }
				
					 html+='<div class="setqid" sequence="'+val.sequence+'" select_type="'+val.type+'" classid="'+val.class_id+'" qid="'+val.id+'">'+val.question+'</div>';
					 html+=	'<ul class="answerList">';
					 $.each(val.options, function(k, v) {
			
						 html+=	'<li class="cf ">';
						 html+=	'<em class="fl ansIco"></em>';
						 html+=	'<span class="options fl">'+k+'</span>';
						 html+=	'<p class="fl">'+v+'</p>';
						 html+=	'</li>';
					 });
					 html+=	'</ul>';
					 html+=	'</div>';
				 });
				 html+='<p class="overBtn">';
				 html+='<a href="javascript:void(0);" class="cf btn3 btn subAns">';
				 html+='<span class="fl">提交答案</span>';
				 html+='<span class="fr"></span>';
				 html+='</a>';
				 html+='<a href="javascript:void(0);" class="cf btn3 btn nextBtn">';
				 html+='<span class="fl">下一题</span>';
				 html+='<span class="fr"></span>';
				 html+='</a>';
				 html+='</p>';

				 $('.doWorkBox').html(html);
				 $(".nextBtn").hide();
				 $.dialog({
		            title:false,
		            ok:false,
		            icon:false,
		            padding:0,
		            content:$(".doWorkBoxHtml").html()
		        });
			 }
			exports.doWork();
		 }, "json");
	}
	
	//题目 做题
	exports.doWork = function (){
		var type = 1,
			ind = 0,
			ans = [],
			index = true,
			qid = "",
			answer = "",
		    sequence = 1,
		    cid = 0,
		    _len = $('.aui_content .doWorkList').size(),
		    select_type = 0,
		    chans = true; //打开做题弹框 判断初始只做一次题		


		$(".aui_content .nextBtn").live('click',function (){
			ans = [];
			ind++;
			
			if(ind>_len){
				ind = 0;
			}
			//last itme
			$(".aui_content .doWorkList").addClass("undis");
			$(".aui_content .doWorkList").eq(ind).removeClass("undis");
			$(this).hide();
			$(".aui_content .subAns").show();
			chans = true;
		});	

		$(".aui_content .subAns").click(function (){
			var aL = $(".aui_content .answerList").eq(ind).find("li");

			for(var i=0;i<aL.length;i++){
				//console.log(!aL.hasClass("curAnswer"))
				if(!aL.hasClass("curAnswer")){
					alert("您还没有做题");
					return;
				}else{
					chans = false;
				}
			}
			if(ind>=_len-1){
				if(index){
					$(this).show().html("查看结果");
					index = false;
					ajax_save();
				}else{
					 var rhtml='';
					 var rurl = '/classroom/get_question_result_data/';
					 var rdata = {
							 class_id: cid,
							 sequence:sequence
					 };
					 $.post(rurl, rdata, function (response) {
						 if (response.status == "error") {
							 alert(response.msg);
						 } else if(response.status == "ok"){
								 	rhtml+='<div class="scoreBox itemscore cf ">';
									rhtml+='<div class="fl optionNav">';	
									rhtml+='	<h3 class="result resultArrow">结果<span class="fr"></span></h3>';
									rhtml+='	<div class="sconParl posr">';
									rhtml+='		<ul class="sconl">';
									$.each(response.data.data_question, function(k, v) {
											if(v.is_correct == '1'){
												rhtml+='	<li>第'+(k+1)+'题</li>';
											} else if(v.is_correct == '0'){
												rhtml+='	<li class="curitme">第'+(k+1)+'题</li>';		
											}
									})
									rhtml+='		</ul>';
									rhtml+='	<div class="sparent undis">';
									rhtml+='		<div class="sbar"></div>';
									rhtml+='	</div>';
									rhtml+='	</div>';
									rhtml+='</div>';
									rhtml+='<div class="fl scoreCon posr Showresult">';
									rhtml+='	<div class="posr scoreBoxPar">';
		 							$.each(response.data.data_question, function(kk, vv) {
									
									rhtml+='		<div class="scoreBoxList undis">';
									rhtml+='			<div>第'+(kk+1)+'题:'+vv.question+'</div>';
									rhtml+='			<ul class="answerList">';
											$.each(vv.options, function(kkk, vvv) {
												if(vv.selected == kkk ){
													aclass = "cf curAnswer";
												} else {
													aclass = "cf ";
												}
												if(vv.answer == kkk){
													aclass += " ansRight";
												} else {
													if(vv.selected == kkk)
													{
													aclass += " ansError";
													}
												}
												
									rhtml+='				<li class="'+aclass+'">';
									rhtml+='					<em class="fl ansIco"></em>';
									rhtml+='					<span class="options fl">'+kkk+'</span>';
									rhtml+='					<p class="fl">'+vvv+'</p>';
									rhtml+='				</li>';
											})
									rhtml+='			</ul>';
									rhtml+='<p>您选择的是'+vv.selected+',正确答案是'+vv.answer+'</p>';
									rhtml+='		</div>';
									
									})
									rhtml+='	</div>';
									rhtml+='	<div class="cparent undis">';
									rhtml+='		<div class="cbar"></div>';
									rhtml+='	</div>';
									rhtml+='</div>';
									rhtml+='<div class="fl scoreCon Hideresult">';
									rhtml+='	<div class="fl scoreShow">';
									rhtml+='		<div class="score">';
									rhtml+='			<h3>你的得分</h3>';
									rhtml+='			<strong>'+response.data.count_score+'</strong>';
									rhtml+='		</div>';
									rhtml+='		<div class="scoref cf">';
									rhtml+='			<span class="fl rightItem">';
									rhtml+='				<span class="ansIco"></span>';
									rhtml+='				<em>'+response.data.right_num+'</em>';
									rhtml+='			</span>';
									rhtml+='			<span class="fl errorItem">';
									rhtml+='				<span class="ansIco"></span>';
									rhtml+='				<em>'+response.data.error_num+'</em>';
									rhtml+='			</span>';
									rhtml+='		</div>';
									rhtml+='	</div>';
									rhtml+='	<div class="promTextBox fl">'
													if(response.data.count_score/100 >=0.5){
									rhtml+='		<h3 class="">'	
													} else {
									rhtml+='		<h3 class="comeOnBg">'						
													}
									rhtml+='			<span class="reward">恭喜你，名列前茅！</span>'
									rhtml+='			<span class="comeOn">成绩不理想，要加油喽！</span>'
									rhtml+='		</h3>'
									rhtml+='		<p class="promText">请点击左侧按钮回顾您的作答情况，<br>'
									rhtml+='		<span class="redText">红色</span>表示做错的题目，<span class="greenText">绿色</span>表示做对的题目。</p>'
									rhtml+='	</div>';						
									rhtml+='</div>';
									rhtml+='</div>';
			
								    $('.scoreBoxHtml').html(rhtml);

								    $(".aui_content").html($(".scoreBoxHtml").html());
									$(".aui_content .result").addClass("resultArrow");
							        //选择题目 切换内容
							        exports.curItem();
	
									$(".aui_content .itemscore .result").click(function (){
										$(this).addClass("resultArrow");
										$(".aui_content .Hideresult").show();
										$(".aui_content .Showresult").hide();
									})
						 }

					 }, "json");
				}
			}else{
				$(this).hide();
				$(".aui_content .nextBtn").show();	
				ajax_save();
			}
			function ajax_save()
			{
				//ajax提交答案
				console.log(qid+"/"+answer+"/"+sequence+'/'+cid+'/'+ans.length+'/'+ind)
				var murl = '/classroom/save/';
	            var mdata = {
	            	class_id: cid,
	            	question_id: qid,
	            	selected:answer,
	            	sequence:sequence
	            };
	            $.post(murl, mdata, function (response) {
    				var n= 0;
					if(type == 1){
						if($(".aui_content .answerList li").eq(ans[ans.length-1]).find(".options").html() == response.data.answer){
							$(".aui_content .answerList li").eq(ans[ans.length-1]).addClass("ansRight");
						}else{
							if(response.data.answer == "A"){
								n = 0;
							}else if(response.data.answer == "B"){
								n = 1;
							}else if(response.data.answer == "C"){
								n = 2;
							}else if(response.data.answer == "D"){
								n = 3;
							}

							$(".aui_content .answerList li").eq(ans[ans.length-1]).addClass("ansError");
							$(".aui_content .answerList li").eq(ind*4+n).addClass("ansRight");
						}
					}
	            });		
			}	
		}); 
		//提交完 答案之后就不能再选择了
		$(".aui_content .answerList li").click(function (){	
			if(chans == true){		
				if(type == 1){
					$(".aui_content .answerList li").removeClass("curAnswer");
					$(this).addClass("curAnswer");
				}else{
					$(this).addClass("curAnswer");
				}
				ans.push($(".aui_content .answerList li").index($(this)));
				qid = $(this).parent().parent().find(".setqid").attr("qid")
				sequence = $(this).parent().parent().find(".setqid").attr("sequence")
				cid = $(this).parent().parent().find(".setqid").attr("classid")
				select_type = $(this).parent().parent().find(".setqid").attr("select_type")
				var answers = [];

				answers.push($(this).find(".options").html());
				answer = answers.join();
			}
		});	
	}
})