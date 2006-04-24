<?php
/*
+ ----------------------------------------------------------------------------+
|     e107 website system
|
|     �Steve Dunstan 2001-2002
|     http://e107.org
|     jalist@e107.org
|
|     Released under the terms and conditions of the
|     GNU General Public License (http://gnu.org).
|
|     $Source: /cvs_backup/e107_0.7/e107_files/pngbehavior.php,v $
|     $Revision: 1.1 $
|     $Date: 2006-04-24 18:59:42 $
|     $Author: sweetas $
+----------------------------------------------------------------------------+
*/

$folder = dirname($_SERVER['PHP_SELF']).'/';
header("Content-type: text/x-component");

?>
<public:component lightWeight="true">
<public:attach event="onpropertychange" onevent="propertyChanged()" />
<public:attach event="onbeforeprint" onevent="beforePrint()" for="window"/>
<public:attach event="onafterprint" onevent="afterPrint()" for="window"/>
<script>

/*
 * PNG Behavior
 *
 * This script was created by Erik Arvidsson (http://webfx.eae.net/contact.html#erik)
 * for WebFX (http://webfx.eae.net)
 * Copyright 2002-2004
 *
 * For usage see license at http://webfx.eae.net/license.html
 *
 * Version: 1.02
 * Created: 2001-??-??	First working version
 * Updated: 2002-03-28	Fixed issue when starting with a non png image and
 *                      switching between non png images
 *          2003-01-06	Fixed RegExp to correctly work with IE 5.0x
 *          2004-05-09  When printing revert to original
 *
 */

var supported = /MSIE ((5\.5)|[6789])/.test(navigator.userAgent) &&
				navigator.platform == "Win32";

var realSrc;
var blankSrc = "<?php echo $folder; ?>sleight_img.gif";
var isPrinting = false;

if (supported) fixImage();

function propertyChanged() {
	if (!supported || isPrinting) return;

	var pName = event.propertyName;
	if (pName != "src") return;
	// if not set to blank
	if (!new RegExp(blankSrc).test(src))
		fixImage();
};

function fixImage() {
	// get src
	var src = element.src;

	// check for real change
	if (src == realSrc && /\.png$/i.test(src)) {
		element.src = blankSrc;
		return;
	}

	if ( ! new RegExp(blankSrc).test(src)) {
		// backup old src
		realSrc = src;
	}

	// test for png
	if (/\.png$/i.test(realSrc)) {
		// set blank image
		element.src = blankSrc;
		// set filter
		element.runtimeStyle.filter = "progid:DXImageTransform.Microsoft." +
					"AlphaImageLoader(src='" + src + "',sizingMethod='scale')";
	}
	else {
		// remove filter
		element.runtimeStyle.filter = "";
	}
}

function beforePrint() {
	isPrinting = true;
	element.src = realSrc;
	element.runtimeStyle.filter = "";
	realSrc = null;
}

function afterPrint() {
	isPrinting = false;
	fixImage();
}

</script>
</public:component>
