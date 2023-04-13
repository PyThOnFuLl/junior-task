<?php

namespace App\Entity;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity
 */
#[ApiFilter(
        SearchFilter::class,
        properties: [
            'id' => SearchFilter::DOCTRINE_INTEGER_TYPE
        ]
    ),
    ApiFilter(
        OrderFilter::class,
        properties: ['date_create']
    )
]
#[ApiResource(
    operations: [
    new Get(normalizationContext: ['groups' => ['user.read']]),
    new GetCollection(normalizationContext: ['groups' => ['user.read']]),
    new Post(normalizationContext: ['groups' => ['user.read']], denormalizationContext: ['groups' => ['user.write']]),
    new Delete(),
    new Put()
])]

class User extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=40, options={"fixed" = false}, unique=true)
     */
    #[Groups(['user.read', 'user.write'])]
    private string $username;

    /**
     * @ORM\Column(type="string", length=255, options={"fixed" = false})
     */
    #[Groups(['user.write'])]
    private string $password;

    /**
     * @var Image[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Image",
     *     mappedBy="user",
     *     cascade={"persist", "remove"})
     */
    private iterable $images = [];



    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function __construct()
    {
        parent::__construct();
        $this->images = new ArrayCollection();
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setUser($this);
        }
        return $this;
    }
}