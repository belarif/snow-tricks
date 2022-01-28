<?php

namespace App\Controller\BackOffice;

use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/messages", name="admin_")
 *
 */
class MessageController extends AbstractController
{
    private $messageRepository;
    private $managerRegistry;

    public function __construct(
        MessageRepository $messageRepository,
        ManagerRegistry   $managerRegistry
    )
    {
        $this->messageRepository = $messageRepository;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/list", name="messages_list", methods={"GET"})
     */
    public function messagesList(): Response
    {
        $messages = $this->messageRepository->getMessages();
        return $this->render('backoffice/messagesList.html.twig', ['messages' => $messages]);
    }

    /**
     * @Route("/details/{id}", name="message_details", methods={"GET"})
     */
    public function show(Request $request): Response
    {
        $message_id = $request->get('id');
        $messageDetails = $this->messageRepository->getMessage($message_id);
        return $this->render('/backoffice/messageDetails.html.twig', ['messageDetails' => $messageDetails]);
    }

    /**
     * @Route("/delete/{id}", name="message_delete")
     */
    public function delete(Request $request): RedirectResponse
    {
        $message_id = $request->get('id');
        $messageDelete = $this->messageRepository->find($message_id);
        $em = $this->managerRegistry->getManager();
        $em->remove($messageDelete);
        $em->flush();
        return $this->redirectToRoute('admin_messages_list');
    }
}




