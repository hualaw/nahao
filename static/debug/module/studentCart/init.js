define(function(require,exports){
	require("module/studentCart/tab").tab();
	if ($(".toPay").length>0) {
        $(".ortherBtn").click(function (){
			require("module/common/method/popUp").popUp(".popBox");
        })
	};
    //填写联系方式 验证
    require("module/studentCart/valid").inforCheckForm();	
    // 发送手机验证码
})