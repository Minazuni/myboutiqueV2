<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(RequestStack $requestStack, OrderRepository $repo, MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('kylian.omahony1@gmail.com')
            ->to('kylian.omahony1@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
        // dd($repo->FindOrder('admin@admin.fr'));


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
