<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE.'/components/com_users/helpers/route.php';

JHtml::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');

?>
	<?php if ($params->get('pretext')) : ?>
		<div class="pretext">
			<p><?php echo $params->get('pretext'); ?></p>
		</div>
	<?php endif; ?>

	<ul class="yt-loginform menu">
		<li class="yt-login">
			<a class="login-switch" data-toggle="modal" href="#myLogin" title="<?php JText::_('Login');?>">
			   <?php echo JText::_('Login'); ?>
			</a>
			<div id="myLogin" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<h3 class="title"><?php echo JText::_('MOD_LOGIN'); ?></h3>
				<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" class="form-inline">
					<div class="userdata">
						<div id="form-login-username" class="control-group">					
							<input id="modlgn-username" type="text" name="username" class="inputbox"  size="18" />
						</div>
						<div id="form-login-password" class="control-group">						
							<input id="modlgn-passwd" type="password" name="password" class="inputbox" size="18"  />
						</div>					
						<div id="form-login-remember" class="control-group ">
							<input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="1"/>
							<label for="modlgn-remember" class="control-label"><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label> 
						</div>
						<div id="form-login-submit" class="control-group">
							<div class="controls">
								<button type="submit" tabindex="3" name="Submit" class="button"><?php echo JText::_('JLOGIN') ?></button>
							</div>
						</div>
						
						<input type="hidden" name="option" value="com_users" />
						<input type="hidden" name="task" value="user.login" />
						<input type="hidden" name="return" value="<?php echo $return; ?>" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
					<ul class="listinline listlogin">
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
							<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?></a>
						</li>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
							<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?></a>
						</li>
						
					</ul>					
				</form>
				<a href="#" class="modal-close" data-dismiss="modal" aria-hidden="true">x</a>			
			</div>
		</li>
		<li class="yt-register">
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		 
			<a class="register-switch text-font" href="<?php echo JRoute::_("index.php?option=com_users&view=registration");?>" 
			onclick="showBox('yt_register_box','jform_name',this, window.event || event);return false;">
				<?php echo JText::_('JREGISTER');?>
			</a>
		<?php endif; ?>	
		</li>
	</ul>

<?php if ($params->get('posttext')) : ?>
		<div class="posttext">
			<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>