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


$tmpObj = new stdClass();
$tmpObj->category_id    = '0';
$tmpObj->created_by     = $blogger->details->id;
$tmpObj->id    			= '0';

$itemId     = modLatestBlogsHelper::_getMenuItemId($tmpObj, $params);

$posterURL 	= $blogger->details->getProfileLink( $itemId );
$posterLink	= '<a href="'.$posterURL.'">'.$blogger->details->getName().'</a>';

$posterWebsite	= '<a href="'.$blogger->details->url.'" target="_blank">'.$blogger->details->url.'</a>';

?>
<div class="ezb-mod">
	<div class="mod-author-brief">
		<?php if ($params->get('showbavatar', true)) : ?>
		<div class="mod-avatar">
			<img class="avatar" src="<?php echo $blogger->details->getAvatar();?>" alt="<?php echo $blogger->details->getName(); ?>" width="50" />
		</div>
		<?php endif;?>
		<div>
			<div class="mod-author-name"><?php echo $posterLink; ?></div>
			<?php if($params->get('showbcount', true)) : ?>
				<div class="mod-author-post small"><?php echo JText::sprintf('MOD_LATESTBLOGS_POST_COUNT', $blogger->postcount);?></div>
			<?php endif; ?>
		</div>
	</div>

	<?php if(! empty($blogger->details->biography)) : ?>
	<div class="mod-author-bio">
		"<?php echo (JString::strlen($blogger->details->biography) > $params->get( 'biography_length' , 50 ) ) ? JString::substr($blogger->details->biography, 0, $params->get( 'biography_length' , 50 ) ) . '...' : $blogger->details->biography ; ?>"
	</div>
	<?php endif; ?>

	<?php if($params->get('showwebsite', true) && !empty($blogger->details->url) && !($blogger->details->url == 'http://')) : ?>
	<div class="mod-author-website">
		<?php echo $posterWebsite;?>
	</div>
	<?php endif; ?>


	<div class="mod-author-posts">
	<?php
	    if( count( $bloggerPosts ) > 0)
	    {
		    for( $bi = 0; $bi < count($bloggerPosts); $bi++)
		    {
		        $post   = $bloggerPosts[$bi];
				require( JModuleHelper::getLayoutPath('mod_latestblogs', 'default_item') );
		    }
	    }
	    else
	    {
	        echo JText::_('MOD_LATESTBLOGS_NO_POST');
	    }
	?>
	</div>
</div>
