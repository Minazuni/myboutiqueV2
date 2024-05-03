<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
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

            if (count($searchFilter->getCategories()) > 0 || $searchFilter->getString()) {

                /* foreach ($searchFilter->getCategories() as $categorie) {

                    $tabId[] = $categorie->getId();
                }*/

                $products = $repo->FindSearch($searchFilter);
                //$products = $repo->findByCategory($tabId);
            } else {
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

    #[Route('/compte/mes-commandes/{slug}/comment', name: 'comment_product')]
    public function comment(Product $product, EntityManagerInterface $manager, Request $request,): Response
    {


        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setCreatedAt(new \DateTime());
            $comment->setUser($this->getUser());
            $comment->setProduct($product);
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash(
                'success',
                'Le commentaire pour le produit ' . $product->getName() . ' a bien été enregistrée !'
            );
            return $this->redirectToRoute('product', ['slug' => $product->getSlug()]);
        }


        return $this->render('product/comment.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }
}
