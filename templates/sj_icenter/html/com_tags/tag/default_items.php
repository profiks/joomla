<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_tags
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');

// Get the user object.
$user = JFactory::getUser();

// Begin: dungnv added
global $leadingFlag;
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
// End: dungnv added

// Check if user is allowed to add/edit based on tags permissions.
// Do we really have to make it so people can see unpublished tags???
$canEdit = $user->authorise('core.edit', 'com_tags');
$canCreate = $user->authorise('core.create', 'com_tags');
$canEditState = $user->authorise('core.edit.state', 'com_tags');
$items = $this->items;
$n = count($this->items);

?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm" class="form-inline">
	

	<?php if ($this->items == false || $n == 0) : ?>
		<p> <?php echo JText::_('COM_TAGS_NO_ITEMS'); ?></p>
	<?php else : ?>

	<ul class="blank items-row">
		<?php foreach ($items as $i => $item) : ?>
				<li class="item">
					
					<a style="display:none;" href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
						<?php echo $this->escape($item->core_title); ?>
					</a>

					<?php $images  = json_decode($item->core_images);?>
					<?php if ($this->params->get('tag_list_show_item_image', 1) == 1 && !empty($images->image_intro)) :?>
						
							<?php $imgfloat = (empty($images->float_intro)) ?  'left' :$images->float_intro;  ?>
							 <?php
							//check placeholder path is exist or not exist?
							if(file_exists($images->image_intro)){
								$imgattr ='';
								$imgH = (isset($leadingFlag) && $leadingFlag)?$templateParams->get('leading_width', '300'):$templateParams->get('intro_width', '200');
								$imgW = (isset($leadingFlag) && $leadingFlag)?$templateParams->get('leading_height', '300'):$templateParams->get('intro_height', '200');
								$imgsrc = YTTemplateUtils::resize($images->image_intro, $imgH, $imgW, array($templateParams->get('thumbnail_background', '#ffffff')));
								if($templateParams->get('includeLazyload')==1){
									$imgattr = ' data-original="'.$imgsrc.'"';
									$imgsrc  = JURI::base().'templates/'.JFactory::getApplication()->getTemplate().'/images/white.gif';
								}
							}else{
								$imgsrc='http://placehold.it/250x240';
								$images->image_fulltext='http://placehold.it/550x530';
							}
							?>
							<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image" style="min-width:<?php echo $imgH ?>px;min-height:<?php echo $imgW ?>px"> 
								<a  data-rel="prettyPhoto"   title="<?php echo htmlspecialchars($images->image_intro_alt); ?>" href="<?php echo htmlspecialchars($images->image_intro); ?>" >
									<img src="<?php echo htmlspecialchars($imgsrc); ?>"<?php echo $imgattr; ?>/> 
								</a>
							</div>
						
					<?php endif; ?>
					
					<div class="article-text">
						<div class="page-header">
							<h2>
								<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>">
									<?php echo $this->escape($item->core_title); ?>
								</a>
							</h2>
						</div>
						<div class="item-headinfo">
							<dl class="article-info muted">
								<dd>
									<div class="create">
									<?php echo JText::sprintf( JHTML::_('date',$item->tag_date, 'l , M d Y')); ?>
										
									</div>
								</dd>
							</dl>
						</div>
						
						<?php if ($this->params->get('all_tags_show_tag_descripion', 1)) : ?>
							<?php echo JHtml::_('string.truncate', $item->core_body, $this->params->get('tag_list_item_maximum_characters')); ?>
							<a href="<?php echo JRoute::_(TagsHelperRoute::getItemRoute($item->content_item_id, $item->core_alias, $item->core_catid, $item->core_language, $item->type_alias, $item->router)); ?>" class="more"> 
								<?php echo JText::_('READ_MORE'); ?>	
							</a>
						<?php endif; ?>
						
						
					</div>
		
				</li>
			
		<?php endforeach; ?>
	</ul>

	
</form>

<?php endif; ?>
