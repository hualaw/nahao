define(function (require, exports) {
	//入口脚本
	exports.init = function(){
		question_manage = this;
		this.autorun();
		this.operation();
		this.load_ckeditor();
	}
	//初始程序
	exports.autorun = function(){
		$(function () {
//			curtab = $("#myTab").attr('rel');
//			$('#myTab a[href="#'+curtab+'"]').tab('show');
			$('#myTab a[href="#question_list"]').tab('show');
		});
	}
	//加载编辑器
	exports.load_ckeditor = function () {
        CKEDITOR.replace('question',{ toolbar:'Basic', height:100 ,width:700});
        CKEDITOR.replace('option-A',{height:100});
        CKEDITOR.replace('option-B',{height:100});
        CKEDITOR.replace('option-C',{height:100});
        CKEDITOR.replace('option-D',{height:100});
        CKEDITOR.replace('option-E',{height:100});
        CKEDITOR.replace('analysis',{height:100});
    };
	//页面操作
	exports.operation = function(){
		$('.question-item-box').hover(function(){
			$(this).children('.control-box').show();
			$(this).siblings().children('.control-box').hide();
			$(this).children().find('.analysis-area').css({'border':'1px dashed #f60','padding':'5px'});
		},function(){
			$(this).children('.control-box').hide();
			$(this).children().find('.analysis-area').css({'border':'none','padding':'6px'});
		});
		//删题
        $(".delete_question").click(function(){
        	if(confirm("此操作不可恢复，是否继续？")){
        		param = $(this).attr('param');
        		window.location.href="/question/lesson_delete/"+param+"/?"+((new Date).valueOf());
        	}
        });
        //增加选项
        $(".option-add").click(function(){
        	$('#answer_row').children('label.hide:first').removeClass('hide');
			$('.options.hide:first').removeClass('hide');
        });
        //减少选项
        $(".option-remove").click(function(){
        	$('#answer_row').children('label').not('.hide').last().addClass('hide');
			$('.options').not('.hide').last().addClass('hide');
        });
        //提交表单
        $("#question_submit").click(function(){
			re = question_manage.subCheck();
			return false;
//			if(re){
//				document.getElementById('question_form').submit();
//			}else{
//				$('.statusMsg').html('<b class="red">请检查表单遗漏！</b>');
//				return false;
//			}
		});
    }
    //验证表单
    exports.subCheck = function(){
    	flag = 1;
//		if(!$('#question').val()){
//			alert('题目内容必须填写');
//			flag = 0;
//			return false;
//		}
		if(!($("input[name='answer[]']:checked").length>0)){
			alert('正确答案必选');
			flag = 0;
			return false;
		}
//		if(!$('#analysis').val()){
//			alert('题目解析必须填写');
//			flag = 0;
//			return false;
//		}
		if(!$('#option-A').val()){
			alert('A选项必须填写');
			flag = 0;
			return false;
		}
		if(!$('#option-B').val()){
			alert('B选项必须填写');
			flag = 0;
			return false;
		}
		if(!$('#option-C').val()){
			alert('C选项必须填写');
			flag = 0;
			return false;
		}
		if(!$('#option-D').val()){
			alert('D选项必须填写');
			flag = 0;
			return false;
		}
		return flag;
    }
});