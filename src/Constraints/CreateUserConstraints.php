<?php declare(strict_types=1);

namespace App\Constraints;

use App\Constraints\CustomConstraints\UniqueField;
use App\Entity\User;
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
                'email' => [
                    new NotBlank(),
                    new Type('string'),
                    new Email(),
                    new UniqueField(['entity' => User::class, 'field' => 'email'])
                ],
                'password' => [
                    new NotBlank(),
                    new Type('string'),
                    //@TODO Regex validation
                ],
                'nick' => [
                    new NotBlank(),
                    new Type('string'),
                    new UniqueField(['entity' => User::class, 'field' => 'nick'])
                ]
            ]
        ]);
    }
}
