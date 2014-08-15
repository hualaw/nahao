define(function(require,exports){
	// 头部下拉菜单入口
	exports.init = function(){
		// 我的那好
		exports.slidownFn('.topMiniNav .myNahao','myNahaoHover');
		// 关注
		exports.slidownFn('.topMiniNav .attention','attentionHover');
		// 导航上选择课程方法
		exports.categorysFn();
	};
	// 头部下拉菜单公共方法
	exports.slidownFn = function(id,_classname){
		$(id).hover(function(){
			$(this).addClass(_classname);
			$(this).find('ul').removeClass('undis');
		},function(){
			$(this).removeClass(_classname);
			$(this).find('ul').addClass('undis');
		});
	};
	// 头部导航菜单的选择课程分类方法
	exports.categorysFn = function(){
		$('.topNav .categorys').hover(function(){
			$(this).find('.title').addClass('shutting');
			$(this).find('dl').removeClass('undis');
		},function(){
			$(this).find('.title').removeClass('shutting');
			$(this).find('dl').addClass('undis');
		})
	}
})