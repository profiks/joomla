<?php 
/*
 * ------------------------------------------------------------------------
 * Copyright (C) 2009 - 2012 The YouTech JSC. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: The YouTech JSC
 * Websites: http://www.smartaddons.com - http://www.cmsportal.net
 * ------------------------------------------------------------------------
*/
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="cpanel_wrapper" style="direction:ltr">
    <div class="accordion row-fluid" id="ytcpanel_accordion">
        <div class="cpanel-head">
			Template Settings
			
			<div class="cpanel-reset">
				<a class="btn btn-info" href="#" onclick="javascript: onCPResetDefault(TMPL_COOKIE);" ><i class="fa fa-refresh fa-spin"></i> Reset</a>
			</div>
		</div>
    	<!--Body-->
        <div class="accordion-group cpnel-theme">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#ytcpanel_accordion" href="#ytitem_1">
				<i class="fa fa-plus-square-o"></i>Theme <span class="label label-warning">New</span>
				</a>
				
            </div>
            <div id="ytitem_1" class="accordion-theme collapse in">
                <div class="accordion-inner clearfix">
                    <!-- Color -->
                    <h4 class="clear" style="padding:0;"><span>Color</span></h4>
                    <div class="fs-desc">For each color, the params below will give default values</div>
                    <div class="cpanel-theme-color">
                        <div class="inner clearfix">
                            <span title="Blue"    class="theme-color blue<?php echo ($templateColor=='blue')?' active':'';?>">Blue</span>
							<span title="Cyan"    class="theme-color cyan<?php echo ($templateColor=='cyan')?' active':'';?>">Cyan</span>
							<span title="Green"    class="theme-color green<?php echo ($templateColor=='green')?' active':'';?>">Green</span>
							<span title="Orange"    class="theme-color orange<?php echo ($templateColor=='orange')?' active':'';?>">Orange</span>
                        </div>
                    </div>
                    <!-- Body -->
                    <h4 class="clear"><span>Body</span></h4>
                    <div class="span4 first cp-item body-backgroud-color">
                        <span>Background Color</span>
                        <div class="inner">
                        	<input type="text" value="<?php echo $yt->getParam('bgcolor');?>" autocomplete="off" size="7" class="color-picker miniColors" name="ytcpanel_bgcolor" maxlength="7">
                        </div>
                    </div>
                    <div class="span4 cp-item link-color">
                        <span>Link Color</span>
                        <div class="inner">
                        	<input type="text" value="<?php echo $yt->getParam('linkcolor');?>" autocomplete="off" size="7" class="color-picker miniColors" name="ytcpanel_linkcolor" maxlength="7">
                        </div>
                    </div>
                    <div class="span4 cp-item text-color">
                        <span>Text Color</span>
                        <div class="inner">
                        	<input type="text" value="<?php echo $yt->getParam('textcolor'); ?>" autocomplete="off" size="7" class="color-picker miniColors" name="ytcpanel_textcolor" maxlength="7">
                        </div>
                    </div>                
                 
					<div class="span12 cp-item body-backgroud-image">
							<h4 class="clear"><span>Background</span></h4>
							<span>Patterns for Layout Style: Boxed</span>
							<div class="inner">
								<?php
								$path = JPATH_ROOT.'/templates/'.$yt->template.'/images/pattern/body';
								$files = JFolder::files($path, '.'); 
								foreach($files as $file) {
									$file = JFile::stripExt($file); ?>
									<a href="#" title="<?php echo $file; ?>" class="pattern <?php echo $file; ?><?php echo ($yt->getParam('bgimage')==$file)?' active':''?>"><?php echo $file; ?></a>
									<?php
								}
								?>
								<input type="hidden" name="ytcpanel_bgimage" value="<?php echo $yt->getParam('bgimage'); ?>"/>
							</div>
					</div>
				</div>
            </div>
        </div>
        
        <!-- Layouts -->
        <div class="accordion-group cpanel-layout">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#ytcpanel_accordion" href="#ytitem_2">
                Layout <i class="fa fa-plus-square-o"></i>
                </a>
            </div>
            <div id="ytitem_2" class="accordion-body collapse">
                <div class="accordion-inner clearfix">
                	
                    <div class="span6 first cp-item footer-backgroud-color">
                        <span>Select layout</span>
                        <div class="inner">
                        	<select onchange="javascript: onCPApply();" name="ytcpanel_templateLayout" class="cp_select">
                            	<?php 
								$path = JPATH_ROOT.'/templates/'.$yt->template.'/layouts';
								$files = JFolder::files($path, '', false, false, array('variations.xml')); 
								foreach($files as $file) {
									$file = JFile::stripExt($file); ?>
                                    <option value="<?php echo $file; ?>"<?php echo ($layout==$file)?' selected="selected"':'';?>><?php echo $file; ?></option>
									<?php
								}
								?>
                            </select>
                        </div>
                    </div>
					
					<!-- Layout Style -->
					<div class="span6 cp-item typeLayout">
						<span>Layout Style</span>
						<div class="inner">
							<select onchange="javascript: onCPApply();" name="ytcpanel_typelayout" class="cp_select">
								<option value="wide"<?php echo ($typelayout=='wide')?' selected="selected"':'';?>>Wide</option>
								<option value="boxed"<?php echo ($typelayout=='boxed')?' selected="selected"':'';?>>Boxed</option>
							</select>
						</div>
					</div>
					
					
					
                </div>
            </div>
        </div>
		
		<!-- Features -->
        <div class="accordion-group cpanel-features">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#ytcpanel_accordion" href="#ytitem_3">
                Menu <i class="fa fa-plus-square-o"></i>
                </a>
            </div>
            <div id="ytitem_3" class="accordion-body collapse">
                <div class="accordion-inner clearfix">
                    <!-- Mainmenu -->
                    <div class="span6 first cp-item body-fontfamily">
                        <span>Select menu </span>
                        <div class="inner">
                            <select onchange="javascript: onCPApply();" name="ytcpanel_menustyle" class="cp_select">
                                <option value="mega"<?php echo ($menustyle=='mega')?' selected="selected"':'';?>>Mega Menu</option>
                                <option value="moo"<?php echo ($menustyle=='moo')?' selected="selected"':'';?>>Moo Menu</option>
                                <option value="basic"<?php echo ($menustyle=='basic')?' selected="selected"':'';?>>CSS Menu</option>
                                
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
        <!-- Typography -->
        <div class="accordion-group cpanel-typography">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#ytcpanel_accordion" href="#ytitem_4">
                Typography <i class="fa fa-plus-square-o"></i>
                </a>
            </div>
            <div id="ytitem_4" class="accordion-typo collapse">
                <div class="accordion-inner clearfix">
                	<!-- Google font -->
                    <div class="span6 first cp-item">
                        <span>Google Font</span>
                        <div class="inner">
                        	<?php 
							$googleFont = array(
										'None'=>'none',
										'Open Sans' => 'Open Sans:300,400',
										'Titillium Web'=>'Titillium Web',
										'Anton'=>'Anton',
										'BenchNine'=>'BenchNine',
										'Droid Sans'=>'Droid Sans',
										'Droid Serif'=>'Droid Serif',
										'PT Sans'=>'PT Sans',
										'Vollkorn'=>'Vollkorn',
										'Ubuntu'=>'Ubuntu',
										'Neucha'=>'Neucha',
										'Cuprum'=>'Cuprum',
										'Archivo Narrow'=>'Archivo Narrow'
							);
							?>
                            <select onchange="javascript: onCPApply();" name="ytcpanel_googleWebFont" class="cp_select">
							<?php foreach($googleFont as $k=>$v):?>
                                <option value="<?php echo $v; ?>"<?php echo ($googleWebFont==$v)?' selected="selected"':'';?>><?php echo $k; ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- Body font-size -->
                    <div class="span6 cp-item">
                        <span>Body Font-size</span>
                        <div class="inner">
                            <?php 
							$fontfamily = array(
										 '10px'=>'10px',
										 '11px'=>'11px',
										 'Default'=>'12px',
										 '13px'=>'13px',
										 '14px'=>'14px',
										 '15px'=>'15px',
										 '16px'=>'16px',
										 '17px'=>'17px',
										 '18px'=>'18px'
							);
							?>
                            <select onchange="javascript: onCPApply();" name="ytcpanel_fontSize" class="cp_select">
							<?php foreach($fontfamily as $k=>$v):?>
                                <option value="<?php echo $v; ?>"<?php echo ($fontSize==$v)?' selected="selected"':'';?>><?php echo $k; ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- Body font-family -->
                    <div class="span6 first cp-item body-fontfamily">
                        <span>Body Font-family</span>
                        <div class="inner">
                        <?php 
						$fontfamily = array(
									 'Arial'=>'arial',
									 'Arial Black'=>'arial-black',
									 'Courier New'=>'courier',
									 'Georgia'=>'georgia',
									 'Impact'=>'impact',
									 'Lucida Console'=>'lucida-console',
									 'Lucida Grande'=>'lucida-grande',
									 'Palatino'=>'palatino',
									 'Tahoma'=>'tahoma',
									 'Times New Roman'=>'times',
									 'Trebuchet'=>'trebuchet',
									 'Verdana'=>'verdana'
						);
						?>
                            <select onchange="javascript: onCPApply();" name="ytcpanel_fontName" class="cp_select">
							<?php foreach($fontfamily as $k=>$v):?>
                                <option value="<?php echo $v; ?>"<?php echo ($fontName==$v)?' selected="selected"':'';?>><?php echo $k; ?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
	
  
    <div id="cpanel_btn" class="isDown">
        <i class="fa fa-wrench"></i>
    </div>
</div>