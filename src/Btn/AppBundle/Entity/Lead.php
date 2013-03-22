<?php

namespace Btn\AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use JMS\SerializerBundle\Annotation\ExclusionPolicy;
use JMS\SerializerBundle\Annotation\Expose;

/**
 * Btn\AppBundle\Entity\Lead
 *
 * @ORM\Table(name="lead")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("all")
 */
class Lead
{
    public $customCallbacks = array(
        'setCurrentUser',
    );

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     */
    private $id;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Expose
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
     * @Assert\NotNull()
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     */
    private $name;

    /**
     * @var string $email
     * @Assert\NotNull()
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Expose
     */
    private $email;

    /**
     * @var string $description
     * @Assert\NotNull()
     * @ORM\Column(name="description", type="string", length=255)
     * @Expose
     */
    private $description;

    /**
     * @var datetime $alert
     * @Assert\NotBlank()
     * @ORM\Column(name="alert", type="datetime")
     * @Expose
     */
    private $alert;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Btn\UserBundle\Entity\User", inversedBy="leads")
     * @Expose
     */
    private $user;

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
        if (!empty($alert) && !($alert instanceof \DateTime)) {
            $alert = new \DateTime($alert);
        }

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

    /**
     * Set user
     *
     * @param Btn\UserBundle\Entity\User $user
     * @return Lead
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

    /**
     * @ORM\PreUpdate
     */
    public function setCreatedValue()
    {
        $this->updatedAt    = new \DateTime();
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateValues()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }
}