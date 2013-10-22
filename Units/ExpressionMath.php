<?php
class PHPmathpublisher_Units_ExpressionMath extends  PHPmathpublisher_Units_Expression {
	public $nodes;
	
	function __construct ($exp) {
		$this->nodes = $exp;
	}
	function design ($size) {
		switch (count ($this->nodes)) {
			case 1:
				$this->nodes [0]->design ($size);
				$this->image = $this->nodes [0]->image;
				$this->base_vertical = $this->nodes [0]->base_vertical;
				break;
			case 2:
				switch ($this->nodes [0]->text) {
					case 'acute': 
					case 'breve':
					case 'dot':
					case 'ddot':
					case 'dddot':
					case 'ddddot':
					case 'grave':
					case 'hat':
					case 'overarc':
					case 'overleftarrow':
					case 'overleftharpoon':
					case 'overleftharp':
					case 'overleftharpup':
					case 'overleftharpoonup':
					case 'overleftrightarrow':
					case 'overrightarrow':
					case 'overrightharp':
					case 'overrightharpoon':
					case 'overrightharpup':
					case 'overrightharpoonup':
					case 'tilde':
					case 'widehat':
						$this->designScript ($size, $this->nodes [0]->text, 'over');
						break;
					case 'b':
					case 'd':
					case 'dd':
					case 'ddd':
					case 'dddd':
					case 'underarc':
					case 'underleftarrow':
					case 'underleftharp':
					case 'underleftharpdown':
					case 'underleftharpoon':
					case 'underleftharpoondown':
					case 'underleftrightarrow':
					case 'underrightarrow':
					case 'underrightharp':
					case 'underrightharpdown':
					case 'underrightharpoon':
					case 'underrightharpoondown':
					case 'undertilde':
						$this->designScript ($size, $this->nodes [0]->text, 'under');
						break;
					case 'vec':
						$this->design_vector ($size);
						break;
					case 'bar':
					case 'overline':
						$this->design_overline ($size);
						break;
					case 'not':
						$this->design_not ($size);
						break;
					case 'sqrt':
						$this->design_sqrt ($size);
						break;
					case 'underline':
						$this->design_underline ($size);
						break;
					default:
						$this->design_expression ($size);
						break;
				}
				break;
			case 3:
				if ($this->nodes [0]->text == "lim") {
					$this->design_limit($size);
				} elseif ($this->nodes [0]->text == "root") {
					$this->design_root ($size);
				} elseif ($this->nodes [0]->text == 'frac') {
					$this->design_fraction ($size);
				} elseif ($this->nodes [0]->text == 'over') {
					$this->design_over ($size);
				} elseif ($this->nodes [0]->text == 'under') {
					$this->design_under ($size);
				} else {
					switch($this->nodes [1]->text) {
						case '^':
							$this->design_exponent ($size);
							break;
						case '_':
							$this->design_index ($size);
							break;
						default:
							$this->design_expression ($size);
							break;
					}
				}
				break;
			case 4:
				switch ($this->nodes [0]->text) {
					case 'int':
						$this->design_bigoperator ($size, '_integral');
						break;
					case 'doubleint':
					case 'iint':
						$this->design_bigoperator ($size, '_dintegral');
						break;
					case 'tripleint':
					case 'iiint':
						$this->design_bigoperator ($size, '_tintegral');
						break;
					case 'oint':
						$this->design_bigoperator ($size, '_ointegral');
						break;
					case 'sum':
						$this->design_bigoperator ($size, '_sum');
						break;
					case 'prod':
						$this->design_bigoperator ($size, '_prod');
						break;
					case 'corprod':
						$this->design_bigoperator ($size, '_corprod');
						break;
					case 'bigcap':
					case 'cap':
						$this->design_bigoperator ($size, '_intersection');
						break;
					case 'bigcup':
					case 'cup':
						$this->design_bigoperator($size, '_reunion');
						break;
					case 'delim':
						$this->design_delimiter ($size);
						break;
					case 'matrix':
						$this->design_matrix ($size);
						break;
					case 'tabular':
						$this->design_tabular ($size);
						break;
					default:
						$this->design_expression ($size);
						break;
				}
				break;
			default:
				$this->design_expression ($size);
				break;
		}
	}

