<?php

$sProtocol = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off') ? 'http://' : 'https://';

define('LOGUN_CURRENT_URL',$sProtocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
define('LOGUN_PATH_ABSOLUTE', __DIR__.'/');
define('LOGUN_PATH_ASSETS', LOGUN_PATH_ABSOLUTE.'assets/');
define('LOGUN_PATH_TEMPLATES', LOGUN_PATH_ABSOLUTE.'templates/');
define('LOGUN_PATH_SOURCES', LOGUN_PATH_ABSOLUTE.'sources/');
define('LOGUN_PATH_TMP', LOGUN_PATH_ABSOLUTE.'tmp/');

define('LOGUN_PATH_INPUTS', LOGUN_PATH_SOURCES.'inputs/');
define('LOGUN_PATH_VALIDATORS', LOGUN_PATH_SOURCES.'validators/');
define('LOGUN_PATH_INTERFACES', LOGUN_PATH_SOURCES.'interfaces/');

define('LOGUN_INPUT_EXTENSION', '.php');
define('LOGUN_INTERFACE_EXTENSION', '.interface.php');
define('LOGUN_SOURCE_EXTENSION', '.php');
define('LOGUN_VALIDATOR_EXTENSION', '.php');

define('LOGUN_RENDER_LINE_END', "\r\n");

define('LOGUN_DEFAULT_METHOD', 'POST');

define('LOGUN_VERSION', '0.0.1');

define('LOGUN_SECURITY_SALT', md5(LOGUN_VERSION . LOGUN_PATH_ABSOLUTE . getlastmod()));

define('LOGUN_MAX_FILESIZE', 167772160);
define('LOGUN_ALLOWED_FILETYPES', '');
