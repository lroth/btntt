<?php

namespace Btn\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Btn\AppBundle\Entity\Enquiry
 *
 * @ORM\Table(name="enquiry")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Enquiry
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
     * @var integer $estimationTime
     *
     * @ORM\Column(name="estimationTime", type="integer")
     */
    private $estimationTime;

    /**
     * @var string $budget
     *
     * @ORM\Column(name="budget", type="string")
     */
    private $budget;

    /**
     * @var string $content
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var datetime $projectStartTime
     *
     * @ORM\Column(name="projectStartTime", type="datetime")
     */
    private $projectStartTime;

    /**
     * @var datetime $projectEndTime
     *
     * @ORM\Column(name="projectEndTime", type="datetime")
     */
    private $projectEndTime;

    /**
     * @var datetime $enquiryDeadline
     *
     * @ORM\Column(name="enquiryDeadline", type="datetime")
     */
    private $enquiryDeadline;

    /**
     * @var string $status
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Lead", inversedBy="enquiries")
     * @ORM\JoinColumn(name="lead_id", referencedColumnName="id")
     */
    private $lead;


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
     * Set estimationTime
     *
     * @param integer $estimationTime
     * @return Enquiry
     */
    public function setEstimationTime($estimationTime)
    {
        $this->estimationTime = $estimationTime;
        return $this;
    }

    /**
     * Get estimationTime
     *
     * @return integer 
     */
    public function getEstimationTime()
    {
        return $this->estimationTime;
    }

    /**
     * Set budget
     *
     * @param string $budget
     * @return Enquiry
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
        return $this;
    }

    /**
     * Get budget
     *
     * @return integer 
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Enquiry
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Enquiry
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
     * Set projectStartTime
     *
     * @param datetime $projectStartTime
     * @return Enquiry
     */
    public function setProjectStartTime($projectStartTime)
    {
        $this->projectStartTime = $projectStartTime;
        return $this;
    }

    /**
     * Get projectStartTime
     *
     * @return datetime 
     */
    public function getProjectStartTime()
    {
        return $this->projectStartTime;
    }

    /**
     * Set projectEndTime
     *
     * @param datetime $projectEndTime
     * @return Enquiry
     */
    public function setProjectEndTime($projectEndTime)
    {
        $this->projectEndTime = $projectEndTime;
        return $this;
    }

    /**
     * Get projectEndTime
     *
     * @return datetime 
     */
    public function getProjectEndTime()
    {
        return $this->projectEndTime;
    }

    /**
     * Set enquiryDeadline
     *
     * @param datetime $enquiryDeadline
     * @return Enquiry
     */
    public function setEnquiryDeadline($enquiryDeadline)
    {
        $this->enquiryDeadline = $enquiryDeadline;
        return $this;
    }

    /**
     * Get enquiryDeadline
     *
     * @return datetime 
     */
    public function getEnquiryDeadline()
    {
        return $this->enquiryDeadline;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Enquiry
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     * @return Enquiry
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return datetime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     * @return Enquiry
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return datetime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set lead
     *
     * @param Btn\AppBundle\Entity\Lead $lead
     * @return Enquiry
     */
    public function setLead(\Btn\AppBundle\Entity\Lead $lead = null)
    {
        $this->lead = $lead;
        return $this;
    }

    /**
     * Get lead
     *
     * @return Btn\AppBundle\Entity\Lead 
     */
    public function getLead()
    {
        return $this->lead;
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
}