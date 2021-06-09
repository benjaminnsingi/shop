<?php

namespace App\Controller;

use App\Data\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/address')]
class AccountAddressController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MessageService $messageService;

    public function __construct(EntityManagerInterface $entityManager, MessageService $messageService)
    {
        $this->entityManager = $entityManager;
        $this->messageService = $messageService;
    }

    #[Route('', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig');
    }

    #[Route('/create', name: 'account_address_create')]
    public function create(Cart $cart, Request $request): Response
    {
        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            $this->messageService->addSuccess("Votre addresse a bien été ajoutée");
            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_address');
            }
        }

        return $this->render('account/address_form.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{id}', name: 'account_address_edit')]
    public function edit(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if (!$address || $address->getUser() != $this->getUser()) {
            return $this->redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->messageService->addSuccess("Votre addresse a bien été modifiée");
            return $this->redirectToRoute('account_address');
        }

        return $this->render('account/address_form.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'account_address_delete')]
    public function delete($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ($address || $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
            $this->messageService->addSuccess("Votre addresse a bien été supprimée");
        }

        return $this->redirectToRoute('account_address');
    }
}
