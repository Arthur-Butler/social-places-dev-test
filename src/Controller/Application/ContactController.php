<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Contact;

class ContactController extends AbstractController
{
    #[Route('/contactForm', name: 'app_contact')]
    public function contactForm(): Response
    {
        return $this->redirect($this->generateUrl('contactForm'));
        return $this->render('base.html.twig');
    }

    #[Route('/addContact/{name}/{surname}/{phoneNo}/{email}/{store}', name: 'add_contact', methods:"POST", requirements: ['contact' => '\d+'])]
    public function addContact(ManagerRegistry $doctrine, MailerInterface $mailer, string $name, string $surname, string $phoneNo, string $email, int $store): Response
    {
        $entityManager = $doctrine->getManager();

        $contact=new Contact();
        $contact->setName($name);
        $contact->setSurname($surname);
        $contact->setPhoneNo($phoneNo);
        $contact->setEmail($email);
        $contact->setStore($store);

        $users = $doctrine->getRepository(User::class)->getAllUserEmails();

        foreach ($users as $key => $user) {
            $email = (new Email())
            ->from('arthurbutler1738@gmail.com')
            ->to($user)
            ->subject('CONTACT FORM')
            ->text('New user has filled out content form')
            ->html('<p>User: $name $surname has filled out contact form.</p>');
        }
        

        $mailer->send($email);

        $entityManager->persist($contact);
        return new Response('Saved new product with id '.$contact->getId());
    }
}
