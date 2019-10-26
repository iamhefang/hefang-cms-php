<?php
require "./libraries/php-helpers-1.0.0.phar";
require "./libraries/php-mvc-1.0.3.phar";

class_exists("\link\hefang\helpers\ClassHelper") or die("未找到库php-helpers");
class_exists("\link\hefang\mvc\Mvc") or die("未找到库php-mvc");
define("HEFANG_CMS", "1.0.0");
define("HEFANG_CMS_ROOT", __DIR__);

startMvcApplication();
