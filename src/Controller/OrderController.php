<?php

namespace App\Controller;

use DateTime;
use App\Entity\Order;
use App\Services\Cart;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use App\Services\StripeService;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/compte/commande', name: 'order')]
    public function index(
        Request $request,
        ProductRepository $repo,
        Cart $cart,
        EntityManagerInterface $manager,
        StripeService $stripeService
    ): Response {

        // Vérifie si l'utilisateur a une adresse
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute('account_add_address');
        }

        // Récupère le panier de l'utilisateur
        $cartContent = $cart->get();
        $cartComplete = [];

        // Récupère les détails des produits dans le panier
        foreach ($cartContent as $id => $quantity) {
            $cartComplete[] = [
                'product' => $repo->findOneById($id),
                'quantity' => $quantity
            ];
        }

        // Crée un formulaire de commande
        $form = $this->createForm(OrderType::class, null, ['user' => $this->getUser()]);
        $form->handleRequest($request);

        // Traite le formulaire lorsqu'il est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $reference = uniqid();

            // Crée une nouvelle commande
            $order = new Order();
            $order->setUser($this->getUser())
                ->setCarrier($form->get('transporteurs')->getData())
                ->setDelivery($form->get('addresses')->getData())
                ->setCreatedAt(new DateTime())
                ->setStatut(0)
                ->setReference($reference);

            $manager->persist($order);

            // Ajoute les détails de chaque produit à la commande
            foreach ($cartComplete as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order)
                    ->setProduct($product['product'])
                    ->setQuantity($product['quantity'])
                    ->setPrice($product['product']->getPrice());

                $manager->persist($orderDetails);
            }


            $stripeSession = $stripeService->getStripeSession($order, $cartComplete);
            $order->setStripeSessionId($stripeSession[1]);
            $manager->flush();
            // Récupère la session Stripe et affiche le récapitulatif de commande


            return $this->render('order/recap.html.twig', [
                'order' => $order,
                'cart' => $cartComplete,
                'url_stripe' => $stripeSession[0],
            ]);
        }

        // Affiche le formulaire de commande
        return $this->render('order/order.html.twig', [
            'form' => $form->createView(),
            'cart' => $cartComplete
        ]);
    }
}
