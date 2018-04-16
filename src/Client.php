<?php

namespace Pims\Api;

use Exception;
use Jsor\HalClient;
use Pims\Api\Exception\ClientException;

class Client {

	var $basePath;
	var $client;


	/**
	 * Client constructor.
	 *
	 * @param string	$url		Url of the root uri. (ex: http://api.opensupporter.org/)
	 * @param string	$username	Username for the Basic Auth
	 * @param string	$password	Password for the Basic Auth
	 * @throws \Exception
	 */
	public function __construct (string $url,string $username,string $password) {

		$this->basePath = parse_url($url)['path'];

		try {
			$this->client = (new HalClient\HalClient($url))->withHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $password));
		} catch (Exception $e) {
			throw new ClientException("bad construct", 42, $e);
		}
	}

	/**
	 * Fonction permettant la récuperations d'une entité
	 *
	 * @param string	$url	Url correspondant au point d'appel de récupération (ex: '/api/v1/people')
	 * @param int		$id		Id de l'élément qu'il faut récupere (ex: '2127')
	 * @param array		$params Parametre de filtre pour la récuperation de donnée (ex: TODO)
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function getOne (string $url, int $id, array $params = null) {

		try {
			return $this->client->get($this->basePath . $url . '/' . $id, ['query' => $params]);
		} catch (Exception $e) {
			throw new ClientException("bad getOne", 42, $e);
		}
	}

	/**
	 * Fonction permettant la récuperations d'une collection
	 *
	 * @param string	$url
	 * @param array		$params
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function getAll (string $url, array $params = null) {
		try {
			return $this->client->get($this->basePath . $url, ['query' => $params]);
		} catch (Exception $e) {
			throw new ClientException("bad getAll", 42, $e);
		}
	}

	/**
	 * Fonction permettant la suppresion d'une entite
	 *
	 * @param string	$url	Url correspondant au point d'appel de suppression (ex: '/events')
	 * @param int		$id		Id de l'élément qu'il faut supprimer (ex: '2127')
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function deleteOne (string $url, int $id) {
		try {
			return $this->client->delete($this->basePath . $url . '/' . $id);
		} catch (Exception $e) {
			throw new ClientException("bad deleteOne", 42, $e);
		}
	}

	/**
	 * @param string	$url	Url correspondant au point d'appel de suppression (ex: '/events')
	 * @param int		$id		Id de l'élément qu'il faut patché (ex: '2127')
	 * @param array		$body
	 *
	 * @return HalClient\HalResource|\Psr\Http\Message\ResponseInterface
	 * @throws ClientException
	 */
	public function patchOne (string $url, int $id, array $body) {
		try {
			return $this->client
				->request('PATCH', $this->basePath . $url . '/' . $id,
					['body'    => $body]);
		} catch (Exception $e) {
			throw new ClientException("bad patchOne", 42, $e);
		}
	}

}