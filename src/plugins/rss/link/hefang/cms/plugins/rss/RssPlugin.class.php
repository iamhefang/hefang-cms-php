<?php


namespace link\hefang\cms\plugins\rss;


use link\hefang\cms\core\plugin\events\PluginEvent;
use link\hefang\cms\core\plugin\PluginManager;
use link\hefang\helpers\StringHelper;
use link\hefang\mvc\entities\Router;

class RssPlugin
{
	const PREFIX_RSS = "/rss/";

	public function __construct(PluginManager $manager)
	{
		$manager->registryHook(HEFANG_CMS_EVENT_REQUEST, [$this, "onRequest"]);
	}

	public function onRequest(PluginEvent $event): bool
	{
		$path = $event->getData();
		if (StringHelper::startsWith($path, true, self::PREFIX_RSS)) {
			$event->setData(new Router(null, "rss", "server"));
			return false;
		}
		return true;
	}
}
