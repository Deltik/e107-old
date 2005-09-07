/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('flash', 'en,de,sv,zh_cn,cs,fa,fr_ca,fr,pl,pt_br,nl');

function TinyMCE_flash_getInfo() {
	return {
		longname : 'Flash',
		author : 'Moxiecode Systems',
		authorurl : 'http://tinymce.moxiecode.com',
		infourl : 'http://tinymce.moxiecode.com/tinymce/docs/plugin_flash.html',
		version : '2.0RC1'
	};
};

function TinyMCE_flash_initInstance(inst) {
	if (!tinyMCE.settings['flash_skip_plugin_css'])
		tinyMCE.importCSS(inst.getDoc(), tinyMCE.baseURL + "/plugins/flash/css/content.css");
}

function TinyMCE_flash_getControlHTML(control_name) {
    switch (control_name) {
        case "flash":
            return '<img id="{$editor_id}_flash" src="{$pluginurl}/images/flash.gif" title="{$lang_flash_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceFlash\');" />';
    }

    return "";
}

function TinyMCE_flash_parseAttributes(attribute_string) {
	var attributeName = "";
	var attributeValue = "";
	var withInName;
	var withInValue;
	var attributes = new Array();
	var whiteSpaceRegExp = new RegExp('^[ \n\r\t]+', 'g');

	if (attribute_string == null || attribute_string.length < 2)
		return null;

	withInName = withInValue = false;

	for (var i=0; i<attribute_string.length; i++) {
		var chr = attribute_string.charAt(i);

		if ((chr == '"' || chr == "'") && !withInValue)
			withInValue = true;
		else if ((chr == '"' || chr == "'") && withInValue) {
			withInValue = false;

			var pos = attributeName.lastIndexOf(' ');
			if (pos != -1)
				attributeName = attributeName.substring(pos+1);

			attributes[attributeName.toLowerCase()] = attributeValue.substring(1);

			attributeName = "";
			attributeValue = "";
		} else if (!whiteSpaceRegExp.test(chr) && !withInName && !withInValue)
			withInName = true;

		if (chr == '=' && withInName)
			withInName = false;

		if (withInName)
			attributeName += chr;

		if (withInValue)
			attributeValue += chr;
	}

	return attributes;
}

function TinyMCE_flash_execCommand(editor_id, element, command, user_interface, value) {
    // Handle commands
    switch (command) {
        case "mceFlash":
			var name = "", swffile = "", swfwidth = "", swfheight = "", action = "insert";
            var template = new Array();
			var inst = tinyMCE.getInstanceById(editor_id);
			var focusElm = inst.getFocusElement();

            template['file']   = '../../plugins/flash/flash.htm'; // Relative to theme
            template['width']  = 430;
            template['height'] = 185;

			if (tinyMCE.getParam("flash_external_list_url", false))
				template['height'] += 20;

			// Is selection a image
            if (focusElm != null && focusElm.nodeName.toLowerCase() == "img") {
				name = tinyMCE.getAttrib(focusElm, 'class');

				if (name.indexOf('mceItemFlash') == -1) // Not a Flash
					return true;

				// Get rest of Flash items
				swffile = tinyMCE.getAttrib(focusElm, 'alt');
				swffile = eval(tinyMCE.settings['urlconverter_callback'] + "(swffile, null, true);");
				swfwidth = tinyMCE.getAttrib(focusElm, 'width');
				swfheight = tinyMCE.getAttrib(focusElm, 'height');
				action = "update";
            }

            tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes", swffile : swffile, swfwidth : swfwidth, swfheight : swfheight, action : action});
		return true;
   }

   // Pass to next handler in chain
   return false;
}

