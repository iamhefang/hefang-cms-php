<?php


namespace link\hefang\cms\core\helpers;


use link\hefang\mvc\Mvc;

class CacheHelper
{
	const KEY_ALL_PLUGINS = "all-plugins";
	const KEY_ALL_MENUS = "all-menus";
	const KEY_ALL_THEMES = "all-themes";
	const KEY_ALL_SETTINGS = "all-settings";

	/**
	 * 从缓存取数据，如果缓存中没有数据，调用回调函数读取数据并存入缓存
	 * @param string $name 缓存名
	 * @param callable $callback 无缓存时读取数据回调
	 * @param int $expireIn 缓存有效时间
	 * @param bool $useCache
	 * @return mixed 值
	 */
	public static function cacheOrFetch(string $name, callable $callback, int $expireIn = -1, bool $useCache = true)
	{
		$cache = Mvc::getCache()->get($name);
		if ($useCache && !Mvc::isDebug() && $cache) return $cache;
		$data = $callback();
		Mvc::getCache()->set($name, $data, $expireIn);
		return $data;
	}
}
