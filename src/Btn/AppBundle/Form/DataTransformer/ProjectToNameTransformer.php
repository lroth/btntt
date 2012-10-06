<?php
namespace Btn\AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Btn\AppBundle\Entity\Project;

class ProjectToNameTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * Transforms an object (Project) to a string (name).
     *
     * @param  Project|null $project
     * @return string
     */
    public function transform($project)
    {
        if (null === $project) {
            return "";
        }

        return $project->getName();
    }

    /**
     * Transforms a string (name) to an object (Project).
     *
     * @param  string $name
     * @return Project|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($name)
    {
        if (!$name) {
            return null;
        }

        $project = $this->om
            ->getRepository('BtnAppBundle:Project')
            ->findOneBy(array('name' => $name))
        ;

        if (null === $project) {
            //@todo: create new project?
            $project = new Project();
            $project->setName($name);
            $this->om->persist($project);
            $this->om->flush();
            // throw new TransformationFailedException(sprintf(
            //     'An project with name "%s" does not exist!',
            //     $name
            // ));
        }

        return $project;
    }
}