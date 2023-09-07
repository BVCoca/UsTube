<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Comments;
use App\Form\CommentType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(int $id, Request $request): Response
    {
        $comments = new Comments();
        $user = $this->getUser();

        $video = $this->manager->getRepository(Video::class)->find($id);

        $randomVideos = $this->manager->getRepository(Video::class)->findRandom();

        $form = $this->createForm(CommentType::class, $comments);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $comments->setCreatedAt(new \DateTime('now'));
                $comments->setUser($user);
                $comments->setVideo($video);

                $this->manager->persist($comments);
                $this->manager->flush();

                $comments = new Comments();
                $form = $this->createForm(CommentType::class, $comments);
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Erreur lors de l\'envoie du commentaire.');
                $e->getMessage();
            }
        }



        if (!$video) {
            throw $this->createNotFoundException(
                'Aucune vidéo à l\'id ' . $id
            );
        }
        return $this->render('single_video/index.html.twig', [
            'controller_name' => 'SingleVideoController',
            'video' => $video,
            'randomVideos' => $randomVideos,
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/remove/comment/{id}", name="app_remove_comment")
     */
    public function removeComment($id)
    {
        try {
            $user = $this->getUser();
            $comment = $this->manager->getRepository(Comments::class)->find($id);
            $videoId = $comment->getVideo()->getId();
            if ($comment->getUser() == $user) {
                $this->manager->remove($comment);
                $this->manager->flush();

                return $this->redirectToRoute('app_single_video', ['id' => $videoId]);
            } else {
                return $this->redirectToRoute('app_home');
            }
        } catch (\Throwable $th) {
            return $this->redirectToRoute('app_home');
        }
    }
}
