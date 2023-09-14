<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\Comments;
use App\Form\AddVideoType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    private $manager;
    private $fileUploader;

    public function __construct(EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $this->manager = $manager;
        $this->fileUploader = $fileUploader;
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

    /**
     * @Route("/admin/remove/video/{id}", name="app_remove_video")
     */
    public function removeVideo($id)
    {
        $video = $this->manager->getRepository(Video::class)->find($id);

        $oldVideoPath = $video->getPathVideo();
        $oldImagePath = $video->getImage();

        if (file_exists($this->getParameter('videos_directory') . '/' . $oldVideoPath)) {
            unlink($this->getParameter('videos_directory') . '/' . $oldVideoPath);
        }

        if (file_exists($this->getParameter('pictures_video_directory') . '/' . $oldImagePath)) {
            unlink($this->getParameter('pictures_video_directory') . '/' . $oldImagePath);
        }

        $this->manager->remove($video);
        $this->manager->flush();
        $this->addFlash('success', 'Vidéo supprimée avec succès');
        return $this->redirectToRoute('app_panel_videos');
    }

    /**
     * @Route("/admin/edit/video/{id}", name="app_edit_video")
     */
    public function editVideo($id, Request $request): Response
    {
        $video = $this->manager->getRepository(video::class)->find($id);

        $oldVideoPath = $video->getPathVideo();
        $oldImagePath = $video->getImage();

        $form = $this->createForm(AddVideoType::class, $video);
        $form->handleRequest($request);



        if ($form->isSubmitted()  && $form->isValid()) {

            $currentVideo = $form->get('path_video')->getData();
            $currentImage = $form->get('image')->getData();


            if ($currentVideo) {
                $video->setPathVideo($currentVideo);
                if (file_exists($this->getParameter('videos_directory') . '/' . $oldVideoPath)) {
                    unlink($this->getParameter('videos_directory') . '/' . $oldVideoPath);
                }
            } else {
                $video->setPathVideo($oldVideoPath);
            }

            if ($currentImage) {
                $newImage = $this->fileUploader->upload($currentImage, $this->getParameter('pictures_video_directory'));
                $video->setImage($newImage);
                if (file_exists($this->getParameter('pictures_video_directory') . '/' . $oldImagePath)) {
                    unlink($this->getParameter('pictures_video_directory') . '/' . $oldImagePath);
                }
            } else {
                $video->setImage($oldImagePath);
            }


            $this->manager->persist($video);
            $this->manager->flush();
            return $this->redirectToRoute('app_panel_videos');
        }

        return $this->render('admin/edit_video.html.twig', [
            'form' => $form->createView(),
            'video' => $video
        ]);
    }

    /**
     * @Route("/admin/panel_comments", name="app_panel_comments")
     */
    public function panelComments(): Response
    {
        $comments = $this->manager->getRepository(Comments::class)->findAll();

        return $this->render('admin/panelComments.html.twig', [
            'controller_name' => 'AdminController',
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/admin/remove/comment/{id}", name="app_remove_comment_admin")
     */
    public function removeComment($id): Response
    {
        $comment = $this->manager->getRepository(Comments::class)->find($id);



        $this->manager->remove($comment);
        $this->manager->flush();
        $this->addFlash('success', 'Commentaire supprimée avec succès');
        return $this->redirectToRoute('app_panel_comments');
    }
}
