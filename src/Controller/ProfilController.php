<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Video;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/profil/{id}", name="app_profil")
     */
    public function index($id): Response
    {
        // Récupérez l'utilisateur actuellement connecté
        $currentUser = $this->getUser();

        // Récupérez l'utilisateur du profil visité en fonction du $username fourni dans l'URL
        $profileUser = $this->manager->getRepository(User::class)->findOneBy(['id' => $id]);

        // Vérifiez si l'utilisateur actuellement connecté est le même que l'utilisateur du profil visité
        $isCurrentUserProfile = ($currentUser === $profileUser);

        // Utilisez cette information pour personnaliser le titre de la page
        $pageTitle = $isCurrentUserProfile ? 'Votre Profil | UsTube' : $profileUser->getPseudo() . ' | UsTube';

        return $this->render('profil/index.html.twig', [
            'profileUser' => $profileUser,
            'pageTitle' => $pageTitle,
        ]);
    }

    /**
     * @Route("/profil/remove/video/{id}", name="app_remove_video_profil")
     */
    public function removeVideo($id)
    {
        try {
            $user = $this->getUser();
            $video = $this->manager->getRepository(Video::class)->find($id);

            if ($video->getUser() == $user) {

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

                return $this->redirectToRoute('app_profil');
            } else {
                return $this->redirectToRoute('app_home');
            }
        } catch (\Throwable $th) {
            return $this->redirectToRoute('app_home');
        }
    }

    /**
     * @Route("/profil/edit/{id}", name="app_edit_profil")
     */
    public function editProfil($id, Request $request): Response
    {
        $user = $this->getUser();
        $profileUser = $this->manager->getRepository(User::class)->findOneBy(['id' => $id]);

        if ($user->getId() == $profileUser->getId()) {
            $form = $this->createForm(RegisterType::class, $profileUser);
            $form->handleRequest($request);

            if ($form->isSubmitted()  && $form->isValid()) {

                $oldAvatarPath = $profileUser->getAvatar();
                $currentAvatar = $form->get('avatar')->getData();
    
                if ($currentAvatar) {
                    $newAvatar = $this->fileUploader->upload($currentAvatar, $this->getParameter('pictures_video_directory'));
                    $video->setAvatar($newAvatar);
                    if (file_exists($this->getParameter('pictures_video_directory') . '/' . $oldAvatarPath)) {
                        unlink($this->getParameter('pictures_video_directory') . '/' . $oldAvatarPath);
                    }
                } else {
                    $video->setImage($oldImagePath);
                }
    
    
                $this->manager->persist($video);
                $this->manager->flush();
                return $this->redirectToRoute('app_panel_videos');
        } else {
            return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
        }


        return $this->render('admin/edit_video.html.twig', [
            'form' => $form->createView(),
            /* 'video' => $video */
        ]);
    }
}