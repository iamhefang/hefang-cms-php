<?php
//require "./libraries/php-helpers-1.0.2.phar";
//require "./libraries/php-mvc-1.1.1.phar";

require "C:\Users\hefang\DevDir\php-frameworks\php-helpers\src\php-helpers.php";
require "C:\Users\hefang\DevDir\php-frameworks\php-mvc\src\php-mvc.php";

class_exists("\link\hefang\helpers\ClassHelper") or die("未找到库php-helpers");
class_exists("\link\hefang\mvc\Mvc") or die("未找到库php-mvc");
define("HEFANG_CMS", "1.0.0");
define("HEFANG_CMS_ROOT", __DIR__);

startMvcApplication();
