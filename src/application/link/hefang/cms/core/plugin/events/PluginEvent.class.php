<?php


namespace link\hefang\cms\core\plugin\events;


use link\hefang\cms\core\plugin\entities\PluginEntry;

class PluginEvent
{
	private $data = null;
	private $event = "";
	private $pluginPath = [];

	public function __construct(string $event, $data = null)
	{
		$this->data = $data;
		$this->event = $event;
	}

	/**
	 * @return string
	 */
	public function getEvent(): string
	{
		return $this->event;
	}

	/**
	 * @return null|mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param null|mixed $data
	 */
	public function setData($data): void
	{
		$this->data = $data;
	}

	/**
	 * @return PluginEntry[]
	 */
	public function getPluginPath(): array
	{
		return array_merge([], $this->pluginPath);
	}

	public function addPluginPath(PluginEntry $entry): PluginEvent
	{
		$this->pluginPath[] = $entry;
		return $this;
	}
}
