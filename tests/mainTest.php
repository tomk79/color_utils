<?php

class mainTest extends PHPUnit_Framework_TestCase{

	public function setup(){
		mb_internal_encoding('UTF-8');
	}


	/**
	 * Initialize
	 */
	public function testInitialize(){
		$main = new \tomk79\colorUtils\main();
		$this->assertSame(is_object($main), true);
	}

}
