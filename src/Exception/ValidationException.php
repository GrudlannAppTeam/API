<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ValidationException extends Exception implements ApiExceptionInterface
{
    public function __construct(array $message = [])
    {
        parent::__construct(json_encode($message), JsonResponse::HTTP_BAD_REQUEST);
    }
}