	function design_expression ($size) {
		$width = 1;
		$height = 1;
		$over = 1;
		$under = 1;
		for ($i = 0; $i < count ($this->nodes); $i++) {
			if ($this->nodes [$i]->text != '(' && $this->nodes [$i]->text != ')') {
				$this->nodes [$i]->design ($size);
				$img [$i] = $this->nodes [$i]->image;
				$base [$i] = $this->nodes [$i]->base_vertical;
				$over = max ($base [$i], $over);
				$under = max (imagesy ($img [$i]) - $base [$i], $under);
			}
		}
		$height = $over + $under;
		$paro = PHPmathpublisher_Units_Publisher::parenthese (max ($over, $under) * 2, "(");
		$parf = PHPmathpublisher_Units_Publisher::parenthese (max ($over, $under) * 2, ")");
		for ($i = 0; $i < count ($this->nodes); $i++) {
			if (! isset ($img [$i])) {
				if ($this->nodes [$i]->text == "(") $img [$i] = $paro;
				else $img [$i] = $parf;
				$over = max (imagesy ($img [$i]) / 2, $over);
				$base [$i] = imagesy ($img [$i]) / 2;
				$under = max (imagesy ($img [$i]) - $base [$i], $under);
				$height = max (imagesy ($img [$i]), $height);
			}
			$width += imagesx ($img [$i]);
		}
		$this->base_vertical = $over;
		$result = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($result, 0, 0, 0);
		$blanc = imageColorAllocate ($result, 255, 255, 255);
		$blanc = imagecolortransparent ($result, $blanc);
		imageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
		$pos = 0;
		for ($i = 0; $i < count ($img); $i++) {
			if (isset ($img [$i])) {
				imageCopy ($result, $img [$i], $pos, $over - $base [$i], 0, 0, imagesx ($img [$i]), imagesy ($img [$i]));
				$pos += imagesx ($img [$i]);
			}
		}
		$this->image = $result;
	}
	function design_fraction ($size) {
		$this->nodes [1]->design ($size * 0.9);
		$img1 = $this->nodes [1]->image;
		$base1 = $this->nodes [1]->base_vertical;
		$this->nodes [2]->design ($size * 0.9);
		$img2 = $this->nodes [2]->image;
		$base2 = $this->nodes [2]->base_vertical;
		$height1 = imagesy ($img1);
		$height2 = imagesy ($img2);
		$width1 = imagesx ($img1);
		$width2 = imagesx ($img2);
		$width = max ($width1, $width2);
		$height = $height1 + $height2 + 4;
		$result = imageCreate (max ($width + 5, 1), max ($height, 1));
		$noir = imageColorAllocate ($result, 0, 0, 0);
		$blanc = imageColorAllocate ($result, 255, 255, 255);
		$blanc = imagecolortransparent ($result, $blanc);
		$this->base_vertical = $height1 + 2;
		imageFilledRectangle ($result, 0, 0,$width + 4, $height - 1, $blanc);
		imageCopy ($result, $img1, ($width - $width1) / 2, 0, 0, 0, $width1, $height1);
		imageline ($result, 0,$this->base_vertical, $width, $this->base_vertical, $noir);
		imageCopy ($result, $img2, ($width - $width2)/2,$height1+4, 0, 0,$width2,$height2);
		$this->image = $result;
	}
	function design_exponent ($size) {
		$this->nodes [0]->design ($size);
		$img1 = $this->nodes [0]->image;
		$base1 = $this->nodes [0]->base_vertical;
		$this->nodes [2]->design ($size * 0.8);
		$img2 = $this->nodes [2]->image;
		$base2 = $this->nodes [2]->base_vertical;
		$height1 = imagesy ($img1);
		$height2 = imagesy ($img2);
		$width1 = imagesx ($img1);
		$width2 = imagesx ($img2);
		$width = $width1 + $width2;
		if ($height1 >= $height2) {
			$height = ceil ($height2 / 2 + $height1);
			$this->base_vertical = $height2 / 2 + $base1;
			$result = imageCreate (max ($width, 1), max ($height, 1));
			$noir = imageColorAllocate ($result, 0, 0, 0);
			$blanc = imageColorAllocate ($result, 255, 255, 255);
			$blanc = imagecolortransparent ($result, $blanc);
			imageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
			imageCopy ($result, $img1, 0, ceil ($height2 / 2), 0, 0, $width1, $height1);
			imageCopy ($result, $img2, $width1, 0, 0, 0, $width2, $height2);
		} else {
			$height = ceil ($height1 / 2 + $height2);
			$this->base_vertical = $height2 - $base1 + $height1 / 2;
			$result = imageCreate (max ($width, 1), max ($height, 1));
			$noir = imageColorAllocate ($result, 0, 0, 0);
			$blanc = imageColorAllocate ($result, 255, 255, 255);
			$blanc = imagecolortransparent ($result, $blanc);
			imageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
			imageCopy ($result, $img1, 0, ceil ($height2 - $height1 / 2), 0, 0, $width1, $height1);
			imageCopy ($result, $img2, $width1, 0, 0, 0, $width2, $height2);
		}
		$this->image = $result;
	}
	function design_index ($size) {
		$this->nodes [0]->design ($size);
		$img1 = $this->nodes [0]->image;
		$base1 = $this->nodes [0]->base_vertical;
		$this->nodes [2]->design ($size * 0.8);
		$img2 = $this->nodes [2]->image;
		$base2 = $this->nodes [2]->base_vertical;
		$height1 = imagesy ($img1);
		$height2 = imagesy ($img2);
		$width1 = imagesx ($img1);
		$width2 = imagesx ($img2);
		$width = $width1 + $width2;
		if ($height1 >= $height2) {
			$height = ceil ($height2 / 2 + $height1);
			$this->base_vertical = $base1;
			$result = ImageCreate (max ($width, 1), max ($height, 1));
			$noir = ImageColorAllocate ($result, 0, 0, 0);
			$blanc = ImageColorAllocate ($result, 255, 255, 255);
			$blanc = imagecolortransparent ($result, $blanc);
			imageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
			imageCopy ($result, $img1, 0, 0, 0, 0, $width1, $height1);
			imageCopy ($result, $img2, $width1, ceil ($height1 - $height2 / 2) , 0, 0, $width2, $height2);
		} else {
			$height = ceil ($height1 / 2 + $height2);
			$this->base_vertical = $base1;
			$result = imageCreate (max ($width, 1), max ($height, 1));
			$noir = imageColorAllocate ($result, 0, 0, 0);
			$blanc = imageColorAllocate ($result, 255, 255, 255);
			$blanc = imagecolortransparent ($result, $blanc);
			imageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
			imageCopy ($result, $img1, 0, 0, 0, 0, $width1, $height1);
			imageCopy ($result, $img2, $width1, ceil ($height1 / 2), 0, 0, $width2, $height2);
		}
		$this->image = $result;
	}
	function design_sqrt ($size) {
		$this->nodes [1]->design ($size);
		$imgexp = $this->nodes [1]->image;
		$baseexp = $this->nodes [1]->base_vertical;
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy ($imgexp);
		$imgrac = PHPmathpublisher_Units_Publisher::declare_symbol ("_root", $heightexp + 2);
		$widthrac = imagesx ($imgrac);
		$heightrac = imagesy ($imgrac);
		$baserac = $heightrac / 2;
		$width = $widthrac + $widthexp;	
		$height = max ($heightexp, $heightrac);
		$result = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($result, 0, 0, 0);
		$blanc = imageColorAllocate ($result, 255, 255, 255);
		$blanc = imagecolortransparent ($result, $blanc);
		imageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
		imageCopy ($result, $imgrac, 0, 0, 0, 0, $widthrac, $heightrac);
		imageCopy ($result, $imgexp, $widthrac, $height - $heightexp, 0, 0, $widthexp, $heightexp);
		imagesetthickness ($result, 1);
		imageline ($result, $widthrac - 2, 2, $widthrac + $widthexp + 2, 2, $noir);
		$this->base_vertical = $height - $heightexp + $baseexp;
		$this->image = $result;	
	}
	function design_root ($size) {
		$this->nodes [1]->design ($size * 0.6);
		$imgroot = $this->nodes [1]->image;
		$baseroot = $this->nodes [1]->base_vertical;
		$widthroot = imagesx ($imgroot);
		$heightroot = imagesy ($imgroot);
		$this->nodes [2]->design ($size);
		$imgexp = $this->nodes [2]->image;
		$baseexp = $this->nodes [2]->base_vertical;
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy ($imgexp);
		$imgrac = PHPmathpublisher_Units_Publisher::declare_symbol ("_root", $heightexp + 2);
		$widthrac = imagesx ($imgrac);
		$heightrac = imagesy ($imgrac);
		$baserac = $heightrac / 2;
		$width = $widthrac + $widthexp;
		$height = max ($heightexp, $heightrac);
		$result = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($result, 0, 0, 0);
		$blanc = imageColorAllocate ($result, 255, 255, 255);
		$blanc = imagecolortransparent ($result, $blanc);
		imageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
		imageCopy ($result, $imgrac, 0, 0, 0, 0, $widthrac, $heightrac);
		imageCopy ($result, $imgexp, $widthrac, $height - $heightexp, 0, 0, $widthexp, $heightexp);
		imagesetthickness ($result, 1);
		imageline ($result, $widthrac - 2, 2, $widthrac + $widthexp + 2, 2, $noir);
		imageCopy ($result, $imgroot, 0, 0, 0, 0, $widthroot, $heightroot); 
		$this->base_vertical = $height - $heightexp + $baseexp;
		$this->image = $result;	
	}
	function design_bigoperator ($size, $indication) {
		$this->nodes [1]->design ($size * 0.8);
		$img1 = $this->nodes [1]->image;
		$base1 = $this->nodes [1]->base_vertical;
		$this->nodes [2]->design ($size * 0.8);
		$img2 = $this->nodes [2]->image;
		$base2 = $this->nodes [2]->base_vertical;
		$this->nodes [3]->design ($size);
		$imgexp = $this->nodes [3]->image;
		$baseexp = $this->nodes [3]->base_vertical;
		// borneinf
		$width1 = imagesx ($img1);
		$height1 = imagesy ($img1);
		// bornesup
		$width2 = imagesx ($img2);
		$height2 = imagesy($img2);
		// expression
		$heightexp = imagesy ($imgexp);
		$widthexp = imagesx ($imgexp);
		// indication
		$imgsymbol = PHPmathpublisher_Units_Publisher::declare_symbol ($indication, $baseexp * 1.8); // max($baseexp,$heightexp-$baseexp)*2);
		$widthsymbol = imagesx ($imgsymbol);
		$heightsymbol = imagesy ($imgsymbol);
		$basesymbol = $heightsymbol / 2;
		$heightDeformation = $heightsymbol + $height1 + $height2;
		$widthDeformation = max ($widthsymbol, $width1, $width2);
		$imgDeformation = imageCreate (max ($widthDeformation, 1), max ($heightDeformation, 1));
		$noir = imageColorAllocate ($imgDeformation, 0, 0, 0);
		$blanc = imageColorAllocate ($imgDeformation, 255, 255, 255);
		$blanc = imagecolortransparent ($imgDeformation, $blanc);
		imageFilledRectangle ($imgDeformation, 0, 0, $widthDeformation - 1, $heightDeformation - 1, $blanc);
		imageCopy ($imgDeformation, $imgsymbol, ($widthDeformation - $widthsymbol) / 2,
			$height2, 0, 0, $widthsymbol, $heightsymbol);
		imageCopy ($imgDeformation, $img2, ($widthDeformation - $width2) / 2, 0, 0, 0, $width2, $height2);
		imageCopy ($imgDeformation, $img1,($widthDeformation - $width1) / 2, $height2 + $heightsymbol,
			0, 0,$width1, $height1);
		$imgEnd = PHPmathpublisher_Units_Publisher::alignement2 ($imgDeformation, $basesymbol + $height2, $imgexp, $baseexp);
		$this->image = $imgEnd;
		$this->base_vertical = max ($basesymbol + $height2, $baseexp + $height2);
	}
	function design_over ($size) {
		$this->nodes [2]->design ($size * 0.8);
		$imgsup = $this->nodes [2]->image;
		$basesup = $this->nodes [2]->base_vertical;
		$this->nodes [1]->design ($size);
		$imgexp = $this->nodes [1]->image;
		$baseexp = $this->nodes [1]->base_vertical;
		// expression
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy ($imgexp);
		// bornesup
		$widthsup = imagesx ($imgsup);
		$heightsup = imagesy ($imgsup);
		// end
		$height = $heightexp + $heightsup;
		$width = max ($widthsup, $widthexp) + ceil ($size / 8);
		$imgEnd = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($imgEnd, 0, 0, 0);
		$blanc = imageColorAllocate ($imgEnd, 255, 255, 255);
		$blanc = imagecolortransparent ($imgEnd, $blanc);
		imageFilledRectangle ($imgEnd, 0, 0, $width - 1, $height - 1, $blanc);
		imageCopy ($imgEnd, $imgsup, ($width - $widthsup) / 2, 0, 0, 0, $widthsup, $heightsup);
		imageCopy ($imgEnd, $imgexp, ($width - $widthexp) / 2, $heightsup, 0, 0, $widthexp, $heightexp);
		$this->image = $imgEnd;
		$this->base_vertical = $baseexp + $heightsup;
	}
	function design_under ($size) {
		$this->nodes [2]->design ($size * 0.8);
		$imginf = $this->nodes [2]->image;
		$baseinf = $this->nodes [2]->base_vertical;
		$this->nodes [1]->design ($size);
		$imgexp = $this->nodes [1]->image;
		$baseexp = $this->nodes [1]->base_vertical;
		// expression
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy ($imgexp);
		// borneinf
		$widthinf = imagesx ($imginf);
		$heightinf = imagesy ($imginf);
		// fin
		$height = $heightexp + $heightinf;
		$width = max ($widthinf, $widthexp) + ceil ($size / 8);
		$imgfin = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($imgfin, 0, 0, 0);
		$blanc = imageColorAllocate ($imgfin, 255, 255, 255);
		$blanc = imagecolortransparent ($imgfin, $blanc);
		imageFilledRectangle ($imgfin, 0, 0, $width - 1, $height - 1, $blanc);
		imageCopy ($imgfin, $imgexp,($width - $widthexp) / 2, 0, 0, 0, $widthexp, $heightexp);
		imageCopy ($imgfin, $imginf,($width - $widthinf) / 2, $heightexp, 0, 0, $widthinf, $heightinf);
		$this->image = $imgfin;
		$this->base_vertical = $baseexp;
	}
	function design_matrix ($size) {
		$padding = 8;
		$nbline = $this->nodes [1]->nodes [0]->text;
		$nbcolumn = $this->nodes [2]->nodes [0]->text;
		$width_case = 0;
		$height_case = 0;
		for ($line = 0; $line < $nbline; $line++) {
			$height_line [$line] = 0;
			$over_line[$line]=0;
		}
		for ($col = 0; $col <$nbcolumn; $col++) {
			$width_column[$col]=0;
		}
		$i = 0;
		for ($line = 0; $line < $nbline; $line++) {
			for ($col = 0; $col < $nbcolumn; $col++) {
				if ($i < count ($this->nodes [3]->nodes)) {
					$this->nodes [3]->nodes [$i]->design ($size * 0.9);
					$img [$i] = $this->nodes [3]->nodes [$i]->image;
					$base [$i] = $this->nodes [3]->nodes [$i]->base_vertical;
					$over_line [$line] = max ($base [$i], $over_line [$line]);
					$width [$i] = imagesx ($img [$i]);
					$height [$i] = imagesy ($img [$i]);
					$height_line [$line] = max ($height_line [$line], $height [$i]);
					$width_column [$col] = max ($width_column [$col], $width [$i]);
				}
				$i++;
			}
		}
		$heightfin = 0;
		$widthfin = 0;
		for ($line = 0; $line < $nbline; $line++) {
			$heightfin += $height_line [$line] + $padding;
		}
		for ($col = 0; $col < $nbcolumn; $col++) {
			$widthfin += $width_column [$col] + $padding;
		}
		$heightfin -= $padding;
		$widthfin -= $padding;
		$imgfin = imageCreate (max ($widthfin, 1), max ($heightfin, 1));
		$noir = imageColorAllocate ($imgfin, 0, 0, 0);
		$blanc = imageColorAllocate ($imgfin, 255, 255, 255);
		$blanc = imagecolortransparent ($imgfin, $blanc);
		imageFilledRectangle ($imgfin, 0, 0, $widthfin - 1, $heightfin - 1, $blanc);
		$i = 0;
		$h = $padding / 2 - 1;
		for ($line = 0; $line < $nbline; $line++) {
			$l = $padding / 2 - 1;
			for ($col = 0; $col < $nbcolumn; $col++) {
				if ($i < count ($this->nodes [3]->nodes)) {
					imageCopy ($imgfin, $img [$i], $l + ceil ($width_column [$col] - $width [$i]) / 2,
						$h + $over_line [$line] - $base [$i], 0, 0, $width [$i], $height [$i]);
					// ImageRectangle ($imgfin, $l, $h, $l + $width_column [$col], $h + $height_line [$line], $noir);
				}
				$l += $width_column [$col] + $padding;
				$i++;
			}
			$h += $height_line [$line] + $padding;
		}
		// ImageRectangle ($imgfin, 0, 0, $widthfin - 1, $heightfin - 1, $noir);
		$this->image = $imgfin;
		$this->base_vertical = imagesy ($imgfin) / 2;
	}
	function design_tabular ($size) {
		$padding = 8;
		$typeline = $this->nodes [1]->nodes [0]->text;
		$typecolumn = $this->nodes [2]->nodes [0]->text;
		$nbline = strlen ($typeline) - 1;
		$nbcolumn = strlen ($typecolumn) - 1;
		$width_case = 0;
		$height_case = 0;
		for ($line = 0; $line < $nbline; $line++) {
			$height_line [$line] = 0;
			$over_line [$line] = 0;
		}
		for ($col = 0; $col < $nbcolumn; $col++) {
			$width_column [$col] = 0;
		}
		$i = 0;
		for ($line = 0; $line < $nbline; $line++) {
			for ($col = 0; $col < $nbcolumn; $col++) {
				if ($i < count ($this->nodes [3]->nodes)) {
					$this->nodes [3]->nodes [$i]->design ($size * 0.9);
					$img [$i] = $this->nodes [3]->nodes [$i]->image;
					$base [$i] = $this->nodes [3]->nodes [$i]->base_vertical;
					$over_line [$line] = max ($base [$i], $over_line [$line]);
					$width [$i] = imagesx ($img [$i]);
					$height [$i] = imagesy ($img [$i]);
					$height_line [$line] = max ($height_line [$line], $height [$i]);
					$width_column [$col] = max ($width_column [$col], $width [$i]);
				}
				$i++;
			}
		}
		$heightfin = 0;
		$widthfin = 0;
		for ($line = 0; $line < $nbline; $line++) {
			$heightfin += $height_line [$line] + $padding;
		}
		for ($col = 0; $col < $nbcolumn; $col++) {
			$widthfin += $width_column [$col] + $padding;
		}
		$imgfin = imageCreate (max ($widthfin, 1), max ($heightfin, 1));
		$noir = imageColorAllocate ($imgfin, 0, 0, 0);
		$blanc = imageColorAllocate ($imgfin, 255, 255, 255);
		$blanc = imagecolortransparent ($imgfin, $blanc);
		imageFilledRectangle ($imgfin, 0, 0, $widthfin - 1, $heightfin - 1, $blanc);
		$i = 0;
		$h = $padding / 2 - 1;
		if (substr ($typeline, 0, 1) == "1") imageLine ($imgfin, 0, 0, $widthfin - 1, 0, $noir);
		for ($line = 0; $line < $nbline; $line++) {
			$l = $padding / 2 - 1;
			if (substr ($typecolumn, 0, 1) == "1") imageLine ($imgfin, 0, $h - $padding / 2, 0,
				$h + $height_line [$line] + $padding / 2, $noir);
			for ($col = 0; $col < $nbcolumn; $col++) {
				if ($i < count ($this->nodes [3]->nodes)) {
					imageCopy ($imgfin, $img [$i], $l + ceil ($width_column [$col] - $width [$i]) / 2,
						$h + $over_line [$line] - $base [$i], 0, 0, $width [$i], $height [$i]);
					if (substr ($typecolumn, $col + 1, 1) == "1") ImageLine ($imgfin, $l + $width_column [$col] + $padding / 2,
						$h - $padding / 2, $l + $width_column [$col] + $padding / 2, $h + $height_line [$line] + $padding / 2,
						$noir);
				}
				$l += $width_column [$col] + $padding;
				$i++;
			}
			if (substr ($typeline, $line + 1, 1) == "1") ImageLine ($imgfin, 0, $h + $height_line [$line] + $padding / 2,
				$widthfin - 1, $h + $height_line [$line] + $padding / 2, $noir);
			$h += $height_line [$line] + $padding;
		}
		$this->image = $imgfin;
		$this->base_vertical = imagesy ($imgfin) / 2;
	}
	function design_vector ($size) {
		// expression
		$this->nodes [1]->design ($size);
		$imgexp = $this->nodes [1]->image;
		$baseexp = $this->nodes [1]->base_vertical;
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy ($imgexp);
		// arrow
		$imgsup = PHPmathpublisher_Units_Publisher::declare_symbol ("rightarrow", 16);
		$widthsup = imagesx ($imgsup);
		$heightsup = imagesy ($imgsup);
		// fin
		$height = $heightexp + $heightsup;
		$width = $widthexp;
		$imgfin = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($imgfin, 0, 0, 0);
		$blanc = imageColorAllocate ($imgfin, 255, 255, 255);
		$blanc = imagecolortransparent ($imgfin, $blanc);
		imageFilledRectangle ($imgfin, 0, 0, $width - 1, $height - 1, $blanc);
		imageCopy ($imgfin, $imgsup, $width - 6, 0, $widthsup - 6, 0, $widthsup, $heightsup);
		imagesetthickness ($imgfin, 1);
		imageline ($imgfin, 0, 6, $width - 4, 6, $noir);
		ImageCopy ($imgfin, $imgexp,($width - $widthexp) / 2, $heightsup, 0, 0, $widthexp, $heightexp);
		$this->image = $imgfin;
		$this->base_vertical = $baseexp + $heightsup;
	}
	function design_overline ($size) {
		// expression
		$this->nodes [1]->design ($size);
		$imgexp = $this->nodes [1]->image;
		$baseexp = $this->nodes [1]->base_vertical;
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy($imgexp);
		$height = $heightexp + 2;
		$width = $widthexp;
		$imgfin = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($imgfin, 0, 0, 0);
		$blanc = imageColorAllocate ($imgfin, 255, 255, 255);
		$blanc = imagecolortransparent ($imgfin, $blanc);
		imageFilledRectangle ($imgfin, 0, 0, $width - 1, $height - 1, $blanc);
		imagesetthickness ($imgfin, 1);
		imageline ($imgfin, 0, 1, $width, 1, $noir);
		imageCopy ($imgfin, $imgexp, 0, 2, 0, 0, $widthexp, $heightexp);
		$this->image = $imgfin;
		$this->base_vertical = $baseexp + 2;
	}
	function design_underline ($size) {
		// expression
		$this->nodes [1]->design ($size);
		$imgexp = $this->nodes [1]->image;
		$baseexp = $this->nodes [1]->base_vertical;
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy ($imgexp);
		$height = $heightexp + 2;
		$width = $widthexp;
		$imgfin = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($imgfin, 0, 0, 0);
		$blanc = imageColorAllocate ($imgfin, 255, 255, 255);
		$blanc = imagecolortransparent ($imgfin, $blanc);
		imageFilledRectangle ($imgfin, 0, 0, $width - 1, $height - 1, $blanc);
		imagesetthickness ($imgfin, 1);
		imageline ($imgfin, 0, $heightexp + 1, $width, $heightexp + 1, $noir);
		imageCopy ($imgfin, $imgexp, 0, 0, 0, 0, $widthexp, $heightexp);
		$this->image = $imgfin;
		$this->base_vertical = $baseexp;
	}
	function designScript ($size, $com, $script) {
		switch ($com) {
			case 'b':
				$symbol = '_b';
				break;
			case 'overarc':
			case 'underarc':
				$symbol = 'arc';
				break;
			case 'overleftarrow':
			case 'underleftarrow':
				$symbol = 'leftarrow';
				break;
			case 'overleftharp':
			case 'overleftharpoon':
			case 'overleftharpup':
			case 'overleftharpoonup':
				$symbol = 'leftharpoonup';
				break;
			case 'overleftrightarrow':
			case 'underleftrightarrow':
				$symbol = 'leftrightarrow';
				break;
			case 'overrightarrow':
			case 'underrightarrow':
				$symbol = 'rightarrow';
				break;
			case 'overrightharp':
			case 'overrightharpoon':
			case 'overrightharpup':
			case 'overrightharpoonup':
				$symbol = 'rightharpoonup';
				break;
			case 'd':
				$symbol = 'dot';
				break;
			case 'dd':
				$symbol = 'dot';
				break;
			case 'ddd':
				$symbol = 'dddot';
				break;
			case 'dddd':
				$symbol = 'ddddot';
				break;
			case 'underleftharp':
			case 'underleftharpdown':
			case 'underleftharpoon':
			case 'underleftharpoondown':
				$symbol = 'leftharpoondown';
				break;
			case 'underrightharp':
			case 'underrightharpdown':
			case 'underrightharpoon':
			case 'underrightharpoondown':
				$symbol = 'rightharpoondown';
				break;
			case 'undertilde':
				$symbol = 'tilde';
				break;
			default:
				$symbol = $com;
		}
		$imgsup = PHPmathpublisher_Units_Publisher::declare_symbol ($symbol, $size);
		$this->nodes [1]->design ($size);
		$imgexp = $this->nodes [1]->image;
		$baseexp = $this->nodes [1]->base_vertical;
		// expression
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy ($imgexp);
		// bornesup
		$widthsup = imagesx ($imgsup);
		$heightsup = imagesy ($imgsup);
		// fin
		$height = $heightexp + $heightsup;
		$width = max ($widthsup, $widthexp) + ceil ($size / 8);
		$imgfin = imageCreate (max ($width, 1), max ($height, 1));
		$noir = imageColorAllocate ($imgfin, 0, 0, 0);
		$blanc = imageColorAllocate ($imgfin, 255, 255, 255);
		$blanc = imagecolortransparent ($imgfin, $blanc);
		imageFilledRectangle ($imgfin, 0, 0, $width - 1, $height - 1, $blanc);
		if (strcmp ($script, 'over') == 0) {
			imageCopy ($imgfin, $imgsup, ($width - $widthsup) / 2, 0, 0, 0, $widthsup, $heightsup);
			imageCopy ($imgfin, $imgexp,($width - $widthexp) / 2, $heightsup, 0, 0, $widthexp, $heightexp);
		} elseif (strcmp ($script, 'under') == 0) {
			imageCopy ($imgfin, $imgexp,($width - $widthexp) / 2, 0, 0, 0, $widthexp, $heightexp);
			imageCopy ($imgfin, $imgsup, ($width - $widthsup) / 2, $heightexp, 0, 0, $widthsup, $heightsup);
		} else die ('unexpected error');
		$this->image = $imgfin;
		$this->base_vertical = $baseexp + $heightsup;
	}
	function design_not ($size) {
		$this->nodes [1]->design ($size);
		$this->image = $this->nodes [1]->image;
		$noir = ImageColorAllocate ($this->image, 0, 0, 0);
		imageLine ($this->image, 0, imagesy ($this->image), imagesx ($this->image), 0, $noir);
	}
	function design_limit ($size) {
		$imglim = PHPmathpublisher_Units_Publisher::declareMath ("_lim", $size);
		$widthlim = imagesx ($imglim);
		$heightlim = imagesy ($imglim);
		$baselim = $heightlim / 2;
		$this->nodes [1]->design ($size * 0.8);
		$imginf = $this->nodes [1]->image;
		$baseinf = $this->nodes [1]->base_vertical;
		$widthinf = imagesx ($imginf);
		$heightinf = imagesy ($imginf);
		$this->nodes [2]->design ($size);
		$imgexp = $this->nodes [2]->image;
		$baseexp = $this->nodes [2]->base_vertical;
		$widthexp = imagesx ($imgexp);
		$heightexp = imagesy ($imgexp);
		$height = $heightlim + $heightinf;
		$width = max ($widthinf, $widthlim) + ceil ($size / 8);
		$imgfin = ImageCreate (max ($width, 1), max ($height, 1));
		$noir = ImageColorAllocate ($imgfin, 0, 0, 0);
		$blanc = ImageColorAllocate ($imgfin, 255, 255, 255);
		$blanc = imagecolortransparent ($imgfin, $blanc);
		ImageFilledRectangle ($imgfin, 0, 0, $width - 1, $height - 1, $blanc);
		ImageCopy ($imgfin, $imglim, ($width - $widthlim) / 2, 0, 0, 0, $widthlim, $heightlim);
		ImageCopy ($imgfin, $imginf, ($width - $widthinf) / 2, $heightlim, 0, 0, $widthinf, $heightinf);
		$this->image = alignement2 ($imgfin, $baselim, $imgexp, $baseexp);
		$this->base_vertical = max ($baselim, $baseexp);
	}
	function design_delimiter ($size) {
		$this->nodes [2]->design ($size);
		$imgexp = $this->nodes [2]->image;
		$baseexp = $this->nodes [2]->base_vertical;
		$heightexp = imagesy ($imgexp);
		if ($this->nodes [1]->text == "&$") $imgdeformation = 
		PHPmathpublisher_Units_Publisher::parenthese ($heightexp, $this->nodes [1]->nodes [0]->text);
		else $imgdeformation = PHPmathpublisher_Units_Publisher::parenthese ($heightexp, $this->nodes [1]->text);
		$basedeformation = imagesy ($imgdeformation) / 2;
		if ($this->nodes [3]->text == "&$") $imgstraight = 
		PHPmathpublisher_Units_Publisher::parenthese ($heightexp, $this->nodes [3]->nodes [0]->text);
		else $imgstraight = PHPmathpublisher_Units_Publisher::parenthese ($heightexp, $this->nodes [3]->text);
		$basestraight = imagesy ($imgstraight) / 2;
		$this->image = PHPmathpublisher_Units_Publisher::alignement3 ($imgdeformation, $basedeformation, $imgexp, $baseexp, $imgstraight, $basestraight);
		$this->base_vertical = max ($basedeformation, $baseexp, $basestraight);
	}
}
?>