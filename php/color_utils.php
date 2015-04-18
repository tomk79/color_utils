<?php
/**
 * tomk79/color_utils
 * 
 * @author Tomoya Koyanagi <tomk79@gmail.com>
 */
namespace tomk79\color_utils;

/**
 * tomk79/color_utils core class
 * 
 * @author Tomoya Koyanagi <tomk79@gmail.com>
 */
class main{

	/**
	 * constructor
	 */
	public function __construct(){
	}



	#------------------------------------------------------------------------------------------------------------------
	#	カラーユーティリティ

	/**
	 * 16進数の色コードからRGBの10進数を得る。
	 *
	 * @param int|string $txt_hex 16進数色コード
	 * @return array 10進数のRGB色コードを格納した連想配列
	 */
	public function hex2rgb( $txt_hex ){
		if( is_int( $txt_hex ) ){
			$txt_hex = dechex( $txt_hex );
			$txt_hex = '#'.str_pad( $txt_hex , 6 , '0' , STR_PAD_LEFT );
		}
		$txt_hex = preg_replace( '/^#/' , '' , $txt_hex );
		if( strlen( $txt_hex ) == 3 ){
			#	長さが3バイトだったら
			if( !preg_match( '/^([0-9a-f])([0-9a-f])([0-9a-f])$/si' , $txt_hex , $matched ) ){
				return	false;
			}
			$matched[1] = $matched[1].$matched[1];
			$matched[2] = $matched[2].$matched[2];
			$matched[3] = $matched[3].$matched[3];
		}elseif( strlen( $txt_hex ) == 6 ){
			#	長さが6バイトだったら
			if( !preg_match( '/^([0-9a-f]{2})([0-9a-f]{2})([0-9a-f]{2})$/si' , $txt_hex , $matched ) ){
				return	false;
			}
		}else{
			return	false;
		}
		$RTN = array(
			"r"=>eval( 'return 0x'.$matched[1].';' ) ,
			"g"=>eval( 'return 0x'.$matched[2].';' ) ,
			"b"=>eval( 'return 0x'.$matched[3].';' ) ,
		);
		return	$RTN;
	}

	/**
	 * RGBの10進数の色コードから16進数を得る。
	 *
	 * @param int $int_r 10進数の色コード(Red)
	 * @param int $int_g 10進数の色コード(Green)
	 * @param int $int_b 10進数の色コード(Blue)
	 * @return string 16進数の色コード
	 */
	public function rgb2hex( $int_r , $int_g , $int_b ){
		$hex_r = dechex( $int_r );
		$hex_g = dechex( $int_g );
		$hex_b = dechex( $int_b );
		if( strlen( $hex_r ) > 2 || strlen( $hex_g ) > 2 || strlen( $hex_b ) > 2 ){
			return	false;
		}
		$RTN = '#';
		$RTN .= str_pad( $hex_r , 2 , '0' , STR_PAD_LEFT );
		$RTN .= str_pad( $hex_g , 2 , '0' , STR_PAD_LEFT );
		$RTN .= str_pad( $hex_b , 2 , '0' , STR_PAD_LEFT );
		return	$RTN;
	}

