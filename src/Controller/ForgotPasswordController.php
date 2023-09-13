<?php

namespace App\Controller;

use App\Service\Mailjet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ForgotPasswordController extends AbstractController
{

    public $manager;
    public $mailjet;
    public $urlGenerator;
    public $tokenGenerator;

    public function __construct(EntityManagerInterface $manager, Mailjet $mailjet, UrlGeneratorInterface $urlGenerator, TokenGeneratorInterface $tokenGenerator)
    {
        $this->manager = $manager;
        $this->mailjet = $mailjet;
        $this->urlGenerator = $urlGenerator;
        $this->tokenGenerator = $tokenGenerator;
    }

    #[Route('/forgot/password', name: 'app_forgot_password')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];

            $userRepository = $this->manager->getRepository(User::class);
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $token = $this->tokenGenerator->generateToken();
                $user->setResetToken($token);
                $this->manager->flush();

                $resetLink = $this->urlGenerator->generate('app_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $resetSubject = "Réinitialisation mot de passe";
                $resetMessage = 'Voici le lien pour réinitialiser votre mot de passe: ' . $resetLink;
                $this->mailjet->sendEmail($user, $resetMessage, $resetSubject);
                $this->addFlash('success', 'Un lien vous a été envoyé par email');
            } else {
                $this->addFlash('danger', 'Cet email n\'existe pas');
            }
        }

        return $this->render('forgot_password/index.html.twig', [
            'controller_name' => 'ForgotPasswordController',
            'form' => $form->createView()
        ]);
    }
}
