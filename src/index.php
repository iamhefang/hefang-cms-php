<?php
//require "./libraries/php-helpers-latest.phar";
//require "./libraries/php-mvc-latest.phar";

require "C:\Users\hefang\DevDir\php-frameworks\php-helpers\src\php-helpers.php";
require "C:\Users\hefang\DevDir\php-frameworks\php-mvc\src\php-mvc.php";

define("HEFANG_CMS", "2.0.0");
define("HEFANG_CMS_ROOT", __DIR__);
define("HEFANG_CMS_PLUGINS", HEFANG_CMS_ROOT . DS . "plugins");
define("HEFANG_CMS_EVENT_INIT", "init");
define("HEFANG_CMS_EVENT_REQUEST", "request");
define("HEFANG_CMS_EVENT_EXCEPTION", "exception");

startMvcApplication();
