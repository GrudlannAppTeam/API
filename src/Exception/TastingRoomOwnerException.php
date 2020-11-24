<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class TastingRoomOwnerException extends Exception implements ApiExceptionInterface
{
    public function __construct()
    {
        parent::__construct(
            "Only owner can operate on tasting room",
            JsonResponse::HTTP_FORBIDDEN
        );
    }
}
