KindEditor.plugin("pagebreak",function(K){var self=this,name="pagebreak",pagebreakHtml=K.undef(self.pagebreakHtml,'<hr style="page-break-after: always;" class="ke-pagebreak" />');self.clickToolbar(name,function(){var cmd=self.cmd,range=cmd.range;self.focus();var tail="br"==self.newlineTag||K.WEBKIT?"":'<span id="__kindeditor_tail_tag__"></span>';if(self.insertHtml(pagebreakHtml+tail),""!==tail){var p=K("#__kindeditor_tail_tag__",self.edit.doc);range.selectNodeContents(p[0]),p.removeAttr("id"),cmd.select()}})});