<?php

class_exists("\link\hefang\helpers\ClassHelper") or die("未找到库php-helpers");
class_exists("\link\hefang\mvc\Mvc") or die("未找到库php-mvc");
define("HEFANG_CMS", "VERSION");
define("HEFANG_CMS_ROOT", __DIR__);

\link\hefang\helpers\ClassHelper::loader(__DIR__);

\link\hefang\mvc\Mvc::init();
