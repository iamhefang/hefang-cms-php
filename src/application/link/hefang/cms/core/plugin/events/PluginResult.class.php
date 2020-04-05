<?php


namespace link\hefang\cms\core\plugin\events;


use link\hefang\cms\core\plugin\entities\PluginEntry;

class PluginResult extends PluginEvent
{
	private $plugin;

	/**
	 * @return PluginEntry
	 */
	public function getPlugin(): PluginEntry
	{
		return $this->plugin;
	}

}
