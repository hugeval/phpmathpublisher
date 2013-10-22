	<?php
class PHPmathpublisher_Units_Publisher {
	private static $instance;
	public static $dirimg;
	public static $dirfonts;
	public static $symbols = array (
		// relations
    'leq'=>'&#8804;', 'le'=>'&#8804;', 'geq'=>'&#8805;', 'ge'=>'&#8805;', 'll'=>'&#8810;', 'gg'=>'&#8811;',
		'prec'=>'&#8826;', 'succ'=>'&#8827;', 'lhd'=>'&#8882;', 'triangleleft'=>'&#8882;', 'rhd'=>'&#8883;',
		'triangleright'=>'&#8883;', 'sim'=>'&#8764;', 'approx'=>'&#8776;', 'simeq'=>'&#8771;', 'cong'=>'&#8773;',
		'neq'=>'&#8800;', 'ne'=>'&#8800;', 'equiv'=>'&#8801;', 'triangleq'=>'&#8796;', 'propto'=>'&#8733;',
		'<'=>'&#60;', '>'=>'&#62;',
		// ellipsis
		'dots'=>'&#8943;', 'ldots'=>'&#8943;', 'vdots'=>'&#8942;', 'ddots'=>'&#8945;', 'udots'=>'&#8944;',
		// operators
		'pm'=>'&#177;', 'mp'=>'&#8723;', 'times'=>'&#215;', 'ast'=>'&#42;', 'div'=>'&#247;', 'oplus'=>'&#8853;',
		'otimes'=>'&#8855;', 'odot'=>'&#8857;', 'cdot'=>'&#8901;', 'bullet'=>'&#8729;', 'circ'=>'&#8728;',
		'langle'=>'&#9001;', 'rangle'=>'&#9002;',
		// arrows 
    'leftrightarrow'=>'&#8596;', 'rightarrow'=>'&#8594;', 'to'=>'&#8594;', 'leftarrow'=>'&#8592;',
		'updownarrow'=>'&#8597;', 'uparrow'=>'&#8593;', 'downarrow'=>'&#8595;', 'Leftrightarrow'=>'&#8660;',
    'Rightarrow'=>'&#8658;', 'Leftarrow'=>'&#8656;', 'Updownarrow'=>'&#8661;', 'Uparrow'=>'&#8657;',
		'Downarrow'=>'&#8659;', 'nearrow'=>'&#8599;', 'swarrow'=>'&#8600;', 'searrow'=>'&#8601;',
		'nwarrow'=>'&#8598;', 'rightleftarrows'=>'&#8644;', 'rightleftharpoons'=>'&#8652;', 'mapsto'=>'&#8614;',
		'rightmapsto'=>'&#8614;', 'dlsh'=>'&#8629;', 'rightharpoonup'=>'&#8640;', 'leftharpoonup'=>'&#8636;',
		'leftharpoondown'=>'&#8637;', 'rightharpoondown'=>'&#8641;',
		// logical
    'therefore'=>'&#8756;', 'because'=>'&#8757;', 'backepsilon'=>'&#8717;', 'exists'=>'&#8707;',
		'forall'=>'&#8704;', 'land'=>'&#8896;', 'wedge'=>'&#8896;', 'lor'=>'&#8897;', 'vee'=>'&#8897;',
		// set theory
    'in'=>'&#8712;', 'notin'=>'&#8713;', 'subset'=>'&#8834;', 'supset'=>'&#8835;',
		'subseteq'=>'&#8838;', 'supseteq'=>'&#8839;', 'nsubset'=>'&#8836;', 'empty'=>'&#8709;',
		// miscellaneos
    'partial'=>'&#8706;', 'wp'=>'&#8472;', 'infty'=>'&#8734;', 'hbar'=>'&#8463;', 'planck'=>'&#8463;',
		'dag'=>'&#8224;', 'nabla'=>'&#8711;', 'diamondsuit'=>'&#8900;', 'degree'=>'&#176;', 'angle'=>'&#8736;',
		'sphericalangle'=>'&#8738;', 'measuredangle'=>'&#8737;', 'perp'=>'&#8869;', 'parallel'=>'&#8741;',
		'square'=>'&#9633;', 'circ'=>'&#8413;', 'ne'=>'&#8800;', 'notexists'=>'&#8708;', 
		'approx'=>'&#8776;', 'leftright'=>'&#8596;', 'doubleleft'=>'&#8656;', 'doubleright'=>'&#8658;',
		'doubleleftright'=>'&#8660;', 'bbR'=>'&#8477;', 'bbN'=>'&#8469;', 'bbZ'=>'&#8484;', 'bbC'=>'&#8450;',
		'inter'=>'&#8898;', 'union'=>'&#8899;', 'ortho'=>'&#8869;', 'backslash'=>'&#92;', 'prime'=>'&#39;',
		'vert'=>'&#8741;', 'notsubset'=>'&#8836;', 'varnothing'=>'&#248;', 'cdots'=>'&#8943;', 'acute'=>'&#714;',
		'grave'=>'&#715;', 'dot'=>'&#46;', 'tilde'=>'&#126;', 'breve'=>'&#728;', 'arc'=>'&#8256;', '_b'=>'&#8211;',
		// greek upper case
		'Alpha'=>'&#913;', 'Beta'=>'&#914;', 'Gamma'=>'&#915;', 'Delta'=>'&#916;', 'Epsilon'=>'&#917;',
		'Zeta'=>'&#918;', 'Eta'=>'&#919;', 'Theta'=>'&#920;', 'Iota'=>'&#921;', 'Kappa'=>'&#922;',
		'Lambda'=>'&#923;', 'Mu'=>'&#924;', 'Nu'=>'&#925;', 'Xi'=>'&#926;', 'Omicron'=>'&#927;', 'Pi'=>'&#928;',
		'Rho'=>'&#929;', 'Sigma'=>'&#931;', 'Tau'=>'&#932;', 'Upsilon'=>'&#933;', 'Phi'=>'&#934;', 'Chi'=>'&#935;',
		'Psi'=>'&#936;', 'Omega'=>'&#937;',
		// greek lower case
    'alpha'=>'&#945;', 'beta'=>'&#946;', 'gamma'=>'&#947;', 'delta'=>'&#948;', 'epsilon'=>'&#949;',
		'varepsilon'=>'&#949;', 'zeta'=>'&#950;', 'eta'=>'&#951;', 'theta'=>'&#952;', 'iota'=>'&#953;',
		'kappa'=>'&#954;', 'lambda'=>'&#955;', 'mu'=>'&#956;', 'nu'=>'&#957;', 'xi'=>'&#958;', 'omicron'=>'&#959;',
		'pi'=>'&#960;', 'rho'=>'&#961;', 'varsigma'=>'&#962;', 'sigma'=>'&#963;', 'tau'=>'&#964;',
		'upsilon'=>'&#965;', 'phi'=>'&#966;', 'varphi'=>'&#966;', 'chi'=>'&#967;', 'psi'=>'&#968;', 'omega'=>'&#969;',
		// parentheses
		'('=>'&#179;', ')'=>'&#180;', '['=>'&#104;', ']'=>'&#105;', 'lbrace'=>'&#40;', 'rbrace'=>'&#41;',
		
		// other
		'hat'=>'&#99;', 'widehat'=>'&#99;', '_root'=>'&#113;', '_integral'=>'&#82;', '_dintegral'=>'&#8748;', '_tintegral'=>'&#8749;',
		'_ointegral'=>'&#72;', '_oiint'=>'&#8751;', '_oiiint'=>'&#8752', '_prod'=>'&#81;', '_corprod'=>'&#96;', '_sum'=>'&#80;', '_intersection'=>'&#84;', '_reunion'=>'&#83;',
		'_lim'=>'lim', 'spaceSymbol'=>'&#44;',
		// functions
		'arccos'=>'arccos', 'ker'=>'ker', 'arcsin'=>'arcsin', 'lg'=>'lg', 'arctan'=>'arctan', 'arg'=>'arg', 'cos'=>'cos',
		'cosh'=>'cosh', 'ln'=>'ln', 'cot'=>'cot', 'log'=>'log', 'coth'=>'coth', 'max'=>'max', 'csc'=>'csc','min'=>'min',
		'deg'=>'deg', 'det'=>'det', 'sec'=>'sec', 'dim'=>'dim', 'sin'=>'sin', 'exp'=>'exp', 'sinh'=>'sinh', 'gcd'=>'gcd',
		'sup'=>'sup', 'hom'=>'hom', 'tan'=>'tan', 'inf'=>'inf', 'tanh'=>'tanh'
	);
	public static $fontsmath = array (
		// relations
    'leq'=>'FreeSerif', 'le'=>'FreeSerif', 'geq'=>'FreeSerif', 'ge'=>'FreeSerif', 'll'=>'FreeSerif',
		'gg'=>'FreeSerif', 'prec'=>'FreeSerif', 'succ'=>'FreeSerif', 'lhd'=>'FreeSerif', 'triangleleft'=>'FreeSerif',
		'rhd'=>'FreeSerif', 'triangleright'=>'FreeSerif', 'sim'=>'FreeSerif', 'approx'=>'FreeSerif',
		'simeq'=>'FreeSerif', 'cong'=>'FreeSerif', 'neq'=>'FreeSerif', 'ne'=>'FreeSerif', 'equiv'=>'FreeSerif',
		'triangleq'=>'FreeSerif', 'propto'=>'FreeSerif', '<'=>'FreeSerif', '>'=>'FreeSerif',
		// ellipsis
		'dots'=>'FreeSerif', 'ldots'=>'FreeSerif', 'vdots'=>'FreeSerif', 'ddots'=>'FreeSerif', 'udots'=>'FreeSerif',
		// operators
		'pm'=>'FreeSerif', 'mp'=>'FreeSerif', 'times'=>'FreeSerif', 'ast'=>'FreeSerif', 'div'=>'FreeSerif',
		'oplus'=>'FreeSerif', 'otimes'=>'FreeSerif', 'odot'=>'FreeSerif', 'cdot'=>'FreeSerif', 'bullet'=>'FreeSerif',
		'circ'=>'FreeSerif', 'langle'=>'FreeSerif', 'rangle'=>'FreeSerif',
		// arrows 
    'leftrightarrow'=>'FreeSerif', 'rightarrow'=>'FreeSerif', 'to'=>'FreeSerif', 'leftarrow'=>'FreeSerif',
		'updownarrow'=>'FreeSerif', 'uparrow'=>'FreeSerif', 'downarrow'=>'FreeSerif', 'Leftrightarrow'=>'FreeSerif',
    'Rightarrow'=>'FreeSerif', 'Leftarrow'=>'FreeSerif', 'Updownarrow'=>'FreeSerif', 'Uparrow'=>'FreeSerif',
		'Downarrow'=>'FreeSerif', 'nearrow'=>'FreeSerif', 'swarrow'=>'FreeSerif', 'searrow'=>'FreeSerif',
		'nwarrow'=>'FreeSerif', 'rightleftarrows'=>'FreeSerif', 'rightleftharpoons'=>'FreeSerif', 'mapsto'=>'FreeSerif',
		'rightmapsto'=>'FreeSerif', 'dlsh'=>'FreeSerif', 'rightharpoonup'=>'FreeSerif', 'leftharpoonup'=>'FreeSerif',
		'leftharpoondown'=>'FreeSerif', 'rightharpoondown'=>'FreeSerif',
		// logical
    'therefore'=>'FreeSerif', 'because'=>'FreeSerif', 'backepsilon'=>'FreeSerif', 'exists'=>'FreeSerif',
		'forall'=>'FreeSerif', 'land'=>'FreeSerif', 'wedge'=>'FreeSerif', 'lor'=>'FreeSerif', 'vee'=>'FreeSerif',
		// set theory
    'in'=>'FreeSerif', 'notin'=>'FreeSerif', 'subset'=>'FreeSerif', 'supset'=>'FreeSerif',
		'subseteq'=>'FreeSerif', 'supseteq'=>'FreeSerif', 'nsubset'=>'FreeSerif', 'empty'=>'FreeSerif',
		// miscellaneos
    'partial'=>'FreeSerif', 'wp'=>'FreeSerif', 'infty'=>'FreeSerif', 'hbar'=>'FreeSerif', 'planck'=>'FreeSerif',
		'dag'=>'FreeSerif', 'nabla'=>'FreeSerif', 'diamondsuit'=>'FreeSerif', 'degree'=>'FreeSerif',
		'angle'=>'FreeSerif', 'sphericalangle'=>'FreeSerif', 'measuredangle'=>'FreeSerif', 'perp'=>'FreeSerif',
		'parallel'=>'FreeSerif', 'square'=>'FreeSerif', 'circ'=>'FreeSerif', 'ne'=>'FreeSerif', 
		'notexists'=>'FreeSerif', 'approx'=>'FreeSerif', 'leftright'=>'FreeSerif', 'doubleleft'=>'FreeSerif',
		'doubleright'=>'FreeSerif', 'doubleleftright'=>'FreeSerif', 'bbR'=>'FreeSerif', 'bbN'=>'FreeSerif',
		'bbZ'=>'FreeSerif', 'bbC'=>'FreeSerif', 'inter'=>'FreeSerif', 'union'=>'FreeSerif', 'ortho'=>'FreeSerif',
		'backslash'=>'FreeSerif', 'prime'=>'FreeSerif', 'vert'=>'FreeSerif', 'notsubset'=>'FreeSerif',
		'varnothing'=>'FreeSerif', 'cdots'=>'FreeSerif', 'acute'=>'FreeSerif', 'grave'=>'FreeSerif',
		'dot'=>'FreeSerif', 'tilde'=>'FreeSerif', 'breve'=>'FreeSerif', 'arc'=>'FreeSerif', '_b'=>'FreeSerif',
		// greek upper case
		'Alpha'=>'FreeSerif', 'Beta'=>'FreeSerif', 'Gamma'=>'FreeSerif', 'Delta'=>'FreeSerif', 'Epsilon'=>'FreeSerif',
		'Zeta'=>'FreeSerif', 'Eta'=>'FreeSerif', 'Theta'=>'FreeSerif', 'Iota'=>'FreeSerif', 'Kappa'=>'FreeSerif',
		'Lambda'=>'FreeSerif', 'Mu'=>'FreeSerif', 'Nu'=>'FreeSerif', 'Xi'=>'FreeSerif', 'Omicron'=>'FreeSerif',
		'Pi'=>'FreeSerif', 'Rho'=>'FreeSerif', 'Sigma'=>'FreeSerif', 'Tau'=>'FreeSerif', 'Upsilon'=>'FreeSerif',
		'Phi'=>'FreeSerif', 'Chi'=>'FreeSerif', 'Psi'=>'FreeSerif', 'Omega'=>'FreeSerif',
		// greek lower case
    'alpha'=>'FreeSerif', 'beta'=>'FreeSerif', 'gamma'=>'FreeSerif', 'delta'=>'FreeSerif', 'epsilon'=>'FreeSerif',
		'varepsilon'=>'FreeSerif', 'zeta'=>'FreeSerif', 'eta'=>'FreeSerif', 'theta'=>'FreeSerif', 'iota'=>'FreeSerif',
		'kappa'=>'FreeSerif', 'lambda'=>'FreeSerif', 'mu'=>'FreeSerif', 'nu'=>'FreeSerif', 'xi'=>'FreeSerif',
		'omicron'=>'FreeSerif', 'pi'=>'FreeSerif', 'rho'=>'FreeSerif', 'varsigma'=>'FreeSerif', 'sigma'=>'FreeSerif',
		'tau'=>'FreeSerif', 'upsilon'=>'FreeSerif', 'phi'=>'FreeSerif', 'varphi'=>'FreeSerif', 'chi'=>'FreeSerif',
		'psi'=>'FreeSerif', 'omega'=>'FreeSerif',
		// parentheses
		'('=>'cmex10', ')'=>'cmex10', '['=>'cmex10', ']'=>'cmex10', 'lbrace'=>'cmex10', 'rbrace'=>'cmex10',
		
		// other
		'hat'=>'cmex10','widehat'=>'cmex10', '_root'=>'cmex10', '_integral'=>'cmex10', '_dintegral'=>'FreeSerif', '_tintegral'=>'FreeSerif',
		'_ointegral'=>'cmex10', '_oiint'=>'FreeSerif', '_oiiint'=>'FreeSerif', '_prod'=>'cmex10','_corprod'=>'cmex10',
		'_sum'=>'cmex10', '_intersection'=>'cmex10', '_reunion'=>'cmex10', '_lim'=>'cmr10', 'spaceSymbol'=>'FreeSerif',
		// functions
		'arccos'=>'cmr10', 'ker'=>'cmr10', 'arcsin'=>'cmr10', 'lg'=>'cmr10', 'arctan'=>'cmr10', 'arg'=>'cmr10',
		'cos'=>'cmr10', 'cosh'=>'cmr10', 'ln'=>'cmr10', 'cot'=>'cmr10', 'log'=>'cmr10', 'coth'=>'cmr10', 'max'=>'cmr10',
		'csc'=>'cmr10', 'min'=>'cmr10', 'deg'=>'cmr10', 'det'=>'cmr10', 'sec'=>'cmr10', 'dim'=>'cmr10', 'sin'=>'cmr10',
		'exp'=>'cmr10', 'sinh'=>'cmr10', 'gcd'=>'cmr10', 'sup'=>'cmr10', 'hom'=>'cmr10', 'tan'=>'cmr10', 'inf'=>'cmr10',
		'tanh'=>'cmr10'
	);
	
