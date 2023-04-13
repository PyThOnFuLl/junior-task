<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

#[AsController]
final class CreateImageAction extends AbstractController
{
    private Security $security;
    private Imagine $imagine;

    public function __construct(Security $security)
    {
        $this->security = $security;
        $this->imagine = new Imagine();
    }

    public function __invoke(Request $request, EntityManagerInterface $entityManager): Image
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $image = new Image();
        $image->file = $uploadedFile;

        $user = $this->security->getUser();
        if (!$user) {
            throw new BadRequestHttpException('User not authenticated');
        }

        $userId = $user->getId();

        $userEntity = $entityManager->getRepository(User::class)->find($userId);
        if (!$userEntity) {
            throw new BadRequestHttpException(sprintf('User with ID %s not found', $userId));
        }

        $image->setUser($userEntity);

        $entityManager->persist($image);
        $entityManager->flush();

        $filePath = $image->file->getPathname();
        list($iwidth, $iheight) = getimagesize($filePath);
        $width = $iwidth * 0.8;
        $height = $iheight* 0.8;

        $photo = $this->imagine->open($filePath);
        $photo->resize(new Box($width, $height))->save($filePath);

        return $image;
    }
}