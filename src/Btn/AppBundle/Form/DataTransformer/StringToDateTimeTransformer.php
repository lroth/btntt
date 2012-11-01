<?php
namespace Btn\AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class StringToDateTimeTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (DateTime) to a string (date).
     *
     * @param  Project|null $project
     * @return string
     */
    public function transform($datetime)
    {
        if (null === $datetime) {
            return "";
        }
        if (!($datetime instanceof \DateTime)) {
            $datetime = new \DateTime($datetime);
        }

        return $datetime->format('d-m-Y');
    }

    /**
     * Transforms a string (date) to an object (DateTime).
     *
     * @param  string $name
     * @return Project|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($date)
    {
        if (!$date) {
            return null;
        }

        $dateTime = new \DateTime($date);

        if (null === $dateTime) {
            throw new TransformationFailedException(sprintf(
                'An date "%s" cant be converted to DateTime!',
                $date
            ));
        }

        return $dateTime;
    }
}