<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    private $symfonyValidator;

    private $errorSerializerService;

    public function __construct(ValidatorInterface $symfonyValidator, ErrorSerializerService $errorSerializerService)
    {
        $this->symfonyValidator = $symfonyValidator;
        $this->errorSerializerService = $errorSerializerService;
    }

    public function validateArray(array $data, Collection $constraints): void
    {
        $errors = $this->symfonyValidator->validate($data, $constraints);

        if ($errors->count()) {
            throw new ValidationException($this->errorSerializerService->serialize($errors));
        }
    }
}
