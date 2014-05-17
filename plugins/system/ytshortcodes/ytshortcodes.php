<?php
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2013 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') or die('Restricted access');

// Import Joomla core library
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.plugin.plugin');
jimport('joomla.version');

// Require shortcodes functions
require_once dirname(__FILE__) . "/includes/shortcodes-func.php";
// Include shortcode prepa
include_once dirname(__FILE__) . "/includes/shortcodes-prepa.php";
// Include googlemap
require_once dirname(__FILE__) . "/includes/libs/googlemap/googleMaps.lib.php";

class plgSystemYtshortcodes extends JPlugin{
	var $document = NULL;
	var $baseurl  = NULL;

	public function __construct(&$subject, $config){
		parent::__construct($subject, $config);
		$this->document = JFactory::getDocument();
		$this->baseurl = str_replace("/administrator", "", JURI::base());
		
		
	}
	// Function on after render
	public function onAfterRender(){
		$app 	 = JFactory::getApplication();
		// Only enable shortcodes in fontend & work with buffer of page
		if($app->isSite()){

		}
		
		// Add shortcodes button into editor(frontend & backend)
		$this->addBtnShortCodes();
	}

	// Enable shortcodes in Articles content
	public function onContentPrepare($context, &$article, &$params, $page=0){

		$param = new stdClass;
        $param->api_key = $this->params->get('google_map_api_key');
        $param->width   = $this->params->get('google_map_width', '400');
        $param->height  = $this->params->get('google_map_height', '400');
        $param->zoom    = $this->params->get('google_map_zoom', '15');
        
        $is_mod = 1;

        $plugin = new Plugin_googleMaps($article, $param, $is_mod);
		$article->text = parse_shortcode($article->text);
		return true;
	}
	

	// Add media
	public function onBeforeRender()
	{
		$app = JFactory::getApplication();
		//get language and direction
		$doc = JFactory::getDocument();
		$this->language = $doc->language;
		$this->direction = $doc->direction;
		
		if( $app->isSite()){
			
			// include Bootstrap
			if($this->params->get('show_sjbootstrap', 0)=='1'){
				$this->ytStyleSheet('plugins/system/ytshortcodes/assets/css/bootstrap/bootstrap.css');
			}
			if($this->params->get('show_sjawesome', 1)==1){
				$this->ytStyleSheet('plugins/system/ytshortcodes/assets/css/awesome/font-awesome.css');
				$this->ytStyleSheet('plugins/system/ytshortcodes/assets/css/awesome/glyphicon.css');
			}
			$this->ytStyleSheet("plugins/system/ytshortcodes/assets/css/shortcodes.css");
			
		}else{
			$this->document->addStyleSheet($this->baseurl."plugins/system/ytshortcodes/assets/css/shortcodes-backend.css");
		}

		$temp_direct = 'ltr';
		if($app->isSite() && defined('YT_FRAMEWORK')){
			if(method_exists('plgSystemYt', 'nameOfSJTemplate') && isset($_COOKIE[plgSystemYt::nameOfSJTemplate().'_direction'])){
				$temp_direct = $_COOKIE[plgSystemYt::nameOfSJTemplate().'_direction'];
			}else{
				$temp_direct = $app->getTemplate(true)->params->get('direction');
			}
		}
		
		if ($this->direction == 'rtl' || $temp_direct == 'rtl'){
			if($app->isSite()){
				$this->ytStyleSheet("plugins/system/ytshortcodes/assets/css/shortcodes-rtl.css");
			}
		}
		
		// include Jquery Joomla25
		$version = new JVersion();
		if($this->params->get('show_sjjquery', 0)==1 && $version->RELEASE=='2.5' ){
			$this->document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/jquery.min.js");
			$this->document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/jquery-noconflict.js");
		}
		
		// include Jquery
		if($this->params->get('show_sjbootstrap', 0)==1 && $app->isSite()){
			$this->document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/bootstrap.min.js");
		}
		if($app->isSite()){
			$this->document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/jquery.prettyPhoto.js");
			if($this->params->get('show_sjprettify', 1)==1){
				$this->document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/prettify.js");
			}
			$this->document->addScript($this->baseurl . "plugins/system/ytshortcodes/assets/js/shortcodes.js");
		}
		
	}
	
