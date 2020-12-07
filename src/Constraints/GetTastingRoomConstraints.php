<?php declare(strict_types=1);

namespace App\Constraints;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;

class GetTastingRoomConstraints
{
    public static function get(): Collection
    {
        return new Collection([
            'allowExtraFields' => true,
            'fields' => [
                'id' => [
                    new NotBlank()
                ]
            ]
        ]);
    }
}