function TinyMCE_flash_cleanup(type, content) {
	switch (type) {
		case "insert_to_editor_dom":
			var imgs = content.getElementsByTagName("img");
			for (var i=0; i<imgs.length; i++) {
				if (tinyMCE.getAttrib(imgs[i], "class") == "mceItemFlash") {
					var src = tinyMCE.getAttrib(imgs[i], "alt");

					src = tinyMCE.convertRelativeToAbsoluteURL(tinyMCE.settings['base_href'], src);

					imgs[i].setAttribute('alt', src);
				}
			}
			break;

		case "get_from_editor_dom":
			var imgs = content.getElementsByTagName("img");
			for (var i=0; i<imgs.length; i++) {
				if (tinyMCE.getAttrib(imgs[i], "class") == "mceItemFlash") {
					var src = tinyMCE.getAttrib(imgs[i], "alt");

					src = eval(tinyMCE.settings['urlconverter_callback'] + "(src, null, true);");

					imgs[i].setAttribute('alt', src);
				}
			}
			break;

		case "insert_to_editor":
			var startPos = 0;
			var embedList = new Array();

			// Fix the embed and object elements
			content = content.replace(new RegExp('<[ ]*embed','gi'),'<embed');
			content = content.replace(new RegExp('<[ ]*/embed[ ]*>','gi'),'</embed>');
			content = content.replace(new RegExp('<[ ]*object','gi'),'<object');
			content = content.replace(new RegExp('<[ ]*/object[ ]*>','gi'),'</object>');

			// Parse all embed tags
			while ((startPos = content.indexOf('<embed', startPos+1)) != -1) {
				var endPos = content.indexOf('>', startPos);
				var attribs = TinyMCE_flash_parseAttributes(content.substring(startPos + 6, endPos));
				embedList[embedList.length] = attribs;
			}

			// Parse all object tags and replace them with images from the embed data
			var index = 0;
			while ((startPos = content.indexOf('<object', startPos)) != -1) {
				if (index >= embedList.length)
					break;

				var attribs = embedList[index];

				// Find end of object
				endPos = content.indexOf('</object>', startPos);
				endPos += 9;

				// Insert image
				var contentAfter = content.substring(endPos);
				content = content.substring(0, startPos);
				content += '<img width="' + attribs["width"] + '" height="' + attribs["height"] + '"';
				content += ' src="' + (tinyMCE.getParam("theme_href") + '/images/spacer.gif') + '" title="' + attribs["src"] + '"';
				content += ' alt="' + attribs["src"] + '" class="mceItemFlash" />' + content.substring(endPos);
				content += contentAfter;
				index++;

				startPos++;
			}
			break;

		case "get_from_editor":
			// Parse all img tags and replace them with object+embed
			var startPos = -1;
			while ((startPos = content.indexOf('<img', startPos+1)) != -1) {
				var endPos = content.indexOf('/>', startPos);
				var attribs = TinyMCE_flash_parseAttributes(content.substring(startPos + 4, endPos));

				// Is not flash, skip it
				if (attribs['class'] != "mceItemFlash")
					continue;

				endPos += 2;

				var embedHTML = '';
				var wmode = tinyMCE.getParam("flash_wmode", "");
				var quality = tinyMCE.getParam("flash_quality", "high");
				var menu = tinyMCE.getParam("flash_menu", "false");

				// Insert object + embed
				embedHTML += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"';
				embedHTML += ' codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0"';
				embedHTML += ' width="' + attribs["width"] + '" height="' + attribs["height"] + '">';
				embedHTML += '<param name="movie" value="' + attribs["title"] + '" />';
				embedHTML += '<param name="quality" value="' + quality + '" />';
				embedHTML += '<param name="menu" value="' + menu + '" />';
				embedHTML += '<param name="wmode" value="' + wmode + '" />';
				embedHTML += '<embed src="' + attribs["title"] + '" wmode="' + wmode + '" quality="' + quality + '" menu="' + menu + '" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="' + attribs["width"] + '" height="' + attribs["height"] + '"></embed></object>';

				// Insert embed/object chunk
				chunkBefore = content.substring(0, startPos);
				chunkAfter = content.substring(endPos);
				content = chunkBefore + embedHTML + chunkAfter;
			}
			break;
	}

	// Pass through to next handler in chain
	return content;
}

function TinyMCE_flash_handleNodeChange(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
	tinyMCE.switchClassSticky(editor_id + '_flash', 'mceButtonNormal');

	if (node == null)
		return;

	do {
		if (node.nodeName.toLowerCase() == "img" && tinyMCE.getAttrib(node, 'class').indexOf('mceItemFlash') == 0)
			tinyMCE.switchClassSticky(editor_id + '_flash', 'mceButtonSelected');
	} while ((node = node.parentNode));

	return true;
}
