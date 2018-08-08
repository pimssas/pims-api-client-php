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
	const DEFAULT_VERSION = 'v1';
	
	/**
	 * Current version of the API
	 *
	 * @var string
	 */
	private $version;
	public function getVersion () : string {
		return $this->version;
	}
	public function setVersion (string $version) {
		$this->version = $version;
	}
	
	/**
	 * Base path (and full path, i.e. including $version) of the API
	 *
	 * @var string
	 */
	private $basePath;
	private function getFullPath () : string {
		return $this->basePath . (! empty($this->version) ? '/' . $this->version : '');
	}
	
	/**
	 * HAL client to request the API
	 *
	 * @var HalClient\HalClient|HalClient\HalClientInterface
	 */
	private $halClient;
	public function getHalClient () : \Jsor\HalClient\HalClient {
		return $this->halClient;
	}
	
	
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
			$this->basePath = $basePath;
			if (! empty($version)) {
				$this->version = $version;
			}
			
			$this->halClient = (new HalClient\HalClient($this->getFullPath()))
					->withHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $password));
			$this->setLanguage($language);
		} catch (Exception $e) {
			throw new ClientException("Can't create a new API Client", $e->getCode(), $e);
		}
	}
	
	/**
	 * @param string $language
	 */
	public function setLanguage (string $language = self::DEFAULT_LANGUAGE) {
		$this->halClient = $this->halClient->withHeader('Accept-Language', $language);
	}
	
	/**
	 * If some $params begin with a semi-colon (e.g. ":event_id"), substitute them in the $endpoint and remove them from $params
	 *
	 * @param array		&$params
	 * @param string	&$endpoint
	 */
	private function substitutePathParameters (array &$params = null, string &$endpoint) {
		if (! empty($params)) {
			foreach ($params as $key => $value) {
				if ($key[0] === ':') {
					$endpoint = str_replace($key, $value, $endpoint);
					unset($params[$key]);
				}
			}
		}
	}
	
	/**
	 * Get a single resource by ID
	 *
	 * @param string $endpoint Endpoint of the resource to fetch (see \Pims\Api\Resource for the possible values)
	 * @param int    $id       ID of the resource to fetch
	 * @param array  $params   Query parameters
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function getOne (string $endpoint, int $id, array $params = null) {
		try {
			$this->substitutePathParameters(
					$params,
					$endpoint);
			
			return $this->halClient->get(
					$this->getFullPath() . $endpoint . '/' . $id,
					['query' => $params]);
		} catch (Exception $e) {
			throw new ClientException("Can't GET a single resource on endpoint $endpoint", $e->getCode(), $e);
		}
	}

	/**
	 * Get a collection of resources
	 *
	 * @param string $endpoint Endpoint of the resources to fetch (see \Pims\Api\Resource for the possible values)
	 * @param array  $params   Query parameters (mostly filters)
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function getAll (string $endpoint, array $params = null) {
		try {
			$this->substitutePathParameters(
					$params,
					$endpoint);
			
			return $this->halClient->get(
					$this->getFullPath() . $endpoint,
					['query' => $params]);
		} catch (Exception $e) {
			throw new ClientException("Can't GET a collection of resources on endpoint $endpoint", $e->getCode(), $e);
		}
	}
	
	/**
	 * Create a new resource
	 *
	 * @param string $endpoint Endpoint of the resource to create (see \Pims\Api\Resource for the possible values)
	 * @param array  $body     Values to be created
	 * @param array  $params   Query parameters (mostly the endpoint to substitute)
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function postOne (string $endpoint, array $body, array $params = null) {
		try {
			$this->substitutePathParameters(
					$params,
					$endpoint);
			
			return $this->halClient->post(
					$this->getFullPath() . $endpoint,
					['body' => $body]);
		} catch (Exception $e) {
			throw new ClientException("Can't POST a single resource on endpoint $endpoint", $e->getCode(), $e);
		}
	}
	
	/**
	 * Update a single resource by ID
	 *
	 * @param string $endpoint Endpoint of the resource to update (see \Pims\Api\Resource for the possible values)
	 * @param int    $id       ID of the resource to update
	 * @param array  $body     New values to be updated
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function patchOne (string $endpoint, int $id, array $body) {
		try {
			$this->substitutePathParameters(
					$params,
					$endpoint);
			
			return $this->halClient->request(
					'PATCH',
					$this->getFullPath() . $endpoint . '/' . $id,
					['body' => $body]);
		} catch (Exception $e) {
			throw new ClientException("Can't PATCH a single resource on endpoint $endpoint", $e->getCode(), $e);
		}
	}
	
	/**
	 * Delete a single resource by ID
	 *
	 * @param string $endpoint Endpoint of the resource to delete (see \Pims\Api\Resource for the possible values)
	 * @param int    $id       ID of the resource to delete
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function deleteOne (string $endpoint, int $id) {
		try {
			$this->substitutePathParameters(
					$params,
					$endpoint);
			
			return $this->halClient->delete($this->getFullPath() . $endpoint . '/' . $id);
		} catch (Exception $e) {
			throw new ClientException("Can't DELETE a single resource on endpoint $endpoint", $e->getCode(), $e);
		}
	}
	
	public function getFirst (HalClient\HalResource $resource) {
		return $this->fetchPaginatedResources($resource, 'first');
	}
	public function getLast (HalClient\HalResource $resource) {
		return $this->fetchPaginatedResources($resource, 'last');
	}
	public function getPrevious (HalClient\HalResource $resource) {
		return $this->fetchPaginatedResources($resource, 'previous');
	}
	public function getNext (HalClient\HalResource $resource) {
		return $this->fetchPaginatedResources($resource, 'next');
	}
	
	/**
	 * Generic method to fetch in a HAL-paginated collection of resources
	 *
	 * @param HalClient\HalResource $resource     Resource from which to fetch
	 * @param string                $keyword      Keyword denoting what is to be fetched.
	 *                             				  Possible values: ['first', 'last', 'previous', 'next']
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface|null
	 * @throws ClientException
	 */
	private function fetchPaginatedResources (HalClient\HalResource $resource, string $keyword) {
		try {
			if ($resource->hasLink($keyword)) {
				return $this->halClient->get($resource->getFirstLink($keyword)->getHref());
			} else {
				return null;
			}
		} catch (Exception $e) {
			throw new ClientException('Error while fetching HATEOAS resource.', $e->getCode(), $e);
		}
	}
}