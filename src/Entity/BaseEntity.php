<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[HasLifecycleCallbacks]
#[MappedSuperclass]
abstract class BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public ?int $id = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    #[ORM\Column(type:"datetime")]
    protected $dateCreate;

    #[ORM\Column(type:"datetime")]
    protected $dateUpdate;

    #[ORM\PrePersist]
    public function onCreate(): void
    {
        $this->dateCreate = new DateTime();
        $this->dateUpdate = new DateTime();
    }

    #[ORM\PreUpdate]
    public function onUpdate(): void
    {
        $this->dateUpdate = new DateTime();
    }
}