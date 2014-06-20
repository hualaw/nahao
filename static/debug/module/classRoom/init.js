define(function(require,exports){
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
