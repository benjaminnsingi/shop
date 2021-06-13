<?php


namespace App\Data;


use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private string $api_key = 'YOUR_KEY';
    private string $api_key_secret = 'YOUR_KEY';

    public function send($to_email, $to_name, $subject, $content)
    {

        $mj = new Client($this->api_key, $this->api_key_secret,true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "",
                        'Name' => "La Boutique Benshop"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 2852955,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}