<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}
//get language and direction
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;
?>
<!DOCTYPE html>
<html  lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->error->getCode(); ?> - <?php echo $this->title; ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=yes">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="HandheldFriendly" content="true">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400" type="text/css" />	
	<link rel="stylesheet" href="<?php echo $this->baseurl.'/templates/'.$this->template; ?>/css/error.css" type="text/css" />	
	<link rel="stylesheet" href="<?php echo $this->baseurl.'/templates/'.$this->template; ?>/asset/fonts/awesome/css/font-awesome.css" type="text/css" />	
</head>
<body>
	<div class="page404-header">
		<div class="page404-container">
			<a href="index.php"><img class="img_logo" src="<?php echo JURI::base() . 'templates/' . JFactory::getApplication()->getTemplate();?>/images/logo.png" alt="" /></a>
		</div>
	</div>
	<div class="page404-middle">
		<div class="page404-container">
			<div class="page404-middle-inner">
				<div class="img_number"><img src="<?php echo JURI::base() . 'templates/' . JFactory::getApplication()->getTemplate();?>/images/404/number-404.png" alt="" /></div>
				<div class="img_error"><img src="<?php echo JURI::base() . 'templates/' . JFactory::getApplication()->getTemplate();?>/images/404/error-404.png" alt="" /></div>
				<h4><?php echo JText::_('We’re sorry — something has gone wrong on our end.'); ?></h4>
				<a href="index.php" class="button-404"><?php echo JText::_('Home page'); ?></a>
				<p><?php echo JText::_('If difficulties persist, please contact the System Administrator <br/> of this site and report the error below..'); ?></p>	
			</div>
		</div>
	</div>
	<div class="page404-footer">
		<div class="page404-container">
			<div class="footer-social">
				<div class="yt-socialbt">
					<a data-placement="top" target="_blank" class="sb facebook default" title="" href="http://www.facebook.com/SmartAddons.page">
						<i class="icon-facebook"></i>
					</a>
				</div> 
				<div class="yt-socialbt">
					<a data-placement="top" target="_blank" class="sb twitter default" title="" href="https://twitter.com/#!/smartaddons">
						<i class="icon-twitter"></i>
					</a>
				</div> 
				<div class="yt-socialbt">
					<a data-placement="top" target="_blank" class="sb linkedin default" title="" href="http://www.linkedin.com/in/smartaddons">
						<i class="icon-linkedin"></i>
					</a>
				</div> 
				<div class="yt-socialbt">
					<a data-placement="top" target="_blank" class="sb rss default" title="" href="http://feeds.feedburner.com/smartaddons/joomla-templates">
						<i class="icon-rss"></i>
					</a>
				</div> 
				<div class="yt-socialbt">
					<a data-placement="top" target="_blank" class="sb google-plus default" title="" href="https://plus.google.com/+Smartaddons">
						<i class="icon-google-plus"></i>
					</a>
				</div> 
			</div>
			<div class="footer-copyright">
				 <div class="footer1">
					Copyright &#169; 2014 Sj Icenter. All Rights Reserved. Designed by 
					<a href="http://www.smartaddons.com/" title="Visit SmartAddons!" target="_blank">SmartAddons.Com</a>,
					<a target="_blank" href="http://www.smartaddons.com/joomla/templates/template-showcase">Joomla Templates</a>,
					<a target="_blank" href="http://www.smartaddons.com/wordpress/themes">Wordpress Themes</a>
				</div>
				<div class="footer2"><a href="http://www.joomla.org">Joomla!</a> is Free Software released under the <a href="http://www.gnu.org/licenses/gpl-2.0.html">GNU General Public License.</a></div>
			</div>
		</div>
	</div>
</body>
</html>
