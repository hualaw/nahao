define(function(require,exports){
	$(function(){
	$(".live_an").click(function(){
		var cid = $.trim($("#cid").val());
		var token = $.trim($("#token").val());
		var name = $.trim($("#username").val());
		if(name==""){
			$(".live_mis_ts").html("<i></i>亲爱的同学，进教室之前给自己起个名字哟！").show();
			//  
		}else if(name.indexOf("中华")>=0 || name.indexOf("仁和")>=0 || name.indexOf("东奥")>=0||name.indexOf("恒企")>=0){
			$(".live_mis_ts").html("<i></i>该用户名已经被使用").show();
		}else{
			$(".live_an").unbind("click");
			window.location.href="/course/go?cid="+cid+"&nickname="+encodeURI(name)+"&token="+token;
		}
	});
	document.onkeydown = function (event) {
        e = event ? event : (window.event ? window.event : null);
        if (e.keyCode == 13) {
        	$(".live_an").click();
        }
    }
	})
})