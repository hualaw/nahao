define(function(require,exports){
	//随滚菜单
	exports.fellowNav = function (){
		var _courseID;
		var _box = $('#course_info_nav'),
			_top = _box.offset().top,
			_courseID = $('#course_comment_courseID').val();
				
		setCourseTabPositon(_top);
		$(window).scroll(function(){
			var _winTop = $(window).scrollTop();
			setCourseTabPositon(_top, _winTop);
			setCourseHandle(_winTop);
		});	
			
		//tab
		$('#course_info_nav li.detail').click(function(){
			var _id = $(this).attr('con');
			if(_id){
				setCourseHandleCurrent($(this));
				courseInfo('#' + _id);
			}
		});
		//课程咨询AJAX异步提交
		$('#course_question_form').submit(function(){
			addCourseQuestion();
		});
		/*
		 * 设置tab的位置
		 */
		function setCourseTabPositon(top, winTop){
			var _t = winTop || $(window).scrollTop(),
				_box = $('#course_info_nav');
			if(_t > top){
				_box.addClass('tab_scoll');
				var isIE6 = /MSIE 6\./.test(navigator.userAgent) && !window.opera;
				if (isIE6) {
		            _box.animate({
		                top: _t
		            },
		            {
		                duration: 600,
		                queue: false
		            });
			    }
			}else{
				_box.removeClass('tab_scoll');
			}		
		}
		/*
		 * 课程信息
		 */
		function courseInfo(id){
			var _box = $(id),
				_top = (id == '#course_content') ? 39 : 39,
				_top = _box.offset().top - _top;
			$(window).scrollTop(_top);
		}

		/*
		 * 根据滚动的位置，显示tab当前状态
		 */
		function setCourseHandle(top){
			var _top = top || $(window).scrollTop();
			var _tabs = $('#course_info_nav li.detail'),
				_item = $('#course_info_box .course_info_item'),
				_lens = _item.length;
				
			_item.each(function(i){
				var _thisTop = $(this).offset().top - 40,
					_next = $(this).next('.course_info_item'),

					_tab = _tabs.eq(i);
				if(_top < _thisTop && i == 0){
					setCourseHandleCurrent(_tabs.first());
					return;
				}
				if((_top > _thisTop || _top == _thisTop) && _next.length == 0){
					setCourseHandleCurrent(_tabs.last());
					return;
				}
				if(_next.length > 0){
					var _nextTop = _next.offset().top - 40;
					if((_top > _thisTop || _top == _thisTop) && _top < _nextTop){
						setCourseHandleCurrent(_tab);
						return;
			}
		}

			});
		}

		/*
		 * 设置tab样式当前状态
		 */
		function setCourseHandleCurrent(d){
			d.addClass('current').siblings('.detail').removeClass('current');
		}
	};
	//模拟日期下拉
	exports.timeSelect = function(){
		$('.selectBox').hover(function(){
			$('.selectInfo').addClass('mouseOver');
			$('.selectInfo').next('ul').show();
		},function(){
			$('.selectInfo').removeClass('mouseOver');
			$('.selectInfo').next('ul').hide();
		});
		$('.selectUl').find('li').live('click',function(){
			var _txt = $(this).text();
			var round_id = $(this).attr('data');
			$('.firmTime').text(_txt);
			$('.selectUl').hide();
			window.location.href="/ke_"+round_id+".html"
		});
	};
});



