<?php

use PHPUnit\Framework\TestCase;
use Pims\Api\Client;
use Pims\Api\Endpoint;

class PimsClientTest extends TestCase {
	
	/**
	 * @return Client
	 * @throws \Pims\Api\Exception\ClientException
	 */
	private function initClient () {
		return new Client(
				'https://demo.pims.io/api',
				getenv('PIMS_API_USER'),
				getenv('PIMS_API_PASSWORD'));
	}
	
	public function test__construct () {
		try {
			$client = $this->initClient();
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}

		self::assertTrue(is_object($client), 'Default __construct() OK');
	}
	
	public function test__construct2 () {
		try {
			$client = new Client(
					'https://demo.pims.io/api',
					getenv('PIMS_API_USER'),
					getenv('PIMS_API_PASSWORD'),
					'fr',
					Client::DEFAULT_VERSION);
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}
		
		self::assertTrue(is_object($client), 'Complete __construct() OK');
	}

	public function testGetOne () {
		try {
			$client = $this->initClient();
			
			$data = $client->getOne(Endpoint::EVENTS, 2127);
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}
		
		self::assertTrue(is_object($data));
		self::assertAttributeCount(13, 'properties', $data);
		self::assertAttributeCount(11, 'properties', $data->getFirstResource('venue'));
		self::assertEquals('ALAN WILSON', mb_strtoupper($data->getProperty('label')));
	}

	public function testGetAll () {
		try {
			$client = $this->initClient();
			
			$data = $client->getAll(Endpoint::EVENTS);
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}

		self::assertTrue(is_object($data), 'getAll() OK');
	}
}