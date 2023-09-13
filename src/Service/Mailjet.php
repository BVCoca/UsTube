<?php

namespace App\Service;

use Mailjet\Client;
use App\Entity\User;
use \Mailjet\Resources;


class Mailjet
{
    private $mailjetKey;
    private $mailjetKeySecret;

    public function __construct(string $mailjetKey, string $mailjetKeySecret)
    {
        $this->mailjetKey = $mailjetKey;
        $this->mailjetKeySecret = $mailjetKeySecret;
    }

    public function generateSingleBody(User $user, string $message, string $subject): array
    {
        return [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "vaique.benjamin@gmail.com",
                        'Name' => "Inscription"
                    ],
                    'To' => [
                        [
                            'Email' => $user->getEmail(),
                            'Name' => $user->getPseudo()
                        ]
                    ],
                    'TemplateID' => 5035426,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'subject' => $subject,
                        'content' => $message,
                        'firstname' => $user->getPseudo(),
                    ]
                ]
            ]
        ];
    }

    public function send(array $body): void
    {
        $mj = new Client($this->mailjetKey, $this->mailjetKeySecret, true, ['version' => 'v3.1']);
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    function sendEmail(User $user, string $myMessage, string $subject): void
    {
        $this->send($this->generateSingleBody($user, $myMessage, $subject));
    }
}
