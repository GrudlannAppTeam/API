<?php declare(strict_types=1);

namespace App\Constraints;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CreateUserConstraints
{
    public static function get(): Collection
    {
        return new Collection([
            'allowExtraFields' => true,
            'fields' => [
                'username' => [
                    new NotBlank(),
                    new Type('string'),
                    new Email()
                    //@TODO Check if email exist
                ],
                'password' => [
                    new NotBlank(),
                    new Type('string'),
                    //@TODO Regex validation
                ]
            ]
        ]);
    }
}