	/**
	 * 色相を調べる。
	 * 
	 * @param int|string $txt_hex 16進数の色コード
	 * @param int $int_round 小数点以下を丸める桁数
	 * @return int 色相値
	 */
	public function get_hue( $txt_hex , $int_round = 0 ){
		$int_round = intval( $int_round );
		if( $int_round < 0 ){ return false; }

		$rgb = $this->hex2rgb( $txt_hex );
		if( $rgb === false ){ return false; }

		foreach( $rgb as $key=>$val ){
			$rgb[$key] = $val/255;
		}

		$hue = 0;
		if( $rgb['r'] == $rgb['g'] && $rgb['g'] == $rgb['b'] ){//PxFW 0.6.2
			return	0;
		}
		if( $rgb['r'] >= $rgb['g'] && $rgb['g'] >= $rgb['b'] ){
			#	R>G>B
			$hue = 60 * ( ($rgb['g']-$rgb['b'])/($rgb['r']-$rgb['b']) );

		}elseif( $rgb['g'] >= $rgb['r'] && $rgb['r'] >= $rgb['b'] ){
			#	G>R>B
			$hue = 60 * ( 2-( ($rgb['r']-$rgb['b'])/($rgb['g']-$rgb['b']) ) );

		}elseif( $rgb['g'] >= $rgb['b'] && $rgb['b'] >= $rgb['r'] ){
			#	G>B>R
			$hue = 60 * ( 2+( ($rgb['b']-$rgb['r'])/($rgb['g']-$rgb['r']) ) );

		}elseif( $rgb['b'] >= $rgb['g'] && $rgb['g'] >= $rgb['r'] ){
			#	B>G>R
			$hue = 60 * ( 4-( ($rgb['g']-$rgb['r'])/($rgb['b']-$rgb['r']) ) );

		}elseif( $rgb['b'] >= $rgb['r'] && $rgb['r'] >= $rgb['g'] ){
			#	B>R>G
			$hue = 60 * ( 4+( ($rgb['r']-$rgb['g'])/($rgb['b']-$rgb['g']) ) );

		}elseif( $rgb['r'] >= $rgb['b'] && $rgb['b'] >= $rgb['g'] ){
			#	R>B>G
			$hue = 60 * ( 6-( ($rgb['b']-$rgb['g'])/($rgb['r']-$rgb['g']) ) );

		}else{
			return	0;
		}

		if( $int_round ){
			$hue = round( $hue , $int_round );
		}else{
			$hue = intval( $hue );
		}
		return $hue;
	}

	/**
	 * 彩度を調べる。
	 * 
	 * @param int|string $txt_hex 16進数の色コード
	 * @param int $int_round 小数点以下を丸める桁数
	 * @return int 彩度値
	 */
	public function get_saturation( $txt_hex , $int_round = 0 ){
		$int_round = intval( $int_round );
		if( $int_round < 0 ){ return false; }

		$rgb = $this->hex2rgb( $txt_hex );
		if( $rgb === false ){ return false; }

		sort( $rgb );
		$minval = $rgb[0];
		$maxval = $rgb[2];

		if( $minval == 0 && $maxval == 0 ){
			#	真っ黒だったら
			return	0;
		}

		$saturation = ( 100-( $minval/$maxval * 100 ) );

		if( $int_round ){
			$saturation = round( $saturation , $int_round );
		}else{
			$saturation = intval( $saturation );
		}
		return $saturation;
	}

	/**
	 * 明度を調べる。
	 * 
	 * @param int|string $txt_hex 16進数の色コード
	 * @param int $int_round 小数点以下を丸める桁数
	 * @return int 明度値
	 */
	public function get_brightness( $txt_hex , $int_round = 0 ){
		$int_round = intval( $int_round );
		if( $int_round < 0 ){ return false; }

		$rgb = $this->hex2rgb( $txt_hex );
		if( $rgb === false ){ return false; }

		sort( $rgb );
		$maxval = $rgb[2];

		$brightness = ( $maxval * 100/255 );

		if( $int_round ){
			$brightness = round( $brightness , $int_round );
		}else{
			$brightness = intval( $brightness );
		}
		return $brightness;
	}

	/**
	 * 16進数のRGBコードからHSB値を得る。
	 * 
	 * @param int|string $txt_hex 16進数の色コード
	 * @param int $int_round 小数点以下を丸める桁数
	 * @return array 色相値、彩度値、明度値を含む連想配列
	 */
	public function hex2hsb( $txt_hex , $int_round = 0 ){
		$int_round = intval( $int_round );
		if( $int_round < 0 ){ return false; }

		$hsb = array(
			'h'=>$this->get_hue( $txt_hex , $int_round ) ,
			's'=>$this->get_saturation( $txt_hex , $int_round ) ,
			'b'=>$this->get_brightness( $txt_hex , $int_round ) ,
		);
		return	$hsb;
	}

