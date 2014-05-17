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
    <div class="eztc">
        <div class="ezcategorytitle">
        <?php

		$tmpObj = new stdClass();
		$tmpObj->category_id    = '0';
		$tmpObj->created_by     = '0';
		$tmpObj->id    			= '0';
		$itemId     = modLatestBlogsHelper::_getMenuItemId($tmpObj, $params);

		$tagURL 	= EasyBlogRouter::_('index.php?option=com_easyblog&view=tags&layout=tag&id=' . $tag->id . $itemId );
		$tagLink	= '<a href="'.$tagURL.'">'.$tag->title.'</a>';
		echo $tagLink;
		?>
        </div>
        <?php if($params->get('showtcount', true)) : ?>
        <div class="ezpostcount small">
            <?php echo JText::sprintf('MOD_LATESTBLOGS_POST_COUNT', $tagTotalPostCnt);?>
        </div>
        <?php endif; ?>
    </div>
</div>