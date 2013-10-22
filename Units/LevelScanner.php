<?php
class PHPmathpublisher_Units_LevelScanner { //manages Reader instance and joins xml nodes to given tree, scans a level of tree
	public $nodes = array ();
	private $reader;
	private $latexSigns;
	private $oneEntityKeeper;
	private $twoEntityKeeper;
	private $sumLikeCom;
	private $fined = false;
	
	function __construct ($userIn) {
		$this->reader = new PHPmathpublisher_Units_Reader ($userIn);
		$this->latexSigns = array (
			'le','leq','geq','ge','ll','gg','prec','succ','lhd','triangleleft','rhd','triangleright','sim','approx',
			'simeq','cong','neq','ne','equiv','triangleq','hateq','doteq','propto','dots','ldots','cdots','vdots',
			'ddots','udots','pm','mp','times','ast','div','oplus','otimes','odot','cdot','bullet','circ','langle',
			'rangle','ldbrack','llbracket','rdbrack','rrbracket','leftrightarrow','rightarrow','to','leftarrow',
			'updownarrow','uparrow','downarrow','Leftrightarrow','Rightarrow','Leftarrow','Updownarrow','Uparrow',
			'Downarrow','nesarrow','nearrow','swarrow','nswarrow','searrow','nwarrow','rightleftarrows',
			'rightleftharpoons','mapsto','rightmapsto','dlsh','therefore','because','backepsilon','exists','forall',
			'land','wedge','lor','vee','in','notin','subset','supset','subseteq',
			'supseteq','nsubset', 'empty', 'O','partial','wp','infty','hbar','planck','barlambda','ell','dag','Delta','nabla',
			'Omega','diamondsuit','degree','angle','sphericalangle','measuredangle','perp','parallel','triangle','square',
			'circ','alpha','beta','gamma','delta','epsilon','varepsilon','zeta','eta','theta','vartheta','iota','kappa',
			'lambda','mu','nu','xi','pi','varpi','rho','varrho','sigma','varsigma','tau','upsilon','phi','varphi','chi',
			'psi','omega','varkappa','omicron','Alpha','Beta','Gamma','Delta','Epsilon','Zeta','Eta','Theta','Iota',
			'Kappa','Lambda','Mu','Nu','Xi','Pi','Rho','Sigma','Tau','Upsilon','Phi','Chi','Psi','Omega','Omicron',
			'ne','notexists','approx','leftright','doubleleft','doubleright','doubleleftright','bbR',
			'bbN','bbZ','bbC','inter','union','ortho','backslash','prime','vert','notsubset','varnothing','cdots',
			'lbrace', 'rbrace'
		);
		$this->oneEntityKeeper = array(
			'acute', 'sqrt', 'grave','not','dot','ddot','dddot','ddddot','d','dd','ddd','dddd','bar','tilde','overarc',
			'breve', 'hat','widehat','b', 'overline', 'underline','undertilde','underarc','vec','overrightarrow',
			'overleftarrow','overleftrightarrow','overrightharpoonup', 'overrightharpup','overrightharp',
			'overrightharpoon','overleftharpoonup','overleftharpup','overleftharp','overleftharpoon', 'underrightarrow',
			'underleftarrow','underleftrightarrow','underrightharpoondown','underrightharpdown','underrightharp',
			'underrightharpoon','underleftharpoondown','underleftharpdown', 'underleftharp', 'underleftharpoon',
		);
		$this->twoEntityKeeper = array (
			'root' 
			/*, 'xrightarrow', 'xleftarrow', 'xleftrightarrow', 'xrightleftarrows', 'xrightleftharpoons'*/
		);
		$this->sumLikeCom = array (
			'sum','int','iint','doubleint','iiint','tripleint','oint',
			/*'oiint','oiiint','ointctrclockwise','ointclockwise',*/
			'prod','corprod','bigcap','bigcup','cap','cup'
		);
	}
	private function getChar () {
		return $this->reader->getChar ();
	}
	private function pushBackChar () {
		$this->reader->pushBackChar ();
	}
	private function isWordChar ($char) {
		return preg_match ("/[A-Za-z]/", $char);
	}
	private function isTextChar ($char) {
		return preg_match ("/[A-Za-z0-9`~!@#$%&*()=+\[\]|;:'\",.\/?№\-<>]/", $char);
	}
	private function isLetterChar ($char) {
		return preg_match ("/[A-Za-zА-Яа-я]/", $char);
	}
	private function isSpaceChar ($char) {
		return preg_match ("/[\t \n\r]/", $char);
	}
	private function isNumberChar ($char) {
		return preg_match ("/[0-9]/", $char);
	}
	private function isEolChar ($char) {
		return preg_match ("/\n|\r", $char);
	}
	private function checkChar ($char) {
		if (!$char) throw new PHPmathpublisher_Units_LatexSyntaxException ();
	}
	private function eatSpaceChars ($char) { // finds non-space char and returns it
		if ($this->isSpaceChar ($char) ) {
			while ($this->isSpaceChar ($char = $this->getChar ())) {}
			$this->checkChar ($char);
		}
		return $char;
	}
	private function eatWordChars ($char) {
		$val = $char;
		while ($this->isWordChar ($char = $this->getChar ())) {
			$val .= $char;
		}
		if ($char) $this->pushBackChar ();
		return $val;
	}
	private function eatNumberChars ($char) {
		$val = $char;
		while ($this->isNumberChar ($char = $this->getChar () ) ) {
			$val .= $char;
		}
		if ($char) $this->pushBackChar ();
		return $val;
	}
	private function eatTextChars ($char) {
		$val = $char;
		while ($this->isTextChar ($char = $this->getChar () ) || $this->isSpaceChar ($char) ) {
			$val .= $char;
		}
		if ($char) $this->pushBackChar ();
		return $val;
	}
	private function eatCom ($char) {
		if ($char != '\\') die ('function eatCom accepts a sign "\\" as first only');
		$char = $this->getChar ();
		if ($char == ',') return '\\'.$char;
		else $this->pushBackChar ();
		$val = $this->eatWordChars ($char);
		return $val;
	}
	private function eatCurlyBrackets ($char) {
		if ($char != '{') die ('eatCurlyBrackets method accepts a sign "{" as first only');
		$val = $this->getChar ();
		$left_curly_brackets_counter = 1;
		$right_curly_brackets_counter = 0;
		while ($left_curly_brackets_counter != $right_curly_brackets_counter) {
			$char = $this->getChar ();
			$this->checkChar ($char);
			$val .= $char;
			if ($char == '{') $left_curly_brackets_counter++;
			else if ($char == '}') $right_curly_brackets_counter++;
		}
		$val = substr ($val, 0, -1);
		return $val;
	}
	private function eatSquareBrackets ($char) {
		if ($char != '[') die ('eatSquareBrackets method accepts a sign "[" as first only');
		$val = $this->getChar ();
		$leftSquareBracketsCounter = 1;
		$rightSquareBracketsCounter = 0;
		while ($leftSquareBracketsCounter != $rightSquareBracketsCounter) {
			$char = $this->getChar ();
			$this->checkChar ($char);
			$val .= $char;
			if ($char == '[') $leftSquareBracketsCounter++;
			else if ($char == ']') $rightSquareBracketsCounter++;
		}
		$val = substr ($val, 0, -1);
		return $val;
	}
	private function eatIndexesChars ($char) {
		$char = $this->eatSpaceChars ($char); // eats space chars beatween '(^|_)' and exp
		if ($this->isTextChar ($char) ) { // eats exp's like '(^|_)ak326;:...'
			$val .= $char;
			while ($this->isTextChar ($char = $this->getChar () ) ) {
				$val .= $char;
			}
			if ($char) $this->pushBackChar ();
		} else if ($char == '{') { // eats exp's like '(^|_){...}'
			$val = $this->eatCurlyBrackets ($char);
		} else if ($char == '\\') { // eats exp's like '(^|_) \alpha'
			$val = $this->eatCom ($char);
		}
		return $val;
	}
	private function eatOneEntityKeeper ($char) {
		$char = $this->eatSpaceChars ($char); // eats space between one entity keeper command and expression
		if ($this->isTextChar ($char) ) {
			$val .= $char;
			while ($this->isTextChar ($char = $this->getChar () ) ) {
				$val .= $char;
			}
			if ($char) $this->pushBackChar ();
		} else if ($char == '{') {
			$val = $this->eatCurlyBrackets ($char);
		} else if ($char == '\\') {
			$val = $this->eatCom ($char);
			$val = substr ($val, 1);
			$temp_flag = false;
			foreach ($this->latexSigns as $latexSign) {
				if (strcmp ($val, $latexSign) == 0) $temp_flag = true;
			}
			if (!$temp_flag) throw new PHPmathpublisher_Units_LatexSyntaxException ();
			$val = '\\'.$val;
		}
		return $val;
	}
	private function eatMathop ($char) {
	// example: '\mathop {expression A} \nolimits_{expression B}'
		// begin eating expression A
		$char = $this->eatSpaceChars ($char); // eats space between mathop command and expression A
		if ($this->isTextChar ($char) ) {
			$content .= $char;
			while ($this->isTextChar ($char = $this->getChar () ) ) {
				$content .= $char;
			}
			$this->checkChar ($char);
		} else if ($char == '{') {
			$content = $this->eatCurlyBrackets ($char);
			$char = $this->getChar ();
			$this->checkChar ($char);
		} else if ($char == '\\') {
			$content = $this->eatCom ($char);
			$content = substr ($content, 1);
			$temp_flag = false;
			foreach ($this->latexSigns as $latexSign) {
				if (strcmp ($content, $latexSign) == 0) $temp_flag = true;
			}
			if (!$temp_flag) throw new PHPmathpublisher_Units_LatexSyntaxException ();
			$content = '\\'.$content;
			$char = $this->getChar ();
			$this->checkChar ($char);
		}
		$char = $this->eatSpaceChars ($char); // eats space between expression A and \(no)?limits
		if ($char != '\\') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$com = $this->eatCom ($char);
		$com = substr ($com, 1);
		if (strcasecmp ($com, 'limits') != 0) throw new PHPmathpublisher_Units_LatexSyntaxException ();
		// begin eating expression B
		$char = $this->eatSpaceChars ($this->getChar ()); // eats space between \limits and expression B
		if ($char == '_') {
			$script = $this->eatIndexesChars ($this->getChar ());
		} else if ($char == '^') {
			$script = $this->eatIndexesChars ($this->getChar ());
		} else throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$script_type = $char;
		return array ($content, $script, $script_type);
	}
	private function eatCompoundFences ($char) {
		$leftBigFenceCounter = 1;
		$rightBigFenceCounter = 0;
		$char = $this->eatSpaceChars ($char); // eats space between '\left' and folowing sign
		// identify opening fence
		if ($char == '.' || $char == '(' || $char == ')' || $char == '[' || $char == ']' || $char == '|') {
			$opening = $char;
		} else if ($char == '\\') {
			$nextChar = $this->getChar ();
			if ($nextChar == '{' || $nextChar == '}' || $nextChar == '|') {
				$opening = $nextChar;
			} else {
				$this->pushBackChar ();
				$com = $this->eatCom ($char);
				$coms = '\langle \rangle \lfloor \rfloor \lceil \rceil';
				if (! (strpos ($coms, $com) === false)) $opening = $com;
			}
		} else throw new PHPmathpublisher_Units_LatexSyntaxException ();
		// identify content
		$content = '';
		while ($char = $this->getChar ()) {
			if ($char == '\\') {
				$com = $this->eatCom ($char);
				$com = substr ($com, 1);
				if (strcasecmp ($com, 'left') == 0) $leftBigFenceCounter++;
				else if (strcasecmp ($com, 'right') == 0) $rightBigFenceCounter++;
				if ($leftBigFenceCounter == $rightBigFenceCounter) break;
				$content .= $com;
			}
			$content .= $char;
		}
		if (! (strpos ($content, '{') === false)) {
				$content = str_replace ('{', '', $content);
				$content = str_replace ('}', '', $content);
			}
		$this->checkChar ($char);
		// identify closing fence
		$char = $this->eatSpaceChars ($this->getChar ()); // eats space between '\right' and folowing sign
		if ($char == '.' || $char == '(' || $char == ')' || $char == '[' || $char == ']' || $char == '|') {
			$closing = $char;
		} else if ($char == '\\') {
			$nextChar = $this->getChar ();
			if ($nextChar == '{' || $nextChar == '}' || $nextChar == '|') {
				$closing = $nextChar;
			} else {
				$this->pushBackChar ();
				$com = $this->eatCom ($char);
				$coms = '\langle \rangle \lfloor \rfloor \lceil \rceil';
				if (! (strpos ($coms, $com) === false)) $opening = $com;
			}
		} else throw new PHPmathpublisher_Units_LatexSyntaxException ();
		return array ($opening, $content, $closing);
	}
	private function eatBrace ($char) {
		$char = $this->eatSpaceChars ($char); // eats space between '\(under|over)brace' and content
		if ($this->isTextChar ($char) ) {
			$val .= $char;
			while ($this->isTextChar ($char = $this->getChar () ) ) {
				$val .= $char;
			}
			$content = $val;
			$this->checkChar ($char);
		} else if ($char == '{') {
			$val = $this->eatCurlyBrackets ($char);
			$content = $val;
			$char = $this->getChar ();
			$this->checkChar ($char);
		} else if ($char == '\\') {
			$val = $this->eatCom ($char);
			$val = substr ($val, 1);
			$temp_flag = false;
			foreach ($this->latexSigns as $latexSign) {
				if (strcmp ($val, $latexSign) == 0) $temp_flag = true;
			}
			if (!$temp_flag) throw new PHPmathpublisher_Units_LatexSyntaxException ();
			$content = '\\'.$val;
			$char = $this->getChar ();
			$this->checkChar ($char);
		}
		$char = $this->eatSpaceChars ($char); // eats space between content and script
		if ($char == '_' || $char == '^') {
			$script = $this->eatIndexesChars ($this->getChar ());
		}
		return array ($content, $script);
	}
	private function eatTwoEntityKeeper ($char) {
		$char = $this->eatSpaceChars ($char);
		if ($char != '[') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$content1 = $this->eatSquareBrackets ($char);
		$char = $this->eatSpaceChars ($this->getChar ());
		$content2 = $this->eatCurlyBrackets ($char);
		return array ($content1, $content2);
	}
	private function eatFracLikeComs ($char) {
		$char = $this->eatSpaceChars ($char);
		if ($char != '{') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$content1 = $this->eatCurlyBrackets ($char);
		$char = $this->eatSpaceChars ($this->getChar ());
		if ($char != '{') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$content2 = $this->eatCurlyBrackets ($char);
		return array ($content1, $content2);
	}
	private function eatSumLikeCom ($char) {
		$char = $this->eatSpaceChars ($char);
		if ($char != '_' && $char != '^' && $char != '\\') { // eat expression like '\sum' (only big sigma will be shown)
			return array (false, false, false);
		}
		$index1Flag = false;
		$index2Flag = false;
		$contentFlag = false;
		$hopFlag = false;
		if ($char == '_') { // in case similar example: '\sum _{index1}^{index2}{content}'
			$index1 = $this->eatIndexesChars ($this->getChar ());
			$char = $this->eatSpaceChars ($this->getChar ());
			if ($char != '^') throw new PHPmathpublisher_Units_LatexSyntaxException ();
			$index2 = $this->eatIndexesChars ($this->getChar ());
			$char = $this->eatSpaceChars ($this->getChar ());
			$content = $this->eatIndexesChars ($char);
			return array ($index1, $index2, $content, $hopFlag);
		} else if ($char == '^') { // in case similar example: '\sum ^{index2}_{index1}{content}
			$index2 = $this->eatIndexesChars ($this->getChar ());
			$char = $this->eatSpaceChars ($this->getChar ());
			$index1 = $this->eatIndexesChars ($char);
			$char = $this->eatSpaceChars ($this->getChar ());
			$content = $this->eatIndexesChars ($char);
			return array ($index1, $index2, $content, $hopFlag);
		} else if ($char == '\\') { // in case similar example: '\sum \(no)?limits...'
			$com = $this->eatCom ($char);
			if (strcasecmp ($com, '\limits') == 0) $hopFlag = true;
			else if (strcasecmp ($com, '\nolimits') == 0) $hopFlag = false;
			else throw new PHPmathpublisher_Units_LatexSyntaxException ();
			$char = $this->eatSpaceChars ($this->getChar ());
			if ($char == '_') {
				$index1 = $this->eatIndexesChars ($this->getChar ());
				$index1Flag = true;
			} else if ($char == '^') {
				$index2 = $this->eatIndexesChars ($this->getChar ());
				$index2Flag = true;
			}
			$char = $this->eatSpaceChars ($this->getChar ());
			if (($char == '_' && $index1Flag) || ($char == '^' && $index2Flag )) {
				throw new PHPmathpublisher_Units_LatexSyntaxException ();
			}
			if ($char == '_') {
				$index1 = $this->eatIndexesChars ($this->getChar ());
			} else if ($char == '^') {
				$index2 = $this->eatIndexesChars ($this->getChar ());
			}
			$char = $this->eatSpaceChars ($this->getChar ());
			$content = $this->eatIndexesChars ($char);
			return array ($index1, $index2, $content, $hopFlag);
		}
	}
	private function eatMatrix ($char) {
		$char = $this->eatSpaceChars ($char);
		if ($char != '{') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		else {
			$num_of_lines = $this->eatCurlyBrackets ($char);
		}
		for ($i = 0; $i < strlen ($num_of_lines); $i++) {
			if (! $this->isNumberChar ($num_of_lines [$i])) throw new PHPmathpublisher_Units_LatexSyntaxException ();
		}
		if ($num_of_lines > 100 || $num_of_lines < 1) throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$char = $this->eatSpaceChars ($char);
		if ($char != '{') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		else {
			$num_of_cols = $this->eatCurlyBrackets ($char);
			$num_of_cols = substr ($num_of_cols, 1);
		}
		for ($i = 0; $i < strlen ($num_of_cols); $i++) {
			if (! $this->isNumberChar ($num_of_cols [$i])) throw new PHPmathpublisher_Units_LatexSyntaxException ();
		}
		if ($num_of_cols > 100 || $num_of_cols < 1) throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$char = $this->eatSpaceChars ($this->getChar ());
		if ($char != '{') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$char = $this->getChar ();
		$arr = array ();
		while ($char) {
			if ($char == '}') break;
			if ($char == '\\') {
				$com = $this->eatCom ($char);
				$com = substr ($com, 1);
				$char = $this->eatSpaceChars ($this->getChar ());
				$arr [] = $com;
				continue;
			} else if ($this->isLetterChar ($char)) {
				$word = $this->eatWordChars ($char);
				$char = $this->eatSpaceChars ($this->getChar ());
				$arr [] = $word;
				continue;
			} else if ($this->isNumberChar ($char)) {
				$number = $this->eatNumberChars ($char);
				$char = $this->eatSpaceChars ($this->getChar ());
				$arr [] = $number;
				continue;
			} else throw new PHPmathpublisher_Units_LatexSyntaxException ();
		}
		if (! $char) throw new PHPmathpublisher_Units_LatexSyntaxException ();
		array_push ($arr, $num_of_lines);
		array_push ($arr, $num_of_cols);
		return $arr;
	}
	function eatTabular ($char) {
		$char = $this->eatSpaceChars ($char);
		if ($char != '{') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		else {
			$lines_desc = $this->eatCurlyBrackets ($char);
		}
		for ($i = 0; $i < strlen ($lines_desc); $i++) {
			if (! $this->isNumberChar ($lines_desc [$i])) throw new PHPmathpublisher_Units_LatexSyntaxException ();
		}
		$char = $this->eatSpaceChars ($char);
		if ($char != '{') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		else {
			$cols_desc = $this->eatCurlyBrackets ($char);
			$cols_desc = substr ($cols_desc, 1);
		}
		$char = $this->eatSpaceChars ($this->getChar ());
		if ($char != '{') throw new PHPmathpublisher_Units_LatexSyntaxException ();
		$char = $this->getChar ();
		$arr = array ();
		while ($char) {
			if ($char == '}') break;
			if ($char == '\\') {
				$com = $this->eatCom ($char);
				$com = substr ($com, 1);
				$char = $this->eatSpaceChars ($this->getChar ());
				$arr [] = $com;
				continue;
			} else if ($this->isLetterChar ($char)) {
				$word = $this->eatWordChars ($char);
				$char = $this->eatSpaceChars ($this->getChar ());
				$arr [] = $word;
				continue;
			} else if ($this->isNumberChar ($char)) {
				$number = $this->eatNumberChars ($char);
				$char = $this->eatSpaceChars ($this->getChar ());
				$arr [] = $number;
				continue;
			} else throw new PHPmathpublisher_Units_LatexSyntaxException ();
		}
		if (! $char) throw new PHPmathpublisher_Units_LatexSyntaxException ();
		array_push ($arr, $lines_desc);
		array_push ($arr, $cols_desc);
		return $arr;
	}
	function eatMathBB ($char) {
		$char = $this->eatSpaceChars ($char);
		if ($this->isTextChar ($char)) {
			$letter = $char;
			return $letter;
		} elseif ($char == '{') {
			$letter = $this->eatCurlyBrackets ($char);
			return $letter;
		} else throw new PHPmathpublisher_Units_LatexSyntaxException ();
	}
	function doScan ($nodeLevel, $i) {
		$char = $this->getChar ();
		$char = $this->eatSpaceChars ($char);
		if ($this->isTextChar ($char)) {
			$this->pushBackChar ();
			$text = $this->eatTextChars ($this->getChar ());
			$node = new PHPmathpublisher_Units_ExpressionText ($text);
			$nodeLevel [$i] = $node;
		} else if ($char == '^') {
			$node = new PHPmathpublisher_Units_ExpressionText ($char);
			$nodeLevel [$i] = $node;
			$text = $this->eatIndexesChars ($this->getChar ());
			$textNode = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($text)));
			$nodeLevel [++$i] = $textNode;
		} else if ($char == '_') {
			$node = new PHPmathpublisher_Units_ExpressionText ($char);
			$nodeLevel [$i] = $node;
			$text = $this->eatIndexesChars ($this->getChar ());
			$textNode = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($text)));
			$nodeLevel [++$i] = $textNode;
		} else if ($char == '\\') {
			$com = $this->eatCom ($char);
			$com = substr ($com, 1);
			if ($com == ',') {
				$node = new PHPmathpublisher_Units_ExpressionText ('spaceSymbol');
				$nodeLevel [$i] = $node;
				$this->fined = true;
			}
			foreach ($this->latexSigns as $latexSign) {
				if (strcmp ($com, $latexSign) == 0) {
					$node = new PHPmathpublisher_Units_ExpressionText ($com);
					$nodeLevel [$i] = $node;
					$this->fined = true;
					break;
				}
			}
			foreach ($this->oneEntityKeeper as $oneEntityKeeper) {
				if (strcmp ($com, $oneEntityKeeper) == 0) {
					$content= $this->eatOneEntityKeeper ($this->getChar ());
					$mathNodes = array ();
					$mathNodes [] = new PHPmathpublisher_Units_ExpressionText ($com);
					$contentNode = new PHPmathpublisher_Units_ExpressionText ($content);
					$mathContentNode = new PHPmathpublisher_Units_ExpressionMath (array ($contentNode));
					$mathNodes [] = $mathContentNode;
					$mainNode = new PHPmathpublisher_Units_ExpressionMath ($mathNodes);
					$nodeLevel [$i] = $mainNode;
					$this->fined = true;
					break;
				}
			}
			foreach ($this->twoEntityKeeper as $twoEntityKeeper) {
				if (strcmp ($com, $twoEntityKeeper) == 0) {
					list ($content1, $content2) = $this->eatTwoEntityKeeper ($this->getChar ());
					$node = new PHPmathpublisher_Units_ExpressionText ($com);
					$nodeLevel [$i] = $node;
					$content1Node = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($content1)));
					$nodeLevel [++$i] = $content1Node;
					$content2Node = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($content2)));
					$nodeLevel [++$i] = $content2Node;
					$this->fined = true;
					break;
				}
			}
			foreach ($this->sumLikeCom as $sumLikeCom) {
				if (strcmp ($com, $sumLikeCom) == 0) {
					list ($index1, $index2, $content, $hopFlag) = $this->eatSumLikeCom ($char = $this->getChar ());
					if (!$index1 && !$index2 && !$content) {
						break;
					}
					if ($hopFlag) {
					} else {
					}
					$node = new PHPmathpublisher_Units_ExpressionText ($com);
					$nodeLevel [$i] = $node;
					$index1Node = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($index1)));
					$nodeLevel [++$i] = $index1Node;
					$index2Node = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($index2)));
					$nodeLevel [++$i] = $index2Node;
					$contentNode = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($content)));
					$nodeLevel [++$i] = $contentNode;
					$this->fined = true;
					break;
				}
			}
			if (strcasecmp ($com, 'left') == 0) {
				list ($opening, $content, $closing) = $this->eatCompoundFences ($this->getChar ());
				$node = new PHPmathpublisher_Units_ExpressionText ('delim');
				$nodeLevel [$i] = $node;
				$openingNode= new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($opening)));
				$nodeLevel [++$i] = $openingNode;
				$contentNode = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($content)));
				$nodeLevel [++$i] = $contentNode;
				$closingNode = new PHPmathpublisher_Units_ExpressionMath (array (new PHPmathpublisher_Units_ExpressionText ($closing)));
				$nodeLevel [++$i] = $closingNode;
				$this->fined = true;
			} else if (strcasecmp ($com, 'underbrace') == 0) { // will be suppressed for while
				list ($content, $under) = $this->eatBrace ($this->getChar ());
				$this->fined = true;
			} else if (strcasecmp ($com, 'overbrace') == 0) { // will be suppressed for while
				list ($content, $over) = $this->eatBrace ($this->getChar ());
				$this->fined = true;
			} else if (strcasecmp ($com, 'frac') == 0 || strcasecmp ($com, 'rlap') == 0) {
				list ($content1, $content2) = $this->eatFracLikeComs ($this->getChar ());
				$fracNode = new PHPmathpublisher_Units_ExpressionText ($com);
				$content1Node = new PHPmathpublisher_Units_ExpressionText ($content1);
				$content2Node = new PHPmathpublisher_Units_ExpressionText ($content2);
				$nodeLevel [$i] = $fracNode;
				$nodeLevel [++$i] = $content1Node;
				$nodeLevel [++$i] = $content2Node;
				$this->fined = true;
			} else if (strcasecmp ($com, 'matrix') == 0) {
				$arr = $this->eatMatrix ($this->getChar ());
				$matrix_node = new PHPmathpublisher_Units_ExpressionText ('matrix');
				$nodeLevel [$i] = $matrix_node;
				$num_of_cols = array_pop ($arr);
				$num_of_lines = array_pop ($arr);
				$lines_text_node = new PHPmathpublisher_Units_ExpressionText ($num_of_lines);
				$lines_math_node = new PHPmathpublisher_Units_ExpressionMath (array ($lines_text_node));
				$nodeLevel [++$i] = $lines_math_node;
				$cols_text_node = new PHPmathpublisher_Units_ExpressionText ($num_of_cols);
				$cols_math_node = new PHPmathpublisher_Units_ExpressionMath (array ($cols_text_node));
				$nodeLevel [++$i] = $cols_math_node;
				$nodes = array ();
				foreach ($arr as $val) {
					$nodes [] = new PHPmathpublisher_Units_ExpressionText ($val);
				}
				$end_node = new PHPmathpublisher_Units_ExpressionMath ($nodes);
				$nodeLevel [++$i] = $end_node;
				$this->fined = true;
			} else if (strcasecmp ($com, 'tabular') == 0) {
				$arr = $this->eatTabular ($this->getChar ());
				$tabular_node = new PHPmathpublisher_Units_ExpressionText ('tabular');
				$nodeLevel [$i] = $tabular_node;
				$cols_desc = array_pop ($arr);
				$lines_desc = array_pop ($arr);
				$lines_text_node = new PHPmathpublisher_Units_ExpressionText ($lines_desc);
				$lines_math_node = new PHPmathpublisher_Units_ExpressionMath (array ($lines_text_node));
				$nodeLevel [++$i] = $lines_math_node;
				$cols_text_node = new PHPmathpublisher_Units_ExpressionText ($cols_desc);
				$cols_math_node = new PHPmathpublisher_Units_ExpressionMath (array ($cols_text_node));
				$nodeLevel [++$i] = $cols_math_node;
				$nodes = array ();
				foreach ($arr as $val) {
					$nodes [] = new PHPmathpublisher_Units_ExpressionText ($val);
				}
				$end_node = new PHPmathpublisher_Units_ExpressionMath ($nodes);
				$nodeLevel [++$i] = $end_node;
				$this->fined = true;
			} else if (strcasecmp ($com, 'mathbb') == 0) {
				$letter = $this->eatMathBB ($this->getChar ());
				if ((strcasecmp ($letter, 'R') != 0) &&
						(strcasecmp ($letter, 'N') != 0) &&
						(strcasecmp ($letter, 'Z') != 0) &&
						(strcasecmp ($letter, 'C') != 0) ) {
					throw new PHPmathpublisher_Units_LatexSyntaxException ();
						}
				$mathbb_node = new PHPmathpublisher_Units_ExpressionText ('bb'.$letter);
				$nodeLevel [$i] = $mathbb_node;
				$this->fined = true;
			} else if (strcasecmp ($com, 'mathop') == 0) {
				list ($content, $script, $script_type) = $this->eatMathop ($this->getChar ());
				if ($script_type =='_') {
					$script_type_node = new PHPmathpublisher_Units_ExpressionText ('under');
				} else if ($script_type == '^') {
					$script_type_node = new PHPmathpublisher_Units_ExpressionText ('over');
				}
				$nodeLevel [$i] = $script_type_node;
				$content_text_node = new PHPmathpublisher_Units_ExpressionText ($content);
				$content_math_node = new PHPmathpublisher_Units_ExpressionMath (array ($content_text_node));
				$nodeLevel [++$i] = $content_math_node;
				$script_text_node = new PHPmathpublisher_Units_ExpressionText ($script);
				$script_math_node = new PHPmathpublisher_Units_ExpressionMath (array ($script_text_node));
				$nodeLevel [++$i] = $script_math_node;
				$this->fined = true;
			}
			if (! $this->fined) {
				throw new PHPmathpublisher_Units_LatexSyntaxException ();
			}
		}
		$char = $this->getChar ();
		if ($char) {
			$this->pushBackChar ();
			$this->fined = false;
			$this->doScan (&$nodeLevel, ++$i); // if no end, continue scan
		}
	}
}
?>