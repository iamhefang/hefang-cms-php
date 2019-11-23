<?php

defined('PHP_MVC') or die('Access Refused');

/**
 * 默认配置项, 如果项目配置项里面缺少值会使用该值
 */
return [
	'debug.enable' => true,
	'pathinfo.type' => 'PATH_INFO',
	'project.package' => "link.hefang.cms",
	'project.application.class' => 'link.hefang.cms.HeFangCMS',
	//项目搜索字段名
	'project.search.field.name' => 'search',
	'database.enable' => true,
	'database.class' => 'link.hefang.mvc.databases.Mysql',
	'database.host' => 'localhost',
	'database.port' => null,
	'database.username' => 'hefang',
	'database.password' => '11111111',
	'database.charset' => 'utf8mb4',
	'database.database' => 'hefang-cms',
	'password.salt' => null,// 密码加密时使用的盐, 该值不能使用方法生成, 必须写死,
	'default.module' => 'main',
	'default.controller' => 'home',
	'default.action' => 'index',
	'default.page.size' => 20,
	'default.charset' => 'utf-8',
	'default.theme' => 'default',
	'default.locale' => 'zh_CN',
	'prefix.url.main' => '/index.php',
	'prefix.url.file' => '/files'
];
