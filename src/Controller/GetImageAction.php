<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;

#[AsController]
final class GetImageAction extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/images', name: 'image_collection', methods: ['GET'])]
    public function __invoke(EntityManagerInterface $entityManager): array
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new BadRequestHttpException('User not authenticated');
        }

        return $entityManager->getRepository(Image::class)->findBy(['user' => $user]);
    }
}