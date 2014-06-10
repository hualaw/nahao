define(function (require, exports) {
    exports.load_ckeditor = function () {
        CKEDITOR.replace('question_1');
        CKEDITOR.replace('question_2');
        CKEDITOR.replace('question_3');
        CKEDITOR.replace('question_4');
        CKEDITOR.replace('question_5');
    };
	
    exports.validate = function(){
        var grade_from = $("#grade_from").val();
        var grade_to = $("#grade_to").val();
    }
});