/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('table', 'en,ar,cs,da,de,el,es,fi,fr_ca,hu,it,ja,ko,nl,no,pl,pt,sv,tw,zh_cn,fr,de');

/**
 * Returns the HTML contents of the table control.
 */
function TinyMCE_table_getControlHTML(control_name) {
	var controls = new Array(
		['table', 'table.gif', '{$lang_table_desc}', 'mceInsertTable', true],
		['delete_col', 'table_delete_col.gif', '{$lang_table_delete_col_desc}', 'mceTableDeleteCol'],
		['delete_row', 'table_delete_row.gif', '{$lang_table_delete_row_desc}', 'mceTableDeleteRow'],
		['col_after', 'table_insert_col_after.gif', '{$lang_table_insert_col_after_desc}', 'mceTableInsertColAfter'],
		['col_before', 'table_insert_col_before.gif', '{$lang_table_insert_col_before_desc}', 'mceTableInsertColBefore'],
		['row_after', 'table_insert_row_after.gif', '{$lang_table_insert_row_after_desc}', 'mceTableInsertRowAfter'],
		['row_before', 'table_insert_row_before.gif', '{$lang_table_insert_row_before_desc}', 'mceTableInsertRowBefore'],
		['row_props', 'table_row_props.gif', '{$lang_table_row_desc}', 'mceTableRowProps', true],
		['cell_props', 'table_cell_props.gif', '{$lang_table_cell_desc}', 'mceTableCellProps', true]);

	// Render table control
	for (var i=0; i<controls.length; i++) {
		var but = controls[i];

		if (but[0] == control_name && tinyMCE.isMSIE)
			return '<img id="{$editor_id}_' + but[0] + '" src="{$pluginurl}/images/' + but[1] + '" title="' + but[2] + '" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'' + but[3] + '\', ' + (but.length > 4 ? but[4] : false) + (but.length > 5 ? ', \'' + but[5] + '\'' : '') + ')">';
		else
		if (but[0] == control_name)
			return '<img id="{$editor_id}_' + but[0] + '" src="{$themeurl}/images/spacer.gif" style="background-image:url({$pluginurl}/images/buttons.gif); background-position: ' + (0-(i*20)) + 'px 0px" title="' + but[2] + '" width="20" height="20" class="mceButtonDisabled" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'' + but[3] + '\', ' + (but.length > 4 ? but[4] : false) + (but.length > 5 ? ', \'' + but[5] + '\'' : '') + ')">';
	}

	// Special tablecontrols
	if (control_name == "tablecontrols") {
		var html = "";

		html += tinyMCE.getControlHTML("table");
		html += tinyMCE.getControlHTML("separator");
		html += tinyMCE.getControlHTML("row_props");
		html += tinyMCE.getControlHTML("cell_props");
		html += tinyMCE.getControlHTML("separator");
		html += tinyMCE.getControlHTML("row_before");
		html += tinyMCE.getControlHTML("row_after");
		html += tinyMCE.getControlHTML("delete_row");
		html += tinyMCE.getControlHTML("separator");
		html += tinyMCE.getControlHTML("col_before");
		html += tinyMCE.getControlHTML("col_after");
		html += tinyMCE.getControlHTML("delete_col");

		return html;
	}

	return "";
}

/**
 * Executes the table commands.
 */
