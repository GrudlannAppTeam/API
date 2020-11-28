<?php declare(strict_types=1);

namespace App\Constraints\CustomConstraints;

use Symfony\Component\Validator\Constraint;

class UniqueField extends Constraint
{
    public $message = 'This field must be unique';
    public $entity;
    public $field;

    public function validatedBy()
    {
        return UniqueFieldValidator::class;
    }

    public function getRequiredOptions()
    {
        return ['entity', 'field'];
    }
}
