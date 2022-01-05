<?php

namespace App\Controller\BackOffice;

use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/messages", name="admin_")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/list", name="messages_list")
     */
    public function messagesList(MessageRepository $messageRepository)
    {
        $messages = $messageRepository->getMessages();
        return $this->render('backoffice/messagesList.html.twig', array('messages' => $messages));
    }

    /**
     * @Route("/details/{id}", name="message_details")
     */
    public function show(MessageRepository $messageRepository, Request $request): Response
    {
        $message_id = $request->get('id');
        $messageDetails = $messageRepository->getMessage($message_id);
        return $this->render('/backoffice/messageDetails.html.twig', array('messageDetails' => $messageDetails));
    }
}

