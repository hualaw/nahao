define(function(require, exports) {
    //设置学校
    $('.resetSchool').live('click',function(){
        var _this = $(this);
        $.ajax({
            'url' : siteUrl + 'school?id=1',
            'type' : 'GET',
            'dataType' : 'json',
            success : function(json, status){
                var listr = '';
                for (var i = 0; i < json.length; ++i){
                    listr += '<li data-id="'+json[i].id+'" ismunicipality="'+json[i].ismunicipality+'">'+json[i].name+'</li>';
                };
                $('.resetSchoolPopCon .province').html(listr);
                $('.resetSchoolPopCon .province').fadeIn();
                // 判断如果需要配置的话添加自定义属性set=1
                if(_this.attr('set') == '1'){
                    // 获取默认省份id
                    var _province = _this.attr('province');
                    // 获取默认城市id
                    var _city = _this.attr('city');
                    // 获取默认城区id
                    var _county = _this.attr('county');
                    // 默认省份
                    exports.normalData({
                        province:_province,
                        city:_city,
                        county:_county
                    });
                }
                //判断如果需要配置的话添加自定义属性set
            }
        });
        require('naHaoDialog');
        $.dialog({
            id:"setSchollID",
            title:'选择学校',
            top:100,
            content:$('#resetSchoolPop').html().replace('resetSchoolPopCon_beta', 'resetSchoolPopCon'),
            icon:null,
            width:800,
            ok:function(){
                $(".theGenusScholl_n").addClass("undis");
                $(".theGenusScholl_y").removeClass("undis");
                var class_id = $('#class_id').val();
                var school_id = $('.aui_content .school li.active').attr('data-id');
                var province = $(".schoolProvice li.active").html();
                var city = $(".schoolCity li.active").html();
                var county = $(".schoolCounty li.active").html();
                var county_id = $(".schoolCounty li.active").attr('data-id');
                var sctype_id = $('.schoolGrade li.active').attr('data-id');
                var schoolname = $(".schoolName li.active").html();
                var seacherResultname = $('.schoolInfo .seacherResult li.active').html();
                var searcherResultid = $('.schoolInfo .seacherResult li.active').attr('data-id');
                var writeSchoolName = $('.writeSchoolName').val();
                if (typeof province == 'undefined'){province = '';}
                if (typeof city == 'undefined'){city = '';}
                if (typeof county == 'undefined'){county = '';}
                if (typeof writeSchoolName == 'undefined'){writeSchoolName = '';}
                if (typeof seacherResultname == 'undefined'){seacherResultname = '';}
                if (typeof schoolname == 'undefined'){schoolname = '';}
                if (typeof city == 'undefined'){city = '';}
                // 判断学校名称\搜索结果的学校名称\没有我的学校未填写，这三者同时为空的时候返回
                if(schoolname == '' && seacherResultname == '' && writeSchoolName == '' ){
                    this.close();
                    return false;
                }
                var fullname = province + city + county + schoolname + seacherResultname + writeSchoolName;
                if(searcherResultid){
                    school_id = searcherResultid;
                }
                // 判断是否是重设学校
                if(_this.hasClass('resetSchool')){
                    $(".theGenusScholl_n").add("undis");
                    _this.siblings('.schoolFullName').html(fullname);
                    _this.text('重设学校');
                    $("#schoolVal").val(school_id);
                }else{
                    $("#schoolVal").val(school_id);
                    _this.siblings('.schoolFullName').html(fullname);
                    _this.text('重设学校');
                    if($("#schoolVal").val() > 0){
                        $('.schoolBox').find('.ValidformInfo,.Validform_checktip').hide();
                    }
                }
                $('#schoolname').val(writeSchoolName);
                $('#area_county_id').val(county_id);
                $('#school_type').val(sctype_id);
            },
            cancel:true,
            close:function(){
                $('.resetSchoolPopCon .city,.resetSchoolPopCon .county,.resetSchoolPopCon .sctype,.resetSchoolPopCon .schoolInfo').hide();
            }
        });
        // 显示学校地区、学校等
        exports.showSchool();
        // 验证表单
        exports.seacherSchoolValid();
        // 搜索结果方法
        exports.seacheSchoolResult();
        // 加载没有我的学校方法
        exports.noMyScholl();
    });
    // 搜索结果方法
    exports.seacheSchoolResult = function(){
        // 点击reset清空输入框内容和恢复学校内容
        $('span.reset').click(function(){
            $('.schoolNames').val('');
            $('.seacherResult').hide();
            $('.school').fadeIn();
            $('span.reset').addClass('undis');
        })
    }
    // 选择学校搜索学校名称验证
    exports.seacherSchoolValid = function(){
        require('validForm');
        var _Form = $(".seacherSchoolForm").Validform({
            // 自定义tips在输入框上面显示
            tiptype: function(msg, o, cssctl) {
                if (!o.obj.is("form")) {
                    var objtip = o.obj.next().find(".Validform_checktip");
                    objtip.text(msg);
                    o.obj.next().show();
                    var objtip = o.obj.next().find(".Validform_checktip");
                    objtip.text(msg);
                    var infoObj = o.obj.next(".ValidformTips");
                    // 判断验证成功
                    if (o.type == 2) {
                        infoObj.show();
                        o.obj.next().hide();
                    }
                }
            },
            showAllError: false,
            ajaxPost: true,
            callback: function(data){
				var words = $('.schoolNames').val();
				if ($.trim(words) != ''){
					if(/.*[\u4e00-\u9fa5]+.*$/.test(words)){
						$.ajax({
							'url' : siteUrl + 'class/schools/convert?chinese='+encodeURIComponent(words),
							'type' : 'GET',
							'dataType' : 'json',
							success : function(json, status){
								words = json.py;
								exports.query(words);
							}
						});
					} else {
						exports.query(words);
					}
				} else {
					exports.buildSchool();
				}
                $('.school').hide();
                $('.seacherResult').fadeIn();
                $('span.reset').removeClass('undis').show();
            }
        });
        _Form.addRule([{
            ele:".schoolNames",
            datatype:"*",
            nullmsg:"请输入学校名称",
            errormsg:"学校名称输入错误"
            }
        ]);
    };
    
    //搜索学校
    exports.query = function(words){
    	var _li = '';
    	$.each(school_array, function(k, v){
    		$.each(v, function(k2, v2){
    			if (typeof words != 'undefined' && $.trim(words) != ''){
    				if (v2.schoolname.indexOf(words) != -1 || v2.py.indexOf(words) != -1 || v2.first_py.indexOf(words) != -1){
    					_li += '<li data-id="'+v2.id+'">'+v2.schoolname+'</li>';
    				}
    			}
    		});
    	});
    	if (_li == ''){
    		_li = '没有找到相关学校，请重新输入关键词。';
    	}
    	$('.seacherResult ul').html(_li);
    }
    
    //取出学校第一个字母
    exports.buildSchool = function(query) {
        $('.resetSchoolPopCon .schoolInfo').fadeIn();
        var listr = "";
        $.each(school_array, function(k, v) {
            var total = 0, asort = "<dl class='cf'><dt class='fl'>" + k.toUpperCase() + "</dt><dd class='fr'><ul>";
            $.each(v, function(k2, v2) {
                if (typeof query != "undefined" && $.trim(query) != "") {
                    if (v2.schoolname.indexOf(query) != -1 || v2.py.indexOf(query) != -1 || v2.first_py.indexOf(query) != -1) listr += '<li data-id="' + v2.id + '" title="' + v2.schoolname + '">' + v2.schoolname + "</li>";
                } else asort += '<li data-id="' + v2.id + '" title="' + v2.schoolname + '">' + v2.schoolname + "</li>", total++;
            }), asort += '</ul></dd></dl>', total > 0 && (listr += asort);
        }), typeof query != "undefined" && $.trim(query) != "" ? $(".school").html("<dl>" + listr + "</dl>") : $(".school").html(listr), $(".school").css("display", "block");
    };
    // 显示学校地区、学校等
    exports.showSchool = function(){
        //点击省份
        $('.resetSchoolPopCon .province li').live('click', function(){
            $('.resetSchoolPopCon .sctype').hide();
            var _cityName = 
                // 北京
                $(this).attr('data-id') == 2 || 
                // 上海
                $(this).attr('data-id') == 25 || 
                // 天津
                $(this).attr('data-id') == 27 || 
                // 重庆
                $(this).attr('data-id') == 32 || 
                // 香港
                $(this).attr('data-id') == 33 || 
                // 澳门
                $(this).attr('data-id') == 34 || 
                // 台湾
                $(this).attr('data-id') == 35;
            if(_cityName){
                $('.resetSchoolPopCon .city,.resetSchoolPopCon .sctype,.resetSchoolPopCon .schoolInfo').hide();
            }else{
                $('.resetSchoolPopCon .county,.resetSchoolPopCon .schoolInfo').hide();
            };
            if($(this).attr('class') !== 'active'){
                var id = $(this).attr('data-id');
                var ismunicipality = $(this).attr('ismunicipality');
                $.ajax({
                    'url' : siteUrl + 'school?id='+id,
                    'type' : 'GET',
                    'dataType' : 'json',
                    success : function(json, status){
                        var listr = '';
                        for (var i = 0; i < json.length; ++i){
                            listr += '<li data-id="'+json[i].id+'">'+json[i].name+'</li>';
                        }
                        if (ismunicipality == 1){
                            $('.resetSchoolPopCon .county').html(listr);
                            $('.resetSchoolPopCon .county').fadeIn();
                        } else {
                            $('.resetSchoolPopCon .city').html(listr);
                            $('.resetSchoolPopCon .city').fadeIn();
                        }
                    }
                });
            }
        });
        //点击城市
        $('.resetSchoolPopCon .city li').live('click', function(){
            $('.resetSchoolPopCon .sctype,.resetSchoolPopCon .schoolInfo').hide();
            if($(this).attr('class') !== 'active'){
                var id = $(this).attr('data-id');
                $.ajax({
                    'url' : siteUrl + 'school?id='+id,
                    'type' : 'GET',
                    'dataType' : 'json',
                    success : function(json, status){
                        var listr = '';
                        for (var i = 0; i < json.length; ++i){
                            listr += '<li data-id="'+json[i].id+'">'+json[i].name+'</li>';
                        }
                        $('.resetSchoolPopCon .county').html(listr);
                        $('.resetSchoolPopCon .county').css('display', 'block');
                    }
                });
            }
        });
        //点击城镇
        $('.resetSchoolPopCon .county li').live('click', function(){
            $('.resetSchoolPopCon .sctype,.resetSchoolPopCon .schoolInfo').hide();
            $.ajax({
                'url' : siteUrl + 'school/type',
                'type' : 'GET',
                'dataType' : 'json',
                success : function(json, status){
                    var listr = '';
                    for (var i = 0; i < json.length; ++i){
                        listr += '<li data-id="'+json[i].id+'">'+json[i].name+'</li>';
                    }
                    $('.resetSchoolPopCon .sctype').html(listr);
                    $('.resetSchoolPopCon .sctype').fadeIn();
                }
            });
        });
        //点击学校
        $('.resetSchoolPopCon .sctype li').live('click', function(){
            // 获取选择的省市县开始
            var _thisPro = $('.resetSchoolPopCon .province li.active').html();
            var _thisCity = $('.resetSchoolPopCon .city li.active').html();
            if (typeof _thisCity == 'undefined'){_thisCity = '';}
            var _thisCounty = $('.resetSchoolPopCon .county li.active').html();
            var _thisSctype  = $(this).html();
            $('span#sName').html(_thisPro+_thisCity+_thisCounty+"的"+_thisSctype);
            // 获取选择的省市县结束
            $(".resetSchoolPopCon .seacherSchoolForm").Validform().resetForm();
            // 屏蔽默认光标在输入框开始
            $('.schoolNames').focus(function(){
                return false;
            })
            // 屏蔽默认光标在输入框结束
            $('.ValidformInfo').hide();
            $('.resetSchoolPopCon .schoolInfo,.resetSchoolPopCon .schoolInfo .hd').show();
            $('.resetSchoolPopCon .schoolNames').val('');
            $('.resetSchoolPopCon .schoolInfo .seacherResult,.resetSchoolPopCon .schoolInfo .reset,.resetSchoolPopCon .schoolInfo .bd').hide();
            if($(this).attr('class') !== 'active'){
                var sctype = $(this).attr('data-id');
                var county_id = $('.aui_content .county li.active').attr('data-id');
                $.ajax({
                    'url' : siteUrl+'school/get_school',
                    'type' : 'GET',
                    'dataType' : 'json',
                    'data' : {
                        'id' : county_id,
                        'sctype' : sctype
                    },
                    success : function(json, status){
                        school_array = json;
                        exports.buildSchool();
                    }
                });
            };
        });
        //设置学校点击地区效果
        $('.schooLocation li').live('click', function(){
            $(this).addClass('active').siblings().removeClass('active');
        }); 
        //设置学校点击学校效果
        $('.school li').live('click', function(){
            $('.school li').removeClass('active');
            $(this).addClass('active');
        });
        //点击搜索结果的li
        $('.seacherResult li').live('click', function(){
			$('.seacherResult li').removeClass('active');
			$(this).addClass('active');
        });  
    }
    // 点击没有我的学校
    exports.noMyScholl = function(){
        // 点击没有我的学校
        $(".noMySchollBtn").live("click",function(){
            $('.resetSchoolPopCon .schoolInfo .hd,.resetSchoolPopCon .schoolInfo .seacherResult').hide();
            $('.resetSchoolPopCon .school').hide();
            $('.resetSchoolPopCon .schoolInfo .bd').fadeIn();
        });
        // 点击返回选择学校
        $(".returnSetSchool").live("click",function(){
            $('.resetSchoolPopCon .schoolInfo .bd').hide();
            $('.resetSchoolPopCon .schoolInfo .hd').fadeIn();
            $('.resetSchoolPopCon .school').fadeIn();
        });
    }

    //当前默认值
    exports.normalData = function(_json){
        $('.resetSchoolPopCon .province li').each(function(){
            if($(this).attr('data-id')  == _json.province){
                $(this).addClass('active');
                var _cityName = 
                    // 北京
                    $(this).attr('data-id') == 2 || 
                    // 上海
                    $(this).attr('data-id') == 25 || 
                    // 天津
                    $(this).attr('data-id') == 27 || 
                    // 重庆
                    $(this).attr('data-id') == 32 || 
                    // 香港
                    $(this).attr('data-id') == 33 || 
                    // 澳门
                    $(this).attr('data-id') == 34 || 
                    // 台湾
                    $(this).attr('data-id') == 35;
                if(_cityName){
                    $('.resetSchoolPopCon .city,.resetSchoolPopCon .sctype,.resetSchoolPopCon .schoolInfo').hide();
                }else{
                    $('.resetSchoolPopCon .county,.resetSchoolPopCon .schoolInfo').hide();
                };
                // if($(this).attr('class') !== 'active'){
                    // var id = $(this).attr('data-id');
                    var ismunicipality = $(this).attr('ismunicipality');
                    $.ajax({
                        'url' : siteUrl + 'school?id='+_json.province,
                        'type' : 'GET',
                        'dataType' : 'json',
                        success : function(json, status){
                            var listr = '';
                            for (var i = 0; i < json.length; ++i){
                                listr += '<li data-id="'+json[i].id+'">'+json[i].name+'</li>';
                            }
                            if (ismunicipality == 1){
                                $('.resetSchoolPopCon .county').html(listr).fadeIn();
                                $('.resetSchoolPopCon .county li').each(function(){
                                    if($(this).attr('data-id')  == _json.county){
                                        $(this).addClass('active');
                                        $('.resetSchoolPopCon .sctype,.resetSchoolPopCon .schoolInfo').hide();
                                        $.ajax({
                                            'url' : siteUrl + 'school/type',
                                            'type' : 'GET',
                                            'dataType' : 'json',
                                            success : function(json, status){
                                                var listr = '';
                                                for (var i = 0; i < json.length; ++i){
                                                    listr += '<li data-id="'+json[i].id+'">'+json[i].name+'</li>';
                                                }
                                                $('.resetSchoolPopCon .sctype').html(listr);
                                                $('.resetSchoolPopCon .sctype').fadeIn();
                                            }
                                        });
                                    }
                                });
                            } else {
                                // console.log(listr);
                                $('.resetSchoolPopCon .city').html(listr);
                                // 加载城市数据开始
                                $('.resetSchoolPopCon .city li').each(function(){
                                    if($(this).attr('data-id')  == _json.city){
                                        $(this).addClass('active');
                                        // 展开默认城市开始
                                        $('.resetSchoolPopCon .sctype,.resetSchoolPopCon .schoolInfo').hide();
                                        // if($(this).attr('class') !== 'active'){
                                            var id = $(this).attr('data-id');
                                            $.ajax({
                                                'url' : siteUrl + 'school?id='+_json.city,
                                                'type' : 'GET',
                                                'dataType' : 'json',
                                                success : function(json, status){
                                                    var listr = '';
                                                    for (var i = 0; i < json.length; ++i){
                                                        listr += '<li data-id="'+json[i].id+'">'+json[i].name+'</li>';
                                                    }
                                                    $('.resetSchoolPopCon .county').html(listr).show();
                                                    $('.resetSchoolPopCon .county li').each(function(){
                                                        if($(this).attr('data-id')  == _json.county){
                                                            $(this).addClass('active');
                                                            $('.resetSchoolPopCon .sctype,.resetSchoolPopCon .schoolInfo').hide();
                                                            $.ajax({
                                                                'url' : siteUrl + 'school/type',
                                                                'type' : 'GET',
                                                                'dataType' : 'json',
                                                                success : function(json, status){
                                                                    var listr = '';
                                                                    for (var i = 0; i < json.length; ++i){
                                                                        listr += '<li data-id="'+json[i].id+'">'+json[i].name+'</li>';
                                                                    }
                                                                    $('.resetSchoolPopCon .sctype').html(listr);
                                                                    $('.resetSchoolPopCon .sctype').fadeIn();
                                                                }
                                                            });
                                                        }
                                                    });
                                                }
                                            });
                                        // };
                                        // 展开默认城市结束
                                    };
                                    // 去除城市的默认值以外li的点击事件
                                    // if(!$(this).hasClass('class')){
                                    //     $(this).addClass('default');
                                    //     $(this).removeAttr('data-id').click(function(){
                                    //         return false;
                                    //     })
                                    // };
                                    // 隐藏没有我的学校功能
                                    $('.noMySchollBtn').hide();
                                });
                                // 加载城市数据结束
                                $('.resetSchoolPopCon .city').fadeIn();
                            }
                        }
                    });
                // }
            };
            // 去除省份的默认值以外li的点击事件
            // if(!$(this).hasClass('class')){
            //     $(this).addClass('default');
            //     $(this).removeAttr('data-id').click(function(){
            //         return false;
            //     })
            // };
        });
    }
});
