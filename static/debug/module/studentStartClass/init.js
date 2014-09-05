define(function(require,exports){
        //倒入jquery
        require("jquery");
        // 公共select模拟
        require('select');
        //倒入dialog
        require("naHaoDialog");
        if($(".popBox").length>0){
            require("module/common/method/popUp").popUp(".popBox");
        };
        var _setSchool=require("module/common/method/setSchool");
        //我要开课验证
        var _valid = require("module/studentStartClass/valid");
        //判断当前页面时注册成功的关于我的页面
        if($('.writeInfo').length > 0){
            // 美化select
            $('select').jqTransSelect();
            // 美化radio
            $('input[type=radio]').jqTransRadio();
            // 美化checkBo
            $('input[type=checkbox]').jqTransCheckBox();

            // require("module/studentStartClass/edit").edit();
            //时间插件
            require("module/studentStartClass/datePlugin").addDatePlugin();
            //我要开课 试讲 信息 验证
            _valid.writeInfoForm();
        }
        if($(".regTeacher").length){
            //我要开课 老师注册验证
            _valid.teaRegForm();
        }

        $(".resetSchool").click(function(){
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
            console.log("studentStartClass里面");
            $.tiziDialog({
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
                    var province = $(".resetSchoolPopCon .schoolProvice li.active").html();
                    var city = $(".resetSchoolPopCon .schoolCity li.active").html();
                    var county = $(".resetSchoolPopCon .schoolCounty li.active").html();
                    var province_id = $(".resetSchoolPopCon .province li.active").attr('data-id');
                    var city_id = $(".resetSchoolPopCon .city li.active").attr('data-id');
                    var county_id = $(".resetSchoolPopCon .schoolCounty li.active").attr('data-id');
                    var sctype_id = $('.resetSchoolPopCon .schoolGrade li.active').attr('data-id');
                    var schoolname = $(".resetSchoolPopCon .schoolName li.active").html();
                    var seacherResultname = $('.resetSchoolPopCon .schoolInfo .seacherResult li.active').html();
                    var searcherResultid = $('.schoolInfo .seacherResult li.active').attr('data-id');
                    var writeSchoolName = $('.resetSchoolPopCon .writeSchoolName').val();
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
                        $('.schoolFullName').val(fullname);
                        $('.resetSchool').text('重设学校');
                        $("#schoolVal").val(school_id);
                    }else{
                        $("#schoolVal").val(school_id);
                        $('.schoolFullName').val(fullname);
                        $('.resetSchool').text('重设学校');
                        if($("#schoolVal").val() > 0){
                            $('.schoolBox').find('.ValidformInfo,.Validform_checktip').hide();
                        }
                    };

                    $('#province_id').val(province_id);
                    $('#city_id').val(city_id);
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
            _setSchool.showSchool();
            // 加载没有我的学校方法
            _setSchool.noMyScholl();
            // 验证表单
            _setSchool.seacherSchoolValid();
            // 搜索结果方法
            _setSchool.seacheSchoolResult();
        });
})
