<?php

namespace App\Controller;

use App\Entity\Order;
use App\Services\Cart;
use Stripe\StripeClient;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
  #[Route('/compte/commande/merci/{stripeSessionId}', name: 'order_success')]
  public function index(Order $order,$stripeSessionId,Cart $cart, EntityManagerInterface $manager): Response
  {

    // dd($CHECKOUT_SESSION_ID);
    $stripeSecretKey = $this->getParameter('STRIPE_KEY');
    $stripe = new StripeClient($stripeSecretKey);

    $session = $stripe->checkout->sessions->retrieve($stripeSessionId);
    // $customer = $stripe->customers->retrieve($session->customer);

// on Vide le panier 
$cart->remove();

//On met le statut de la commande a payé(1) 
$order->setStatut(1);
// on utilise le manager pour ecrire dans la base de donnée
$manager->flush();
    return $this->render('order_success/index.html.twig', [
      'total' => $session->amount_total,
      'order'=>$order
    ]);
  }
}
