<?php
defined('JPATH_BASE') or die;

class JFormFieldYtless2css extends JFormField{

	protected $type = 'ytless2css';

	protected function getInput() {
		$url = str_replace("/administrator", "", JURI::base()).'?less2css=all&compile=server';
		$html  = '<a id="'.$this->id.'" name="'.$this->name.'" href="javascript:void(0)" class="btn less2css"><span class="icon-disk"></span> Convert</a>';
		$html .= '<span style="padding: 0 8px" id="less2css_result"></span>';
		return $html;
	}
}
