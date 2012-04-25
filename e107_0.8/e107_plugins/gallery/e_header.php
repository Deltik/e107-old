<?php
/*
* Copyright (c) e107 Inc e107.org, Licensed under GNU GPL (http://www.gnu.org/licenses/gpl.txt)
* $Id: e_shortcode.php 12438 2011-12-05 15:12:56Z secretr $
*
* Featurebox shortcode batch class - shortcodes available site-wide. ie. equivalent to multiple .sc files.
*/
if (!defined('e107_INIT')) { exit; }

e107::getJS()->headerFile("http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js",1);
e107::getJs()->headerPlugin('gallery', 'jslib/lightbox/js/lightbox.js');
e107::getJs()->pluginCSS('gallery', 'jslib/lightbox/css/lightbox.css');

e107::getJS()->headerFile("https://ajax.googleapis.com/ajax/libs/prototype/1.7.0.0/prototype.js",1);
e107::getJS()->headerFile("https://ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js?load=effects",1);
e107::getJs()->headerPlugin('gallery', 'jslib/carousel.js');
e107::getJs()->pluginCSS('gallery', 'gallery_style.css');

$gp = e107::getPlugPref('gallery');

e107::getJs()->footerInline("		
	new Carousel('gallery-slideshow-wrapper', $$('#gallery-slideshow-content .slide'), $$('a.carousel-control', 'a.gallery-slide-jumper' ),
	{
		duration:           ".varset($gp['slideshow_duration'],1).",
        auto:               ".varset($gp['slideshow_auto'],0).",
        frequency:          ".varset($gp['slideshow_freq'],3).",
		circular: 			".varset($gp['slideshow_circular'],1).",
        wheel:              true,
        visibleSlides: 		1,
        effect:             '".varset($gp['slideshow_effect'],'scroll')."',
        transition:         '".varset($gp['slideshow_transition'],'sinoidal')."',
        jumperClassName:    'gallery-slide-jumper',
        selectedClassName:	'gallery-slide-jumper-selected'
   
		   
	});
	
	var aj = $$('.gallery-slide-jumper')[0];
	if (!aj.hasClassName('gallery-slide-jumper-selected'))  aj.toggleClassName('gallery-slide-jumper-selected');
");
/*

				jumperClassName:    'scroller-jumper',
				selectedClassName:  'scroller-selected',
				var aj = $$('.donwload-jumper')[0];
				if (!aj.hasClassName('scroller-selected'))  aj.toggleClassName('scroller-selected');
			*/
	
unset($gp);


?>