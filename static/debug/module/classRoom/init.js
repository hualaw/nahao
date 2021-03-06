define(function(require,exports){

	
	var _classRoom = require("module/classRoom/classRoom");

    //学生做题
    student_get_exercise_page = function (class_id){
        _classRoom.show_question();
//    	_classRoom.class_comment();
    }
    //老师出题
    teacher_get_exercise_page = function (class_id) {
        _classRoom.load_questions();
    }
    //老师查看统计
    teacher_get_exercise_stat = function (class_id) {
        _classRoom.load_questions_count();
    }
    
    //学生对课的评价
    student_send_evaluation = function (class_id){
    	//弹出评论框
    	_classRoom.class_comment();
    }
    
})
