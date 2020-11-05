<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ErrorSerializerService
{
    public function serialize(ConstraintViolationListInterface $violationList): array
    {
        $serializedErrors = [];

        foreach ($violationList as $error) {
            if (!empty($error->getPropertyPath())) {
                $serializedErrors[$error->getPropertyPath()] = $error->getMessage();
            } else {
                $serializedErrors[] = $error->getMessage();
            }
        }

        return $serializedErrors;
    }
}
