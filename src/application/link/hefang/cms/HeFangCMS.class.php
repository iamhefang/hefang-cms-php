<?php


namespace link\hefang\cms;


use link\hefang\helpers\StringHelper;
use link\hefang\mvc\entities\Router;
use link\hefang\mvc\Mvc;
use link\hefang\mvc\SimpleApplication;

class HeFangCMS extends SimpleApplication
{
	const PREFIX_APIS = "/apis/";

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
