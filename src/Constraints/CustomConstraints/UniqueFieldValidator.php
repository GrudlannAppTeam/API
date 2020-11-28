<?php declare(strict_types=1);

namespace App\Constraints\CustomConstraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class UniqueFieldValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueField) {
            throw new UnexpectedTypeException($constraint, UniqueField::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $entity = $constraint->entity;
        $field = $constraint->field;

        if (!$entity || !$field) {
            throw new \Exception('Incorrect entity or field');
        }

        $result = $this->em->getRepository($entity)->findOneBy([$field => $value]);

        if ($result) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}