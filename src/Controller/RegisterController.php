<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    public $manager;
    public $passwordHash;
    public $fileUploader;

    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHash, FileUploader $fileUploader)
    {
        $this->manager = $manager;
        $this->passwordHash = $passwordHash;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function index(Request $request): Response
    {
        // Nouvelle instanciation d'User
        $user = new User();
        // Connexion du formulaire avec l'entité User
        $form = $this->createForm(RegisterType::class, $user);
        // On écoute les champs du formulaire et si il est envoyé et que les champs sont valides
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Définit la date de création de l'utilisateur.
                $user->setCreatedAt(new \DateTime());

                // On récupère le mot de passe en clair
                $passwordClear = $form->get('password')->getData();
                // On le hash
                $passwordIsHashed = $this->passwordHash->hashPassword($user, $passwordClear);
                // On le remet dans l'objet
                $user->setPassword($passwordIsHashed);

                // On récupère la donnée que l'utilisateur à envoyer dans le champ 'avatar'
                $avatar = $form->get('avatar')->getData();
                // Si la variable ci-dessus n'est pas null alors on appelle le service FileUploader afin de faire un traitement pour l'avatar puis de la stocker dans l'entité User
                if ($avatar) {
                    $newFilename = $this->fileUploader->upload($avatar, $this->getParameter('pictures_directory'));
                    $user->setAvatar($newFilename);
                }

                // Enfin on envoie les données de User à la base de données
                $this->manager->persist($user);
                $this->manager->flush();

                $this->addFlash('success', 'Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.');
                // Redirection à la page de connexion
                return $this->redirectToRoute('app_login');
            } catch (\Exception $e) {
                // Le formulaire non valide, envoie d'un message d'erreur
                $this->addFlash('danger', 'Le formulaire contient des erreurs. Veuillez vérifier les champs.');
            }
        }
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form->createView()
        ]);
    }
}
