KindEditor.plugin("autoheight",function(K){function hideScroll(){var edit=self.edit,body=edit.doc.body;edit.iframe[0].scroll="no",body.style.overflowY="hidden"}function resetHeight(){var edit=self.edit,body=edit.doc.body;edit.iframe.height(minHeight),self.resize(null,Math.max((K.IE?body.scrollHeight:body.offsetHeight)+76,minHeight))}function init(){minHeight=K.removeUnit(self.height),self.edit.afterChange(resetHeight),hideScroll(),resetHeight()}var self=this;if(self.autoHeightMode){var minHeight;self.isCreated?init():self.afterCreate(init)}});