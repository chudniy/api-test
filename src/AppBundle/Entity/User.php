<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 8/11/19
 * Time: 12:33
 */

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * User
 *
 * @ORM\Table("users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    
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
     * @ORM\OneToMany(targetEntity="Invitation", mappedBy="sender")
     */
    private $sentInvitations;
    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Invitation", mappedBy="recipient")
     */
    private $receivedInvitations;
    
    public function __construct()
    {
        parent::__construct ();
        $this->sentInvitations = new ArrayCollection();
        $this->receivedInvitations = new ArrayCollection();
    }
    
    /**
     * @return Collection|Invitation[]
     */
    public function getSentInvitations(): Collection
    {
        return $this->sentInvitations;
    }
    
    public function addSentInvitation(Invitation $sentInvitation): self
    {
        if (!$this->sentInvitations->contains($sentInvitation)) {
            $this->sentInvitations[] = $sentInvitation;
            $sentInvitation->setSender($this);
        }
        
        return $this;
    }
    
    public function removeSentInvitation(Invitation $sentInvitation): self
    {
        if ($this->sentInvitations->contains($sentInvitation)) {
            $this->sentInvitations->removeElement($sentInvitation);
            // set the owning side to null (unless already changed)
            if ($sentInvitation->getSender() === $this) {
                $sentInvitation->setSender(null);
            }
        }
        
        return $this;
    }
    
    /**
     * @return Collection|Invitation[]
     */
    public function getReceivedInvitations(): Collection
    {
        return $this->receivedInvitations;
    }
    
    public function addReceivedInvitation(Invitation $receivedInvitation): self
    {
        if (!$this->receivedInvitations->contains($receivedInvitation)) {
            $this->receivedInvitations[] = $receivedInvitation;
            $receivedInvitation->setRecipient($this);
        }
        
        return $this;
    }
    
    public function removeReceivedInvitation(Invitation $receivedInvitation): self
    {
        if ($this->receivedInvitations->contains($receivedInvitation)) {
            $this->receivedInvitations->removeElement($receivedInvitation);
            // set the owning side to null (unless already changed)
            if ($receivedInvitation->getRecipient() === $this) {
                $receivedInvitation->setRecipient(null);
            }
        }
        
        return $this;
    }
}