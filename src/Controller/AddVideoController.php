<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\AddVideoType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddVideoController extends AbstractController
{
    private $fileUploader;
    private $entityManager;

    public function __construct(FileUploader $fileUploader, EntityManagerInterface $entityManager)
    {
        $this->fileUploader = $fileUploader;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/add/video", name="app_add_video")
     */
    public function index(Request $request): Response
    {
        $video = new Video();
        $form = $this->createForm(AddVideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {

                $video->setCreatedAt(new \DateTime());

                $user = $this->getUser();
                $video->setUser($user);

                $image = $form->get('image')->getData();
                $videoSrc = $form->get('path_video')->getData();

                if ($image) {
                    $newFilename = $this->fileUploader->upload($image, $this->getParameter('pictures_video_directory'));
                    $video->setImage($newFilename);
                }

                if ($videoSrc) {
                    $newFilename = $this->fileUploader->upload($videoSrc, $this->getParameter('videos_directory'));
                    $video->setpathVideo($newFilename);
                }


                $this->entityManager->persist($video);
                $this->entityManager->flush();
                $form = $this->createForm(AddVideoType::class, new Video());
                $this->addFlash('success', 'La vidéo a bien été ajouté !');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'ajout de la vidéo');
            }
        }
        return $this->render('add_video/index.html.twig', [
            'controller_name' => 'AddVideoController',
            'addVideoForm' => $form->createView(),
        ]);
    }
}
