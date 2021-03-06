<?php


namespace App\Controller;


use App\Data\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('', name: 'order')]
    public function index(Cart $cart): Response
    {
       if (!$this->getUser()->getAddresses()->getValues())
       {
           return $this->redirectToRoute('account_address_create');
       }

       $form = $this->createForm(OrderType::class, null, [
           'user' => $this->getUser()
       ]);

       return $this->render('order/index.html.twig',[
           'form' => $form->createView(),
           'cart' => $cart->getFull()
       ]);
    }

    #[Route('/summary', name: 'order_summary', methods: ["POST"])]
    public function create(Cart $cart, Request $request): Response
    {
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $date = new \DateTime();
           $carriers = $form->get('carriers')->getData();
           $delivery = $form->get('addresses')->getData();
           $delivery_content = $delivery->getFirstname(). '' .$delivery->getLastname();
           $delivery_content .= '<br/>'.$delivery->getPhone();

           if ($delivery->getCompany())
           {
               $delivery_content .= '<br/>'.$delivery->getCompany();
           }
           $delivery_content .= '<br/>'.$delivery->getCompany();
           $delivery_content .= '<br/>'.$delivery->getPostal(). ''.$delivery->getCity();
           $delivery_content .= '<br/>'.$delivery->getCountry();

           // Register my order Order()
           $order = new Order();

           $reference = $date->format('dmY').'-'.uniqid();
           $order->setReference($reference);
           $order->setUser($this->getUser());
           $order->setCreatedAt($date);
           $order->setCarrierName($carriers->getName());
           $order->setCarrierPrice($carriers->getPrice());
           $order->setDelivery($delivery_content);
           $order->setState(0);

           $this->entityManager->persist($order);

            // For each product I have in my cart
           foreach ($cart->getFull() as $product) {
               $orderDetails = new OrderDetails();
               $orderDetails->setMyorder($order);
               $orderDetails->setProduct($product['product']->getName());
               $orderDetails->setQuantity($product['quantity']);
               $orderDetails->setPrice($product['product']->getPrice());
               $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
               $this->entityManager->persist($orderDetails);
           }

           $this->entityManager->flush();

            return $this->render('order/add.html.twig', [
                'cart' =>$cart->getFull(),
                'carrier' => $carriers,
                'delivery' => $delivery_content,
                'reference' => $order->getReference()
            ]);
        }
        return $this->redirectToRoute('cart');
    }
}