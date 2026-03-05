<?php

declare(strict_types=1);

date_default_timezone_set('Africa/Abidjan');
session_start();

require_once __DIR__ . '/app/core/autoload.php';

new App();
