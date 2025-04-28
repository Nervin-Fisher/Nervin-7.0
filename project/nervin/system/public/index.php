<?php
namespace app;

ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

use system\server\Start;

define("START_APP", "main");
define("ROOT_DIR", "../");
session_start();

spl_autoload_register(function ($class_name) {
	if (file_exists(ROOT_DIR . str_replace('\\', '/', $class_name)  . '.php')) {
		include ROOT_DIR . str_replace('\\', '/', $class_name)  . '.php';
	}
});
include ROOT_DIR . "vendor/autoload.php";
return (new Start)->start_product();