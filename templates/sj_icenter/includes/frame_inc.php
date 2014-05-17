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
jimport( 'joomla.application.component.controller' );

include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'lib'.J_SEPARATOR.'template.php');
include_once (J_TEMPLATEDIR.J_SEPARATOR.'includes'.J_SEPARATOR.'lib'.J_SEPARATOR.'renderxml.php');

// Object of class YtTemplate
global $yt;
$doc = JFactory::getDocument();
$Itemid = JRequest::getInt('Itemid');

// Array param for cookie
$params_cookie =	array(
						  'fontSize',
						  'fontName',
						  'templateColor',
						  'bgcolor',
						  'linkcolor',
						  'textcolor',
						  'bgimage',
						  'header-bgimage',
						  'header-bgcolor',
						  'footer-bgcolor',
						  'footer-bgimage',
						  'templateLayout',
						  'menustyle',
						  'googleWebFont',
						  'activeNotice',
						  'typelayout'
					);
$yt = new YtTemplate($this, $params_cookie);

// Get param template
$fontName                  	= $yt->getParam('fontName');
$fontSize                   = $yt->getParam('fontSize');
$responsive					= $yt->getParam('responsive');
$layout						= $yt->getParam('templateLayout');
$templateColor				= $yt->getParam('templateColor');
$keepmenu				    = $yt->getParam('keepMenu');
$direction					= $this->direction;
$typelayout					= $yt->getParam('typelayout');
$scrollanimate				= $yt->getParam('scrollAnimate');

if($doc->direction=="rtl"){
	$direction = $doc->direction;
}else{
	$direction = $yt->getParam('direction');
}

if(isset($_COOKIE[$yt->template.'_typelayout'])){
	$typelayout = $_COOKIE[$yt->template.'_typelayout'];
}else{
	$typelayout = $yt->getParam('typelayout');
}


$menustyle					= $yt->getParam('menustyle');
$overrideLayouts			= trim($yt->getParam('overrideLayouts'));
$setGeneratorTag			= $yt->getParam('setGeneratorTag');
$googleWebFont 				= $yt->getParam('googleWebFont');
$googleWebFontTargets		= $yt->getParam('googleWebFontTargets');
$showCpanel					= $yt->getParam('showCpanel');

$compileLess				= $yt->getParam('compileLess');

// Include Class YtRenderXML
if($layout=='-1' || $layout=='') die(JTEXT::_("SELECT_LAYOUT_NOW"));

// Parse layout
$boolOverride = false;
if(trim($overrideLayouts)!=''){
	$overrideLayouts = explode(' ', $overrideLayouts);
	if( count($overrideLayouts)>=1 ) {
		for($i=0; $i<count($overrideLayouts); $i++){
			$layoutItemArray[] = explode('=', $overrideLayouts[$i]);
		}
		if( !empty($layoutItemArray) ){
			foreach($layoutItemArray as $item){
				if($Itemid == $item[0]){
					$boolOverride = true; $layoutItem = trim($item[1]);
				}
			}
		}
	}
}

if($boolOverride == true && isset($layoutItem) && $layoutItem != ''){
	$yt_render = new YtRenderXML($layoutItem.'.xml');
}else{
	$yt_render = new YtRenderXML($layout.'.xml');
}

// Set GeneratorTag
$this->setGenerator($setGeneratorTag);


/*** CSS ***
************/
$yt->ytStyleSheet('templates/system/css/general.css');
$yt->ytStyleSheet('templates/system/css/system.css');
$yt->ytStyleSheet('asset/bootstrap/css/bootstrap.css');

if(!defined('FONT_AWESOME')){
	$yt->ytStyleSheet('asset/fonts/awesome/css/font-awesome.css');
	define('FONT_AWESOME', 1);
}

$yt->ytStyleSheet('css/template.css');
$yt->ytStyleSheet('css/pattern.css');
$yt->ytStyleSheet('css/your_css.css');



if($scrollanimate) $yt->ytStyleSheet('css/scroll-animate.css');
if($showCpanel) $yt->ytStyleSheet('asset/minicolors/jquery.miniColors.css');
if($responsive) $yt->ytStyleSheet('asset/bootstrap/css/responsive.css');

// Include css in layout(.xml)
if(isset($yt_render->arr_TH['stylesheet'])){
	foreach($yt_render->arr_TH['stylesheet'] as $tagStyle){
		$yt->ytStyleSheet('css/'.$tagStyle);
	}
}



// Check if com_easyblog is installed
$db = JFactory::getDbo();
$prefix = $db->getPrefix();
$tables = $db->getTableList();
//var_dump($prefix, $tables);die;
if ( in_array($prefix . 'easyblog_category', $tables)) {
	if ( JComponentHelper::isEnabled( 'com_easyblog', true) ) {
	$yt->ytStyleSheet('css/easyblog.css');
    return;
}
}




// Include css with IE8, IE9
if($yt->ieversion()==8) $yt->ytStyleSheet('css/template-ie8.css');
if($yt->ieversion()==9) $yt->ytStyleSheet('css/template-ie9.css');

// Include css RTL
if($direction == 'rtl'){ 
	$yt->ytStyleSheet('css/template-rtl.css');
}
// Enable & disable responsive
if($responsive) $yt->ytStyleSheet('css/responsive.css');

/*** JS ***
***********/
// Javascript of joomla core
if(J_VERSION <= 2){
	JHTML::_('behavior.framework');
}
// Include jQuery & bootstrap's javascript
if(J_VERSION >= 3){
	JHtml::_('bootstrap.framework');
}elseif(J_VERSION <= 2){
	if (!defined('SMART_JQUERY')){
		define('SMART_JQUERY', 1);
		$doc->addScript($yt->templateurl().'js/jquery.min.js');
		$doc->addScript($yt->templateurl().'js/jquery-noconflict.js');
	}
	$doc->addScript($yt->templateurl().'asset/bootstrap/js/bootstrap.min.js');
}
if(strtolower($compileLess)=='client'){
	$doc->addScript($yt->templateurl().'js/less.min.js');
}

if($keepmenu){ 
	$doc->addScript($yt->templateurl().'js/keepmenu.js');
}

if($scrollanimate){ 
	$doc->addScript($yt->templateurl().'js/classie.js');
	$doc->addScript($yt->templateurl().'js/cbpScroller.js');
	$doc->addScript($yt->templateurl().'js/modernizr.custom.js');
}

$doc->addScript($yt->templateurl().'js/yt-script.js');
if($showCpanel) {
	$doc->addScript($yt->templateurl().'js/ytcpanel.js');
	$doc->addScript($yt->templateurl().'asset/minicolors/jquery.miniColors.min.js');
}
// Include js in layout(.xml)
if(isset($yt_render->arr_TH['script'])){
	foreach($yt_render->arr_TH['script'] as $tagScript){
		$doc->addScript($yt->templateurl().'js/'.$tagScript);
	}
}

// Responsive window resize 
if($responsive) $doc->addScript($yt->templateurl().'js/yt-extend.js');

// Menu for mobile
$doc->addCustomTag('
<script type="text/javascript">
	var TMPL_NAME = "'.$yt->template.'";
	var TMPL_COOKIE = '.json_encode($params_cookie).';

	function MobileRedirectUrl(){
	  window.location.href = document.getElementById("yt-mobilemenu").value;
	}
</script>
');

?>