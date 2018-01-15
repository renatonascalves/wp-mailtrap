<?php
/**
 * Class WP_Mailtrap_TestCase
 *
 * @package Wp_Mailtrap
 */

class WP_Mailtrap_TestCase extends WP_UnitTestCase {

	function test_class() {
		$this->assertTrue( class_exists( 'wp_Mailtrap' ) );
	}
}
