<?php


namespace App\Controller;


use App\Data\Search;
use App\Entity\Product;
use App\Form\SearchType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     public function index(ProductRepository $repository, Request $request): Response
     {
         $search = new Search();
         $search->page = $request->get('page', 1);
         $form = $this->createForm(SearchType::class, $search);

         $form->handleRequest($request);
         $products = $repository->findWithSearch($search);

         if ($request->get('ajax')) {
             return new JsonResponse([
                 'content' => $this->renderView('product/_single_product.html.twig', ['products' => $products]),
                 'pagination' => $this->renderView('product/_pagination.html.twig', ['products' => $products]),
                 'pages' => ceil($products->getTotalItemCount() / $products->getItemNumberPerPage()),
             ]);
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
