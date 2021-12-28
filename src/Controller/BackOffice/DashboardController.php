<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dashboard", name="admin_")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard", methods={"GET"})
     */
    public function index()
    {
        return $this->render('backoffice/dashboard.html.twig');
    }
}
