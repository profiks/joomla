<?php
/**
 * @package Sj Content Slick Slider
 * @version 2.5.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2013 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 */

defined('_JEXEC') or die;

JHtml::stylesheet('templates/'.JFactory::getApplication()->getTemplate().'/html/'.$module->module.'/css/sj-slickslider.css');
JHtml::stylesheet('modules/'.$module->module.'/assets/css/slickslider-font-color.css');
if (!defined('SMART_JQUERY') && ( int ) $params->get ( 'include_jquery', '1' )) {
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-1.8.2.min.js');
	define('SMART_JQUERY', 1);
}
if (!defined('SMART_NOCONFLICT')){
	JHtml::script('modules/'.$module->module.'/assets/js/jquery-noconflict.js');
	define('SMART_NOCONFLICT', 1);
}
JHtml::script('modules/'.$module->module.'/assets/js/jcarousel.js');
JHtml::script('modules/'.$module->module.'/assets/js/jquery.touchwipe.1.1.1.js');
ImageHelper::setDefault($params);
$start = (int)$params->get('start','1');
if ($start < 1 || $start > count($list)){
	$start = 1;
}
$pause_hover = ($params->get('pause_hover') == 'hover')?'hover':'';;
$interval = (int)$params->get('interval','4000');
$effect = ($params->get('effect') == 1)?' slide':'';

if ($params->get('play') != 1){
	$interval = 0;
} else {
	$interval = $params->get('interval', 5000);
}

$pag_position = $params->get('button_position' , 'conner-bl');
$pag_type = in_array($params->get('button_theme', 'num'), array('num', 'number')) ? 'type-num' : 'type-dot';
$slick_slider_background = $params->get('theme','theme1')=='theme1' ? 'bg-style1' : 'bg-style2';
?>
<?php if ($params->get('pretext') != ''){ ?>
<div class="text-block">
	<?php echo $params->get('pretext'); ?>
</div>
<?php }?>
<div id="sj-slickslider<?php echo $module->id; ?>" class="sj-slickslider <?php echo $effect ?> <?php echo 'slickslider-'.$params->get('item_image_position'); ?>"  data-interval="<?php echo $interval?>" data-pause="<?php echo $pause_hover?>" >
	<!-- Carousel items -->
    <div class="slickslider-items <?php echo $slick_slider_background?>">
    	<?php
    	$i=1;
    	foreach ($list as $item){
			if ($i==$start){$active = 'active';}
    		else $active = '';
    		$i++;
    	?>
    	<div class="slickslider-item item clearfix <?php echo $active; ?> ">
    		<?php $img = ContentSlickSliderHelper::getAImage($item, $params); ?>
    		
    		<?php if ($img): ?>
    		<div class="item-image">
    			<div class="item-image-inner">
		            	<?php
	    				echo ContentSlickSliderHelper::imageTag($img);
	    				?>
				</div>
			</div>
			<?php endif; ?>
			
			<div class="item-content <?php if(!$img){echo 'no-images';} ?>">
				<div class="item-content-inner">
					<?php
					if( (int)$params->get('item_title_display', 1)){ ?>
					<div class="item-title">
						<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" <?php echo ContentSlickSliderHelper::parseTarget($params->get('item_link_target')); ?> >
							<?php echo ContentSlickSliderHelper::truncate($item->title, $params->get('item_title_max_characs',25)); ?>
						</a>
					</div>
					<?php } // title display 
					if( (int)$params->get('item_desc_display', 1)){ ?>
					<div class="item-description">
						<?php echo $item->displayIntrotext; ?>
					</div>
					<?php } 
					$tags = '';
					if($params->get('item_tags_display') == 1 && $item->tags != '' && !empty($item->tags->itemTags) ) {	
						$item->tagLayout = new JLayoutFile('joomla.content.tags');
						$tags = $item->tagLayout->render($item->tags->itemTags); 
					}	
					if($tags != ''){?>
					<div class="item-tags">
						<?php echo  $tags; ?>
					</div>
					<?php }
					if( (int)$params->get('item_readmore_display', 1) ){ ?>
					<div class="item-readmore">
						<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>" <?php echo ContentSlickSliderHelper::parseTarget($params->get('item_link_target')); ?> >
							<?php echo $params->get('item_readmore_text', 'Read more'); ?>
						</a>
					</div>
					<?php } // readmore display ?>
				</div>
			</div>
			
    	</div>
    	<?php } ?>
    </div>
    <!-- Carousel nav -->
	<a class="bt-slideshow left" href="#sj-slickslider<?php echo $module->id?>" data-jslide="prev"></a>
	<a class="bt-slideshow right" href="#sj-slickslider<?php echo $module->id?>" data-jslide="next"></a>
	<div class="nav-pagination <?php echo $pag_position?> <?php echo $slick_slider_background; ?>" >
		<ul class="<?php echo $pag_type;?>">			
			<?php for($i=0; $i<count($list); $i++){
				if ($i+1==$start){$sel = 'sel';}
	    		else $sel = '';
			?>
			<li class="pag-item <?php echo $sel; ?>" href="#sj-slickslider<?php echo $module->id?>" data-jslide="<?php echo $i?>">
				<?php //echo $i+1;?>
				<span></span>
			</li>
			
			<?php } ?>
		</ul>
	</div>
</div>

<script>
//<![CDATA[    					
	jQuery(document).ready(function($){
		$('#sj-slickslider<?php echo $module->id; ?>').each(function(){
			var $this = $(this), options = options = !$this.data('modal') && $.extend({}, $this.data());
			$this.jcarousel(options);
			
			$this.bind('jslide', function(e){
				var index = $(this).find(e.relatedTarget).index();
				// process for nav
				$('[data-jslide]').each(function(){
					var $nav = $(this), $navData = $nav.data(), href, $target = $($nav.attr('data-target') || (href = $nav.attr('href')) && href.replace(/.*(?=#[^\s]+$)/, ''));
					if ( !$target.is($this) ) return;
					if (typeof $navData.jslide == 'number' && $navData.jslide==index){
						$nav.addClass('sel');
					} else {
						$nav.removeClass('sel');
					}
				});
			});
			<?php if($params->get('swipe') == 1){ ?>
				$this.touchwipe({
					wipeLeft: function() { 
						$this.jcarousel('next');
					},
					wipeRight: function() { 
						$this.jcarousel('prev');
					},
					wipeUp: function() { 
						$this.jcarousel('next');
					},
					wipeDown: function() {
						$this.jcarousel('prev');
					}
				});
			<?php } ?>	
		});
		return ;
	});
//]]>	
</script>

<?php if ($params->get('posttext') != ''){ ?>
<div class="text-block">
	<?php echo $params->get('posttext'); ?>
</div>
<?php } ?>

