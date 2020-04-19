<?php


namespace link\hefang\cms\core\plugin;


use link\hefang\cms\application\plugin\models\PluginModel;
use link\hefang\cms\core\plugin\events\PluginEvent;
use link\hefang\helpers\CollectionHelper;

class PluginManager
{
	private static $hooks = [];
	private $plugin;

	public function __construct(PluginModel $plugin)
	{
		$this->plugin = $plugin;
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
