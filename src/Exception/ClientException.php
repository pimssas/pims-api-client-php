<?php

namespace Pims\Api\Exception;

class ClientException extends \Exception {
	public function __construct(string $message, int $code, \Exception $previous = null) {
		parent::__construct($message, $code, $previous);
	}
}