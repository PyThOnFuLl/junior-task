<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter, ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter, ApiPlatform\Metadata\ApiResource, ApiPlatform\Metadata\Delete,
    ApiPlatform\Metadata\Get, ApiPlatform\Metadata\GetCollection, ApiPlatform\Metadata\Post, ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\ArrayCollection, Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface, Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\User\RegistrationController;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class),

    ApiFilter(
        SearchFilter::class,
        properties: ['id' => SearchFilter::DOCTRINE_INTEGER_TYPE]
    ),
    ApiFilter(
        OrderFilter::class,
        properties: ['date_create']
    ),
    ApiResource(
        operations: [
            new Post(
                uriTemplate: 'user/register',
                controller: RegistrationController::class,
                normalizationContext: ['groups' => ['user.read']],
                denormalizationContext: ['groups' => ['user.write']]),
            new Get(normalizationContext: ['groups' => ['user.image.read']]),
            new GetCollection(normalizationContext: ['groups' => ['user.image.read']]),
            new Put(
                controller: RegistrationController::class,
                normalizationContext: ['groups' => ['user.read']],
                denormalizationContext: ['groups' => ['user.write']]),
            new Delete()],
        inputFormats: ['multipart' => ['multipart/form-data']])
]

class User extends BaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user.read', 'user.write'])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user.write'])]
    private string $password;

    /**
     * @var Image[]
     */
    #[ORM\OneToMany(mappedBy: "user", targetEntity: "Image")]
    #[Groups(['user.read', 'user.image.read'])]
    private iterable $images = [];

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __construct()
    {
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