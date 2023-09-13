<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    public $manager;
    private $passwordHash;

    public function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHash)
    {
        $this->passwordHash = $passwordHash;
        $this->manager = $manager;
    }

    #[Route('/reset/password', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        $token = $request->query->get('token');
        $user = $this->manager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        if (!$user) {
            return $this->redirectToRoute('app_home');
            $this->addFlash('danger', 'Echec du reset mot de passe');
        } else {

            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $formData = $form->getData();
                $newPassword = $formData['password'];

                $hashedPassword = $this->passwordHash->hashPassword($user, $newPassword);

                $user->setPassword($hashedPassword);
                $user->setResetToken(null);
                $this->manager->flush();

                $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès !');
                return $this->redirectToRoute('app_login');
            }
        }


        return $this->render('reset_password/index.html.twig', [
            'controller_name' => 'ResetPasswordController',
            'form' => $form->createView()
        ]);
    }
}
