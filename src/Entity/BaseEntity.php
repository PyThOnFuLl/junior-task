<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[HasLifecycleCallbacks]
abstract class BaseEntity
{
    public function __construct()
    {
        $this->date_create = new \DateTime();
        $this->date_update = new \DateTime();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id = null;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_update;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_create;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * @param \DateTimeInterface $date_update
     */
    public function setDateUpdate($date_update): void
    {
        $this->date_update = $date_update;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * @param \DateTimeInterface $date_create
     */
    public function setDateCreate($date_create): void
    {
        $this->date_create = $date_create;
    }

    #[ORM\PrePersist]
    public function OnCreate(): void
    {
        $this->date_create = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function OnUpdate(): void
    {
        $this->date_update = new \DateTime();
    }
}