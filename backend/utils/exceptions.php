<?php
declare(strict_types=1);
class ValidationException extends RuntimeException { public function __construct(string $message) { parent::__construct($message, 400); } }
class NotFoundException extends RuntimeException { public function __construct(string $message) { parent::__construct($message, 404); } }
class ExternalApiException extends RuntimeException { public function __construct(string $message) { parent::__construct($message, 502); } }