function TinyMCE_table_execCommand(editor_id, element, command, user_interface, value) {
	function getAttrib(elm, name) {
		return elm.getAttribute(name) ? elm.getAttribute(name) : "";
	}

	var inst = tinyMCE.getInstanceById(editor_id);
	var focusElm = inst.getFocusElement();
	var tdElm = tinyMCE.getParentElement(focusElm, "td");
	var trElm = tinyMCE.getParentElement(focusElm, "tr");

	// Handle commands
	switch (command) {
		case "mceTableRowProps":
			if (trElm == null)
				return true;

			if (user_interface) {
				// Setup template
				var template = new Array();

				template['file'] = '../../plugins/table/row.htm';
				template['width'] = 340;
				template['height'] = 220;

				// Open window
				tinyMCE.openWindow(template, {editor_id : inst.editorId, align : getAttrib(trElm, 'align'), valign : getAttrib(trElm, 'valign'), height : getAttrib(trElm, 'height'), className : getAttrib(trElm, 'className')});
			} else {
				trElm.setAttribute('align', value['align']);
				trElm.setAttribute('vAlign', value['valign']);
				trElm.setAttribute('height', value['height']);
				trElm.setAttribute('class', value['className']);
				trElm.setAttribute('className', value['className']);
			}

			return true;

		case "mceTableCellProps":
			if (tdElm == null)
				return true;

			if (user_interface) {
				// Setup template
				var template = new Array();

				template['file'] = '../../plugins/table/cell.htm';
				template['width'] = 340;
				template['height'] = 220;

				// Open window
				tinyMCE.openWindow(template, {editor_id : inst.editorId, align : getAttrib(tdElm, 'align'), valign : getAttrib(tdElm, 'valign'), width : getAttrib(tdElm, 'width'), height : getAttrib(tdElm, 'height'), className : getAttrib(tdElm, 'className')});
			} else {
				tdElm.setAttribute('align', value['align']);
				tdElm.setAttribute('vAlign', value['valign']);
				tdElm.setAttribute('width', value['width']);
				tdElm.setAttribute('height', value['height']);
				tdElm.setAttribute('class', value['className']);
				tdElm.setAttribute('className', value['className']);
			}

			return true;

		case "mceInsertTable":
			if (user_interface) {
				var cols = 2, rows = 2, border = 0, cellpadding = "", cellspacing = "", align = "", width = "", height = "", action = "insert", className = "";

				tinyMCE.tableElement = tinyMCE.getParentElement(inst.getFocusElement(), "table");

				if (tinyMCE.tableElement) {
					var rowsAr = tinyMCE.tableElement.rows;
					var cols = 0;
					for (var i=0; i<rowsAr.length; i++)
						if (rowsAr[i].cells.length > cols)
							cols = rowsAr[i].cells.length;

					cols = cols;
					rows = rowsAr.length;

					border = tinyMCE.getAttrib(tinyMCE.tableElement, 'border', border);
					cellpadding = tinyMCE.getAttrib(tinyMCE.tableElement, 'cellpadding', "");
					cellspacing = tinyMCE.getAttrib(tinyMCE.tableElement, 'cellspacing', "");
					width = tinyMCE.getAttrib(tinyMCE.tableElement, 'width', width);
					height = tinyMCE.getAttrib(tinyMCE.tableElement, 'height', height);
					align = tinyMCE.getAttrib(tinyMCE.tableElement, 'align', align);
					className = tinyMCE.getAttrib(tinyMCE.tableElement, tinyMCE.isMSIE ? 'className' : "class", "");

					if (tinyMCE.isMSIE) {
						width = tinyMCE.tableElement.style.pixelWidth == 0 ? tinyMCE.tableElement.getAttribute("width") : tinyMCE.tableElement.style.pixelWidth;
						height = tinyMCE.tableElement.style.pixelHeight == 0 ? tinyMCE.tableElement.getAttribute("height") : tinyMCE.tableElement.style.pixelHeight;
					}

					action = "update";
				}

				// Setup template
				var template = new Array();

				template['file'] = '../../plugins/table/table.htm';
				template['width'] = 340;
				template['height'] = 220;

				// Language specific width and height addons
				template['width'] += tinyMCE.getLang('lang_insert_table_delta_width', 0);
				template['height'] += tinyMCE.getLang('lang_insert_table_delta_height', 0);

				// Open window
				tinyMCE.openWindow(template, {editor_id : inst.editorId, cols : cols, rows : rows, border : border, cellpadding : cellpadding, cellspacing : cellspacing, align : align, width : width, height : height, action : action, className : className});
			} else {
				var html = '';
				var cols = 2, rows = 2, border = 0, cellpadding = -1, cellspacing = -1, align, width, height, className;

				if (typeof(value) == 'object') {
					cols = value['cols'];
					rows = value['rows'];
					border = value['border'] != "" ? value['border'] : 0;
					cellpadding = value['cellpadding'] != "" ? value['cellpadding'] : -1;
					cellspacing = value['cellspacing'] != "" ? value['cellspacing'] : -1;
					align = value['align'];
					width = value['width'];
					height = value['height'];
					className = value['className'];
				}

				// Update table
				if (tinyMCE.tableElement) {
					tinyMCE.setAttrib(tinyMCE.tableElement, 'cellPadding', cellpadding);
					tinyMCE.setAttrib(tinyMCE.tableElement, 'cellSpacing', cellspacing);
					tinyMCE.setAttrib(tinyMCE.tableElement, 'border', border);
					tinyMCE.setAttrib(tinyMCE.tableElement, 'width', width);
					tinyMCE.setAttrib(tinyMCE.tableElement, 'height', height);
					tinyMCE.setAttrib(tinyMCE.tableElement, 'align', align, true);
					tinyMCE.setAttrib(tinyMCE.tableElement, tinyMCE.isMSIE ? 'className' : "class", className, true);

					if (tinyMCE.isMSIE) {
						tinyMCE.tableElement.style.pixelWidth = (width == null || width == "") ? 0 : width;
						tinyMCE.tableElement.style.pixelHeight = (height == null || height == "") ? 0 : height;
					}

					tinyMCE.handleVisualAid(tinyMCE.tableElement, false, inst.visualAid);

					// Fix for stange MSIE align bug
					tinyMCE.tableElement.outerHTML = tinyMCE.tableElement.outerHTML;

					//inst.contentWindow.dispatchEvent(createEvent("click"));

					tinyMCE.triggerNodeChange();
					return true;
				}

				// Create new table
				html += '<table border="' + border + '" ';
				var visualAidStyle = inst.visualAid ? tinyMCE.settings['visual_table_style'] : "";

				if (cellpadding != -1)
					html += 'cellpadding="' + cellpadding + '" ';

				if (cellspacing != -1)
					html += 'cellspacing="' + cellspacing + '" ';

				if (width != 0 && width != "")
					html += 'width="' + width + '" ';

				if (height != 0 && height != "")
					html += 'height="' + height + '" ';

				if (align)
					html += 'align="' + align + '" ';

				if (className)
					html += 'class="' + className + '" ';

				if (border == 0 && tinyMCE.settings['visual'])
					html += 'style="' + visualAidStyle + '" ';

				html += '>';

				for (var y=0; y<rows; y++) {
					html += "<tr>";
					for (var x=0; x<cols; x++) {
						if (border == 0 && tinyMCE.settings['visual'])
							html += '<td style="' + visualAidStyle + '">';
						else
							html += '<td>';

						html += "&nbsp;</td>";
					}
					html += "</tr>";
				}

				html += "</table>";

				inst.execCommand('mceInsertContent', false, html);
			}

			return true;

		case "mceTableInsertRowBefore":
		case "mceTableInsertRowAfter":
		case "mceTableDeleteRow":
		case "mceTableInsertColBefore":
		case "mceTableInsertColAfter":
		case "mceTableDeleteCol":
			var trElement = tinyMCE.getParentElement(inst.getFocusElement(), "tr");
			var tdElement = tinyMCE.getParentElement(inst.getFocusElement(), "td");
			var tableElement = tinyMCE.getParentElement(inst.getFocusElement(), "table");

			// No table just return (invalid command)
			if (!tableElement)
				return true;

			var doc = inst.contentWindow.document;
			var tableBorder = tableElement.getAttribute("border");
			var visualAidStyle = inst.visualAid ? tinyMCE.settings['visual_table_style'] : "";

			// Table has a tbody use that reference
			if (tableElement.firstChild && tableElement.firstChild.nodeName.toLowerCase() == "tbody")
				tableElement = tableElement.firstChild;

			if (tableElement && trElement) {
				switch (command) {
					case "mceTableInsertRowBefore":
						var numcells = trElement.cells.length;
						var rowCount = 0;
						var tmpTR = trElement;

						// Count rows
						while (tmpTR) {
							if (tmpTR.nodeName.toLowerCase() == "tr")
								rowCount++;

							tmpTR = tmpTR.previousSibling;
						}

						var r = tableElement.insertRow(rowCount == 0 ? 1 : rowCount-1);
						for (var i=0; i<numcells; i++) {
							var newTD = doc.createElement("td");
							newTD.innerHTML = "&nbsp;";

							if (tableBorder == 0)
								newTD.style.cssText = visualAidStyle;

							var c = r.appendChild(newTD);

							if (tdElement.parentNode.childNodes[i].colSpan)
								c.colSpan = tdElement.parentNode.childNodes[i].colSpan;
						}
					break;

					case "mceTableInsertRowAfter":
						var numcells = trElement.cells.length;
						var rowCount = 0;
						var tmpTR = trElement;
						var doc = inst.contentWindow.document;

						// Count rows
						while (tmpTR) {
							if (tmpTR.nodeName.toLowerCase() == "tr")
								rowCount++;

							tmpTR = tmpTR.previousSibling;
						}

						var r = tableElement.insertRow(rowCount == 0 ? 1 : rowCount);
						for (var i=0; i<numcells; i++) {
							var newTD = doc.createElement("td");
							newTD.innerHTML = "&nbsp;";

							if (tableBorder == 0)
								newTD.style.cssText = visualAidStyle;

							var c = r.appendChild(newTD);

							if (tdElement.parentNode.childNodes[i].colSpan)
								c.colSpan = tdElement.parentNode.childNodes[i].colSpan;
						}
					break;

					case "mceTableDeleteRow":
						// Remove whole table
						if (tableElement.rows.length <= 1) {
							tableElement.parentNode.removeChild(tableElement);
							tinyMCE.triggerNodeChange();
							return true;
						}

						var selElm = inst.contentWindow.document.body;
						if (trElement.previousSibling)
							selElm = trElement.previousSibling.cells[0];

						// Delete row
						trElement.parentNode.removeChild(trElement);

						if (tinyMCE.isGecko)
							inst.selectNode(selElm);
					break;

					case "mceTableInsertColBefore":
						var cellCount = tdElement.cellIndex;

						// Add columns
						for (var y=0; y<tableElement.rows.length; y++) {
							var cell = tableElement.rows[y].cells[cellCount];

							// Can't add cell after cell that doesn't exist
							if (!cell)
								break;

							var newTD = doc.createElement("td");
							newTD.innerHTML = "&nbsp;";

							if (tableBorder == 0)
								newTD.style.cssText = visualAidStyle;

							cell.parentNode.insertBefore(newTD, cell);
						}
					break;

					case "mceTableInsertColAfter":
						var cellCount = tdElement.cellIndex;

						// Add columns
						for (var y=0; y<tableElement.rows.length; y++) {
							var append = false;
							var cell = tableElement.rows[y].cells[cellCount];
							if (cellCount == tableElement.rows[y].cells.length-1)
								append = true;
							else
								cell = tableElement.rows[y].cells[cellCount+1];

							var newTD = doc.createElement("td");
							newTD.innerHTML = "&nbsp;";

							if (tableBorder == 0)
								newTD.style.cssText = visualAidStyle;

							if (append)
								cell.parentNode.appendChild(newTD);
							else
								cell.parentNode.insertBefore(newTD, cell);
						}
					break;

					case "mceTableDeleteCol":
						var index = tdElement.cellIndex;
						var selElm = inst.contentWindow.document.body;

						var numCols = 0;
						for (var y=0; y<tableElement.rows.length; y++) {
							if (tableElement.rows[y].cells.length > numCols)
								numCols = tableElement.rows[y].cells.length;
						}

						// Remove whole table
						if (numCols <= 1) {
							if (tinyMCE.isGecko)
								inst.selectNode(selElm);

							tableElement.parentNode.removeChild(tableElement);
							tinyMCE.triggerNodeChange();
							return true;
						}

						// Remove columns
						for (var y=0; y<tableElement.rows.length; y++) {
							var cell = tableElement.rows[y].cells[index];
							if (cell)
								cell.parentNode.removeChild(cell);
						}

						if (index > 0)
							selElm = tableElement.rows[0].cells[index-1];

						if (tinyMCE.isGecko)
							inst.selectNode(selElm);
					break;
				}

				tinyMCE.triggerNodeChange();
			}

		return true;
	}

	// Pass to next handler in chain
	return false;
}

