<?php

/**
 * Ping l'app pour la garder éveillée (contournement du spin-down Render free tier).
 *
 * ⚠️ Ne PAS exécuter ce script depuis un cron sur Render lui-même — s'il tourne
 * sur l'app qui s'endort, il s'endort avec elle. Ce script doit tourner depuis
 * un endroit externe toujours actif (ex: GitHub Actions, voir plus bas).
 *
 * Usage : php ping.php
 */

$url = getenv('APP_PING_URL') ?: 'https://ton-app.onrender.com/ping';

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 90, // le cold start peut prendre jusqu'à ~1 minute
    CURLOPT_FOLLOWLOCATION => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

$timestamp = date('Y-m-d H:i:s');

if ($error) {
    fwrite(STDERR, "[{$timestamp}] Erreur ping : {$error}\n");
    exit(1);
}

echo "[{$timestamp}] Ping envoyé à {$url} — code HTTP : {$httpCode}\n";

exit($httpCode >= 200 && $httpCode < 400 ? 0 : 1);