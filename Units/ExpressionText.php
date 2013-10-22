<?php
class PHPmathpublisher_Units_ExpressionText extends  PHPmathpublisher_Units_Expression {
	public $text;
	
	function __construct ($exp) {
		$this->text = $exp;
	}
	function design ($size) {
		$this->image = PHPmathpublisher_Units_Publisher::declareMath ($this->text, $size);
		$this->base_vertical = imagesy ($this->image) / 2;
	}
}
?>