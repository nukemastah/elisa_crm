<?php

// Vercel serverless function entry point for Laravel
// This file acts as the bridge between Vercel's serverless environment and Laravel

// Set the correct document root for Laravel
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../public';

// Ensure Laravel can find its public path
define('LARAVEL_PUBLIC_PATH', __DIR__ . '/../public');

// Forward to Laravel's public index.php
require __DIR__ . '/../public/index.php';
