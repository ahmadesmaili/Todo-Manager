<?php

define('SITE_TITLE', '7Task Project');
$protocol=$_SERVER['PROTOCOL'] = isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http';
define('BASE_URL', "$protocol://{$_SERVER['HTTP_HOST']}/");
define('BASE_PATH', "{$_SERVER['DOCUMENT_ROOT']}/");