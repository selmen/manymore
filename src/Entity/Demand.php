<?php

namespace App\Entity;

use App\Repository\DemandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Demand
{
    public const STATUS_WAITING = 'waiting';
    public const STATUS_IN_PROGRESS = 'in-progress';
    public const STATUS_CLOSED = 'closed';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;
    
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $fileName = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'demands')]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    
    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): ?self
    {
        $this->fileName = $fileName;

        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime();
        $this->status = self::STATUS_WAITING;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    } 
    
    /**     
     *
     * @param string|null $status
     * @return string|null
     */
    public function changeStatus(?string $status): ?string
    {
        if ($this->getStatus() === Demand::STATUS_WAITING) {
            if ($status === Demand::STATUS_IN_PROGRESS || $status === Demand::STATUS_CLOSED) {
                $this->setStatus($status);

                return $status;
            }
        }

        if ($this->getStatus() === Demand::STATUS_IN_PROGRESS && 
            $status === Demand::STATUS_CLOSED) {
                $this->setStatus($status);
                
                return $status;
        }
        
        return null;
    }
}
