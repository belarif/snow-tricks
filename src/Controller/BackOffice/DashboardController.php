<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 *
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard", methods={"GET"})
     */
    public function index()
    {
        return $this->render('backoffice/dashboard.html.twig');
    }
}