	private function __construct () {
		self::$dirimg = $_SERVER["DOCUMENT_ROOT"]."/phpmathpublisher/Img";
		self::$dirfonts = $_SERVER["DOCUMENT_ROOT"]."/phpmathpublisher/Fonts";
	}
	public static function getInstance () {
		if (empty (self::$instance)) {
			self::$instance = new PHPmathpublisher_Units_Publisher ();
		}
		return self::$instance;
	}
	public static function is_number ($str) {
		return ereg("^[0-9]", $str);
	}
	public static function declare_symbol ($text, $high) {
		self::getInstance ();
		$text = trim(stripslashes($text));
		switch($text) {
			case '':
				$img = ImageCreate(1, max($high,1));
				$blanc=ImageColorAllocate($img,255,255,255);
				$blanc=imagecolortransparent($img,$blanc);
				ImageFilledRectangle($img,0,0,1,$high,$blanc);
				break;
			case '~':
				$img = ImageCreate(1, max($high,1));
				$blanc=ImageColorAllocate($img,255,255,255);
				$blanc=imagecolortransparent($img,$blanc);
				ImageFilledRectangle($img,0,0,1,$high,$blanc);
				break;
			case 'vert':
				$img = ImageCreate(6, max($high,1));
				$blanc=ImageColorAllocate($img,255,255,255);
				$blanc=imagecolortransparent($img,$blanc);
				$noir=ImageColorAllocate($img,0,0,0);
				ImageFilledRectangle($img,0,0,6,$high,$blanc);
				ImageFilledRectangle($img,2,0,2,$high,$noir);
				ImageFilledRectangle($img,4,0,4,$high,$noir);
				break;
			case '|':
				$img = ImageCreate(5, max($high,1));
				$blanc=ImageColorAllocate($img,255,255,255);
				$blanc=imagecolortransparent($img,$blanc);
				$noir=ImageColorAllocate($img,0,0,0);
				ImageFilledRectangle($img,0,0,5,$high,$blanc);
				ImageFilledRectangle($img,2,0,2,$high,$noir);
				break;
			case 'rightarrow':
				$font = self::$dirfonts."/".self::$fontsmath [$text].".ttf";
				$t=16;
				$text = self::$symbols[$text];
				$tmp_dim = ImageTTFBBox($t, 0, $font , $text);
				$tmp_width = abs($tmp_dim[2] - $tmp_dim[0])+2;
				$tmp_height = abs($tmp_dim[3] - $tmp_dim[5])+2;
				$tmp_img = ImageCreate(max($tmp_width,1), max($tmp_height,1));
				$tmp_noir=ImageColorAllocate($tmp_img,0,0,0);
				$tmp_blanc=ImageColorAllocate($tmp_img,255,255,255);
				$tmp_blanc=imagecolortransparent($tmp_img,$tmp_blanc);
				ImageFilledRectangle($tmp_img,0,0,$tmp_width,$tmp_height,$tmp_blanc);
				ImageTTFText($tmp_img, $t, 0,0,$tmp_height,$tmp_noir, $font,$text);
				$allblanc=true;
				$sx = $sy = $ex = $ey = -1;
				for ($y = 0; $y < $tmp_height; $y++) {
					for ($x = 0; $x < $tmp_width; $x++) {
						$rgb = ImageColorAt($tmp_img, $x, $y);
						if ($rgb !=$tmp_blanc) {
							$allblanc=false;
							if ($sy == -1) $sy = $y;
							else $ey = $y;
							if ($sx == -1) $sx = $x;
							else {
								if ($x < $sx) $sx = $x;
								else if ($x > $ex) $ex = $x;
							}
						}
					}
				}
				$nx = abs($ex - $sx);
				$ny = abs($ey - $sy);
				$img = ImageCreate (max ($nx + 4, 1), max ($ny + 4, 1));
				$blanc = ImageColorAllocate ($img, 255, 255, 255);
				$blanc = imagecolortransparent ($img, $blanc);
				ImageFilledRectangle($img,0,0,$nx+4,$ny+4,$blanc);
				ImageCopy($img,$tmp_img,2,2,$sx,$sy,min($nx+2,$tmp_width-$sx),min($ny+2,$tmp_height-$sy));
				break;
			case 'acute':
			case 'arc':
			case '_b':
			case 'breve':
			case 'dot':
			case 'rightharpoonup':
			case 'leftharpoonup':
			case 'rightharpoondown':
			case 'leftharpoondown':
			case 'leftarrow':
			case 'leftrightarrow':
			case 'grave':
			case 'tilde':
			case 'hat':
				$font =self::$dirfonts."/".self::$fontsmath[$text].".ttf";
				$t=$high;
				$text = self::$symbols[$text];
				$tmp_dim = ImageTTFBBox($t, 0, $font , $text);
				$tmp_width = abs($tmp_dim[2] - $tmp_dim[0]);
				$tmp_height = abs($tmp_dim[3] - $tmp_dim[5])*4;
				$tmp_img = ImageCreate(max($tmp_width,1), max($tmp_height,1));
				$tmp_noir=ImageColorAllocate($tmp_img,0,0,0);
				$tmp_blanc=ImageColorAllocate($tmp_img,255,255,255);
				$tmp_blanc=imagecolortransparent($tmp_img,$tmp_blanc);
				ImageFilledRectangle($tmp_img,0,0,$tmp_width,$tmp_height,$tmp_blanc);
				ImageTTFText($tmp_img, $t, 0,0,$tmp_height,$tmp_noir, $font,$text);
				$allblanc=true;
				$img = $tmp_img;
				$sx = $sy = $ex = $ey = -1;
				for ($y = 0; $y < $tmp_height; $y++) {
					for ($x = 0; $x < $tmp_width; $x++) {
						$rgb = ImageColorAt($tmp_img, $x, $y);
						if ($rgb !=$tmp_blanc) {
							$allblanc=false;
							if ($sy == -1) $sy = $y;
							else $ey = $y;	
							if ($sx == -1) $sx = $x;
							else {
								if ($x < $sx) $sx = $x;
								else if ($x > $ex) $ex = $x;
							}
						}
					}
				}
				$nx = abs($ex - $sx);
				$ny = abs($ey - $sy);
				$img = ImageCreate(max($nx+4,1),max($ny+4,1));
				$blanc=ImageColorAllocate($img,255,255,255);
				$blanc=imagecolortransparent($img,$blanc);
				ImageFilledRectangle($img,0,0,$nx+4,$ny+4,$blanc);
				ImageCopy($img,$tmp_img,2,2,$sx,$sy,min($nx+2,$tmp_width-$sx),min($ny+2,$tmp_height-$sy));
				break;
			case 'ddot':
				$font =self::$dirfonts."/".self::$fontsmath['dot'].".ttf";
				$t=$high;
				$text = self::$symbols['dot'].self::$symbols ['dot'];
				$tmp_dim = ImageTTFBBox($t, 0, $font , $text);
				$tmp_width = abs($tmp_dim[2] - $tmp_dim[0]);
				$tmp_height = abs($tmp_dim[3] - $tmp_dim[5])*4;
				$tmp_img = ImageCreate(max($tmp_width,1), max($tmp_height,1));
				$tmp_noir=ImageColorAllocate($tmp_img,0,0,0);
				$tmp_blanc=ImageColorAllocate($tmp_img,255,255,255);
				$tmp_blanc=imagecolortransparent($tmp_img,$tmp_blanc);
				ImageFilledRectangle($tmp_img,0,0,$tmp_width,$tmp_height,$tmp_blanc);
				ImageTTFText($tmp_img, $t, 0,0,$tmp_height,$tmp_noir, $font,$text);
				$allblanc=true;
				$img = $tmp_img;
				$sx = $sy = $ex = $ey = -1;
				for ($y = 0; $y < $tmp_height; $y++) {
					for ($x = 0; $x < $tmp_width; $x++) {
						$rgb = ImageColorAt($tmp_img, $x, $y);
						if ($rgb !=$tmp_blanc) {
							$allblanc=false;
							if ($sy == -1) $sy = $y;
							else $ey = $y;	
							if ($sx == -1) $sx = $x;
							else {
								if ($x < $sx) $sx = $x;
								else if ($x > $ex) $ex = $x;
							}
						}
					}
				}
				$nx = abs($ex - $sx);
				$ny = abs($ey - $sy);
				$img = ImageCreate(max($nx+4,1),max($ny+4,1));
				$blanc=ImageColorAllocate($img,255,255,255);
				$blanc=imagecolortransparent($img,$blanc);
				ImageFilledRectangle($img,0,0,$nx+4,$ny+4,$blanc);
				ImageCopy($img,$tmp_img,2,2,$sx,$sy,min($nx+2,$tmp_width-$sx),min($ny+2,$tmp_height-$sy));
				break;
			case 'dddot':
				$font =self::$dirfonts."/".self::$fontsmath['dot'].".ttf";
				$t=$high;
				$text = self::$symbols['dot'].self::$symbols ['dot'].self::$symbols ['dot'];
				$tmp_dim = ImageTTFBBox($t, 0, $font , $text);
				$tmp_width = abs($tmp_dim[2] - $tmp_dim[0]);
				$tmp_height = abs($tmp_dim[3] - $tmp_dim[5])*4;
				$tmp_img = ImageCreate(max($tmp_width,1), max($tmp_height,1));
				$tmp_noir=ImageColorAllocate($tmp_img,0,0,0);
				$tmp_blanc=ImageColorAllocate($tmp_img,255,255,255);
				$tmp_blanc=imagecolortransparent($tmp_img,$tmp_blanc);
				ImageFilledRectangle($tmp_img,0,0,$tmp_width,$tmp_height,$tmp_blanc);
				ImageTTFText($tmp_img, $t, 0,0,$tmp_height,$tmp_noir, $font,$text);
				$allblanc=true;
				$img = $tmp_img;
				$sx = $sy = $ex = $ey = -1;
				for ($y = 0; $y < $tmp_height; $y++) {
					for ($x = 0; $x < $tmp_width; $x++) {
						$rgb = ImageColorAt($tmp_img, $x, $y);
						if ($rgb !=$tmp_blanc) {
							$allblanc=false;
							if ($sy == -1) $sy = $y;
							else $ey = $y;	
							if ($sx == -1) $sx = $x;
							else {
								if ($x < $sx) $sx = $x;
								else if ($x > $ex) $ex = $x;
							}
						}
					}
				}
				$nx = abs($ex - $sx);
				$ny = abs($ey - $sy);
				$img = ImageCreate(max($nx+4,1),max($ny+4,1));
				$blanc=ImageColorAllocate($img,255,255,255);
				$blanc=imagecolortransparent($img,$blanc);
				ImageFilledRectangle($img,0,0,$nx+4,$ny+4,$blanc);
				ImageCopy($img,$tmp_img,2,2,$sx,$sy,min($nx+2,$tmp_width-$sx),min($ny+2,$tmp_height-$sy));
				break;
			case 'ddddot':
				$font =self::$dirfonts."/".self::$fontsmath['dot'].".ttf";
				$t=$high;
				$text = self::$symbols['dot'].self::$symbols ['dot'].self::$symbols ['dot'].self::$symbols ['dot'];
				$tmp_dim = ImageTTFBBox($t, 0, $font , $text);
				$tmp_width = abs($tmp_dim[2] - $tmp_dim[0]);
				$tmp_height = abs($tmp_dim[3] - $tmp_dim[5])*4;
				$tmp_img = ImageCreate(max($tmp_width,1), max($tmp_height,1));
				$tmp_noir=ImageColorAllocate($tmp_img,0,0,0);
				$tmp_blanc=ImageColorAllocate($tmp_img,255,255,255);
				$tmp_blanc=imagecolortransparent($tmp_img,$tmp_blanc);
				ImageFilledRectangle($tmp_img,0,0,$tmp_width,$tmp_height,$tmp_blanc);
				ImageTTFText($tmp_img, $t, 0,0,$tmp_height,$tmp_noir, $font,$text);
				$allblanc=true;
				$img = $tmp_img;
				$sx = $sy = $ex = $ey = -1;
				for ($y = 0; $y < $tmp_height; $y++) {
					for ($x = 0; $x < $tmp_width; $x++) {
						$rgb = ImageColorAt($tmp_img, $x, $y);
						if ($rgb !=$tmp_blanc) {
							$allblanc=false;
							if ($sy == -1) $sy = $y;
							else $ey = $y;	
							if ($sx == -1) $sx = $x;
							else {
								if ($x < $sx) $sx = $x;
								else if ($x > $ex) $ex = $x;
							}
						}
					}
				}
				$nx = abs($ex - $sx);
				$ny = abs($ey - $sy);
				$img = ImageCreate(max($nx+4,1),max($ny+4,1));
				$blanc=ImageColorAllocate($img,255,255,255);
				$blanc=imagecolortransparent($img,$blanc);
				ImageFilledRectangle($img,0,0,$nx+4,$ny+4,$blanc);
				ImageCopy($img,$tmp_img,2,2,$sx,$sy,min($nx+2,$tmp_width-$sx),min($ny+2,$tmp_height-$sy));
				break;
			case '_dintegral':
			case '_tintegral':
				if(isset(self::$fontsmath[$text])) $font = self::$dirfonts."/".self::$fontsmath[$text].".ttf";
				elseif (is_number($text)) $font = self::$dirfonts."/cmr10.ttf";
				else $font = self::$dirfonts."/cmmi10.ttf";
				$t = 6;
				if (isset (self::$symbols [$text])) $text = self::$symbols [$text];
				do {
					$tmp_dim = ImageTTFBBox($t, 0, $font , $text);
					$t += 1;
				}
				while ((abs ($tmp_dim [3] - $tmp_dim [5]) < 1.2 * $high));
				$tmp_width = abs($tmp_dim[2] - $tmp_dim[0])*2;
				$tmp_height = abs($tmp_dim[3] - $tmp_dim[5])*2;
				$tmp_img = ImageCreate(max($tmp_width,1), max($tmp_height,1));
				$tmp_noir=ImageColorAllocate($tmp_img,0,0,0);
				$tmp_blanc=ImageColorAllocate($tmp_img,255,255,255);
				$tmp_blanc=imagecolortransparent($tmp_img,$tmp_blanc);
				ImageFilledRectangle($tmp_img,0,0,$tmp_width,$tmp_height,$tmp_blanc);
				ImageTTFText($tmp_img, $t,0,5,$tmp_height/2,$tmp_noir, $font,$text);
				$img = $tmp_img;
				$allblanc = true;
				$sx = $sy = $ex = $ey = -1;
				for ($y = 0; $y < $tmp_height; $y++) {
					for ($x = 0; $x < $tmp_width; $x++) {
						$rgb = ImageColorAt($tmp_img, $x, $y);
						if ($rgb !=$tmp_blanc) {
							$allblanc=false;
							if ($sy == -1) $sy = $y;
							else $ey = $y;	
							if ($sx == -1) $sx = $x;
							else {
								if ($x < $sx) $sx = $x;
								else if ($x > $ex) $ex = $x;
							}
						}
					}
				}
				$nx = abs($ex - $sx);
				$ny = abs($ey - $sy);
				if ($allblanc) {
					$img = ImageCreate(1, max ($high, 1));
					$blanc = ImageColorAllocate($img, 255, 255, 255);
					$blanc = imagecolortransparent($img, $blanc);
					ImageFilledRectangle($img, 0, 0, 1, $high, $blanc);
				}
				else {
					$img = ImageCreate (max ($nx + 4, 1), max ($ny + 4, 1));
					$blanc = ImageColorAllocate ($img, 255, 255, 255);
					$blanc = imagecolortransparent ($img, $blanc);
					ImageFilledRectangle($img, 0, 0, $nx + 4, $ny + 4, $blanc);
					ImageCopy($img,$tmp_img,2,2,$sx,$sy,min($nx+2,$tmp_width-$sx),min($ny+2,$tmp_height-$sy));
				}
				break;
			default:
				if(isset(self::$fontsmath[$text])) $font = self::$dirfonts."/".self::$fontsmath[$text].".ttf";
				elseif (is_number($text)) $font = self::$dirfonts."/cmr10.ttf";
				else $font = self::$dirfonts."/cmmi10.ttf";
				$t = 6;
				if(isset(self::$symbols[$text])) $text = self::$symbols[$text];
				do {
					$tmp_dim = ImageTTFBBox($t, 0, $font , $text);
					$t += 1;
				}
				while ((abs ($tmp_dim [3] - $tmp_dim [5]) < $high));
				$tmp_width = abs($tmp_dim[2] - $tmp_dim[0])*2;
				$tmp_height = abs($tmp_dim[3] - $tmp_dim[5])*2;
				$tmp_img = ImageCreate(max($tmp_width,1), max($tmp_height,1));
				if (strcmp ($text, 'spaceSymbol') == 0) {
					$tmp_noir=ImageColorAllocate($tmp_img, 255, 255, 255);
				} else {
					$tmp_noir=ImageColorAllocate($tmp_img,0, 0, 0);
				}
				$tmp_blanc=ImageColorAllocate($tmp_img,255,255,255);
				$tmp_blanc=imagecolortransparent($tmp_img,$tmp_blanc);
				ImageFilledRectangle($tmp_img,0,0,$tmp_width,$tmp_height,$tmp_blanc);
				ImageTTFText($tmp_img, $t, 0,0,$tmp_height/4,$tmp_noir, $font,$text);
				// ImageTTFText($tmp_img, $t, 0,5,5,$tmp_noir, $font,$text);
				// $img=$tmp_img;
				$allblanc=true;
				$sx = $sy = $ex = $ey = -1;
				for ($y = 0; $y < $tmp_height; $y++) {
					for ($x = 0; $x < $tmp_width; $x++) {
						$rgb = ImageColorAt($tmp_img, $x, $y);
						if ($rgb !=$tmp_blanc) {
							$allblanc=false;
							$allblanc=false;
							if ($sy == -1) $sy = $y;
							else $ey = $y;	
							if ($sx == -1) $sx = $x;
							else {
								if ($x < $sx) $sx = $x;
								else if ($x > $ex) $ex = $x;
							}
						}
					}
				}
				$nx = abs($ex - $sx);
				$ny = abs($ey - $sy);
				if ($allblanc) {
					$img = ImageCreate (1, max ($high, 1));
					$blanc = ImageColorAllocate ($img, 255, 255, 255);
					$blanc = imagecolortransparent ($img, $blanc);
					ImageFilledRectangle ($img, 0, 0, 1, $high, $blanc);
				}
				else {
					$img = ImageCreate(max($nx+4,1),max($ny+4,1));
					$blanc = ImageColorAllocate($img,255,255,255);
					$blanc=imagecolortransparent($img,$blanc);
					ImageFilledRectangle($img,0,0,$nx+4,$ny+4,$blanc);
					ImageCopy($img,$tmp_img,2,2,$sx,$sy,min($nx+2,$tmp_width-$sx),min($ny+2,$tmp_height-$sy));
				}
				break;
		}
		// $rouge=ImageColorAllocate($img,255,0,0);
		// ImageRectangle($img,0,0,ImageSX($img)-1,ImageSY($img)-1,$rouge);
		return $img;
	}
	public static function declare_text ($text, $size) {
		self::getInstance ();
		$size = max ($size, 6);
		$text = stripslashes ($text);
		$font = self::$dirfonts."/cmr10.ttf";
		$htext = 'dg'.$text;
		$hdim = ImageTTFBBox ($size, 0, $font, $htext);
		$wdim = ImageTTFBBox ($size, 0, $font, $text); 
		$dx = max ($wdim [2], $wdim [4]) - min ($wdim [0], $wdim [6]) + ceil ($size / 8);
		$dy = max ($hdim [1], $hdim [3]) - min ($hdim [5], $hdim [7])+ ceil ($size / 8);
		$img = ImageCreate (max ($dx, 1), max ($dy, 1));
		$noir = ImageColorAllocate ($img, 0, 0, 0);
		$blanc = ImageColorAllocate ($img, 255, 255, 255);
		$blanc = imagecolortransparent ($img, $blanc);
		ImageFilledRectangle ($img, 0, 0, $dx, $dy, $blanc);
		// ImageRectangle ($img, 0, 0, $dx - 1, $dy - 1, $noir);
		ImageTTFText ($img, $size, $angle, 0, - min ($hdim [5], $hdim [7]), $noir, $font, $text);
		return $img;
	}
	public static function declareMath ($text, $size) {
		self::getInstance ();
		$size = max ($size, 6);
		$text = stripslashes ($text);
		$spaceFlag = false;
		if (strcmp ($text, 'spaceSymbol') == 0) $spaceFlag = true;
		if (isset (self::$fontsmath [$text])) $font = self::$dirfonts."/".self::$fontsmath[$text].".ttf";
		//elseif (ereg ("[a-zA-Z]", $text)) $font = self::$dirfonts."/cmmi10.ttf";
		elseif (ereg ("[a-zA-Z]", $text)) $font = self::$dirfonts."/FreeSerifItalic.ttf";
		else $font = self::$dirfonts."/cmr10.ttf";
		if (isset (self::$symbols [$text])) $text = self::$symbols [$text];
		$htext = 'dg'.$text;
		$hdim = ImageTTFBBox ($size, 0, $font, $htext);
		$wdim = ImageTTFBBox ($size, 0, $font, $text); 
		$dx = max ($wdim [2], $wdim [4]) - min ($wdim [0], $wdim [6])+ ceil ($size / 8);
		$dy = max ($hdim [1], $hdim [3]) - min ($hdim [5], $hdim [7])+ ceil ($size / 8);
		$img = ImageCreate (max ($dx, 1), max ($dy, 1));
		if ($spaceFlag) $noir = ImageColorAllocate ($img, 255, 255, 255);
		else $noir = ImageColorAllocate ($img, 0, 0, 0);
		$blanc = ImageColorAllocate ($img, 255, 255, 255);
		$blanc = imagecolortransparent ($img, $blanc);
		ImageFilledRectangle ($img, 0, 0, $dx, $dy, $blanc);
		// ImageRectangle ($img, 0, 0, $dx - 1, $dy - 1, $noir);
		ImageTTFText ($img, $size, 0, 0, - min ($hdim [5], $hdim [7]), $noir, $font, $text);
		return $img;
	}
	public static function parenthese ($height, $style) {
		$image = self::declare_symbol ($style, $height);
		return $image;
	}
	public static function alignement2 ($image1, $base1, $image2, $base2) {
		$width1 = imagesx ($image1);
		$height1 = imagesy ($image1);
		$width2 = imagesx ($image2);
		$height2 = imagesy ($image2);
		$over = max ($base1, $base2);
		$under = max ($height1 - $base1, $height2 - $base2);
		$width = $width1 + $width2;
		$height = $over + $under;
		$result = ImageCreate (max ($width, 1), max ($height, 1));
		$noir = ImageColorAllocate ($result, 0, 0, 0);
		$blanc = ImageColorAllocate ($result, 255, 255, 255);
		$blanc = imagecolortransparent ($result, $blanc);
		ImageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
		ImageCopy ($result, $image1, 0, $over - $base1, 0, 0,$width1, $height1);
		ImageCopy ($result, $image2, $width1, $over - $base2, 0, 0, $width2, $height2);
		// ImageRectangle ($result, 0, 0, $width - 1, $height - 1, $noir);
		return $result;
	}
	public static function alignement3 ($image1, $base1, $image2, $base2, $image3, $base3) {
		$width1 = imagesx ($image1);
		$height1 = imagesy ($image1);
		$width2 = imagesx ($image2);
		$height2 = imagesy ($image2);
		$width3 = imagesx ($image3);
		$height3 = imagesy ($image3);
		$over = max ($base1, $base2, $base3);
		$under = max ($height1 - $base1, $height2 - $base2, $height3 - $base3);
		$width = $width1 + $width2 + $width3;
		$height = $over + $under;
		$result = ImageCreate (max ($width, 1), max ($height, 1));
		$noir = ImageColorAllocate ($result, 0, 0, 0);
		$blanc = ImageColorAllocate ($result, 255, 255, 255);
		$blanc = imagecolortransparent ($result, $blanc);
		ImageFilledRectangle ($result, 0, 0, $width - 1, $height - 1, $blanc);
		ImageCopy ($result, $image1, 0, $over - $base1, 0, 0,$width1, $height1);
		ImageCopy ($result, $image2, $width1, $over - $base2, 0, 0, $width2, $height2);
		ImageCopy ($result, $image3, $width1 + $width2, $over - $base3, 0, 0, $width3, $height3);
		// ImageRectangle ($result, 0, 0, $width - 1, $height - 1, $noir);
		return $result;
	}
	/**
	 * Detects if the formula image already exists in the $dirimg cache directory. 
	 * In that case, the function returns a parameter (recorded in the name of the image file)
	 * which allows to align correctly the image with the text.
	 * 
	 * @param string $n
	 */
	private static function detectimg ($n) {
		$ret = 0;
		$handle = opendir (self::$dirimg);
		while ($fi = readdir ($handle)) {
			$info = pathinfo ($fi);
			if ($fi != "." && $fi != ".." && $info ["extension"] == "png" && ereg ("^math", $fi)) {
				list ($math, $v, $name) = explode ("_", $fi);
				if ($name == $n) {
					$ret = $v;
					break;
				}
			}
		}
		closedir ($handle);
		return $ret;
	}
	/**
	 * Creates the formula image (if the image is not in the cache) and returns the <img src=...></img> html code.
	 * 
	 * @param string	$text
	 * @param int			$size
	 * @param string	$pathtoimg
	 */
	private static function mathimage ($text, $size, $pathtoimg) {
		$nameimg = md5 (trim ($text).$size).'.png';
		$v = self::detectimg ($nameimg);
		$debugFlag = true;
		if ($v == 0 || $debugFlag) {
			// the image doesn't exist in the cache directory. we create it.
			$scannerManager = new PHPmathpublisher_Units_ScannerManager ();
			try {
				$formula = new PHPmathpublisher_Units_ExpressionMath ($scannerManager->doManage (trim ($text)));
			} catch (Exception 	$e) {
				$formula = new PHPmathpublisher_Units_ExpressionMath ($scannerManager->doManage ($e->msg));
			}
			$formula->design ($size);
			$v = 1000 - imagesy ($formula->image) + $formula->base_vertical + 3;
			// 1000+baseline ($v) is recorded in the name of the image
			ImagePNG ($formula->image, self::$dirimg."/math_".$v."_".$nameimg);
		}
		$valign = $v - 1000;
		return '<img src="'.$pathtoimg."math_".$v."_".$nameimg.'" style="vertical-align:'.$valign.'px;'.' display: inline-block ;" alt="'.$text.'" title="'.$text.'"/>';
	}
	/**
	 * 	THE MAIN METHOD
	 * 	1) the content of the math tags (<m></m>) are extracted in the $t variable (you can replace <m></m> by your
	 * own tag).
	 * 	2) the "mathimage" function replaces the $t code by <img src=...></img> according to this method :
	 * 		- if the image corresponding to the formula doesn't exist in the $dirimg cache directory
	 * (detectimg($nameimg)=0), the script creates the image and returns the "<img src=...></img>" code.
	 * 		- otherwise, the script returns only the <img src=...></img>" code.
	 * 	To align correctly the formula image with the text, the "valign" parameter of the image is required.
	 * That's why a parameter (1000+valign) is recorded in the name of the image file (the "detectimg" function
	 * returns this parameter if the image exists in the cache directory).
	 * 	To be sure that the name of the image file is unique and to allow the script to retrieve the valign parameter
	 * without re-creating the image, the syntax of the image filename is :
	 * 	math_(1000+valign)_md5(formulatext.size).png.(1000+valign is
	 * used instead of valign directly to avoid a negative number)
	 * 
	 * @param string 	$text
	 * @param int			$size
	 * @param string	$pathtoimg
	 */
	public function mathfilter ($text, $size, $pathtoimg) {
		self::getInstance ();
		$size = max ($size, 10);
		$size = min ($size, 24);
		preg_match_all ("|<m>(.*?)</m>|", $text, $regs, PREG_SET_ORDER);
		foreach ($regs as $math) {
			$t = str_replace ('<m>', '', $math [0]);
			$t = str_replace ('</m>', '', $t);
			$code = self::mathimage (trim($t), $size, $pathtoimg);
			$text = str_replace ($math[0], $code, $text);
		}	
		return $text;
	}
}
?>