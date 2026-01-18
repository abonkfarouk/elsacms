<?php
require 'vendor/autoload.php';

use League\OAuth2\Client\Provider\Google;

echo "--- Generate Google OAuth2 Refresh Token ---\n";

// 1. Load Credentials
$clientId = '';
$clientSecret = '';

if (file_exists('.env')) {
    $lines = file('.env');
    foreach ($lines as $line) {
        $line = trim($line);
        if (strpos($line, 'GOOGLE_CLIENT_ID') === 0) {
            $parts = explode('=', $line, 2);
            $clientId = trim($parts[1]);
        }
        if (strpos($line, 'GOOGLE_CLIENT_SECRET') === 0) {
            $parts = explode('=', $line, 2);
            $clientSecret = trim($parts[1]);
        }
    }
}

if (empty($clientId) || $clientId == 'xxxxx') {
    echo "Client ID (dari .env kosong): ";
    $clientId = trim(fgets(STDIN));
} else {
    echo "Client ID: $clientId\n";
}

if (empty($clientSecret) || $clientSecret == 'xxxxx') {
    echo "Client Secret (dari .env kosong): ";
    $clientSecret = trim(fgets(STDIN));
} else {
    echo "Client Secret: [Hidden]\n";
}

// 2. Setup Provider
// NOTE: User MUST add this URL to 'Authorized redirect URIs' in Google Cloud Console
$redirectUri = 'https://developers.google.com/oauthplayground'; 

$provider = new Google([
    'clientId'     => $clientId,
    'clientSecret' => $clientSecret,
    'redirectUri'  => $redirectUri,
]);

// 3. Generate Auth URL
$authUrl = $provider->getAuthorizationUrl([
    'access_type' => 'offline', // Critical for Refresh Token
    'prompt'      => 'consent', // Force consent to ensure R.Token is returned
    'scope'       => ['https://mail.google.com/']
]);

echo "\n--------------------------------------------------\n";
echo "LANGKAH-LANGKAH:\n";
echo "1. Pastikan '$redirectUri' sudah dimasukkan ke 'Authorized redirect URIs' di Google Cloud Console Anda.\n";
echo "2. Buka URL berikut di Browser:\n\n";
echo $authUrl . "\n\n";
echo "3. Login akun Google -> Allow/Izinkan.\n";
echo "4. Anda akan diarahkan ke OAuth Playground.\n";
echo "5. Lihat di URL bar atau box, cari 'Authorization code'.\n";
echo "6. Paste Code tersebut di bawah ini.\n";
echo "--------------------------------------------------\n";
echo "Masukkan Authorization Code: ";

$code = trim(fgets(STDIN));

// 4. Exchange Code
try {
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $code
    ]);

    $refreshToken = $token->getRefreshToken();

    echo "\n\n✅ BERHASIL!\n";
    echo "Refresh Token Anda:\n";
    echo "--------------------------------------------------\n";
    echo $refreshToken . "\n";
    echo "--------------------------------------------------\n";
    echo "Silakan copy token ini ke file .env bagian GOOGLE_REFRESH_TOKEN\n";

} catch (Exception $e) {
    echo "\n❌ GAGAL: " . $e->getMessage() . "\n";
}