	public function ytStyleSheet($url){
		$app = JFactory::getApplication();
		$doc = JFactory::getDocument();
		$lessurl = str_replace('.css', '.less', str_replace('/css/', '/less/', $url));
		if(($app->getTemplate(true)->params->get('developing', 0)==1 || JRequest::getVar('less2css')=='all') && file_exists($lessurl)){
			YTLess::addStyleSheet($lessurl);
		}elseif(file_exists($url)){
			$doc->addStyleSheet($url);
		}else{
			die($url.' not exists');
		}
	}
	// Function add shortcodes button into editor
	public function addBtnShortCodes()
	{
		$page   = JResponse::GetBody();
		$button = $this->listShortCodes();
		$stext  = '<script language="javascript" type="text/javascript">
						function jSelectShortcode(text) {
							jQuery("#yt_shorcodes").removeClass("open");
							text = text.replace(/\'/g, \'"\');
							if(document.getElementById(\'jform_articletext\') != null) {
								jInsertEditorText(text, \'jform_articletext\');
							}
							if(document.getElementById(\'text\') != null) {
								jInsertEditorText(text, \'text\');
							}
							if(document.getElementById(\'jform_description\') != null) {
								jInsertEditorText(text, \'jform_description\');
							}
							if(document.getElementById(\'jform_content\') != null) {
								jInsertEditorText(text, \'jform_content\');
							}
							if(document.getElementById(\'product_desc\') != null) {
								jInsertEditorText(text, \'product_desc\');
							}
							SqueezeBox.close();
						}
				   </script>';
		$page = str_replace('<div id="editor-xtd-buttons">', '<div id="editor-xtd-buttons">' . $button, $page);
		$page = str_replace('<div id="editor-xtd-buttons" class="btn-toolbar pull-left">', '<div id="editor-xtd-buttons" class="btn-toolbar pull-left">' . $button, $page);
		$page = str_replace('</body>', $stext . '</body>', $page);
		JResponse::SetBody($page);
	}
	// Build shorcodes list
	public function listShortCodes()
	{
		$shortcoders = array(
		'accordion' => array(
			'name'		=> "Accordion",
			'desc'		=> "Accordion",
			'syntax'	=> "[accordion]<br/>[acc_item title=\'ITEM_TITLE\']ADD_CONTENT_HERE[/acc_item]<br/>[acc_item title=\'ITEM_TITLE\']ADD_CONTENT_HERE[/acc_item]<br/>[acc_item title=\'ITEM_TITLE\']ADD_CONTENT_HERE[/acc_item]<br/>[/accordion]<br/>",
			'image'		=> "accordion.png"
		),
		'gallery' => array(
			'name'		=> "Gallery",
			'desc'		=> "Gallery",
			'syntax'	=> "[gallery title=\'GALLERY_TITLE\' width=\'IMAGE_THUMB_WIDTH\' height=\'IMAGE_THUMB_HEIGHT\' columns=\'3\']<br/>[gallery_item title=\'IMAGE_TITLE\' src=\'IMAGE_SRC\' video_addr=\'VIDEO_ADDRESS\']IMAGE_DESCRIPTION[/gallery_item]<br/>[gallery_item title=\'IMAGE_TITLE\' src=\'IMAGE_SRC\' video_addr=\'VIDEO_ADDRESS\']IMAGE_DESCRIPTION[/gallery_item]<br/>[gallery_item title=\'IMAGE_TITLE\' src=\'IMAGE_SRC\' video_addr=\'VIDEO_ADDRESS\']IMAGE_DESCRIPTION[/gallery_item]<br/>[/gallery]",
			'image'		=> "gallery.png"
		),
		'icon' => array(
			'name'		=> "Retina Icons",
			'desc'		=> "Retina Icons",
			'syntax'	=> "[icon type=\'fa|gly\' name=\'twitter\' size=\'FONT_SIZE\' color=\'COLOR\' align=\'left|right|none\'] ",
			'image'		=> "icon.png"
		),
		
		'spacer' => array(
			'name'		=> "Add Space",
			'desc'		=> "Add Space",
			'syntax'	=> "[space height=\'HEIGHT\']<br/>",
			'image'		=> "space.png"
		),
		'googlefont' => array(
			'name'		=> "Google Font",
			'desc'		=> "Google Font",
			'syntax'	=> "[googlefont font=\'FONT_NAME\' size=\'FONT_SIZE\' color=\'COLOR\' font_weight=\'normal|bold\' align=\'left|right|none\' margin=\'1em 0 1em 0\'] ADD_CONTENT_HERE[/googlefont]<br/>",
			'image'		=> "space.png"
		),
		'social' => array(
			'name'		=> "Social Icons",
			'desc'		=> "Social Icons",
			'syntax'	=> "[social type=\'facebook\' title=\'ADD_TITLE_HERE\' color=\'yes|no\']PLACE_LINK_HERE[/social]",
			'image'		=> "social.png"
		),
		'blockquote' => array(
			'name'		=> "Blockquote",
			'desc'		=> "Blockquote",
			'syntax'	=> "[quote width=\'auto\' align=\'left|right|none\' border=\'COLOR\' color=\'COLOR\' title=\'SOMEONE_FAMOUS_TITLE\']ADD_CONTENT_HERE[/quote]",
			'image'		=> "blockquote.png"
		),
		'googlemap' => array(
			'name'		=> "Google Map",
			'desc'		=> "Google Map",
			'syntax'	=> "[googleMaps align=\'left|right|none\' addr=\'ADDRESS OR Latitude/Longitude\' label=\'GOOLE_MAP_LABEL\' width=100% height=400]<br/>",
			'image'		=> "google_map.png"
		),
			'highlighter' => array(
			'name'		=> "Syntax Highlighting",
			'desc'		=> "Syntax highlighting of code snippets in a web page",
			'syntax'	=> "[highlighter label=\'Example\' linenums=\'yes|no\' startnums=\'1\']YOUR_CODE_HERE[/highlighter]<br/>",
			'image'		=> "highlighter.png"
		),
		
		'buttons' => array(
			'name'		=> "Buttons",
			'desc'		=> "Buttons",
			'syntax'	=> "[button type=\'info\' target=\'_self\' link=\'#\' icon=\'info-sign\']ADD_BUTTON_CONTENT[/button]",
			'image'		=> "buttons.png"
		),
		'list' => array(
			'name'		=> "List Style",
			'desc'		=> "List Style",
			'syntax'	=> "[list type=\'disc\']<br/>[list_item]ADD_LIST_CONTENT[/list_item] <br/>[list_item]ADD_LIST_CONTENT[/list_item] <br/>[/list]",
			'image'		=> "list.png"
		),
		'testimonial' => array(
			'name'		=> "Testimonial",
			'desc'		=> "Testimonial",
			'syntax'	=> "[testimonial author=\'TESTIMONIAL_AUTHOR\' position=\'AUTHOR_POSITION\' avatar=\'URL_IMAGES\']ADD_TESTIMONIAL_HERE[/testimonial]",
			'image'		=> "testimonial.png"
		),
		'clear' => array(
			'name'		=> "Clear Floated ",
			'desc'		=> "Clear Floated ",
			'syntax'	=> "[clear]<br/>",
			'image'		=> "space.png"
		),
		'br' => array(
			'name'		=> "Line Break",
			'desc'		=> "Line Break",
			'syntax'	=> "[br]<br/>",
			'image'		=> "space.png"
		),
		'tabs' => array(
			'name'		=> "Togglable Tabs",
			'desc'		=> "Togglable Tabs",
			'syntax'	=> "[tabs]<br/>[tab_item title=\'ITEM_TITLE\']ADD_CONTENT_HERE[/tab_item]<br/>[tab_item title=\'ITEM_TITLE\']ADD_CONTENT_HERE[/tab_item]<br/>[tab_item title=\'ITEM_TITLE\']ADD_CONTENT_HERE[/tab_item]<br/>[/tabs]",
			'image'		=> "tabs.png"
		),
		'column' => array(
			'name'		=> "Columns",
			'desc'		=> "Columns",
			'syntax'	=> "[columns background=\'#ccc\' ]<br/>[column_item col=\'4\']ADD_CONTENT_HERE[/column_item]<br/>[column_item col=\'4\']ADD_CONTENT_HERE[/column_item]<br/>[column_item col=\'4\']ADD_CONTENT_HERE[/column_item]<br/>[/columns]",
			'image'		=> "column.png"
		),
		'lightbox' => array(
			'name'		=> "Lightbox",
			'desc'		=> "Lightbox",
			'syntax'	=> "[lightbox src=\'IMAGE_SRC\' width=\'IMAGE_WIDTH\' height=\'IMAGE_HEIGHT\' lightbox=\'on|off\' border=\'yes|no\' title=\'IMAGE_TITLE\' align=\'left|right|none\']",
			'image'		=> "lightbox.png"
		),
		
		'toggle' => array(
			'name'		=> "Toggle Boxes",
			'desc'		=> "Toggle Boxes",
			'syntax'	=> "[toggle_box]<br/>[toggle_item icon=\'user\' title=\'ITEM_TITLE\']ADD_CONTENT_HERE[/toggle_item]<br/>[toggle_item icon=\'heart\' title=\'ITEM_TITLE\' active=\'yes|no\']ADD_CONTENT_HERE[/toggle_item]<br/>[/toggle_box]",
			'image'		=> "toggle.png"
		),

		'dropcap' => array(
			'name'		=> "Dropcap",
			'desc'		=> "Dropcap",
			'syntax'	=> "[dropcap type=\'square\' color=\'COLOR\' background=\'COLOR\' ]ADD_CONTENT_HERE[/dropcap]",
			'image'		=> "dropcap.png"
		),
		
		'message' => array(
			'name'		=> "Message Boxe",
			'desc'		=> "Message Boxe",
			'syntax'	=> "[message_box title=\'MESSAGE_TITLE\' type=\'error\' close=\'yes|no\']ADD_CONTENT_HERE[/message_box]",
			'image'		=> "message.png"
		),
		
		'vimeo' => array(
			'name'		=> "Vimeo",
			'desc'		=> "Vimeo",
			'syntax'	=> "[vimeo height=\'HEIGHT\' width=\'WIDTH\' align=\'left|right|none\']PLACE_LINK_HERE[/vimeo]",
			'image'		=> "vimeo.png"
		),
		'divider' => array(
			'name'		=> "Divider",
			'desc'		=> "Divider",
			'syntax'	=> "[divider margin=\'0 2em 0 2em\']<br/>",
			'image'		=> "divider.png"
		),
		'pricing' => array(
		'name'		=> "Pricing Tables",
		'desc'		=> "Pricing Tables",
		'syntax'	=> "[pricing columns=\'3\']<br/>[plan title=\'PRICING_TITLE\' button_link=\'http://\' button_label=\'PRICING_BUTTON_LABEL\' price=\'$0\'  per=\'month\']TEXT_OF_PLAN[/plan]<br/>[plan title=\'PRICING_TITLE\' button_link=\'http://\' button_label=\'PRICING_BUTTON_LABEL\' price=\'$30\' featured=\'true\' per=\'month\']TEXT_OF_PLAN[/plan]<br/>[plan title=\'PRICING_TITLE\' button_link=\'http://\' button_label=\'PRICING_BUTTON_LABEL\' price=\'$70\' per=\'month\']TEXT_OF_PLAN[/plan]<br/>[/pricing]<br/>",
		'image'		=> "pricing.png"
		),
		'youtube' => array(
			'name'		=> "Youtube",
			'desc'		=> "Youtube",
			'syntax'	=> "[youtube height=\'HEIGHT\' width=\'WIDTH\' align=\'left|right|none\']PLACE_LINK_HERE[/youtube]<br/>",
			'image'		=> "youtube.png"
		),
		'slideshow' => array(
			'name'		=> "Slideshow",
			'desc'		=> "Slideshow",
			'syntax'	=> "[slideshow width=\'SLIDESHOW_WIDTH\' height=\'SLIDESHOW_HEIGHT\' align=\'left|right|none\' caption=\'yes|no\']<br/>[slider_item title=\'CAPTION_TITLE\' src=\'IMAGE_SRC\' ]CAPTION_DESCRIPTION [/slider_item]<br/>[slider_item title=\'CAPTION_TITLE\' src=\'IMAGE_SRC\' ]CAPTION_DESCRIPTION [/slider_item]<br/>[slider_item title=\'CAPTION_TITLE\' src=\'IMAGE_SRC\' ]CAPTION_DESCRIPTION [/slider_item]<br/>[/slideshow]",
			'image'		=> "Slideshow.png"
		),
		'tooltip' => array(
			'name'		=> "Tooltip",
			'desc'		=> "Tooltip",
			'syntax'	=> "[tooltip link=\'#\' title=\'TITLE\' position=\'top|right|bottom|left\']ADD_CONTENT_HERE[/tooltip]<br/>",
			'image'		=> "tooltip.png"
		),
		'modal' => array(
			'name'		=> "Modal",
			'desc'		=> "Modal",
			'syntax'	=> "[modal title=\'TITLE\' header=\'TITLE_HEADER\']ADD_CONTENT_HERE[/modal]<br/>",
			'image'		=> "modal.png"
		)
		
	);

		$text  = '';
		$linkShortcode='';
		
		if(count($shortcoders)){
			$text .='<div id="yt_shorcodes">';
			$text .='<span class="button-shortcodes btn-text">Yt Shortcodes</span>
			<span class="button-shortcodes btn-act"><span class="arrow"></span></span>';
			$text .='<ul>';
			
			foreach($shortcoders as $key => $shortcoder) {
				$text .= '<li class="item item-'.$key.'">';
				$text .= '<a class="pointer" href="javascript: void(0);" onclick="jSelectShortcode(\'' . $shortcoder['syntax'] . '\')" title="' . $shortcoder['desc'] . '">';
				$text .= $shortcoder['name'];
				$text .= '</a>';
				$text .= '</li>';
			}
			
		
			$linkShortcode ="http://demo.smartaddons.com/extensions/yt-shortcode/index.php";
			$text .='<li class="allShortcode"><a href="'.$linkShortcode.'"  target="_blank">  Click here to view example YT Shortcodes</a></li>';
			$text .='</ul>';
			$text .='</div>';
			$text .='
			<script type="text/javascript">
				jQuery(document).ready(function($) {
				  $("#yt_shorcodes .btn-act").click(function(){
				  	if( $(this).parent().attr("class")==="open" ){
				  		$(this).parent().removeClass("open");
				  	}else {
				  		$(this).parent().addClass("open");
				  	}
				  });
				})
			</script>
			';
		}
		return $text;
	}
}