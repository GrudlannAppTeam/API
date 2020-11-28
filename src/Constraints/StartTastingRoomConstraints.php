<?php declare(strict_types=1);

namespace App\Constraints;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class StartTastingRoomConstraints
{
    public static function get(): Collection
    {
        return new Collection([
            'allowExtraFields' => true,
            'fields' => [
                'tastingRoomId' => [
                    new NotBlank(),
                    new Type('integer')
                ],
                'status' => [
                    new Type('bool')
                ]
            ]
        ]);
    }
}
