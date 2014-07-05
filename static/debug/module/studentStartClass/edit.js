define(function(require,exports){
    exports.edit = function (){
        //投稿编辑器
        seajs.use("kindEditor",function(){
            //  KindEditor.ready(function(K){
            //    K.create("#postEditor",{
            //         //简易版
            //         items : [
            //                     'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            //                     'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
            //                     'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
            //         //字符检测
            //        afterChange : function() {
            //             //（字数统计包含纯文本、IMG、EMBED，不包含换行符，IMG和EMBED算一个文字。）
            //             K('.word_count2').html(10000-this.count('text'));
            //             $('.word_count1').html(KindEditor.instances[0].html().length);
            //             this.sync();
            //         }
            //     });
            // });
        });
        // seajs.use("kindEditor",function(){
        //      KindEditor.ready(function(K){
        //        K.create("#introEditor",{
        //             //简易版
        //             items : [
        //                         'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
        //                         'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
        //                         'insertunorderedlist', '|', 'emoticons', 'image', 'link'],
        //             //字符检测
        //            afterChange : function() {
        //                 //（字数统计包含纯文本、IMG、EMBED，不包含换行符，IMG和EMBED算一个文字。）
        //                 K('.word_count2').html(10000-this.count('text'));
        //                 $('.word_count1').html(KindEditor.instances[0].html().length);
        //                 this.sync();
        //             }
        //         });
        //     });
        // });
    }
})