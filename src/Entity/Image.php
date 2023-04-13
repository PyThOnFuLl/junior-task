<?php

namespace App\Entity;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter, ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter, ApiPlatform\Metadata\ApiResource, ApiPlatform\Metadata\ApiProperty,
    ApiPlatform\Metadata\Delete, ApiPlatform\Metadata\Get, ApiPlatform\Metadata\GetCollection,
    ApiPlatform\Metadata\Post, ApiPlatform\Metadata\Put;
use App\Controller\GetImageAction, App\Controller\CreateImageAction;
use Symfony\Component\Serializer\Annotation\Groups, Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

#[Vich\Uploadable,

    ApiResource(
        types: ['https://schema.org/MediaObject'],
        operations: [
        new Post(
            controller: CreateImageAction::class,
            validationContext: ['groups' => ['Default', 'image_create']],
            deserialize: false),
        new Get(),
        new GetCollection(
            controller: GetImageAction::class,
            security: "is_granted('ROLE_USER')"),
        new Delete(),
        new Put()],
        inputFormats: ['multipart' => ['multipart/form-data']],
        normalizationContext: ['groups' => ['image.read']],
        denormalizationContext: ['groups' => ['image.write']],
        ),
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

#[ORM\Entity]
class Image extends BaseEntity
{
    #[ORM\ManyToOne(targetEntity: "User", inversedBy: "images")]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;

    #[ApiProperty(types: ['https://schema.org/contentUrl'])]
    #[Groups(['image.read'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "filePath")]
    #[Assert\NotNull(groups: ['image_create'])]
    #[Groups(['image.write'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    public ?string $filePath = null;

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