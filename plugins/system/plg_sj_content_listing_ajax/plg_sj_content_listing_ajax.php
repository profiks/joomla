<?php
/**
 * @package Sj Content Listing Ajax
 * @version 1.0.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
class plgSystemPlg_Sj_Content_Listing_Ajax extends JPlugin {
	
	protected	$_total			= 0;
	protected	$_start 		= 0;
	protected	$_limit 		= 0; 
	protected	$tag_id 		= null;
	protected	$app 			= array();
	protected   $_params 		= null;
	protected   $_admin			= null;
	protected   $columns		= null;
	protected	$_view			= null;
	
	function __construct ( $subject, $config ) {
		parent::__construct( $subject, $config );
		$this->loadLanguage();
		$this->_admin = JFactory::getApplication()->isAdmin();
		$this->_params = $this->params;
		$this->_limit = (int)$this->_params->get('limit');
		$this->cls_items_leading1 = trim($this->_params->get('cls_items_leading1','items-leading'));
		$this->cls_items_leading2 = trim($this->_params->get('cls_items_leading2','item'));
		$this->cls_items_row1 = trim($this->_params->get('cls_items_row1','items-row'));
		$this->cls_items_row2 = trim($this->_params->get('cls_items_row2','item'));
		$this->cls_items_more = trim($this->_params->get('cls_items_more','items-more'));
	}

	function onAfterInitialise () {
		if ($this->_admin) return;
	}	
	
	function onAfterRouter () {
		if ($this->_admin) return;
		JPlugin::loadLanguage('com_content', JPATH_SITE);
	}
	

	function onAfterDispatch(){
		if ($this->_admin) return;
		$app = JFactory::getApplication();
		$option = $app->input->get('option');
		$view =  $app->input->get('view');
		$layout = $app->input->get('layout');
		if($option  ==  'com_content') {
			if($view == 'category' &&  $layout == 'blog'){
				$this->_view = 'view_category';
			}else if($view == 'featured'){
				$this->_view = 'view_featured';
			}
		}
		if($this->_view == null) return;
		
		$com_path = JPATH_SITE.'/components/com_content/';
		if(!class_exists('ContentBuildRoute')){
			require_once $com_path.'router.php';
		}
		if(!class_exists('ContentHelperRoute')) {
			require_once $com_path.'helpers/route.php';
		}
		JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
		$_cont_params = JComponentHelper::getParams('com_content');	
		$menuParams = new JRegistry;
		if ($menu = $app->getMenu()->getActive()){
			$menuParams->loadString($menu->params);
		}
		$cont_params = clone $_cont_params;
		$cont_params->merge($menuParams);
		$numLeading	= $cont_params->get('num_leading_articles', 1);
		$numIntro	= $cont_params->get('num_intro_articles', 4);
		$numLinks	= $cont_params->get('num_links', 4);
		$limit = $numLeading  + $numIntro + $numLinks;
		
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		$model->setState('params', $cont_params);
		if($this->_view == 'view_category'){
			$model->setState('filter.category_id', JRequest::getVar('id'));
		}else{
			$model->setState('filter.category_id', JRequest::getVar('id',$cont_params->get('featured_categories')));
		}
		$model->setState('list.start', 0);
		$model->setState('list.limit', 0);
		$model->setState('filter.published', 1);
		
		$access = !$cont_params->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access',$access);
		$model->setState('filter.language', $app->getLanguageFilter());
		
		if($this->_view == 'view_featured'){
			$articleOrderby = $cont_params->get('orderby_sec', 'rdate');
			$articleOrderDate = $cont_params->get('order_date');
			$categoryOrderby = $cont_params->def('orderby_pri', '');
			$secondary = ContentHelperQuery::orderbySecondary($articleOrderby, $articleOrderDate) . ', ';
			$primary = ContentHelperQuery::orderbyPrimary($categoryOrderby);
			$orderby = $primary . ' ' . $secondary . ' a.created DESC ';
			$model->setState('filter.frontpage', true);
			$model->setState('list.ordering', $orderby);
			$model->setState('list.direction', '');
			$model->setState('filter.featured', 'only');
		}elseif($this->_view == 'view_category'){
			$model->setState('list.ordering', $this->_buildContentOrderBy());
		}
		
		$numberitems = $model->getItems();
	
		$this->_total = count($numberitems); 
		$this->_start = $limit;
		$this->_limit = ($this->_limit > 0)?$this->_limit:$limit;
		$this->tag_id = ($view == 'category')?'.blog':'.blog-featured';
		if($this->isAjax()){
			$com_content_ajax = (int)JRequest::getVar('com_content_ajax', 0);
			$content_listing_ajax_com = JRequest::getVar('content_listing_ajax_com');
			$content_listing_ajax_view	= JRequest::getVar('content_listing_ajax_view');
			$content_listing_ajax_layout	= JRequest::getVar('content_listing_ajax_layout');
			if($com_content_ajax == 1  && $content_listing_ajax_com == 'com_content' && (($content_listing_ajax_view == 'category' && $content_listing_ajax_layout == 'blog') || ($content_listing_ajax_view == 'featured' &&$content_listing_ajax_layout == 'featured'))){
				$task = JRequest::getCmd('task');
				$view = JRequest::getCmd('view');
				$result = new stdClass();
				$model->setState('filter.category_id', JRequest::getVar('id'));
				$model->setState('list.start', $app->input->getInt('limitstart',0));
				$model->setState('list.limit', $this->_limit);
				$lead_items = array();
				$intro_items = array();
				$link_items = array();
				$items = $model->getItems();
				for ($i = 0, $n = count($items); $i < $n; $i++){
					$item = &$items[$i];
					$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
					$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;
					// No link for ROOT category
					if ($item->parent_alias == 'root'){
						$item->parent_slug = null;
					}
					$item->catslug = $item->category_alias ? ($item->catid.':'.$item->category_alias) : $item->catid;
					$item->event = new stdClass();
					
					$dispatcher = JDispatcher::getInstance();
					// Old plugins: Ensure that text property is available
					if (!isset($item->text))
					{
						$item->text = $item->introtext;
					}

					JPluginHelper::importPlugin('content');
					$results = $dispatcher->trigger('onContentPrepare', array ('com_content.category', &$item, &$this->params, 0));

					// Old plugins: Use processed text as introtext
					$item->introtext = $item->text;

					$results = $dispatcher->trigger('onContentAfterTitle', array('com_content.category', &$item, &$item->params, 0));
					$item->event->afterDisplayTitle = trim(implode("\n", $results));

					$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_content.category', &$item, &$item->params, 0));
					$item->event->beforeDisplayContent = trim(implode("\n", $results));

					$results = $dispatcher->trigger('onContentAfterDisplay', array('com_content.category', &$item, &$item->params, 0));
					$item->event->afterDisplayContent = trim(implode("\n", $results));
				}
				if (($cont_params->get('layout_type') == 'blog') || ($app->input->get('layout') == 'blog') || ($content_listing_ajax_view == 'featured' &&$content_listing_ajax_layout == 'featured') ){
					$max = count($items);
					$limit = $numLeading;
					for ($i = 0; $i < $limit && $i < $max; $i++){
						$lead_items[$i] = &$items[$i];
					}
					$limit = $numLeading + $numIntro;
					for ($i = $numLeading; $i < $limit && $i < $max; $i++){
						$intro_items[$i] = &$items[$i];
					}
					$this->columns = max(1, $cont_params->get('num_columns', 1));
					$order = $cont_params->get('multi_column_order', 1);
					if ($order == 0 && $this->columns > 1){
						$intro_items = ContentHelperQuery::orderDownColumns($intro_items, $this->columns);
					}
					$_numLinks = ($numLeading + $numIntro + $numLinks <= 0 || ($this->_limit >= $numLeading + $numIntro + $numLinks) )?$this->_limit:$numLeading + $numIntro + $numLinks;
					$limit = $_numLinks;
					for ($i = $numLeading + $numIntro; $i < $limit && $i < $max;$i++){
						$link_items[$i] = &$items[$i];
					}
				}
				ob_start();
				if( !empty($lead_items) ||  !empty($intro_items)  ||  !empty($link_items)){
				
					$buffer0 = $this->getViewHtml($lead_items, $intro_items, $link_items, $cont_params);
					$result->items_markup = preg_replace(
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
				ob_end_clean();
				die(json_encode($result));
			}
		}
	}
	
	function isAjax(){
		return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
	function escape($var){
		$jview = new JViewLegacy();
		return  $jview->escape($var);
	}
	
	protected function getFileDefault($file_name = 'item'){
		if($this->_view == 'view_category' ){
			$path_template =  JPATH_SITE.DS.$this->_params->get('path_blog_category').DS.'blog_'.$file_name.'.php';
			if(file_exists($path_template)){
				require ($path_template);
			}else{
				require (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'views'.DS.'category'.DS.'tmpl'.DS.'blog_'.$file_name.'.php');
			}
		}else if($this->_view == 'view_featured'){
			$path_template =  JPATH_SITE.DS.$this->_params->get('patt_blog_featured').DS.'default_'.$file_name.'.php';
			if(file_exists($path_template)){
				require ($path_template);
			}else{
				require (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'views'.DS.'featured'.DS.'tmpl'.DS.'default_'.$file_name.'.php');
			}
		}
		return true;
	}
	
	protected function getViewHtml($lead_items, $intro_items, $link_items,$cont_params){
		ob_start(); 
		$leadingcount = 0;
		if (!empty($lead_items)) : ?>
		<div class="<?php echo $this->cls_items_leading1 ?> ">
			<?php foreach ($lead_items as &$item) : ?>
			<div class="<?php echo $this->cls_items_leading2 ?>  leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
				<?php
					ob_start();
					$this->item = $item;
					$this->params = $cont_params;
					$this->getFileDefault('item');
					$getitemhtml = ob_get_contents();
					ob_end_clean();
					echo $getitemhtml;
				?>
			</div>
			<?php
				$leadingcount++;
			?>
			<?php endforeach; ?>
		</div><!-- end items-leading -->
		<?php endif;
		$introcount = (count($intro_items));
		$counter = 0;
		if (!empty($intro_items)) :
		foreach ($intro_items as $key => &$item) : 
		$key = ($key - $leadingcount) + 1;
		$rowcount = (((int) $key - 1) % (int) $this->columns) + 1;
		$row = $counter / $this->columns;
		if ($rowcount == 1) : ?>
		<div class="<?php echo $this->cls_items_row1 ?>  cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row; ?> row-fluid ">
		<?php endif; ?>
			<div class="<?php echo $this->cls_items_row2 ?> span<?php echo round((12 / $this->columns));?>">
				<?php
				
				ob_start();
				
				$this->item = $item;
				$this->params = $cont_params;
				$this->getFileDefault('item');
				$getitemhtml = ob_get_contents();
				ob_end_clean();
				echo $getitemhtml;
				?>
				<?php $counter++; ?>
			</div><!-- end span -->
			<?php if (($rowcount == $this->columns) or ($counter == $introcount)) : ?>
		</div><!-- end row -->
			<?php endif; ?>
		<?php endforeach; ?>
		<?php endif;
		if (!empty($link_items)) :  ?>
		<div class="<?php echo $this->cls_items_more; ?>">
			<?php
			ob_start();
			$this->link_items = $link_items;
			$this->params = $cont_params;
			$this->getFileDefault('links');
			$getitemhtml = ob_get_contents();
			ob_end_clean();
			echo $getitemhtml;
			?>
		</div>
		<?php endif; 
		$getleadinghtml = ob_get_contents();
		ob_end_clean();
		return $getleadinghtml;
	}
	
	function onAfterRender(){
		if ($this->_admin || $this->_view == '') return;
		$body   = JResponse::GetBody();
		if($this->_params->get('display_pagination') == 0){
			$body = str_replace('div class="pagination">','div class="pagination" style="display:none;">', $body);
		}
		$body = str_replace('<div class="blog">', '<div class="blog">'.$this->addBtnLoadMore(), $body); 
		$body = str_replace('<div class="blog-featured">', '<div class="blog-featured">'.$this->addBtnLoadMore(), $body);
		$body = str_replace('</body>', $this->addjQuery().'</body>', $body);
		JResponse::setBody($body);
		return true;
	}
	
	function onBeforeRender(){
		if ($this->_admin || $this->_view == null) return;
		$document = JFactory::getDocument();
		if (!defined('SMART_JQUERY') && ( int ) $this->params->get ( 'include_jquery', '1' )) {
			$document->addScript(JURI::root(true).'/plugins/system/plg_sj_content_listing_ajax/assets/js/jquery-1.8.2.min.js');
			$document->addScript(JURI::root(true).'/plugins/system/plg_sj_content_listing_ajax/assets/js/jquery-noconflict.js');
			define('SMART_JQUERY', 1);
		}
		$document->addStyleSheet(JURI::root(true).'/plugins/system/plg_sj_content_listing_ajax/assets/css/styles.css' );
		return true;
	}
	
	function onBeforeCompileHead(){
	
	}
	
	protected function _buildContentOrderBy(){
		$app		= JFactory::getApplication();
		$db			= JFactory::getDbo();
		$params		= JComponentHelper::getParams('com_content');
		$itemid		= $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		$orderCol	= $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		$orderDirn	= $app->getUserStateFromRequest('com_content.category.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		$orderby	= ' ';
		$_filter_fields = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'state', 'a.state',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'modified', 'a.modified',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language',
				'hits', 'a.hits',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'author', 'a.author'
			);
		if (!in_array($orderCol, $_filter_fields))
		{
			$orderCol = null;
		}

		if (!in_array(strtoupper($orderDirn), array('ASC', 'DESC', '')))
		{
			$orderDirn = 'ASC';
		}

		if ($orderCol && $orderDirn)
		{
			$orderby .= $db->escape($orderCol) . ' ' . $db->escape($orderDirn) . ', ';
		}

		$articleOrderby		= $params->get('orderby_sec', 'rdate');
		$articleOrderDate	= $params->get('order_date');
		$categoryOrderby	= $params->def('orderby_pri', '');
		$secondary			= ContentHelperQuery::orderbySecondary($articleOrderby, $articleOrderDate) . ', ';
		$primary			= ContentHelperQuery::orderbyPrimary($categoryOrderby);
		$orderby .= $primary . ' ' . $secondary . ' a.created ';
		return $orderby;
	}
	
	protected function addBtnLoadMore(){
		$app = JFactory::getApplication();
		$option = JRequest::getVar('option');
		$view = JRequest::getVar('view');
		$layout = JRequest::getVar('layout');
		$classloaded = ($this->_start >= $this->_total || $this->_start == 0 )?'loaded':''; 
		ob_start();
		?>
		<div 	data-rlc_option="<?php echo $option; ?>" 
				data-rlc_view="<?php echo $view; ?>" 
				data-rlc_layout="<?php echo ($layout != '')?$layout:'featured'; ?>" 
				data-rlc_load="<?php echo $this->_start; ?>" 
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
						$btn_load 	= $('.resplc-loadmore-btn', $load_more);
						
					$load_more.insertAfter($element.children().last());
					$load_more.removeAttr("style");
					
					function showAnimateItems(el){
						var  $_items = el , delay = 0, nub = 0;
						//$load_more.fadeOut('fast');
						$_items.each(function(){
							nub ++;
							$(this).delay(delay).animate({
								opacity:1,
								filter:'alpha(opacity = 100)'
							},{
								delay: 80,
								queue: true,
								complete: function(){
									$_items.removeAttr('style');
								}
							} );
							delay += 80;
							if(nub == $_items.length){
								//$load_more.fadeIn(delay);
							}
						});
					}
					
					function updateStatus($el){
						$('.resplc-loadmore-btn',$el).removeClass('loading');
						var $leading = $('.<?php echo $this->cls_items_leading1; ?>',$element),	
							$items_row = $('.<?php echo $this->cls_items_row1; ?>',$element),
							$links = $('.<?php echo $this->cls_items_more; ?>',$element),
							num_leading = $leading.children().length
							num_rows = $items_row.children().length;
							num_links =$('ol',$links).children().length;
						var countitem = num_leading + num_rows + num_links;
						$('.resplc-image-loading',  $el).css({display:'none'});
						$('.resplc-loadmore-btn',$el).parent().attr('data-rlc_start', countitem);
						var rlc_total = $('.resplc-loadmore-btn',$el).parent().attr('data-rlc_total'),
							rlc_load = $('.resplc-loadmore-btn',$el).parent().attr('data-rlc_load'),
							rlc_allready =  $('.resplc-loadmore-btn',$el).parent().attr('data-rlc_allready');
					
						if(countitem >= rlc_total){
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
								rlc_ajaxurl = $this.parent().attr('data-rlc_ajaxurl'),
								rlc_option = $this.parent().attr('data-rlc_option'),
								rlc_view = $this.parent().attr('data-rlc_view'),
								rlc_layout = $this.parent().attr('data-rlc_layout');
							var $leading = $('.<?php echo $this->cls_items_leading1; ?>',$element),	
								$items_row = $('.<?php echo $this->cls_items_row1; ?>',$element),
								$links = $('.<?php echo $this->cls_items_more; ?>',$element),
								$child_links = $('ol',$links).children();
							$.ajax({
								type: 'POST',
								url: rlc_ajaxurl,
								data:{
									content_listing_ajax_com: rlc_option,
									content_listing_ajax_view: rlc_view,
									content_listing_ajax_layout: rlc_layout,
									is_ajax: 1,
									com_content_ajax: 1,
									limitstart: rlc_start,
									limit: rlc_limit
								},
								success: function(data){
								   if( typeof data.items_markup != 'undefined' &&  data.items_markup.length != '') {
										var $new_element = $(data.items_markup),
											$_leading = $new_element.filter('.<?php echo $this->cls_items_leading1; ?>'),
											$child_leading = $_leading.children(),
											$_items_row = $new_element.filter('.<?php echo $this->cls_items_row1; ?>'),
											$child_items_row = $_items_row.children(),
											$_links = $new_element.filter('.<?php echo $this->cls_items_more; ?>');
											$_child_links = $('ol',$_links).children();
											;
										if($_leading.length  > 0){
											$child_leading.css({
												opacity:0,
												filter:'alpha(opacity = 0)'
											}).insertAfter($leading.children().last());
											showAnimateItems($child_leading);
										}
										if($_items_row.length  > 0){
											$_items_row.css({
												opacity:0,
												filter:'alpha(opacity = 0)'
											}).insertAfter($items_row.last());
											showAnimateItems($_items_row);
										}
										if($_links.length  > 0){
											$_child_links.css({
												opacity:0,
												filter:'alpha(opacity = 0)'
											}).insertAfter($child_links.last());
											showAnimateItems($_child_links);
										}
										if($.isFunction($.fn.lazyload)){
											$("#yt_component img").lazyload({ 
												effect : "fadeIn",
												effect_speed: 1500,
												load: function(){
													//$(this).css("visibility", "visible"); 
													$(this).removeAttr("data-original");
												}
											});
										}
										
										if($.isFunction($.fn.prettyPhoto)){
											$("a[data-rel^='prettyPhoto']").prettyPhoto({
												autoplay: false,
												deeplinking: false,
												animation_speed: 'fast', /* fast/slow/normal */
												slideshow: 5000, /* false OR interval time in ms */
												autoplay_slideshow: false, /* true/false */
												opacity: 0.8, /* Value between 0 and 1 */
												show_title: true, /* true/false */
												allow_resize: true, /* Resize the photos bigger than viewport. true/false */
												default_width: 500,
												default_height: 344,
												counter_separator_label: '/', /* The separator for the gallery counter 1 "of" 2 */
												theme: 'pp_default', /* light_rounded / dark_rounded / light_square / dark_square / facebook */
												horizontal_padding: 20, /* The padding on each side of the picture */
												overlay_gallery: true, /* If set to true, a gallery will overlay the fullscreen image on mouse over */
												keyboard_shortcuts: true, /* Set to false if you open forms inside prettyPhoto */
												social_tools: false
											});
										}
								    }
									$('.resplc-image-loading',  $this).css({display:'none'});
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
