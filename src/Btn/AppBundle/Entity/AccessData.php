<?php

namespace Btn\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Btn\AppBundle\Entity\AccessData
 *
 * @ORM\Table(name="access_data")
 * @ORM\Entity
 */
class AccessData
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="accessData")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id")
     */
    private $project;    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return AccessData
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return AccessData
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set project
     *
     * @param Btn\AppBundle\Entity\Project $project
     * @return AccessData
     */
    public function setProject(\Btn\AppBundle\Entity\Project $project = null)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Get project
     *
     * @return Btn\AppBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }
}