<?php

namespace App\Controller\Admin;

use App\Entity\Adress;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\State;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $routerBuilder = $this->container->get(AdminUrlGenerator::class);
        $url = $routerBuilder->setController(UserCrudController::class)->generateUrl();
        return $this->redirect($url);


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
        // return $this->render('some/path/my-dashboard.html.twig');

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Dashboard Sorties');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('City', 'fas fa-city', City::class);
        yield MenuItem::linkToCrud('State', 'fa fa-info-circle', State::class);
        yield MenuItem::linkToCrud('Location', 'fa fa-location-arrow', Location::class);
        yield MenuItem::linkToCrud('Adress', 'fa fa-address-card', Adress::class);
        //yield MenuItem::linkToCrud('User', 'fa fa-user', User::class);

    }
}
