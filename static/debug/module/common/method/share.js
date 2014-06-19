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
					
					//return {"bdText":bd_text,"bdDesc":bd_desc,"bdPic":bd_pic};
				}
			},
			"share": {}
			//"image": {
				// "viewList": ["qzone", "tsina", "tqq", "renren", "weixin"],
				// "viewText": "分享到：",
				// "viewSize": "16"
			//},
			// "selectShare": {
			// 	//分享 范围
			// 	"bdContainerClass": null,
			// 	"bdSelectMiniList": ["qzone", "tsina", "tqq", "renren", "weixin"]
			// }
		};
		with(document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~ (-new Date() / 36e5)];
    };
})