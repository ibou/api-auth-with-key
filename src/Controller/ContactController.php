<?php

namespace App\Controller;

use App\Dto\ContactDTO;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $data = new ContactDTO();
        $data->name = 'John Doe';
        $data->email = 'test@example.com';
        $data->message = 'Hello World';
        $form = $this
            ->createForm(ContactType::class, $data)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $email = (new Email())
                ->from($form->get('email')->getData())
                ->to('local@example.com')
                ->bcc('bcc@example.com')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Mail de contact')
                ->text($form->get('message')->getData())
                ->html('<p>'.$form->get('message')->getData().'</p>');

            $mailer->send($email);
            $this->addFlash('success', 'Votre message a bien été envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render('contact/contact.html.twig', [
            'form' => $form,
        ]);
    }
}
