<?php
class PHPmathpublisher_Units_Reader { //reads string
	private $in;
	private $pos = 0;
	
	function __construct ($in) {
		$this->in = $in;
	}
	function getChar () {
		if ($this->pos >=strlen ($this->in)) return false;
		$char = substr ($this->in, $this->pos, 1);
		$this->pos++;
		return $char;
	}
	function getPos () {
		return $this->pos;
	}
	function pushBackChar () {
		$this->pos--;
	}
	function string () {
		return $this->in;
	}
}
?>