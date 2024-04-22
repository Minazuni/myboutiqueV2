<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function index(): Response
    {

       
        return $this->render('account/account.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
}
