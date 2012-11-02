<?php

namespace Btn\AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;

class UniqueValidator extends ConstraintValidator
{

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($value, Constraint $constraint)
    {   

        if (null === $value || '' === $value) {
            return;
        }

        $repo = $this->em->getRepository($constraint->repository);

        $object = $repo->findOneBy(array($constraint->field => $value));

        if (!$object) return;

        if ($object && $constraint->object && $object == $constraint->object) return;

        $this->context->addViolation($constraint->message);
    }
}
