<?php
/**
 * @version		$Id: item_comments_form.php 1812 2013-01-14 18:45:06Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>

<h3><?php echo JText::_('K2_LEAVE_A_COMMENT') ?></h3>



<form action="<?php echo JURI::root(true); ?>/index.php" method="post" id="comment-form" class="form-validate form-horizontal">
	
	<div class="control-group">
		<label class="control-label" for="commentText"><?php echo JText::_('Your message'); ?> *</label>
		<div class="controls">
			<textarea rows="20" cols="10" class="inputbox" onblur="if(this.value=='') this.value="" onfocus="if(this.value=='<?php echo JText::_(''); ?>') this.value='';" name="commentText" id="commentText"><?php echo JText::_(''); ?></textarea>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="userName"><?php echo JText::_('K2_YOUR_NAME'); ?> *</label>
		<div class="controls">
			<input class="inputbox" type="text" name="userName" id="userName" value=""  />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="commentEmail"><?php echo JText::_('Your email'); ?> *</label>
		<div class="controls">
			<input class="inputbox" type="text" name="commentEmail" id="commentEmail"  />
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="commentURL"><?php echo JText::_('K2_WEBSITE'); ?></label>
		<div class="controls">
			<input class="inputbox" type="text" name="commentURL" id="commentURL" value=""  />
		</div>
	</div>
	
	<?php if($this->params->get('recaptcha') && $this->user->guest): ?>
	<div class="control-group">
			<label class="control-label"><?php echo JText::_('K2_ENTER_THE_TWO_WORDS_YOU_SEE_BELOW'); ?></label>
			<div class="controls">
				<div id="recaptcha"></div>
			</div>
	</div>
	<?php endif; ?>
	
	
	
	<div class="controls">
		<button type="submit" class="button validate">
			<?php echo JText::_('send'); ?>
		</button>
	</div>
	<span id="formLog"></span>

	<input type="hidden" name="option" value="com_k2" />
	<input type="hidden" name="view" value="item" />
	<input type="hidden" name="task" value="comment" />
	<input type="hidden" name="itemID" value="<?php echo JRequest::getInt('id'); ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
