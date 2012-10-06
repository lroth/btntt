<?php

namespace Btn\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Btn\AppBundle\Entity\Time
 *
 * @ORM\Table(name="time")
 * @ORM\Entity(repositoryClass="Btn\AppBundle\Entity\TimeRepository")
 */
class Time
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
     * @var float $time
     *
     * @ORM\Column(name="time", type="decimal")
     */
    private $time;

    /**
     * @var integer $project_id
     *
     * @ORM\ManyToOne(targetEntity="Btn\AppBundle\Entity\Project", inversedBy="times")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $project;

    /**
     * @var User $user_id
     * @ORM\ManyToOne(targetEntity="Btn\UserBundle\Entity\User", inversedBy="times")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    private $user;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @var boolean $billable
     *
     * @ORM\Column(name="billable", type="boolean")
     */
    private $billable;

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
     * Set time
     *
     * @param float $time
     * @return Time
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return float
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set project_id
     *
     * @param integer $projectId
     * @return Time
     */
    public function setProjectId($projectId)
    {
        $this->project_id = $projectId;

        return $this;
    }

    /**
     * Get project_id
     *
     * @return integer
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * Set user_id
     *
     * @param integer $userId
     * @return Time
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get user_id
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Time
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Time
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
     * Set billable
     *
     * @param boolean $billable
     * @return Time
     */
    public function setBillable($billable)
    {
        $this->billable = $billable;

        return $this;
    }

    /**
     * Get billable
     *
     * @return boolean
     */
    public function getBillable()
    {
        return $this->billable;
    }

    /**
     * Set project
     *
     * @param Btn\AppBundle\Entity\Project $project
     * @return Time
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

    /**
     * Set user
     *
     * @param Btn\UserBundle\Entity\User $user
     * @return Time
     */
    public function setUser(\Btn\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return Btn\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}