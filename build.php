<?php
#!/usr/bin/env php

$pkg = json_decode(file_get_contents("./composer.json"), true);
$name = explode("/", $pkg["name"])[1];
$version = $pkg["version"];

$pharFile = __DIR__ . "/build/$name-$version.phar";
$mainFile = __DIR__ . "/src/index.php";

$template = file_get_contents("./$name.template.php");

$template = str_replace("VERSION", $version, $template);

file_put_contents($mainFile, $template);


