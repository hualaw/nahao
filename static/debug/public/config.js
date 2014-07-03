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
    if(typeof(_page) != 'undefined'){
        seajs.use("module/"+_page+"/init");
    }
})
