define(function (require, exports) {
	//入口脚本
	exports.init = function(){
		tools_manage = this;
		this.autorun();
	}
	//初始程序
	exports.autorun = function(){
		$('.toolbar').click(function(){
			showBox = $(this).attr('data');
			$('#'+showBox).attr('class','panel-collapse collapse in').siblings().removeClass('in');
		});
	}
	//操作方法
	exports.autorun = function(){
		//创建订单模板
		$('.create_order').click(function(){
			ispass = tools_manage.subCheck();
			if(ispass){
				var copy = $('.order-copy').html();
				var curId = (new Date).valueOf();
				copyHtml = '<tbody class="order-item" id="order-'+curId+'">'+copy+'</tbody>';
				curRound_id = $('input[name="round_id"]').val();
				curUser_id = $('input[name="user_id"]').val();
				var url = '/tools/ajax_search_info/?round_id='+curRound_id+'&user_id='+curUser_id+'&&tmp='+((new Date).valueOf());
				$.get(url,function(data){
					res = eval('('+data+')');
					if(res.status=='ok'){
						time= new Date((res.data.roundInfo[0].start_time)*1000).toLocaleString();
						nowtime = ((new Date).valueOf())/1000;
						roundtime = res.data.roundInfo[0].start_time;
						if(nowtime>roundtime){
							seperday = parseInt(((nowtime-roundtime)/3600)/24);
							seperhour = parseInt(((nowtime-roundtime)%(3600*24))/3600);
							sepertime = '开课时间已过'+seperday+'天'+seperhour+'时';
						}else{
							seperday = parseInt(((roundtime-nowtime)/3600)/24);
							seperhour = parseInt(((roundtime-nowtime)%(3600*24))/3600);
							sepertime = '还剩'+seperday+'天'+seperhour+'时开课';
						}
						$("#order-"+curId+" tr.order-hd").find('.teach_time').html(time);
						$("#order-"+curId+" tr.order-hd").find('.teacherName').html('授课老师：'+res.data.roundInfo[0].teacherName);
						$("#order-"+curId+" tr.order-info").find('.round_pic').html('<img src="'+res.data.roundInfo[0].round_img+'">');
						$("#order-"+curId+" tr.order-info").find('.round_name').html(res.data.roundInfo[0].title);
						$("#order-"+curId+" tr.order-info").find('.spec').html('<span>所属学科：'+res.data.roundInfo[0].subjectName+'</span>');
						$("#order-"+curId+" tr.order-info").find('.status').html('预览订单');
						$("#order-"+curId+" tr.order-info").find('.operation').children('.time').html(sepertime);
						$("#order-"+curId+" tr.order-info").find('.price').html(res.data.roundInfo[0].sale_price);
						$("#order-"+curId+" tr.order-info").find('.now_price').html(res.data.roundInfo[0].now_price);
						$("#order-"+curId+" tr.order-info").find('.rate').html(res.data.roundInfo[0].rate);
						$("#order-"+curId+" tr.order-info").find('.buyer_student').html("<p class='buyer_name'>"+res.data.studentInfo[0].nickname+"</p><p class='edit_buyer'>修改</p>");
						$("#order-"+curId).attr({'data-round':curRound_id,'data-user':curUser_id});
					}
				});
				$('.orderTable').append(copyHtml);
				$("#order-"+curId).find('.pay-order').attr('data-order',curId);
				$("#order-"+curId).find('.del-order').attr('data-order',curId);
				$("#order-"+curId).find('.send_msg').attr('data-order',curId);
			}
		});
		//删除订单模板
		$('.del-order').live('click',function(){
			delId = $(this).attr('data-order');
			$("#order-"+delId).fadeOut(100).remove();
		});
		//按钮搜索
		$('.search-btn').live('click',function(){
			var inputobj = $(this).prev();
			var inputname = $(this).prev().attr('name');
			var inputval = $(this).prev().val();
			var suggestion = $(this).siblings('.popover');
			if(!inputval){
				if(!$(this).parent().is(":animated")){
					$(inputobj).css({'transform':'rotate(10deg)','transition':'all .1s ease-in-out'});
					$(this).parent().animate({margin:-6},210,function(){
						$(inputobj).css({'transform':'rotate(-7deg)'});
					}).animate({margin:5},180,function(){
						$(inputobj).css({'transform':'rotate(8deg)'});
					}).animate({margin:-3},150,function(){
						$(inputobj).css({'transform':'rotate(-15deg)'});
					}).animate({margin:4},130,function(){
						$(inputobj).css({'transform':'rotate(11deg)'});
					}).animate({margin:-1},100,function(){
						$(inputobj).css({'transform':'rotate(-7deg)'});
					}).animate({margin:0},80,function(){
						$(inputobj).css({'transform':'rotate(0deg)'});
					});
				}
				return false;
			}
			var url = "/tools/ajax_search_info/?";
			url += inputname+'='+inputval+'&tmp='+((new Date).valueOf());
			$.get(url,function(data){
				res = eval('('+data+')');
				if(res.status=='ok'){
					searchList = '<ul>';
					if(inputname=='nickname'){
						suggestion.children('.popover-title').html('昵称：<b>'+inputval+'</b>搜索<a class="close_pop" href="javascript:void(0)">×</a>');
						result = res.data.studentInfo;
						console.log(result);
						if(result.length>0){
							for(i=0;i<result.length;i++){
								searchList += '<li rel="'+result[i].id+'" data="'+result[i].nickname+'">'+result[i].nickname+'</li>';
							}
						}
					}else if(inputname=='round_name'){
						suggestion.children('.popover-title').html('班次：<b>'+inputval+'</b>搜索<a class="close_pop" href="javascript:void(0)">×</a>');
						result = res.data.roundInfo;
						if(result.length>0){
							for(i=0;i<result.length;i++){
								round_time= new Date((result[i].start_time)*1000).toLocaleString();
								searchList += '<li rel="'+result[i].id+'" data="'+result[i].title+'">'+result[i].title+'<font class="search_round_time">'+round_time+'</font></li>';
							}
						}
					}
					searchList += '</ul>';
					suggestion.children('.popover-content').html(searchList);
					suggestion.fadeIn(500);
				}
			});
		});
		//智能提醒
		$('.search_input').live('keyup',function(e){
			var inputname = $(this).attr('name');
			var inputval = $(this).val();
			var suggestion = $(this).siblings('.popover');
			if(!($(this).val().length>0)){
				suggestion.fadeOut(100);
				return false;
			}
			var url = "/tools/ajax_search_info/?";
			url += inputname+'='+inputval+'&tmp='+((new Date).valueOf());
			$.get(url,function(data){
				res = eval('('+data+')');
				if(res.status=='ok'){
					searchList = '<ul>';
					if(inputname=='nickname'){
						suggestion.children('.popover-title').html('昵称：<b>'+inputval+'</b>搜索<a class="close_pop" href="javascript:void(0)">×</a>');
						result = res.data.studentInfo;
						if(result.length>0){
							for(i=0;i<result.length;i++){
								s=(result[i].nickname).replace(inputval,"<b>"+inputval+"</b>");
								searchList += '<li rel="'+result[i].id+'" data="'+result[i].nickname+'">'+s+'</li>';
							}
						}
					}else if(inputname=='round_name'){
						suggestion.children('.popover-title').html('班次：<b>'+inputval+'</b>搜索<a class="close_pop" href="javascript:void(0)">×</a>');
						result = res.data.roundInfo;
						if(result.length>0){
							for(i=0;i<result.length;i++){
								s=(result[i].title).replace(inputval,"<b>"+inputval+"</b>");
								round_time= (new Date((result[i].start_time)*1000)).toLocaleString();
								t ='<font class="search_round_time">'+round_time+'</font>';
								searchList += '<li rel="'+result[i].id+'" data="'+result[i].title+'">'+s+t+'</li>';
							}
						}
					}
					searchList += '</ul>';
					suggestion.children('.popover-content').html(searchList);
					suggestion.fadeIn(200);
				}
			});
		});
		//选中搜索项
		$('.popover-content ul>li').live('click',function(){
			data = $(this).attr('data');
			id = $(this).attr('rel');
			inputobj = $(this).parent().parent().parent();
			$(inputobj).siblings('.search_input').val(data);
			poprel = $(inputobj).attr('rel');
			$(inputobj).parent().siblings('input[name="'+poprel+'"]').val(id);
			$(inputobj).siblings('.is_selected').removeClass('hide').html(id).attr('title',data);
			$(inputobj).siblings('.is_selected').tooltip();
			$(inputobj).fadeOut(100);
		});
		//关闭提醒框
		$('.close_pop').live('click',function(){
			$(this).parent().parent().fadeOut(100);
		});
		//确认购买
		$('.pay-order').live('click',function(){
			buyId = $(this).attr('data-order');
			buy_round_id = $("#order-"+buyId).attr('data-round');
			buy_user_id = $("#order-"+buyId).attr('data-user');
			if(!confirm("确认购买信息：购买轮id："+buy_round_id+",购买用户id："+buy_user_id+"。是否继续？")){
				return false;
			}
			var payurl = "/tools/sub_student_order/?user_id="+buy_user_id+"&round_id="+buy_round_id+"&tmp="+((new Date).valueOf());
			$.get(payurl,function(data){
				res = eval('('+data+')');
				if(res.status=='ok'){
					$("#order-"+buyId).find('.status').html('<a class="btn ok data-toggle="tooltip" data-placement="top" title="'+res.msg+'" data-original-title="'+res.msg+'">支付成功</a>');
					$("#order-"+buyId).find('.ok').tooltip('show');
				}else{
					$("#order-"+buyId).find('.status').html('<b class="error">'+res.msg+'</b>');
				}
			});
		});
		//给用户发短信
		$('.send_msg').live('click',function(){
			buyId = $(this).attr('data-order');
			buy_round_id = $("#order-"+buyId).attr('data-round');
			buy_user_id = $("#order-"+buyId).attr('data-user');
			msgurl = "/tools/send_msg/?user_id="+buy_user_id+"&round_id="+buy_round_id+"&tmp="+((new Date).valueOf());
			$.get(payurl,function(data){
				if(data==1){
					alert('发送成功');
				}else{
					alert('发送失败');
				}
			});
		});
	}
    //验证表单
    exports.subCheck = function(){
    	flag = 1;
		if(!$('input[name="round_id"]').val()){
//			alert('班次不能为空');
			flag = 0;
			if(!$('.search_round').parent().is(":animated")){
				$('.search_round').css({'transform':'rotate(10deg)','transition':'all .1s ease-in-out'});
				$('.search_round').parent().animate({margin:-6},210,function(){
					$('.search_round').css({'transform':'rotate(-7deg)'});
				}).animate({margin:5},180,function(){
					$('.search_round').css({'transform':'rotate(8deg)'});
				}).animate({margin:-3},150,function(){
					$('.search_round').css({'transform':'rotate(-15deg)'});
				}).animate({margin:4},130,function(){
					$('.search_round').css({'transform':'rotate(11deg)'});
				}).animate({margin:-1},100,function(){
					$('.search_round').css({'transform':'rotate(-7deg)'});
				}).animate({margin:0},80,function(){
					$('.search_round').css({'transform':'rotate(0deg)'});
				});
			}
		}
		if(!$('input[name="user_id"]').val()){
//			alert('学生昵称不能为空');
			flag = 0;
			if(!$('.search_student').parent().is(":animated")){
				$('.search_student').css({'transform':'rotate(10deg)','transition':'all .1s ease-in-out'});
				$('.search_student').parent().animate({margin:-6},210,function(){
					$('.search_student').css({'transform':'rotate(-7deg)'});
				}).animate({margin:5},180,function(){
					$('.search_student').css({'transform':'rotate(8deg)'});
				}).animate({margin:-3},150,function(){
					$('.search_student').css({'transform':'rotate(-15deg)'});
				}).animate({margin:4},130,function(){
					$('.search_student').css({'transform':'rotate(11deg)'});
				}).animate({margin:-1},100,function(){
					$('.search_student').css({'transform':'rotate(-7deg)'});
				}).animate({margin:0},80,function(){
					$('.search_student').css({'transform':'rotate(0deg)'});
				});
			}
		}
		return flag;
    }
});