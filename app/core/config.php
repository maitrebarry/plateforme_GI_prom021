<?php

if (!defined('ROOT')) {
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
	define('ROOT', $scheme . '://' . $host . '/plateforme_GI_prom021/public');
}

define('APP_NAME', 'Plateforme GI Promo 21');
