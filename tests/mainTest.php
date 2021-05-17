<?php

class mainTest extends PHPUnit_Framework_TestCase{

	public function setup(){
		mb_internal_encoding('UTF-8');
	}


	/**
	 * Initialize
	 */
	public function testInitialize(){
		$colorUtils = new \tomk79\colorUtils\main();
		$this->assertSame(is_object($colorUtils), true);
	}

	/**
	 * hex2rgb()
	 */
	public function testHex2rgb(){
		$colorUtils = new \tomk79\colorUtils\main();

		$this->assertSame($colorUtils->hex2rgb('#fff'), array(
			'r' => 255,
			'g' => 255,
			'b' => 255,
		));
		$this->assertSame($colorUtils->hex2rgb('#ffffff'), array(
			'r' => 255,
			'g' => 255,
			'b' => 255,
		));
		$this->assertSame($colorUtils->hex2rgb('#000'), array(
			'r' => 0,
			'g' => 0,
			'b' => 0,
		));
		$this->assertSame($colorUtils->hex2rgb('#000000'), array(
			'r' => 0,
			'g' => 0,
			'b' => 0,
		));
		$this->assertSame($colorUtils->hex2rgb('#123'), array(
			'r' => 17,
			'g' => 34,
			'b' => 51,
		));
	}

	/**
	 * rgb2hex()
	 */
	public function testRgb2hex(){
		$colorUtils = new \tomk79\colorUtils\main();

		$this->assertSame($colorUtils->rgb2hex(255, 255, 255), '#ffffff');
		$this->assertSame($colorUtils->rgb2hex(0, 0, 0), '#000000');
	}

	/**
	 * get_hue()
	 */
	public function testGetHue(){
		$colorUtils = new \tomk79\colorUtils\main();

		$this->assertSame($colorUtils->get_hue('#f93'), 29);
		$this->assertSame($colorUtils->get_hue('#ff9933'), 29);
	}

	/**
	 * get_saturation()
	 */
	public function testGetSaturation(){
		$colorUtils = new \tomk79\colorUtils\main();

		$this->assertSame($colorUtils->get_saturation('#f93'), 80);
		$this->assertSame($colorUtils->get_saturation('#ff9933'), 80);
	}

	/**
	 * get_brightness()
	 */
	public function testGetBrightness(){
		$colorUtils = new \tomk79\colorUtils\main();

		$this->assertSame($colorUtils->get_brightness('#f93'), 100);
		$this->assertSame($colorUtils->get_brightness('#ff9933'), 100);
	}

	/**
	 * hex2hsb()
	 */
	public function testHex2hsb(){
		$colorUtils = new \tomk79\colorUtils\main();

		$this->assertSame($colorUtils->hex2hsb('#f93'), array(
			"h" => 29,
			"s" => 80,
			"b" => 100,
		));
		$this->assertSame($colorUtils->hex2hsb('#ff9933'), array(
			"h" => 29,
			"s" => 80,
			"b" => 100,
		));
	}

	/**
	 * rgb2hsb()
	 */
	public function testRgb2hsb(){
		$colorUtils = new \tomk79\colorUtils\main();

		$this->assertSame($colorUtils->rgb2hsb(255, 255, 255), array(
			"h" => 0,
			"s" => 0,
			"b" => 100,
		));
		$this->assertSame($colorUtils->rgb2hsb(0, 0, 0), array(
			"h" => 0,
			"s" => 0,
			"b" => 0,
		));

	}

	/**
	 * hsb2rgb()
	 */
	public function testHsb2rgb(){
		$colorUtils = new \tomk79\colorUtils\main();
		$this->assertSame( $colorUtils->hsb2rgb(100, 100, 100), array(
			"r" => 85,
			"g" => 255,
			"b" => 0,
		) );
	}

	/**
	 * hsb2hex()
	 */
	public function testHsb2hex(){
		$colorUtils = new \tomk79\colorUtils\main();

		$this->assertSame($colorUtils->hsb2hex(100, 100, 100), '#55ff00');
	}

}
