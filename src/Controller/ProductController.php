<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SearchFilters;
use App\Form\SearchFiltersType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request, ProductRepository $repo): Response
    {

        $searchFilter = new SearchFilters();

        $form = $this->createForm(SearchFiltersType::class, $searchFilter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (count($searchFilter->getCategories()) > 0) {
                foreach ($searchFilter->getCategories() as $categorie) {

                    $tabId[] = $categorie->getId();
                }

                
                $products = $repo->findByCategory($tabId);
            } else 
            {
                $products = $repo->findAll();

            }

        } else {

            $products = $repo->findAll();
        }

        // $products = $repo->findAll();

        // dd($products);
        // produit d'id 57
        // $product = $entityManager->getRepository(Product::class)->find(57);

        // aller chercher tous les produits
        // $product = $entityManager->getRepository(Product::class)->findAll();

        // le produit dont le sous-titre est eos eos commodi
        //$product = $entityManager->getRepository(Product::class)->findOneBySubtitle('eos eos commodi');

        // les produits qui ont un sous-titre commun
        //$product = $entityManager->getRepository(Product::class)->findBySubtitle('eos eos commodi');

        //les produits de la categorie 101 par ordre croissant
        //$product = $entityManager->getRepository(Product::class)->findBy(['category'=>101],['price'=>'asc']);



        return $this->render('product/products.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }


    #[Route('/produit/{slug}', name: 'product')]
    public function produit(Product $product): Response
    {




        return $this->render('product/product.html.twig', [
            'product' => $product
        ]);
    }
}
