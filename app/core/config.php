<?php

if ( !defined( 'ROOT' ) ) {
    $scheme = ( !empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] !== 'off' ) ? 'https' : 'http';
    $host = $_SERVER[ 'HTTP_HOST' ] ?? 'localhost';
    define( 'ROOT', $scheme . '://' . $host . '/plateforme_GI_prom021/public' );
}
if ( !defined( 'ROOT_IMG' ) ) {
    $scheme = ( !empty( $_SERVER[ 'HTTPS' ] ) && $_SERVER[ 'HTTPS' ] !== 'off' ) ? 'https' : 'http';
    $host = $_SERVER[ 'HTTP_HOST' ] ?? 'localhost';
    define( 'ROOT_IMG', $scheme . '://' . $host . '/plateforme_GI_prom021' );
}

define( 'APP_NAME', 'Plateforme GI Promo 21' );

define( 'DB_NAME', 'plateforme_gi_promo21' );
define( 'DBHOST', 'localhost' );
define( 'DB_USERNAME', 'root' );
define( 'DB_PASSWORD', '' );

define('HF_API_TOKEN', getenv('HF_API_TOKEN') ?: '');
define('HF_MODEL', getenv('HF_MODEL') ?: 'Qwen/Qwen2.5-7B-Instruct-1M');

