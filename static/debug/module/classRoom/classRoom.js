define(function (require,exports){
	//做题选答案 (加背景色)
	var options = function (){
		$(".answerList li").click(function (){
			$(".answerList li").removeClass("curAnswer");
			$(this).addClass("curAnswer");
		})
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
            }else{
				$(this).addClass("itemOn");
				$(".Titem a").eq(_this).addClass("titemOn");
				$(".itemNum").html(($(".itemNum").html()-"")+1);
            }
		})
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
		alert(1)
		//$(".nextBtn").hide();

		var type = 1,
			ind = 0,
			ans = [],
			index = true,
			qid = "",
			answer = "",
		    sequence = 1,
		    cid = 0;
		var _len = $('.doWorkList').size()/2;
		//console.log(_len);
		$(".subAns").click(function (){

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
					$(".aui_content").html($(".scorePageHtml").html())
				}
			}else{
				$(this).hide();
				$(".nextBtn").show();	
			}
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

            	console.log($(".answerList li").eq(ans[ans.length-1]).find(".options").html() +'/'+response.data.answer);
            	if(type == 1){
    				if($(".answerList li").eq(ans[ans.length-1]).find(".options").html() == response.data.answer){
    					$(".answerList li").eq(ans[ans.length-1]).addClass("ansRight");
    				}else{
    					$(".answerList li").eq(ans[ans.length-1]).addClass("ansError");
    					//$(".answerList li").eq(ind*4).addClass("ansRight");
    					// if($(".answerList li").eq(i).find(".options").html().indexOf(response.data.answer)){

    					// }
    				}
    			}
            });
			//right error
/*			if(type == 1){
				if($(".answerList li").eq(ans[ans.length-1]).find(".options").html() == answer){
					$(".answerList li").eq(ans[ans.length-1]).addClass("ansRight");
				}else{
					$(".answerList li").eq(ans[ans.length-1]).addClass("ansError");
					$(".answerList li").eq(ind*4).addClass("ansRight");
				}
			}	*/				
		});

		$(".nextBtn").click(function (){
			ans = [];
			ind++;
			//last itme
			$(".doWorkList").addClass("undis");
			$(".doWorkList").eq(ind).removeClass("undis");
			$(this).hide();
			$(".subAns").show();
		});	

		$(".answerList li").click(function (){
			alert(22)
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