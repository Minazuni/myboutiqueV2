<?php

namespace App\Controller;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    #[Route('/compte/mes-commandes', name: 'account_order')]
    public function index(OrderRepository $repo): Response
    { 
        //dd($repo->FindPaidOrder($this->getUser()));



        return $this->render('account/accountOrder.html.twig', [
            'orders'=>$repo->FindPaidOrder($this->getUser())
        ]);
    }


    #[Route('/compte/mes-commandes/{reference}', name: 'account_order_show')]
    public function show(Order $order): Response
    { 
      if ($order->getUser() != $this->getUser()) {
        return $this->redirectToRoute('account');
      }



        return $this->render('account/accountOrderShow.html.twig', [
            'order'=>$order
        ]);
    }
}
