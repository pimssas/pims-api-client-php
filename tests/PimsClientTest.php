<?php

use PHPUnit\Framework\TestCase;
use Pims\Api\Client;
use Pims\Api\Endpoint;

class PimsClientTest extends TestCase {
	
	/**
	 * Initialize the client
	 *
	 * @return Client
	 */
	public function initClient () : Pims\Api\Client {
		try {
			return new Client(
					getenv('PIMS_API_PATH'),
					getenv('PIMS_API_USER'),
					getenv('PIMS_API_PASSWORD'));
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}
	}
	
	/**
	 * Basic test on constructor
	 */
	public function testConstructBase () {
		$client = $this->initClient();
		
		self::assertInstanceOf(
				'Pims\Api\Client',
				$client,
				'Failed first constructor test');
	}
	
	/**
	 * Extended test on constructor
	 */
	public function testConstructExtended () {
		try {
			$client = new Client(
					getenv('PIMS_API_PATH'),
					getenv('PIMS_API_USER'),
					getenv('PIMS_API_PASSWORD'),
					Client::DEFAULT_VERSION);
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}
		
		self::assertInstanceOf(
				'Pims\Api\Client',
				$client,
				'Failed second constructor test ');
	}
	
	/**
	 * Test of the method setVersion
	 */
	public function testSetVersion () {
		$version = 'v' . uniqId();
		try {
			$client = $this->initClient();
			$client->setVersion($version);
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}
		
		self::assertSame(
				$version,
				$client->getVersion(),
				'Failed of the method setVersion');
	}
	
	/**
	 * Test of the method setLanguage
	 */
	public function testSetLanguage () {
		$language = 'fr';
		try {
			$client = $this->initClient();
			$client->setLanguage($language);
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}
		
		self::assertSame(
				$language,
				$client->getHalClient()->getHeader('Accept-Language')[0],
				'Failed of the method setLanguage');
	}
	
	/**
	 * Test of the method GetOne
	 */
	public function testGetOne () {
		try {
			$data = $this->initClient()->getOne(
					Endpoint::EVENTS,
					2127);
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}
		
		self::assertInstanceOf(
				'Jsor\HalClient\HalResource',
				$data,
				'Failed of the method getOne');
		self::assertAttributeCount(
				16,
				'properties',
				$data);
		self::assertAttributeCount(
				11,
				'properties',
				$data->getFirstResource('venue'));
		self::assertSame(
				'ALAN WILSON',
				mb_strtoupper($data->getProperty('label')));
	}
	
	/**
	 * Test of the method GetAll
	 */
	public function testGetAll () {
		try {
			$data = $this->initClient()->getAll(Endpoint::EVENTS);
		} catch (\Exception $e) {
			self::assertTrue(false, $e->getMessage());
		}
		self::assertInstanceOf(
				'Jsor\HalClient\HalResource',
				$data,
				'Failed of the method getAll');
	}
}