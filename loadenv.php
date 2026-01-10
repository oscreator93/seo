<?php
require 'vendor/autoload.php';
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    // .env file doesn't exist, use defaults
    // Environment variables can be set via $_ENV or system environment
}

