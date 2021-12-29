<?php

namespace App\Controller\BackOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/messages", name="admin_")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/list", name="messages" method={"GET"})
     */
}