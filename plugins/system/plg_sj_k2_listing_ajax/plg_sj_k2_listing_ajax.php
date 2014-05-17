<?php
/**
 * @package Sj K2 Listing Ajax
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

class plgSystemPlg_Sj_K2_Listing_Ajax extends JPlugin {
	
	protected	$_total			= 0;
	protected	$_start 		= 0;
	protected	$_limit 		= 0; 
	protected	$tag_id 		= null;
	protected	$app 			= array();
	protected   $_params 		= null;
	
	function __construct ( $subject, $config ) {
		parent::__construct( $subject, $config );
		$this->loadLanguage();
		$this->app = JFactory::getApplication();
		$this->_params = $this->params;
		$this->_limit = $this->_params->get('limit');
	}

	function onAfterInitialise () {
		if ($this->app ->isAdmin()) return;
	}	
	
	function onAfterRouter () {
		if ($this->app ->isAdmin()) return;
		JPlugin::loadLanguage('com_k2', JPATH_SITE);
	}
	
	function onAfterDispatch(){
		if ($this->app ->isAdmin()) return;
		if(!class_exists('K2HelperRoute')){
			require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
		}
		if(!class_exists('K2HelperUtilities')){
			require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'utilities.php');
		}
		$k2params = K2HelperUtilities::getParams('com_k2');
		$limit = $k2params->get('num_leading_items') + $k2params->get('num_primary_items') + $k2params->get('num_secondary_items') + $k2params->get('num_links');
		$this->_start =  $limit;
		$this->tag_id = '#k2Container';
		$ItemList = K2Model::getInstance('Itemlist', 'K2Model');
		$Item = K2Model::getInstance('Item', 'K2Model');
		$this->_total = $ItemList->getTotal();
		if($this->isAjax()){
			$com_k2_ajax = (int)JRequest::getVar('com_k2_ajax', 0);
			$k2reslisting_ajax_com = JRequest::getVar('k2reslisting_ajax_com');
			$k2reslisting_ajax_view	= JRequest::getVar('k2reslisting_ajax_view');
			if($com_k2_ajax == 1  && $k2reslisting_ajax_com == 'com_k2' && $k2reslisting_ajax_view == 'itemlist'){
				$ordering = $k2params->get('catOrdering');
				$task = JRequest::getCmd('task');
				$view = JRequest::getCmd('view');
				$limitstart = JRequest::getInt('limitstart');
				$result = new stdClass();
				JRequest::set(array('limit'=>$this->_limit),'POST', true);	
				$items = $ItemList->getData($ordering);
				for ($i = 0; $i < sizeof($items); $i++) {
					$items[$i]->itemGroup = 'leading';
					if ( $task == "category" || $task == "" ) {
						if ($i < ($k2params->get('num_links') + $k2params->get('num_leading_items') + $k2params->get('num_primary_items') + $k2params->get('num_secondary_items')))
							$items[$i]->itemGroup = 'links';
						if ($i < ($k2params->get('num_secondary_items') + $k2params->get('num_leading_items') + $k2params->get('num_primary_items')))
							$items[$i]->itemGroup = 'secondary';
						if ($i < ($k2params->get('num_primary_items') + $k2params->get('num_leading_items')))
							$items[$i]->itemGroup = 'primary';
						if ($i < $k2params->get('num_leading_items'))
							$items[$i]->itemGroup = 'leading';
					}
					$items[$i] = $Item->prepareItem($items[$i],$view, $task);
					$items[$i] = $Item->execPlugins($items[$i], $view, $task);
					$dispatcher = JDispatcher::getInstance();
					JPluginHelper::importPlugin('k2');
					$results = $dispatcher->trigger('onK2CommentsCounter', array(
						&$items[$i],
						&$params,
						$limitstart
					));
					$items[$i]->event->K2CommentsCounter = trim(implode("\n", $results));
				}
				if ($task == "category" || $task == ""){
					// Leading items
					$offset = 0;
					$length = $k2params->get('num_leading_items');
					$leading = array_slice($items, $offset, $length);
					// Primary
					$offset = (int)$k2params->get('num_leading_items');
					$length = (int)$k2params->get('num_primary_items');
					$primary = array_slice($items, $offset, $length);
					// Secondary
					$offset = (int)($k2params->get('num_leading_items') + $k2params->get('num_primary_items'));
					$length = (int)$k2params->get('num_secondary_items');
					$secondary = array_slice($items, $offset, $length);
					// Links
					$offset = (int)($k2params->get('num_leading_items') + $k2params->get('num_primary_items') + $k2params->get('num_secondary_items'));
					$length = (int)$k2params->get('num_links');
					$links = array_slice($items, $offset, $length);
				}
				ob_start();
				if(isset($leading) && count($leading)){
					$buffer0 = $this->getLeadingViewHtml($leading,$k2params);
					$result->items_markup['leading'] = preg_replace(
						array(
								'/ {2,}/',
								'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
						),
						array(
								' ',
								''
						),
						$buffer0
					);
				}
				if(isset($primary) && count($primary)){
					$buffer1 = $this->getPrimaryViewHtml($primary,$k2params);
					$result->items_markup['primary'] = preg_replace(
						array(
								'/ {2,}/',
								'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
						),
						array(
								' ',
								''
						),
						$buffer1
					);
				}
				if(isset($secondary) && count($secondary)){
					$buffer2 = $this->getSecondaryViewHtml($secondary,$k2params);
					$result->items_markup['secondary'] = preg_replace(
						array(
								'/ {2,}/',
								'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
						),
						array(
								' ',
								''
						),
						$buffer2
					);
				}
				if(isset($links) && count($links)){
					$buffer3 = $this->getLinksViewHtml($links,$k2params);
					$result->items_markup['links'] = preg_replace(
						array(
								'/ {2,}/',
								'/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
						),
						array(
								' ',
								''
						),
						$buffer3
					);
				}
				ob_end_clean();
				die(json_encode($result));
			}
		}
	}
	
	function isAjax(){
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	protected function getLeadingViewHtml($leading,$k2params){
		ob_start(); 
		foreach($leading as $key=>$item):
			if( (($key+1)%($k2params->get('num_leading_columns'))==0) || count($leading)<$k2params->get('num_leading_columns') )
			$lastContainer= ' itemContainerLast';
			else
			$lastContainer='';
		?>
			<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($leading)==1) ? '' : ' style="width:'.number_format(100/$k2params->get('num_leading_columns'), 1).'%;"'; ?>>
				<?php
					ob_start();
					$this->item = $item;
					$this->params = $k2params;
					$path_template =  JPATH_SITE.DS.$this->_params->get('path_template').DS.'category_item.php';
					if(file_exists($path_template)){
						require ($path_template);
					}else{
						require (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'templates'.DS.'default'.DS.'category_item.php');
					}
					$getitemhtml = ob_get_contents();
					ob_end_clean();
					echo $getitemhtml;
				?>
			</div>
			<?php if(($key+1)%($k2params->get('num_leading_columns'))==0): ?>
			<div class="clr"></div>
			<?php endif;
		endforeach; 
		$getleadinghtml = ob_get_contents();
		ob_end_clean();
		return $getleadinghtml;
	}
	
	protected function getPrimaryViewHtml($primary,$k2params){
		ob_start(); 
		foreach($primary as $key=>$item): 
			if( (($key+1)%($k2params->get('num_primary_columns'))==0) || count($primary)<$k2params->get('num_primary_columns') )
				$lastContainer= ' itemContainerLast';
			else
				$lastContainer='';
		?>
			<div class="itemContainer<?php echo $lastContainer; ?>" <?php echo (count($primary)==1) ? '' : ' style="width:'.number_format(100/$k2params->get('num_primary_columns'), 1).'%;"'; ?>>
				<?php
					ob_start();
					$this->item = $item;
					$this->params = $k2params;
					$path_template =  JPATH_SITE.DS.$this->_params->get('path_template').DS.'category_item.php';
					if(file_exists($path_template)){
						require ($path_template);
					}else{
						require (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'templates'.DS.'default'.DS.'category_item.php');
					}
					$getitemhtml = ob_get_contents();
					ob_end_clean();
					echo $getitemhtml;
				?>
			</div>
			<?php if(($key+1)%($k2params->get('num_primary_columns'))==0): ?>
			<div class="clr"></div>
			<?php endif; 
		endforeach;
		$getprimaryhtml = ob_get_contents();
		ob_end_clean();
		return $getprimaryhtml;
	}
	
	protected function getSecondaryViewHtml($secondary,$k2params){
		ob_start(); 
		foreach($secondary as $key=>$item): 
			if( (($key+1)%($k2params->get('num_secondary_columns'))==0) || count($secondary)<$k2params->get('num_secondary_columns') )
				$lastContainer= ' itemContainerLast';
			else
				$lastContainer='';
			?>
			<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($secondary)==1) ? '' : ' style="width:'.number_format(100/$k2params->get('num_secondary_columns'), 1).'%;"'; ?>>
				<?php
					ob_start();
					$this->item = $item;
					$this->params = $k2params;
					$path_template =  JPATH_SITE.DS.$this->_params->get('path_template').DS.'category_item.php';
					if(file_exists($path_template)){
						require ($path_template);
					}else{
						require (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'templates'.DS.'default'.DS.'category_item.php');
					}
					$getitemhtml = ob_get_contents();
					ob_end_clean();
					echo $getitemhtml;
				?>
			</div>
			<?php if(($key+1)%($k2params->get('num_secondary_columns'))==0): ?>
			<div class="clr"></div>
			<?php endif;
		endforeach; 
		$getsecondaryhtml = ob_get_contents();
		ob_end_clean();
		return $getsecondaryhtml;
	}
	
	protected function getLinksViewHtml($links,$k2params){
		ob_start();
		foreach($links as $key=>$item): 
			if( (($key+1)%($k2params->get('num_links_columns'))==0) || count($links)<$k2params->get('num_links_columns') )
				$lastContainer= ' itemContainerLast';
			else
				$lastContainer='';
			?>
			<div class="itemContainer<?php echo $lastContainer; ?>"<?php echo (count($links)==1) ? '' : ' style="width:'.number_format(100/$k2params->get('num_links_columns'), 1).'%;"'; ?>>
				<?php
					ob_start();
					$this->item = $item;
					$this->params = $k2params;
					$path_template =  JPATH_SITE.DS.$this->_params->get('select_template').DS.'category_item_links.php';
					if(file_exists($path_template)){
						require ($path_template);
					}else{
						require (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'templates'.DS.'default'.DS.'category_item_links.php');
					}
					$getitemhtml = ob_get_contents();
					ob_end_clean();
					echo $getitemhtml;
				?>
			</div>
			<?php if(($key+1)%($k2params->get('num_links_columns'))==0): ?>
			<div class="clr"></div>
			<?php endif;
		endforeach;
		$getlinkshtml = ob_get_contents();
		ob_end_clean();
		return $getlinkshtml;
	}
	
	function onAfterRender(){
		if ($this->app ->isAdmin()) return;
		$app = JFactory::getApplication();
		$option = $app->input->get('option');
		$view =  $app->input->get('view');
		$body   = JResponse::GetBody();
		if($app->isSite()){
			if($option  ==  'com_k2' && $view == 'itemlist'){
				if($this->_params->get('display_pagination') == 0){
					$body = str_replace('div class="k2Pagination">','div class="k2Pagination" style="display:none;">', $body);
					$body = str_replace('div class="pagination">','div class="pagination" style="display:none;">', $body);
				}
				$body = str_replace('<div class="itemList">', '<div class="itemList">'.$this->addBtnLoadMore(), $body); 
				$body = str_replace('</body>', $this->addjQuery().'</body>', $body);
				JResponse::setBody($body);
				return true;
			}	
		}	
	}
	
	function onBeforeRender(){
		if ($this->app ->isAdmin()) return;
		$document = JFactory::getDocument();
		if (!defined('SMART_JQUERY') && ( int ) $this->params->get ( 'include_jquery', '1' )) {
			$document->addScript(JURI::root(true).'/plugins/system/plg_sj_k2_listing_ajax/assets/js/jquery-1.8.2.min.js');
			$document->addScript(JURI::root(true).'/plugins/system/plg_sj_k2_listing_ajax/assets/js/jquery-noconflict.js');
			define('SMART_JQUERY', 1);
		}
		$document->addStyleSheet(JURI::root(true).'/plugins/system/plg_sj_k2_listing_ajax/assets/css/styles.css' );
		return true;
	}
	
	function onBeforeCompileHead(){
	
	}
	
	protected function addBtnLoadMore(){
		$app = JFactory::getApplication();
		$option = JRequest::getVar('option');
		$view = JRequest::getVar('view');
		$classloaded = ($this->_start >= $this->_total || $this->_start == 0 )?'loaded':''; 
		ob_start();
		?>
		<div 	data-rlc_load="<?php echo $this->_start; ?>" 
				data-rlc_ajaxurl="<?php echo (string)JURI::getInstance(); ?>"
				data-rlc_allready="<?php echo JText::_('ALL_READY_LABEL'); ?>" 
				data-rlc_total="<?php echo $this->_total; ?>" 
				data-rlc_start="<?php echo $this->_start; ?>" 
				data-rlc_limit="<?php echo $this->_limit; ?>"
				class="resplc-loadmore " style="display:none;">
			<div data-rlc_label="<?php echo ($classloaded)?JText::_('ALL_READY_LABEL'):JText::_('LOAD_MORE_LABEL');?>" class="resplc-loadmore-btn <?php echo $classloaded?>" >
				<span class="resplc-image-loading"></span>
				<p class='ajax-first'><?php echo JText::_('TEXT_MORE_LABEL'); ?></p>
			</div>
		</div>
		<?php 
		$html = ob_get_contents();
		ob_end_clean();
		return $html;	
	}
	
	protected function addjQuery(){
		ob_start();?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				;(function(element){
					var $element 	= $(element),
						$load_more = $('.resplc-loadmore',$element),
						$btn_load 	= $('.resplc-loadmore-btn', $load_more),
						$item_list 	= $('.itemList', $element);
						
					$load_more.insertAfter($item_list);
					$load_more.removeAttr("style");
					
					function showAnimateItems(el){
						var $_items = $('.itemContainer',el), delay = 0, nub = 0;
						$('.resplc-loadmore-btn',el).fadeOut('fast');
						$_items.each(function(){
							nub ++;
							$(this).delay(delay).animate({
								opacity:1,
								filter:'alpha(opacity = 100)'
							},{
								delay: 50
							});
							delay += 50;
							if(nub == $_items.length){
								$('.resplc-loadmore-btn',el).fadeIn(delay);
							}
						});
					}
					
					function updateStatus($el){
						$('.resplc-loadmore-btn',$el).removeClass('loading');
						var countitem = $('.itemContainer',$el).length;
						$('.resplc-image-loading',  $el).css({display:'none'});
						$('.resplc-loadmore-btn',$el).parent().attr('data-rlc_start', countitem);
						var rlc_total = $('.resplc-loadmore-btn',$el).parent().attr('data-rlc_total');
						var rlc_load = $('.resplc-loadmore-btn',$el).parent().attr('data-rlc_load');
						var rlc_allready =  $('.resplc-loadmore-btn',$el).parent().attr('data-rlc_allready');
						if(countitem == rlc_total){
							$('.resplc-loadmore-btn',$el).addClass('loaded');
							$('.resplc-image-loading',  $el).css({display:'none'});
							$('.resplc-loadmore-btn',$el).attr('data-rlc_label',rlc_allready);
							$('.resplc-loadmore-btn',$el).removeClass('loading');
						}
					}
					
					$btn_load.click(function(){
						var $this = $(this);
						if ($this.hasClass('loaded') || $this.hasClass('loading')){
							return false;
						}else{
							$this.addClass('loading');
							$('.resplc-image-loading',  $this).css({display:'inline-block'});
							var rlc_start = $this.parent().attr('data-rlc_start'),
								rlc_limit = $this.parent().attr('data-rlc_limit'),
								rlc_ajaxurl = $this.parent().attr('data-rlc_ajaxurl');
								
							$.ajax({
								type: 'POST',
								url: rlc_ajaxurl,
								data:{
									k2reslisting_ajax_com: 'com_k2',
									k2reslisting_ajax_view: 'itemlist',
									is_ajax: 1,
									com_k2_ajax: 1,
									limitstart: rlc_start,
									limit: rlc_limit
								},
								success: function(data){
								   if(typeof data.items_markup != 'undefined') {
									   if (typeof data.items_markup.leading != 'undefined' && data.items_markup.leading != ''){
											
											$(data.items_markup.leading).css({
												opacity:0,
												filter:'alpha(opacity = 0)'
											}).insertAfter($('.itemContainer',$('#itemListLeading',$item_list)).nextAll().last());
									   }
									   
									   if(typeof data.items_markup.primary != 'undefined' && data.items_markup.primary != ''){
											$(data.items_markup.primary).css({
												opacity:0,
												filter:'alpha(opacity = 0)'
											}).insertAfter($('.itemContainer',$('#itemListPrimary',$item_list)).nextAll().last());
									   }
									   
									   if( typeof data.items_markup.secondary != 'undefined' && data.items_markup.secondary != ''){
											$(data.items_markup.secondary).css({
												opacity:0,
												filter:'alpha(opacity = 0)'
											}).insertAfter($('.itemContainer',$('#itemListSecondary',$item_list)).nextAll().last());
									   }
									   
									   if( typeof data.items_markup.links != 'undefined' && data.items_markup.links != ''){
											$(data.items_markup.links).css({
												opacity:0,
												filter:'alpha(opacity = 0)'
											}).insertAfter($('.itemContainer',$('#itemListLinks',$item_list)).nextAll().last());
									   }
									   
									   if($.isFunction($.fn.lazyload)){
											$("#yt_component img").lazyload({ 
												effect : "fadeIn",
												effect_speed: 2000,
												/*container: "#yt_component",*/
												load: function(){
													$(this).css("visibility", "visible"); 
													$(this).removeAttr("data-original");
												}
											});
										}
								    }
									$('.resplc-image-loading',  $this).css({display:'none'});
									showAnimateItems($element);
									updateStatus($element);
								}, 
								dataType:'json'
							});
						}
						return false;
					});
				})('<?php echo $this->tag_id ?>');
			});
		</script>
		<?php 
		$jq = ob_get_contents();
		ob_end_clean();
		return $jq;
	}
}
