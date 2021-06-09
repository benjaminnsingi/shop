<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use App\Security\EmailVerifier;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private EntityManagerInterface $entityManager;
    private MessageService $messageService;

    public function __construct(EmailVerifier $emailVerifier, EntityManagerInterface $entityManager, MessageService $messageService)
    {
        $this->emailVerifier = $emailVerifier;
        $this->entityManager = $entityManager;
        $this->messageService = $messageService;
    }

    #[Route('', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/password-edit', name: 'account_password')]
    public function editPassword(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_password = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user, $old_password)) {

                $new_password = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($user, $new_password);

                $user->setPassword($password);

                $this->entityManager->flush();
                $this->messageService->addSuccess("Votre mot de passe a été mis à jour.");
            } else {
                $this->messageService->addError("Votre mot de passe actuel est incorrect.");
            }

            //return $this->redirectToRoute('app_account');
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
