<?php

namespace App\Controller\Admin;

use App\Entity\Carrier;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    private $order;
   
    public function __construct(OrderRepository $repo)
    {
        $this->order = $repo;
    }


    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
       // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
         return $this->render('admin/dashBoard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Myboutique');
    }

    public function configureMenuItems(): iterable
    {


$orderValid = count($this->order->findByStatut(1));
$orderNoValid = count($this->order->findByStatut(0));

        yield MenuItem::linkToDashboard('Dashboard', 'fas fa-home');
        yield MenuItem::section('Utilisateurs');
        yield MenuItem::linkToCrud('Administrateurs', 'fas fa-users', User::class)->setController(AdminUserCrudController::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::section('Commandes');
        yield MenuItem::linkToCrud('Commandes <span class="badge badge-success">'.$orderValid.'</span> <span class="badge badge-danger">'.$orderNoValid.'</span>', 'fas fa-shopping-cart', Order::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-folder', Category::class);
        yield MenuItem::linkToCrud('Produits', 'fas fa-tags', Product::class);
        yield MenuItem::linkToCrud('Transporteurs', 'fas fa-truck', Carrier::class);
    }
}
