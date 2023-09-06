<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
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
}
