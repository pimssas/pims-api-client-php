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
	private static function initClient () : Pims\Api\Client {
		try {
			return new Client(
					getenv('PIMS_API_PATH'),
					getenv('PIMS_API_USER'),
					getenv('PIMS_API_PASSWORD'));
		} catch (Exception $e) {
			self::fail($e->getMessage());
		}
	}
	
	/**
	 * Basic test on constructor
	 */
	public function testConstructorBasic () {
		self::assertInstanceOf(
				'Pims\Api\Client',
				self::initClient(),
				'Failed basic constructor test');
	}
	
	/**
	 * Extended test on constructor
	 */
	public function testConstructorExtended () {
		try {
			$client = new Client(
					getenv('PIMS_API_PATH'),
					getenv('PIMS_API_USER'),
					getenv('PIMS_API_PASSWORD'),
					Client::DEFAULT_VERSION);
		} catch (Exception $e) {
			self::fail($e->getMessage());
		}
		
		self::assertInstanceOf(
				'Pims\Api\Client',
				$client,
				'Failed extended constructor test');
		self::assertSame(
				Client::DEFAULT_VERSION,
				$client->getVersion(),
				'Failed extended constructor test');
	}
	
	/**
	 * Test for the method setVersion()
	 */
	public function testSetVersion () {
		$version = 'v' . uniqId();
		try {
			$client = self::initClient();
			$client->setVersion($version);
		} catch (Exception $e) {
			self::fail($e->getMessage());
		}
		
		self::assertSame(
				$version,
				$client->getVersion(),
				'Failed of the method setVersion');
	}
	
	/**
	 * Test for the method setLanguage()
	 */
	public function testSetLanguage () {
		$language = 'fr';
		try {
			$client = self::initClient();
			$client->setLanguage($language);
		} catch (Exception $e) {
			self::fail($e->getMessage());
		}
		
		self::assertSame(
				$language,
				$client->getHalClient()->getHeader('Accept-Language')[0],
				'Failed of the method setLanguage');
	}
	
	/**
	 * Test for the method getOne()
	 */
	public function testGetOne () {
		try {
			$data = self::initClient()->getOne(
					Endpoint::EVENTS,
					2127);
		} catch (Exception $e) {
			self::fail($e->getMessage());
		}
		
		self::assertInstanceOf(
				'Jsor\HalClient\HalResource',
				$data,
				'Failed of the method getOne');
		self::assertAttributeCount(
				23,
				'properties',
				$data);
		self::assertAttributeCount(
				12,
				'properties',
				$data->getFirstResource('venue'));
		self::assertSame(
				'ALAN WILSON',
				mb_strtoupper($data->getProperty('label')));
	}
	
	/**
	 * Test for the method getAll()
	 */
	public function testGetAll () {
		try {
			$data = $this->initClient()->getAll(Endpoint::EVENTS);
		} catch (Exception $e) {
			self::fail($e->getMessage());
		}
		
		self::assertInstanceOf(
				'Jsor\HalClient\HalResource',
				$data,
				'Failed of the method getAll');
	}
}