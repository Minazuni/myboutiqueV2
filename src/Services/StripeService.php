<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeService extends AbstractController
{


    public function getStripeSession($order, $cartComplete)
    {
        foreach ($cartComplete as $product) {
            $stripe_products[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [
                            $_SERVER['HTTP_ORIGIN'] . '/uploads/' . $product['product']->getPicture()
                        ]
                    ],
                    'unit_amount' => $product['product']->getPrice(),
                ],
                'quantity' => $product['quantity']
            ];
        }


        // ajout du transporteur
        $stripe_products[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $order->getCarrier()->getName(),

                ],
                'unit_amount' => $order->getCarrier()->getPrice() * 100,
            ],
            'quantity' => 1
        ];


        $YOUR_DOMAIN = $_SERVER['HTTP_ORIGIN'];

        $stripeSecretKey = $this->getParameter('STRIPE_KEY');
        Stripe::setApiKey($stripeSecretKey);

        $checkout_session = Session::create([
            'line_items' => $stripe_products,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/compte/commande/merci/{CHECKOUT_SESSION_ID}/',
            'cancel_url' => $YOUR_DOMAIN . '/compte/commande/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        
        return [
            $checkout_session->url, $checkout_session->id

        ];
    }
}
