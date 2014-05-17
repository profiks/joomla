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
<div class="ezb-mod ezblog-latestpost<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<?php
	if( $filterType == '2' )
	{
	    require( JModuleHelper::getLayoutPath('mod_latestblogs', 'default_category') );
	}
	else if ($filterType == '3')
	{
	    require( JModuleHelper::getLayoutPath('mod_latestblogs', 'default_tag') );
	}
	else if ($filterType == '4')
	{
		require( JModuleHelper::getLayoutPath('mod_latestblogs', 'default_team') );
	}
	?>

	<!-- Entries -->
	<?php if( $posts ){ ?>
	<div class="ezb-mod">
		<?php foreach( $posts as $ditem ){ ?>
			
			<?php if( $filterType == 1 || $filterType == 5 ){ ?>
				<?php
				$blogger		= $ditem;
				$bloggerPosts	= $ditem->posts;

				require( JModuleHelper::getLayoutPath('mod_latestblogs', 'default_blogger') );
				?>
			<?php } else { ?>
				<?php
					$post   = $ditem;
					require( JModuleHelper::getLayoutPath('mod_latestblogs', 'default_item' ) );
				?>
			<?php } ?>

		<?php } ?>
	</div>

	<?php } else { ?>
		<?php if( $filterType == 0 || $filterType == 2 ){ ?>
			<?php echo JText::_('MOD_LATESTBLOGS_NO_POST'); ?>
		<?php } else if( $filterType == 1 ){ ?>
			<?php echo JText::_('MOD_LATESTBLOGS_NO_BLOGGER');?>
		<?php } ?>
	<?php } ?>
</div>
