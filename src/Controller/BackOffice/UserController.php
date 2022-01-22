<?php

namespace App\Controller\BackOffice;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/users", name="admin_")
 *
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="users_list")
     */
    public function usersList(UserRepository $userRepository)
    {
        $users = $userRepository->getUsers();
        return $this->render('/backoffice/usersList.html.twig', ['users' => $users]);
    }
}