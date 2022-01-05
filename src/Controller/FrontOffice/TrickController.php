<?php

namespace App\Controller\FrontOffice;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tricks", name="trick_")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/details/{slug}", name="details")
     */
}