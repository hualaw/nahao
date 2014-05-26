KindEditor.plugin("media",function(K){var self=this,name="media",lang=self.lang(name+"."),allowMediaUpload=K.undef(self.allowMediaUpload,!0),allowFileManager=K.undef(self.allowFileManager,!1),formatUploadUrl=K.undef(self.formatUploadUrl,!0),extraParams=K.undef(self.extraFileUploadParams,{}),filePostName=K.undef(self.filePostName,"imgFile"),uploadJson=K.undef(self.uploadJson,self.basePath+"php/upload_json.php");self.plugin.media={edit:function(){var html=['<div style="padding:20px;">','<div class="ke-dialog-row">','<label for="keUrl" style="width:60px;">'+lang.url+"</label>",'<input class="ke-input-text" type="text" id="keUrl" name="url" value="" style="width:160px;" /> &nbsp;','<input type="button" class="ke-upload-button" value="'+lang.upload+'" /> &nbsp;','<span class="ke-button-common ke-button-outer">','<input type="button" class="ke-button-common ke-button" name="viewServer" value="'+lang.viewServer+'" />',"</span>","</div>",'<div class="ke-dialog-row">','<label for="keWidth" style="width:60px;">'+lang.width+"</label>",'<input type="text" id="keWidth" class="ke-input-text ke-input-number" name="width" value="550" maxlength="4" />',"</div>",'<div class="ke-dialog-row">','<label for="keHeight" style="width:60px;">'+lang.height+"</label>",'<input type="text" id="keHeight" class="ke-input-text ke-input-number" name="height" value="400" maxlength="4" />',"</div>",'<div class="ke-dialog-row">','<label for="keAutostart">'+lang.autostart+"</label>",'<input type="checkbox" id="keAutostart" name="autostart" value="" /> ',"</div>","</div>"].join(""),dialog=self.createDialog({name:name,width:450,height:230,title:self.lang(name),body:html,yesBtn:{name:self.lang("yes"),click:function(){var url=K.trim(urlBox.val()),width=widthBox.val(),height=heightBox.val();if("http://"==url||K.invalidUrl(url))return alert(self.lang("invalidUrl")),urlBox[0].focus(),void 0;if(!/^\d*$/.test(width))return alert(self.lang("invalidWidth")),widthBox[0].focus(),void 0;if(!/^\d*$/.test(height))return alert(self.lang("invalidHeight")),heightBox[0].focus(),void 0;var html=K.mediaImg(self.themesPath+"common/blank.gif",{src:url,type:K.mediaType(url),width:width,height:height,autostart:autostartBox[0].checked?"true":"false",loop:"true"});self.insertHtml(html).hideDialog().focus()}}}),div=dialog.div,urlBox=K('[name="url"]',div),viewServerBtn=K('[name="viewServer"]',div),widthBox=K('[name="width"]',div),heightBox=K('[name="height"]',div),autostartBox=K('[name="autostart"]',div);if(urlBox.val("http://"),allowMediaUpload){var uploadbutton=K.uploadbutton({button:K(".ke-upload-button",div)[0],fieldName:filePostName,extraParams:extraParams,url:K.addParam(uploadJson,"dir=media"),afterUpload:function(data){if(dialog.hideLoading(),0===data.error){var url=data.url;formatUploadUrl&&(url=K.formatUrl(url,"absolute")),urlBox.val(url),self.afterUpload&&self.afterUpload.call(self,url,data,name),alert(self.lang("uploadSuccess"))}else alert(data.message)},afterError:function(html){dialog.hideLoading(),self.errorDialog(html)}});uploadbutton.fileBox.change(function(){dialog.showLoading(self.lang("uploadLoading")),uploadbutton.submit()})}else K(".ke-upload-button",div).hide();allowFileManager?viewServerBtn.click(function(){self.loadPlugin("filemanager",function(){self.plugin.filemanagerDialog({viewType:"LIST",dirName:"media",clickFn:function(url){self.dialogs.length>1&&(K('[name="url"]',div).val(url),self.afterSelectFile&&self.afterSelectFile.call(self,url),self.hideDialog())}})})}):viewServerBtn.hide();var img=self.plugin.getSelectedMedia();if(img){var attrs=K.mediaAttrs(img.attr("data-ke-tag"));urlBox.val(attrs.src),widthBox.val(K.removeUnit(img.css("width"))||attrs.width||0),heightBox.val(K.removeUnit(img.css("height"))||attrs.height||0),autostartBox[0].checked="true"===attrs.autostart}urlBox[0].focus(),urlBox[0].select()},"delete":function(){self.plugin.getSelectedMedia().remove(),self.addBookmark()}},self.clickToolbar(name,self.plugin.media.edit)});