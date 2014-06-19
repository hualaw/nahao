define(function(require,exports){
	//分享
    exports.shareInsertBg = function(){
    	
	  	window._bd_share_config = {
			"common": {
				"bdSnsKey": {},
				"bdText": "",
				'bdDesc':"",
				"bdMini": "2",
				"bdPic": "",
				"bdStyle": "0",
				"bdSize": "24",
				"bdUrl" : "",
				"onBeforeClick" : function(){
					
				}
			},
			"share": {}
		};
		with(document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~ (-new Date() / 36e5)];
    };
})