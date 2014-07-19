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
		$('.toolbar').click(function(){
			target = $(this).attr('data');
			$('#'+target).attr('class','panel-collapse in').siblings().attr('class','panel-collapse collapse');
		});
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