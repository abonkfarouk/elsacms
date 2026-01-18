<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;

// Load .env manually
$env = [];
if (file_exists('.env')) {
    $lines = file('.env');
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line && strpos($line, '#') !== 0 && strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $env[trim($key)] = trim($val);
        }
    }
}

function env($key, $default = null) {
    global $env;
    return $env[$key] ?? $default;
}

echo "--- SMTP Diagnostic Tool ---\n";
echo "Reading .env...\n";

$clientId = env('GOOGLE_CLIENT_ID');
$clientSecret = env('GOOGLE_CLIENT_SECRET');
$refreshToken = env('GOOGLE_REFRESH_TOKEN');
$email = env('GOOGLE_EMAIL');

echo "Email: $email\n";
echo "Client ID: " . substr($clientId, 0, 10) . "...\n";
echo "Refresh Token: " . substr($refreshToken, 0, 10) . "...\n";

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = SMTP::DEBUG_CONNECTION; // Full Log
    $mail->isSMTP();
    
    if ($clientId && $clientSecret && $refreshToken) {
        echo "Mode: XOAUTH2\n";
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';
        
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
                'userName'     => $email,
            ])
        );
        $mail->setFrom($email, 'Test Script');
    } else {
        echo "Mode: Standard SMTP\n";
        $mail->Host       = env('SMTP_HOST');
        $mail->SMTPAuth   = true;
        $mail->Username   = env('SMTP_USER');
        $mail->Password   = env('SMTP_PASS');
        $mail->SMTPSecure = env('SMTP_SECURE');
        $mail->Port       = env('SMTP_PORT');
        $mail->setFrom(env('SMTP_USER'), 'Test Script');
    }

    $mail->addAddress($email); // Send to self
    $mail->Subject = 'SMTP Test';
    $mail->Body    = 'This is a test email';

    echo "Attempting to send...\n\n";
    $mail->send();
    echo "\n✅ Message has been sent\n";
} catch (Exception $e) {
    echo "\n❌ Message could not be sent. Mailer Error: {$mail->ErrorInfo}\n";
}
