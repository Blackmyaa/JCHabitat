<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerService
{
    private MailerInterface $mailer;
    private string $fromAddress;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->fromAddress = $params->get('app.mail_from') ?? 'no-reply@example.com';
    }

    /**
     * Envoie un email basé sur un template Twig.
     *
     * @param string $to Adresse du destinataire
     * @param string $subject Sujet de l’email
     * @param string $template Nom du template Twig (ex: emails/contact.html.twig)
     * @param array $context Variables passées au template
     * @return void
     */
    public function send(string $to, string $subject, string $template, array $context = []): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->fromAddress, 'Votre Entreprise'))
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("email/$template.html.twig")
            ->context($context);

        $this->mailer->send($email);
    }
}