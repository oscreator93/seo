<?php
require 'vendor/autoload.php';
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    // .env file doesn't exist, use defaults
    // Environment variables can be set via $_ENV or system environment
}

// Use null coalescing so missing keys never trigger "Undefined array key" warnings on PHP 8+
$GOOGLE_RECAPTCHA_SITE_KEY = $_ENV['GOOGLE_RECAPTCHA_SITE_KEY'] ?? '';