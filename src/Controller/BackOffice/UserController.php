<?php

namespace App\Controller\BackOffice;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('/backoffice/usersList.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/details/{slug}", name="user_details")
     */
    public function show(userRepository $userRepository, Request $request): Response
    {
        $slug = $request->get('slug');
        $userDetails = $userRepository->getUser($slug);
        return $this->render(
            '/backoffice/userDetails.html.twig', [
            'userDetails' => $userDetails
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete")
     */
    public function delete(UserRepository $userRepository, Request $request, ManagerRegistry $doctrine): Response
    {
        $user_id = $request->get('id');
        $userDelete = $userRepository->find($user_id);
        $em = $doctrine->getManager();
        $em->remove($userDelete);
        $em->flush();
        return $this->redirectToRoute('admin_users_list');
    }

    /**
     * @Route("/create", name="user_create")
     */
}