/* Import theme specific language pack */
 tinyMCE.importThemeLanguagePack('advanced');var TinyMCE_advanced_autoImportCSSClasses=true;var TinyMCE_advanced_foreColor="#000000";var TinyMCE_advanced_anchorName="";var TinyMCE_advanced_buttons=[['bold','{$lang_bold_img}','{$lang_bold_desc}','Bold'],['italic','{$lang_italic_img}','{$lang_italic_desc}','Italic'],['underline','{$lang_underline_img}','{$lang_underline_desc}','Underline'],['strikethrough','strikethrough.gif','{$lang_striketrough_desc}','Strikethrough'],['justifyleft','left.gif','{$lang_justifyleft_desc}','JustifyLeft'],['justifycenter','center.gif','{$lang_justifycenter_desc}','JustifyCenter'],['justifyright','right.gif','{$lang_justifyright_desc}','JustifyRight'],['justifyfull','full.gif','{$lang_justifyfull_desc}','JustifyFull'],['bullist','bullist.gif','{$lang_bullist_desc}','InsertUnorderedList'],['numlist','numlist.gif','{$lang_numlist_desc}','InsertOrderedList'],['outdent','outdent.gif','{$lang_outdent_desc}','Outdent'],['indent','indent.gif','{$lang_indent_desc}','Indent'],['cut','cut.gif','{$lang_cut_desc}','Cut'],['copy','copy.gif','{$lang_copy_desc}','Copy'],['paste','paste.gif','{$lang_paste_desc}','Paste'],['undo','undo.gif','{$lang_undo_desc}','Undo'],['redo','redo.gif','{$lang_redo_desc}','Redo'],['link','link.gif','{$lang_link_desc}','mceLink',true],['unlink','unlink.gif','{$lang_unlink_desc}','unlink'],['image','image.gif','{$lang_image_desc}','mceImage',true],['cleanup','cleanup.gif','{$lang_cleanup_desc}','mceCleanup'],['help','help.gif','{$lang_help_desc}','mceHelp'],['code','code.gif','{$lang_theme_code_desc}','mceCodeEditor'],['hr','hr.gif','{$lang_theme_hr_desc}','inserthorizontalrule'],['removeformat','removeformat.gif','{$lang_theme_removeformat_desc}','removeformat'],['sub','sub.gif','{$lang_theme_sub_desc}','subscript'],['sup','sup.gif','{$lang_theme_sup_desc}','superscript'],['forecolor','forecolor.gif','{$lang_theme_forecolor_desc}','mceForeColor',true],['backcolor','backcolor.gif','{$lang_theme_backcolor_desc}','mceBackColor',true],['charmap','charmap.gif','{$lang_theme_charmap_desc}','mceCharMap'],['visualaid','visualaid.gif','{$lang_theme_visualaid_desc}','mceToggleVisualAid'],['anchor','anchor.gif','{$lang_theme_anchor_desc}','mceInsertAnchor'],['newdocument','newdocument.gif','{$lang_newdocument_desc}','mceSetContent',false,'']];function TinyMCE_advanced_getControlHTML(button_name){var buttonTileMap=new Array('anchor.gif','backcolor.gif','bullist.gif','center.gif','charmap.gif','cleanup.gif','code.gif','copy.gif','custom_1.gif','cut.gif','forecolor.gif','full.gif','help.gif','hr.gif','image.gif','indent.gif','left.gif','link.gif','numlist.gif','outdent.gif','paste.gif','redo.gif','removeformat.gif','right.gif','strikethrough.gif','sub.gif','sup.gif','undo.gif','unlink.gif','visualaid.gif');for(var i=0;i<TinyMCE_advanced_buttons.length;i++){var but=TinyMCE_advanced_buttons[i];if(but[0]==button_name){var safariPatch='" onclick="';if(tinyMCE.isSafari)safariPatch="";if(tinyMCE.settings['button_tile_map']){for(var x=0;!tinyMCE.isMSIE&&x<buttonTileMap.length;x++){if(buttonTileMap[x]==but[1])return '<img id="{$editor_id}_'+but[0]+'" src="{$themeurl}/images/spacer.gif" style="background-image:url({$themeurl}/images/buttons.gif); background-position: '+(0-(x*20))+'px 0px" title="'+but[2]+'" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');'+safariPatch+'tinyMCE.execInstanceCommand(\'{$editor_id}\',\''+but[3]+'\', '+(but.length>4?but[4]:false)+(but.length>5?', \''+but[5]+'\'':'')+')">';}}return '<img id="{$editor_id}_'+but[0]+'" src="{$themeurl}/images/'+but[1]+'" title="'+but[2]+'" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');'+safariPatch+'tinyMCE.execInstanceCommand(\'{$editor_id}\',\''+but[3]+'\', '+(but.length>4?but[4]:false)+(but.length>5?', \''+but[5]+'\'':'')+')">';}}switch(button_name){case "formatselect":var html='<select id="{$editor_id}_formatSelect" name="{$editor_id}_formatSelect" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'FormatBlock\',false,this.options[this.selectedIndex].value);" class="mceSelectList">';var formats=tinyMCE.getParam("theme_advanced_blockformats","p,address,pre,h1,h2,h3,h4,h5,h6",true).split(',');var lookup=[['p','{$lang_theme_paragraph}'],['address','{$lang_theme_address}'],['pre','{$lang_theme_pre}'],['h1','{$lang_theme_h1}'],['h2','{$lang_theme_h2}'],['h3','{$lang_theme_h3}'],['h4','{$lang_theme_h4}'],['h5','{$lang_theme_h5}'],['h6','{$lang_theme_h6}']];for(var i=0;i<formats.length;i++){for(var x=0;x<lookup.length;x++){if(formats[i]==lookup[x][0])html+='<option value="<'+lookup[x][0]+'>">'+lookup[x][1]+'</option>';}}html+='</select>';return html;case "styleselect":return '<select id="{$editor_id}_styleSelect" onmousedown="TinyMCE_advanced_setupCSSClasses(\'{$editor_id}\');" name="{$editor_id}_styleSelect" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceSetCSSClass\',false,this.options[this.selectedIndex].value);" class="mceSelectList">{$style_select_options}</select>';case "fontselect":return '<select id="{$editor_id}_fontNameSelect" name="{$editor_id}_fontNameSelect" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'FontName\',false,this.options[this.selectedIndex].value);" class="mceSelectList">\
					<option value="">{$lang_theme_fontdefault}</option>\
					<option value="arial,helvetica,sans-serif">Arial</option>\
					<option value="times new roman,times,serif">Times New Roman</option>\
					<option value="verdana,arial,helvetica,sans-serif">Verdana</option>\
					<option value="courier new,courier,monospace">Courier</option>\
					<option value="georgia,times new roman,times,serif">Georgia</option>\
					<option value="tahoma,arial,helvetica,sans-serif">Tahoma</option>\
					</select>';case "fontsizeselect":return '<select id="{$editor_id}_fontSizeSelect" name="{$editor_id}_fontSizeSelect" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'FontSize\',false,this.options[this.selectedIndex].value);" class="mceSelectList">\
					<option value="0">-- {$lang_theme_font_size} --</option>\
					<option value="1">1 (8 pt)</option>\
					<option value="2">2 (10 pt)</option>\
					<option value="3">3 (12 pt)</option>\
					<option value="4">4 (14 pt)</option>\
					<option value="5">5 (18 pt)</option>\
					<option value="6">6 (24 pt)</option>\
					<option value="7">7 (36 pt)</option>\
					</select>';case "|":case "separator":return '<img src="{$themeurl}/images/spacer.gif" width="1" height="15" class="mceSeparatorLine">';case "spacer":return '<img src="{$themeurl}/images/spacer.gif" width="1" height="15" border="0" class="mceSeparatorLine" style="vertical-align: middle" />';case "rowseparator":return '<br />';}return "";}function TinyMCE_advanced_execCommand(editor_id,element,command,user_interface,value){switch(command){case "mceForeColor":var template=new Array();var inputColor=TinyMCE_advanced_foreColor;if(!inputColor)inputColor="#000000";template['file']='color_picker.htm';template['width']=210;template['height']=200;tinyMCE.openWindow(template,{editor_id:editor_id,command:"forecolor",input_color:inputColor});return true;case "mceBackColor":var template=new Array();var inputColor=TinyMCE_advanced_foreColor;if(!inputColor)inputColor="#000000";template['file']='color_picker.htm';template['width']=210;template['height']=200;tinyMCE.openWindow(template,{editor_id:editor_id,command:"HiliteColor",input_color:inputColor});return true;case "mceCodeEditor":var template=new Array();template['file']='source_editor.htm';template['width']=tinyMCE.getParam("theme_advanced_source_editor_width",500);template['height']=tinyMCE.getParam("theme_advanced_source_editor_height",400);tinyMCE.openWindow(template,{editor_id:editor_id,resizable:"yes",scrollbars:"no"});return true;case "mceCharMap":var template=new Array();template['file']='charmap.htm';template['width']=550;template['height']=280;tinyMCE.openWindow(template,{editor_id:editor_id});return true;case "mceInsertAnchor":var template=new Array();template['file']='anchor.htm';template['width']=320;template['height']=130;tinyMCE.openWindow(template,{editor_id:editor_id,name:TinyMCE_advanced_anchorName,action:(TinyMCE_advanced_anchorName==""?"insert":"update")});return true;}return false;}function TinyMCE_advanced_getEditorTemplate(settings){function removeFromArray(in_array,remove_array){var outArray=new Array();for(var i=0;i<in_array.length;i++){skip=false;for(var j=0;j<remove_array.length;j++){if(in_array[i]==remove_array[j])skip=true;}if(!skip)outArray[outArray.length]=in_array[i];}return outArray;}function addToArray(in_array,add_array){for(var i=0;i<add_array.length;i++)in_array[in_array.length]=add_array[i];return in_array;}var template=new Array();var deltaHeight=0;var pathHTML='{$lang_theme_path}: <span id="{$editor_id}_path">&nbsp;</span>';var layoutManager=tinyMCE.getParam("theme_advanced_layout_manager","SimpleLayout");switch(layoutManager){case "SimpleLayout":var toolbarHTML="";var toolbarLocation=tinyMCE.getParam("theme_advanced_toolbar_location","bottom");var toolbarAlign=tinyMCE.getParam("theme_advanced_toolbar_align","center");var pathLocation=tinyMCE.getParam("theme_advanced_path_location","none");var buttonNamesRow1=tinyMCE.getParam("theme_advanced_buttons1","bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect",true,',');buttonNamesRow1=removeFromArray(buttonNamesRow1,tinyMCE.getParam("theme_advanced_disable","",true,','));buttonNamesRow1=addToArray(buttonNamesRow1,tinyMCE.getParam("theme_advanced_buttons1_add","",true,','));buttonNamesRow1=addToArray(tinyMCE.getParam("theme_advanced_buttons1_add_before","",true,','),buttonNamesRow1);for(var i=0;i<buttonNamesRow1.length;i++)toolbarHTML+=tinyMCE.getControlHTML(buttonNamesRow1[i]);if(buttonNamesRow1.length>0){toolbarHTML+="<br />";deltaHeight-=23;}var buttonNamesRow2=tinyMCE.getParam("theme_advanced_buttons2","bullist,numlist,separator,outdent,indent,separator,undo,redo,separator,link,unlink,anchor,image,cleanup,help,code",true,',');buttonNamesRow2=removeFromArray(buttonNamesRow2,tinyMCE.getParam("theme_advanced_disable","",true,','));buttonNamesRow2=addToArray(buttonNamesRow2,tinyMCE.getParam("theme_advanced_buttons2_add","",true,','));buttonNamesRow2=addToArray(tinyMCE.getParam("theme_advanced_buttons2_add_before","",true,','),buttonNamesRow2);for(var i=0;i<buttonNamesRow2.length;i++)toolbarHTML+=tinyMCE.getControlHTML(buttonNamesRow2[i]);if(buttonNamesRow2.length>0){toolbarHTML+="<br />";deltaHeight-=23;}var buttonNamesRow3=tinyMCE.getParam("theme_advanced_buttons3","hr,removeformat,visualaid,separator,sub,sup,separator,charmap",true,',');buttonNamesRow3=removeFromArray(buttonNamesRow3,tinyMCE.getParam("theme_advanced_disable","",true,','));buttonNamesRow3=addToArray(buttonNamesRow3,tinyMCE.getParam("theme_advanced_buttons3_add","",true,','));buttonNamesRow3=addToArray(tinyMCE.getParam("theme_advanced_buttons3_add_before","",true,','),buttonNamesRow3);for(var i=0;i<buttonNamesRow3.length;i++)toolbarHTML+=tinyMCE.getControlHTML(buttonNamesRow3[i]);if(buttonNamesRow3.length>0)deltaHeight-=20;template['html']='<table class="mceEditor" border="0" cellpadding="0" cellspacing="0" width="{$width}" height="{$height}"><tbody>';if(toolbarLocation=="top")template['html']+='<tr><td class="mceToolbarTop" align="'+toolbarAlign+'" height="1" nowrap="nowrap">'+toolbarHTML+'</td></tr>';if(pathLocation=="top"){template['html']+='<tr><td class="mcePathTop" height="1">'+pathHTML+'</td></tr>';deltaHeight-=23;}template['html']+='<tr><td align="center">\
                    <span id="{$editor_id}"></span>\
                    </td></tr>';if(toolbarLocation=="bottom")template['html']+='<tr><td class="mceToolbarBottom" align="'+toolbarAlign+'" height="1">'+toolbarHTML+'</td></tr>';if(pathLocation=="bottom"){template['html']+='<tr><td class="mcePathBottom" height="1">'+pathHTML+'</td></tr>';deltaHeight-=23;}template['html']+='</table>';break;case "RowLayout":template['html']='<table class="mceEditor" border="0" cellpadding="0" cellspacing="0" width="{$width}" height="{$height}"><tbody>';var containers=tinyMCE.getParam("theme_advanced_containers","",true,",");var defaultContainerCSS=tinyMCE.getParam("theme_advanced_containers_default_class","container");var defaultContainerAlign=tinyMCE.getParam("theme_advanced_containers_default_align","center");for(var i=0;i<containers.length;i++){if(containers[i]=="mceEditor"){template['html']+='<tr><td align="center" class="mceEditor_border">\
                        <span id="{$editor_id}"></span>\
                        </td></tr>';}else if(containers[i]=="mceElementpath"){var pathClass="mcePath";if(i==containers.length-1)pathClass="mcePathBottom";else if(i==0)pathClass="mcePathTop";else deltaHeight-=2;template['html']+='<tr><td class="'+pathClass+'" height="1">'+pathHTML+'</td></tr>';deltaHeight-=22;}else{var curContainer=tinyMCE.getParam("theme_advanced_container_"+containers[i],"",true,',');var curContainerHTML="";var curAlign=tinyMCE.getParam("theme_advanced_container_"+containers[i]+"_align",defaultContainerAlign);var curCSS=tinyMCE.getParam("theme_advanced_container_"+containers[i]+"_class",defaultContainerCSS);for(var j=0;j<curContainer.length;j++)curContainerHTML+=tinyMCE.getControlHTML(curContainer[j]);if(curContainer.length>0){curContainerHTML+="<br />";deltaHeight-=23;}template['html']+='<tr><td class="'+curCSS+'" align="'+curAlign+'" height="1">'+curContainerHTML+'</td></tr>';}}template['html']+='</tbody></table>';break;case "BorderLayout":break;case "CustomLayout":var customLayout=tinyMCE.getParam("theme_advanced_custom_layout","");if(customLayout!=""&&eval("typeof("+customLayout+")")!="undefined")template=eval(customLayout+"(template);");break;default:alert('UNDEFINED LAYOUT MANAGER! PLEASE CHECK YOUR TINYMCE CONFIG!');break;}var styleSelectHTML='<option value="">-- {$lang_theme_style_select} --</option>';if(settings['theme_advanced_styles']){var stylesAr=settings['theme_advanced_styles'].split(';');for(var i=0;i<stylesAr.length;i++){var key,value;key=stylesAr[i].split('=')[0];value=stylesAr[i].split('=')[1];styleSelectHTML+='<option value="'+value+'">'+key+'</option>';}TinyMCE_advanced_autoImportCSSClasses=false;}template['html']=tinyMCE.replaceVar(template['html'],'style_select_options',styleSelectHTML);template['delta_width']=0;template['delta_height']=deltaHeight;return template;}function TinyMCE_advanced_getInsertLinkTemplate(){var template=new Array();template['file']='link.htm';template['width']=300;template['height']=150;template['width']+=tinyMCE.getLang('lang_insert_link_delta_width',0);template['height']+=tinyMCE.getLang('lang_insert_link_delta_height',0);return template;}function TinyMCE_advanced_getInsertImageTemplate(){var template=new Array();template['file']='image.htm?src={$src}';template['width']=340;template['height']=280;template['width']+=tinyMCE.getLang('lang_insert_image_delta_width',0);template['height']+=tinyMCE.getLang('lang_insert_image_delta_height',0);return template;}function TinyMCE_advanced_handleNodeChange(editor_id,node,undo_index,undo_levels,visual_aid,any_selection){function selectByValue(select_elm,value){if(select_elm){for(var i=0;i<select_elm.options.length;i++){if(select_elm.options[i].value==value){select_elm.selectedIndex=i;return true;}}}return false;}if(node==null)return;var pathElm=document.getElementById(editor_id+"_path");if(pathElm){var parentNode=node;var path=new Array();while(parentNode){if(parentNode.nodeName.toLowerCase()=="body")break;if(parentNode.nodeType==1)path[path.length]=parentNode;parentNode=parentNode.parentNode;}var html="";for(var i=path.length-1;i>=0;i--){var nodeName=path[i].nodeName.toLowerCase();var nodeData="";var className=tinyMCE.getVisualAidClass(tinyMCE.getAttrib(path[i],'class'),false);if(nodeName=="b")nodeName="strong";if(nodeName=="i")nodeName="em";if(tinyMCE.getAttrib(path[i],'id')!="")nodeData+="id: "+path[i].getAttribute('id')+" ";if(className!="")nodeData+="class: "+className+" ";if(tinyMCE.getAttrib(path[i],'src')!="")nodeData+="src: "+path[i].getAttribute('src')+" ";if(tinyMCE.getAttrib(path[i],'href')!="")nodeData+="href: "+path[i].getAttribute('href')+" ";if(nodeName=="img"&&tinyMCE.getAttrib(path[i],'name')=="mce_plugin_flash"){nodeName="flash";nodeData="";}if(tinyMCE.getAttrib(path[i],'name').indexOf("mce_")!=0){if(className!="")nodeName+="."+className;}if(tinyMCE.isMSIE||tinyMCE.isSafari)html+='<a title="'+nodeData+'" href="javascript:void(0);" onmousedown="tinyMCE.execInstanceCommand(\''+editor_id+'\',\'mceSelectNodeDepth\',false,\''+i+'\');return false;" class="mcePathItem">'+nodeName+'</a>';else html+='<a title="'+nodeData+'" href="javascript:tinyMCE.execInstanceCommand(\''+editor_id+'\',\'mceSelectNodeDepth\',false,\''+i+'\');" class="mcePathItem">'+nodeName+'</a>';if(i>0)html+=" &raquo; ";}pathElm.innerHTML=html+"&nbsp;";}var colorElm=tinyMCE.getParentElement(node,"font","color");if(colorElm)TinyMCE_advanced_foreColor=""+colorElm.color.toUpperCase();tinyMCE.switchClassSticky(editor_id+'_justifyleft','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_justifyright','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_justifycenter','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_justifyfull','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_bold','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_italic','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_underline','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_strikethrough','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_bullist','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_numlist','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_sub','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_sup','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_anchor','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_link','mceButtonDisabled',true);tinyMCE.switchClassSticky(editor_id+'_unlink','mceButtonDisabled',true);tinyMCE.switchClassSticky(editor_id+'_outdent','mceButtonDisabled',true);tinyMCE.switchClassSticky(editor_id+'_image','mceButtonNormal');tinyMCE.switchClassSticky(editor_id+'_hr','mceButtonNormal');var anchorName=tinyMCE.getParentElement(node,"a","name");TinyMCE_advanced_anchorName="";if(anchorName){TinyMCE_advanced_anchorName=anchorName.getAttribute("name");tinyMCE.switchClassSticky(editor_id+'_anchor','mceButtonSelected');}var anchorLink=tinyMCE.getParentElement(node,"a","href");if(anchorLink||any_selection){tinyMCE.switchClassSticky(editor_id+'_link',anchorLink?'mceButtonSelected':'mceButtonNormal',false);tinyMCE.switchClassSticky(editor_id+'_unlink',anchorLink?'mceButtonSelected':'mceButtonNormal',false);}tinyMCE.switchClassSticky(editor_id+'_visualaid',visual_aid?'mceButtonSelected':'mceButtonNormal',false);if(undo_levels!=-1){tinyMCE.switchClassSticky(editor_id+'_undo','mceButtonDisabled',true);tinyMCE.switchClassSticky(editor_id+'_redo','mceButtonDisabled',true);}if(tinyMCE.getParentElement(node,"li,blockquote"))tinyMCE.switchClassSticky(editor_id+'_outdent','mceButtonNormal',false);if(undo_index!=-1&&(undo_index<undo_levels-1&&undo_levels>0))tinyMCE.switchClassSticky(editor_id+'_redo','mceButtonNormal',false);if(undo_index!=-1&&(undo_index>0&&undo_levels>0))tinyMCE.switchClassSticky(editor_id+'_undo','mceButtonNormal',false);var selectElm=document.getElementById(editor_id+"_styleSelect");if(selectElm){TinyMCE_advanced_setupCSSClasses(editor_id);classNode=node;breakOut=false;var index=0;do{if(classNode&&classNode.className){for(var i=0;i<selectElm.options.length;i++){if(selectElm.options[i].value==classNode.className){index=i;breakOut=true;break;}}}}while(!breakOut&&classNode!=null&&(classNode=classNode.parentNode));selectElm.selectedIndex=index;}var selectElm=document.getElementById(editor_id+"_formatSelect");if(selectElm){var elm=tinyMCE.getParentElement(node,"p,div,h1,h2,h3,h4,h5,h6,pre,address");if(elm){selectByValue(selectElm,"<"+elm.nodeName.toLowerCase()+">");}else selectByValue(selectElm,"<p>");}var selectElm=document.getElementById(editor_id+"_fontNameSelect");if(selectElm){var elm=tinyMCE.getParentElement(node,"font","face");if(elm)selectByValue(selectElm,elm.getAttribute("face"));else selectByValue(selectElm,"");}var selectElm=document.getElementById(editor_id+"_fontSizeSelect");if(selectElm){var elm=tinyMCE.getParentElement(node,"font","size");if(elm&&tinyMCE.getAttrib(elm,"size")!="")selectByValue(selectElm,elm.getAttribute("size"));else selectByValue(selectElm,"0");}alignNode=node;breakOut=false;do{if(!alignNode.getAttribute||!alignNode.getAttribute('align'))continue;switch(alignNode.getAttribute('align').toLowerCase()){case "left":tinyMCE.switchClassSticky(editor_id+'_justifyleft','mceButtonSelected');breakOut=true;break;case "right":tinyMCE.switchClassSticky(editor_id+'_justifyright','mceButtonSelected');breakOut=true;break;case "middle":case "center":tinyMCE.switchClassSticky(editor_id+'_justifycenter','mceButtonSelected');breakOut=true;break;case "justify":tinyMCE.switchClassSticky(editor_id+'_justifyfull','mceButtonSelected');breakOut=true;break;}}while(!breakOut&&(alignNode=alignNode.parentNode));if(tinyMCE.isGecko&&node.nodeType==3){var inst=tinyMCE.getInstanceById(editor_id);var doc=inst.getDoc();if(doc.queryCommandState("Bold"))tinyMCE.switchClassSticky(editor_id+'_bold','mceButtonSelected');if(doc.queryCommandState("Italic"))tinyMCE.switchClassSticky(editor_id+'_italic','mceButtonSelected');if(doc.queryCommandState("Underline")&&(node.parentNode==null||node.parentNode.nodeName!="A"))tinyMCE.switchClassSticky(editor_id+'_underline','mceButtonSelected');if(doc.queryCommandState("Strikethrough"))tinyMCE.switchClassSticky(editor_id+'_strikethrough','mceButtonSelected');}do{switch(node.nodeName.toLowerCase()){case "b":case "strong":tinyMCE.switchClassSticky(editor_id+'_bold','mceButtonSelected');break;case "i":case "em":tinyMCE.switchClassSticky(editor_id+'_italic','mceButtonSelected');break;case "u":tinyMCE.switchClassSticky(editor_id+'_underline','mceButtonSelected');break;case "strike":tinyMCE.switchClassSticky(editor_id+'_strikethrough','mceButtonSelected');break;case "ul":tinyMCE.switchClassSticky(editor_id+'_bullist','mceButtonSelected');break;case "ol":tinyMCE.switchClassSticky(editor_id+'_numlist','mceButtonSelected');break;case "sub":tinyMCE.switchClassSticky(editor_id+'_sub','mceButtonSelected');break;case "sup":tinyMCE.switchClassSticky(editor_id+'_sup','mceButtonSelected');break;case "hr":tinyMCE.switchClassSticky(editor_id+'_hr','mceButtonSelected');break;case "img":if(tinyMCE.getAttrib(node,'name').indexOf('mce_')!=0)tinyMCE.switchClassSticky(editor_id+'_image','mceButtonSelected');break;}}while((node=node.parentNode));}function TinyMCE_advanced_setupCSSClasses(editor_id){if(!TinyMCE_advanced_autoImportCSSClasses)return;var selectElm=document.getElementById(editor_id+'_styleSelect');if(selectElm&&selectElm.getAttribute('cssImported')!='true'){var csses=tinyMCE.getCSSClasses(editor_id);if(csses&&selectElm){for(var i=0;i<csses.length;i++)selectElm.options[selectElm.length]=new Option(csses[i],csses[i]);}if(csses!=null&&csses.length>0)selectElm.setAttribute('cssImported','true');}}