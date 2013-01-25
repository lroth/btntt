<?php

namespace Btn\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Btn\AppBundle\Entity\Lead
 *
 * @ORM\Table(name="lead")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Lead
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
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var datetime $alert
     *
     * @ORM\Column(name="alert", type="datetime")
     */
    private $alert;

    /**
     * @var integer $userId
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @ORM\OneToMany(targetEntity="Enquiry", mappedBy="lead")
     */
    private $enquiries;

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
     * Set createdAt
     *
     * @param datetime $createdAt
     * @return Lead
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
     * @return Lead
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
     * Set name
     *
     * @param string $name
     * @return Lead
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
     * Set email
     *
     * @param string $email
     * @return Lead
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set descrcription
     *
     * @param string $descrcription
     * @return Lead
     */
    public function setDescrcription($descrcription)
    {
        $this->descrcription = $descrcription;
        return $this;
    }

    /**
     * Get descrcription
     *
     * @return string 
     */
    public function getDescrcription()
    {
        return $this->descrcription;
    }

    /**
     * Set alert
     *
     * @param datetime $alert
     * @return Lead
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;
        return $this;
    }

    /**
     * Get alert
     *
     * @return datetime 
     */
    public function getAlert()
    {
        return $this->alert;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Lead
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }
    public function __construct()
    {
        $this->enquiries = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add enquiries
     *
     * @param Btn\AppBundle\Entity\Enquiry $enquiries
     * @return Lead
     */
    public function addEnquirie(\Btn\AppBundle\Entity\Enquiry $enquiries)
    {
        $enquiries->setLead($this);
        $this->enquiries[] = $enquiries;
        return $this;
    }

    /**
     * Remove enquiries
     *
     * @param Btn\AppBundle\Entity\Enquiry $enquiries
     */
    public function removeEnquirie(\Btn\AppBundle\Entity\Enquiry $enquiries)
    {
        $this->enquiries->removeElement($enquiries);
    }

    /**
     * Get enquiries
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEnquiries()
    {
        return $this->enquiries;
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
     * Set description
     *
     * @param string $description
     * @return Lead
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
}