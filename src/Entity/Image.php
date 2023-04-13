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
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 */
#[ApiResource(operations: [
    new Get(normalizationContext: ['groups' => ['image.read']], denormalizationContext: ['groups' => ['image.write']]),
    new GetCollection(normalizationContext: ['groups' => ['image.read']], denormalizationContext: ['groups' => ['image.write']]),
    new Post(normalizationContext: ['groups' => ['image.read']], denormalizationContext: ['groups' => ['image.write']]),
    new Delete(),
    new Put()
]),
    ApiFilter(
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

class Image extends BaseEntity
{
    /**
     * @ORM\Column(type="string", length=40, options={"fixed" = false}, unique=true)
     */
    #[Groups(['image.read', 'image.write'])]
    private string $name;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="images")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['image.read', 'image.write'])]
    private User $user;

    /**
     * @return string
     */

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
        $user?->addImage($this);
    }
}