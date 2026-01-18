<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

class Mailer
{
    public function send($to, $subject, $htmlBody, $plainBody = '')
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();

            // Check for Google OAuth2
            $clientId     = env('GOOGLE_CLIENT_ID');
            $clientSecret = env('GOOGLE_CLIENT_SECRET');
            $refreshToken = env('GOOGLE_REFRESH_TOKEN');
            $googleEmail  = env('GOOGLE_EMAIL');

            if ($clientId && $clientSecret && $refreshToken) {
                log_message('info', 'Mailer: Attempting Google XOAUTH2');

                $mail->Host       = 'smtp.gmail.com';
                $mail->Port       = 465;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth   = true;
                $mail->AuthType   = 'XOAUTH2';

                $provider = new Google([
                    'clientId'     => $clientId,
                    'clientSecret' => $clientSecret,
                ]);

                $mail->setOAuth(
                    new OAuth([
                        'provider'     => $provider,
                        'clientId'     => $clientId,
                        'clientSecret' => $clientSecret,
                        'refreshToken' => $refreshToken,
                        'userName'     => $googleEmail,
                    ])
                );
                
                // From address must match authenticated user or alias
                $fromName = env('SMTP_FROM_NAME') ?: 'ElsaCMS';
                $mail->setFrom($googleEmail, $fromName);

            } else {
                log_message('info', 'Mailer: Attempting Standard SMTP');

                $mail->Host       = env('SMTP_HOST');
                $mail->Port       = env('SMTP_PORT') ?: 465;
                $mail->SMTPSecure = env('SMTP_SECURE') ?: PHPMailer::ENCRYPTION_SMTPS;
                $mail->SMTPAuth   = true;
                $mail->Username   = env('SMTP_USER');
                $mail->Password   = env('SMTP_PASS');
                
                $fromEmail = env('SMTP_FROM_EMAIL') ?: 'no-reply@elsacms.com';
                $fromName  = env('SMTP_FROM_NAME') ?: 'ElsaCMS';
                $mail->setFrom($fromEmail, $fromName);
            }

            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = $plainBody ?: strip_tags($htmlBody);

            $mail->send();
            return true;

        } catch (Exception $e) {
            log_message('error', "Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
