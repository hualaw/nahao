define(function (require, exports) {
	//入口脚本
	exports.init = function(){
		tools_manage = this;
		this.autorun();
	}
	//初始程序
	exports.autorun = function(){
		$(function () {
//			$('#myTab a[href="#question_list"]').tab('show');
		});
	}
	//操作方法
	exports.autorun = function(){
		//创建订单模板
		$('.create_order').click(function(){
			var copy = $('.order-copy').html();
			var curId = (new Date).valueOf();
			copyHtml = '<tbody class="order-item" id="order-'+curId+'">'+copy+'</tbody>';
			$('.orderTable').append(copyHtml);
			$("#order-"+curId).find('.del-order').attr('data-order',curId);
		});
		//删除订单模板
		$('.del-order').live('click',function(){
			delId = $(this).attr('data-order');
			$("#order-"+delId).remove();
		});
		//生成订单内容
		
	}
    //验证表单
    exports.subCheck = function(){
    	flag = 1;
		if(!$('#question').val()){
			alert('题目内容必须填写');
			flag = 0;
			return false;
		}
		if(!($("input[name='answer[]']:checked").length>0)){
			alert('正确答案必选');
			flag = 0;
			return false;
		}
		return flag;
    }
});