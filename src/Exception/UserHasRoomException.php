<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserHasRoomException extends Exception implements ApiExceptionInterface
{
    public function __construct($tastingRoomId)
    {
        parent::__construct(
            "The user already has a room. First close the room with id: $tastingRoomId",
            JsonResponse::HTTP_BAD_REQUEST
        );
    }
}
