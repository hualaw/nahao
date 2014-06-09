// 配置sea的路径，别名等参数
// 获取当前时间
var myDate = new Date();
var timestamp = myDate.getFullYear() + '' + myDate.getMonth() + 1 + '' + myDate.getDate() + '' + myDate.getHours() + '' + myDate.getMinutes();
// seajs配置开始
seajs.config({
    // 基础目录
    base: staticPath,
    // 别名
    alias: aliasContent,
    preload: ["jquery"],
    // 映射
    'map': [
        [ /^(.*\.(?:css|js))(.*)$/i, '$1?version=' + timestamp ]
    ]
})

// 调用方法
seajs.use('jquery', function () {
    //加载全站公共方法入口
    seajs.use('module/nahaoCommon/init');
    // 得到当前页面是哪个
    var _page = $('#nahaoModule').attr('module');
    seajs.use("module/"+_page+"/init");
    switch (_page) {
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
        //管理员系统用户管理
        case 'adminAdmin':
            seajs.use("module/adminAdmin/init");
            break;
        //管理员组管理
        case 'adminGroup':
            seajs.use("module/adminGroup/init");
            break;
        case 'adminOrder':
            seajs.use("module/adminOrder/init");
        case 'adminLecture':
            seajs.use("module/adminLecture/init");
        //管理员学生管理
        case 'adminStudent':
            seajs.use("module/adminStudent/init");
            break;
        //管理员首页
        case 'adminIndex':
            seajs.use("module/adminIndex/init");
            break;
        //管理员课程管理
        case 'adminCourse':
            seajs.use("module/adminCourse/init");
            break;
        //管理员轮管理
        case 'adminRound':
            seajs.use("module/adminRound/init");
            break;
        case 'studentStudy':
            seajs.use("module/studentStudy/init");
            break;
        // 模块是老师端首页
        case 'teacherHomePage':
            seajs.use("module/teacherHomePage/init");
            break;
        // 模块是班次列表
        case 'teacherOrderList':
            seajs.use("module/teacherOrderList/init");
            break;
        // 模块是个人资料
        case 'teacherSelfInfo':
            seajs.use("module/teacherSelfInfo/init");
            break;
        // 模块是课酬结算
        case 'teacherPay':
            seajs.use("module/teacherPay/init");
        // 模块是教室
        case 'classRoom':
            seajs.use("module/classRoom/init");
            break;
        // 模块是题目管理
        case 'adminQuestion':
            seajs.use("module/adminQuestion/init");
            break;
    }
})
