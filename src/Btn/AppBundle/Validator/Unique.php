<?php

namespace Btn\AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
* @Annotation
*/
class Unique extends Constraint
{
    public $message = 'Ta wartość już istnieje w bazie.';

    public $repository;
    public $field;
    public $object = null;

    public function __construct($options = null)
    {
        parent::__construct($options);

        if (null === $this->repository && null === $this->field) {
            throw new MissingOptionsException('Specify field and repository ' . __CLASS__, array('repository', 'field'));
        }
    }

    public function validatedBy()
    {
        return 'btn.validator.unique';
    }
}

