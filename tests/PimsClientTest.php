<?php

use PHPUnit\Framework\TestCase;
use Pims\Api\Client;

class PimsClientTest extends TestCase {

	public function test__construct() {
		try {
			$client = new Client('https://demo.pims.io/api/v1', getenv('PIMS_API_USER'), getenv('PIMS_API_PASSWORD'));
		} catch (\Exception $e) {
			$this->assertTrue(false, $e->getMessage());
		}

		self::assertTrue(is_object($client), 'Contruction OK');
		return $client;
	}

	public function testGetOne() {

		try {
			$client = new Client('https://demo.pims.io/api/v1', getenv('PIMS_API_USER'), getenv('PIMS_API_PASSWORD'));

			$data = $client->getOne('/events', 2127);
		} catch (\Exception $e) {
			$this->assertTrue(false, $e->getMessage());
		}

		self::assertTrue(is_object($data), 'getOne OK');
	}

	public function testGetAll() {
		try {
			$client = new Client('https://demo.pims.io/api/v1', getenv('PIMS_API_USER'), getenv('PIMS_API_PASSWORD'));

			$data = $client->getAll('/events');
		} catch (\Exception $e) {
			$this->assertTrue(false, $e->getMessage());
		}

		self::assertTrue(is_object($data), 'getAll OK');
	}

}