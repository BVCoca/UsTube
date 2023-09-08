<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Comments;
use App\Form\CommentType;
use App\Form\RegisterType;
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
    public function index(): Response
    {
        $videos = $this->manager->getRepository(Video::class)->findAll();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'videos' => $videos,
        ]);
    }

    /**
     * @Route("/search", name="app_search")
     */
    public function search(Request $request): Response
    {

        $title = $request->request->get('search');
        $videos = $this->manager->getRepository(Video::class)->searchByTitle($title);
        if ($videos == null) {
            $this->addFlash('secondary', 'Aucunes vidéos n\'a été trouvée.');
        }



        return $this->render('home/search.html.twig', [
            'videos' => $videos,
            'title' => $title
        ]);
    }
}
