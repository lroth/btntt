<?php

namespace Btn\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Btn\AppBundle\Entity\Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Project
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    /**
     * @var boolean $is_active
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $is_active = true;

    /**
     * @ORM\OneToMany(targetEntity="Btn\AppBundle\Entity\Time", mappedBy="project")
     **/
    private $times;

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
     * Set name
     *
     * @param string $name
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Project
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Project
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
    * Set createdAt rePersist
    *
    * @ORM\PrePersist
    */
    public function setCreatedAtValue()
    {
        $this->setCreatedAt(new \DateTime());
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->times = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add times
     *
     * @param Btn\AppBundle\Entity\Time $times
     * @return Project
     */
    public function addTime(\Btn\AppBundle\Entity\Time $times)
    {
        $this->times[] = $times;

        return $this;
    }

    /**
     * Remove times
     *
     * @param Btn\AppBundle\Entity\Time $times
     */
    public function removeTime(\Btn\AppBundle\Entity\Time $times)
    {
        $this->times->removeElement($times);
    }

    /**
     * Get times
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getTimes()
    {
        return $this->times;
    }

    /**
     * Default to string
     *
     * @return string
     **/
    public function __toString()
    {
        return $this->name;
    }
}