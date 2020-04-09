<?php


namespace link\hefang\cms\core\plugin;


use link\hefang\cms\core\helpers\CacheHelper;
use link\hefang\cms\core\plugin\entities\PluginEntry;
use link\hefang\cms\core\plugin\events\PluginEvent;
use link\hefang\helpers\CollectionHelper;

class PluginManager
{
	private static $hooks = [];
	private static $plugins = [];
	private $plugin;

	public function __construct(PluginEntry $plugin)
	{
		$this->plugin = $plugin;
	}

	/**
	 * @param bool $useCache
	 * @return PluginEntry[]
	 */
	public static function listPlugins(bool $useCache = true): array
	{
		if (empty(self::$plugins)) {
			self::$plugins = CacheHelper::cacheOrFetch(__FUNCTION__ . "plugins", function () {
				$pluginDirs = scandir(HEFANG_CMS_PLUGINS);
				$pluginArray = [];
				foreach ($pluginDirs as $pluginDir) {
					$pluginPath = HEFANG_CMS_PLUGINS . DS . $pluginDir;
					$file = $pluginPath . DS . "manifest.json";
					if ($pluginDir === "." || $pluginDir === ".." || !is_file($file)) continue;
					$plugin = json_decode(file_get_contents($file), true);
					$plugin["enable"] = file_exists($pluginPath . DS . "plugin-enabled");
					unset($plugin["\$schema"]);
					$plugin["pluginDir"] = $pluginPath;
					$pluginArray[] = new PluginEntry($plugin);
				}
				return $pluginArray;
			}, -1, $useCache);
		}
		return self::$plugins;
	}

	public static function executeHooks(string $eventName, $data): PluginEvent
	{
		$hooks = array_values(CollectionHelper::getOrDefault(self::$hooks, $eventName, []));
		$event = new PluginEvent($eventName, $data);

		foreach ($hooks as $hook) {
			foreach ($hook as $callback) {
				$propagation = call_user_func($callback["callback"], $event->addPluginPath($callback["plugin"]));
				if ($propagation === false)
					return $event;
			}
		}
		return $event;
	}

	/**
	 * @param string $name hook名称
	 * @param callable $callback 回调函数
	 * @param int $level
	 */
	public function registryHook(string $name, callable $callback, int $level = 10)
	{
		$thisHooks = CollectionHelper::getOrDefault(self::$hooks, $name, []);
		if (isset($thisHooks[$level])) {
			$thisHooks[$level][] = ["callback" => $callback, "plugin" => $this->plugin];
		} else {
			$thisHooks[$level] = [
				["callback" => $callback, "plugin" => $this->plugin]
			];
		}
		ksort($thisHooks);
		self::$hooks[$name] = $thisHooks;
	}

	public function unRegistryHook(string $name, callable $callback)
	{
		if (!isset(self::$hooks[$name])) return;

	}
}
