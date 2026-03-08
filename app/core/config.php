<?php

if (!defined('ROOT')) {
	$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
	$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
	define('ROOT', $scheme . '://' . $host . '/plateforme_GI_prom021/public');
}

define('APP_NAME', 'Plateforme GI Promo 21');

define('DB_NAME', 'plateforme_gi_promo21');
define('DBHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
