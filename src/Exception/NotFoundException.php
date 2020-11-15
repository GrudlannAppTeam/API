<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotFoundException extends Exception implements ApiExceptionInterface
{
    public function __construct($id = null)
    {
        parent::__construct(
            "Not found object with this id: $id",
            JsonResponse::HTTP_NOT_FOUND
        );
    }
}
