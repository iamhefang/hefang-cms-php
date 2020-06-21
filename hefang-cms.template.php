<?php
require "./libraries/php-helpers-latest.phar";
require "./libraries/php-mvc-latest.phar";

use link\hefang\helpers\ClassHelper;

define("HEFANG_CMS", "!!VERSION!!");
define("HEFANG_CMS_ROOT", __DIR__);
defined("HEFANG_CMS_PLUGINS") or define("HEFANG_CMS_PLUGINS", $_SERVER["DOCUMENT_ROOT"] . DS . "plugins");
define("HEFANG_CMS_EVENT_INIT", "init");
define("HEFANG_CMS_EVENT_REQUEST", "request");
define("HEFANG_CMS_EVENT_EXCEPTION", "exception");

ClassHelper::loader(HEFANG_CMS_ROOT);

if (defined("APPLICATION_CONFIG_FILE")) {
	$settings = include(APPLICATION_CONFIG_FILE);
	startMvcApplication($settings);
} else {
	startMvcApplication();
}


