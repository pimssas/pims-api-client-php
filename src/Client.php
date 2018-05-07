<?php

namespace Pims\Api;

use Exception;
use Jsor\HalClient;
use Pims\Api\Exception\ClientException;

class Client {
	
	/**
	 * Default language of the API
	 *
	 * @var string
	 */
	const DEFAULT_LANGUAGE = 'en';
	
	/**
	 * Default version of the API
	 *
	 * @var string
	 */
	const DEFAULT_VERSION = 'v2';
	
	var $basePath;
	var $version;
	private function getFullPath () : string {
		return $this->basePath . (! empty($this->version) ? '/' . $this->version : '');
	}
	
	/**
	 * HAL client to request the API
	 *
	 * @var HalClient\HalClient|HalClient\HalClientInterface
	 */
	var $client;
	
	
	/**
	 * Client constructor.
	 *
	 * @param string $basePath URL of the root URI (e.g.: https://demo.pims.io/api)
	 * @param string $username Username for the Basic Auth
	 * @param string $password Password for the Basic Auth
	 * @param string $language Language of the translatable fields (e.g.: 'fr')
	 * @param string $version  Version of the API (e.g.: 'v1')
	 *
	 * @throws ClientException
	 */
	public function __construct (string $basePath, string $username, string $password, string $language = self::DEFAULT_LANGUAGE, string $version = self::DEFAULT_VERSION) {
		try {
			$this->basePath = parse_url($basePath)['path'];
			if (! empty($language)) {
				$this->language = $language;
			}
			if (! empty($version)) {
				$this->version = $version;
			}
			
			$this->client = (new HalClient\HalClient($this->getFullPath()))->withHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $password));
		} catch (Exception $e) {
			throw new ClientException("Can't create a new API Client", $e->getCode(), $e);
		}
	}
	
	/**
	 * If some $params begin with a semi-colon (e.g. ":event_id"), substitute them in the $url and remove them from $params
	 *
	 * @param array		&$params
	 * @param string	&$url
	 */
	private function substitutePathParameters (array &$params = null, string &$url) {
		if (! empty($params)) {
			foreach ($params as $key => $value) {
				if ($key[0] === ':') {
					$url = str_replace($key, $value, $url);
					unset($params[$key]);
				}
			}
		}
	}
	
	/**
	 * Get a single resource by ID
	 *
	 * @param string	$url	Enpoint of the resource to fetch (see \Pims\Api\Resource for the possible values)
	 * @param int		$id		ID of the resource to fetch
	 * @param array		$params Query parameters
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function getOne (string $url, int $id, array $params = null) {
		try {
			$this->substitutePathParameters($url, $params);
			
			return $this->client->get(
					$this->getFullPath() . $url . '/' . $id,
					[
							'headers'	=> ['Accept-Language' => $this->language],
							'query' 	=> $params
					]);
		} catch (Exception $e) {
			throw new ClientException("Can't GET a single resource on endpoint $url", $e->getCode(), $e);
		}
	}

	/**
	 * Get a collection of resources
	 *
	 * @param string	$url	Enpoint of the resources to fetch (see \Pims\Api\Resource for the possible values)
	 * @param array		$params Query parameters (mostly filters)
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function getAll (string $url, array $params = null) {
		try {
			$this->substitutePathParameters($url, $params);
			
			return $this->client->get(
					$this->getFullPath() . $url,
					[
							'headers'	=> ['Accept-Language' => $this->language],
							'query' 	=> $params
					]);
		} catch (Exception $e) {
			throw new ClientException("Can't GET a collection of resources on endpoint $url", $e->getCode(), $e);
		}
	}
	
	/**
	 * Create a new resource
	 *
	 * @param string	$url	Enpoint of the resource to create (see \Pims\Api\Resource for the possible values)
	 * @param array		$body	Values to be created
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function postOne (string $url, array $body) {
		try {
			$this->substitutePathParameters($url, $params);
			
			return $this->client->post(
					$this->getFullPath() . $url,
					['body' => $body]);
		} catch (Exception $e) {
			throw new ClientException("Can't POST a single resource on endpoint $url", $e->getCode(), $e);
		}
	}
	
	/**
	 * Update a single resource by ID
	 *
	 * @param string	$url	Enpoint of the resource to update (see \Pims\Api\Resource for the possible values)
	 * @param int		$id		ID of the resource to update
	 * @param array		$body	New values to be updated
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function patchOne (string $url, int $id, array $body) {
		try {
			$this->substitutePathParameters($url, $params);
			
			return $this->client->request(
					'PATCH',
					$this->getFullPath() . $url . '/' . $id,
					['body' => $body]);
		} catch (Exception $e) {
			throw new ClientException("Can't PATCH a single resource on endpoint $url", $e->getCode(), $e);
		}
	}
	
	/**
	 * Delete a single resource by ID
	 *
	 * @param string	$url	Enpoint of the resource to delete (see \Pims\Api\Resource for the possible values)
	 * @param int		$id		ID of the resource to delete
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function deleteOne (string $url, int $id) {
		try {
			$this->substitutePathParameters($url, $params);
			
			return $this->client->delete($this->getFullPath() . $url . '/' . $id);
		} catch (Exception $e) {
			throw new ClientException("Can't DELETE a single resource on endpoint $url", $e->getCode(), $e);
		}
	}
	
	public function getFirst (HalClient\HalResource $resource) {
		$this->fetchHatoasResources($resource, 'first');
	}
	public function getLast (HalClient\HalResource $resource) {
		$this->fetchHatoasResources($resource, 'last');
	}
	public function getPrevious (HalClient\HalResource $resource) {
		$this->fetchHatoasResources($resource, 'previous');
	}
	public function getNext (HalClient\HalResource $resource) {
		$this->fetchHatoasResources($resource, 'next');
	}
	
	/**
	 *
	 *
	 * @param HalClient\HalResource $resource
	 * @param string                $keyword
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface|null
	 * @throws ClientException
	 */
	private function fetchHatoasResources (HalClient\HalResource $resource, string $keyword) {
		try {
			if ($resource->hasLink($keyword)) {
				return $this->client->get($resource->getFirstLink($keyword)->get());
			} else {
				return null;
			}
		} catch (Exception $e) {
			throw new ClientException('Error while browsing HATOAS resource.', $e->getCode(), $e);
		}
	}
}