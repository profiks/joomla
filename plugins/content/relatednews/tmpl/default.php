<?php
/**
 * @package Content - Related News
 * @version 1.7.0
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2012 YouTech Company. All Rights Reserved.
 * @author YouTech Company http://www.smartaddons.com
 *
 */
defined('_JEXEC') or die;
ImageHelper::setDefault($_params);
if (count($items)){
	if ((int)$this->params->get('usecss', 1)){
		$css_url = JURI::root() . 'plugins/content/relatednews/assets/css/relatednews.css';
		$document = &JFactory::getDocument();
		$document->addStyleSheet($css_url);
	}
	if ($this->params->get('title')){
		echo '<h3 class="related-title">';
		echo $this->params->get('title');
		echo '</h3>';
	}
	echo '<ul class="related-items row-fluid">';
	foreach ($items as $id => $item) { 
		if($item->id != $article_id){?>
			<li class="span<?php echo round((12/$this->params->get('count'))); ?>"  >
			<?php
			if ((int)$this->params->get('item_image_display', 1)){ ?>
				<div class="img-fulltext">
				
					<?php 
						$img = BaseHelper::getArticleImage($item, $_params);
						
						//check placeholder path is exist or not exist?
						if(file_exists($img['src'])){
							 echo BaseHelper::imageTag($img);
						}else{
							echo '<img alt="http://placehold.it/200x100" src="http://placehold.it/200x100" />';
							$img['src']='http://placehold.it/350x530';
						}
					?>
					
				</div>
			<?php }?>
			
			<?php
			if ((int)$_params->get('item_date_display', 1) == 1): ?>
				<span class="related-item-date"><?php echo JHtml::date($item->created, 'Y-m-d'); ?></span>
			<?php
			endif;
			?>
				<h3 class="related-item-title">
					<a href="<?php echo $item->link; ?>" <?php echo BaseHelper::parseTarget($_params->get('item_link_target'));?> >
					<?php echo $item->title;; ?>
					</a>
				</h3>
				
			<?php
			if ((int)$_params->get('item_date_display', 1) == 2): ?>
				<span class="related-item-date"><?php echo JHtml::date($item->created, 'Y-m-d'); ?></span>
			<?php
			endif;
			?>
			</li>
	<?php
	}}
	echo '</ul>';
}

?>