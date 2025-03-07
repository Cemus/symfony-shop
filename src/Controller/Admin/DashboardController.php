<?php

namespace App\Controller\Admin;

use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(private readonly AdminUrlGenerator $adminUrlGenerator)
    {
    }
    #[Route(path: "/admin/category", name: "app_admin_category")]
    public function category(): Response
    {
        $url = $this->adminUrlGenerator->setController(CategoryCrudController::class)->generateUrl();
        return $this->redirect($url);
    }
    #[Route(path: "/admin/account", name: "app_admin_account")]
    public function account(): Response
    {
        $url = $this->adminUrlGenerator->setController(AccountCrudController::class)->generateUrl();
        return $this->redirect($url);
    }
    #[Route(path: "/admin/article", name: "app_admin_article")]
    public function article(): Response
    {
        $url = $this->adminUrlGenerator->setController(AccountCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Revival');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Accounts', 'fa fa-user', Account::class),

            MenuItem::section('Articles'),
            MenuItem::linkToCrud('Articles', 'fa fa-file-lines', Article::class),
            MenuItem::linkToCrud('Categories', 'fa fa-table', Category::class),

        ];
    }
}
