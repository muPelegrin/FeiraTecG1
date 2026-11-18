<?php
$cookieFile = __DIR__ . '/test_cookie.txt';
if (file_exists($cookieFile)) @unlink($cookieFile);

// 1. GET login page
$ch = curl_init('http://localhost/FeiraTecG1/auth/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
$loginHtml = curl_exec($ch);

if (!preg_match('/name="csrf_token"\s+value="([^"]+)"/', $loginHtml, $matches)) {
    die("Falha ao obter CSRF token\n");
}
$csrfToken = $matches[1];

// 2. POST login
curl_setopt($ch, CURLOPT_URL, 'http://localhost/FeiraTecG1/auth/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'csrf_token' => $csrfToken,
    'email'      => 'maria@escola.com',
    'senha'      => 'senha123'
]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$dashboardHtml = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Status HTTP pós-login: " . $httpCode . "\n";
echo "Encontrou VIVALCRIS: " . (strpos($dashboardHtml, 'VIVAL') !== false ? 'SIM' : 'NAO') . "\n";
echo "Encontrou Painel de Controle: " . (strpos($dashboardHtml, 'Painel de Controle') !== false ? 'SIM' : 'NAO') . "\n";
echo "Encontrou Maria Inspetora: " . (strpos($dashboardHtml, 'Maria Inspetora') !== false ? 'SIM' : 'NAO') . "\n";

@unlink($cookieFile);
