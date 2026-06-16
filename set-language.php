<?php
require_once 'config.php';

$lang = $_GET['lang'] ?? 'en';
$redirect = $_GET['redirect'] ?? 'index.php';

setLang($lang);
redirect($redirect);
