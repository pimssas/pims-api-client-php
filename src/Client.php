<?php
	
	namespace Pims\Api;
	
	use Exception;
	use Jsor\HalClient;
	use Pims\Api\Exception\ClientException;
	
	/**
	 * @package Pims\Api
	 */
	class Client {
		
		/** Properties used for pagination */
		const PAGINATION_FIRST		= 'first';
		const PAGINATION_LAST		= 'last';
		const PAGINATION_PREVIOUS	= 'prev';
		const PAGINATION_NEXT		= 'next';
		
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
		 * @var HalClient\HalClientInterface
		 */
		private $halClient;
		public function getHalClient () : HalClient\HalClientInterface {
			return $this->halClient;
		}
		
		
		/**
		 * Client constructor
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
				throw new ClientException("Can't create a new API client", $e->getCode(), $e);
			}
		}
		
		/**
		 * Change the language used by the client
		 *
		 * @param string $language	Language of the translatable fields (e.g.: 'fr')
		 */
		public function setLanguage (string $language = self::DEFAULT_LANGUAGE) {
			$this->halClient = $this->halClient->withHeader('Accept-Language', $language);
		}
		
		/**
		 * If some $params begin with a semi-colon (e.g. ":event_id"), substitute them in the $endpoint and remove them from $params
		 *
		 * @param array	 &$params
		 * @param string &$endpoint
		 */
		private static function substitutePathParameters (array &$params = null, string &$endpoint) {
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
		 * @param string	$endpoint	Endpoint of the resource to fetch (see \Pims\Api\Endpoint for the possible values)
		 * @param mixed		$id			ID of the resource to fetch
		 * @param array		$params		Query and/or path parameters if any
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
		 * @throws ClientException
		 */
		public function getOne (string $endpoint, $id, array $params = null) {
			try {
				self::substitutePathParameters(
						$params,
						$endpoint);
				
				return $this->halClient->get(
						$this->getFullPath() . $endpoint . '/' . $id,
						['query' => $params]);
			} catch (Exception $e) {
				throw new ClientException(
						$e->getMessage(),
						$e->getCode(),
						$e);
			}
		}
		
		/**
		 * Get a collection of resources
		 *
		 * @param string $endpoint Endpoint of the resources to fetch (see \Pims\Api\Endpoint for the possible values)
		 * @param array  $params   Query and/or path parameters if any
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
		 * @throws ClientException
		 */
		public function getAll (string $endpoint, array $params = null) {
			try {
				self::substitutePathParameters(
						$params,
						$endpoint);
				
				return $this->halClient->get(
						$this->getFullPath() . $endpoint,
						['query' => $params]);
			} catch (Exception $e) {
				throw new ClientException(
						$e->getMessage(),
						$e->getCode(),
						$e);
			}
		}
		
		/**
		 * Create a new resource
		 *
		 * @param string $endpoint Endpoint of the resource to create (see \Pims\Api\Endpoint for the possible values)
		 * @param array  $body     Values to be created
		 * @param array  $params   Path parameters if any
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
		 * @throws ClientException
		 */
		public function postOne (string $endpoint, array $body, array $params = null) {
			try {
				self::substitutePathParameters(
						$params,
						$endpoint);
				
				return $this->halClient->post(
						$this->getFullPath() . $endpoint,
						['body' => $body]);
			} catch (Exception $e) {
				throw new ClientException(
						$e->getMessage(),
						$e->getCode(),
						$e);
			}
		}
		
		/**
		 * Update a single resource by ID
		 *
		 * @param string $endpoint Endpoint of the resource to update (see \Pims\Api\Endpoint for the possible values)
		 * @param mixed  $id       ID of the resource to update
		 * @param array  $body     New values to be updated
		 * @param array  $params   Path parameters if any
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
		 * @throws ClientException
		 */
		public function patchOne (string $endpoint, $id, array $body, array $params = null) {
			try {
				self::substitutePathParameters(
						$params,
						$endpoint);
				
				return $this->halClient->request(
						'PATCH',
						$this->getFullPath() . $endpoint . '/' . $id,
						['body' => $body]);
			} catch (Exception $e) {
				throw new ClientException(
						$e->getMessage(),
						$e->getCode(),
						$e);
			}
		}
		
		/**
		 * Delete a single resource by ID
		 *
		 * @param string $endpoint Endpoint of the resource to delete (see \Pims\Api\Endpoint for the possible values)
		 * @param mixed  $id       ID of the resource to delete
		 * @param array  $params   Path parameters if any
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
		 * @throws ClientException
		 */
		public function deleteOne (string $endpoint, $id, array $params = null) {
			try {
				self::substitutePathParameters(
						$params,
						$endpoint);
				
				return $this->halClient->delete($this->getFullPath() . $endpoint . '/' . $id);
				
			} catch (Exception $e) {
				throw new ClientException(
						$e->getMessage(),
						$e->getCode(),
						$e);
			}
		}
		
		/**
		 * Delete a list of resources
		 *
		 * @param string $endpoint Endpoint of the resources to delete (see \Pims\Api\Endpoint for the possible values)
		 * @param array  $body     Request body (contains ID of the ressource to delete)
		 * @param array  $params   Path parameters if any
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
		 * @throws ClientException
		 */
		public function deleteList (string $endpoint, array $body, array $params = null) {
			try {
				self::substitutePathParameters(
						$params,
						$endpoint);
				
				return $this->halClient->request(
						'DELETE',
						$this->getFullPath() . $endpoint,
						['body' => $body]);
				
			} catch (Exception $e) {
				throw new ClientException(
						$e->getMessage(),
						$e->getCode(),
						$e);
			}
		}
		
		/**
		 * Replace a list of resources
		 *
		 * @param string $endpoint Endpoint of the resources to replace (see \Pims\Api\Endpoint for the possible values)
		 * @param array  $body     Request body (contains ID of the ressource to replace)
		 * @param array  $params   Path parameters if any
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
		 * @throws ClientException
		 */
		public function replaceList (string $endpoint, array $body, array $params = null) {
			try {
				self::substitutePathParameters(
						$params,
						$endpoint);
				
				return $this->halClient->request(
						'PUT',
						$this->getFullPath() . $endpoint,
						['body' => $body]);
				
			} catch (Exception $e) {
				throw new ClientException(
						$e->getMessage(),
						$e->getCode(),
						$e);
			}
		}
		
		/**
		 * Get the first page of a HAL-paginated collection of resources
		 *
		 * @param HalClient\HalResource $resource	Collection from which the page is to be fetched
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface|null
		 * @throws ClientException
		 */
		public function getFirst (HalClient\HalResource $resource) {
			return $this->fetchPaginatedResources($resource, self::PAGINATION_FIRST);
		}
		
		/**
		 * Get the last page of a HAL-paginated collection of resources
		 *
		 * @param HalClient\HalResource $resource	Collection from which the page is to be fetched
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface|null
		 * @throws ClientException
		 */
		public function getLast (HalClient\HalResource $resource) {
			return $this->fetchPaginatedResources($resource, self::PAGINATION_LAST);
		}
		
		/**
		 * Get the previous page of a HAL-paginated collection of resources
		 *
		 * @param HalClient\HalResource $resource	Collection from which the page is to be fetched
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface|null
		 * @throws ClientException
		 */
		public function getPrevious (HalClient\HalResource $resource) {
			return $this->fetchPaginatedResources($resource, self::PAGINATION_PREVIOUS);
		}
		
		/**
		 * Get the next page of a HAL-paginated collection of resources
		 *
		 * @param HalClient\HalResource $resource	Collection from which the page is to be fetched
		 *
		 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface|null
		 * @throws ClientException
		 */
		public function getNext (HalClient\HalResource $resource) {
			return $this->fetchPaginatedResources($resource, self::PAGINATION_NEXT);
		}
		
		/**
		 * Generic method to fetch in a HAL-paginated collection of resources
		 *
		 * @param HalClient\HalResource $resource	Resource from which to fetch
		 * @param string 				$keyword 	Keyword denoting what is to be fetched
		 *                        					(See the constants self::PAGINATION_* for the possible values)
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
				throw new ClientException('Error while fetching paginated resource.', $e->getCode(), $e);
			}
		}
	}