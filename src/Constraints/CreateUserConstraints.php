<?php declare(strict_types=1);

namespace App\Constraints;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Unique;

class CreateUserConstraints
{
    public static function get(): Collection
    {
        return new Collection([
            'allowExtraFields' => true,
            'fields' => [
                'email' => [
                    new NotBlank(),
                    new Type('string'),
                    new Email()
                ],
                'password' => [
                    new NotBlank(),
                    new Type('string'),
                    //@TODO Regex validation
                ],
                'nick' => [
                    new NotBlank(),
                    new Type('string')
                ]
            ]
        ]);
    }
}
