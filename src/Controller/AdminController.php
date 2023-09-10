<?php

namespace App\Controller;

use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/panel_videos", name="app_panel_videos")
     */
    public function panelVideos(): Response
    {
        $videos = $this->manager->getRepository(Video::class)->findAll();

        return $this->render('admin/panelVideos.html.twig', [
            'controller_name' => 'AdminController',
            'videos' => $videos,
        ]);
    }
}
