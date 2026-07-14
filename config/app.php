<?php
date_default_timezone_set('America/Lima');

define('APP_NAME', 'Adopción de Animales');
define('SESSION_TIMEOUT', 1800); // 30 minutos

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
define('APP_BASE', $scriptDir === '/' ? '' : $scriptDir);