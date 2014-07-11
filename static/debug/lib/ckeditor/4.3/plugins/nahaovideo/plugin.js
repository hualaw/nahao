(function(){
	var a = {
		exec:function(editor){
			if($('#play-area').length>0){
				$('#play-area').modal('show')
			}else{
				alert('【jason提示】：暂不适用于此页，若要使用请先在bootstrap 3.0风格页面定义id为play-area的元素~！');
			}
		}
	},
	b='nahaovideo';
	CKEDITOR.plugins.add(b, {
        init: function(editor) {
            editor.addCommand(b,a);
            editor.ui.addButton('nahaovideo', {
                label: "那好视频-【版权：jason song】",
				icon: this.path + "nahao.jpg",
                command: b
            });
        }
    });
})();