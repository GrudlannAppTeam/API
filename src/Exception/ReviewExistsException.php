<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReviewExistsException extends Exception implements ApiExceptionInterface
{
    public function __construct()
    {
        parent::__construct(
            "This user in this tasting room already vote this beer",
            JsonResponse::HTTP_BAD_REQUEST
        );
    }
}
