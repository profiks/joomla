<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *  
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
 
defined('_JEXEC') or die('Restricted access');
?>
<div class="ezcategoryhead ezcf">
    <?php if ($params->get('showcavatar', true)) : ?>
	<div class="avatar ezfl ezmrs">
		<img style="border-style:solid; border-width:1px; border-color:grey;" src="<?php echo $category->getAvatar(); ?>" width="35" alt="<?php echo $category->title; ?>" />
	</div>
	<?php endif; ?>

    <div class="eztc">
        <div class="ezcategorytitle">
        <?php

		$tmpObj = new stdClass();
		$tmpObj->category_id    = $category->id;
		$tmpObj->created_by     = '0';
		$tmpObj->id    			= '0';

		$itemId     = modLatestBlogsHelper::_getMenuItemId($tmpObj, $params);


		$catURL 	= EasyBlogRouter::_('index.php?option=com_easyblog&view=categories&layout=listings&id=' . $category->id . $itemId );
		$catLink	= '<a href="'.$catURL.'">'.$category->title.'</a>';
		echo $catLink;
		?>
        </div>
        <?php if($params->get('showccount', true)) : ?>
        <div class="ezpostcount small">
            <?php echo JText::sprintf('MOD_LATESTBLOGS_POST_COUNT', $categoryTotalPostCnt);?>
        </div>
        <?php endif; ?>
    </div>
</div>