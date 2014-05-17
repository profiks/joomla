<?php
/**
 * @version		$Id: latest_item.php 1618 2012-09-21 11:23:08Z lefteris.kavadas $
 * @package		K2
 * @author		JoomlaWorks http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2012 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

?>
   

<!-- Start K2 Item Layout -->

	<!-- Plugins: BeforeDisplay -->
	<?php echo $this->item->event->BeforeDisplay; ?>

	<!-- K2 Plugins: K2BeforeDisplay -->
	<?php echo $this->item->event->K2BeforeDisplay; ?>

	
  <!-- Plugins: AfterDisplayTitle -->
  <?php echo $this->item->event->AfterDisplayTitle; ?>

  <!-- K2 Plugins: K2AfterDisplayTitle -->
    <?php echo $this->item->event->K2AfterDisplayTitle; ?>
	
<div class="latest-in-main">
	
	<?php if($this->item->params->get('latestItemImage')):
		//check placeholder path is exist or not exist?
		(!empty($this->item->image))? $imgsrc = $this->item->image : $imgsrc = 'http://placehold.it/250x200' ;
	?>
	  <!-- Item Image -->
		<div class="catItemImageBlock pull-left">
            <div class="catItemImage">
                <img src="<?php echo $imgsrc; ?>" alt="<?php if(!empty($this->item->image_caption)) echo K2HelperUtilities::cleanHtml($this->item->image_caption); else echo K2HelperUtilities::cleanHtml($this->item->title); ?>" />
			
				<div class="image-overlay">
					<div class="hover-links clearfix">
						<?php if($this->item->params->get('latestItemAuthor')): ?>
						<!-- Item Author -->
							<div class="catItemAuthor">
								<i class="icon-user"></i>
								
								<?php if(isset($this->item->author->link) && $this->item->author->link): ?>
								<a rel="author" href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
								<?php else: ?>
								<?php echo $this->item->author->name; ?>
								<?php endif; ?>
							</div>
							
						<?php endif; ?>
						<?php if($this->item->params->get('latestItemCategory')): ?>
							<!-- Item category name -->
							<div class="catItemCategory">
								<i class="icon-briefcase"></i>
								<a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
							</div>
						<?php endif; ?>
						<?php if($this->item->params->get('latestItemHits')): ?>
						<!-- Item Hits -->
						<div class="catItemHitsBlock">
							<?php echo JText::_('#Hit: '); ?> <?php echo $this->item->hits; ?> 
							
						</div>
					<?php endif; ?>
					</div>
				</div>
				
			</div>
        		  
		</div>
	 <?php endif; ?>
	 <?php if($this->item->params->get('latestItemTitle')): ?>
          <!-- Item title -->
              <h3 class="catItemTitle">
                <?php if ($this->item->params->get('latestItemTitleLinked')): ?>
                    <a href="<?php echo $this->item->link; ?>">
                    <?php echo $this->item->title; ?>
                </a>
                <?php else: ?>
                <?php echo $this->item->title; ?>
                <?php endif; ?>
              </h3>
   <?php endif; ?>  
   
   	<div class="catItemHeader">
		<dl class="article-info">
			<?php if($this->item->params->get('latestItemAuthor')): ?>
			<!-- Item Author -->
			<dd class="catItemAuthor">
				<i class="icon-user"></i>
				<?php //echo K2HelperUtilities::writtenBy($this->item->author->profile->gender); ?> 
				<?php if(isset($this->item->author->link) && $this->item->author->link): ?>
				<a rel="author" href="<?php echo $this->item->author->link; ?>"><?php echo $this->item->author->name; ?></a>
				<?php else: ?>
				<?php echo $this->item->author->name; ?>
				<?php endif; ?>
			</dd>
			<?php endif; ?>
			
			<?php if($this->item->params->get('latestItemCategory')): ?>
				<!-- Item category name -->
				<dd class="catItemCategory">
					<i class="icon-briefcase"></i>
					<a href="<?php echo $this->item->category->link; ?>"><?php echo $this->item->category->name; ?></a>
				</dd>
			<?php endif; ?>
			<?php if($this->params->get('userItemCommentsAnchor') && ( ($this->params->get('comments') == '2' && !$this->user->guest) || ($this->params->get('comments') == '1')) ): ?>
			<!-- Anchor link to comments below -->
			<div class="tagItemCommentsLink">
				<?php if($this->params->get('userItemDateCreated')): ?>
				<!-- Date created -->
				<span class="tagItemDateCreated">
					<i class="icon-time"></i><?php echo JHTML::_('date', $this->item->created , JText::_('d M Y')); ?>
				</span>
				<?php endif; ?>

				
			</div>
			<?php endif; ?>

			
		</dl>
	</div>  
   
  <!-- Plugins: BeforeDisplayContent -->
  <?php echo $this->item->event->BeforeDisplayContent; ?>

  <!-- K2 Plugins: K2BeforeDisplayContent -->
  <?php echo $this->item->event->K2BeforeDisplayContent; ?>
        
	
	
	  
	  <div class="catItemBody">
		<?php if($this->item->params->get('latestItemIntroText')): ?>
		<!-- Item introtext -->
	  	<?php echo $this->item->introtext; ?>
		<?php endif; ?>
		<?php if ($this->item->params->get('latestItemReadMore')): ?>
		<!-- Item "read more..." link -->
		<div class="item-hover">
			<a class="" data-hover="Read more" href="<?php echo $this->item->link; ?>">
				<span><?php echo JText::_('K2_READ_MORE'); ?> </span>
			</a>
		</div>
		<?php endif; ?>		
	    </div>
	  
		<?php if( $this->item->params->get('latestItemTags')): ?>
  <div class="catItemLinks">
	  <?php if($this->item->params->get('latestItemTags') && count($this->item->tags)): ?>
	  <!-- Item tags -->
	  <div class="catItemTagsBlock">
		  <span></span>
		  <ul class="catItemTags">
		    <?php foreach ($this->item->tags as $tag): ?>
		    <li><a href="<?php echo $tag->link; ?>"><?php echo $tag->name; ?></a></li>
		    <?php endforeach; ?>
		  </ul>
		  <div class="clr"></div>
	  </div>
	  <?php endif; ?>

		<div class="clr"></div>
  </div>
  <?php endif; ?>

		<div class="clr"></div>

	  <!-- Plugins: AfterDisplayContent -->
	  <?php echo $this->item->event->AfterDisplayContent; ?>

	  <!-- K2 Plugins: K2AfterDisplayContent -->
	  <?php echo $this->item->event->K2AfterDisplayContent; ?>

	 

  
	<div class="clr"></div>

  <?php if($this->params->get('latestItemVideo') && !empty($this->item->video)): ?>
  <!-- Item video -->
  <div class="catItemVideoBlock">
  	<h3><?php echo JText::_('K2_RELATED_VIDEO'); ?></h3>
	  <span class="catItemVideo<?php if($this->item->videoType=='embedded'): ?> embedded<?php endif; ?>"><?php echo $this->item->video; ?></span>
  </div>
  <?php endif; ?>

	

	<div class="clr"></div>


  <!-- Plugins: AfterDisplay -->
  <?php echo $this->item->event->AfterDisplay; ?>

  <!-- K2 Plugins: K2AfterDisplay -->
  <?php echo $this->item->event->K2AfterDisplay; ?>

</div>
<div class="latest-in-right">
    
    
    <?php if($this->item->params->get('catItemCommentsAnchor') && ( ($this->item->params->get('comments') == '2' && !$this->user->guest) || ($this->item->params->get('comments') == '1')) ): ?>
		<!-- Anchor link to comments below -->
	<dd class="catItemCommentsLink">
		<i class="icon-comments"></i>
		<?php if(!empty($this->item->event->K2CommentsCounter)): ?>
			<!-- K2 Plugins: K2CommentsCounter -->
			<?php echo $this->item->event->K2CommentsCounter; ?>
		<?php else: ?>
			<a href="<?php echo $this->item->link; ?>#itemCommentsAnchor" title="<?php echo (int)$this->item->numOfComments > 1?JText::_('COMMENTS_LABEL'):JText::_('COMMENT_LABEL') ?>">
				<?php echo JText::_('K2_COMMENTS').": "; echo $this->item->numOfComments; ?> 
			</a>
		<?php endif; ?>
	</dd>
	<?php endif; ?>
    
    <div class="catItemHitsBlock">
	     <?php echo $this->item->hits; ?> <?php echo JText::_('view (s) '); ?> 
    
    </div>
    


</div>
<!-- End K2 Item Layout -->
