<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {

        $this->requestStack = $requestStack;
    }


    // ajout d'un id d'un produit 
    public function add($id)
    {
        $cart = $this->requestStack->getSession()->get('cart', []);

// on teste si le produit correspondant à l'id est vide
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else $cart[$id] = 1; // ajout d'une seule quantité

        $this->requestStack->getSession()->set('cart', $cart);
    }


   public function decrease($id){

    $cart = $this->requestStack->getSession()->get('cart', []);

    if ($cart[$id] > 1){
        $cart[$id]--;
    } else unset($cart[$id]);

    $this->requestStack->getSession()->set('cart', $cart);

   } 

    // suppression d'un produit
    public function delete($id){

        $cart = $this->requestStack->getSession()->get('cart', []);

      unset($cart[$id]);

      $this->requestStack->getSession()->set('cart', $cart);

    }


    // recuperation du panier
    public function get()
    {
        return $this->requestStack->getSession()->get('cart', []);
    }


    // suppression du panier
    public function remove(){

        return $this->requestStack->getSession()->remove('cart');

    }



}
