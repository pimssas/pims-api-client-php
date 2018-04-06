<?php
/**
 * Created by PhpStorm.
 * User: pims-banane
 * Date: 05/04/18
 * Time: 12:04
 */

use PHPUnit\Framework\TestCase;
use Pims\Api\Client;

class PimsClientTest extends TestCase {

	var $client;

	public function test__construct()
	{
		try {
			$this->client = new Client('http://api.opensupporter.org/', 'test', 'test');
		} catch (\Exception $e) {

		}

		self::assertTrue(is_object($this->client), 'Contruction OK');
	}
}