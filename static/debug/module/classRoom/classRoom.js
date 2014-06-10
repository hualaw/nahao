define(function (require,exports){
	//做题选答案 (加背景色)
	exports.options = function (){
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

	//题目 做题
	exports.doWork = function (){
		$(".nextBtn").hide();

		var type = 1,
			ind = 0,
			ans = [],
			index = true,
			qid = "",
			answer = "";  
		
		$(".subAns").click(function (){

			var aL = $(this).parent().parent().find(".answerList");
			for(var i=0;i<aL.find("li").length;i++){
				if(!aL.find("li").hasClass("curAnswer")){
					alert("您还没有做题");
					return;
				}
			}
			if(ind>=4){
				if(index){
					$(this).show().html("chakandaan");
					index = false;
				}else{
					$(".aui_content").html($(".scorePageHtml").html())
				}
			}else{
				$(this).hide();
				$(".nextBtn").show();	
			}
			//ajax
			console.log(qid+"/"+answer)
			//right error
			if(type == 1){
				if($(".answerList li").eq(ans[ans.length-1]).find(".options").html() == "A"){
					$(".answerList li").eq(ans[ans.length-1]).addClass("ansRight");
				}else{
					$(".answerList li").eq(ans[ans.length-1]).addClass("ansError");
					$(".answerList li").eq(ind*4).addClass("ansRight");
				}
			}					
		})

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
			if(type == 1){
				$(".answerList li").removeClass("curAnswer");
				$(this).addClass("curAnswer");
			}else{
				$(this).addClass("curAnswer");
			}
			ans.push($(".answerList li").index($(this)));
			qid = $(this).parent().parent().find(".setqid").attr("qid")
			var answers = [];

			answers.push($(this).find(".options").html());
			answer = answers.join();
		}); 
	}
})