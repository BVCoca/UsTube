<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Comments;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request): Response
    {

        $comments = new Comments();
        $form = $this->createForm(RegisterType::class, $comments);

        $form->handleRequest($request);

        $videos = $this->manager->getRepository(Video::class)->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'videos' => $videos,
        ]);
    }
}
