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
<?php
if ( $this->canAccess() ){
	$haveChild = $this->haveChild(); ?>
    <?php echo $this->getLinkInMobile($this->get('level',1)); ?>
    <?php
        if($haveChild){
            $cidx = 0;
            foreach($this->getChild() as $child){
                ++$cidx;
                $child->getContent('selectbox');
            }
        }
    ?>
<?php
} ?>