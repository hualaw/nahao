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
				$('.publish_questions').html(response.msg);
			}
		});
	}
	//选择练习题  左右点击切换 题目选中
	exports.itemClick = function (){
		var iniW = $(".itemTabList").eq(0).outerWidth(true),
			iniH = $(".itemTabList").eq(0).outerHeight(true),
			ind = 0;
		//初始高度	
		$(".itemTabBox").height(iniH);

		//选题
		$(".itemTabList").click(function (){
			var _this = $(".itemTabList").index($(this));
			if($(this).hasClass("itemOn")){
				$(this).removeClass("itemOn");
				$(".Titem a").eq(_this).removeClass("titemOn");
				$(".itemNum").html($(".itemNum").html()-1);
				$(".itemCurNum").html($(".itemCurNum").html()-1);
            }else{
				$(this).addClass("itemOn");
				$(".Titem a").eq(_this).addClass("titemOn");
				$(".itemNum").html(($(".itemNum").html()-"")+1);
				$(".itemCurNum").html(($(".itemCurNum").html()-"")+1);
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
					alert(response.msg);
					$.tiziDialog({ id: 'exerciseHtml' }).close();
				}
			}, "json");
		});
		//左右切换
		function roll(ind){
			$(".itemTabBox").height($(".itemTabList").eq(ind).outerHeight(true));
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
			if(ind>$(".itemTabList").length-1){
				ind = $(".itemTabList").length-1;
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
		var ind = 2;
		$(".itemscore .sconl li").eq(ind).addClass("curitme");
		$(".itemscore .scoreBoxList").eq(ind).removeClass("undis");

		//点击时切换
		$(".itemscore .sconl li").click(function (){
			var _this = $(".itemscore .sconl li").index($(this));
			$(".itemscore .sconl li").removeClass("curitme");
			$(this).addClass("curitme");
			$(".itemscore .scoreBoxList").addClass("undis");
			$(".itemscore .scoreBoxList").eq(_this).removeClass("undis");
		})
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
		 var url = '/classroom/get_exercise/';
		 var data = {
				 class_id: 4
		 };
		 $.post(url, data, function (response) {
			 if (response.status == "error") {
				 alert(response.msg);
			 } else if(response.status == "ok"){
				 
				 $.each(response.data, function(key, val) {
					 if(key == '0'){
						 html+='<div class="doWorkList" >';
					 } else{
						 html+='<div class="doWorkList undis">';
					 }
				
					 html+='<div class="setqid" sequence="'+val.sequence+'" classid=4 qid="'+val.id+'">'+val.question+'</div>';
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
			 }
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
		    cid = 0;
		
		
		$(".subAns").live('click',function (){
			var _len = $('.doWorkList').size()/2;
			//console.log($('.doWorkList').size());
			var aL = $(this).parent().parent().find(".answerList");
			for(var i=0;i<aL.find("li").length;i++){
				if(!aL.find("li").hasClass("curAnswer")){
					alert("您还没有做题");
					return;
				}
			}
			if(ind>=_len-1){
				if(index){
					$(this).show().html("查看结果");
					index = false;
				}else{
					$(".aui_content").html($(".scoreBoxHtml").html());

					$(".itemscore .result").click(function (){
						$(".aui_content").html($(".scorePageHtml").html());
					})
				}
			}else{
				$(this).hide();
				$(".nextBtn").show();	
			}
			//ajax提交答案
			//console.log(qid+"/"+answer+"/"+sequence+'/'+cid+'/'+ans.length+'/'+ind)
			var murl = '/classroom/save/';
            var mdata = {
            	class_id: cid,
            	question_id: qid,
            	selected:answer,
            	sequence:sequence
            };
            $.post(murl, mdata, function (response) {
				if(type == 1){
    				if($(".answerList li").eq(ans[ans.length-1]).find(".options").html() == response.data.answer){
    					$(".answerList li").eq(ans[ans.length-1]).addClass("ansRight");
    				}else{
    					var n;
    					switch(response.data.answer){
    						case "A":
    						n = 0;
    						case "B":
    						n = 1;
    						case "C":
    						n = 2;
    						case "D":
    						n = 3;
    					}
    					$(".answerList li").eq(ans[ans.length-1]).addClass("ansError");
    					$(".answerList li").eq(ind*4+n).addClass("ansRight");
    				}
    			}
            });			
		});

		$(".nextBtn").live('click',function (){
			ans = [];
			ind++;
			//last itme
			$(".doWorkList").addClass("undis");
			$(".doWorkList").eq(ind).removeClass("undis");
			$(this).hide();
			$(".subAns").show();
		});	

		$(".answerList li").live('click',function (){
			if(type == 1){
				$(".answerList li").removeClass("curAnswer");
				$(this).addClass("curAnswer");
			}else{
				$(this).addClass("curAnswer");
			}
			ans.push($(".answerList li").index($(this)));
			qid = $(this).parent().parent().find(".setqid").attr("qid")
			sequence = $(this).parent().parent().find(".setqid").attr("sequence")
			cid = $(this).parent().parent().find(".setqid").attr("classid")
			var answers = [];

			answers.push($(this).find(".options").html());
			answer = answers.join();
		}); 
	}
	
	
/*	//提交答案
	exports.save_answer = function(){
		$('').click(function (){
			
			var url = '/classroom/save/';
            var data = {
            	class_id: $("#class_id").val(),
            	question_id: $("#question_id").val(),
            	selected:$("#selected").val(),
            	sequence:$("#sequence").val(),
            	answer:$("#answer").val(),
            };
            $.post(url, data, function (response) {
                if (response.status == "ok") {
                	alert(response.msg);
                } else if(response.status == "error"){
                	alert(response.msg);
                }
            });
		});
	}
	
	//查看结果
	exports.save_answer = function(){
		$('').click(function (){
			
			var url = '/classroom/get_question_result_data/';
            var data = {
            	class_id: $("#class_id").val(),
            	selected:$("#selected").val()
            };
            $.post(url, data, function (response) {
                if (response.status == "ok") {
                	alert(response.msg);
                } else if(response.status == "error"){
                	alert(response.msg);
                }
            });
		});
	}*/
})