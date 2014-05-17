<?php
/*
 * @package		mod_easyblogwelcome
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

if($isLogged && !empty($blogger->id))
{
?>
<div class="ezb-mod mod_easyblogwelcome<?php echo $params->get( 'moduleclass_sfx' ) ?>">
	<?php if( $params->get( 'display_avatar')){?>
    <div class="mod-profile">
        <?php if( !$useracl->rules->add_entry && !$config->get('main_nonblogger_profile') ) : ?>
        <a href="javascript:void(0);" class="mod-avatar">
        <?php else: ?>
		<a href="<?php echo $blogger->getProfileLink();?>" class="mod-avatar">
		<?php endif; ?>
            <img src="<?php echo $blogger->getAvatar();?>" class="avatar" width="50" height="50" />
        </a>

        <?php if( !$useracl->rules->add_entry && !$config->get('main_nonblogger_profile') ) : ?>
        <a href="javascript:void(0);">
        <?php else: ?>
		<a href="<?php echo $blogger->getProfileLink();?>">
		<?php endif; ?>
			<b><?php echo $blogger->getName();?></b>
		</a>

		<br />

        <?php if( $useracl->rules->add_entry ){ ?>
        <a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=dashboard&layout=profile' . $useMenuItem);?>" class="small"><?php echo JText::_( 'MOD_EASYBLOGWELCOME_SETTINGS');?></a>
        <?php } ?>
    </div>
    <?php } ?>
	<?php if( $useracl->rules->add_entry ){ ?>

		<?php if( $config->get( 'main_microblog' ) ){ ?>
		<div class="mod-option ezquick">
			<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=dashboard&layout=microblog' . $useMenuItem);?>"><?php echo JText::_( 'MOD_EASYBLOGWELCOME_QUICK_SHARE');?></a>
		</div>
		<?php } ?>

		<div class="mod-option ezwrite">
			<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=dashboard&layout=write' . $useMenuItem);?>"><?php echo JText::_( 'MOD_EASYBLOGWELCOME_WRITE_NEW');?></a>
		</div>
		<div class="mod-option ezmyblog">
			<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=dashboard&layout=entries' . $useMenuItem);?>"><?php echo JText::_( 'MOD_EASYBLOGWELCOME_MYBLOGS');?> </a>
		</div>
		<div class="mod-option ezmydrafts">
			<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=dashboard&layout=drafts' . $useMenuItem);?>"><?php echo JText::_( 'MOD_EASYBLOGWELCOME_MYDRAFTS');?></a>
		</div>

		<?php if( ( $config->get( 'comment_easyblog' ) && $config->get( 'main_comment_multiple' ) ) && $config->get( 'main_comment' ) ){ ?>
		<div class="mod-option ezmycomment">
			<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=dashboard&layout=comments' . $useMenuItem);?>"><?php echo JText::_( 'MOD_EASYBLOGWELCOME_MYCOMMENTS');?></a>
		</div>
		<?php } ?>

	<?php } ?>
    <div class="mod-option ezmysubscription">
		<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=subscription' . $useMenuItem);?>"><?php echo JText::_( 'MOD_EASYBLOGWELCOME_MYSUBSCRIPTION');?></a>
	</div>

	<?php if( $params->get( 'enable_login') ){ ?>
	<div class="mod-option ezlogout">
		<a href="<?php echo JRoute::_('index.php?option='.$comUserOption.'&task='.$tasklogout. '&' . JSession::getFormToken() . '=1&return='.$return);?>"><?php echo JText::_( 'MOD_EASYBLOGWELCOME_LOGOUT');?></a>
	</div>
	<?php } ?>
</div>
<?php
}
else if( $params->get( 'enable_login') )
{
?>
<div class="ezb-mod mod_easyblogwelcome<?php echo $params->get( 'moduleclass_sfx' ) ?>">

<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	<?php echo $params->get('pretext'); ?>
	<p id="form-login-username" class="full">
		<label for="modlgn_username"><?php echo JText::_('MOD_EASYBLOGWELCOME_USERNAME') ?></label>
		<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" placeholder="User name" size="18" />
	</p>
	<p id="form-login-password" class="full">
		<label for="modlgn_passwd"><?php echo JText::_('MOD_EASYBLOGWELCOME_PASSWORD') ?></label>
		<input id="modlgn_passwd" type="password" name="<?php echo $InputPassword; ?>" class="inputbox" size="18"  placeholder="******" alt="password" />
	</p>

<?php
	if(JPluginHelper::isEnabled('system', 'remember'))
	{
?>
	<p id="form-login-remember">
		<input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" />
		<label for="modlgn_remember"><?php echo JText::_('MOD_EASYBLOGWELCOME_REMEMBER_ME') ?></label>
	</p>
<?php
	}
?>
	<div>
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('MOD_EASYBLOGWELCOME_LOGIN') ?>" />
	</div>

	<ul>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option='.$comUserOption.'&view=reset' ); ?>">
			<?php echo JText::_('MOD_EASYBLOGWELCOME_FORGOT_YOUR_PASSWORD'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option='.$comUserOption.'&view=remind' ); ?>">
			<?php echo JText::_('MOD_EASYBLOGWELCOME_FORGOT_YOUR_USERNAME'); ?></a>
		</li>
		<?php
		$usersConfig = JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option='.$comUserOption.'&view='.$viewRegister ); ?>">
				<?php echo JText::_('MOD_EASYBLOGWELCOME_REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>

	<?php echo $params->get('posttext'); ?>

	<input type="hidden" name="option" value="<?php echo $comUserOption; ?>" />
	<input type="hidden" name="task" value="<?php echo $tasklogin; ?>" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
<?php
}
?>
