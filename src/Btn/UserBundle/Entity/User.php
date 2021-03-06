<?php
namespace Btn\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Btn\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string $screenName
     *
     * @ORM\Column(name="screenname", type="string", length=255, nullable=true)
     */
    private $screenName;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="Btn\AppBundle\Entity\Time", mappedBy="user")
     **/
    private $times;

    /**
     * @ORM\ManyToMany(targetEntity="Btn\AppBundle\Entity\Project", mappedBy="users")
     */
    private $projects;

    /**
     * @ORM\OneToMany(targetEntity="Btn\AppBundle\Entity\Lead", mappedBy="user")
     * @ORM\JoinColumn(name="lead_id", referencedColumnName="id")
     */
    private $leads;

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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set screenName
     *
     * @param string $screenName
     * @return User
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;

        return $this;
    }

    /**
     * Get screenName
     *
     * @return string
     */
    public function getScreenName()
    {
        return $this->screenName ? $this->screenName : $this->username;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->times = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add times
     *
     * @param Btn\AppBundle\Entity\Time $times
     * @return User
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
     * Add projects
     *
     * @param Btn\AppBundle\Entity\Project $projects
     * @return User
     */
    public function addProject(\Btn\AppBundle\Entity\Project $projects)
    {
        $this->projects[] = $projects;
        return $this;
    }

    /**
     * Remove projects
     *
     * @param Btn\AppBundle\Entity\Project $projects
     */
    public function removeProject(\Btn\AppBundle\Entity\Project $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Add leads
     *
     * @param Btn\AppBundle\Entity\Lead $leads
     * @return User
     */
    public function addLead(\Btn\AppBundle\Entity\Lead $leads)
    {
        $this->leads[] = $leads;
        return $this;
    }

    /**
     * Remove leads
     *
     * @param Btn\AppBundle\Entity\Lead $leads
     */
    public function removeLead(\Btn\AppBundle\Entity\Lead $leads)
    {
        $this->leads->removeElement($leads);
    }

    /**
     * Get leads
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getLeads()
    {
        return $this->leads;
    }
}