<?php


namespace App\Controller;


use App\Data\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
     private EntityManagerInterface $entityManager;

     public function __construct(EntityManagerInterface $entityManager)
     {
         $this->entityManager = $entityManager;
     }

     #[Route('/products', name: 'products')]
     public function index(Request $request, PaginatorInterface $paginator): Response
     {
         $search = new Search();
         $form = $this->createForm(SearchType::class, $search);

         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid()) {
             $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
         } else {
             $pages = $this->entityManager->getRepository(Product::class)->findAll();
             $products = $paginator->paginate(
                 $pages,
                 $request->query->getInt('page', 1),
                 10
             );
         }

         return $this->render('product/index.html.twig', [
             'products' => $products,
             'form' => $form->createView()
         ]);
     }

    #[Route('/products/{slug}', name: 'product')]
     public function show($slug): Response
     {
         $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);
         $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

         //If you don't found product
         if (!$product) {
             return $this->redirectToRoute('products');
         }

         return $this->render('product/show.html.twig', [
             'product' => $product,
             'products' => $products
         ]);
     }
}
