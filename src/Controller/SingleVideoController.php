<?php

namespace App\Controller;

use App\Entity\Video;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SingleVideoController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/single/video/{id}", name="app_single_video")
     */
    public function index(int $id): Response
    {

        $video = $this->manager->getRepository(Video::class)->find($id);
        $videoRepo = $this->manager->getRepository(Video::class);
        $randomVideos = $videoRepo->findRandomVideos($video->getUser(), 5);

        if (!$video) {
            throw $this->createNotFoundException(
                'Aucune vidéo à l\'id ' . $id
            );
        }
        return $this->render('single_video/index.html.twig', [
            'controller_name' => 'SingleVideoController',
            'video' => $video,
            'randomVideos' => $randomVideos
        ]);
    }
}
