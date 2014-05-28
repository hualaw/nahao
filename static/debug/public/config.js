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
		// 模块是学生首页
		case 'studentHomePage':
		seajs.use("module/studentHomePage/init");
		break;
		// 模块是注册忘记密码等模块
		case 'login':
		seajs.use("module/login/init");
		break;
		// 模块是学生购物车
		case 'studentCart':
		seajs.use("module/studentCart/init");
		break;
		// 模块是学生我的课程
		case 'studentMyCourse':
		seajs.use("module/studentMyCourse/init");
		break;
		// 模块是学生我要开课
		case 'studentStartClass':
		seajs.use("module/studentStartClass/init");
		break;
		// 模块是学生我要开课
        case 'studentStudy':
        seajs.use("module/studentStudy/init");
        break;
        case 'adminAdmin':
            seajs.use("module/adminAdmin/init");
            break;
        case 'adminGroup':
            seajs.use("module/adminGroup/init");
            break;
        //订单
        case 'adminOrder':
            seajs.use("module/adminOrder/init");
            break;
	}
})
