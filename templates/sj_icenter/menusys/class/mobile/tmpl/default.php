<?php
/** 
 * YouTech menu template file.
 * 
 * @author The YouTech JSC
 * @package menusys
 * @filesource default.php
 * @license Copyright (c) 2011 The YouTech JSC. All Rights Reserved.
 * @tutorial http://www.smartaddons.com
 */
global $yt;
?>
<?php if($yt->getParam('responsive', 1)): ?>
<div id="yt-responivemenu" class="yt-resmenu menu-<?php echo $yt->getParam('responsiveMenu'); ?>">
<?php
if ($this->isRoot()){
	if($yt->getParam('responsiveMenu') == 'selectbox'){
		echo "<select id=\"yt_resmenu_selectbox\" name=\"menu\" onchange=\"MobileRedirectUrl()\">";
		if($this->haveChild()){
			$idx = 0;
			foreach($this->getChild() as $child){
				++$idx;
				$child->getContent('selectbox');
			}
		}
		echo "</select>";
		?>
		<script type="text/javascript">
			function MobileRedirectUrl(){
			  window.location.href = document.getElementById("yt_resmenu_selectbox").value;
			}
		</script>
		<?php
	}elseif($yt->getParam('responsiveMenu')=='collapse'){
	?>
	<button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar collapsed" type="button">
		<i class="icon-align-justify"></i>
    </button>
    <div id="yt_resmenu_collapse" class="nav-collapse collapse" style="height: 0px;">
        <ul class="nav resmenu">
	<?php
		if($this->haveChild()){
			$idx = 0;
			foreach($this->getChild() as $child){
				++$idx;
				$child->getContent('collapse');
			}
		}
	?>
		</ul>
	</div>
	<script type="text/javascript">
		jQuery(window).load(function(){
			jQuery('#yt_resmenu_collapse .haveChild .menuress-toggle').css('height', jQuery('#yt_resmenu_collapse .haveChild > a').outerHeight());

			jQuery('#yt_resmenu_collapse .haveChild > .res-wrapnav').each(function(){
				if(jQuery(this).parent().hasClass('open')){
					jQuery(this).css('height', jQuery(this).children('ul').height());
				}
			});
			jQuery('#yt_resmenu_collapse .haveChild .menuress-toggle').click(function(){
				if(jQuery(this).parent().hasClass('open')){
					jQuery(this).parent().removeClass('open');
					jQuery(this).parent().children('.res-wrapnav').css('height', '0px');
				}else{
					jQuery(this).parent().addClass('open');
					jQuery(this).parent().children('.res-wrapnav').css('height', jQuery(this).parent().children('.res-wrapnav').children('ul').height());
				}
			});
		});
	</script>
	<?php
	}elseif($yt->getParam('responsiveMenu')=='sidebar'){ ?>
	<button class="btn btn-navbar yt-resmenu-sidebar" type="button">
	    <i class="icon-list-alt"></i>
    </button>
    <div id="yt_resmenu_sidebar">
        <ul class="nav resmenu">
	<?php
		if($this->haveChild()){
			$idx = 0;
			foreach($this->getChild() as $child){
				++$idx;
				$child->getContent('sidebar');
			}
		}
	?>
		</ul>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			if($('.yt-off-sideresmenu .yt-sideresmenu')){
				$('.yt-off-sideresmenu .yt-sideresmenu').html($('#yt_resmenu_sidebar').html());
			}
			function openSidebar() {
				$("#yt_wrapper,#bd").addClass('isOpen');
			}
			function closeSidebar() {
				$("#yt_wrapper,#bd").removeClass('isOpen');
			}

			  $('#yt_wrapper').on('click', function(e) {
				if($('#yt_wrapper,#bd').hasClass('isOpen')) {
				  e.preventDefault();
				  closeSidebar();
				  
				}
			  });

			$('.yt-resmenu-sidebar').on('click', function(e) {
				e.stopPropagation();
				e.preventDefault();
				//alert('vao day');
				if($('#yt_wrapper,#bd').hasClass('isOpen')) {
				  closeSidebar();
				} else {
				  openSidebar();
				}

			});

		});
	</script>
	<?php
	}else{
		echo 'Please select responsive menu in administrator!';
	}


}
?>
</div>
<?php endif ?>