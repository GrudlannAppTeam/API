<?php declare(strict_types=1);

namespace App\Constraints;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateReviewConstraints
{
    public static function get(): Collection
    {
        return new Collection([
            'allowExtraFields' => true,
            'fields' => [
                'beerId' => [
                    new NotBlank(),
                    new Type('integer')
                ],
                'answerId' => [
                    new NotBlank(),
                    new Type('integer')
                ]
            ]
        ]);
    }
}
