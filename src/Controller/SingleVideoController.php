<?php

namespace App\Controller;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SingleVideoController extends AbstractController
{
    /**
     * @Route("/single/video/{id}", name="app_single_video")
     */
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {

        $video = $entityManager->getRepository(Video::class)->find($id);

        if (!$video) {
            throw $this->createNotFoundException(
                'Aucune vidéo à l\'id ' . $id
            );
        }
        return $this->render('single_video/index.html.twig', [
            'controller_name' => 'SingleVideoController',
            'video' => $video,
        ]);
    }
}
