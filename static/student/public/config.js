// 配置sea的路径，别名等参数
// 获取当前时间
var myDate = new Date();
var timestamp = myDate.getFullYear() +'' + myDate.getMonth()+1 + '' + myDate.getDate() + '' + myDate.getHours() + '' + myDate.getMinutes();
// seajs配置开始
seajs.config({
	// 基础目录
    base: staticPath,
    // 别名
    alias: aliasContent,
	preload:["jquery"],
	// 映射
	'map': [
	    [ /^(.*\.(?:css|js))(.*)$/i, '$1?version=' + timestamp ]
	  ]
})


// 调用方法
seajs.use('jquery',function(){
	//加载全站公共方法入口
	seajs.use('module/nahaoCommon/init');
	// 得到当前页面是哪个
	var _page = $('#nahaoModule').attr('module');
	switch(_page){
		// 模块是首页
		case 'homePage':
		seajs.use("module/homePage/init");
		break;
		// 模块是注册忘记密码等模块
		case 'login':
		seajs.use("module/login/init");
		break;
		// 模块是购物车
		case 'cart':
		seajs.use("module/cart/init");
		break;
		// 模块是我的课程
		case 'myCourse':
		seajs.use("module/myCourse/init");
		break;
		// 模块是我要开课
		case 'startClass':
		seajs.use("module/startClass/init");
		break;
		// 模块是我要开课
		case 'study':
		seajs.use("module/study/init");
		break;
	}
})
