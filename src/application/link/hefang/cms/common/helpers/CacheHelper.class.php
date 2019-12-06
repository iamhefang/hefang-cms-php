<?php


namespace link\hefang\cms\common\helpers;


use link\hefang\mvc\Mvc;

class CacheHelper
{
	/**
	 * 从缓存取数据，如果缓存中没有数据，调用回调函数读取数据并存入缓存
	 * @param string $name 缓存名
	 * @param callable $callback 无缓存时读取数据回调
	 * @param int $expireIn 缓存有效时间
	 * @return mixed 值
	 */
	public static function cacheOrFetch(string $name, callable $callback, int $expireIn = -1)
	{
		$cache = Mvc::getCache()->get($name);
		if ($cache) return $cache;
		$data = $callback();
		Mvc::getCache()->set($name, $data, $expireIn);
		return $data;
	}
}
