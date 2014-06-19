define(function(require,exports){
	var _classRoom = require("module/classRoom/classRoom");

	var _valid = require("module/classRoom/valid");

    //滚动条
    var _scroll = require("module/common/method/scroll");

    //教室弹层
    var _popUp = require('module/common/method/popUp');
    //学生做题
    student_get_exercise_page = function (class_id){
        _classRoom.show_question();
    }
    //老师出题
    teacher_get_exercise_page = function (class_id) {
        _classRoom.load_questions();
    }
    //老师查看统计
    teacher_get_exercise_stat = function (class_id) {
        _classRoom.load_questions_count();
    }
})