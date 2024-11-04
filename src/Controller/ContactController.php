<?php

namespace App\Controller;

use App\Dto\ContactDTO;
use App\Event\ContactRequestEvent;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(
        Request $request,
        MailerInterface $mailer,
        EventDispatcherInterface $dispatcher
    ): Response
    {
        $data = new ContactDTO();
        $data->name = 'John Doe';
        $data->email = 'test@example.com';
        $data->message = 'Hello World';
        $form = $this
            ->createForm(ContactType::class, $data)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            try {
                $dispatcher->dispatch(new ContactRequestEvent($data));

                $this->addFlash('success', 'Votre message a bien été envoyé');

                return $this->redirectToRoute('contact');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue');
            }

        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
