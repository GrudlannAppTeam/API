<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class TastingRoomIsStartException extends Exception implements ApiExceptionInterface
{
    public function __construct()
    {
        parent::__construct(
            "Tasting room has been started, you can no longer join",
            JsonResponse::HTTP_FORBIDDEN
        );
    }
}
