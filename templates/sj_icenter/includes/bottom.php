<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
// Body's font-size & font-family
$doc->addStyleDeclaration('body.'.$yt->template.'{font-size:'.$fontSize.'}');
if(trim($fontName)!=''){
	$doc->addStyleDeclaration('body.'.$yt->template.'{font-family:'.$fontName.',sans-serif;}');
}

// Google Font & Element use
if ($googleWebFont != "" && $googleWebFont != " " && strtolower($googleWebFont)!="none") {
	$doc->addStyleSheet('//fonts.googleapis.com/css?family='.str_replace(" ","+",$googleWebFont).'');
	$googleWebFontFamily = strpos($googleWebFont, ':')?substr($googleWebFont, 0, strpos($googleWebFont, ':')):$googleWebFont;
	if(trim($googleWebFontTargets)!="")
		$doc->addStyleDeclaration('  '.$googleWebFontTargets.'{font-family:'.$googleWebFontFamily.', serif !important}');
}
// Add css... config to <head>...</head>
$doc->addStyleDeclaration('
#bd{
	background-color:'.$yt->getParam('bgcolor').' ;
	color:'.$yt->getParam('textcolor').' ;
	font-size: '.$yt->getParam('fontSize').';
	font-family: '.$yt->getParam('fontName').';
}
body a{
	color:'.$yt->getParam('linkcolor').' ;
}
');
// Add class pattern to element wrap
?>
<script type="text/javascript">
	jQuery(document).ready(function($){
		/* Begin: add class pattern for element */
		var bodybgimage = '<?php echo $yt->getParam('bgimage');?>';
	
		
		<?php if($yt->getParam('typelayout') == 'boxed') {?>
			if(bodybgimage){
				$('#bd').addClass(bodybgimage);
			}
		<?php }; ?>
		
		
		/* End: add class pattern for element */
	});
</script>
<?php
// Include cpanel
if($showCpanel) {
	include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'cpanel.php');
	?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		miniColorsCPanel('.body-backgroud-color .color-picker', 'body', 'background-color');
		miniColorsCPanel('.link-color .color-picker', 'body a', 'color');
		miniColorsCPanel('.text-color .color-picker', 'body', 'color');
		
		<?php if($yt->getParam('typelayout') == 'boxed') {?>
			patternClick('.body-backgroud-image .pattern', 'bgimage', Array('#bd'));
		<?php } ?>
		
		var array 				= Array('bgcolor','linkcolor','textcolor','bgimage');
		var array_blue          = Array('#f4f4f4','#58bee7','#666666','pattern6');
		var array_cyan 	        = Array('#f4f4f4','#00a68e','#666666','pattern6');
		var array_green 	    = Array('#f4f4f4','#8eb748','#666666','pattern6');
		var array_orange        = Array('#f4f4f4','#ffb80e','#666666','pattern6');
		
		//1.Color Blue
		$('.theme-color.blue').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'templateColor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_blue);
			onCPApply();
		});
		
		//2.Color Cyan
		$('.theme-color.cyan').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'templateColor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_cyan);
			onCPApply();
		});
		
		//3.Color Green
		$('.theme-color.green').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'templateColor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_green);
			onCPApply();
		});
		
		//4.Color Orange
		$('.theme-color.orange').click(function(){
			$($(this).parent().find('.active')).removeClass('active'); $(this).addClass('active');
			createCookie(TMPL_NAME+'_'+'templateColor', $(this).html().toLowerCase(), 365);
			setCpanelValues(array_orange);
			onCPApply();
		});
		
		/* miniColorsCPanel */
		function miniColorsCPanel(elC, elT, selector){
			$(elC).miniColors({
				change: function(hex, rgb) {
					if(typeof(elT)!='string'){
						for(i=0;i<elT.length;i++){
							$(elT[i]).css(selector, hex);
						}
					}else{
						$(elT).css(selector, hex); 
					}
					createCookie(TMPL_NAME+'_'+($(this).attr('name').match(/^ytcpanel_(.*)$/))[1], hex, 365);
				}
			});
		}
		
		/* Begin: Set click pattern */
		function patternClick(elC, paramCookie, elT){
			$(elC).click(function(){
				oldvalue = $(this).parent().find('.active').html();
				$(elC).removeClass('active');
				$(this).addClass('active');
				value = $(this).html();
				if(elT.length > 0){
					for($i=0; $i < elT.length; $i++){
						$(elT[$i]).removeClass(oldvalue);
						$(elT[$i]).addClass(value);
					}
				}
				if(paramCookie){
					$('input[name$="ytcpanel_'+paramCookie+'"]').attr('value', value);
					createCookie(TMPL_NAME+'_'+paramCookie, value, 365);
				}
			});
		}
		function setCpanelValues(array){
			if(array['0']){
				$('.body-backgroud-color input.miniColors').attr('value', array['0']);
				$('.body-backgroud-color a.miniColors-trigger').css('background-color', array['0']);
				$('input.ytcpanel_bgcolor').attr('value', array['0']);
			}
			if(array['1']){
				$('.link-color input.miniColors').attr('value', array['1']);
				$('.link-color a.miniColors-trigger').css('background-color', array['1']);
				$('input.ytcpanel_linkcolor').attr('value', array['1']);
			}
			if(array['2']){
				$('.text-color input.miniColors').attr('value', array['2']);
				$('.text-color a.miniColors-trigger').css('background-color', array['2']);
				$('input.ytcpanel_textcolor').attr('value', array['2']);
			}
			if(array['3']){
				$('.body-backgroud-image .pattern').removeClass('active');
				$('.body-backgroud-image .pattern.'+array['3']).addClass('active');
				$('input[name$="ytcpanel_bgimage"]').attr('value', array['3']);
			}
		}
	});
	</script>
	<?php
}

//Fix Cpanel
$doc->addCustomTag('
	<script type="text/javascript">
	jQuery(window).on("load", function() {
	if (typeof jQuery != "undefined" && typeof MooTools != "undefined" ) {
		Element.implement({
			hide: function(how, mode){
			return this;
			}
		});
	}
	});
	</script>
');

// Show back to top
if( $yt->getParam('showBacktotop') ) { ?>
    <a id="yt-totop" class="backtotop" href="#"><i class="icon-angle-up"></i></a>

    <script type="text/javascript">
		
        jQuery(".backtotop").addClass("hidden-top");
			jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() === 0) {
				jQuery(".backtotop").addClass("hidden-top")
			} else {
				jQuery(".backtotop").removeClass("hidden-top")
			}
		});

		jQuery('.backtotop').click(function () {
			jQuery('body,html').animate({
					scrollTop:0
				}, 1200);
			return false;
		});
    </script>
<?php
}
?>

<script type="text/javascript">
	<?php if($yt->getParam('scrollAnimate')) {?>
		new cbpScroller( document.getElementById( 'yt_wrapper' ) );
	<?php };?>
		
</script>