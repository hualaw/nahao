define(function(require,exports){
    //初始化首页
    require("module/studentHomePage/homePage").init();
    require("module/studentHomePage/homePage").register_check();
    //首页课程倒计时
   	require("module/studentHomePage/homePage").countDown();
    
})
