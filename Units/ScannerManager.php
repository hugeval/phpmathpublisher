<?php
class PHPmathpublisher_Units_ScannerManager {
	private $levelScanner;
	private $repeatScan = false;
	
	private function processTextObj ($arr, $i) {
		$obj = &$arr [$i];
		$text = $obj->text;
		if ((! (strpos ($text, '^') === false) && strlen ($text) != 1) ||
				(! (strpos ($text, '_') === false) && strlen ($text) != 1) ||
				(! (strpos ($text, '\\') === false)) && strlen ($text) != 1) 
		{
			$this->levelScanner = new PHPmathpublisher_Units_LevelScanner ($text);
			unset ($obj);
			unset ($arr [$i]);
			$this->levelScanner->doScan (&$arr, $i);
			$this->repeatScan = true;
		}
		$this->repeatScan = false;
	}
	private function processMathObj ($obj) {
		$nodes = &$obj->nodes;
		$this->processArray (&$nodes);
		if (count ($nodes) == 1) {
			$this->repeatScan = false;
			return;
		}
	}
	private function processArray ($arr) {
		for ($i = 0; $i < count ($arr); $i++) {
			$item = &$arr [$i];
			$class = get_class ($item);
			if (strcmp ($class, 'PHPmathpublisher_Units_ExpressionText') == 0) {
				$this->processTextObj (&$arr, $i);
			} else if (strcmp ($class, 'PHPmathpublisher_Units_ExpressionMath') == 0) {
				$this->processMathObj (&$item);
			} else {
				die ('unknown error');
			}
		}
	}
	public function doManage ($userIn) {
		$this->levelScanner = new PHPmathpublisher_Units_LevelScanner ($userIn);
		$i = 0;
		$this->levelScanner->doScan (&$this->levelScanner->nodes, $i);
		$ret = $this->levelScanner->nodes;
		unset ($this->levelScanner);
		do {
			$this->processArray (&$ret);
		} while ($this->repeatScan);
		print_r ($ret);
		return $ret;
	}
}
?>