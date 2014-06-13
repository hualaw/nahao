define(function (require,exports){

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
									rhtml+='			<div>第'+(kk+1)+'题、'+vv.question+'</div>';
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
									rhtml+='	<p class="fl promText">请点击左侧按钮回顾您的作答情况，红色表示做错的题目，绿色表示做对的题目。请认真查看做错的题目，看看自己能否解出正确答案。如仍不能解出正确答案的，请耐心等待老师讲解哦！</p>';						
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