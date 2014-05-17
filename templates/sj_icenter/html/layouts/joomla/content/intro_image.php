<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// Create a shortcut for params.
$params  = $displayData->params;
//$canEdit = $this->item->params->get('access-edit');


// Begin: dungnv added
global $leadingFlag;
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
$templateParams = JFactory::getApplication()->getTemplate(true)->params;
// End: dungnv added
?>
<?php $images = json_decode($displayData->images);?>

<?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
	<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
    
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
			$imgscr_full = JURI::base(true).'/'.htmlspecialchars($images->image_intro);
		}
	}else{
		$imgsrc = 'http://placehold.it/250x240';
		$imgscr_full = 'http://placehold.it/700x450';
	}
	
	?>
	<div class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image " style="min-width:<?php echo $imgH ?>px;min-height:<?php echo $imgW ?>px"> 
		<a  class="item_image_wrap" data-rel="prettyPhoto"   title="<?php echo htmlspecialchars($images->image_intro_alt); ?>" href="<?php echo $imgscr_full ?>" >
			<img src="<?php echo htmlspecialchars($imgsrc); ?>"<?php echo $imgattr; ?>/> 
			
			<div class="circle_hover">+</div>
		</a>
		
	</div>
<?php endif; 
// End: dungnv edited
?>
