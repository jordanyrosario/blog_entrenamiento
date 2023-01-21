<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class EMailService
{
    private string $message;
    private PHPMailer $mailer;

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();

        $this->mailer->Host = $_ENV['MAIL_HOST'];
        $this->mailer->Port = $_ENV['MAIL_PORT'];
        $this->mailer->setFrom($_ENV['MAIL_USERNAME']);

        $this->SMTPSecure = 'tls';

        if (isset($_ENV['MAIL_PASSWORD'])) {
            $this->SMTPAuth = true;
            $this->password = '5cC#[^l}xAK[P}pG';
        }
    }

    public function addMessage(string $message): self
    {
        $this->mailer->Body = $message;

        return $this;
    }

    public function fromAddres(string $address, string $name): self
    {
        $this->mailer->setFrom($address, $name);

        return $this;
    }

 public function replyTo(string $address, string $name = ''): self
 {
     $this->mailer->addReplyTo($address, $name);

     return $this;
 }

    public function addSubject(string $subject): self
    {
        $this->mailer->Subject = $subject;

        return $this;
    }

    public function addAddress(string|array $address): self
    {
        if (is_array($address)) {
            foreach ($address as $recipient) {
                $this->mailer->addAddress($recipient);
            }

            return $this;
        }

        $this->mailer->addAddress($address);

        return $this;
    }

public function sendEmail(): ?array
{
    try {
        $this->mailer->send();
    } catch (\Exception $th) {
        return ['error' => 'Ubable to send recovery email, contact administrator'];
    }

    return null;
}
}