function TinyMCE_table_handleNodeChange(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
	// Reset table controls
	tinyMCE.switchClassSticky(editor_id + '_table', 'mceButtonNormal');
	tinyMCE.switchClassSticky(editor_id + '_row_props', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_cell_props', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_row_before', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_row_after', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_delete_row', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_col_before', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_col_after', 'mceButtonDisabled', true);
	tinyMCE.switchClassSticky(editor_id + '_delete_col', 'mceButtonDisabled', true);

	// Within a tr element
	if (tinyMCE.getParentElement(node, "tr"))
		tinyMCE.switchClassSticky(editor_id + '_row_props', 'mceButtonSelected', false);

	// Within a td element
	if (tinyMCE.getParentElement(node, "td")) {
		tinyMCE.switchClassSticky(editor_id + '_cell_props', 'mceButtonSelected', false);
		tinyMCE.switchClassSticky(editor_id + '_row_before', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_row_after', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_delete_row', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_col_before', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_col_after', 'mceButtonNormal', false);
		tinyMCE.switchClassSticky(editor_id + '_delete_col', 'mceButtonNormal', false);
	}

	// Within table
	if (tinyMCE.getParentElement(node, "table"))
		tinyMCE.switchClassSticky(editor_id + '_table', 'mceButtonSelected');
}
