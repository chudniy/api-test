<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvitationRepository")
 */
class Invitation implements \JsonSerializable
{
    use Timestampable;
    
    const TYPE_SENT = 'sent';
    const TYPE_RECEIVED = 'received';
    
    const STATUS_PENDING = 'pending';
    const STATUS_ALLOWED = 'allow';
    const STATUS_DECLINED = 'decline';
    
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="sentInvitations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sender;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="receivedInvitations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipient;
    
    public function __construct()
    {
        $this->setStatus($this::STATUS_PENDING);
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * @Assert\NotBlank
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }
    
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'status' => $this->getStatus()
        ];
    }
}