	/**
	 * RGB値からHSB値を得る。
	 * 
	 * @param int $int_r 10進数の色コード(Red)
	 * @param int $int_g 10進数の色コード(Green)
	 * @param int $int_b 10進数の色コード(Blue)
	 * @param int $int_round 小数点以下を丸める桁数
	 * @return array 色相値、彩度値、明度値を含む連想配列
	 */
	public function rgb2hsb( $int_r , $int_g , $int_b , $int_round = 0 ){
		$int_round = intval( $int_round );
		if( $int_round < 0 ){ return false; }

		$txt_hex = $this->rgb2hex( $int_r , $int_g , $int_b );
		$hsb = array(
			'h'=>$this->get_hue( $txt_hex , $int_round ) ,
			's'=>$this->get_saturation( $txt_hex , $int_round ) ,
			'b'=>$this->get_brightness( $txt_hex , $int_round ) ,
		);
		return	$hsb;
	}

	/**
	 * HSB値からRGB値を得る。
	 * 
	 * @param int $int_hue 10進数の色相値
	 * @param int $int_saturation 10進数の彩度値
	 * @param int $int_brightness 10進数の明度値
	 * @param int $int_round 小数点以下を丸める桁数
	 * @return array 10進数のRGB色コードを格納した連想配列
	 */
	public function hsb2rgb( $int_hue , $int_saturation , $int_brightness , $int_round = 0 ){
		$int_round = intval( $int_round );
		if( $int_round < 0 ){ return false; }

		$int_hue = round( $int_hue%360 , 3 );
		$int_saturation = round( $int_saturation , 3 );
		$int_brightness = round( $int_brightness , 3 );

		$maxval = round( $int_brightness * ( 255/100 ) , 3 );
		$minval = round( $maxval - ( $maxval * $int_saturation/100 ) , 3 );

		$keyname = array( 'r' , 'g' , 'b' );
		if(      $int_hue >=   0 && $int_hue <  60 ){
			$keyname = array( 'r' , 'g' , 'b' );
			$midval = $minval + ( ($maxval - $minval) * ( ($int_hue -  0)/60 ) );
		}elseif( $int_hue >=  60 && $int_hue < 120 ){
			$keyname = array( 'g' , 'r' , 'b' );
			$midval = $maxval - ( ($maxval - $minval) * ( ($int_hue - 60)/60 ) );
		}elseif( $int_hue >= 120 && $int_hue < 180 ){
			$keyname = array( 'g' , 'b' , 'r' );
			$midval = $minval + ( ($maxval - $minval) * ( ($int_hue -120)/60 ) );
		}elseif( $int_hue >= 180 && $int_hue < 240 ){
			$keyname = array( 'b' , 'g' , 'r' );
			$midval = $maxval - ( ($maxval - $minval) * ( ($int_hue -180)/60 ) );
		}elseif( $int_hue >= 240 && $int_hue < 300 ){
			$keyname = array( 'b' , 'r' , 'g' );
			$midval = $minval + ( ($maxval - $minval) * ( ($int_hue -240)/60 ) );
		}elseif( $int_hue >= 300 && $int_hue < 360 ){
			$keyname = array( 'r' , 'b' , 'g' );
			$midval = $maxval - ( ($maxval - $minval) * ( ($int_hue -300)/60 ) );
		}

		$tmp_rgb = array();
		if( $int_round ){
			$tmp_rgb = array(
				$keyname[0]=>round( $maxval , $int_round ) ,
				$keyname[1]=>round( $midval , $int_round ) ,
				$keyname[2]=>round( $minval , $int_round ) ,
			);
		}else{
			$tmp_rgb = array(
				$keyname[0]=>intval( $maxval ) ,
				$keyname[1]=>intval( $midval ) ,
				$keyname[2]=>intval( $minval ) ,
			);
		}
		$rgb = array( 'r'=>$tmp_rgb['r'] , 'g'=>$tmp_rgb['g'] , 'b'=>$tmp_rgb['b'] );
		return	$rgb;
	}
	/**
	 * HSB値から16進数のRGBコードを得る。
	 * 
	 * @param int $int_hue 10進数の色相値
	 * @param int $int_saturation 10進数の彩度値
	 * @param int $int_brightness 10進数の明度値
	 * @param int $int_round 小数点以下を丸める桁数
	 * @return string 16進数の色コード
	 */
	public function hsb2hex( $int_hue , $int_saturation , $int_brightness , $int_round = 0 ){
		$rgb = $this->hsb2rgb( $int_hue , $int_saturation , $int_brightness , $int_round );
		$hex = $this->rgb2hex( $rgb['r'] , $rgb['g'] , $rgb['b'] );
		return	$hex;
	}

}