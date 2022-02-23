<?php

namespace App\Controller\BackOffice;

use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/messages", name="admin_")
 */
class MessageController extends AbstractController
{
    private $messageRepository;

    private $em;

    public function __construct(
        MessageRepository      $messageRepository,
        EntityManagerInterface $em
    )
    {
        $this->messageRepository = $messageRepository;
        $this->em = $em;
    }

    /**
     * @Route("/list", name="messages_list", methods={"GET"})
     *
     * @return Response
     */
    public function messagesList(): Response
    {
        return $this->render('backoffice/messagesList.html.twig', [
            'messages' => $this->messageRepository->getMessages()
        ]);
    }

    /**
     * @Route("/details/{id}", name="message_details", methods={"GET"}, requirements={"id"="\d+"})
     *
     * @param int $id
     * @return Response
     * @throws EntityNotFoundException
     */
    public function show(int $id): Response
    {
        return $this->render('/backoffice/messageDetails.html.twig', [
            'message' => $this->messageRepository->getMessage($id)
        ]);
    }

    /**
     * @Route("/delete/{id}", name="message_delete", requirements={"id"="\d+"})
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function delete(int $id): RedirectResponse
    {
        $this->em->remove($this->messageRepository->find($id));
        $this->em->flush();

        return $this->redirectToRoute('admin_messages_list');
    }
}






