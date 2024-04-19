<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(RequestStack $requestStack): Response
    {


      //  $panier = $requestStack->getSession()->get('cart',[]);
/*
        $panier[12] = 2;

        $panier[56] = 1;

        $requestStack->getSession()->set('cart',$panier);

        $requestStack->getSession()->get('cart',[]);

        dd($requestStack->getSession()->get('cart',[]));
*/
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
