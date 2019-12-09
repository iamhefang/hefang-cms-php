<?php


namespace link\hefang\cms;


use link\hefang\cms\admin\models\SettingModel;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\entities\Router;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\SimpleApplication;

class HeFangCMS extends SimpleApplication
{
	const PREFIX_APIS = "/apis/";

	public static function searchKey(): string
	{
		return Mvc::getProperty("project.search.field.name", "search");
	}

	public function onInit()
	{
		$settings = SettingModel::allValues();
		Mvc::getLogger()->debug(print_r($settings, true), "初始化时读取到配置项");
		return $settings;
	}

	public function onRequest(string $path)
	{
		Mvc::getLogger()->debug("Request: " . $path);
		if (StringHelper::startsWith($path, true, self::PREFIX_APIS)) {
			$path = substr($path, strlen(self::PREFIX_APIS) - 1);
			return Router::parsePath($path);
		}
		return parent::onRequest($path);
	}
}
