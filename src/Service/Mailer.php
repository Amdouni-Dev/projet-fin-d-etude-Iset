<?php
namespace App\Service;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;


class Mailer{
        /**
      * @var MailerInterface
      */
      private $mailer;
      public function __construct(MailerInterface $mailer){
      $this->mailer=$mailer;
  }

  public function senEmail($email,$nom){
      $email=(new TemplatedEmail())
          ->from('galactech@findme.com')
          ->to(new Address($email))
          ->subject('Confirmer votre inscription')
          ->text('sending emails is fun, isn \'t it !')
          ->htmlTemplate('registration/templateregistration.html.twig')
          ->context([
              'expiration_date'=>new \DateTime('+2 days'),
              'adresse'=>$email,
              'username'=>$nom
          ]);
      try {
          $this->mailer->send($email);
      } catch (TransportExceptionInterface $e) {
      }
      // do anything else you need here, like send an email

  }
}
