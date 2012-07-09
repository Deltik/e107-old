$(document).ready(function()
{
	
	$(".e-dialog-save").click(function () { // FIXME TODO missing caret , text selection overwrite etc. 
		
		//alert('hello');
		var newval = $('#bbcode_holder').val();
	//	alert(newval);
		var target 	= $(this).attr('data-target');
		
		if(!target){return true; }
		//('#' + target, window.top.document).insertAtCaret(newVal);
		
	//	$('#' + target, window.parent.document).append(newval);	//FIXME caret!!
	//	var t = $('#' + target, window.parent.document).text();
		
		$('#' + target, window.top.document).attr('value',newval);	// set new value
		// inserttext(newval,target);
		// alert(newval);
	});
	
	
	$(".e-media-attribute").keyup(function () {  
		
		eMediaAttribute();	
	});
	
	function eMediaAttribute(e)
	{		
		var style 		= '';
		var bb 			= '';
		
		var target 		= $(e).attr('data-target');
	//	var path		= $(e).attr('data-path');
	//	var preview 	= $(e).attr('data-preview');
	//	var src			= $(e).attr('data-src');
		
		var src 			= $('#src').attr('value'); // working old
		var path 			= $('#path').attr('value'); // working old
		var preview 		= $('#preview').attr('value'); // working old
		
		var width 			= $('#width').val();	
		var height			= $('#height').val();			
		var margin_top 		= $('#margin-top').val();				
		var margin_bottom 	= $('#margin-bottom').val();	
		var margin_right 	= $('#margin-right').val();	
		var margin_left 	= $('#margin-left').val();	
						
		if(width !='')
		{				
			style  = style + 'width:' + width + 'px;';	
		}

		if(height !='')
		{				
			style  = style + 'height:' + height + 'px;';	
		}				
					
		if(margin_right !='')
		{				
			style  = style + 'margin-right:' + margin_right + 'px;';	
		}
		
		if(margin_left !='')
		{				
			style  = style + 'margin-left:' + margin_left + 'px;';	
		}
		
		if(margin_top !='')
		{				
			style  = style + 'margin-top:' + margin_top + 'px;';	
		}
		
		if(margin_bottom !='')
		{				
			style  = style + 'margin-bottom:' + margin_bottom + 'px;';	
		}
		
		bb = '[img';
		
		if(style !='')
		{
			bb = bb + ' style='+style;			
		}
		
		bb = bb + ']';
		bb = bb + path;
		bb = bb + '[/img]';
				
		$('#bbcode_holder').val(bb);
		//	document.getElementById('bbcode_holder').value = bb;
				
			//	var html = '<img style=\"' + style + '\" src=\"'+ src +'\" />'; 
		var html = '<img style=\"' + style + '\" src=\"'+ src +'\" alt=\"\" width=\"' + width + '\" height=\"' + height + '\"/>'; 

		$('#html_holder').val(html);
	}
	
		
		
		
		
				// $(".e-media-select").click(function () {  
		$(".e-media-select").live("click", function(){
  	
    	
    		//	console.log(this);
    			
		
				
				var target 		= $(this).attr('data-target');
				var path		= $(this).attr('data-path');
				var preview 	= $(this).attr('data-preview');
				var src			= $(this).attr('data-src');
				var bbcode		= $(this).attr('data-bbcode');
				
				// Create new offscreen image to test
			//	var theImage = new Image();
			//	theImage.src = $(this).attr('data-src');
				
				// Get accurate measurements from that.
			//	var imageWidth = theImage.width;
			//	var imageHeight = theImage.height;
			
			//	$('#width').val(theImage.width);
			//	$('#height').val(theImage.height);		
				// alert('width='+imageWidth);
				

						
				$(this).addClass("media-select-active");
				$(this).closest("img").addClass("active");			
				
				if(bbcode) // not needed for Tinymce
				{
			//		$('input#' + target, window.top.document).attr('value',path);	// set new value
				//	bbpath = '['+bbcode+']'+ path + '[/' + bbcode + ']';
			//		$('textarea#' + target, window.top.document).attr('value',bbpath);	
				}
				
				$('#src').attr('value',src); // working old
				$('#preview').attr('src',preview);	// working old
				
				$('#path').attr('value',path); // working old
				$('#src').attr('src',src);	// working old
				
				$('img#' + target + "_prev", window.top.document).attr('src',preview); // set new value
				$('div#' + target + "_prev", window.top.document).html(preview); // set new value
				$('span#' + target + "_prev", window.top.document).html(preview); // set new value
								
			
				
				
				eMediaAttribute(this);	
			//	$(this).parent('#src').attr('value',preview); // set new value
			//	$(this).parent('#preview').attr('src',preview);	 // set new value

			return true;
				
	}); 			
});	