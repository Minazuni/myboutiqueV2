<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Services\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'cart')]
    public function index(Cart $cart,ProductRepository $repo): Response
    {

        $cart = $cart->get();
        $cartComplete=[];
        foreach ($cart as $id => $quantity) {
            $cartComplete[]=[
                'product'=> $repo->findOneById($id),
                'quantity'=>$quantity
            ];
        }

       // dd($cartComplete);


        return $this->render('cart/cart.html.twig', [
            'cart'=>$cartComplete
        ]);
    }



    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart,$id)
    {
        $cart ->add($id);

        return $this->redirectToRoute('cart');

    }

// diminution de la quantité d'un produit
    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart,$id)
    {

      $cart->decrease($id);  
      return $this->redirectToRoute('cart');
    }


    #[Route('/cart/delete/{id}', name: 'delete_product')]
    public function delete(Cart $cart,$id)
    {

        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }



    #[Route('/cart/remove', name: 'remove_cart')]
    public function remove(Cart $cart)
    {
        $cart->remove();

        return $this->redirectToRoute('cart');
    }

}
