<?php

namespace Pims\Api;

use Exception;

class ClientException extends Exception {
	public function __construct(string $message, int $code, Exception $previous = null) {

		parent::__construct($message, $code, $previous);
	}
}