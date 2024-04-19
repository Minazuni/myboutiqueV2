<?php

namespace App\Controller;

use App\Services\Cart;
use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAddressController extends AbstractController
{
    #[Route('/compte/adresses', name: 'account_address')]
    public function index(): Response
    {


        return $this->render('account/address.html.twig', [
            'controller_name' => 'AccountAddressController',
        ]);
    }


    #[Route('/compte/ajouter-une-adresse', name: 'account_add_address')]
    public function add(Cart $cart,Request $request, EntityManagerInterface $manager): Response
    {

        $address = new Address();

        $form = $this->createForm(AddressType::class, $address);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $address->setUser($this->getUser());
            $manager->persist($address);

            $manager->flush();

            $this->addFlash(
                'success',
                'L\'adresse de nom ' . $address->getName() . ' a bien été créé'
            );

            if ($cart->get())
            {
                return $this->redirectToRoute('order');
            }


            return $this->redirectToRoute('account_address');
        }


        return $this->render('account/add_address.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/compte/modifier-une-adresse/{id}', name: 'account_edit_address')]
    public function modifier(Request $request,EntityManagerInterface $manager, Address $address): Response
    {

        if ($address->getUser() != $this->getUser())  
           return $this->redirectToRoute('account_address');

           $form = $this->createForm(AddressType::class, $address);

           $form->handleRequest($request);
   
           if ($form->isSubmitted() && $form->isValid()) {
   
               $manager->persist($address);
   
               $manager->flush();
   
               $this->addFlash(
                   'success',
                   'L\'adresse de nom ' . $address->getName() . ' a bien été modifiée'
               );
   
               return $this->redirectToRoute('account_address');
           }



           return $this->render('account/add_address.html.twig', [
            'form' => $form->createView()
        ]);

    }


    #[Route('/compte/Supprimer-une-adresse/{id}', name: 'account_delete_address')]
    public function delete(EntityManagerInterface $manager, Address $address): Response
    {

        if ($address->getUser() == $this->getUser()) {

            $manager->remove($address);
            $manager->flush();


            $this->addFlash(
                'success',
                'L\'adresse de nom ' . $address->getName() . ' a bien été supprimée'
            );

            return $this->redirectToRoute('account_address');
        } else
        {
            $this->addFlash(
                'danger',
                'Vous essayez de supprimer une adresse qui ne vous appartient pas'
            );
            return $this->redirectToRoute('account_address');
        
        
        }
    }
}
